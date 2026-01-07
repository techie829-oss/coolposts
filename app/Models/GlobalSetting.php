<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class GlobalSetting extends Model
{
    protected $fillable = [
        'no_ads_rate_inr',
        'short_ads_rate_inr',
        'long_ads_rate_inr',
        'no_ads_rate_usd',
        'short_ads_rate_usd',
        'long_ads_rate_usd',
        'short_ads_min_duration',
        'short_ads_max_duration',
        'long_ads_min_duration',
        'long_ads_max_duration',
        'premium_monthly_price_inr',
        'premium_monthly_price_usd',
        'premium_yearly_price_inr',
        'premium_yearly_price_usd',
        'premium_short_ads_multiplier',
        'premium_long_ads_multiplier',
        'min_withdrawal_inr',
        'min_withdrawal_usd',
        'withdrawal_fee_percentage',
        'maintenance_mode',
        'maintenance_message',
        'new_registrations',
        'link_creation_enabled',
        'adsense_publisher_id',
        'adsense_ad_slot_1',
        'adsense_ad_slot_2',
        'adsense_ad_slot_3',
        'enable_click_tracking',
        'enable_geo_tracking',
        'enable_device_tracking',
        'default_blog_monetization_type',
        'default_blog_ad_type',
        'default_blog_earning_rate_less_2min_inr',
        'default_blog_earning_rate_2_5min_inr',
        'default_blog_earning_rate_more_5min_inr',
        'default_blog_earning_rate_less_2min_usd',
        'default_blog_earning_rate_2_5min_usd',
        'default_blog_earning_rate_more_5min_usd',
        'adsense_enabled',
        'adsense_client_id',
        'adsense_client_secret',
        'adsense_refresh_token',
        'adsense_account_id',
        'admob_enabled',
        'admob_client_id',
        'admob_client_secret',
        'admob_refresh_token',
        'admob_account_id',
        'primary_ad_network',
        'ad_network_fallback_order',
        'ad_network_fallback_enabled',
        'ad_network_revenue_share',
        'earnings_enabled',
        'monetization_enabled',
        'ads_enabled',
        'referrals_enabled',
        'referral_signup_bonus_inr',
        'referral_signup_bonus_usd',
        'referral_commission_type',
        'referral_commission_rate',
        'referral_commission_flat_inr',
        'referral_commission_flat_usd',
        'referral_minimum_earnings',
        'referral_commission_duration',
        'referral_max_referrals_per_user',
        'referral_premium_upgrade_bonus_inr',
        'referral_premium_upgrade_bonus_usd',
        'referral_tier_2_enabled',
        'referral_tier_2_rate',
        'referral_tier_3_enabled',
        'referral_tier_3_rate',
        'fraud_rapid_click_threshold',
        'fraud_click_time_window',
        'fraud_vpn_penalty_score',
        'fraud_bot_penalty_score',
        'payouts_frozen',
    ];

    protected $casts = [
        'no_ads_rate_inr' => 'decimal:4',
        'short_ads_rate_inr' => 'decimal:4',
        'long_ads_rate_inr' => 'decimal:4',
        'no_ads_rate_usd' => 'decimal:4',
        'short_ads_rate_usd' => 'decimal:4',
        'long_ads_rate_usd' => 'decimal:4',
        'short_ads_min_duration' => 'integer',
        'short_ads_max_duration' => 'integer',
        'long_ads_min_duration' => 'integer',
        'long_ads_max_duration' => 'integer',
        'premium_monthly_price_inr' => 'decimal:2',
        'premium_monthly_price_usd' => 'decimal:2',
        'premium_yearly_price_inr' => 'decimal:2',
        'premium_yearly_price_usd' => 'decimal:2',
        'premium_short_ads_multiplier' => 'decimal:2',
        'premium_long_ads_multiplier' => 'decimal:2',
        'min_withdrawal_inr' => 'decimal:2',
        'min_withdrawal_usd' => 'decimal:2',
        'withdrawal_fee_percentage' => 'decimal:2',
        'maintenance_mode' => 'boolean',
        'new_registrations' => 'boolean',
        'link_creation_enabled' => 'boolean',
        'enable_click_tracking' => 'boolean',
        'enable_geo_tracking' => 'boolean',
        'enable_device_tracking' => 'boolean',
        'default_blog_earning_rate_less_2min_inr' => 'decimal:4',
        'default_blog_earning_rate_2_5min_inr' => 'decimal:4',
        'default_blog_earning_rate_more_5min_inr' => 'decimal:4',
        'default_blog_earning_rate_less_2min_usd' => 'decimal:4',
        'default_blog_earning_rate_2_5min_usd' => 'decimal:4',
        'default_blog_earning_rate_more_5min_usd' => 'decimal:4',
        'adsense_enabled' => 'boolean',
        'admob_enabled' => 'boolean',
        'ad_network_fallback_enabled' => 'boolean',
        'ad_network_revenue_share' => 'decimal:4',
        'ad_network_fallback_order' => 'array',
        'earnings_enabled' => 'boolean',
        'monetization_enabled' => 'boolean',
        'ads_enabled' => 'boolean',
        'referrals_enabled' => 'boolean',
        'referral_signup_bonus_inr' => 'decimal:4',
        'referral_signup_bonus_usd' => 'decimal:4',
        'referral_commission_type' => 'string',
        'referral_commission_rate' => 'decimal:4',
        'referral_commission_flat_inr' => 'decimal:4',
        'referral_commission_flat_usd' => 'decimal:4',
        'referral_minimum_earnings' => 'decimal:4',
        'referral_commission_duration' => 'integer',
        'referral_max_referrals_per_user' => 'integer',
        'referral_premium_upgrade_bonus_inr' => 'decimal:4',
        'referral_premium_upgrade_bonus_usd' => 'decimal:4',
        'referral_tier_2_enabled' => 'boolean',
        'referral_tier_2_rate' => 'decimal:4',
        'referral_tier_3_enabled' => 'boolean',
        'referral_tier_3_rate' => 'decimal:4',
        'fraud_rapid_click_threshold' => 'integer',
        'fraud_click_time_window' => 'integer',
        'fraud_vpn_penalty_score' => 'decimal:2',
        'fraud_bot_penalty_score' => 'decimal:2',
        'payouts_frozen' => 'boolean',
    ];

    /**
     * Get global settings (cached for performance)
     */
    public static function getSettings(): self
    {
        return Cache::remember('global_settings', 3600, function () {
            return self::first() ?? self::createDefaultSettings();
        });
    }

    /**
     * Create default global settings
     */
    public static function createDefaultSettings(): self
    {
        return self::create([
            'no_ads_rate_inr' => 0.00,
            'short_ads_rate_inr' => 0.50,
            'long_ads_rate_inr' => 1.00,
            'no_ads_rate_usd' => 0.00,
            'short_ads_rate_usd' => 0.006,
            'long_ads_rate_usd' => 0.012,
            'short_ads_min_duration' => 10,
            'short_ads_max_duration' => 30,
            'long_ads_min_duration' => 30,
            'long_ads_max_duration' => 60,
            'premium_monthly_price_inr' => 299.00,
            'premium_monthly_price_usd' => 3.99,
            'premium_yearly_price_inr' => 2999.00,
            'premium_yearly_price_usd' => 39.99,
            'premium_short_ads_multiplier' => 1.5,
            'premium_long_ads_multiplier' => 2.0,
            'min_withdrawal_inr' => 100.00,
            'min_withdrawal_usd' => 1.00,
            'withdrawal_fee_percentage' => 2.50,
            'maintenance_mode' => false,
            'new_registrations' => true,
            'link_creation_enabled' => true,
            'enable_click_tracking' => true,
            'enable_geo_tracking' => true,
            'enable_device_tracking' => true,
            'earnings_enabled' => false,
            'monetization_enabled' => true,
            'ads_enabled' => true,
            'referrals_enabled' => false,
            'referral_signup_bonus_inr' => 5.00,
            'referral_signup_bonus_usd' => 0.06,
            'referral_commission_type' => 'percentage',
            'referral_commission_rate' => 10.00,
            'referral_commission_flat_inr' => 1.00,
            'referral_commission_flat_usd' => 0.012,
            'referral_minimum_earnings' => 10.00,
            'referral_commission_duration' => 30,
            'referral_max_referrals_per_user' => 100,
            'referral_premium_upgrade_bonus_inr' => 50.00,
            'referral_premium_upgrade_bonus_usd' => 0.60,
            'referral_tier_2_enabled' => false,
            'referral_tier_2_rate' => 5.00,
            'referral_tier_3_enabled' => false,
            'referral_tier_3_rate' => 2.00,
            'fraud_rapid_click_threshold' => 10,
            'fraud_click_time_window' => 300,
            'fraud_vpn_penalty_score' => 0.30,
            'fraud_bot_penalty_score' => 0.40,
            'payouts_frozen' => true,
        ]);
    }

    /**
     * Get earning rate for specific ad type and currency
     */
    public function getEarningRate(string $adType, string $currency, bool $isPremium = false): string
    {
        $rate = match ($adType) {
            'no_ads' => $currency === 'INR' ? $this->no_ads_rate_inr : $this->no_ads_rate_usd,
            'short_ads' => $currency === 'INR' ? $this->short_ads_rate_inr : $this->short_ads_rate_usd,
            'long_ads' => $currency === 'INR' ? $this->long_ads_rate_inr : $this->long_ads_rate_usd,
            default => 0.00,
        };

        // Apply premium multiplier if user is premium
        if ($isPremium && $adType !== 'no_ads') {
            $multiplier = $adType === 'short_ads' ? $this->premium_short_ads_multiplier : $this->premium_long_ads_multiplier;
            $rate *= $multiplier;
        }

        return (string) round($rate, 4);
    }

    /**
     * Get ad duration for specific ad type
     */
    public function getAdDuration(string $adType): array
    {
        return match ($adType) {
            'short_ads' => [
                'min' => $this->short_ads_min_duration,
                'max' => $this->short_ads_max_duration,
            ],
            'long_ads' => [
                'min' => $this->long_ads_min_duration,
                'max' => $this->long_ads_max_duration,
            ],
            default => ['min' => 0, 'max' => 0],
        };
    }

    /**
     * Get premium price for specific currency and period
     */
    public function getPremiumPrice(string $currency, string $period): float
    {
        return match (true) {
            $currency === 'INR' && $period === 'monthly' => $this->premium_monthly_price_inr,
            $currency === 'INR' && $period === 'yearly' => $this->premium_yearly_price_inr,
            $currency === 'USD' && $period === 'monthly' => $this->premium_monthly_price_usd,
            $currency === 'USD' && $period === 'yearly' => $this->premium_yearly_price_usd,
            default => 0.00,
        };
    }

    /**
     * Get minimum withdrawal amount for specific currency
     */
    public function getMinWithdrawal(string $currency): float
    {
        return (float) ($currency === 'INR' ? $this->min_withdrawal_inr : $this->min_withdrawal_usd);
    }

    /**
     * Check if system is in maintenance mode
     */
    public function isMaintenanceMode(): bool
    {
        return $this->maintenance_mode;
    }

    /**
     * Check if new registrations are allowed
     */
    public function areNewRegistrationsAllowed(): bool
    {
        return $this->new_registrations;
    }

    /**
     * Check if link creation is enabled
     */
    public function isLinkCreationEnabled(): bool
    {
        return $this->link_creation_enabled;
    }

    /**
     * Clear settings cache
     */
    public static function clearCache(): void
    {
        Cache::forget('global_settings');
    }

    /**
     * Check if earnings feature is enabled
     */
    public function isEarningsEnabled(): bool
    {
        return $this->earnings_enabled ?? false;
    }

    /**
     * Check if monetization feature is enabled
     */
    public function isMonetizationEnabled(): bool
    {
        return $this->monetization_enabled ?? true;
    }

    /**
     * Check if ads feature is enabled
     */
    public function isAdsEnabled(): bool
    {
        return $this->ads_enabled ?? true;
    }

    /**
     * Check if subscriptions feature is enabled
     */
    public function isSubscriptionEnabled(): bool
    {
        // Subscriptions are enabled if premium pricing is configured
        return ($this->premium_monthly_price_inr > 0 || $this->premium_monthly_price_usd > 0) ||
            ($this->premium_yearly_price_inr > 0 || $this->premium_yearly_price_usd > 0);
    }

    /**
     * Check if referrals feature is enabled
     */
    public function isReferralsEnabled(): bool
    {
        // Referrals depend on earnings being enabled
        return ($this->referrals_enabled ?? false) && $this->isEarningsEnabled();
    }

    /**
     * Get referral signup bonus for specific currency
     */
    public function getReferralSignupBonus(string $currency): float
    {
        return (float) ($currency === 'INR' ? $this->referral_signup_bonus_inr : $this->referral_signup_bonus_usd);
    }

    /**
     * Get referral commission amount based on type and currency
     */
    public function getReferralCommission(float $earnedAmount, string $currency): float
    {
        if ($this->referral_commission_type === 'flat') {
            return (float) ($currency === 'INR' ? $this->referral_commission_flat_inr : $this->referral_commission_flat_usd);
        }

        // Percentage-based commission
        return (float) ($earnedAmount * ($this->referral_commission_rate / 100));
    }

    /**
     * Get referral premium upgrade bonus for specific currency
     */
    public function getReferralPremiumUpgradeBonus(string $currency): float
    {
        return (float) ($currency === 'INR' ? $this->referral_premium_upgrade_bonus_inr : $this->referral_premium_upgrade_bonus_usd);
    }

    /**
     * Check if tier 2 referrals are enabled
     */
    public function isTier2ReferralsEnabled(): bool
    {
        return $this->referral_tier_2_enabled ?? false;
    }

    /**
     * Check if tier 3 referrals are enabled
     */
    public function isTier3ReferralsEnabled(): bool
    {
        return $this->referral_tier_3_enabled ?? false;
    }

    /**
     * Get tier 2 referral commission rate
     */
    public function getTier2ReferralRate(): float
    {
        return (float) $this->referral_tier_2_rate;
    }

    /**
     * Get tier 3 referral commission rate
     */
    public function getTier3ReferralRate(): float
    {
        return (float) $this->referral_tier_3_rate;
    }
}
