<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class PaymentTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'subscription_id',
        'payment_gateway_id',
        'transaction_id',
        'gateway_transaction_id',
        'status',
        'type',
        'amount',
        'currency',
        'gateway_fee',
        'net_amount',
        'payment_method',
        'payment_method_details',
        'gateway_response',
        'description',
        'failure_reason',
        'processed_at',
        'failed_at',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_fee' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'gateway_response' => 'array',
        'metadata' => 'array',
        'processed_at' => 'datetime',
        'failed_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($transaction) {
            if (empty($transaction->transaction_id)) {
                $transaction->transaction_id = 'TXN_' . strtoupper(Str::random(16));
            }
        });
    }

    /**
     * Get the user that owns the transaction
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subscription related to this transaction
     */
    public function subscription(): BelongsTo
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Get the payment gateway used
     */
    public function gateway(): BelongsTo
    {
        return $this->belongsTo(PaymentGateway::class, 'payment_gateway_id');
    }

    /**
     * Check if transaction is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if transaction is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if transaction failed
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Check if transaction is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Check if transaction is refunded
     */
    public function isRefunded(): bool
    {
        return $this->status === 'refunded';
    }

    /**
     * Mark transaction as completed
     */
    public function markAsCompleted(string $gatewayTransactionId = null): bool
    {
        $this->update([
            'status' => 'completed',
            'gateway_transaction_id' => $gatewayTransactionId,
            'processed_at' => now(),
        ]);

        return true;
    }

    /**
     * Mark transaction as failed
     */
    public function markAsFailed(string $reason = null): bool
    {
        $this->update([
            'status' => 'failed',
            'failure_reason' => $reason,
            'failed_at' => now(),
        ]);

        return true;
    }

    /**
     * Mark transaction as cancelled
     */
    public function markAsCancelled(): bool
    {
        $this->update([
            'status' => 'cancelled',
        ]);

        return true;
    }

    /**
     * Mark transaction as refunded
     */
    public function markAsRefunded(): bool
    {
        $this->update([
            'status' => 'refunded',
        ]);

        return true;
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmount(): string
    {
        $symbol = $this->currency === 'INR' ? '₹' : '$';
        return $symbol . number_format($this->amount, 2);
    }

    /**
     * Get formatted net amount
     */
    public function getFormattedNetAmount(): string
    {
        $symbol = $this->currency === 'INR' ? '₹' : '$';
        return $symbol . number_format($this->net_amount, 2);
    }

    /**
     * Get formatted gateway fee
     */
    public function getFormattedGatewayFee(): string
    {
        $symbol = $this->currency === 'INR' ? '₹' : '$';
        return $symbol . number_format($this->gateway_fee, 2);
    }

    /**
     * Get status display name
     */
    public function getStatusDisplayName(): string
    {
        return ucfirst($this->status);
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'completed' => 'bg-green-100 text-green-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            'failed' => 'bg-red-100 text-red-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
            'refunded' => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get type display name
     */
    public function getTypeDisplayName(): string
    {
        return match($this->type) {
            'subscription' => 'Subscription',
            'one_time' => 'One Time',
            'refund' => 'Refund',
            default => ucfirst($this->type),
        };
    }

    /**
     * Get payment method display name
     */
    public function getPaymentMethodDisplayName(): string
    {
        return match($this->payment_method) {
            'card' => 'Credit/Debit Card',
            'wallet' => 'Digital Wallet',
            'upi' => 'UPI',
            'netbanking' => 'Net Banking',
            'bank_transfer' => 'Bank Transfer',
            default => ucfirst($this->payment_method ?? 'Unknown'),
        };
    }

    /**
     * Scope for completed transactions
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for pending transactions
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for failed transactions
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Scope for transactions by user
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for transactions by type
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for transactions by status
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Get successful transactions for user
     */
    public static function getSuccessfulForUser(int $userId)
    {
        return static::completed()->byUser($userId)->orderBy('created_at', 'desc');
    }

    /**
     * Get total successful amount for user
     */
    public static function getTotalSuccessfulAmountForUser(int $userId, string $currency = 'INR'): float
    {
        return static::completed()
            ->byUser($userId)
            ->where('currency', $currency)
            ->sum('amount');
    }
}
