<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\BlogVisitor;
use App\Services\FraudDetectionService;
use App\Services\CPMEarningsService;
use App\Services\RealTimeAnalyticsService;
use App\Events\BlogVisitorTracked;
use App\Events\EarningsTracked;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BlogController extends Controller
{
    protected $fraudDetectionService;
    protected $cpmEarningsService;
    protected $realTimeAnalyticsService;

    public function __construct(
        FraudDetectionService $fraudDetectionService,
        CPMEarningsService $cpmEarningsService,
        RealTimeAnalyticsService $realTimeAnalyticsService
    ) {
        $this->fraudDetectionService = $fraudDetectionService;
        $this->cpmEarningsService = $cpmEarningsService;
        $this->realTimeAnalyticsService = $realTimeAnalyticsService;
    }

    /**
     * Display a listing of blog posts
     */
    public function index(Request $request)
    {
        $query = BlogPost::with('user')
            ->published()
            ->orderBy('published_at', 'desc');

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->byType($request->type);
        }

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->byCategory($request->category);
        }

        // Search
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('content', 'like', "%{$search}%")
                    ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }

        $posts = $query->paginate(12);

        // Get categories and types for filters
        $categories = BlogPost::published()->distinct()->pluck('category')->filter();
        $types = BlogPost::TYPES;

        return view('blog.index', compact('posts', 'categories', 'types'));
    }

    /**
     * Display a management listing of blog posts
     */
    public function manage()
    {
        $user = Auth::user();

        $posts = $user->blogPosts()
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('blog.manage', compact('posts'));
    }

    /**
     * Show the form for creating a new blog post
     */
    public function create()
    {
        $types = BlogPost::TYPES;
        $monetizationTypes = BlogPost::MONETIZATION_TYPES;
        $adTypes = BlogPost::AD_TYPES;

        // Get global settings for defaults
        $globalSettings = \App\Models\GlobalSetting::getSettings();

        return view('blog.create', compact('types', 'monetizationTypes', 'adTypes', 'globalSettings'));
    }

    /**
     * Store a newly created blog post
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'content_type' => 'required|in:' . implode(',', array_keys(BlogPost::CONTENT_TYPES)),
            'type' => 'required|in:' . implode(',', array_keys(BlogPost::TYPES)),
            'category' => 'nullable|string|max:100',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'attachments.*' => 'nullable|file|max:10240',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|array',
            'canonical_url' => 'nullable|url',
            'is_monetized' => 'boolean',
            'monetization_type' => 'required|in:' . implode(',', array_keys(BlogPost::MONETIZATION_TYPES)),
            'earning_rate_less_2min' => 'required|numeric|min:0|max:1',
            'earning_rate_2_5min' => 'required|numeric|min:0|max:1',
            'earning_rate_more_5min' => 'required|numeric|min:0|max:1',
            'ad_type' => 'required|in:' . implode(',', array_keys(BlogPost::AD_TYPES)),
            'ad_frequency' => 'nullable|integer|min:1|max:10',
            'status' => 'required|in:draft,published,scheduled',
            'scheduled_at' => 'nullable|date|after:now|required_if:status,scheduled',
        ]);

        $user = Auth::user();

        // Handle featured image
        $featuredImage = null;
        if ($request->hasFile('featured_image')) {
            $upload = cloudinary()->upload(
                $request->file('featured_image')->getRealPath(),
                [
                    'folder' => 'coolposts/blog/featured',
                    'quality' => 'auto',
                    'fetch_format' => 'auto',
                ]
            );
            $featuredImage = $upload->getSecurePath();
        }

        // Handle gallery images
        $galleryImages = [];
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $upload = cloudinary()->upload(
                    $image->getRealPath(),
                    [
                        'folder' => 'coolposts/blog/gallery',
                        'quality' => 'auto',
                        'fetch_format' => 'auto',
                    ]
                );
                $galleryImages[] = $upload->getSecurePath();
            }
        }

        // Handle attachments
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $upload = cloudinary()->upload(
                    $file->getRealPath(),
                    [
                        'folder' => 'coolposts/blog/attachments',
                        'resource_type' => 'auto',
                    ]
                );
                $attachments[] = [
                    'path' => $upload->getSecurePath(),
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                ];
            }
        }

        // Get global settings for defaults
        $globalSettings = \App\Models\GlobalSetting::getSettings();

        // Prepare data for mass assignment
        $data = [
            'user_id' => $user->id,
            'title' => $request->input('title'),
            'slug' => BlogPost::generateSlug($request->input('title')),
            'excerpt' => $request->input('excerpt'),
            'content' => $request->input('content'),
            'content_type' => $request->input('content_type'),
            'type' => $request->input('type'),
            'category' => $request->input('category'),
            'tags' => $request->input('tags'),
            'featured_image' => $featuredImage,
            'gallery_images' => $galleryImages,
            'attachments' => $attachments,
            'meta_title' => $request->input('meta_title'),
            'meta_description' => $request->input('meta_description'),
            'meta_keywords' => $request->input('meta_keywords'),
            'canonical_url' => $request->input('canonical_url'),
            'is_monetized' => $request->boolean('is_monetized', true),
            'monetization_type' => $request->input('monetization_type') ?: $globalSettings->default_blog_monetization_type,
            'ad_type' => $request->input('ad_type') ?: $globalSettings->default_blog_ad_type,
            'earning_rate_less_2min' => $request->input('earning_rate_less_2min') ?: $globalSettings->default_blog_earning_rate_less_2min_inr,
            'earning_rate_2_5min' => $request->input('earning_rate_2_5min') ?: $globalSettings->default_blog_earning_rate_2_5min_inr,
            'earning_rate_more_5min' => $request->input('earning_rate_more_5min') ?: $globalSettings->default_blog_earning_rate_more_5min_inr,
            'ad_frequency' => $request->input('ad_frequency'),
            'status' => $request->input('status'),
        ];

        if ($request->status === 'published') {
            $data['published_at'] = now();
        } elseif ($request->status === 'scheduled') {
            $data['scheduled_at'] = $request->scheduled_at;
        }

        $post = BlogPost::create($data);

        return redirect()->route('blog.show', $post->slug)
            ->with('success', 'Blog post created successfully!');
    }

    /**
     * Display the specified blog post
     */
    public function show(string $slug)
    {
        $post = BlogPost::with(['user', 'visitors'])
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();

        // Track visitor
        $this->trackVisitor($post);

        // Get related posts
        $relatedPosts = $post->getRelatedPosts(6);

        // Get popular posts
        $popularPosts = BlogPost::published()
            ->where('id', '!=', $post->id)
            ->orderBy('views', 'desc')
            ->limit(5)
            ->get();

        // Get categories with post counts
        $categories = BlogPost::published()
            ->selectRaw('category, COUNT(*) as count')
            ->whereNotNull('category')
            ->groupBy('category')
            ->orderBy('count', 'desc')
            ->pluck('count', 'category')
            ->take(8);

        // Get popular tags with post counts
        $popularTags = BlogPost::published()
            ->whereNotNull('tags')
            ->get()
            ->flatMap(function ($post) {
                return $post->tags ?? [];
            })
            ->countBy()
            ->sortDesc()
            ->take(12);

        return view('blog.show', compact('post', 'relatedPosts', 'popularPosts', 'categories', 'popularTags'));
    }

    /**
     * Show the form for editing the specified blog post
     */
    public function edit(BlogPost $post)
    {
        $this->authorize('update', $post);

        $types = BlogPost::TYPES;
        $monetizationTypes = BlogPost::MONETIZATION_TYPES;
        $adTypes = BlogPost::AD_TYPES;

        // Get global settings for defaults
        $globalSettings = \App\Models\GlobalSetting::getSettings();

        return view('blog.edit', compact('post', 'types', 'monetizationTypes', 'adTypes', 'globalSettings'));
    }

    /**
     * Update the specified blog post
     */
    public function update(Request $request, BlogPost $post)
    {
        $this->authorize('update', $post);

        $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'type' => 'required|in:' . implode(',', array_keys(BlogPost::TYPES)),
            'category' => 'nullable|string|max:100',
            'tags' => 'nullable|array',
            'tags.*' => 'string|max:50',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'attachments.*' => 'nullable|file|max:10240',
            'meta_title' => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',
            'meta_keywords' => 'nullable|array',
            'canonical_url' => 'nullable|url',
            'is_monetized' => 'boolean',
            'monetization_type' => 'required|in:' . implode(',', array_keys(BlogPost::MONETIZATION_TYPES)),
            'earning_rate_less_2min' => 'required|numeric|min:0|max:1',
            'earning_rate_2_5min' => 'required|numeric|min:0|max:1',
            'earning_rate_more_5min' => 'required|numeric|min:0|max:1',
            'ad_type' => 'required|in:' . implode(',', array_keys(BlogPost::AD_TYPES)),
            'ad_frequency' => 'nullable|integer|min:1|max:10',
            'status' => 'required|in:draft,published,archived,scheduled',
            'scheduled_at' => 'nullable|date|after:now|required_if:status,scheduled',
        ]);

        // Handle featured image
        if ($request->hasFile('featured_image')) {
            // Note: Cloudinary handles replacement automatically when using same public_id
            $upload = cloudinary()->upload(
                $request->file('featured_image')->getRealPath(),
                [
                    'folder' => 'coolposts/blog/featured',
                    'quality' => 'auto',
                    'fetch_format' => 'auto',
                ]
            );
            $post->featured_image = $upload->getSecurePath();
        }

        // Handle gallery images
        if ($request->hasFile('gallery_images')) {
            $galleryImages = [];
            foreach ($request->file('gallery_images') as $image) {
                $upload = cloudinary()->upload(
                    $image->getRealPath(),
                    [
                        'folder' => 'coolposts/blog/gallery',
                        'quality' => 'auto',
                        'fetch_format' => 'auto',
                    ]
                );
                $galleryImages[] = $upload->getSecurePath();
            }
            $post->gallery_images = $galleryImages;
        }

        // Handle attachments
        if ($request->hasFile('attachments')) {
            $attachments = [];
            foreach ($request->file('attachments') as $file) {
                $upload = cloudinary()->upload(
                    $file->getRealPath(),
                    [
                        'folder' => 'coolposts/blog/attachments',
                        'resource_type' => 'auto',
                    ]
                );
                $attachments[] = [
                    'path' => $upload->getSecurePath(),
                    'name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                ];
            }
            $post->attachments = $attachments;
        }

        // Prepare data for update
        $data = [
            'title' => $request->input('title'),
            'excerpt' => $request->input('excerpt'),
            'content' => $request->input('content'),
            'content_type' => $request->input('content_type'),
            'type' => $request->input('type'),
            'category' => $request->input('category'),
            'tags' => $request->input('tags'),
            'meta_title' => $request->input('meta_title'),
            'meta_description' => $request->input('meta_description'),
            'meta_keywords' => $request->input('meta_keywords'),
            'canonical_url' => $request->input('canonical_url'),
            'is_monetized' => $request->boolean('is_monetized', true),
            'monetization_type' => $request->input('monetization_type'),
            'earning_rate_less_2min' => $request->input('earning_rate_less_2min'),
            'earning_rate_2_5min' => $request->input('earning_rate_2_5min'),
            'earning_rate_more_5min' => $request->input('earning_rate_more_5min'),
            'ad_type' => $request->input('ad_type'),
            'ad_frequency' => $request->input('ad_frequency'),
            'status' => $request->input('status'),
        ];

        if ($request->status === 'published' && !$post->published_at) {
            $data['published_at'] = now();
        } elseif ($request->status === 'scheduled') {
            $data['scheduled_at'] = $request->scheduled_at;
        }

        $post->update($data);

        return redirect()->route('blog.show', $post->slug)
            ->with('success', 'Blog post updated successfully!');
    }

    /**
     * Remove the specified blog post
     */
    public function destroy(BlogPost $post)
    {
        $this->authorize('delete', $post);

        // Note: With Cloudinary, images persist unless explicitly deleted via API
        // For now, we keep them for potential recovery. Add Cloudinary deletion if needed.

        $post->delete();

        return redirect()->route('blog.index')
            ->with('success', 'Blog post deleted successfully!');
    }

    /**
     * Track visitor and calculate earnings with smart tracking logic
     */
    public function trackVisitor(BlogPost $post): void
    {
        $request = request();
        $ip = $request->ip();
        $userAgent = $request->userAgent();
        $sessionId = $request->session()->getId();
        $currentUser = Auth::user();

        // SMART TRACKING LOGIC - Skip tracking for:

        // 1. Self visits (user visiting their own blog)
        if ($currentUser && $currentUser->id === $post->user_id) {
            Log::info('Self visit detected, skipping tracking', [
                'user_id' => $currentUser->id,
                'blog_post_id' => $post->id,
                'ip' => $ip
            ]);
            return;
        }

        // 2. Check if tab is active (will be validated via AJAX)
        $isTabActive = $request->input('tab_active', true);
        if (!$isTabActive) {
            Log::info('Inactive tab detected, skipping tracking', [
                'blog_post_id' => $post->id,
                'ip' => $ip
            ]);
            return;
        }

        // 3. Check for recent activity (will be validated via AJAX)
        $lastActivity = $request->input('last_activity');
        if ($lastActivity) {
            $lastActivityTime = \Carbon\Carbon::parse($lastActivity);
            $timeSinceLastActivity = now()->diffInSeconds($lastActivityTime);

            if ($timeSinceLastActivity > 30) {
                Log::info('No activity for 30+ seconds, skipping tracking', [
                    'blog_post_id' => $post->id,
                    'ip' => $ip,
                    'seconds_inactive' => $timeSinceLastActivity
                ]);
                return;
            }
        }

        // Check if this is a unique visit (same IP, same post, within 24 hours)
        $existingVisit = $post->visitors()
            ->where('ip_address', $ip)
            ->where('visited_at', '>=', now()->subDay())
            ->first();

        $isUniqueVisit = !$existingVisit;

        // Fraud detection
        $fraudAnalysis = $this->fraudDetectionService->analyzeRequest($request, [
            'blog_post_id' => $post->id,
            'type' => 'blog_visit',
        ]);

        // Create visitor record with smart tracking flags
        $visitor = new BlogVisitor();
        $visitor->blog_post_id = $post->id;
        $visitor->user_id = $currentUser ? $currentUser->id : null;
        $visitor->ip_address = $ip;
        $visitor->user_agent = $userAgent;
        $visitor->session_id = $sessionId;
        $visitor->referrer = $request->header('referer');
        $visitor->visited_at = now();
        $visitor->is_unique_visit = $isUniqueVisit;
        $visitor->is_suspicious = $fraudAnalysis['should_block'];
        $visitor->fraud_flags = $fraudAnalysis['flags'];
        $visitor->risk_score = $fraudAnalysis['risk_score'];

        // Add smart tracking metadata
        $visitor->tracking_metadata = [
            'tab_active' => $isTabActive,
            'last_activity' => $lastActivity,
            'self_visit' => false,
            'smart_tracking_enabled' => true,
            'tracking_reason' => 'Valid visitor with active engagement'
        ];

        // Detect device and browser
        $visitor->detectDeviceType();
        $visitor->detectBrowserAndOS();

        $visitor->save();

        // Increment post views only for valid tracking
        $post->incrementViews();

        // Dispatch real-time events
        try {
            // Track visitor in real-time
            $this->realTimeAnalyticsService->trackBlogVisitor($visitor);

            // Broadcast visitor event
            event(new BlogVisitorTracked($visitor));
        } catch (\Exception $e) {
            Log::error('Real-time visitor tracking failed: ' . $e->getMessage());
        }

        // Log fraud attempts
        if (!$fraudAnalysis['is_safe']) {
            Log::warning('Suspicious blog visit detected', [
                'blog_post_id' => $post->id,
                'ip' => $ip,
                'risk_score' => $fraudAnalysis['risk_score'],
                'flags' => $fraudAnalysis['flags'],
            ]);
        }
    }

    /**
     * Handle visitor leaving (AJAX call)
     */
    public function trackLeave(Request $request, BlogPost $post)
    {
        $visitor = $post->visitors()
            ->where('session_id', $request->session()->getId())
            ->where('ip_address', $request->ip())
            ->whereNull('left_at')
            ->first();

        if ($visitor) {
            $visitor->left_at = now();
            $visitor->time_spent_seconds = $request->input('time_spent', 0);
            $visitor->scroll_depth_percentage = $request->input('scroll_depth', 0);
            $visitor->is_bounce = $request->input('is_bounce', false);
            $visitor->interactions = $request->input('interactions', []);
            $visitor->save();

            // Update post engagement metrics
            $post->updateEngagementMetrics(
                $visitor->time_spent_seconds,
                $visitor->scroll_depth_percentage,
                $visitor->is_bounce
            );

            // Calculate and credit earnings if monetized
            if ($post->is_monetized && !$visitor->is_suspicious) {
                $visitor->markAsCompleted();
                $earning = $visitor->creditEarnings();

                // Dispatch real-time earnings event if earnings were generated
                if ($earning) {
                    try {
                        event(new EarningsTracked($earning));
                    } catch (\Exception $e) {
                        Log::error('Real-time earnings tracking failed: ' . $e->getMessage());
                    }
                }
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * Handle smart tracking AJAX requests
     */
    public function trackVisitorAjax(Request $request, BlogPost $post)
    {
        $currentUser = Auth::user();

        // SMART TRACKING VALIDATION

        // 1. Self visit check
        if ($currentUser && $currentUser->id === $post->user_id) {
            return response()->json([
                'success' => false,
                'message' => 'Self visit detected',
                'tracking_disabled' => true
            ]);
        }

        // 2. Tab activity check
        $isTabActive = $request->input('tab_active', true);
        if (!$isTabActive) {
            return response()->json([
                'success' => false,
                'message' => 'Tab inactive',
                'tracking_disabled' => true
            ]);
        }

        // 3. Activity timeout check
        $lastActivity = $request->input('last_activity');
        if ($lastActivity) {
            $lastActivityTime = \Carbon\Carbon::parse($lastActivity);
            $timeSinceLastActivity = now()->diffInSeconds($lastActivityTime);

            if ($timeSinceLastActivity > 30) {
                return response()->json([
                    'success' => false,
                    'message' => 'No activity for 30+ seconds',
                    'tracking_disabled' => true,
                    'seconds_inactive' => $timeSinceLastActivity
                ]);
            }
        }

        // If all checks pass, proceed with tracking
        $this->trackVisitor($post);

        return response()->json([
            'success' => true,
            'message' => 'Tracking successful',
            'tracking_enabled' => true
        ]);
    }

    /**
     * Get blog analytics for user
     */
    public function analytics()
    {
        $user = Auth::user();

        $posts = $user->blogPosts()
            ->withCount(['visitors', 'earnings'])
            ->orderBy('created_at', 'desc')
            ->get();

        $totalViews = $posts->sum('views');
        $totalEarnings = $posts->sum('total_earnings');
        $totalVisitors = $posts->sum('visitors_count');

        return view('blog.analytics', compact('posts', 'totalViews', 'totalEarnings', 'totalVisitors'));
    }

    /**
     * Get blog post analytics
     */
    public function postAnalytics(BlogPost $post)
    {
        $this->authorize('view', $post);

        // Get visitor statistics
        $visitors = $post->visitors()
            ->with('user')
            ->orderBy('visited_at', 'desc')
            ->paginate(20);

        // Get earnings statistics
        $earnings = $post->earnings()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Get time category breakdown
        $timeCategories = $post->visitors()
            ->selectRaw('time_category, COUNT(*) as count, SUM(earnings_inr) as total_earnings_inr, SUM(earnings_usd) as total_earnings_usd')
            ->whereNotNull('time_category')
            ->groupBy('time_category')
            ->get();

        return view('blog.post-analytics', compact('post', 'visitors', 'earnings', 'timeCategories'));
    }

    /**
     * Show how we work page
     */
    public function howWeWork()
    {
        return view('blog.how-we-work');
    }

    public function templates()
    {
        return view('blog.templates');
    }
}
