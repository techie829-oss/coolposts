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
        Schema::create('global_settings', function (Blueprint $table) {
            $table->id();

            // 3-Tier Monetization Rates (INR)
            $table->decimal('no_ads_rate_inr', 8, 4)->default(0.00)->comment('No ads - No earnings');
            $table->decimal('short_ads_rate_inr', 8, 4)->default(0.50)->comment('Short ads (10-30s) - Basic rate');
            $table->decimal('long_ads_rate_inr', 8, 4)->default(1.00)->comment('Long ads (30s-1min) - Premium rate');

            // 3-Tier Monetization Rates (USD)
            $table->decimal('no_ads_rate_usd', 8, 4)->default(0.00)->comment('No ads - No earnings');
            $table->decimal('short_ads_rate_usd', 8, 4)->default(0.006)->comment('Short ads (10-30s) - Basic rate');
            $table->decimal('long_ads_rate_usd', 8, 4)->default(0.012)->comment('Long ads (30s-1min) - Premium rate');

            // Ad Duration Settings
            $table->integer('short_ads_min_duration')->default(10)->comment('Minimum duration for short ads (seconds)');
            $table->integer('short_ads_max_duration')->default(30)->comment('Maximum duration for short ads (seconds)');
            $table->integer('long_ads_min_duration')->default(30)->comment('Minimum duration for long ads (seconds)');
            $table->integer('long_ads_max_duration')->default(60)->comment('Maximum duration for long ads (seconds)');

            // Premium Subscription Settings
            $table->decimal('premium_monthly_price_inr', 8, 2)->default(299.00)->comment('Monthly premium subscription price (INR)');
            $table->decimal('premium_monthly_price_usd', 8, 2)->default(3.99)->comment('Monthly premium subscription price (USD)');
            $table->decimal('premium_yearly_price_inr', 8, 2)->default(2999.00)->comment('Yearly premium subscription price (INR)');
            $table->decimal('premium_yearly_price_usd', 8, 2)->default(39.99)->comment('Yearly premium subscription price (USD)');

            // Premium Benefits (Multipliers)
            $table->decimal('premium_short_ads_multiplier', 5, 2)->default(1.5)->comment('Premium users get 1.5x earnings for short ads');
            $table->decimal('premium_long_ads_multiplier', 5, 2)->default(2.0)->comment('Premium users get 2.0x earnings for long ads');

            // Withdrawal Settings
            $table->decimal('min_withdrawal_inr', 8, 2)->default(100.00)->comment('Minimum withdrawal amount (INR)');
            $table->decimal('min_withdrawal_usd', 8, 2)->default(1.00)->comment('Minimum withdrawal amount (USD)');
            $table->decimal('withdrawal_fee_percentage', 5, 2)->default(2.50)->comment('Withdrawal fee percentage');

            // System Settings
            $table->boolean('maintenance_mode')->default(false)->comment('Maintenance mode toggle');
            $table->text('maintenance_message')->nullable()->comment('Maintenance mode message');
            $table->boolean('new_registrations')->default(true)->comment('Allow new user registrations');
            $table->boolean('link_creation_enabled')->default(true)->comment('Allow users to create new links');

            // Analytics & Tracking
            $table->boolean('enable_click_tracking')->default(true)->comment('Enable click tracking');
            $table->boolean('enable_geo_tracking')->default(true)->comment('Enable geographic tracking');
            $table->boolean('enable_device_tracking')->default(true)->comment('Enable device tracking');

            // Blog Monetization Settings
            $table->string('default_blog_monetization_type')->default('time_based')->comment('Default blog monetization type');
            $table->string('default_blog_ad_type')->default('banner_ads')->comment('Default blog ad type');

            // Default earning rates for blog posts (INR)
            $table->decimal('default_blog_earning_rate_less_2min_inr', 8, 4)->default(0.1000)->comment('Earnings for <2min views (INR)');
            $table->decimal('default_blog_earning_rate_2_5min_inr', 8, 4)->default(0.2500)->comment('Earnings for 2-5min views (INR)');
            $table->decimal('default_blog_earning_rate_more_5min_inr', 8, 4)->default(0.5000)->comment('Earnings for >5min views (INR)');

            // Default earning rates for blog posts (USD)
            $table->decimal('default_blog_earning_rate_less_2min_usd', 8, 4)->default(0.0010)->comment('Earnings for <2min views (USD)');
            $table->decimal('default_blog_earning_rate_2_5min_usd', 8, 4)->default(0.0030)->comment('Earnings for 2-5min views (USD)');
            $table->decimal('default_blog_earning_rate_more_5min_usd', 8, 4)->default(0.0060)->comment('Earnings for >5min views (USD)');

            // AdSense Settings
            $table->boolean('adsense_enabled')->default(false)->comment('Enable Google AdSense');
            $table->string('adsense_client_id')->nullable()->comment('AdSense Client ID');
            $table->string('adsense_client_secret')->nullable()->comment('AdSense Client Secret');
            $table->string('adsense_refresh_token')->nullable()->comment('AdSense Refresh Token');
            $table->string('adsense_account_id')->nullable()->comment('AdSense Account ID');
            $table->string('adsense_ad_unit_id')->nullable()->comment('AdSense Ad Unit ID');

            // AdMob Settings
            $table->boolean('admob_enabled')->default(false)->comment('Enable Google AdMob');
            $table->string('admob_client_id')->nullable()->comment('AdMob Client ID');
            $table->string('admob_client_secret')->nullable()->comment('AdMob Client Secret');
            $table->string('admob_refresh_token')->nullable()->comment('AdMob Refresh Token');
            $table->string('admob_account_id')->nullable()->comment('AdMob Account ID');
            $table->string('admob_app_id')->nullable()->comment('AdMob App ID');
            $table->string('admob_banner_unit_id')->nullable()->comment('AdMob Banner Unit ID');

            // Facebook Ads Settings
            $table->boolean('facebook_enabled')->default(false)->comment('Enable Facebook Ads');
            $table->string('facebook_app_id')->nullable()->comment('Facebook App ID');
            $table->string('facebook_ad_unit_id')->nullable()->comment('Facebook Ad Unit ID');

            // Ad Network General Settings
            $table->string('primary_ad_network')->default('adsense')->comment('Primary ad network');
            $table->json('ad_network_fallback_order')->nullable()->comment('Ad network fallback order');
            $table->boolean('ad_network_fallback_enabled')->default(true)->comment('Enable ad network fallback');
            $table->decimal('ad_network_revenue_share', 5, 4)->default(0.7000)->comment('Ad network revenue share (0-1)');
            $table->decimal('default_cpm_rate', 8, 2)->default(2.50)->comment('Default CPM rate');
            $table->integer('revenue_share_percentage')->default(70)->comment('Revenue share percentage');

            // Feature Toggles
            $table->boolean('earnings_enabled')->default(false)->comment('Enable earnings feature');
            $table->boolean('monetization_enabled')->default(true)->comment('Enable monetization feature');
            $table->boolean('ads_enabled')->default(true)->comment('Enable ads feature');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('global_settings');
    }
};
