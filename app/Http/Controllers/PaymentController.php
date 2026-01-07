<?php

namespace App\Http\Controllers;

use App\Models\PaymentGateway;
use App\Models\SubscriptionPlan;
use App\Models\Subscription;
use App\Models\PaymentTransaction;
use App\Services\Payment\PaymentServiceFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\User; // Added missing import

class PaymentController extends Controller
{
    /**
     * Show subscription plans
     */
    public function showPlans()
    {
        $plans = SubscriptionPlan::active()
            ->orderBy('sort_order')
            ->get()
            ->groupBy('billing_cycle');

        $user = Auth::user();
        $activeSubscription = $user->activeSubscription();

        return view('subscriptions.plans', compact('plans', 'activeSubscription'));
    }

    /**
     * Initiate subscription payment
     */
    public function initiatePayment(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
            'gateway' => 'required|string',
        ]);

        $plan = SubscriptionPlan::findOrFail($request->plan_id);
        $user = Auth::user();
        $gateway = PaymentGateway::where('slug', $request->gateway)
            ->where('is_active', true)
            ->first();

        if (!$gateway) {
            return back()->with('error', 'Selected payment gateway is not available.');
        }

        // Check if gateway supports user's currency
        if (!$gateway->supportsCurrency($user->preferred_currency)) {
            return back()->with('error', 'Selected payment gateway does not support your currency.');
        }

        try {
            // Create payment service
            $paymentService = PaymentServiceFactory::create($gateway);

            // Create payment
            $result = $paymentService->createSubscriptionPayment($plan, [
                'user_id' => $user->id,
                'currency' => $user->preferred_currency,
            ]);

            if (!$result['success']) {
                return back()->with('error', $result['error'] ?? 'Payment initiation failed.');
            }

            // Store payment data in session for frontend
            session([
                'payment_data' => [
                    'transaction_id' => $result['transaction_id'],
                    'gateway' => $gateway->slug,
                    'plan' => $plan->toArray(),
                ]
            ]);

            // Redirect based on gateway type
            return $this->redirectToPaymentGateway($gateway->slug, $result);

        } catch (\Exception $e) {
            Log::error('Payment initiation failed: ' . $e->getMessage());
            return back()->with('error', 'Payment initiation failed. Please try again.');
        }
    }

    /**
     * Redirect to appropriate payment gateway
     */
    protected function redirectToPaymentGateway(string $gateway, array $result)
    {
        return match ($gateway) {
            'stripe' => view('payments.stripe', compact('result')),
            'paypal' => redirect($result['approval_url']),
            'paytm' => view('payments.paytm', compact('result')),
            'razorpay' => view('payments.razorpay', compact('result')),
            default => back()->with('error', 'Unsupported payment gateway.'),
        };
    }

    /**
     * Handle Stripe payment success
     */
    public function stripeSuccess(Request $request)
    {
        try {
            $transactionId = session('payment_data.transaction_id');
            $transaction = PaymentTransaction::where('transaction_id', $transactionId)->first();

            if (!$transaction) {
                return redirect()->route('subscriptions.plans')
                    ->with('error', 'Transaction not found.');
            }

            // Verify payment with Stripe
            $gateway = $transaction->gateway;
            $paymentService = PaymentServiceFactory::create($gateway);
            $verification = $paymentService->verifyPayment($transaction->gateway_transaction_id);

            if ($verification['success'] && $verification['status'] === 'succeeded') {
                $transaction->markAsCompleted($transaction->gateway_transaction_id);

                // Clear session
                session()->forget('payment_data');

                return redirect()->route('subscriptions.plans')
                    ->with('success', 'Payment successful! Your subscription is now active.');
            }

            return redirect()->route('subscriptions.plans')
                ->with('error', 'Payment verification failed.');

        } catch (\Exception $e) {
            Log::error('Stripe payment success handling failed: ' . $e->getMessage());
            return redirect()->route('subscriptions.plans')
                ->with('error', 'Payment processing failed.');
        }
    }

    /**
     * Handle PayPal payment success
     */
    public function paypalSuccess(Request $request)
    {
        try {
            $paymentId = $request->get('paymentId');
            $payerId = $request->get('PayerID');

            if (!$paymentId || !$payerId) {
                return redirect()->route('subscriptions.plans')
                    ->with('error', 'Invalid payment response from PayPal.');
            }

            // Find transaction
            $transaction = PaymentTransaction::where('gateway_transaction_id', $paymentId)->first();

            if (!$transaction) {
                return redirect()->route('subscriptions.plans')
                    ->with('error', 'Transaction not found.');
            }

            // Execute payment
            $gateway = $transaction->gateway;
            $paymentService = PaymentServiceFactory::create($gateway);
            $result = $paymentService->executePayment($paymentId, $payerId);

            if ($result['success']) {
                return redirect()->route('subscriptions.plans')
                    ->with('success', 'Payment successful! Your subscription is now active.');
            }

            return redirect()->route('subscriptions.plans')
                ->with('error', $result['error'] ?? 'Payment execution failed.');

        } catch (\Exception $e) {
            Log::error('PayPal payment success handling failed: ' . $e->getMessage());
            return redirect()->route('subscriptions.plans')
                ->with('error', 'Payment processing failed.');
        }
    }

    /**
     * Handle PayPal payment cancellation
     */
    public function paypalCancel()
    {
        return redirect()->route('subscriptions.plans')
            ->with('info', 'Payment was cancelled.');
    }

    /**
     * Handle Paytm callback
     */
    public function paytmCallback(Request $request)
    {
        try {
            $gateway = PaymentGateway::where('slug', 'paytm')->first();
            $paymentService = PaymentServiceFactory::create($gateway);

            $result = $paymentService->processWebhook($request->all());

            if ($result) {
                return response('OK', 200);
            }

            return response('Failed', 400);

        } catch (\Exception $e) {
            Log::error('Paytm callback handling failed: ' . $e->getMessage());
            return response('Error', 500);
        }
    }

    /**
     * Handle Razorpay callback
     */
    public function razorpayCallback(Request $request)
    {
        try {
            $gateway = PaymentGateway::where('slug', 'razorpay')->first();
            $paymentService = PaymentServiceFactory::create($gateway);

            $result = $paymentService->processWebhook($request->all());

            if ($result) {
                return response('OK', 200);
            }

            return response('Failed', 400);

        } catch (\Exception $e) {
            Log::error('Razorpay callback handling failed: ' . $e->getMessage());
            return response('Error', 500);
        }
    }

    /**
     * Handle Stripe webhook
     */
    public function stripeWebhook(Request $request)
    {
        try {
            $gateway = PaymentGateway::where('slug', 'stripe')->first();
            $paymentService = PaymentServiceFactory::create($gateway);

            $result = $paymentService->processWebhook($request->all());

            if ($result) {
                return response('OK', 200);
            }

            return response('Failed', 400);

        } catch (\Exception $e) {
            Log::error('Stripe webhook handling failed: ' . $e->getMessage());
            return response('Error', 500);
        }
    }

    /**
     * Handle PayPal webhook
     */
    public function paypalWebhook(Request $request)
    {
        try {
            $gateway = PaymentGateway::where('slug', 'paypal')->first();
            $paymentService = PaymentServiceFactory::create($gateway);

            $result = $paymentService->processWebhook($request->all());

            if ($result) {
                return response('OK', 200);
            }

            return response('Failed', 400);

        } catch (\Exception $e) {
            Log::error('PayPal webhook handling failed: ' . $e->getMessage());
            return response('Error', 500);
        }
    }

    /**
     * Verify payment status
     */
    public function verifyPayment(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|string',
        ]);

        try {
            $transaction = PaymentTransaction::where('transaction_id', $request->transaction_id)
                ->with('gateway')
                ->first();

            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'error' => 'Transaction not found.',
                ], 404);
            }

            $paymentService = PaymentServiceFactory::create($transaction->gateway);
            $verification = $paymentService->verifyPayment($transaction->gateway_transaction_id);

            return response()->json([
                'success' => true,
                'transaction' => $transaction->toArray(),
                'verification' => $verification,
            ]);

        } catch (\Exception $e) {
            Log::error('Payment verification failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Verification failed.',
            ], 500);
        }
    }

    /**
     * Get available payment gateways for user
     */
    public function getAvailableGateways()
    {
        $user = Auth::user();
        $gateways = PaymentGateway::active()->get();

        $availableGateways = [];

        foreach ($gateways as $gateway) {
            if ($gateway->supportsCurrency($user->preferred_currency)) {
                $availableGateways[] = [
                    'id' => $gateway->id,
                    'slug' => $gateway->slug,
                    'name' => $gateway->name,
                    'description' => $gateway->description,
                    'transaction_fee_percentage' => $gateway->transaction_fee_percentage,
                    'transaction_fee_fixed' => $gateway->transaction_fee_fixed,
                    'is_test_mode' => $gateway->is_test_mode,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'gateways' => $availableGateways,
            'user_currency' => $user->preferred_currency,
        ]);
    }

    /**
     * Test payment gateway connection
     */
    public function testGateway(Request $request)
    {
        $request->validate([
            'gateway_id' => 'required|exists:payment_gateways,id',
        ]);

        try {
            $gateway = PaymentGateway::findOrFail($request->gateway_id);
            $paymentService = PaymentServiceFactory::create($gateway);

            $result = $paymentService->testConnection();

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show user subscription dashboard
     */
    public function dashboard()
    {
        $user = auth()->user();

        $activeSubscription = $user->activeSubscription();
        $subscriptions = $user->subscriptions()->with('plan')->orderBy('created_at', 'desc')->get();
        $transactions = $user->paymentTransactions()->with('gateway')->orderBy('created_at', 'desc')->get();

        return view('subscriptions.dashboard', compact('activeSubscription', 'subscriptions', 'transactions'));
    }

    /**
     * Cancel user subscription
     */
    public function cancel(Request $request)
    {
        $request->validate([
            'subscription_id' => 'required|exists:subscriptions,id',
            'cancellation_reason' => 'nullable|string|max:500',
        ]);

        $subscription = Subscription::findOrFail($request->subscription_id);

        if ($subscription->user_id !== auth()->id()) {
            return back()->with('error', 'Unauthorized action.');
        }

        if (!$subscription->isActive()) {
            return back()->with('error', 'Subscription is not active.');
        }

        try {
            $subscription->cancel($request->cancellation_reason);

            return back()->with('success', 'Subscription cancelled successfully. You will continue to have access until ' . $subscription->ends_at->format('M d, Y'));
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to cancel subscription: ' . $e->getMessage());
        }
    }

    /**
     * Show transaction details
     */
    public function showTransaction($id)
    {
        $transaction = PaymentTransaction::where('id', $id)
            ->where('user_id', auth()->id())
            ->with('gateway')
            ->first();

        if (!$transaction) {
            return response()->json(['success' => false, 'error' => 'Transaction not found']);
        }

        $html = view('subscriptions.partials.transaction-details', compact('transaction'))->render();

        return response()->json(['success' => true, 'html' => $html]);
    }

    /**
     * Retry failed payment
     */
    public function retryPayment($id)
    {
        $transaction = PaymentTransaction::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$transaction) {
            return response()->json(['success' => false, 'error' => 'Transaction not found']);
        }

        if (!$transaction->isPending()) {
            return response()->json(['success' => false, 'error' => 'Transaction cannot be retried']);
        }

        try {
            // Create new payment intent
            $service = PaymentServiceFactory::create($transaction->gateway);
            $result = $service->createSubscriptionPayment(
                $transaction->subscription->plan,
                ['user_id' => auth()->id()]
            );

            // Update transaction
            $transaction->update([
                'gateway_transaction_id' => $result['gateway_transaction_id'] ?? null,
                'gateway_response' => $result,
                'status' => 'pending',
                'processed_at' => null,
                'failed_at' => null,
            ]);

            return response()->json(['success' => true, 'message' => 'Payment retry initiated']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Download payment receipt
     */
    public function downloadReceipt($id)
    {
        $transaction = PaymentTransaction::where('id', $id)
            ->where('user_id', auth()->id())
            ->with(['gateway', 'subscription.plan'])
            ->first();

        if (!$transaction) {
            abort(404);
        }

        if (!$transaction->isCompleted()) {
            abort(400, 'Transaction not completed');
        }

        // Return the receipt view
        return view('subscriptions.receipt', compact('transaction'));
    }

    /**
     * Handle payment failure
     */
    public function handlePaymentFailure(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|string',
            'error_message' => 'nullable|string',
            'gateway' => 'required|string',
        ]);

        try {
            $transaction = PaymentTransaction::where('transaction_id', $request->transaction_id)->first();

            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'error' => 'Transaction not found'
                ], 404);
            }

            // Mark transaction as failed
            $transaction->markAsFailed($request->error_message);

            // Log the failure
            Log::error('Payment failed', [
                'transaction_id' => $request->transaction_id,
                'gateway' => $request->gateway,
                'error' => $request->error_message,
                'user_id' => $transaction->user_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Payment failure recorded',
                'transaction_id' => $transaction->id
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to handle payment failure: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Failed to process payment failure'
            ], 500);
        }
    }

    /**
     * Get payment status
     */
    public function getPaymentStatus(Request $request)
    {
        $request->validate([
            'transaction_id' => 'required|string',
        ]);

        try {
            $transaction = PaymentTransaction::where('transaction_id', $request->transaction_id)->first();

            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'error' => 'Transaction not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'status' => $transaction->status,
                'amount' => $transaction->amount,
                'currency' => $transaction->currency,
                'gateway' => $transaction->gateway->name,
                'created_at' => $transaction->created_at,
                'processed_at' => $transaction->processed_at,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to get payment status'
            ], 500);
        }
    }

    /**
     * Upgrade subscription
     */
    public function upgradeSubscription(Request $request)
    {
        $request->validate([
            'new_plan_id' => 'required|exists:subscription_plans,id',
        ]);

        $user = auth()->user();
        $currentSubscription = $user->activeSubscription();
        $newPlan = SubscriptionPlan::findOrFail($request->new_plan_id);

        if (!$currentSubscription) {
            return back()->with('error', 'No active subscription found.');
        }

        if (!$user->canUpgrade()) {
            return back()->with('error', 'Cannot upgrade subscription at this time.');
        }

        try {
            // Calculate prorated amount
            $proratedAmount = $this->calculateProratedAmount($currentSubscription, $newPlan);

            // Create upgrade payment
            $result = $this->createUpgradePayment($user, $newPlan, $proratedAmount);

            if ($result['success']) {
                return back()->with('success', 'Subscription upgrade initiated. Please complete the payment.');
            }

            return back()->with('error', $result['error'] ?? 'Failed to initiate upgrade.');

        } catch (\Exception $e) {
            Log::error('Subscription upgrade failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to process upgrade. Please try again.');
        }
    }

    /**
     * Downgrade subscription
     */
    public function downgradeSubscription(Request $request)
    {
        $request->validate([
            'new_plan_id' => 'required|exists:subscription_plans,id',
        ]);

        $user = auth()->user();
        $currentSubscription = $user->activeSubscription();
        $newPlan = SubscriptionPlan::findOrFail($request->new_plan_id);

        if (!$currentSubscription) {
            return back()->with('error', 'No active subscription found.');
        }

        if (!$user->canDowngrade()) {
            return back()->with('error', 'Cannot downgrade subscription at this time.');
        }

        try {
            // Schedule downgrade for next billing cycle
            $currentSubscription->scheduleDowngrade($newPlan->id);

            return back()->with('success', 'Subscription will be downgraded at the end of your current billing cycle.');

        } catch (\Exception $e) {
            Log::error('Subscription downgrade failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to process downgrade. Please try again.');
        }
    }

    /**
     * Calculate prorated amount for upgrade
     */
    protected function calculateProratedAmount(Subscription $currentSubscription, SubscriptionPlan $newPlan): float
    {
        $currentPlan = $currentSubscription->plan;
        $daysRemaining = now()->diffInDays($currentSubscription->ends_at, false);
        $totalDays = $currentPlan->duration_days;

        // Calculate remaining value of current subscription
        $remainingValue = ($currentPlan->price_inr / $totalDays) * $daysRemaining;

        // Calculate cost of new plan for remaining period
        $newPlanCost = ($newPlan->price_inr / $totalDays) * $daysRemaining;

        // Return the difference
        return max(0, $newPlanCost - $remainingValue);
    }

    /**
     * Create upgrade payment
     */
    protected function createUpgradePayment(User $user, SubscriptionPlan $newPlan, float $amount): array
    {
        try {
            // Create payment transaction
            $transaction = PaymentTransaction::create([
                'user_id' => $user->id,
                'transaction_id' => 'UPG_' . uniqid(),
                'amount' => $amount,
                'currency' => $user->preferred_currency,
                'status' => 'pending',
                'type' => 'subscription_upgrade',
                'metadata' => [
                    'new_plan_id' => $newPlan->id,
                    'upgrade_type' => 'plan_change',
                ],
            ]);

            return [
                'success' => true,
                'transaction_id' => $transaction->transaction_id,
                'amount' => $amount,
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get subscription upgrade/downgrade options
     */
    public function getSubscriptionOptions()
    {
        $user = auth()->user();
        $currentSubscription = $user->activeSubscription();

        if (!$currentSubscription) {
            return response()->json([
                'success' => false,
                'error' => 'No active subscription found'
            ], 404);
        }

        $upgradePlans = $user->getAvailableUpgradePlans();
        $downgradePlans = $user->getAvailableDowngradePlans();

        return response()->json([
            'success' => true,
            'current_plan' => $currentSubscription->plan,
            'upgrade_plans' => $upgradePlans,
            'downgrade_plans' => $downgradePlans,
            'can_upgrade' => $user->canUpgrade(),
            'can_downgrade' => $user->canDowngrade(),
        ]);
    }
}
