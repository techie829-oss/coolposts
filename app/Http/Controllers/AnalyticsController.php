<?php

namespace App\Http\Controllers;

use App\Models\Link;
use App\Models\User;
use App\Models\UserEarning;
use App\Models\Subscription;
use App\Models\Referral;
use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
        /**
     * Show main analytics dashboard - OPTIMIZED VERSION
     */
    public function dashboard()
    {
        $user = Auth::user();
        $currency = $user->preferred_currency ?? 'INR';

        // OPTIMIZATION: Use caching for analytics data
        $cacheKey = "analytics_user_{$user->id}_{$currency}_" . now()->format('Y-m-d');

        return Cache::remember($cacheKey, 300, function() use ($user, $currency) { // Cache for 5 minutes
            // Get date range (last 30 days by default)
            $endDate = now();
            $startDate = $endDate->copy()->subDays(30);

            // Overall statistics
            $overallStats = $this->getOverallStats($user, $currency);

            // Time-based analytics
            $timeAnalytics = $this->getTimeAnalytics($user, $startDate, $endDate, $currency);

            // Link performance analytics
            $linkAnalytics = $this->getLinkAnalytics($user, $startDate, $endDate);

            // Geographic analytics
            $geoAnalytics = $this->getGeographicAnalytics($user, $startDate, $endDate);

            // Device analytics
            $deviceAnalytics = $this->getDeviceAnalytics($user, $startDate, $endDate);

            // Referral analytics (if user has referrals)
            $referralAnalytics = $this->getReferralAnalytics($user, $startDate, $endDate);

            return view('analytics.dashboard', compact(
                'overallStats',
                'timeAnalytics',
                'linkAnalytics',
                'geoAnalytics',
                'deviceAnalytics',
                'referralAnalytics',
                'startDate',
                'endDate'
            ));
        });
    }

        /**
     * Get overall statistics - OPTIMIZED VERSION
     */
    protected function getOverallStats(User $user, string $currency): array
    {
        // OPTIMIZATION: Get all stats in fewer queries
        $linksWithClicks = $user->links()
            ->withCount('clicks')
            ->get();

        $totalLinks = $linksWithClicks->count();
        $totalClicks = $linksWithClicks->sum('clicks_count');

        // OPTIMIZATION: Get earnings in one query
        $currencyLower = strtolower($currency);
        $earningsData = DB::table('user_earnings')
            ->where('user_id', $user->id)
            ->selectRaw("
                SUM(amount_{$currencyLower}) as total_earnings,
                SUM(CASE WHEN status = 'pending' THEN amount_{$currencyLower} ELSE 0 END) as pending_earnings
            ")
            ->first();

        $totalEarnings = $earningsData->total_earnings ?? 0;
        $pendingEarnings = $earningsData->pending_earnings ?? 0;

        // Calculate conversion rate
        $conversionRate = $totalClicks > 0 ? ($totalEarnings / $totalClicks) * 100 : 0;

        // Calculate average earnings per click
        $avgEarningsPerClick = $totalClicks > 0 ? $totalEarnings / $totalClicks : 0;

        // Get subscription status
        $subscriptionStatus = $user->getSubscriptionStatus();
        $isPremium = $user->isPremium();

        return [
            'total_links' => $totalLinks,
            'total_clicks' => $totalClicks,
            'total_earnings' => $totalEarnings,
            'pending_earnings' => $pendingEarnings,
            'conversion_rate' => round($conversionRate, 2),
            'avg_earnings_per_click' => round($avgEarningsPerClick, 2),
            'subscription_status' => $subscriptionStatus,
            'is_premium' => $isPremium,
            'currency' => $currency,
        ];
    }

    /**
     * Get time-based analytics - OPTIMIZED VERSION
     */
    protected function getTimeAnalytics(User $user, Carbon $startDate, Carbon $endDate, string $currency): array
    {
        // OPTIMIZATION: Get all clicks data in one query instead of loop
        $clicksData = DB::table('link_clicks')
            ->join('links', 'link_clicks.link_id', '=', 'links.id')
            ->where('links.user_id', $user->id)
            ->whereBetween('link_clicks.created_at', [$startDate, $endDate])
            ->selectRaw('DATE(link_clicks.created_at) as date, COUNT(*) as clicks')
            ->groupBy('date')
            ->pluck('clicks', 'date')
            ->toArray();

        // OPTIMIZATION: Get all earnings data in one query instead of loop
        $currencyLower = strtolower($currency);
        $earningsData = DB::table('user_earnings')
            ->where('user_id', $user->id)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("DATE(created_at) as date, SUM(amount_{$currencyLower}) as earnings")
            ->groupBy('date')
            ->pluck('earnings', 'date')
            ->toArray();

        $dailyStats = [];
        $currentDate = $startDate->copy();

        while ($currentDate->lte($endDate)) {
            $date = $currentDate->format('Y-m-d');

            $dailyStats[] = [
                'date' => $date,
                'clicks' => $clicksData[$date] ?? 0,
                'earnings' => round($earningsData[$date] ?? 0, 2),
                'formatted_date' => $currentDate->format('M d'),
            ];

            $currentDate->addDay();
        }

        // Weekly aggregation
        $weeklyStats = $this->aggregateWeeklyStats($dailyStats);

        // Monthly aggregation
        $monthlyStats = $this->aggregateMonthlyStats($dailyStats);

        return [
            'daily' => $dailyStats,
            'weekly' => $weeklyStats,
            'monthly' => $monthlyStats,
        ];
    }

    /**
     * Get link performance analytics
     */
    protected function getLinkAnalytics(User $user, Carbon $startDate, Carbon $endDate): array
    {
        $links = $user->links()
            ->withCount(['clicks' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }])
            ->withSum(['earnings' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            }], 'amount')
            ->orderBy('clicks_count', 'desc')
            ->limit(10)
            ->get();

        $topPerformers = $links->take(5);
        $underPerformers = $links->where('clicks_count', 0)->take(5);

        return [
            'top_performers' => $topPerformers,
            'under_performers' => $underPerformers,
            'total_links' => $links->count(),
            'active_links' => $links->where('clicks_count', '>', 0)->count(),
        ];
    }

    /**
     * Get geographic analytics
     */
    protected function getGeographicAnalytics(User $user, Carbon $startDate, Carbon $endDate): array
    {
        // This would require additional tracking of user location/IP
        // For now, we'll return sample data structure
        $geoData = [
            'top_countries' => [
                ['country' => 'India', 'clicks' => 45, 'percentage' => 45],
                ['country' => 'United States', 'clicks' => 25, 'percentage' => 25],
                ['country' => 'United Kingdom', 'clicks' => 15, 'percentage' => 15],
                ['country' => 'Canada', 'clicks' => 10, 'percentage' => 10],
                ['country' => 'Australia', 'clicks' => 5, 'percentage' => 5],
            ],
            'top_cities' => [
                ['city' => 'Mumbai', 'clicks' => 20, 'percentage' => 20],
                ['city' => 'Delhi', 'clicks' => 15, 'percentage' => 15],
                ['city' => 'New York', 'clicks' => 12, 'percentage' => 12],
                ['city' => 'London', 'clicks' => 10, 'percentage' => 10],
                ['city' => 'Bangalore', 'clicks' => 8, 'percentage' => 8],
            ],
        ];

        return $geoData;
    }

    /**
     * Get device analytics
     */
    protected function getDeviceAnalytics(User $user, Carbon $startDate, Carbon $endDate): array
    {
        // This would require additional tracking of user agent/device
        // For now, we'll return sample data structure
        $deviceData = [
            'devices' => [
                ['device' => 'Mobile', 'clicks' => 60, 'percentage' => 60],
                ['device' => 'Desktop', 'clicks' => 30, 'percentage' => 30],
                ['device' => 'Tablet', 'clicks' => 10, 'percentage' => 10],
            ],
            'browsers' => [
                ['browser' => 'Chrome', 'clicks' => 45, 'percentage' => 45],
                ['browser' => 'Safari', 'clicks' => 25, 'percentage' => 25],
                ['browser' => 'Firefox', 'clicks' => 15, 'percentage' => 15],
                ['browser' => 'Edge', 'clicks' => 10, 'percentage' => 10],
                ['browser' => 'Others', 'clicks' => 5, 'percentage' => 5],
            ],
        ];

        return $deviceData;
    }

    /**
     * Get referral analytics
     */
    protected function getReferralAnalytics(User $user, Carbon $startDate, Carbon $endDate): array
    {
        $referrals = $user->referrals()
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $totalReferrals = $referrals->count();
        $completedReferrals = $referrals->where('status', 'completed')->count();
        $pendingReferrals = $referrals->where('status', 'pending')->count();
        $expiredReferrals = $referrals->where('status', 'expired')->count();

        $completionRate = $totalReferrals > 0 ? ($completedReferrals / $totalReferrals) * 100 : 0;

        return [
            'total_referrals' => $totalReferrals,
            'completed_referrals' => $completedReferrals,
            'pending_referrals' => $pendingReferrals,
            'expired_referrals' => $expiredReferrals,
            'completion_rate' => round($completionRate, 2),
            'referral_earnings' => [
                'inr' => $user->getReferralEarnings('INR'),
                'usd' => $user->getReferralEarnings('USD'),
            ],
        ];
    }

    /**
     * Aggregate weekly statistics
     */
    protected function aggregateWeeklyStats(array $dailyStats): array
    {
        $weeklyStats = [];
        $currentWeek = null;
        $weekData = ['clicks' => 0, 'earnings' => 0, 'days' => 0];

        foreach ($dailyStats as $day) {
            $week = Carbon::parse($day['date'])->startOfWeek()->format('Y-W');

            if ($currentWeek !== $week) {
                if ($currentWeek !== null) {
                    $weeklyStats[] = [
                        'week' => $currentWeek,
                        'clicks' => $weekData['clicks'],
                        'earnings' => $weekData['earnings'],
                        'avg_clicks_per_day' => round($weekData['clicks'] / max($weekData['days'], 1), 2),
                        'avg_earnings_per_day' => round($weekData['earnings'] / max($weekData['days'], 1), 2),
                    ];
                }

                $currentWeek = $week;
                $weekData = ['clicks' => 0, 'earnings' => 0, 'days' => 0];
            }

            $weekData['clicks'] += $day['clicks'];
            $weekData['earnings'] += $day['earnings'];
            $weekData['days']++;
        }

        // Add the last week
        if ($currentWeek !== null) {
            $weeklyStats[] = [
                'week' => $currentWeek,
                'clicks' => $weekData['clicks'],
                'earnings' => $weekData['earnings'],
                'avg_clicks_per_day' => round($weekData['clicks'] / max($weekData['days'], 1), 2),
                'avg_earnings_per_day' => round($weekData['earnings'] / max($weekData['days'], 1), 2),
            ];
        }

        return $weeklyStats;
    }

    /**
     * Aggregate monthly statistics
     */
    protected function aggregateMonthlyStats(array $dailyStats): array
    {
        $monthlyStats = [];
        $currentMonth = null;
        $monthData = ['clicks' => 0, 'earnings' => 0, 'days' => 0];

        foreach ($dailyStats as $day) {
            $month = Carbon::parse($day['date'])->format('Y-m');

            if ($currentMonth !== $month) {
                if ($currentMonth !== null) {
                    $monthlyStats[] = [
                        'month' => $currentMonth,
                        'clicks' => $monthData['clicks'],
                        'earnings' => $monthData['earnings'],
                        'avg_clicks_per_day' => round($monthData['clicks'] / max($monthData['days'], 1), 2),
                        'avg_earnings_per_day' => round($monthData['earnings'] / max($monthData['days'], 1), 2),
                    ];
                }

                $currentMonth = $month;
                $monthData = ['clicks' => 0, 'earnings' => 0, 'days' => 0];
            }

            $monthData['clicks'] += $day['clicks'];
            $monthData['earnings'] += $day['earnings'];
            $monthData['days']++;
        }

        // Add the last month
        if ($currentMonth !== null) {
            $monthlyStats[] = [
                'month' => $currentMonth,
                'clicks' => $monthData['clicks'],
                'earnings' => $monthData['earnings'],
                'avg_clicks_per_day' => round($monthData['clicks'] / max($monthData['days'], 1), 2),
                'avg_earnings_per_day' => round($monthData['earnings'] / max($monthData['days'], 1), 2),
            ];
        }

        return $monthlyStats;
    }

    /**
     * Get real-time analytics data (AJAX endpoint)
     */
    public function getRealTimeData(Request $request)
    {
        $user = Auth::user();
        $currency = $user->preferred_currency ?? 'INR';

        // Get today's stats
        $todayClicks = $user->links()
            ->join('link_clicks', 'links.id', '=', 'link_clicks.link_id')
            ->whereDate('link_clicks.created_at', today())
            ->count();

        $todayEarnings = $user->earnings()
            ->whereDate('created_at', today())
            ->sum("amount_{strtolower($currency)}");

        // Get this hour's stats
        $thisHourClicks = $user->links()
            ->join('link_clicks', 'links.id', '=', 'link_clicks.link_id')
            ->whereBetween('link_clicks.created_at', [
                now()->startOfHour(),
                now()->endOfHour()
            ])
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'today_clicks' => $todayClicks,
                'today_earnings' => round($todayEarnings, 2),
                'this_hour_clicks' => $thisHourClicks,
                'last_updated' => now()->format('H:i:s'),
            ],
        ]);
    }

    /**
     * Export analytics data
     */
    public function export(Request $request)
    {
        $request->validate([
            'format' => 'required|in:csv,json,xlsx',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $user = Auth::user();
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        // Get analytics data
        $analyticsData = [
            'overall_stats' => $this->getOverallStats($user, $user->preferred_currency ?? 'INR'),
            'time_analytics' => $this->getTimeAnalytics($user, $startDate, $endDate, $user->preferred_currency ?? 'INR'),
            'link_analytics' => $this->getLinkAnalytics($user, $startDate, $endDate),
            'referral_analytics' => $this->getReferralAnalytics($user, $startDate, $endDate),
        ];

        // For now, return JSON (you can implement CSV/XLSX export later)
        return response()->json($analyticsData);
    }
}
