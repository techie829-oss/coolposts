<?php

namespace App\Services\Payment;

use App\Models\PaymentGateway;
use App\Models\PaymentTransaction;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;
use Illuminate\Support\Facades\Log;

class RazorpayPaymentService implements PaymentServiceInterface
{
    protected PaymentGateway $gateway;
    protected Api $api;
    protected string $keyId;
    protected string $keySecret;
    protected bool $isTestMode;

    public function __construct(PaymentGateway $gateway)
    {
        $this->gateway = $gateway;
        $this->keyId = $gateway->getConfig('key_id');
        $this->keySecret = $gateway->getConfig('key_secret');
        $this->isTestMode = $gateway->isTestMode();

        // Initialize Razorpay API
        $this->api = new Api($this->keyId, $this->keySecret);
    }

    /**
     * Create a Razorpay payment for subscription
     */
    public function createSubscriptionPayment(SubscriptionPlan $plan, array $userData): array
    {
        try {
            $user = User::find($userData['user_id']);
            $currency = strtoupper($userData['currency'] ?? 'INR');
            $amount = $plan->getPrice($userData['currency'] ?? 'INR');

            // Convert amount to paise (smallest currency unit)
            $amountInPaise = (int)($amount * 100);

            // Create Razorpay order
            $orderData = [
                'receipt' => 'order_' . uniqid(),
                'amount' => $amountInPaise,
                'currency' => strtolower($currency),
                'notes' => [
                    'subscription_for' => 'CoolPosts Posts Premium',
                    'plan_name' => $plan->name,
                    'billing_cycle' => $plan->billing_cycle,
                    'user_id' => $user->id,
                ],
                'partial_payment' => false,
            ];

            $order = $this->api->order->create($orderData);

            // Create transaction record
            $transaction = PaymentTransaction::create([
                'user_id' => $user->id,
                'payment_gateway_id' => $this->gateway->id,
                'gateway_transaction_id' => $order->id,
                'status' => 'pending',
                'type' => 'subscription',
                'amount' => $amount,
                'currency' => $currency,
                'gateway_fee' => $this->gateway->getTransactionFee($amount),
                'net_amount' => $amount - $this->gateway->getTransactionFee($amount),
                'payment_method' => 'upi',
                'description' => "Subscription to {$plan->name} ({$plan->billing_cycle})",
                'gateway_response' => [
                    'order_id' => $order->id,
                    'receipt' => $order->receipt,
                    'amount' => $order->amount,
                    'currency' => $order->currency,
                ],
            ]);

            return [
                'success' => true,
                'transaction_id' => $transaction->transaction_id,
                'order_id' => $order->id,
                'key_id' => $this->keyId,
                'amount' => $amount,
                'currency' => $currency,
                'amount_in_paise' => $amountInPaise,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'user_contact' => $user->phone ?? '',
            ];

        } catch (\Exception $e) {
            Log::error('Razorpay payment creation failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Payment creation failed',
                'error_code' => 'unknown',
            ];
        }
    }

    /**
     * Process webhook from Razorpay
     */
    public function processWebhook(array $payload): bool
    {
        try {
            $event = $payload['event'] ?? null;
            $data = $payload['payload']['payment']['entity'] ?? null;

            if (!$event || !$data) {
                return false;
            }

            // Verify webhook signature
            if (!$this->verifyWebhookSignature($payload)) {
                Log::error('Razorpay webhook signature verification failed');
                return false;
            }

            switch ($event) {
                case 'payment.captured':
                    return $this->handlePaymentSuccess($data);

                case 'payment.failed':
                    return $this->handlePaymentFailure($data);

                case 'subscription.activated':
                    return $this->handleSubscriptionActivation($data);

                case 'subscription.cancelled':
                    return $this->handleSubscriptionCancellation($data);
            }

            return true;

        } catch (\Exception $e) {
            Log::error('Razorpay webhook processing failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Verify payment status
     */
    public function verifyPayment(string $paymentId): array
    {
        try {
            $payment = $this->api->payment->fetch($paymentId);

            return [
                'success' => true,
                'status' => $payment->status,
                'amount' => $payment->amount / 100, // Convert from paise
                'currency' => strtoupper($payment->currency),
                'order_id' => $payment->order_id,
                'method' => $payment->method,
                'captured' => $payment->captured,
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'error_code' => 'verification_failed',
            ];
        }
    }

    /**
     * Refund a payment
     */
    public function refundPayment(string $paymentId, float $amount = null): array
    {
        try {
            $refundData = ['payment_id' => $paymentId];

            if ($amount) {
                $refundData['amount'] = (int)($amount * 100); // Convert to paise
            }

            $refund = $this->api->refund->create($refundData);

            return [
                'success' => true,
                'refund_id' => $refund->id,
                'amount' => $refund->amount / 100,
                'status' => $refund->status,
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'error_code' => 'refund_failed',
            ];
        }
    }

    /**
     * Cancel a subscription
     */
    public function cancelSubscription(string $subscriptionId): bool
    {
        try {
            $subscription = $this->api->subscription->fetch($subscriptionId);
            $subscription->cancel();
            return true;

        } catch (\Exception $e) {
            Log::error('Razorpay subscription cancellation failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get payment methods for user
     */
    public function getPaymentMethods(string $customerId): array
    {
        try {
            $customer = $this->api->customer->fetch($customerId);

            return [
                'success' => true,
                'customer' => [
                    'id' => $customer->id,
                    'name' => $customer->name,
                    'email' => $customer->email,
                    'contact' => $customer->contact,
                ],
                'payment_methods' => [], // Razorpay doesn't store payment methods
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Test gateway connectivity
     */
    public function testConnection(): array
    {
        try {
            // Try to retrieve account information
            $account = $this->api->account->fetch();

            return [
                'success' => true,
                'message' => 'Razorpay connection successful',
                'account_id' => $account->id,
                'mode' => $this->isTestMode ? 'test' : 'live',
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'error_code' => 'connection_failed',
            ];
        }
    }

    /**
     * Verify webhook signature
     */
    protected function verifyWebhookSignature(array $payload): bool
    {
        try {
            $webhookSecret = $this->gateway->getConfig('webhook_secret');

            if (!$webhookSecret) {
                Log::warning('Razorpay webhook secret not configured');
                return false;
            }

            $signature = $payload['signature'] ?? '';
            $payloadData = json_encode($payload['payload']);

            $expectedSignature = hash_hmac('sha256', $payloadData, $webhookSecret);

            return hash_equals($expectedSignature, $signature);

        } catch (\Exception $e) {
            Log::error('Razorpay webhook signature verification failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Handle successful payment
     */
    protected function handlePaymentSuccess(array $data): bool
    {
        $transaction = PaymentTransaction::where('gateway_transaction_id', $data['order_id'])->first();

        if ($transaction) {
            $transaction->markAsCompleted($data['id']);
            $this->activateUserSubscription($transaction);
        }

        return true;
    }

    /**
     * Handle failed payment
     */
    protected function handlePaymentFailure(array $data): bool
    {
        $transaction = PaymentTransaction::where('gateway_transaction_id', $data['order_id'])->first();

        if ($transaction) {
            $transaction->markAsFailed('Payment failed on Razorpay');
        }

        return true;
    }

    /**
     * Handle subscription activation
     */
    protected function handleSubscriptionActivation(array $data): bool
    {
        // Handle subscription activation
        return true;
    }

    /**
     * Handle subscription cancellation
     */
    protected function handleSubscriptionCancellation(array $data): bool
    {
        // Handle subscription cancellation
        return true;
    }

    /**
     * Activate user subscription
     */
    protected function activateUserSubscription(PaymentTransaction $transaction): void
    {
        $user = $transaction->user;
        $metadata = $transaction->gateway_response;

        // Create or update subscription
        $subscription = \App\Models\Subscription::updateOrCreate(
            ['user_id' => $user->id],
            [
                'subscription_plan_id' => $metadata['plan_id'] ?? null,
                'status' => 'active',
                'payment_method' => 'razorpay',
                'payment_id' => $transaction->gateway_transaction_id,
                'amount_paid' => $transaction->amount,
                'currency' => $transaction->currency,
                'starts_at' => now(),
                'ends_at' => now()->addDays(30), // Default to 30 days
            ]
        );
    }
}
