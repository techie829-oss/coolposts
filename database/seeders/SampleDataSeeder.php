<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Link;
use App\Models\LinkClick;
use App\Models\UserEarning;
use App\Models\Referral;
use App\Models\SubscriptionPlan;
use App\Models\PaymentGateway;
use Carbon\Carbon;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->seedSubscriptionPlans();
        $this->seedPaymentGateways();
        $this->seedSampleUsers();
        $this->seedSampleLinks();
        $this->seedSampleClicks();
        $this->seedSampleEarnings();
        $this->seedSampleReferrals();
    }

    protected function seedSubscriptionPlans(): void
    {
        $plans = [
            [
                'name' => 'Basic',
                'slug' => 'basic',
                'description' => 'Perfect for beginners',
                'billing_cycle' => 'monthly',
                'duration_days' => 30,
                'price_inr' => 299.00,
                'price_usd' => 3.99,
                'features' => ['Basic analytics', 'Standard support', 'Up to 100 links'],
                'is_active' => true,
                'is_popular' => false,
                'sort_order' => 1,
            ],
            [
                'name' => 'Pro',
                'slug' => 'pro',
                'description' => 'Best for professionals',
                'billing_cycle' => 'monthly',
                'duration_days' => 30,
                'price_inr' => 599.00,
                'price_usd' => 7.99,
                'features' => ['Advanced analytics', 'Priority support', 'Unlimited links', 'Custom domains'],
                'is_active' => true,
                'is_popular' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Enterprise',
                'slug' => 'enterprise',
                'description' => 'For large organizations',
                'billing_cycle' => 'monthly',
                'duration_days' => 30,
                'price_inr' => 1499.00,
                'price_usd' => 19.99,
                'features' => ['Enterprise analytics', '24/7 support', 'Unlimited links', 'Custom domains', 'API access'],
                'is_active' => true,
                'is_popular' => false,
                'sort_order' => 3,
            ],
        ];

        foreach ($plans as $plan) {
            SubscriptionPlan::updateOrCreate(
                ['slug' => $plan['slug'], 'billing_cycle' => $plan['billing_cycle']],
                $plan
            );
        }

        // Add yearly plans
        foreach ($plans as $plan) {
            $yearlyPlan = $plan;
            $yearlyPlan['billing_cycle'] = 'yearly';
            $yearlyPlan['duration_days'] = 365;
            $yearlyPlan['price_inr'] = $plan['price_inr'] * 10; // 2 months free
            $yearlyPlan['price_usd'] = $plan['price_usd'] * 10; // 2 months free
            $yearlyPlan['slug'] = $plan['slug'] . '-yearly';

            SubscriptionPlan::updateOrCreate(
                ['slug' => $yearlyPlan['slug'], 'billing_cycle' => $yearlyPlan['billing_cycle']],
                $yearlyPlan
            );
        }
    }

    protected function seedPaymentGateways(): void
    {
        $gateways = [
            [
                'name' => 'Stripe',
                'slug' => 'stripe',
                'description' => 'Credit card payments',
                'is_active' => true,
                'is_test_mode' => true,
                'environment' => 'test',
                'config' => [
                    'publishable_key' => 'pk_test_...',
                    'secret_key' => 'sk_test_...',
                    'webhook_secret' => 'whsec_...',
                ],
                'supported_currencies' => ['INR', 'USD'],
                'supported_countries' => ['IN', 'US', 'GB', 'CA'],
                'transaction_fee_percentage' => 2.9,
                'transaction_fee_fixed' => 0.30,
            ],
            [
                'name' => 'PayPal',
                'slug' => 'paypal',
                'description' => 'PayPal payments',
                'is_active' => true,
                'is_test_mode' => true,
                'environment' => 'sandbox',
                'config' => [
                    'client_id' => 'sb-...',
                    'client_secret' => 'sb-...',
                    'webhook_id' => '...',
                ],
                'supported_currencies' => ['INR', 'USD'],
                'supported_countries' => ['IN', 'US', 'GB', 'CA'],
                'transaction_fee_percentage' => 3.49,
                'transaction_fee_fixed' => 0.49,
            ],
            [
                'name' => 'Paytm',
                'slug' => 'paytm',
                'description' => 'Indian payment gateway',
                'is_active' => true,
                'is_test_mode' => true,
                'environment' => 'test',
                'config' => [
                    'merchant_id' => 'TEST_MERCHANT_ID',
                    'merchant_key' => 'TEST_MERCHANT_KEY',
                    'website' => 'WEBSTAGING',
                ],
                'supported_currencies' => ['INR'],
                'supported_countries' => ['IN'],
                'transaction_fee_percentage' => 2.0,
                'transaction_fee_fixed' => 0.00,
            ],
            [
                'name' => 'Razorpay',
                'slug' => 'razorpay',
                'description' => 'Indian payment gateway',
                'is_active' => true,
                'is_test_mode' => true,
                'environment' => 'test',
                'config' => [
                    'key_id' => 'rzp_test_...',
                    'key_secret' => '...',
                    'webhook_secret' => '...',
                ],
                'supported_currencies' => ['INR'],
                'supported_countries' => ['IN'],
                'transaction_fee_percentage' => 2.0,
                'transaction_fee_fixed' => 0.00,
            ],
        ];

        foreach ($gateways as $gateway) {
            PaymentGateway::updateOrCreate(
                ['slug' => $gateway['slug']],
                $gateway
            );
        }
    }

    protected function seedSampleUsers(): void
    {
        // Create demo users if they don't exist
        $users = [
            [
                'name' => 'Demo User 1',
                'email' => 'demo1@example.com',
                'password' => bcrypt('password'),
                'role' => 'user',
                'currency' => 'INR',
                'balance_inr' => 1500.00,
                'balance_usd' => 0.00,
            ],
            [
                'name' => 'Demo User 2',
                'email' => 'demo2@example.com',
                'password' => bcrypt('password'),
                'role' => 'user',
                'currency' => 'USD',
                'balance_inr' => 0.00,
                'balance_usd' => 25.00,
            ],
        ];

        foreach ($users as $userData) {
            User::updateOrCreate(
                ['email' => $userData['email']],
                $userData
            );
        }

        // Generate referral codes for all users
        User::all()->each(function ($user) {
            if (!$user->referral_code) {
                $user->generateReferralCode();
            }
        });
    }

    protected function seedSampleLinks(): void
    {
        $users = User::where('role', 'user')->get();

        foreach ($users as $user) {
            $links = [
                [
                    'title' => 'My Awesome Blog Post',
                    'original_url' => 'https://example.com/blog/awesome-post',
                    'short_code' => 'blog' . $user->id,
                    'ad_type' => 'short_ads',
                    'ad_duration' => 15,
                    'is_active' => true,
                    'earnings_per_click_inr' => 2.50,
                    'earnings_per_click_usd' => 0.03,
                ],
                [
                    'title' => 'Product Review',
                    'original_url' => 'https://example.com/products/review',
                    'short_code' => 'prod' . $user->id,
                    'ad_type' => 'long_ads',
                    'ad_duration' => 30,
                    'is_active' => true,
                    'earnings_per_click_inr' => 5.00,
                    'earnings_per_click_usd' => 0.06,
                ],
                [
                    'title' => 'Tutorial Guide',
                    'original_url' => 'https://example.com/tutorials/guide',
                    'short_code' => 'tut' . $user->id,
                    'ad_type' => 'no_ads',
                    'ad_duration' => 0,
                    'is_active' => true,
                    'earnings_per_click_inr' => 1.00,
                    'earnings_per_click_usd' => 0.01,
                ],
            ];

            foreach ($links as $linkData) {
                Link::updateOrCreate(
                    ['short_code' => $linkData['short_code']],
                    array_merge($linkData, ['user_id' => $user->id])
                );
            }
        }
    }

    protected function seedSampleClicks(): void
    {
        $links = Link::all();

        foreach ($links as $link) {
            // Generate random clicks for the last 30 days
            $days = 30;
            for ($i = 0; $i < $days; $i++) {
                $date = now()->subDays($i);
                $clicksCount = rand(0, 15); // Random clicks per day

                for ($j = 0; $j < $clicksCount; $j++) {
                    LinkClick::create([
                        'link_id' => $link->id,
                        'ip_address' => '127.0.0.' . rand(1, 255),
                        'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                        'is_unique' => rand(0, 1),
                        'clicked_at' => $date->copy()->addMinutes(rand(0, 1440)),
                        'created_at' => $date->copy()->addMinutes(rand(0, 1440)),
                    ]);
                }
            }
        }
    }

    protected function seedSampleEarnings(): void
    {
        $users = User::where('role', 'user')->get();

        foreach ($users as $user) {
            $links = $user->links;
            $currency = $user->preferred_currency;

            // Generate earnings for the last 30 days
            $days = 30;
            for ($i = 0; $i < $days; $i++) {
                $date = now()->subDays($i);
                $dailyEarnings = 0;

                foreach ($links as $link) {
                    $clicks = $link->clicks()->whereDate('created_at', $date)->count();
                    $earningsPerClick = $currency === 'INR' ? $link->earnings_per_click_inr : $link->earnings_per_click_usd;
                    $dailyEarnings += $clicks * $earningsPerClick;
                }

                if ($dailyEarnings > 0) {
                    UserEarning::create([
                        'user_id' => $user->id,
                        'link_id' => $links->first()->id,
                        'amount' => $dailyEarnings,
                        'amount_inr' => $currency === 'INR' ? $dailyEarnings : 0,
                        'amount_usd' => $currency === 'USD' ? $dailyEarnings : 0,
                        'currency' => $currency,
                        'status' => rand(0, 1) ? 'approved' : 'pending',
                        'created_at' => $date,
                    ]);
                }
            }
        }
    }

    protected function seedSampleReferrals(): void
    {
        $users = User::where('role', 'user')->get();

        if ($users->count() >= 2) {
            $referrer = $users->first();
            $referred = $users->last();

            // Create a referral relationship
            Referral::updateOrCreate(
                ['referred_id' => $referred->id],
                [
                    'referrer_id' => $referrer->id,
                    'referred_id' => $referred->id,
                    'referral_code' => $referrer->referral_code,
                    'status' => 'completed',
                    'currency' => $referred->preferred_currency,
                    'commission_rate' => 10.0,
                    'amount_inr' => $referred->preferred_currency === 'INR' ? 100.00 : 0,
                    'amount_usd' => $referred->preferred_currency === 'USD' ? 1.50 : 0,
                    'completed_at' => now()->subDays(5),
                    'expires_at' => now()->addDays(25),
                ]
            );

            // Update the referred user
            $referred->update(['referred_by' => Referral::where('referred_id', $referred->id)->first()->id]);
        }
    }
}
