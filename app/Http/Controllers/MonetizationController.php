<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\LinkClick;
use App\Models\UserEarning;
use App\Models\GlobalSetting;
use App\Services\RecaptchaService;
use App\Services\CurrencyService;
use App\Services\AdService;
use App\Services\FraudDetectionService;
use App\Services\CPMEarningsService;
use App\Services\RealTimeAnalyticsService;
use App\Events\ClickTracked;
use App\Events\EarningsTracked;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class MonetizationController extends Controller
{
        protected $recaptchaService;
    protected $currencyService;
    protected $adService;
    protected $fraudDetectionService;
    protected $cpmEarningsService;
    protected $realTimeAnalyticsService;

    public function __construct(
        RecaptchaService $recaptchaService,
        CurrencyService $currencyService,
        AdService $adService,
        FraudDetectionService $fraudDetectionService,
        CPMEarningsService $cpmEarningsService,
        RealTimeAnalyticsService $realTimeAnalyticsService
    ) {
        $this->recaptchaService = $recaptchaService;
        $this->currencyService = $currencyService;
        $this->adService = $adService;
        $this->fraudDetectionService = $fraudDetectionService;
        $this->cpmEarningsService = $cpmEarningsService;
        $this->realTimeAnalyticsService = $realTimeAnalyticsService;
    }

    /**
     * Show the intermediate monetization page
     */
    public function showIntermediate($shortCode)
    {
        try {
            $link = Link::where('short_code', $shortCode)->firstOrFail();

            // Check if link is active
            if (!$link->is_active) {
                abort(404, 'This link is no longer active.');
            }

            // Check if link has expired
            if ($link->expiry && now()->isAfter($link->expiry)) {
                abort(404, 'This link has expired.');
            }

            // Check if link can be clicked (daily limit)
            if (!$link->canBeClicked()) {
                return view('links.limit_reached', compact('link'));
            }

            // Fraud detection analysis
            $fraudAnalysis = $this->fraudDetectionService->analyzeRequest(request(), [
                'link_id' => $link->id,
                'short_code' => $shortCode,
            ]);

            // Log fraud attempts
            if (!$fraudAnalysis['is_safe']) {
                $this->fraudDetectionService->logFraudAttempt($fraudAnalysis, request(), [
                    'link_id' => $link->id,
                    'short_code' => $shortCode,
                ]);
            }

            // Block high-risk requests
            if ($fraudAnalysis['should_block']) {
                abort(403, 'Access denied due to suspicious activity.');
            }

            // Get ad content based on link type
            $adContent = $this->adService->getAdContent($link->ad_type, [
                'duration' => $link->getAdDuration(),
                'link_id' => $link->id,
            ]);

            // Record a preliminary click for analytics (earnings will be added on completion)
            $preliminaryClick = $this->recordPreliminaryClick($link, request());

            // Generate a unique session token for this visit
            $sessionToken = Hash::make($shortCode . request()->ip() . time());

            // Store session data in cache for 15 minutes (including the preliminary click ID)
            Cache::put("monetization_session_{$sessionToken}", [
                'link_id' => $link->id,
                'click_id' => $preliminaryClick->id,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'referer' => request()->header('referer'),
                'created_at' => now(),
                'recaptcha_verified' => false,
                'fraud_analysis' => $fraudAnalysis,
                'ad_content' => $adContent,
            ], 900); // 15 minutes

            return view('links.monetization', compact('link', 'sessionToken', 'adContent', 'fraudAnalysis'));

        } catch (\Exception $e) {
            Log::error('Monetization error', [
                'shortCode' => $shortCode,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            abort(500, 'An error occurred while processing your request.');
        }
    }

    /**
     * Complete monetization and redirect to target URL
     */
    public function completeMonetization(Request $request, $shortCode)
    {
        $link = Link::where('short_code', $shortCode)->firstOrFail();
        $sessionToken = $request->input('session_token');
        $recaptchaToken = $request->input('recaptcha_token');

        // Validate session token
        $sessionData = Cache::get("monetization_session_{$sessionToken}");
        if (!$sessionData) {
            abort(400, 'Invalid or expired session.');
        }

        // Verify this is the correct link
        if ($sessionData['link_id'] != $link->id) {
            abort(400, 'Session mismatch.');
        }

        // Check if link is still valid
        if (!$link->is_active || !$link->canBeClicked()) {
            abort(400, 'Link is no longer available.');
        }

        // Verify reCAPTCHA if configured
        if ($this->recaptchaService->isConfigured()) {
            if (!$recaptchaToken) {
                abort(400, 'reCAPTCHA verification required.');
            }

            $recaptchaResult = $this->recaptchaService->verify($recaptchaToken, 'monetization');
            if (!$recaptchaResult['success']) {
                Log::warning('reCAPTCHA verification failed for monetization', [
                    'link_id' => $link->id,
                    'ip' => request()->ip(),
                    'score' => $recaptchaResult['score'] ?? 0,
                    'message' => $recaptchaResult['message'] ?? 'Unknown error',
                ]);

                abort(400, 'reCAPTCHA verification failed. Please try again.');
            }

            // Update session with reCAPTCHA verification
            $sessionData['recaptcha_verified'] = true;
            Cache::put("monetization_session_{$sessionToken}", $sessionData, 900);
        }

        // Record the click and generate earnings
        $click = $this->recordClick($link, $request, $sessionData);

        // Remove session from cache
        Cache::forget("monetization_session_{$sessionToken}");

        // Redirect to target URL
        return redirect($link->original_url);
    }

    /**
     * Record a preliminary click when user visits the intermediate page
     * This ensures clicks are tracked for analytics even if user doesn't complete the flow
     */
    private function recordPreliminaryClick(Link $link, Request $request): LinkClick
    {
        // Check if this is a unique click (same IP within 24 hours)
        $isUnique = !$link->clicks()
            ->where('ip_address', $request->ip())
            ->where('clicked_at', '>=', now()->subHours(24))
            ->exists();

        // Create preliminary click record (earnings will be updated on completion)
        $click = $link->clicks()->create([
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referer' => $request->header('referer'),
            'earnings_generated' => 0, // Will be updated on completion
            'is_unique' => $isUnique,
            'clicked_at' => now(),
        ]);

        // Update last click time
        $link->update(['last_click_at' => now()]);

        // Track click in real-time
        try {
            $this->realTimeAnalyticsService->trackClick($click);
            event(new ClickTracked($click));
        } catch (\Exception $e) {
            Log::error('Real-time tracking failed: ' . $e->getMessage());
        }

        return $click;
    }

    /**
     * Update the preliminary click with earnings and generate earnings record
     */
    private function recordClick(Link $link, Request $request, array $sessionData): LinkClick
    {
        // Get the preliminary click record if it exists
        $click = null;
        if (isset($sessionData['click_id'])) {
            $click = LinkClick::find($sessionData['click_id']);
        }

        // If no preliminary click found, create a new one
        if (!$click || $click->link_id != $link->id) {
            $click = $this->recordPreliminaryClick($link, $request);
        }

        // Check if this is still unique (re-check in case time passed)
        $isUnique = !$link->clicks()
            ->where('id', '!=', $click->id)
            ->where('ip_address', $sessionData['ip_address'])
            ->where('clicked_at', '>=', now()->subHours(24))
            ->exists();

        // Get global settings to check if earnings are enabled
        $globalSettings = GlobalSetting::getSettings();
        $earningsEnabled = $globalSettings->isEarningsEnabled();

        // Calculate earnings only if earnings feature is enabled
        $earningsAmount = 0;
        $earningsData = null;

        if ($earningsEnabled) {
            // Calculate earnings based on CPM rates and country (enhanced)
            $earningsData = $this->calculateEarnings($link, $sessionData);
            $earningsAmount = $isUnique ? $earningsData['amount'] : 0;
        }

        // Update click record (always update, but earnings will be 0 if earnings disabled)
        $click->update([
            'earnings_generated' => $earningsAmount,
            'is_unique' => $isUnique,
        ]);

        // Generate earnings only if earnings feature is enabled and it's a unique click
        if ($earningsEnabled && $isUnique && $earningsAmount > 0 && $earningsData) {
            $earning = $this->generateEarnings($link, $click, $earningsData);

            // Dispatch real-time events
            try {
                // Track updated click in real-time
                $this->realTimeAnalyticsService->trackClick($click);

                // Broadcast earnings event if earnings were generated
                if ($earning) {
                    event(new EarningsTracked($earning));
                }
            } catch (\Exception $e) {
                Log::error('Real-time tracking failed: ' . $e->getMessage());
            }
        } else {
            // Still track the click even if earnings are disabled
            try {
                $this->realTimeAnalyticsService->trackClick($click);
            } catch (\Exception $e) {
                Log::error('Real-time tracking failed: ' . $e->getMessage());
            }
        }

        return $click;
    }

    /**
     * Calculate earnings based on CPM rates and context
     */
    private function calculateEarnings(Link $link, array $sessionData): array
    {
        $userCurrency = $link->user->preferred_currency ?? 'INR';

        // Get context for CPM calculation
        $context = [
            'country' => $this->cpmEarningsService->getCountryFromIP($sessionData['ip_address']),
            'device' => $this->cpmEarningsService->detectDevice($sessionData['user_agent']),
            'time_of_day' => $this->cpmEarningsService->getTimeOfDay(),
            'ad_type' => $link->ad_type,
            'is_premium' => $link->user && $link->user->isPremium(),
        ];

        // Calculate earnings using CPM service
        $cpmEarnings = $this->cpmEarningsService->calculateEarnings($context);

        // Apply reCAPTCHA bonus if verified
        $recaptchaBonus = $sessionData['recaptcha_verified'] ? 1.1 : 1.0;

        // Calculate final earnings
        $earnings = $cpmEarnings['earnings_per_click'] * $recaptchaBonus;
        $earnings = round($earnings, 4);

        // Calculate earnings in all supported currencies
        $multiCurrencyEarnings = $this->cpmEarningsService->calculateMultiCurrencyEarnings($earnings, ['USD', 'INR']);

        return [
            'amount' => $earnings,
            'currency' => $userCurrency,
            'multi_currency' => $multiCurrencyEarnings,
            'country' => $context['country'],
            'device' => $context['device'],
            'time_of_day' => $context['time_of_day'],
            'ad_type' => $context['ad_type'],
            'is_premium' => $context['is_premium'],
            'cpm_data' => $cpmEarnings,
            'recaptcha_bonus' => $recaptchaBonus,
        ];
    }



    /**
     * Generate earnings for a valid click
     */
    private function generateEarnings(Link $link, LinkClick $click, array $earningsData): ?UserEarning
    {
        $user = $link->user;
        $userCurrency = $user->preferred_currency ?? 'INR';

        // Create earnings record in user's preferred currency
        $earning = $link->earnings()->create([
            'user_id' => $user->id,
            'link_id' => $link->id,
            'amount' => $earningsData['amount'],
            'currency' => $userCurrency,
            'status' => 'pending', // Will be approved by admin
            'notes' => "Generated from click #{$click->id}",
        ]);

        // Update user balance in their preferred currency
        $user->incrementBalanceInCurrency($userCurrency, $earningsData['amount']);

        // Also update the legacy balance field for backward compatibility
        if ($userCurrency === 'INR') {
            $user->increment('balance_inr', $earningsData['amount']);
        } elseif ($userCurrency === 'USD') {
            $user->increment('balance_usd', $earningsData['amount']);
        }

        // Process referral commission
        $referralService = app(\App\Services\ReferralCommissionService::class);
        $referralService->processReferralCommission($user, $earningsData['amount'], $userCurrency, 'link_click');

        return $earning;
    }

    /**
     * Show limit reached page
     */
    public function showLimitReached($shortCode)
    {
        $link = Link::where('short_code', $shortCode)->firstOrFail();
        return view('links.limit_reached', compact('link'));
    }

    /**
     * Get monetization statistics for a link
     */
    public function getStats(Link $link)
    {
        $this->authorize('view', $link);

        $stats = [
            'total_clicks' => $link->total_clicks,
            'unique_clicks' => $link->unique_clicks,
            'total_earnings' => $link->total_earnings,
            'today_clicks' => $link->clicks()->today()->count(),
            'this_week_clicks' => $link->clicks()->thisWeek()->count(),
            'this_month_clicks' => $link->clicks()->thisMonth()->count(),
            'earnings_today' => $link->earnings()
                ->where('status', 'approved')
                ->whereDate('created_at', today())
                ->sum('amount'),
            'earnings_this_month' => $link->earnings()
                ->where('status', 'approved')
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount'),
        ];

        return response()->json($stats);
    }

    /**
     * Validate monetization session (AJAX endpoint)
     */
    public function validateSession(Request $request)
    {
        $sessionToken = $request->input('session_token');
        $sessionData = Cache::get("monetization_session_{$sessionToken}");

        if (!$sessionData) {
            return response()->json(['valid' => false, 'message' => 'Session expired']);
        }

        return response()->json(['valid' => true, 'data' => $sessionData]);
    }

    /**
     * Get countdown status (AJAX endpoint)
     */
    public function getCountdownStatus(Request $request)
    {
        $sessionToken = $request->input('session_token');
        $sessionData = Cache::get("monetization_session_{$sessionToken}");

        if (!$sessionData) {
            return response()->json(['valid' => false, 'message' => 'Session expired']);
        }

        $elapsed = now()->diffInSeconds($sessionData['created_at']);
        $required = 10; // 10 seconds countdown
        $remaining = max(0, $required - $elapsed);

        return response()->json([
            'valid' => true,
            'remaining' => $remaining,
            'completed' => $remaining <= 0,
            'progress' => min(100, (($required - $remaining) / $required) * 100)
        ]);
    }

    /**
     * Verify reCAPTCHA token (AJAX endpoint)
     */
    public function verifyRecaptcha(Request $request)
    {
        $token = $request->input('token');

        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'reCAPTCHA token required'
            ]);
        }

        $result = $this->recaptchaService->verify($token, 'monetization');

        return response()->json($result);
    }
}
