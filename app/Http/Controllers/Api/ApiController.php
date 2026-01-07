<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Link;
use App\Models\BlogPost;
use App\Models\User;
use App\Models\UserEarning;
use App\Models\LinkClick;
use App\Models\BlogVisitor;
use App\Models\GlobalSetting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ApiController extends Controller
{
    /**
     * API Authentication middleware
     */
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('throttle:60,1'); // 60 requests per minute
    }

    /**
     * Get API status and version
     */
    public function status(): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Link Sharing App API is running',
            'version' => '1.0.0',
            'timestamp' => now()->toISOString(),
            'features' => [
                'links' => true,
                'blogs' => true,
                'analytics' => true,
                'earnings' => true,
                'real_time' => true,
                'geoip' => true,
                'smart_tracking' => true
            ]
        ]);
    }

    /**
     * Get user's links
     */
    public function getLinks(Request $request): JsonResponse
    {
        $user = Auth::user();

        $query = $user->links()->with(['clicks', 'user']);

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        $links = $query->orderBy('created_at', 'desc')
                      ->paginate($request->get('per_page', 15));

        return response()->json([
            'status' => 'success',
            'data' => $links,
            'summary' => [
                'total_links' => $user->links()->count(),
                'active_links' => $user->links()->where('status', 'active')->count(),
                'total_clicks' => $user->links()->withCount('clicks')->get()->sum('clicks_count'),
                'total_earnings' => $user->earnings()->sum('amount_inr')
            ]
        ]);
    }

    /**
     * Get single link details
     */
    public function getLink(Link $link): JsonResponse
    {
        $user = Auth::user();

        if ($link->user_id !== $user->id && !$user->isAdmin()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        $link->load(['clicks', 'user', 'clicks.visitor']);

        return response()->json([
            'status' => 'success',
            'data' => $link,
            'analytics' => [
                'total_clicks' => $link->clicks()->count(),
                'unique_clicks' => $link->clicks()->where('is_unique', true)->count(),
                'total_earnings' => $link->clicks()->sum('earnings_generated'),
                'click_rate' => $link->clicks()->count() / max($link->views, 1) * 100,
                'recent_clicks' => $link->clicks()->latest()->take(10)->get()
            ]
        ]);
    }

    /**
     * Create new link
     */
    public function createLink(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'original_url' => 'required|url|max:2048',
            'description' => 'nullable|string|max:1000',
            'type' => 'required|in:standard,monetized,premium',
            'status' => 'in:active,inactive,draft'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();

        $link = $user->links()->create([
            'title' => $request->title,
            'original_url' => $request->original_url,
            'description' => $request->description,
            'type' => $request->type,
            'status' => $request->status ?? 'active',
            'short_code' => $this->generateShortCode(),
            'is_monetized' => $request->type === 'monetized'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Link created successfully',
            'data' => $link
        ], 201);
    }

    /**
     * Update link
     */
    public function updateLink(Request $request, Link $link): JsonResponse
    {
        $user = Auth::user();

        if ($link->user_id !== $user->id && !$user->isAdmin()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'string|max:255',
            'original_url' => 'url|max:2048',
            'description' => 'nullable|string|max:1000',
            'type' => 'in:standard,monetized,premium',
            'status' => 'in:active,inactive,draft'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $link->update($request->only(['title', 'original_url', 'description', 'type', 'status']));

        return response()->json([
            'status' => 'success',
            'message' => 'Link updated successfully',
            'data' => $link
        ]);
    }

    /**
     * Delete link
     */
    public function deleteLink(Link $link): JsonResponse
    {
        $user = Auth::user();

        if ($link->user_id !== $user->id && !$user->isAdmin()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        $link->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Link deleted successfully'
        ]);
    }

    /**
     * Get user's blog posts
     */
    public function getBlogPosts(Request $request): JsonResponse
    {
        $user = Auth::user();

        $query = $user->blogPosts()->with(['visitors', 'user']);

        // Apply filters
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('content', 'like', '%' . $request->search . '%');
        }

        $posts = $query->orderBy('created_at', 'desc')
                      ->paginate($request->get('per_page', 15));

        return response()->json([
            'status' => 'success',
            'data' => $posts,
            'summary' => [
                'total_posts' => $user->blogPosts()->count(),
                'published_posts' => $user->blogPosts()->where('status', 'published')->count(),
                'total_views' => $user->blogPosts()->sum('views'),
                'total_visitors' => $user->blogPosts()->withCount('visitors')->get()->sum('visitors_count')
            ]
        ]);
    }

    /**
     * Get single blog post
     */
    public function getBlogPost(BlogPost $post): JsonResponse
    {
        $user = Auth::user();

        if ($post->user_id !== $user->id && !$user->isAdmin()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access'
            ], 403);
        }

        $post->load(['visitors', 'user']);

        return response()->json([
            'status' => 'success',
            'data' => $post,
            'analytics' => [
                'total_visitors' => $post->visitors()->count(),
                'unique_visitors' => $post->visitors()->where('is_unique_visit', true)->count(),
                'total_earnings' => $post->visitors()->sum('earnings_inr'),
                'avg_time_spent' => $post->visitors()->avg('time_spent_seconds'),
                'bounce_rate' => $post->visitors()->where('is_bounce', true)->count() / max($post->visitors()->count(), 1) * 100
            ]
        ]);
    }

    /**
     * Get user analytics
     */
    public function getAnalytics(Request $request): JsonResponse
    {
        $user = Auth::user();

        $period = $request->get('period', '30'); // days
        $startDate = now()->subDays($period);

        // Link analytics
        $linkAnalytics = [
            'total_clicks' => $user->links()->withCount(['clicks' => function($query) use ($startDate) {
                $query->where('clicked_at', '>=', $startDate);
            }])->get()->sum('clicks_count'),
            'unique_clicks' => $user->links()->withCount(['clicks' => function($query) use ($startDate) {
                $query->where('clicked_at', '>=', $startDate)->where('is_unique', true);
            }])->get()->sum('clicks_count'),
            'total_earnings' => $user->earnings()->where('created_at', '>=', $startDate)->sum('amount_inr'),
            'top_links' => $user->links()->withCount(['clicks' => function($query) use ($startDate) {
                $query->where('clicked_at', '>=', $startDate);
            }])->orderBy('clicks_count', 'desc')->take(5)->get()
        ];

        // Blog analytics
        $blogAnalytics = [
            'total_views' => $user->blogPosts()->where('updated_at', '>=', $startDate)->sum('views'),
            'total_visitors' => $user->blogPosts()->withCount(['visitors' => function($query) use ($startDate) {
                $query->where('visited_at', '>=', $startDate);
            }])->get()->sum('visitors_count'),
            'total_earnings' => $user->blogPosts()->withSum(['visitors' => function($query) use ($startDate) {
                $query->where('visited_at', '>=', $startDate);
            }], 'earnings_inr')->get()->sum('visitors_sum_earnings_inr'),
            'top_posts' => $user->blogPosts()->where('updated_at', '>=', $startDate)
                               ->orderBy('views', 'desc')->take(5)->get()
        ];

        return response()->json([
            'status' => 'success',
            'data' => [
                'period' => $period . ' days',
                'start_date' => $startDate->toISOString(),
                'end_date' => now()->toISOString(),
                'link_analytics' => $linkAnalytics,
                'blog_analytics' => $blogAnalytics,
                'total_earnings' => $linkAnalytics['total_earnings'] + $blogAnalytics['total_earnings']
            ]
        ]);
    }

    /**
     * Get real-time analytics
     */
    public function getRealTimeAnalytics(): JsonResponse
    {
        $user = Auth::user();

        // Get live visitor count
        $liveVisitors = Cache::get('live_visitors_' . $user->id, 0);

        // Get recent activity
        $recentClicks = $user->links()->with(['clicks' => function($query) {
            $query->where('clicked_at', '>=', now()->subMinutes(30));
        }])->get()->flatMap->clicks;

        $recentVisitors = $user->blogPosts()->with(['visitors' => function($query) {
            $query->where('visited_at', '>=', now()->subMinutes(30));
        }])->get()->flatMap->visitors;

        return response()->json([
            'status' => 'success',
            'data' => [
                'live_visitors' => $liveVisitors,
                'recent_clicks' => $recentClicks->count(),
                'recent_visitors' => $recentVisitors->count(),
                'last_updated' => now()->toISOString()
            ]
        ]);
    }

    /**
     * Get user earnings
     */
    public function getEarnings(Request $request): JsonResponse
    {
        $user = Auth::user();

        $period = $request->get('period', '30'); // days
        $startDate = now()->subDays($period);

        $earnings = $user->earnings()
                        ->where('created_at', '>=', $startDate)
                        ->orderBy('created_at', 'desc')
                        ->paginate($request->get('per_page', 15));

        $summary = [
            'total_earnings' => $user->earnings()->where('created_at', '>=', $startDate)->sum('amount_inr'),
            'total_earnings_usd' => $user->earnings()->where('created_at', '>=', $startDate)->sum('amount_usd'),
            'earnings_count' => $user->earnings()->where('created_at', '>=', $startDate)->count(),
            'avg_earnings_per_day' => $user->earnings()->where('created_at', '>=', $startDate)->avg('amount_inr')
        ];

        return response()->json([
            'status' => 'success',
            'data' => $earnings,
            'summary' => $summary
        ]);
    }

    /**
     * Get global settings (admin only)
     */
    public function getGlobalSettings(): JsonResponse
    {
        $user = Auth::user();

        if (!$user->isAdmin()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Admin access required'
            ], 403);
        }

        $settings = GlobalSetting::first();

        return response()->json([
            'status' => 'success',
            'data' => $settings
        ]);
    }

    /**
     * Generate unique short code
     */
    private function generateShortCode(): string
    {
        do {
            $code = strtoupper(substr(md5(uniqid()), 0, 6));
        } while (Link::where('short_code', $code)->exists());

        return $code;
    }
}
