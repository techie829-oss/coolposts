<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserEarning extends Model
{
    protected $fillable = [
        'user_id',
        'link_id',
        'blog_post_id',
        'amount',
        'currency',
        'country_code',
        'country_name',
        'continent_code',
        'continent_name',
        'status',
        'approved_at',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:4',
        'approved_at' => 'datetime',
    ];

    /**
     * Get the user that owns the earning
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the link that generated the earning
     */
    public function link(): BelongsTo
    {
        return $this->belongsTo(Link::class);
    }

    /**
     * Get the blog post that generated the earning
     */
    public function blogPost(): BelongsTo
    {
        return $this->belongsTo(BlogPost::class);
    }

    /**
     * Scope for pending earnings
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for approved earnings
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for rejected earnings
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Approve the earning
     */
    public function approve($notes = null): bool
    {
        return $this->update([
            'status' => 'approved',
            'approved_at' => now(),
            'notes' => $notes,
        ]);
    }

    /**
     * Reject the earning
     */
    public function reject($notes = null): bool
    {
        return $this->update([
            'status' => 'rejected',
            'notes' => $notes,
        ]);
    }

    /**
     * Get formatted amount
     */
    public function getFormattedAmountAttribute(): string
    {
        return '$' . number_format($this->amount, 4);
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'pending' => 'bg-warning',
            'approved' => 'bg-success',
            'rejected' => 'bg-danger',
            default => 'bg-secondary',
        };
    }
}
