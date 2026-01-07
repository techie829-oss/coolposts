<?php

namespace App\Services\Payment;

use App\Models\PaymentGateway;
use InvalidArgumentException;

class PaymentServiceFactory
{
    /**
     * Create payment service instance based on gateway
     */
    public static function create(PaymentGateway $gateway): PaymentServiceInterface
    {
        return match ($gateway->slug) {
            'stripe' => new StripePaymentService($gateway),
            'paypal' => new PayPalPaymentService($gateway),
            'paytm' => new PaytmPaymentService($gateway),
            'razorpay' => new RazorpayPaymentService($gateway),
            default => throw new InvalidArgumentException("Unsupported payment gateway: {$gateway->slug}"),
        };
    }

    /**
     * Get available payment gateways
     */
    public static function getAvailableGateways(): array
    {
        return [
            'stripe' => [
                'name' => 'Stripe',
                'description' => 'Global credit card and digital wallet support',
                'currencies' => ['USD', 'EUR', 'GBP', 'CAD', 'AUD', 'INR'],
                'features' => ['subscriptions', 'recurring_billing', '3d_secure', 'sca_compliance'],
            ],
            'paypal' => [
                'name' => 'PayPal',
                'description' => 'International payment methods',
                'currencies' => ['USD', 'EUR', 'GBP', 'CAD', 'AUD', 'INR'],
                'features' => ['subscriptions', 'recurring_payments', 'local_methods'],
            ],
            'paytm' => [
                'name' => 'Paytm',
                'description' => 'Popular Indian UPI and wallet support',
                'currencies' => ['INR'],
                'features' => ['upi', 'wallet', 'netbanking', 'cards'],
            ],
            'razorpay' => [
                'name' => 'Razorpay',
                'description' => 'Modern Indian payment gateway',
                'currencies' => ['INR'],
                'features' => ['upi', 'cards', 'netbanking', 'wallets'],
            ],
        ];
    }

    /**
     * Check if gateway supports currency
     */
    public static function supportsCurrency(string $gatewaySlug, string $currency): bool
    {
        $gateways = self::getAvailableGateways();
        
        if (!isset($gateways[$gatewaySlug])) {
            return false;
        }

        return in_array(strtoupper($currency), $gateways[$gatewaySlug]['currencies']);
    }

    /**
     * Get recommended gateway for currency
     */
    public static function getRecommendedGateway(string $currency): ?string
    {
        $currency = strtoupper($currency);
        
        // For INR, prefer Indian gateways
        if ($currency === 'INR') {
            return 'razorpay'; // Razorpay is generally preferred for INR
        }
        
        // For USD and other currencies, prefer Stripe
        if (in_array($currency, ['USD', 'EUR', 'GBP', 'CAD', 'AUD'])) {
            return 'stripe';
        }
        
        return null;
    }

    /**
     * Get gateway features
     */
    public static function getGatewayFeatures(string $gatewaySlug): array
    {
        $gateways = self::getAvailableGateways();
        
        return $gateways[$gatewaySlug]['features'] ?? [];
    }
}
