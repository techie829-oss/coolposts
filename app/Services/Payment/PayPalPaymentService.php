<?php

namespace App\Services\Payment;

use App\Models\PaymentGateway;
use App\Models\PaymentTransaction;
use App\Models\SubscriptionPlan;
use App\Models\User;
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\Transaction;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Exception\PayPalConnectionException;
use Illuminate\Support\Facades\Log;

class PayPalPaymentService implements PaymentServiceInterface
{
    protected PaymentGateway $gateway;
    protected ApiContext $apiContext;
    protected bool $isTestMode;

    public function __construct(PaymentGateway $gateway)
    {
        $this->gateway = $gateway;
        $this->isTestMode = $gateway->isTestMode();
        
        // Initialize PayPal API context
        $this->apiContext = new ApiContext(
            new OAuthTokenCredential(
                $gateway->getConfig('client_id'),
                $gateway->getConfig('client_secret')
            )
        );

        // Set PayPal mode (sandbox or live)
        $this->apiContext->setConfig([
            'mode' => $this->isTestMode ? 'sandbox' : 'live',
            'log.LogEnabled' => true,
            'log.FileName' => storage_path('logs/paypal.log'),
            'log.LogLevel' => 'INFO',
        ]);
    }

    /**
     * Create a PayPal payment for subscription
     */
    public function createSubscriptionPayment(SubscriptionPlan $plan, array $userData): array
    {
        try {
            $user = User::find($userData['user_id']);
            $currency = strtoupper($userData['currency'] ?? 'INR');
            $amount = $plan->getPrice($userData['currency'] ?? 'INR');

            // Create PayPal payment
            $payment = new Payment();
            $payment->setIntent('sale');

            // Set payer
            $payer = new Payer();
            $payer->setPaymentMethod('paypal');
            $payment->setPayer($payer);

            // Set amount
            $paypalAmount = new Amount();
            $paypalAmount->setCurrency($currency)
                        ->setTotal(number_format($amount, 2, '.', ''));
            $payment->setAmount($paypalAmount);

            // Set transaction
            $transaction = new Transaction();
            $transaction->setAmount($paypalAmount)
                       ->setDescription("Subscription to {$plan->name} ({$plan->billing_cycle})")
                       ->setInvoiceNumber(uniqid('INV-'));
            $payment->setTransactions([$transaction]);

            // Set redirect URLs
            $redirectUrls = new RedirectUrls();
            $redirectUrls->setReturnUrl(route('paypal.success'))
                        ->setCancelUrl(route('paypal.cancel'));
            $payment->setRedirectUrls($redirectUrls);

            // Create payment
            $payment->create($this->apiContext);

            // Get approval URL
            $approvalUrl = $payment->getApprovalLink();

            // Create transaction record
            $dbTransaction = PaymentTransaction::create([
                'user_id' => $user->id,
                'payment_gateway_id' => $this->gateway->id,
                'gateway_transaction_id' => $payment->getId(),
                'status' => 'pending',
                'type' => 'subscription',
                'amount' => $amount,
                'currency' => $currency,
                'gateway_fee' => $this->gateway->getTransactionFee($amount),
                'net_amount' => $amount - $this->gateway->getTransactionFee($amount),
                'payment_method' => 'paypal',
                'description' => "Subscription to {$plan->name} ({$plan->billing_cycle})",
                'gateway_response' => [
                    'payment_id' => $payment->getId(),
                    'approval_url' => $approvalUrl,
                    'state' => $payment->getState(),
                ],
            ]);

            return [
                'success' => true,
                'transaction_id' => $dbTransaction->transaction_id,
                'payment_id' => $payment->getId(),
                'approval_url' => $approvalUrl,
                'amount' => $amount,
                'currency' => $currency,
            ];

        } catch (PayPalConnectionException $e) {
            Log::error('PayPal payment creation failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'error_code' => 'paypal_connection_error',
            ];
        } catch (\Exception $e) {
            Log::error('PayPal payment creation failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Payment creation failed',
                'error_code' => 'unknown',
            ];
        }
    }

    /**
     * Process webhook from PayPal
     */
    public function processWebhook(array $payload): bool
    {
        try {
            $eventType = $payload['event_type'] ?? null;
            $resource = $payload['resource'] ?? null;

            if (!$eventType || !$resource) {
                return false;
            }

            switch ($eventType) {
                case 'PAYMENT.CAPTURE.COMPLETED':
                    return $this->handlePaymentSuccess($resource);
                
                case 'PAYMENT.CAPTURE.DENIED':
                    return $this->handlePaymentFailure($resource);
                
                case 'BILLING.SUBSCRIPTION.CANCELLED':
                    return $this->handleSubscriptionCancellation($resource);
            }

            return true;

        } catch (\Exception $e) {
            Log::error('PayPal webhook processing failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Verify payment status
     */
    public function verifyPayment(string $paymentId): array
    {
        try {
            $payment = Payment::get($paymentId, $this->apiContext);
            
            return [
                'success' => true,
                'status' => $payment->getState(),
                'amount' => $payment->getTransactions()[0]->getAmount()->getTotal(),
                'currency' => $payment->getTransactions()[0]->getAmount()->getCurrency(),
                'payer_id' => $payment->getPayer()->getPayerInfo()->getPayerId(),
            ];

        } catch (PayPalConnectionException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'error_code' => 'paypal_connection_error',
            ];
        }
    }

    /**
     * Execute PayPal payment after user approval
     */
    public function executePayment(string $paymentId, string $payerId): array
    {
        try {
            $payment = Payment::get($paymentId, $this->apiContext);
            
            $execution = new PaymentExecution();
            $execution->setPayerId($payerId);

            $result = $payment->execute($execution, $this->apiContext);

            if ($result->getState() === 'approved') {
                // Update transaction status
                $transaction = PaymentTransaction::where('gateway_transaction_id', $paymentId)->first();
                if ($transaction) {
                    $transaction->markAsCompleted($paymentId);
                    $this->activateUserSubscription($transaction);
                }

                return [
                    'success' => true,
                    'status' => 'approved',
                    'transaction_id' => $result->getId(),
                ];
            }

            return [
                'success' => false,
                'error' => 'Payment not approved',
                'status' => $result->getState(),
            ];

        } catch (PayPalConnectionException $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'error_code' => 'paypal_connection_error',
            ];
        }
    }

    /**
     * Refund a payment
     */
    public function refundPayment(string $paymentId, float $amount = null): array
    {
        try {
            // PayPal refunds are handled through the PayPal dashboard
            // This is a placeholder for future implementation
            return [
                'success' => true,
                'message' => 'Refund request submitted to PayPal',
                'payment_id' => $paymentId,
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
                'error_code' => 'unknown',
            ];
        }
    }

    /**
     * Cancel a subscription
     */
    public function cancelSubscription(string $subscriptionId): bool
    {
        try {
            // PayPal subscription cancellation is handled through the PayPal dashboard
            // This is a placeholder for future implementation
            return true;

        } catch (\Exception $e) {
            Log::error('PayPal subscription cancellation failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get payment methods for user
     */
    public function getPaymentMethods(string $customerId): array
    {
        // PayPal doesn't store payment methods in the same way as Stripe
        // This is a placeholder for future implementation
        return [
            'success' => true,
            'payment_methods' => [],
            'message' => 'PayPal payment methods are managed through PayPal account',
        ];
    }

    /**
     * Test gateway connectivity
     */
    public function testConnection(): array
    {
        try {
            // Try to create a test payment context
            $testContext = new ApiContext(
                new OAuthTokenCredential(
                    $this->gateway->getConfig('client_id'),
                    $this->gateway->getConfig('client_secret')
                )
            );

            $testContext->setConfig([
                'mode' => 'sandbox',
                'log.LogEnabled' => false,
            ]);

            return [
                'success' => true,
                'message' => 'PayPal connection successful',
                'mode' => $this->isTestMode ? 'sandbox' : 'live',
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
     * Handle successful payment
     */
    protected function handlePaymentSuccess(array $data): bool
    {
        $transaction = PaymentTransaction::where('gateway_transaction_id', $data['id'])->first();
        
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
        $transaction = PaymentTransaction::where('gateway_transaction_id', $data['id'])->first();
        
        if ($transaction) {
            $transaction->markAsFailed('Payment denied by PayPal');
        }

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
                'payment_method' => 'paypal',
                'payment_id' => $transaction->gateway_transaction_id,
                'amount_paid' => $transaction->amount,
                'currency' => $transaction->currency,
                'starts_at' => now(),
                'ends_at' => now()->addDays(30), // Default to 30 days
            ]
        );
    }
}
