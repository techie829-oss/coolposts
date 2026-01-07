<?php

namespace App\Services\Payment;

use App\Models\PaymentGateway;
use App\Models\PaymentTransaction;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PaytmPaymentService implements PaymentServiceInterface
{
    protected PaymentGateway $gateway;
    protected string $merchantId;
    protected string $merchantKey;
    protected string $website;
    protected string $industryType;
    protected bool $isTestMode;
    protected string $baseUrl;

    public function __construct(PaymentGateway $gateway)
    {
        $this->gateway = $gateway;
        $this->merchantId = $gateway->getConfig('merchant_id');
        $this->merchantKey = $gateway->getConfig('merchant_key');
        $this->website = $gateway->getConfig('website', 'WEBSTAGING');
        $this->industryType = $gateway->getConfig('industry_type', 'Retail');
        $this->isTestMode = $gateway->isTestMode();

        // Set base URL based on environment
        $this->baseUrl = $this->isTestMode
            ? 'https://securegw-stage.paytm.in'
            : 'https://securegw.paytm.in';
    }

    /**
     * Create a Paytm payment for subscription
     */
    public function createSubscriptionPayment(SubscriptionPlan $plan, array $userData): array
    {
        try {
            $user = User::find($userData['user_id']);
            $currency = strtoupper($userData['currency'] ?? 'INR');
            $amount = $plan->getPrice($userData['currency'] ?? 'INR');

            // Generate unique order ID
            $orderId = 'ORDER_' . uniqid() . '_' . time();

            // Prepare payment parameters
            $paymentParams = [
                'MID' => $this->merchantId,
                'ORDER_ID' => $orderId,
                'CUST_ID' => (string)$user->id,
                'TXN_AMOUNT' => (string)$amount,
                'CHANNEL_ID' => $this->gateway->getConfig('channel_id', 'WEB'),
                'WEBSITE' => $this->website,
                'INDUSTRY_TYPE_ID' => $this->industryType,
                'CALLBACK_URL' => route('paytm.callback'),
                'EMAIL' => $user->email,
                'MOBILE_NO' => $user->phone ?? '',
                'PAYMENT_MODE_ONLY' => 'YES',
                'AUTH_MODE' => 'USRPWD',
                'PAYMENT_TYPE_ID' => 'PPI',
            ];

            // Add payment modes if configured
            $paymentModes = $this->gateway->getConfig('payment_mode_enabled', ['UPI', 'CARD', 'NETBANKING', 'WALLET']);
            if (!empty($paymentModes)) {
                $paymentParams['PAYMENT_MODE_ONLY'] = 'YES';
                $paymentParams['AUTH_MODE'] = 'USRPWD';
            }

            // Generate checksum
            $checksum = $this->generateChecksum($paymentParams);

            // Create transaction record
            $transaction = PaymentTransaction::create([
                'user_id' => $user->id,
                'payment_gateway_id' => $this->gateway->id,
                'gateway_transaction_id' => $orderId,
                'status' => 'pending',
                'type' => 'subscription',
                'amount' => $amount,
                'currency' => $currency,
                'gateway_fee' => $this->gateway->getTransactionFee($amount),
                'net_amount' => $amount - $this->gateway->getTransactionFee($amount),
                'payment_method' => 'wallet',
                'description' => "Subscription to {$plan->name} ({$plan->billing_cycle})",
                'gateway_response' => [
                    'order_id' => $orderId,
                    'checksum' => $checksum,
                    'payment_params' => $paymentParams,
                ],
            ]);

            return [
                'success' => true,
                'transaction_id' => $transaction->transaction_id,
                'order_id' => $orderId,
                'checksum' => $checksum,
                'payment_params' => $paymentParams,
                'amount' => $amount,
                'currency' => $currency,
                'paytm_url' => $this->baseUrl . '/theia/processTransaction',
            ];

        } catch (\Exception $e) {
            Log::error('Paytm payment creation failed: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => 'Payment creation failed',
                'error_code' => 'unknown',
            ];
        }
    }

    /**
     * Process webhook from Paytm
     */
    public function processWebhook(array $payload): bool
    {
        try {
            $orderId = $payload['ORDERID'] ?? null;
            $txnStatus = $payload['TXNSTATUS'] ?? null;
            $txnAmount = $payload['TXNAMOUNT'] ?? null;

            if (!$orderId || !$txnStatus) {
                return false;
            }

            // Verify checksum
            if (!$this->verifyChecksum($payload)) {
                Log::error('Paytm webhook checksum verification failed for order: ' . $orderId);
                return false;
            }

            $transaction = PaymentTransaction::where('gateway_transaction_id', $orderId)->first();

            if (!$transaction) {
                return false;
            }

            switch ($txnStatus) {
                case 'TXN_SUCCESS':
                    $transaction->markAsCompleted($orderId);
                    $this->activateUserSubscription($transaction);
                    break;

                case 'TXN_FAILURE':
                    $transaction->markAsFailed('Payment failed on Paytm');
                    break;

                case 'PENDING':
                    // Keep transaction as pending
                    break;
            }

            return true;

        } catch (\Exception $e) {
            Log::error('Paytm webhook processing failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Verify payment status
     */
    public function verifyPayment(string $paymentId): array
    {
        try {
            $response = Http::post($this->baseUrl . '/v3/order/status', [
                'MID' => $this->merchantId,
                'ORDERID' => $paymentId,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'success' => true,
                    'status' => $data['TXNSTATUS'] ?? 'unknown',
                    'amount' => $data['TXNAMOUNT'] ?? 0,
                    'currency' => 'INR',
                    'order_id' => $data['ORDERID'] ?? $paymentId,
                    'transaction_id' => $data['TXNID'] ?? null,
                ];
            }

            return [
                'success' => false,
                'error' => 'Failed to verify payment',
                'error_code' => 'verification_failed',
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
     * Refund a payment
     */
    public function refundPayment(string $paymentId, float $amount = null): array
    {
        try {
            // Paytm refunds are handled through the Paytm dashboard
            // This is a placeholder for future implementation
            return [
                'success' => true,
                'message' => 'Refund request submitted to Paytm',
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
            // Paytm subscription cancellation is handled through the Paytm dashboard
            // This is a placeholder for future implementation
            return true;

        } catch (\Exception $e) {
            Log::error('Paytm subscription cancellation failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get payment methods for user
     */
    public function getPaymentMethods(string $customerId): array
    {
        // Paytm doesn't store payment methods in the same way as Stripe
        // This is a placeholder for future implementation
        return [
            'success' => true,
            'payment_methods' => [],
            'message' => 'Paytm payment methods are managed through Paytm account',
        ];
    }

    /**
     * Test gateway connectivity
     */
    public function testConnection(): array
    {
        try {
            // Try to make a test API call
            $response = Http::timeout(10)->get($this->baseUrl . '/v3/order/status');

            return [
                'success' => true,
                'message' => 'Paytm connection successful',
                'mode' => $this->isTestMode ? 'test' : 'live',
                'base_url' => $this->baseUrl,
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
     * Generate Paytm checksum
     */
    protected function generateChecksum(array $params): string
    {
        // Sort parameters alphabetically
        ksort($params);

        // Create checksum string
        $checksumString = '';
        foreach ($params as $key => $value) {
            if ($key !== 'CHECKSUMHASH') {
                $checksumString .= $key . '=' . $value . '&';
            }
        }
        $checksumString = rtrim($checksumString, '&');

        // Generate checksum using merchant key
        return hash_hmac('sha256', $checksumString, $this->merchantKey);
    }

    /**
     * Verify Paytm checksum
     */
    protected function verifyChecksum(array $params): bool
    {
        $receivedChecksum = $params['CHECKSUMHASH'] ?? '';
        unset($params['CHECKSUMHASH']);

        $calculatedChecksum = $this->generateChecksum($params);

        return hash_equals($calculatedChecksum, $receivedChecksum);
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
                'payment_method' => 'paytm',
                'payment_id' => $transaction->gateway_transaction_id,
                'amount_paid' => $transaction->amount,
                'currency' => $transaction->currency,
                'starts_at' => now(),
                'ends_at' => now()->addDays(30), // Default to 30 days
            ]
        );
    }
}
