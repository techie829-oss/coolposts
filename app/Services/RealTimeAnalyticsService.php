<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Pusher\Pusher;
use App\Models\LinkClick;
use App\Models\BlogVisitor;
use App\Models\UserEarning;
use App\Models\Link;
use App\Models\BlogPost;
use App\Models\User;

class RealTimeAnalyticsService
{
    protected $pusher;
    protected $cacheService;

    public function __construct(CacheService $cacheService = null)
    {
        $this->cacheService = $cacheService;
        $this->initializePusher();
    }

    /**
     * Initialize Pusher for real-time communication
     */
    protected function initializePusher()
    {
        try {
            $key = config('broadcasting.connections.pusher.key');
            $secret = config('broadcasting.connections.pusher.secret');
            $appId = config('broadcasting.connections.pusher.app_id');
            $options = config('broadcasting.connections.pusher.options');

            if (!$key || !$secret || !$appId) {
                Log::warning('Pusher configuration missing, real-time features will be disabled');
                $this->pusher = null;
                return;
            }

            $this->pusher = new Pusher($key, $secret, $appId, $options);
        } catch (\Exception $e) {
            Log::error('Pusher initialization failed: ' . $e->getMessage());
            $this->pusher = null;
        }
    }

    /**
     * Track real-time click event
     */
    public function trackClick(LinkClick $click): void
    {
        try {
            $data = [
                'type' => 'click',
                'link_id' => $click->link_id,
                'user_id' => $click->link->user_id,
                'earnings' => $click->earnings_generated,
                'country' => $click->country_code,
                'city' => $click->city,
                'timestamp' => $click->clicked_at->toISOString(),
                'is_unique' => $click->is_unique,
            ];

            // Broadcast to user's private channel
            $this->broadcastToUser($click->link->user_id, 'click-tracked', $data);

            // Update real-time counters
            $this->updateRealTimeCounters($click->link->user_id, 'clicks');

            // Update global statistics
            $this->updateGlobalStatistics('clicks');

        } catch (\Exception $e) {
            Log::error('Real-time click tracking failed: ' . $e->getMessage());
        }
    }

    /**
     * Track real-time blog visitor event
     */
    public function trackBlogVisitor(BlogVisitor $visitor): void
    {
        try {
            $data = [
                'type' => 'blog_visit',
                'blog_post_id' => $visitor->blog_post_id,
                'user_id' => $visitor->blogPost->user_id,
                'earnings' => $visitor->earnings_inr,
                'country' => $visitor->country_code,
                'city' => $visitor->city,
                'timestamp' => $visitor->visited_at->toISOString(),
                'time_spent' => $visitor->time_spent_seconds,
                'is_unique' => $visitor->is_unique_visit,
            ];

            // Broadcast to user's private channel
            $this->broadcastToUser($visitor->blogPost->user_id, 'blog-visitor-tracked', $data);

            // Update real-time counters
            $this->updateRealTimeCounters($visitor->blogPost->user_id, 'blog_visits');

            // Update global statistics
            $this->updateGlobalStatistics('blog_visits');

        } catch (\Exception $e) {
            Log::error('Real-time blog visitor tracking failed: ' . $e->getMessage());
        }
    }

    /**
     * Track real-time earnings event
     */
    public function trackEarnings(UserEarning $earning): void
    {
        try {
            $data = [
                'type' => 'earning',
                'user_id' => $earning->user_id,
                'amount' => $earning->amount,
                'currency' => $earning->currency,
                'source' => $earning->link_id ? 'link' : 'blog',
                'timestamp' => $earning->created_at->toISOString(),
            ];

            // Broadcast to user's private channel
            $this->broadcastToUser($earning->user_id, 'earning-tracked', $data);

            // Update real-time counters
            $this->updateRealTimeCounters($earning->user_id, 'earnings');

            // Update global statistics
            $this->updateGlobalStatistics('earnings');

        } catch (\Exception $e) {
            Log::error('Real-time earnings tracking failed: ' . $e->getMessage());
        }
    }

    /**
     * Get real-time dashboard data for a user
     */
    public function getUserDashboardData(int $userId): array
    {
        if (!$this->cacheService) {
            return $this->generateUserDashboardData($userId);
        }

        $cacheKey = "realtime_dashboard_{$userId}";

        return $this->cacheService->getCachedData($cacheKey, function () use ($userId) {
            return $this->generateUserDashboardData($userId);
        }, 300); // Cache for 5 minutes
    }

    /**
     * Get real-time global statistics
     */
    public function getGlobalStatistics(): array
    {
        if (!$this->cacheService) {
            return $this->generateGlobalStatistics();
        }

        return $this->cacheService->getCachedData('realtime_global_stats', function () {
            return $this->generateGlobalStatistics();
        }, 300); // Cache for 5 minutes
    }

    /**
     * Get real-time link analytics
     */
    public function getLinkAnalytics(int $linkId): array
    {
        if (!$this->cacheService) {
            return $this->generateLinkAnalytics($linkId);
        }

        $cacheKey = "realtime_link_analytics_{$linkId}";

        return $this->cacheService->getCachedData($cacheKey, function () use ($linkId) {
            return $this->generateLinkAnalytics($linkId);
        }, 300); // Cache for 5 minutes
    }

    /**
     * Get real-time blog analytics
     */
    public function getBlogAnalytics(int $blogId): array
    {
        if (!$this->cacheService) {
            return $this->generateBlogAnalytics($blogId);
        }

        $cacheKey = "realtime_blog_analytics_{$blogId}";

        return $this->cacheService->getCachedData($cacheKey, function () use ($blogId) {
            return $this->generateBlogAnalytics($blogId);
        }, 300); // Cache for 5 minutes
    }

    /**
     * Get live visitor count
     */
    public function getLiveVisitorCount(): array
    {
        if (!$this->cacheService) {
            return $this->generateLiveVisitorCount();
        }

        return $this->cacheService->getCachedData('live_visitor_count', function () {
            return $this->generateLiveVisitorCount();
        }, 60); // Cache for 1 minute
    }

    /**
     * Get real-time earnings summary
     */
    public function getRealTimeEarningsSummary(int $userId = null): array
    {
        if (!$this->cacheService) {
            return $this->generateEarningsSummary($userId);
        }

        $cacheKey = $userId ? "realtime_earnings_{$userId}" : 'realtime_earnings_global';

        return $this->cacheService->getCachedData($cacheKey, function () use ($userId) {
            return $this->generateEarningsSummary($userId);
        }, 300); // Cache for 5 minutes
    }

    /**
     * Broadcast event to user's private channel
     */
    protected function broadcastToUser(int $userId, string $event, array $data): void
    {
        if (!$this->pusher) {
            return;
        }

        try {
            $this->pusher->trigger("private-user-{$userId}", $event, $data);
        } catch (\Exception $e) {
            Log::error("Failed to broadcast to user {$userId}: " . $e->getMessage());
        }
    }

    /**
     * Broadcast event to public channel
     */
    protected function broadcastToPublic(string $channel, string $event, array $data): void
    {
        if (!$this->pusher) {
            return;
        }

        try {
            $this->pusher->trigger($channel, $event, $data);
        } catch (\Exception $e) {
            Log::error("Failed to broadcast to public channel {$channel}: " . $e->getMessage());
        }
    }

    /**
     * Update real-time counters for a user
     */
    protected function updateRealTimeCounters(int $userId, string $type): void
    {
        $cacheKey = "realtime_counters_{$userId}";
        $counters = Cache::get($cacheKey, []);

        if (!isset($counters[$type])) {
            $counters[$type] = 0;
        }
        $counters[$type]++;

        Cache::put($cacheKey, $counters, 3600); // Cache for 1 hour
    }

    /**
     * Update global statistics
     */
    protected function updateGlobalStatistics(string $type): void
    {
        $cacheKey = "realtime_global_{$type}";
        $count = Cache::get($cacheKey, 0);
        Cache::put($cacheKey, $count + 1, 3600); // Cache for 1 hour
    }

    /**
     * Generate user dashboard data
     */
    protected function generateUserDashboardData(int $userId): array
    {
        $now = now();
        $today = $now->startOfDay();
        $thisWeek = $now->startOfWeek();
        $thisMonth = $now->startOfMonth();

        // Get user's links and blogs
        $links = Link::where('user_id', $userId)->pluck('id');
        $blogs = BlogPost::where('user_id', $userId)->pluck('id');

        // Today's statistics
        $todayClicks = LinkClick::whereIn('link_id', $links)
            ->whereDate('clicked_at', $today)
            ->count();

        $todayVisitors = BlogVisitor::whereIn('blog_post_id', $blogs)
            ->whereDate('visited_at', $today)
            ->count();

        $todayEarnings = UserEarning::where('user_id', $userId)
            ->whereDate('created_at', $today)
            ->sum('amount');

        // This week's statistics
        $weekClicks = LinkClick::whereIn('link_id', $links)
            ->whereBetween('clicked_at', [$thisWeek, $now])
            ->count();

        $weekVisitors = BlogVisitor::whereIn('blog_post_id', $blogs)
            ->whereBetween('visited_at', [$thisWeek, $now])
            ->count();

        $weekEarnings = UserEarning::where('user_id', $userId)
            ->whereBetween('created_at', [$thisWeek, $now])
            ->sum('amount');

        // This month's statistics
        $monthClicks = LinkClick::whereIn('link_id', $links)
            ->whereBetween('clicked_at', [$thisMonth, $now])
            ->count();

        $monthVisitors = BlogVisitor::whereIn('blog_post_id', $blogs)
            ->whereBetween('visited_at', [$thisMonth, $now])
            ->count();

        $monthEarnings = UserEarning::where('user_id', $userId)
            ->whereBetween('created_at', [$thisMonth, $now])
            ->sum('amount');

        // Recent activity
        $recentClicks = LinkClick::whereIn('link_id', $links)
            ->with('link')
            ->orderBy('clicked_at', 'desc')
            ->take(10)
            ->get();

        $recentVisitors = BlogVisitor::whereIn('blog_post_id', $blogs)
            ->with('blogPost')
            ->orderBy('visited_at', 'desc')
            ->take(10)
            ->get();

        return [
            'today' => [
                'clicks' => $todayClicks,
                'visitors' => $todayVisitors,
                'earnings' => $todayEarnings,
            ],
            'week' => [
                'clicks' => $weekClicks,
                'visitors' => $weekVisitors,
                'earnings' => $weekEarnings,
            ],
            'month' => [
                'clicks' => $monthClicks,
                'visitors' => $monthVisitors,
                'earnings' => $monthEarnings,
            ],
            'recent_activity' => [
                'clicks' => $recentClicks,
                'visitors' => $recentVisitors,
            ],
            'last_updated' => $now->toISOString(),
        ];
    }

    /**
     * Generate global statistics
     */
    protected function generateGlobalStatistics(): array
    {
        $now = now();
        $today = $now->startOfDay();

        // Today's global statistics
        $todayClicks = LinkClick::whereDate('clicked_at', $today)->count();
        $todayVisitors = BlogVisitor::whereDate('visited_at', $today)->count();
        $todayEarnings = UserEarning::whereDate('created_at', $today)->sum('amount');
        $todayUsers = User::whereDate('created_at', $today)->count();

        // Active users (last 24 hours)
        $activeUsers = User::whereHas('links.clicks', function ($query) use ($now) {
            $query->where('clicked_at', '>=', $now->subDay());
        })->orWhereHas('blogPosts.visitors', function ($query) use ($now) {
            $query->where('visited_at', '>=', $now->subDay());
        })->count();

        return [
            'today' => [
                'clicks' => $todayClicks,
                'visitors' => $todayVisitors,
                'earnings' => $todayEarnings,
                'new_users' => $todayUsers,
            ],
            'active_users' => $activeUsers,
            'total_users' => User::count(),
            'total_links' => Link::count(),
            'total_blogs' => BlogPost::count(),
            'last_updated' => $now->toISOString(),
        ];
    }

    /**
     * Generate link analytics
     */
    protected function generateLinkAnalytics(int $linkId): array
    {
        $now = now();
        $today = $now->startOfDay();
        $thisWeek = $now->startOfWeek();

        // Today's statistics
        $todayClicks = LinkClick::where('link_id', $linkId)
            ->whereDate('clicked_at', $today)
            ->count();

        $todayUniqueClicks = LinkClick::where('link_id', $linkId)
            ->whereDate('clicked_at', $today)
            ->where('is_unique', true)
            ->count();

        $todayEarnings = LinkClick::where('link_id', $linkId)
            ->whereDate('clicked_at', $today)
            ->sum('earnings_generated');

        // This week's statistics
        $weekClicks = LinkClick::where('link_id', $linkId)
            ->whereBetween('clicked_at', [$thisWeek, $now])
            ->count();

        $weekEarnings = LinkClick::where('link_id', $linkId)
            ->whereBetween('clicked_at', [$thisWeek, $now])
            ->sum('earnings_generated');

        // Geographic breakdown
        $topCountries = LinkClick::where('link_id', $linkId)
            ->whereNotNull('country_code')
            ->selectRaw('country_code, country_name, COUNT(*) as clicks')
            ->groupBy('country_code', 'country_name')
            ->orderBy('clicks', 'desc')
            ->take(5)
            ->get();

        return [
            'today' => [
                'clicks' => $todayClicks,
                'unique_clicks' => $todayUniqueClicks,
                'earnings' => $todayEarnings,
            ],
            'week' => [
                'clicks' => $weekClicks,
                'earnings' => $weekEarnings,
            ],
            'top_countries' => $topCountries,
            'last_updated' => $now->toISOString(),
        ];
    }

    /**
     * Generate blog analytics
     */
    protected function generateBlogAnalytics(int $blogId): array
    {
        $now = now();
        $today = $now->startOfDay();
        $thisWeek = $now->startOfWeek();

        // Today's statistics
        $todayVisitors = BlogVisitor::where('blog_post_id', $blogId)
            ->whereDate('visited_at', $today)
            ->count();

        $todayUniqueVisitors = BlogVisitor::where('blog_post_id', $blogId)
            ->whereDate('visited_at', $today)
            ->where('is_unique_visit', true)
            ->count();

        $todayEarnings = BlogVisitor::where('blog_post_id', $blogId)
            ->whereDate('visited_at', $today)
            ->sum('earnings_inr');

        // This week's statistics
        $weekVisitors = BlogVisitor::where('blog_post_id', $blogId)
            ->whereBetween('visited_at', [$thisWeek, $now])
            ->count();

        $weekEarnings = BlogVisitor::where('blog_post_id', $blogId)
            ->whereBetween('visited_at', [$thisWeek, $now])
            ->sum('earnings_inr');

        // Geographic breakdown
        $topCountries = BlogVisitor::where('blog_post_id', $blogId)
            ->whereNotNull('country_code')
            ->selectRaw('country_code, country_name, COUNT(*) as visitors')
            ->groupBy('country_code', 'country_name')
            ->orderBy('visitors', 'desc')
            ->take(5)
            ->get();

        return [
            'today' => [
                'visitors' => $todayVisitors,
                'unique_visitors' => $todayUniqueVisitors,
                'earnings' => $todayEarnings,
            ],
            'week' => [
                'visitors' => $weekVisitors,
                'earnings' => $weekEarnings,
            ],
            'top_countries' => $topCountries,
            'last_updated' => $now->toISOString(),
        ];
    }

    /**
     * Generate live visitor count
     */
    protected function generateLiveVisitorCount(): array
    {
        $now = now();
        $lastHour = $now->subHour();

        // Active visitors in the last hour
        $activeVisitors = BlogVisitor::where('visited_at', '>=', $lastHour)->count();
        $activeClickers = LinkClick::where('clicked_at', '>=', $lastHour)->count();

        // Unique visitors in the last hour
        $uniqueVisitors = BlogVisitor::where('visited_at', '>=', $lastHour)
            ->where('is_unique_visit', true)
            ->count();

        $uniqueClickers = LinkClick::where('clicked_at', '>=', $lastHour)
            ->where('is_unique', true)
            ->count();

        return [
            'active_visitors' => $activeVisitors + $activeClickers,
            'unique_visitors' => $uniqueVisitors + $uniqueClickers,
            'blog_visitors' => $activeVisitors,
            'link_clickers' => $activeClickers,
            'last_updated' => $now->toISOString(),
        ];
    }

    /**
     * Generate earnings summary
     */
    protected function generateEarningsSummary(int $userId = null): array
    {
        $now = now();
        $today = $now->startOfDay();
        $thisWeek = $now->startOfWeek();
        $thisMonth = $now->startOfMonth();

        $query = UserEarning::query();
        if ($userId) {
            $query->where('user_id', $userId);
        }

        // Today's earnings
        $todayEarnings = $query->whereDate('created_at', $today)->sum('amount');

        // This week's earnings
        $weekEarnings = $query->whereBetween('created_at', [$thisWeek, $now])->sum('amount');

        // This month's earnings
        $monthEarnings = $query->whereBetween('created_at', [$thisMonth, $now])->sum('amount');

        // Total earnings
        $totalEarnings = $query->sum('amount');

        // Earnings by source
        $earningsBySource = $query->selectRaw('
            CASE
                WHEN link_id IS NOT NULL THEN "links"
                WHEN blog_post_id IS NOT NULL THEN "blogs"
                ELSE "other"
            END as source,
            SUM(amount) as total
        ')
            ->groupBy('source')
            ->get();

        return [
            'today' => $todayEarnings,
            'week' => $weekEarnings,
            'month' => $monthEarnings,
            'total' => $totalEarnings,
            'by_source' => $earningsBySource,
            'last_updated' => $now->toISOString(),
        ];
    }

    /**
     * Get real-time notifications for a user
     */
    public function getUserNotifications(int $userId): array
    {
        if (!$this->cacheService) {
            return $this->generateUserNotifications($userId);
        }

        $cacheKey = "realtime_notifications_{$userId}";

        return $this->cacheService->getCachedData($cacheKey, function () use ($userId) {
            return $this->generateUserNotifications($userId);
        }, 600); // Cache for 10 minutes
    }

    /**
     * Generate user notifications
     */
    protected function generateUserNotifications(int $userId): array
    {
        $now = now();
        $lastDay = $now->subDay();

        // Recent earnings milestones
        $recentEarnings = UserEarning::where('user_id', $userId)
            ->where('created_at', '>=', $lastDay)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Recent high-value clicks
        $recentHighValueClicks = LinkClick::whereHas('link', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })
            ->where('earnings_generated', '>', 0.01)
            ->where('clicked_at', '>=', $lastDay)
            ->orderBy('clicked_at', 'desc')
            ->take(5)
            ->get();

        return [
            'recent_earnings' => $recentEarnings,
            'high_value_clicks' => $recentHighValueClicks,
            'last_updated' => $now->toISOString(),
        ];
    }

    /**
     * Check if real-time service is available
     */
    public function isAvailable(): bool
    {
        return $this->pusher !== null;
    }

    /**
     * Get service status
     */
    public function getStatus(): array
    {
        return [
            'available' => $this->isAvailable(),
            'pusher_configured' => $this->pusher !== null,
            'cache_available' => $this->cacheService && $this->cacheService->isRedisAvailable(),
            'last_check' => now()->toISOString(),
        ];
    }
}
