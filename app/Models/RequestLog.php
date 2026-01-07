<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RequestLog extends Model
{
    protected $table = 'request_logs';

    protected $fillable = [
        'method',
        'url',
        'ip_address',
        'user_agent',
        'response_time',
        'status_code',
        'request_data',
        'response_data',
        'session_id',
        'user_id',
    ];

    protected $casts = [
        'response_time' => 'integer',
        'status_code' => 'integer',
        'request_data' => 'array',
        'response_data' => 'array',
        'user_id' => 'integer',
    ];

    /**
     * Get the user that made the request
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for successful requests
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status_code', '>=', 200)->where('status_code', '<', 300);
    }

    /**
     * Scope for failed requests
     */
    public function scopeFailed($query)
    {
        return $query->where('status_code', '>=', 400);
    }

    /**
     * Scope for slow requests (over 1000ms)
     */
    public function scopeSlow($query)
    {
        return $query->where('response_time', '>', 1000);
    }

    /**
     * Scope for recent requests
     */
    public function scopeRecent($query, $minutes = 30)
    {
        return $query->where('created_at', '>=', now()->subMinutes($minutes));
    }

    /**
     * Get average response time for a time period
     */
    public static function getAverageResponseTime($minutes = 30): float
    {
        return static::recent($minutes)->avg('response_time') ?? 0;
    }

    /**
     * Get request count for a time period
     */
    public static function getRequestCount($minutes = 30): int
    {
        return static::recent($minutes)->count();
    }

    /**
     * Get error rate for a time period
     */
    public static function getErrorRate($minutes = 30): float
    {
        $total = static::recent($minutes)->count();
        $failed = static::recent($minutes)->failed()->count();

        return $total > 0 ? round(($failed / $total) * 100, 2) : 0;
    }

    /**
     * Get top slowest requests
     */
    public static function getSlowestRequests($limit = 10)
    {
        return static::orderBy('response_time', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get top most requested URLs
     */
    public static function getMostRequestedUrls($limit = 10)
    {
        return static::selectRaw('url, COUNT(*) as count')
            ->groupBy('url')
            ->orderBy('count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Get performance statistics
     */
    public static function getPerformanceStats($minutes = 30): array
    {
        $recent = static::recent($minutes);

        return [
            'total_requests' => $recent->count(),
            'avg_response_time' => round($recent->avg('response_time') ?? 0, 2),
            'min_response_time' => $recent->min('response_time') ?? 0,
            'max_response_time' => $recent->max('response_time') ?? 0,
            'error_rate' => static::getErrorRate($minutes),
            'successful_requests' => $recent->successful()->count(),
            'failed_requests' => $recent->failed()->count(),
            'slow_requests' => $recent->slow()->count(),
        ];
    }
}
