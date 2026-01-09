<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'content_type',
        'type',
        'category',
        'tags',
        'featured_image',
        'gallery_images',
        'attachments',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'is_monetized',
        'monetization_type',
        'earning_rate_less_2min',
        'earning_rate_2_5min',
        'earning_rate_more_5min',
        'ad_type',
        'ad_frequency',
        'sections',
        'code_blocks',
        'tables',
        'quotes',
        'status',
        'published_at',
        'scheduled_at',
    ];

    protected $casts = [
        'tags' => 'array',
        'gallery_images' => 'array',
        'attachments' => 'array',
        'meta_keywords' => 'array',
        'sections' => 'array',
        'code_blocks' => 'array',
        'tables' => 'array',
        'quotes' => 'array',
        'is_monetized' => 'boolean',
        'published_at' => 'datetime',
        'scheduled_at' => 'datetime',
        'last_viewed_at' => 'datetime',
        'earning_rate_less_2min' => 'decimal:4',
        'earning_rate_2_5min' => 'decimal:4',
        'earning_rate_more_5min' => 'decimal:4',
        'total_earnings_inr' => 'decimal:4',
        'total_earnings_usd' => 'decimal:4',
    ];

    /**
     * Blog types with descriptions
     */
    public const TYPES = [
        'tutorial' => 'Tutorial',
        'news' => 'News',
        'guide' => 'Guide',
        'review' => 'Review',
        'article' => 'Article',
        'case_study' => 'Case Study',
        'list' => 'List',
        'business_page' => 'Business Page',
        'company_portfolio' => 'Company Portfolio',
        'personal_portfolio' => 'Personal Portfolio',
    ];

    /**
     * Monetization types
     */
    public const MONETIZATION_TYPES = [
        'time_based' => 'Time-based Earnings',
        'ad_based' => 'Ad-based Earnings',
        'both' => 'Both Time and Ad-based',
    ];

    /**
     * Ad types
     */
    public const AD_TYPES = [
        'no_ads' => 'No Ads',
        'banner_ads' => 'Banner Ads',
        'interstitial_ads' => 'Interstitial Ads',
        'both' => 'Both Banner and Interstitial',
    ];

    /**
     * Content types
     */
    public const CONTENT_TYPES = [
        'markdown' => 'Markdown',
        'text' => 'Plain Text',
        'html' => 'HTML',
    ];

    /**
     * Get the user that owns the blog post
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get formatted content based on content type
     */
    public function getFormattedContent(): string
    {
        switch ($this->content_type) {
            case 'markdown':
                $content = $this->content;

                // Remove first H1 from markdown if it matches the title
                // This prevents duplicate H1 tags (one in template, one in content)
                $content = preg_replace('/^#\s+.+$/m', '', $content, 1);

                return Str::markdown($content);
            case 'html':
                return $this->content; // HTML content is already formatted
            case 'text':
            default:
                return nl2br(e($this->content)); // Convert line breaks to <br> tags
        }
    }

    /**
     * Get the blog visitors
     */
    public function visitors(): HasMany
    {
        return $this->hasMany(BlogVisitor::class);
    }

    /**
     * Get the blog earnings
     */
    public function earnings(): HasMany
    {
        return $this->hasMany(UserEarning::class, 'blog_post_id');
    }

    /**
     * Generate slug from title
     */
    public static function generateSlug(string $title): string
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Get the blog type display name
     */
    public function getTypeDisplayName(): string
    {
        return self::TYPES[$this->type] ?? 'Unknown';
    }

    /**
     * Get the monetization type display name
     */
    public function getMonetizationTypeDisplayName(): string
    {
        return self::MONETIZATION_TYPES[$this->monetization_type] ?? 'Unknown';
    }

    /**
     * Get the ad type display name
     */
    public function getAdTypeDisplayName(): string
    {
        return self::AD_TYPES[$this->ad_type] ?? 'Unknown';
    }

    /**
     * Check if post is published
     */
    public function isPublished(): bool
    {
        return $this->status === 'published' && $this->published_at !== null;
    }

    /**
     * Check if post is scheduled
     */
    public function isScheduled(): bool
    {
        return $this->status === 'scheduled' && $this->scheduled_at !== null;
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Get the full URL to the blog post
     */
    public function getUrlAttribute(): string
    {
        return url('/blog/' . $this->slug);
    }

    /**
     * Get the featured image URL
     */
    public function getFeaturedImageUrlAttribute(): ?string
    {
        if (!$this->featured_image) {
            return null;
        }

        return asset('storage/' . $this->featured_image);
    }

    /**
     * Get the gallery images URLs
     */
    public function getGalleryImageUrlsAttribute(): array
    {
        if (!$this->gallery_images) {
            return [];
        }

        return array_map(function ($image) {
            return asset('storage/' . $image);
        }, $this->gallery_images);
    }

    /**
     * Get the attachment URLs
     */
    public function getAttachmentUrlsAttribute(): array
    {
        if (!$this->attachments) {
            return [];
        }

        return array_map(function ($attachment) {
            return [
                'url' => asset('storage/' . $attachment['path']),
                'name' => $attachment['name'] ?? 'Attachment',
                'size' => $attachment['size'] ?? 0,
            ];
        }, $this->attachments);
    }

    /**
     * Get the average time spent in minutes
     */
    public function getAverageTimeSpentMinutesAttribute(): float
    {
        return round($this->avg_time_spent / 60, 2);
    }

    /**
     * Get the bounce rate percentage
     */
    public function getBounceRatePercentageAttribute(): float
    {
        return round($this->bounce_rate, 2);
    }

    /**
     * Get the scroll depth percentage
     */
    public function getScrollDepthPercentageAttribute(): float
    {
        return round($this->scroll_depth_avg, 2);
    }

    /**
     * Calculate earnings based on time spent
     */
    public function calculateEarnings(int $timeSpentSeconds): array
    {
        $timeSpentMinutes = $timeSpentSeconds / 60;
        $userCurrency = $this->user->preferred_currency ?? 'INR';

        if ($timeSpentMinutes < 2) {
            $rate = $this->earning_rate_less_2min;
            $category = 'less_2min';
        } elseif ($timeSpentMinutes <= 5) {
            $rate = $this->earning_rate_2_5min;
            $category = '2_5min';
        } else {
            $rate = $this->earning_rate_more_5min;
            $category = 'more_5min';
        }

        // Convert to USD for calculation
        $rateUSD = $userCurrency === 'INR' ?
            app(\App\Services\CurrencyService::class)->convert((float) $rate, 'INR', 'USD') :
            (float) $rate;

        $earningsUSD = $rateUSD;
        $earningsINR = app(\App\Services\CurrencyService::class)->convert($earningsUSD, 'USD', 'INR');

        return [
            'amount' => $userCurrency === 'INR' ? $earningsINR : $earningsUSD,
            'currency' => $userCurrency,
            'category' => $category,
            'time_spent_minutes' => $timeSpentMinutes,
            'rate_used' => $rate,
            'earnings_inr' => $earningsINR,
            'earnings_usd' => $earningsUSD,
        ];
    }

    /**
     * Get total earnings in user's preferred currency
     */
    public function getTotalEarningsAttribute(): float
    {
        $userCurrency = $this->user->preferred_currency ?? 'INR';

        return (float) ($userCurrency === 'INR' ?
            $this->total_earnings_inr :
            $this->total_earnings_usd);
    }

    /**
     * Get pending earnings in user's preferred currency
     */
    public function getPendingEarningsAttribute(): float
    {
        return $this->earnings()
            ->where('status', 'pending')
            ->sum('amount');
    }

    /**
     * Get approved earnings in user's preferred currency
     */
    public function getApprovedEarningsAttribute(): float
    {
        return $this->earnings()
            ->where('status', 'approved')
            ->sum('amount');
    }

    /**
     * Scope for published posts
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope for monetized posts
     */
    public function scopeMonetized($query)
    {
        return $query->where('is_monetized', true);
    }

    /**
     * Scope for posts by type
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for posts by category
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope for posts with tags
     */
    public function scopeWithTags($query, array $tags)
    {
        return $query->whereJsonContains('tags', $tags);
    }

    /**
     * Get related posts
     */
    public function getRelatedPosts(int $limit = 5): \Illuminate\Database\Eloquent\Collection
    {
        $query = static::published()
            ->where('id', '!=', $this->id)
            ->where(function ($query) {
                $query->where('category', $this->category)
                    ->orWhere('type', $this->type);

                // Handle tags query safely for SQLite
                if (!empty($this->tags) && is_array($this->tags)) {
                    foreach ($this->tags as $tag) {
                        $query->orWhere('tags', 'LIKE', '%"' . $tag . '"%');
                    }
                }
            })
            ->orderBy('views', 'desc')
            ->limit($limit);

        return $query->get();
    }

    /**
     * Increment view count
     */
    public function incrementViews(): void
    {
        $this->increment('views');
        $this->update(['last_viewed_at' => now()]);
    }

    /**
     * Update engagement metrics
     */
    public function updateEngagementMetrics(int $timeSpent, int $scrollDepth, bool $isBounce = false): void
    {
        $this->avg_time_spent = $this->calculateNewAverage($this->avg_time_spent, $timeSpent, $this->views);
        $this->scroll_depth_avg = $this->calculateNewAverage($this->scroll_depth_avg, $scrollDepth, $this->views);

        if ($isBounce) {
            $this->bounce_rate = $this->calculateNewBounceRate();
        }

        $this->save();
    }

    /**
     * Calculate new average
     */
    private function calculateNewAverage(int $currentAvg, int $newValue, int $count): int
    {
        if ($count === 0) {
            return $newValue;
        }

        return (int) (($currentAvg * ($count - 1) + $newValue) / $count);
    }

    /**
     * Calculate new bounce rate
     */
    private function calculateNewBounceRate(): int
    {
        $bounceVisitors = $this->visitors()->where('is_bounce', true)->count();
        $totalVisitors = $this->visitors()->count();

        if ($totalVisitors === 0) {
            return 0;
        }

        return (int) (($bounceVisitors / $totalVisitors) * 100);
    }
}
