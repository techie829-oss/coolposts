<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SubscriptionPlan;

class SubscriptionPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing plans
        // Disable foreign key checks based on database driver
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        } elseif ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        }

        SubscriptionPlan::truncate();

        // Re-enable foreign key checks
        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } elseif ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        }

        // Basic Plan (Monthly)
        SubscriptionPlan::create([
            'name' => 'Basic',
            'slug' => 'basic',
            'description' => 'Perfect for getting started with link monetization',
            'price_inr' => 199.00,
            'price_usd' => 2.99,
            'billing_cycle' => 'monthly',
            'duration_days' => 30,
            'features' => [
                'No waiting time for links',
                'Basic analytics dashboard',
                'Email support',
                'Standard earning rates',
                'Up to 100 links per month',
            ],
            'is_active' => true,
            'is_popular' => false,
            'sort_order' => 1,
        ]);

        // Basic Plan (Yearly)
        SubscriptionPlan::create([
            'name' => 'Basic',
            'slug' => 'basic',
            'description' => 'Perfect for getting started with link monetization',
            'price_inr' => 1999.00,
            'price_usd' => 29.99,
            'billing_cycle' => 'yearly',
            'duration_days' => 365,
            'features' => [
                'No waiting time for links',
                'Basic analytics dashboard',
                'Email support',
                'Standard earning rates',
                'Up to 100 links per month',
                '2 months free (save 17%)',
            ],
            'is_active' => true,
            'is_popular' => false,
            'sort_order' => 2,
        ]);

        // Pro Plan (Monthly)
        SubscriptionPlan::create([
            'name' => 'Pro',
            'slug' => 'pro',
            'description' => 'Advanced features for serious content creators',
            'price_inr' => 499.00,
            'price_usd' => 6.99,
            'billing_cycle' => 'monthly',
            'duration_days' => 30,
            'features' => [
                'No waiting time for links',
                'Advanced analytics dashboard',
                'Priority email support',
                '1.5x earning multiplier for short ads',
                '2.0x earning multiplier for long ads',
                'Unlimited links per month',
                'Custom link branding',
                'A/B testing for links',
                'Advanced click tracking',
                'Export analytics data',
            ],
            'is_active' => true,
            'is_popular' => true,
            'sort_order' => 3,
        ]);

        // Pro Plan (Yearly)
        SubscriptionPlan::create([
            'name' => 'Pro',
            'slug' => 'pro',
            'description' => 'Advanced features for serious content creators',
            'price_inr' => 4999.00,
            'price_usd' => 69.99,
            'billing_cycle' => 'yearly',
            'duration_days' => 365,
            'features' => [
                'No waiting time for links',
                'Advanced analytics dashboard',
                'Priority email support',
                '1.5x earning multiplier for short ads',
                '2.0x earning multiplier for long ads',
                'Unlimited links per month',
                'Custom link branding',
                'A/B testing for links',
                'Advanced click tracking',
                'Export analytics data',
                '2 months free (save 17%)',
            ],
            'is_active' => true,
            'is_popular' => true,
            'sort_order' => 4,
        ]);

        // Enterprise Plan (Monthly)
        SubscriptionPlan::create([
            'name' => 'Enterprise',
            'slug' => 'enterprise',
            'description' => 'Maximum features for professional teams and businesses',
            'price_inr' => 999.00,
            'price_usd' => 14.99,
            'billing_cycle' => 'monthly',
            'duration_days' => 30,
            'features' => [
                'No waiting time for links',
                'Enterprise analytics dashboard',
                '24/7 priority support',
                '2.0x earning multiplier for short ads',
                '3.0x earning multiplier for long ads',
                'Unlimited links per month',
                'Custom link branding',
                'Advanced A/B testing',
                'Real-time analytics',
                'API access',
                'White-label options',
                'Team collaboration tools',
                'Custom integrations',
                'Dedicated account manager',
            ],
            'is_active' => true,
            'is_popular' => false,
            'sort_order' => 5,
        ]);

        // Enterprise Plan (Yearly)
        SubscriptionPlan::create([
            'name' => 'Enterprise',
            'slug' => 'enterprise',
            'description' => 'Maximum features for professional teams and businesses',
            'price_inr' => 9999.00,
            'price_usd' => 149.99,
            'billing_cycle' => 'yearly',
            'duration_days' => 365,
            'features' => [
                'No waiting time for links',
                'Enterprise analytics dashboard',
                '24/7 priority support',
                '2.0x earning multiplier for short ads',
                '3.0x earning multiplier for long ads',
                'Unlimited links per month',
                'Custom link branding',
                'Advanced A/B testing',
                'Real-time analytics',
                'API access',
                'White-label options',
                'Team collaboration tools',
                'Custom integrations',
                'Dedicated account manager',
                '2 months free (save 17%)',
            ],
            'is_active' => true,
            'is_popular' => false,
            'sort_order' => 6,
        ]);

        $this->command->info('Subscription plans seeded successfully!');
        $this->command->info('Created 6 subscription plans (Basic, Pro, Enterprise × Monthly/Yearly)');
    }
}
