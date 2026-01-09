<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\PaymentGateway;

class PaymentGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        } elseif ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        }

        PaymentGateway::truncate();

        if ($driver === 'mysql') {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        } elseif ($driver === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        }

        // Stripe Gateway
        PaymentGateway::create([
            'name' => 'Stripe',
            'slug' => 'stripe',
            'description' => 'Accept credit cards, debit cards, and digital wallets worldwide',
            'is_active' => false, // Disabled by default, admin can enable
            'is_test_mode' => true,
            'environment' => 'test',
            'config' => [
                'publishable_key' => '',
                'secret_key' => '',
                'webhook_secret' => '',
                'statement_descriptor' => 'CoolHax Posts',
                'statement_descriptor_suffix' => 'Subscription',
                'tax_behavior' => 'exclusive',
                'automatic_payment_methods' => true,
                'payment_method_types' => ['card', 'sepa_debit', 'sofort'],
            ],
            'supported_currencies' => ['USD', 'EUR', 'GBP', 'CAD', 'AUD', 'INR'],
            'supported_countries' => ['US', 'CA', 'GB', 'AU', 'DE', 'FR', 'IN'],
            'transaction_fee_percentage' => 2.9,
            'transaction_fee_fixed' => 0.30,
            'webhook_url' => '/webhooks/stripe',
            'sort_order' => 1,
        ]);

        // PayPal Gateway
        PaymentGateway::create([
            'name' => 'PayPal',
            'slug' => 'paypal',
            'description' => 'Accept PayPal, credit cards, and local payment methods',
            'is_active' => false, // Disabled by default, admin can enable
            'is_test_mode' => true,
            'environment' => 'test',
            'config' => [
                'client_id' => '',
                'client_secret' => '',
                'webhook_id' => '',
                'brand_name' => 'CoolHax Posts',
                'landing_page' => 'BILLING',
                'user_action' => 'SUBSCRIBE_NOW',
                'payment_method' => 'PAYPAL',
                'intent' => 'SUBSCRIPTION',
            ],
            'supported_currencies' => ['USD', 'EUR', 'GBP', 'CAD', 'AUD', 'INR'],
            'supported_countries' => ['US', 'CA', 'GB', 'AU', 'DE', 'FR', 'IN'],
            'transaction_fee_percentage' => 3.49,
            'transaction_fee_fixed' => 0.49,
            'webhook_url' => '/webhooks/paypal',
            'sort_order' => 2,
        ]);

        // Paytm Gateway
        PaymentGateway::create([
            'name' => 'Paytm',
            'slug' => 'paytm',
            'description' => 'Popular Indian payment gateway supporting UPI, cards, and wallets',
            'is_active' => false, // Disabled by default, admin can enable
            'is_test_mode' => true,
            'environment' => 'test',
            'config' => [
                'merchant_id' => '',
                'merchant_key' => '',
                'website' => 'WEBSTAGING',
                'industry_type' => 'Retail',
                'channel_id' => 'WEB',
                'payment_mode_enabled' => ['UPI', 'CARD', 'NETBANKING', 'WALLET'],
                'emi_enabled' => false,
                'subs_enabled' => true,
                'preauth_enabled' => false,
            ],
            'supported_currencies' => ['INR'],
            'supported_countries' => ['IN'],
            'transaction_fee_percentage' => 2.0,
            'transaction_fee_fixed' => 0.00,
            'webhook_url' => '/webhooks/paytm',
            'sort_order' => 3,
        ]);

        // Razorpay Gateway (Popular Indian alternative)
        PaymentGateway::create([
            'name' => 'Razorpay',
            'slug' => 'razorpay',
            'description' => 'Modern Indian payment gateway with excellent UPI and card support',
            'is_active' => false, // Disabled by default, admin can enable
            'is_test_mode' => true,
            'environment' => 'test',
            'config' => [
                'key_id' => '',
                'key_secret' => '',
                'webhook_secret' => '',
                'prefill' => [
                    'name' => '',
                    'email' => '',
                    'contact' => '',
                ],
                'notes' => [
                    'subscription_for' => 'CoolHax Posts Premium',
                ],
                'theme' => [
                    'color' => '#3399cc',
                ],
            ],
            'supported_currencies' => ['INR'],
            'supported_countries' => ['IN'],
            'transaction_fee_percentage' => 2.0,
            'transaction_fee_fixed' => 0.00,
            'webhook_url' => '/webhooks/razorpay',
            'sort_order' => 4,
        ]);

        $this->command->info('Payment gateways seeded successfully!');
        $this->command->info('Created 4 payment gateways: Stripe, PayPal, Paytm, Razorpay');
        $this->command->info('All gateways are disabled by default - enable them from admin panel');
    }
}
