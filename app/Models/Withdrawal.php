<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Withdrawal extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'currency',
        'status',
        'method',
        'payment_details',
        'notes',
        'requested_at',
        'processed_at',
    ];

    protected $casts = [
        'amount' => 'decimal:4',
        'payment_details' => 'array',
        'requested_at' => 'datetime',
        'processed_at' => 'datetime',
    ];

    /**
     * Get the user that owns the withdrawal
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for pending withdrawals
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for processing withdrawals
     */
    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    /**
     * Scope for completed withdrawals
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for cancelled withdrawals
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Scope for failed withdrawals
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute(): string
    {
        $symbol = $this->currency === 'INR' ? '₹' : '$';
        return $symbol . number_format($this->amount, 2);
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'processing' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
            'failed' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get method display name
     */
    public function getMethodDisplayNameAttribute(): string
    {
        return match($this->method) {
            'paypal' => 'PayPal',
            'stripe' => 'Stripe',
            'bank_transfer' => 'Bank Transfer',
            'crypto' => 'Cryptocurrency',
            'upi' => 'UPI',
            default => ucfirst($this->method),
        };
    }

    /**
     * Get method icon
     */
    public function getMethodIconAttribute(): string
    {
        return match($this->method) {
            'paypal' => 'fab fa-paypal',
            'stripe' => 'fas fa-credit-card',
            'bank_transfer' => 'fas fa-university',
            'crypto' => 'fab fa-bitcoin',
            'upi' => 'fas fa-mobile-alt',
            default => 'fas fa-money-bill',
        };
    }

    /**
     * Process the withdrawal
     */
    public function process($notes = null): bool
    {
        return $this->update([
            'status' => 'processing',
            'notes' => $notes,
        ]);
    }

    /**
     * Complete the withdrawal
     */
    public function complete($notes = null): bool
    {
        return $this->update([
            'status' => 'completed',
            'processed_at' => now(),
            'notes' => $notes,
        ]);
    }

    /**
     * Cancel the withdrawal
     */
    public function cancel($notes = null): bool
    {
        return $this->update([
            'status' => 'cancelled',
            'notes' => $notes,
        ]);
    }

    /**
     * Mark withdrawal as failed
     */
    public function fail($notes = null): bool
    {
        return $this->update([
            'status' => 'failed',
            'notes' => $notes,
        ]);
    }

    /**
     * Check if withdrawal can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending']);
    }

    /**
     * Check if withdrawal is pending or processing
     */
    public function isActive(): bool
    {
        return in_array($this->status, ['pending', 'processing']);
    }

    /**
     * Get payment details for display
     */
    public function getPaymentDetailsDisplayAttribute(): string
    {
        if (!$this->payment_details) {
            return 'Not provided';
        }

        return match($this->method) {
            'paypal' => $this->payment_details['email'] ?? 'PayPal Email',
            'stripe' => $this->payment_details['account_id'] ?? 'Stripe Account',
            'bank_transfer' => $this->payment_details['account_number'] ?? 'Bank Account',
            'crypto' => $this->payment_details['wallet_address'] ?? 'Crypto Wallet',
            'upi' => $this->payment_details['upi_id'] ?? 'UPI ID',
            default => 'Payment Details',
        };
    }
}
