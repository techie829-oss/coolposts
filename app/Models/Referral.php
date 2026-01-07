<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Referral extends Model
{
    protected $fillable = [
        'referrer_id',
        'referred_id',
        'referral_code',
        'status',
        'amount_inr',
        'amount_usd',
        'currency',
        'commission_rate',
        'completed_at',
        'expires_at',
        'metadata',
    ];

    protected $casts = [
        'amount_inr' => 'decimal:2',
        'amount_usd' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'completed_at' => 'datetime',
        'expires_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Get the user who made the referral
     */
    public function referrer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    /**
     * Get the user who was referred
     */
    public function referred(): BelongsTo
    {
        return $this->belongsTo(User::class, 'referred_id');
    }

    /**
     * Check if referral is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if referral is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if referral is expired
     */
    public function isExpired(): bool
    {
        return $this->status === 'expired';
    }

    /**
     * Check if referral has expired
     */
    public function hasExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Mark referral as completed
     */
    public function markAsCompleted(): bool
    {
        return $this->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);
    }

    /**
     * Mark referral as expired
     */
    public function markAsExpired(): bool
    {
        return $this->update([
            'status' => 'expired',
        ]);
    }

    /**
     * Get amount in specific currency
     */
    public function getAmount(string $currency = null): float
    {
        $currency = strtoupper($currency ?? $this->currency);
        
        return match($currency) {
            'INR' => $this->amount_inr,
            'USD' => $this->amount_usd,
            default => $this->amount_inr,
        };
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmount(string $currency = null): string
    {
        $currency = strtoupper($currency ?? $this->currency);
        $amount = $this->getAmount($currency);
        
        return match($currency) {
            'INR' => '₹' . number_format($amount, 2),
            'USD' => '$' . number_format($amount, 2),
            default => '₹' . number_format($amount, 2),
        };
    }

    /**
     * Get status display name
     */
    public function getStatusDisplayName(): string
    {
        return match($this->status) {
            'pending' => 'Pending',
            'completed' => 'Completed',
            'expired' => 'Expired',
            default => 'Unknown',
        };
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'completed' => 'bg-green-100 text-green-800',
            'expired' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get days until expiration
     */
    public function daysUntilExpiration(): int
    {
        if (!$this->expires_at) {
            return 0;
        }

        return max(0, now()->diffInDays($this->expires_at, false));
    }

    /**
     * Get commission amount
     */
    public function getCommissionAmount(string $currency = null): float
    {
        $currency = strtoupper($currency ?? $this->currency);
        $amount = $this->getAmount($currency);
        
        return ($amount * $this->commission_rate) / 100;
    }

    /**
     * Get formatted commission amount
     */
    public function getFormattedCommissionAmount(string $currency = null): string
    {
        $currency = strtoupper($currency ?? $this->currency);
        $amount = $this->getCommissionAmount($currency);
        
        return match($currency) {
            'INR' => '₹' . number_format($amount, 2),
            'USD' => '$' . number_format($amount, 2),
            default => '₹' . number_format($amount, 2),
        };
    }

    /**
     * Scope for pending referrals
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for completed referrals
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    /**
     * Scope for expired referrals
     */
    public function scopeExpired($query)
    {
        return $query->where('status', 'expired');
    }

    /**
     * Scope for active referrals (pending and not expired)
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'pending')
            ->where(function($q) {
                $q->whereNull('expires_at')
                  ->orWhere('expires_at', '>', now());
            });
    }

    /**
     * Scope for referrals by user
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('referrer_id', $userId);
    }

    /**
     * Get active referrals for a user
     */
    public static function getActiveForUser(int $userId)
    {
        return static::byUser($userId)->active()->get();
    }

    /**
     * Get completed referrals for a user
     */
    public static function getCompletedForUser(int $userId)
    {
        return static::byUser($userId)->completed()->get();
    }

    /**
     * Get total referral earnings for a user
     */
    public static function getTotalEarningsForUser(int $userId, string $currency = 'INR'): float
    {
        $currency = strtoupper($currency);
        $field = "amount_{strtolower($currency)}";
        
        return static::byUser($userId)
            ->completed()
            ->sum($field);
    }
}
