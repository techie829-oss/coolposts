<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LinkClick extends Model
{
    protected $fillable = [
        'link_id',
        'ip_address',
        'user_agent',
        'referer',
        'country',
        'city',
        'country_code',
        'country_name',
        'state',
        'continent_code',
        'continent_name',
        'latitude',
        'longitude',
        'timezone',
        'isp',
        'organization',
        'earnings_generated',
        'is_unique',
        'clicked_at',
    ];

    protected $casts = [
        'earnings_generated' => 'decimal:4',
        'is_unique' => 'boolean',
        'clicked_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Get the link that was clicked
     */
    public function link(): BelongsTo
    {
        return $this->belongsTo(Link::class);
    }

    /**
     * Scope for unique clicks
     */
    public function scopeUnique($query)
    {
        return $query->where('is_unique', true);
    }

    /**
     * Scope for today's clicks
     */
    public function scopeToday($query)
    {
        return $query->whereDate('clicked_at', today());
    }

    /**
     * Scope for this week's clicks
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('clicked_at', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    /**
     * Scope for this month's clicks
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('clicked_at', now()->month)
                    ->whereYear('clicked_at', now()->year);
    }

    /**
     * Get formatted earnings
     */
    public function getFormattedEarningsAttribute(): string
    {
        return '$' . number_format($this->earnings_generated, 4);
    }

    /**
     * Get click time ago
     */
    public function getTimeAgoAttribute(): string
    {
        return $this->clicked_at->diffForHumans();
    }
}
