<?php

namespace App\Http\Controllers;

use App\Models\Withdrawal;
use App\Models\GlobalSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WithdrawalController extends Controller
{
    /**
     * Display a listing of the user's withdrawals
     */
    public function index()
    {
        $user = auth()->user();
        $withdrawals = $user->withdrawals()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $stats = [
            'total_withdrawn' => $user->withdrawals()->where('status', 'completed')->sum('amount'),
            'pending_amount' => $user->withdrawals()->whereIn('status', ['pending', 'processing'])->sum('amount'),
            'total_requests' => $user->withdrawals()->count(),
        ];

        return view('withdrawals.index', compact('withdrawals', 'stats'));
    }

    /**
     * Show the form for creating a new withdrawal
     */
    public function create()
    {
        $user = auth()->user();
        $globalSettings = GlobalSetting::getSettings();

        // Check if payouts are frozen
        if ($globalSettings->payouts_frozen) {
            return redirect()->route('withdrawals.index')
                ->with('error', 'Withdrawals are currently frozen for system maintenance.');
        }

        // Get user's available balance
        $userCurrency = $user->preferred_currency ?? 'INR';
        $availableBalance = $user->getPendingEarningsInCurrency($userCurrency);

        // Get minimum withdrawal amount
        $minWithdrawal = $globalSettings->getMinWithdrawal($userCurrency);

        return view('withdrawals.create', compact('availableBalance', 'minWithdrawal', 'userCurrency'));
    }

    /**
     * Store a newly created withdrawal request
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        $globalSettings = GlobalSetting::getSettings();

        // Check if payouts are frozen
        if ($globalSettings->payouts_frozen) {
            return redirect()->route('withdrawals.index')
                ->with('error', 'Withdrawals are currently frozen for system maintenance.');
        }

        $userCurrency = $user->preferred_currency ?? 'INR';

        // Validate request
        $request->validate([
            'amount' => "required|numeric|min:{$globalSettings->getMinWithdrawal($userCurrency)}",
            'method' => 'required|in:paypal,stripe,bank_transfer,crypto,upi',
            'payment_details' => 'required|array',
        ]);

        // Validate payment details based on method
        $this->validatePaymentDetails($request->input('method'), $request->input('payment_details'));

        // Check if user has sufficient balance
        $availableBalance = $user->getPendingEarningsInCurrency($userCurrency);
        if ($request->input('amount') > $availableBalance) {
            return back()->withErrors(['amount' => 'Insufficient balance for withdrawal.']);
        }

        // Check if user has any pending withdrawals
        $pendingWithdrawals = $user->withdrawals()
            ->whereIn('status', ['pending', 'processing'])
            ->sum('amount');

        if ($pendingWithdrawals > 0) {
            return back()->withErrors(['amount' => 'You already have pending withdrawal requests.']);
        }

        try {
            DB::beginTransaction();

            // Create withdrawal request
            $withdrawal = $user->withdrawals()->create([
                'amount' => $request->input('amount'),
                'currency' => $userCurrency,
                'method' => $request->input('method'),
                'payment_details' => $request->input('payment_details'),
                'status' => 'pending',
                'requested_at' => now(),
            ]);

            // Deduct from user's pending earnings
            $this->deductFromPendingEarnings($user, $request->input('amount'), $userCurrency);

            DB::commit();

            return redirect()->route('withdrawals.index')
                ->with('success', 'Withdrawal request submitted successfully! It will be processed within 24-48 hours.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Withdrawal creation failed', [
                'user_id' => $user->id,
                'amount' => $request->input('amount'),
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['error' => 'Failed to create withdrawal request. Please try again.']);
        }
    }

    /**
     * Display the specified withdrawal
     */
    public function show(Withdrawal $withdrawal)
    {
        // Ensure user can only view their own withdrawals
        if ($withdrawal->user_id !== auth()->id()) {
            abort(403);
        }

        return view('withdrawals.show', compact('withdrawal'));
    }

    /**
     * Cancel a pending withdrawal
     */
    public function cancel(Withdrawal $withdrawal)
    {
        // Ensure user can only cancel their own withdrawals
        if ($withdrawal->user_id !== auth()->id()) {
            abort(403);
        }

        if (!$withdrawal->canBeCancelled()) {
            return back()->withErrors(['error' => 'This withdrawal cannot be cancelled.']);
        }

        try {
            DB::beginTransaction();

            // Cancel the withdrawal
            $withdrawal->cancel('Cancelled by user');

            // Refund the amount to user's pending earnings
            $this->refundToPendingEarnings($withdrawal->user, (float) $withdrawal->amount, $withdrawal->currency);

            DB::commit();

            return redirect()->route('withdrawals.index')
                ->with('success', 'Withdrawal cancelled successfully. Amount has been refunded to your account.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Withdrawal cancellation failed', [
                'withdrawal_id' => $withdrawal->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['error' => 'Failed to cancel withdrawal. Please contact support.']);
        }
    }

    /**
     * Validate payment details based on method
     */
    public function validatePaymentDetails(string $method, array $details): void
    {
        $rules = match ($method) {
            'paypal' => ['email' => 'required|email'],
            'stripe' => ['account_id' => 'required|string|min:10'],
            'bank_transfer' => [
                'account_number' => 'required|string|min:8',
                'ifsc_code' => 'required|string|size:11',
                'account_holder' => 'required|string|min:2',
            ],
            'crypto' => ['wallet_address' => 'required|string|min:26'],
            'upi' => ['upi_id' => 'required|string|min:5'],
            default => throw new \InvalidArgumentException('Invalid payment method'),
        };

        $validator = validator($details, $rules);
        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }
    }

    /**
     * Deduct amount from user's pending earnings
     */
    private function deductFromPendingEarnings($user, float $amount, string $currency): void
    {
        // Get pending earnings to deduct from
        $pendingEarnings = $user->earnings()
            ->where('status', 'pending')
            ->where('currency', $currency)
            ->orderBy('created_at', 'asc')
            ->get();

        $remainingAmount = $amount;

        foreach ($pendingEarnings as $earning) {
            if ($remainingAmount <= 0)
                break;

            $deductAmount = min($earning->amount, $remainingAmount);
            $earning->amount -= $deductAmount;
            $remainingAmount -= $deductAmount;

            if ($earning->amount <= 0) {
                $earning->delete(); // Remove zero amount earnings
            } else {
                $earning->save();
            }
        }

        if ($remainingAmount > 0) {
            throw new \Exception('Insufficient pending earnings to process withdrawal');
        }
    }

    /**
     * Refund amount to user's pending earnings
     */
    private function refundToPendingEarnings($user, float $amount, string $currency): void
    {
        // Create a new pending earning for the refunded amount
        $user->earnings()->create([
            'link_id' => null, // No specific link for refund
            'amount' => $amount,
            'currency' => $currency,
            'status' => 'pending',
            'notes' => 'Refund from cancelled withdrawal',
        ]);
    }
}
