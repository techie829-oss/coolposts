<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'subscription_plan_id',
        'status',
        'payment_method',
        'payment_id',
        'amount_paid',
        'currency',
        'trial_ends_at',
        'starts_at',
        'ends_at',
        'cancelled_at',
        'cancellation_reason',
        'metadata',
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'trial_ends_at' => 'datetime',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'metadata' => 'array',
    ];

    /**
     * Get the user that owns the subscription
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subscription plan
     */
    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    /**
     * Check if subscription is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && $this->ends_at->isFuture();
    }

    /**
     * Check if subscription is expired
     */
    public function isExpired(): bool
    {
        return $this->ends_at->isPast();
    }

    /**
     * Check if subscription is cancelled
     */
    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    /**
     * Check if subscription is on trial
     */
    public function onTrial(): bool
    {
        return $this->trial_ends_at && $this->trial_ends_at->isFuture();
    }

    /**
     * Check if subscription has expired
     */
    public function hasExpired(): bool
    {
        return $this->ends_at->isPast();
    }

    /**
     * Get days remaining in subscription
     */
    public function daysRemaining(): int
    {
        if ($this->hasExpired()) {
            return 0;
        }
        return now()->diffInDays($this->ends_at, false);
    }

    /**
     * Get trial days remaining
     */
    public function trialDaysRemaining(): int
    {
        if (!$this->onTrial()) {
            return 0;
        }
        return now()->diffInDays($this->trial_ends_at, false);
    }

    /**
     * Get subscription progress percentage
     */
    public function getProgressPercentage(): float
    {
        $totalDays = $this->starts_at->diffInDays($this->ends_at);
        $elapsedDays = $this->starts_at->diffInDays(now());

        if ($totalDays <= 0) {
            return 100;
        }

        $percentage = ($elapsedDays / $totalDays) * 100;
        return min(100, max(0, $percentage));
    }

    /**
     * Cancel the subscription
     */
    public function cancel(string $reason = null): bool
    {
        $this->update([
            'status' => 'cancelled',
            'cancelled_at' => now(),
            'cancellation_reason' => $reason,
        ]);

        return true;
    }

    /**
     * Reactivate the subscription
     */
    public function reactivate(): bool
    {
        if ($this->isExpired()) {
            return false;
        }

        $this->update([
            'status' => 'active',
            'cancelled_at' => null,
            'cancellation_reason' => null,
        ]);

        return true;
    }

    /**
     * Extend the subscription
     */
    public function extend(int $days): bool
    {
        $this->update([
            'ends_at' => $this->ends_at->addDays($days),
        ]);

        return true;
    }

    /**
     * Get formatted amount paid
     */
    public function getFormattedAmount(): string
    {
        $symbol = $this->currency === 'INR' ? '₹' : '$';
        return $symbol . number_format($this->amount_paid, 2);
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
            'active' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            'expired' => 'bg-gray-100 text-gray-800',
            'pending' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Scope for active subscriptions
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')->where('ends_at', '>', now());
    }

    /**
     * Scope for expired subscriptions
     */
    public function scopeExpired($query)
    {
        return $query->where('ends_at', '<', now());
    }

    /**
     * Scope for cancelled subscriptions
     */
    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    /**
     * Scope for subscriptions by user
     */
    public function scopeByUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Get active subscription for user
     */
    public static function getActiveForUser(int $userId): ?self
    {
        return static::active()->byUser($userId)->first();
    }

    /**
     * Check if user has active subscription
     */
    public static function userHasActive(int $userId): bool
    {
        return static::active()->byUser($userId)->exists();
    }
}
