<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Link extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'short_code',
        'original_url',
        'title',
        'description',
        'is_active',
        'expiry',
        'max_clicks',
        'earnings_per_click',
        'currency',
        'ad_type',
        'ad_duration',
        'earnings_per_click_inr',
        'earnings_per_click_usd',
        'daily_click_limit',
        'is_protected',
        'password',
        'category',
        'last_click_at',
        'is_monetized',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'expiry' => 'datetime',
            'max_clicks' => 'integer',
            'earnings_per_click' => 'decimal:4',
            'earnings_per_click_inr' => 'decimal:4',
            'earnings_per_click_usd' => 'decimal:4',
            'daily_click_limit' => 'integer',
            'password_protected' => 'boolean',
            'last_click_at' => 'datetime',
            'is_monetized' => 'boolean',
        ];
    }

    /**
     * Get earnings per click in specific currency
     */
    public function getEarningsPerClickInCurrency(string $currency): float
    {
        // Get global settings
        $globalSettings = \App\Models\GlobalSetting::getSettings();

        // Check if user is premium
        $isPremium = $this->user && $this->user->isPremium();

        // Get rate from global settings based on ad type
        $rate = $globalSettings->getEarningRate($this->ad_type, $currency, $isPremium);

        return (float) $rate;
    }

    /**
     * Get ad duration for this link
     */
    public function getAdDuration(): int
    {
        // If custom duration is set, use it
        if ($this->ad_duration) {
            return $this->ad_duration;
        }

        // Otherwise use global settings
        $globalSettings = \App\Models\GlobalSetting::getSettings();
        $duration = $globalSettings->getAdDuration($this->ad_type);

        return $duration['max'] ?? 30; // Default to 30 seconds
    }

    /**
     * Get ad type display name
     */
    public function getAdTypeDisplayName(): string
    {
        return match ($this->ad_type) {
            'no_ads' => 'No Ads (Free)',
            'short_ads' => 'Short Ads (10-30s)',
            'long_ads' => 'Long Ads (30s-1min)',
            default => 'Unknown',
        };
    }

    /**
     * Get ad type description
     */
    public function getAdTypeDescription(): string
    {
        return match ($this->ad_type) {
            'no_ads' => 'No advertisements, no earnings',
            'short_ads' => 'Quick ads with basic earning rate',
            'long_ads' => 'Longer ads with premium earning rate',
            default => 'Unknown ad type',
        };
    }

    /**
     * Set earnings per click in specific currency
     */
    public function setEarningsPerClickInCurrency(string $currency, float $amount): void
    {
        switch (strtoupper($currency)) {
            case 'INR':
                $this->earnings_per_click_inr = $amount;
                break;
            case 'USD':
                $this->earnings_per_click_usd = $amount;
                break;
        }
    }

    /**
     * Get total earnings in specific currency
     */
    public function getTotalEarningsInCurrency(string $currency): float
    {
        return $this->earnings()
            ->where('status', 'approved')
            ->where('currency', $currency)
            ->sum('amount');
    }

    /**
     * Get pending earnings in specific currency
     */
    public function getPendingEarningsInCurrency(string $currency): float
    {
        return $this->earnings()
            ->where('status', 'pending')
            ->where('currency', $currency)
            ->sum('amount');
    }

    /**
     * Get the user that owns the link
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the clicks for this link
     */
    public function clicks(): HasMany
    {
        return $this->hasMany(LinkClick::class);
    }

    /**
     * Get the earnings for this link
     */
    public function earnings(): HasMany
    {
        return $this->hasMany(UserEarning::class);
    }

    /**
     * Generate a unique short code
     */
    public static function generateShortCode(): string
    {
        do {
            $code = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 6);
        } while (static::where('short_code', $code)->exists());

        return $code;
    }

    /**
     * Get the full short URL
     */
    public function getShortUrlAttribute(): string
    {
        return url('/' . $this->short_code);
    }

    /**
     * Get total earnings for this link
     */
    public function getTotalEarningsAttribute(): float
    {
        return $this->earnings()->where('status', 'approved')->sum('amount');
    }

    /**
     * Get total clicks for this link
     */
    public function getTotalClicksAttribute(): int
    {
        return $this->clicks()->count();
    }

    /**
     * Get unique clicks for this link
     */
    public function getUniqueClicksAttribute(): int
    {
        return $this->clicks()->where('is_unique', true)->count();
    }

    /**
     * Check if link can be clicked (daily limit not reached)
     */
    public function canBeClicked(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        // Check if link has expired
        if ($this->expiry && now()->isAfter($this->expiry)) {
            return false;
        }

        // Check daily click limit
        if ($this->daily_click_limit) {
            $todayClicks = $this->clicks()
                ->whereDate('clicked_at', today())
                ->count();

            return $todayClicks < $this->daily_click_limit;
        }

        return true;
    }

    /**
     * Record a click on this link
     */
    public function recordClick($request): LinkClick
    {
        $isUnique = !$this->clicks()
            ->where('ip_address', $request->ip())
            ->where('created_at', '>=', now()->subHours(24))
            ->exists();

        $click = $this->clicks()->create([
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referer' => $request->header('referer'),
            'earnings_generated' => $isUnique ? $this->earnings_per_click : 0,
            'is_unique' => $isUnique,
            'clicked_at' => now(),
        ]);

        if ($isUnique) {
            // Check if monetization is strictly enabled
            $globalSettings = \App\Models\GlobalSetting::getSettings();
            $isEarningsEnabled = $globalSettings->isEarningsEnabled();
            $isShadow = !$isEarningsEnabled; // If not enabled, run in shadow mode

            // Create earnings record
            $earning = $this->earnings()->create([
                'user_id' => $this->user_id,
                'link_id' => $this->id,
                'amount' => $this->earnings_per_click,
                'status' => 'pending',
                'is_shadow' => $isShadow,
                'notes' => $isShadow ? 'Shadow earning (Monetization disabled)' : null,
            ]);

            // Update user balance
            if ($isShadow) {
                $this->user->incrementShadowBalanceInCurrency($this->user->preferred_currency ?? 'INR', $this->earnings_per_click);
            } else {
                $this->user->incrementBalanceInCurrency($this->user->preferred_currency ?? 'INR', $this->earnings_per_click);
            }

            // Process referral commission
            $referralService = app(\App\Services\ReferralCommissionService::class);
            $referralService->processReferralCommission(
                $this->user,
                (float) $this->earnings_per_click,
                $this->user->preferred_currency ?? 'INR',
                'link_click',
                $isShadow // Pass shadow flag
            );

            // Update last click time
            $this->update(['last_click_at' => now()]);
        }

        return $click;
    }
}
