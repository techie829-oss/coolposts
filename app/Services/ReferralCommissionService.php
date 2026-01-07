<?php

namespace App\Services;

use App\Models\User;
use App\Models\Referral;
use App\Models\GlobalSetting;
use App\Models\UserEarning;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReferralCommissionService
{
    protected $globalSettings;

    public function __construct()
    {
        $this->globalSettings = GlobalSetting::getSettings();
    }

    /**
     * Process referral commission when user earns money
     */
    /**
     * Process referral commission when user earns money
     */
    public function processReferralCommission(User $earningUser, float $earnedAmount, string $currency, string $source = 'unknown', bool $isShadow = false): bool
    {
        // Check if referrals are enabled (this now includes earnings check)
        if (!$this->globalSettings->isReferralsEnabled()) {
            return false;
        }

        // Check if user was referred
        if (!$earningUser->referred_by) {
            return false;
        }

        $referral = Referral::find($earningUser->referred_by);
        if (!$referral || !$referral->isPending()) {
            return false;
        }

        try {
            DB::beginTransaction();

            // Calculate commission amount
            $commissionAmount = $this->calculateCommission($earnedAmount, $currency);

            if ($commissionAmount <= 0) {
                DB::rollBack();
                return false;
            }

            // Get referrer
            $referrer = $referral->referrer;

            // Add commission to referrer's wallet
            $this->creditReferrerWallet($referrer, $commissionAmount, $currency, $earningUser, $source, $isShadow);

            // Update referral record (ONLY if not shadow)
            if (!$isShadow) {
                $this->updateReferralRecord($referral, $commissionAmount, $currency);
            }

            // Process multi-tier referrals
            $this->processMultiTierReferrals($referrer, $commissionAmount, $currency, $earningUser, $source, $isShadow);

            DB::commit();

            Log::info("Referral commission processed", [
                'referrer_id' => $referrer->id,
                'referred_id' => $earningUser->id,
                'commission_amount' => $commissionAmount,
                'currency' => $currency,
                'source' => $source,
                'is_shadow' => $isShadow
            ]);

            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Referral commission processing failed", [
                'error' => $e->getMessage(),
                'earning_user_id' => $earningUser->id,
                'earned_amount' => $earnedAmount,
                'currency' => $currency
            ]);
            return false;
        }
    }

    /**
     * Calculate commission amount based on settings
     */
    protected function calculateCommission(float $earnedAmount, string $currency): float
    {
        // Check minimum earnings threshold
        $minimumEarnings = $this->globalSettings->referral_minimum_earnings;
        if ($earnedAmount < $minimumEarnings) {
            return 0;
        }

        // Get commission amount from global settings
        return $this->globalSettings->getReferralCommission($earnedAmount, $currency);
    }

    /**
     * Credit commission to referrer's wallet
     */
    protected function creditReferrerWallet(User $referrer, float $amount, string $currency, User $earningUser, string $source, bool $isShadow = false): void
    {
        if ($isShadow) {
            // Add to referrer's shadow balance
            $referrer->incrementShadowBalanceInCurrency($currency, $amount);
        } else {
            // Add to referrer's real balance
            $referrer->incrementBalanceInCurrency($currency, $amount);

            // Update referral earnings tracking
            $field = $currency === 'INR' ? 'referral_earnings_inr' : 'referral_earnings_usd';
            $referrer->increment($field, $amount);
            $referrer->update(['last_referral_at' => now()]);
        }

        // Create earnings record for referrer
        UserEarning::create([
            'user_id' => $referrer->id,
            'amount' => $amount,
            'currency' => $currency,
            'status' => 'approved', // Referral commissions are auto-approved
            'is_shadow' => $isShadow,
            'notes' => "Referral commission from {$earningUser->name} ({$source})" . ($isShadow ? ' [Shadow]' : ''),
            'approved_at' => now(),
        ]);
    }

    /**
     * Update referral record
     */
    protected function updateReferralRecord(Referral $referral, float $amount, string $currency): void
    {
        $field = $currency === 'INR' ? 'amount_inr' : 'amount_usd';
        $referral->increment($field, $amount);

        // Mark as completed if this is first earning
        if ($referral->status === 'pending') {
            $referral->markAsCompleted();
        }
    }

    /**
     * Process multi-tier referrals (Tier 2 & Tier 3)
     */
    protected function processMultiTierReferrals(User $referrer, float $amount, string $currency, User $earningUser, string $source, bool $isShadow = false): void
    {
        // Tier 2: Referrer's referrer
        if ($this->globalSettings->isTier2ReferralsEnabled() && $referrer->referred_by) {
            $tier2Referral = Referral::find($referrer->referred_by);
            if ($tier2Referral && $tier2Referral->isCompleted()) {
                $tier2Rate = $this->globalSettings->getTier2ReferralRate();
                $tier2Amount = $amount * ($tier2Rate / 100);

                if ($tier2Amount > 0) {
                    $tier2Referrer = $tier2Referral->referrer;
                    $this->creditReferrerWallet($tier2Referrer, $tier2Amount, $currency, $earningUser, "Tier 2 - {$source}", $isShadow);
                }
            }
        }

        // Tier 3: Referrer's referrer's referrer
        if ($this->globalSettings->isTier3ReferralsEnabled() && $referrer->referred_by) {
            $tier2Referral = Referral::find($referrer->referred_by);
            if ($tier2Referral && $tier2Referral->referrer && $tier2Referral->referrer->referred_by) {
                $tier3Referral = Referral::find($tier2Referral->referrer->referred_by);
                if ($tier3Referral && $tier3Referral->isCompleted()) {
                    $tier3Rate = $this->globalSettings->getTier3ReferralRate();
                    $tier3Amount = $amount * ($tier3Rate / 100);

                    if ($tier3Amount > 0) {
                        $tier3Referrer = $tier3Referral->referrer;
                        $this->creditReferrerWallet($tier3Referrer, $tier3Amount, $currency, $earningUser, "Tier 3 - {$source}", $isShadow);
                    }
                }
            }
        }
    }

    /**
     * Process signup bonus
     */
    public function processSignupBonus(User $referredUser): bool
    {
        if (!$this->globalSettings->isReferralsEnabled() || !$referredUser->referred_by) {
            return false;
        }

        $referral = Referral::find($referredUser->referred_by);
        if (!$referral) {
            return false;
        }

        $referrer = $referral->referrer;
        $currency = $referredUser->preferred_currency ?? 'INR';
        $bonusAmount = $this->globalSettings->getReferralSignupBonus($currency);

        if ($bonusAmount <= 0) {
            return false;
        }

        try {
            DB::beginTransaction();

            // Credit signup bonus
            $this->creditReferrerWallet($referrer, $bonusAmount, $currency, $referredUser, 'Signup Bonus');

            // Update referral record
            $field = $currency === 'INR' ? 'amount_inr' : 'amount_usd';
            $referral->increment($field, $bonusAmount);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Signup bonus processing failed", [
                'error' => $e->getMessage(),
                'referred_user_id' => $referredUser->id,
                'referrer_id' => $referrer->id
            ]);
            return false;
        }
    }

    /**
     * Process premium upgrade bonus
     */
    public function processPremiumUpgradeBonus(User $referredUser): bool
    {
        if (!$this->globalSettings->isReferralsEnabled() || !$referredUser->referred_by) {
            return false;
        }

        $referral = Referral::find($referredUser->referred_by);
        if (!$referral) {
            return false;
        }

        $referrer = $referral->referrer;
        $currency = $referredUser->preferred_currency ?? 'INR';
        $bonusAmount = $this->globalSettings->getReferralPremiumUpgradeBonus($currency);

        if ($bonusAmount <= 0) {
            return false;
        }

        try {
            DB::beginTransaction();

            // Credit premium upgrade bonus
            $this->creditReferrerWallet($referrer, $bonusAmount, $currency, $referredUser, 'Premium Upgrade Bonus');

            // Update referral record
            $field = $currency === 'INR' ? 'amount_inr' : 'amount_usd';
            $referral->increment($field, $bonusAmount);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Premium upgrade bonus processing failed", [
                'error' => $e->getMessage(),
                'referred_user_id' => $referredUser->id,
                'referrer_id' => $referrer->id
            ]);
            return false;
        }
    }
}
