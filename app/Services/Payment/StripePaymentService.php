<?php

namespace App\Services\Payment;

use App\Models\PaymentGateway;
use App\Models\PaymentTransaction;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Stripe\Stripe;
use Stripe\Customer;
use Stripe\PaymentIntent;
use Stripe\Subscription;
use Stripe\Refund;
use Stripe\Exception\ApiErrorException;
use Illuminate\Support\Facades\Log;

class StripePaymentService implements PaymentServiceInterface
{
    protected PaymentGateway $gateway;
    protected string $secretKey;
    protected string $publishableKey;
    protected bool $isTestMode;

    public function __construct(PaymentGateway $gateway)
    {
        $this->gateway = $gateway;
        $this->secretKey = $gateway->getConfig('secret_key');
        $this->publishableKey = $gateway->getConfig('publishable_key');
        $this->isTestMode = $gateway->isTestMode();

        // Set Stripe API key
        Stripe::setApiKey($this->secretKey);
    }

    /**
     * Create a payment intent for subscription
     */
    public function createSubscriptionPayment(SubscriptionPlan $plan, array $userData): array
    {
        try {
            $user = User::find($userData['user_id']);
            $currency = strtolower($userData['currency'] ?? 'inr');
            $amount = $plan->getPrice($userData['currency'] ?? 'INR');

            // Create or get Stripe customer
            $customer = $this->getOrCreateCustomer($user);

            // Create payment intent
            $paymentIntent = PaymentIntent::create([
                'amount' => (int) ($amount * 100), // Convert to cents
                'currency' => $currency,
                'customer' => $customer->id,
                'metadata' => [
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'plan_name' => $plan->name,
                    'billing_cycle' => $plan->billing_cycle,
                ],
                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
                'statement_descriptor' => $this->gateway->getConfig('statement_descriptor', 'CoolPosts'),
                'statement_descriptor_suffix' => $this->gateway->getConfig('statement_descriptor_suffix', 'Subscription'),
            ]);

            // Create transaction record
            $transaction = PaymentTransaction::create([
                'user_id' => $user->id,
                'payment_gateway_id' => $this->gateway->id,
                'gateway_transaction_id' => $paymentIntent->id,
                'status' => 'pending',
                'type' => 'subscription',
                'amount' => $amount,
                'currency' => strtoupper($currency),
                'gateway_fee' => $this->gateway->getTransactionFee($amount),
                'net_amount' => $amount - $this->gateway->getTransactionFee($amount),
                'payment_method' => 'card',
                'description' => "Subscription to {$plan->name} ({$plan->billing_cycle})",
                'gateway_response' => [
                    'client_secret' => $paymentIntent->client_secret,
                    'payment_intent_id' => $paymentIntent->id,
                ],
            ]);

            return [
                'success' => true,
                'transaction_id' => $transaction->transaction_id,
                'client_secret' => $paymentIntent->client_secret,
                'publishable_key' => $this->publishableKey,
                'amount' => $amount,
                'currency' => strtoupper($currency),
            ];

        } catch (ApiErrorException $e) {
            Log::error('Stripe payment creation failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'error_code' => $e->getStripeCode(),
            ];
        } catch (\Exception $e) {
            Log::error('Stripe payment creation failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Payment creation failed',
                'error_code' => 'unknown',
            ];
        }
    }

    /**
     * Process webhook from Stripe
     */
    public function processWebhook(array $payload): bool
    {
        try {
            $event = $payload['type'] ?? null;
            $data = $payload['data']['object'] ?? null;

            if (!$event || !$data) {
                return false;
            }

            switch ($event) {
                case 'payment_intent.succeeded':
                    return $this->handlePaymentSuccess($data);

                case 'payment_intent.payment_failed':
                    return $this->handlePaymentFailure($data);

                case 'invoice.payment_succeeded':
                    return $this->handleSubscriptionPayment($data);

                case 'customer.subscription.deleted':
                    return $this->handleSubscriptionCancellation($data);
            }

            return true;

        } catch (\Exception $e) {
            Log::error('Stripe webhook processing failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Verify payment status
     */
    public function verifyPayment(string $paymentId): array
    {
        try {
            $paymentIntent = PaymentIntent::retrieve($paymentId);

            return [
                'success' => true,
                'status' => $paymentIntent->status,
                'amount' => $paymentIntent->amount / 100,
                'currency' => strtoupper($paymentIntent->currency),
                'customer_id' => $paymentIntent->customer,
                'metadata' => $paymentIntent->metadata->toArray(),
            ];

        } catch (ApiErrorException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'error_code' => $e->getStripeCode(),
            ];
        }
    }

    /**
     * Refund a payment
     */
    public function refundPayment(string $paymentId, float $amount = null): array
    {
        try {
            $refundData = ['payment_intent' => $paymentId];

            if ($amount) {
                $refundData['amount'] = (int) ($amount * 100);
            }

            $refund = Refund::create($refundData);

            return [
                'success' => true,
                'refund_id' => $refund->id,
                'amount' => $refund->amount / 100,
                'status' => $refund->status,
            ];

        } catch (ApiErrorException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'error_code' => $e->getStripeCode(),
            ];
        }
    }

    /**
     * Cancel a subscription
     */
    public function cancelSubscription(string $subscriptionId): bool
    {
        try {
            $subscription = Subscription::retrieve($subscriptionId);
            $subscription->cancel();
            return true;

        } catch (ApiErrorException $e) {
            Log::error('Stripe subscription cancellation failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get payment methods for user
     */
    public function getPaymentMethods(string $customerId): array
    {
        try {
            $customer = Customer::retrieve($customerId);
            $paymentMethods = \Stripe\PaymentMethod::all([
                'customer' => $customerId,
                'type' => 'card',
            ]);

            return [
                'success' => true,
                'payment_methods' => $paymentMethods->data,
            ];

        } catch (ApiErrorException $e) {
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
            $account = \Stripe\Account::retrieve();

            return [
                'success' => true,
                'message' => 'Connection successful',
                'account_id' => $account->id,
                'charges_enabled' => $account->charges_enabled,
                'payouts_enabled' => $account->payouts_enabled,
            ];

        } catch (ApiErrorException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'error_code' => $e->getStripeCode(),
            ];
        }
    }

    /**
     * Get or create Stripe customer
     */
    protected function getOrCreateCustomer(User $user): Customer
    {
        // Check if user already has a Stripe customer ID
        if ($user->stripe_customer_id) {
            try {
                return Customer::retrieve($user->stripe_customer_id);
            } catch (ApiErrorException $e) {
                // Customer not found, create new one
            }
        }

        // Create new customer
        $customer = Customer::create([
            'email' => $user->email,
            'name' => $user->name,
            'metadata' => [
                'user_id' => $user->id,
            ],
        ]);

        // Update user with Stripe customer ID
        $user->update(['stripe_customer_id' => $customer->id]);

        return $customer;
    }

    /**
     * Handle successful payment
     */
    protected function handlePaymentSuccess(array $data): bool
    {
        $transaction = PaymentTransaction::where('gateway_transaction_id', $data['id'])->first();

        if ($transaction) {
            $transaction->markAsCompleted($data['id']);

            // Update user subscription status
            $this->activateUserSubscription($transaction);
        }

        return true;
    }

    /**
     * Handle failed payment
     */
    protected function handlePaymentFailure(array $data): bool
    {
        $transaction = PaymentTransaction::where('gateway_transaction_id', $data['id'])->first();

        if ($transaction) {
            $transaction->markAsFailed($data['last_payment_error']['message'] ?? 'Payment failed');
        }

        return true;
    }

    /**
     * Handle subscription payment
     */
    protected function handleSubscriptionPayment(array $data): bool
    {
        // Handle recurring subscription payments
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
                'payment_method' => 'stripe',
                'payment_id' => $transaction->gateway_transaction_id,
                'amount_paid' => $transaction->amount,
                'currency' => $transaction->currency,
                'starts_at' => now(),
                'ends_at' => now()->addDays(30), // Default to 30 days
            ]
        );
    }
}
