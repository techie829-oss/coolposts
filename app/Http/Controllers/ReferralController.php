<?php

namespace App\Http\Controllers;

use App\Models\Referral;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ReferralController extends Controller
{
    /**
     * Show referral dashboard
     */
    public function dashboard()
    {
        $user = Auth::user();

        // Generate referral code if user doesn't have one
        if (!$user->hasReferralCode()) {
            $user->generateReferralCode();
        }

        $referrals = $user->referrals()->with('referred')->orderBy('created_at', 'desc')->get();
        $activeReferrals = $referrals->where('status', 'pending');
        $completedReferrals = $referrals->where('status', 'completed');
        $expiredReferrals = $referrals->where('status', 'expired');

        $stats = [
            'total_referrals' => $referrals->count(),
            'active_referrals' => $activeReferrals->count(),
            'completed_referrals' => $completedReferrals->count(),
            'expired_referrals' => $expiredReferrals->count(),
            'total_earnings_inr' => $user->getReferralEarnings('INR'),
            'total_earnings_usd' => $user->getReferralEarnings('USD'),
            'referral_code' => $user->referral_code,
        ];

        return view('referrals.dashboard', compact('stats', 'referrals', 'activeReferrals', 'completedReferrals', 'expiredReferrals'));
    }

    /**
     * Process referral signup
     */
    public function processReferral(Request $request)
    {
        $request->validate([
            'referral_code' => 'required|string|max:20',
        ]);

        // Check if referrals are enabled
        $globalSettings = \App\Models\GlobalSetting::getSettings();
        if (!$globalSettings->isReferralsEnabled()) {
            return back()->with('error', 'Referral system is currently disabled.');
        }

        // Check if earnings are enabled (referrals depend on earnings)
        if (!$globalSettings->isEarningsEnabled()) {
            return back()->with('error', 'Earnings system must be enabled for referrals to work.');
        }

        $user = Auth::user();
        $referralCode = strtoupper($request->referral_code);

        // Check if user already has a referral
        if ($user->referred_by) {
            return back()->with('error', 'You have already been referred by another user.');
        }

        // Check if user is trying to refer themselves
        if ($user->referral_code === $referralCode) {
            return back()->with('error', 'You cannot refer yourself.');
        }

        // Find the referrer
        $referrer = User::where('referral_code', $referralCode)->first();
        if (!$referrer) {
            return back()->with('error', 'Invalid referral code.');
        }

        try {
            DB::beginTransaction();

            // Create referral record
            $referral = Referral::create([
                'referrer_id' => $referrer->id,
                'referred_id' => $user->id,
                'referral_code' => $referralCode,
                'status' => 'pending',
                'currency' => $user->preferred_currency,
                'commission_rate' => $this->getReferralCommissionRate(),
                'expires_at' => now()->addDays(30), // 30 days to complete referral
            ]);

            // Update user with referral info
            $user->update([
                'referred_by' => $referral->id,
            ]);

            DB::commit();

            return back()->with('success', 'Referral applied successfully! Complete your first payment to earn your referrer a bonus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to process referral. Please try again.');
        }
    }

    /**
     * Get referral commission rate
     */
    protected function getReferralCommissionRate(): float
    {
        // This could be configurable from admin settings
        return 10.0; // 10% commission
    }

    /**
     * Complete referral when user makes first payment
     */
    public function completeReferral(User $user)
    {
        if (!$user->referred_by) {
            return false;
        }

        $referral = Referral::find($user->referred_by);
        if (!$referral || $referral->status !== 'pending') {
            return false;
        }

        try {
            DB::beginTransaction();

            // Calculate referral bonus
            $bonusAmount = $this->calculateReferralBonus($user);

            // Update referral record
            $referral->update([
                'status' => 'completed',
                'completed_at' => now(),
                'amount_inr' => $user->preferred_currency === 'INR' ? $bonusAmount : 0,
                'amount_usd' => $user->preferred_currency === 'USD' ? $bonusAmount : 0,
            ]);

            // Add bonus to referrer's balance
            $referrer = $referral->referrer;
            $referrer->incrementBalanceInCurrency($bonusAmount, $user->preferred_currency);

            // Update referrer's referral earnings
            $referrer->increment("referral_earnings_{strtolower($user->preferred_currency)}", $bonusAmount);
            $referrer->update(['last_referral_at' => now()]);

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    /**
     * Calculate referral bonus amount
     */
    protected function calculateReferralBonus(User $user): float
    {
        // Base bonus amount
        $baseBonus = $user->preferred_currency === 'INR' ? 100.00 : 1.50; // ₹100 or $1.50

        // Additional bonus based on user's first payment
        // This could be a percentage of their first subscription payment
        $firstPayment = $user->paymentTransactions()
            ->where('status', 'completed')
            ->orderBy('created_at')
            ->first();

        if ($firstPayment) {
            $paymentAmount = $firstPayment->amount;
            $bonusPercentage = 0.05; // 5% of first payment
            $additionalBonus = $paymentAmount * $bonusPercentage;
            $baseBonus += $additionalBonus;
        }

        return $baseBonus;
    }

    /**
     * Get referral statistics for admin
     */
    public function adminStats()
    {
        $this->authorize('viewAny', Referral::class);

        $stats = [
            'total_referrals' => Referral::count(),
            'pending_referrals' => Referral::pending()->count(),
            'completed_referrals' => Referral::completed()->count(),
            'expired_referrals' => Referral::expired()->count(),
            'total_earnings_inr' => Referral::completed()->sum('amount_inr'),
            'total_earnings_usd' => Referral::completed()->sum('amount_usd'),
            'active_users_with_referrals' => User::whereNotNull('referral_code')->count(),
        ];

        $topReferrers = User::whereNotNull('referral_code')
            ->withCount(['referrals as completed_count' => function($query) {
                $query->where('status', 'completed');
            }])
            ->orderBy('completed_count', 'desc')
            ->limit(10)
            ->get();

        return view('admin.referrals.stats', compact('stats', 'topReferrers'));
    }

    /**
     * Get referral link for sharing
     */
    public function getReferralLink()
    {
        $user = Auth::user();

        if (!$user->hasReferralCode()) {
            $user->generateReferralCode();
        }

        $referralLink = route('welcome') . '?ref=' . $user->referral_code;

        return response()->json([
            'success' => true,
            'referral_link' => $referralLink,
            'referral_code' => $user->referral_code,
        ]);
    }

    /**
     * Share referral on social media
     */
    public function shareReferral(Request $request)
    {
        $request->validate([
            'platform' => 'required|string|in:whatsapp,telegram,facebook,twitter,email',
        ]);

        $user = Auth::user();
        $referralLink = route('welcome') . '?ref=' . $user->referral_code;

        $message = "Join CoolHax Posts and start earning money by sharing links! Use my referral code: {$user->referral_code}\n\n{$referralLink}";

        $shareUrls = [
            'whatsapp' => "https://wa.me/?text=" . urlencode($message),
            'telegram' => "https://t.me/share/url?url=" . urlencode($referralLink) . "&text=" . urlencode($message),
            'facebook' => "https://www.facebook.com/sharer/sharer.php?u=" . urlencode($referralLink),
            'twitter' => "https://twitter.com/intent/tweet?text=" . urlencode($message) . "&url=" . urlencode($referralLink),
            'email' => "mailto:?subject=Join CoolHax Posts&body=" . urlencode($message),
        ];

        return response()->json([
            'success' => true,
            'share_url' => $shareUrls[$request->platform],
        ]);
    }
}
