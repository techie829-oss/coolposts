<?php

namespace App\Services\Payment;

use App\Models\PaymentGateway;
use App\Models\PaymentTransaction;
use App\Models\SubscriptionPlan;

interface PaymentServiceInterface
{
    /**
     * Initialize the payment service with gateway configuration
     */
    public function __construct(PaymentGateway $gateway);

    /**
     * Create a payment intent/order for subscription
     */
    public function createSubscriptionPayment(SubscriptionPlan $plan, array $userData): array;

    /**
     * Process payment confirmation from webhook
     */
    public function processWebhook(array $payload): bool;

    /**
     * Verify payment status
     */
    public function verifyPayment(string $paymentId): array;

    /**
     * Refund a payment
     */
    public function refundPayment(string $paymentId, float $amount = null): array;

    /**
     * Cancel a subscription
     */
    public function cancelSubscription(string $subscriptionId): bool;

    /**
     * Get payment methods for user
     */
    public function getPaymentMethods(string $customerId): array;

    /**
     * Test gateway connectivity
     */
    public function testConnection(): array;
}
