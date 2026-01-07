<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('global_settings', function (Blueprint $table) {
            // Referral System Settings
            $table->boolean('referrals_enabled')->default(false);
            $table->decimal('referral_signup_bonus_inr', 10, 4)->default(5.00);
            $table->decimal('referral_signup_bonus_usd', 10, 4)->default(0.06);

            // Commission Type: 'percentage' or 'flat'
            $table->string('referral_commission_type')->default('percentage');
            $table->decimal('referral_commission_rate', 5, 2)->default(10.00); // Percentage
            $table->decimal('referral_commission_flat_inr', 10, 4)->default(1.00); // Flat INR
            $table->decimal('referral_commission_flat_usd', 10, 4)->default(0.012); // Flat USD

            // Commission Rules
            $table->decimal('referral_minimum_earnings', 10, 4)->default(10.00);
            $table->integer('referral_commission_duration')->default(30); // Days
            $table->integer('referral_max_referrals_per_user')->default(100);

            // Premium Upgrade Bonus
            $table->decimal('referral_premium_upgrade_bonus_inr', 10, 4)->default(50.00);
            $table->decimal('referral_premium_upgrade_bonus_usd', 10, 4)->default(0.60);

            // Multi-tier Referral System
            $table->boolean('referral_tier_2_enabled')->default(false);
            $table->decimal('referral_tier_2_rate', 5, 2)->default(5.00);
            $table->boolean('referral_tier_3_enabled')->default(false);
            $table->decimal('referral_tier_3_rate', 5, 2)->default(2.00);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('global_settings', function (Blueprint $table) {
            $table->dropColumn([
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
            ]);
        });
    }
};
