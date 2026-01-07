<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogVisitor extends Model
{
    protected $fillable = [
        'blog_post_id',
        'user_id',
        'ip_address',
        'user_agent',
        'session_id',
        'referrer',
        'visited_at',
        'left_at',
        'time_spent_seconds',
        'scroll_depth_percentage',
        'is_unique_visit',
        'is_bounce',
        'page_views',
        'interactions',
        'time_category',
        'earnings_inr',
        'earnings_usd',
        'earnings_credited',
        'earnings_credited_at',
        'device_type',
        'browser',
        'os',
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
        'is_suspicious',
        'fraud_flags',
        'risk_score',
        'tracking_metadata',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
        'left_at' => 'datetime',
        'earnings_credited_at' => 'datetime',
        'is_unique_visit' => 'boolean',
        'is_bounce' => 'boolean',
        'earnings_credited' => 'boolean',
        'is_suspicious' => 'boolean',
        'interactions' => 'array',
        'fraud_flags' => 'array',
        'tracking_metadata' => 'array',
        'earnings_inr' => 'decimal:4',
        'earnings_usd' => 'decimal:4',
        'risk_score' => 'decimal:2',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Time categories
     */
    const TIME_CATEGORIES = [
        'less_2min' => 'Less than 2 minutes',
        '2_5min' => '2-5 minutes',
        'more_5min' => 'More than 5 minutes',
    ];

    /**
     * Device types
     */
    const DEVICE_TYPES = [
        'desktop' => 'Desktop',
        'mobile' => 'Mobile',
        'tablet' => 'Tablet',
    ];

    /**
     * Get the blog post
     */
    public function blogPost(): BelongsTo
    {
        return $this->belongsTo(BlogPost::class);
    }

    /**
     * Get the user (if logged in)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get time spent in minutes
     */
    public function getTimeSpentMinutesAttribute(): float
    {
        return round($this->time_spent_seconds / 60, 2);
    }

    /**
     * Get time spent in hours
     */
    public function getTimeSpentHoursAttribute(): float
    {
        return round($this->time_spent_seconds / 3600, 2);
    }

    /**
     * Get time category display name
     */
    public function getTimeCategoryDisplayNameAttribute(): string
    {
        return self::TIME_CATEGORIES[$this->time_category] ?? 'Unknown';
    }

    /**
     * Get device type display name
     */
    public function getDeviceTypeDisplayNameAttribute(): string
    {
        return self::DEVICE_TYPES[$this->device_type] ?? 'Unknown';
    }

    /**
     * Get total earnings in user's preferred currency
     */
    public function getTotalEarningsAttribute(): float
    {
        $userCurrency = $this->blogPost->user->preferred_currency ?? 'INR';

        return $userCurrency === 'INR' ?
            $this->earnings_inr :
            $this->earnings_usd;
    }

    /**
     * Check if visit is still active (within 30 minutes)
     */
    public function isActive(): bool
    {
        return $this->left_at === null &&
               $this->visited_at->diffInMinutes(now()) < 30;
    }

    /**
     * Calculate time spent from visit start
     */
    public function calculateTimeSpent(): int
    {
        if ($this->left_at) {
            return $this->visited_at->diffInSeconds($this->left_at);
        }

        return $this->visited_at->diffInSeconds(now());
    }

    /**
     * Update time spent and categorize
     */
    public function updateTimeSpent(): void
    {
        $this->time_spent_seconds = $this->calculateTimeSpent();

        // Categorize based on time spent
        $minutes = $this->time_spent_seconds / 60;

        if ($minutes < 2) {
            $this->time_category = 'less_2min';
        } elseif ($minutes <= 5) {
            $this->time_category = '2_5min';
        } else {
            $this->time_category = 'more_5min';
        }

        $this->save();
    }

    /**
     * Mark visit as completed
     */
    public function markAsCompleted(): void
    {
        $this->left_at = now();
        $this->updateTimeSpent();

        // Calculate earnings
        $earnings = $this->blogPost->calculateEarnings($this->time_spent_seconds);
        $this->earnings_inr = $earnings['earnings_inr'];
        $this->earnings_usd = $earnings['earnings_usd'];

        $this->save();
    }

    /**
     * Credit earnings to user
     */
    public function creditEarnings(): bool
    {
        if ($this->earnings_credited || $this->is_suspicious) {
            return false;
        }

        $user = $this->blogPost->user;
        $userCurrency = $user->preferred_currency ?? 'INR';
        $amount = $userCurrency === 'INR' ? $this->earnings_inr : $this->earnings_usd;

        if ($amount <= 0) {
            return false;
        }

        // Create earnings record
        $earning = $this->blogPost->earnings()->create([
            'user_id' => $user->id,
            'blog_post_id' => $this->blog_post_id,
            'amount' => $amount,
            'currency' => $userCurrency,
            'status' => 'pending',
            'notes' => "Blog visitor earnings - {$this->time_category_display_name}",
        ]);

        // Update user balance
        $user->incrementBalanceInCurrency($userCurrency, $amount);

        // Update blog post earnings
        if ($userCurrency === 'INR') {
            $this->blogPost->increment('total_earnings_inr', $this->earnings_inr);
        } else {
            $this->blogPost->increment('total_earnings_usd', $this->earnings_usd);
        }

        // Mark as credited
        $this->earnings_credited = true;
        $this->earnings_credited_at = now();
        $this->save();

        // Process referral commission
        $referralService = app(\App\Services\ReferralCommissionService::class);
        $referralService->processReferralCommission($user, $amount, $userCurrency, 'blog_view');

        return true;
    }

    /**
     * Detect device type from user agent
     */
    public function detectDeviceType(): void
    {
        $userAgent = strtolower($this->user_agent ?? '');

        if (strpos($userAgent, 'mobile') !== false ||
            strpos($userAgent, 'android') !== false ||
            strpos($userAgent, 'iphone') !== false) {
            $this->device_type = 'mobile';
        } elseif (strpos($userAgent, 'tablet') !== false ||
                   strpos($userAgent, 'ipad') !== false) {
            $this->device_type = 'tablet';
        } else {
            $this->device_type = 'desktop';
        }

        $this->save();
    }

    /**
     * Detect browser and OS
     */
    public function detectBrowserAndOS(): void
    {
        $userAgent = $this->user_agent ?? '';

        // Simple browser detection
        if (strpos($userAgent, 'Chrome') !== false) {
            $this->browser = 'Chrome';
        } elseif (strpos($userAgent, 'Firefox') !== false) {
            $this->browser = 'Firefox';
        } elseif (strpos($userAgent, 'Safari') !== false) {
            $this->browser = 'Safari';
        } elseif (strpos($userAgent, 'Edge') !== false) {
            $this->browser = 'Edge';
        } else {
            $this->browser = 'Other';
        }

        // Simple OS detection
        if (strpos($userAgent, 'Windows') !== false) {
            $this->os = 'Windows';
        } elseif (strpos($userAgent, 'Mac') !== false) {
            $this->os = 'macOS';
        } elseif (strpos($userAgent, 'Linux') !== false) {
            $this->os = 'Linux';
        } elseif (strpos($userAgent, 'Android') !== false) {
            $this->os = 'Android';
        } elseif (strpos($userAgent, 'iOS') !== false) {
            $this->os = 'iOS';
        } else {
            $this->os = 'Other';
        }

        $this->save();
    }

    /**
     * Scope for unique visits
     */
    public function scopeUnique($query)
    {
        return $query->where('is_unique_visit', true);
    }

    /**
     * Scope for credited earnings
     */
    public function scopeCredited($query)
    {
        return $query->where('earnings_credited', true);
    }

    /**
     * Scope for suspicious visits
     */
    public function scopeSuspicious($query)
    {
        return $query->where('is_suspicious', true);
    }

    /**
     * Scope for visits by time category
     */
    public function scopeByTimeCategory($query, string $category)
    {
        return $query->where('time_category', $category);
    }

    /**
     * Scope for visits by device type
     */
    public function scopeByDeviceType($query, string $deviceType)
    {
        return $query->where('device_type', $deviceType);
    }
}
