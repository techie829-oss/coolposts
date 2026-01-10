<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LinkController extends Controller
{
    public function __construct()
    {
        // Remove authorizeResource() call as it's not available in base Controller
        // We'll implement authorization manually in each method
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $links = Link::with(['user', 'clicks', 'earnings'])
                ->withCount([
                    'clicks',
                    'clicks as unique_clicks_count' => function ($query) {
                        $query->where('is_unique', true);
                    }
                ])
                ->latest()
                ->paginate(10);
        } else {
            $links = $user->links()
                ->withCount([
                    'clicks',
                    'clicks as unique_clicks_count' => function ($query) {
                        $query->where('is_unique', true);
                    }
                ])
                ->latest()
                ->paginate(10);
        }

        return view('links.index', compact('links'));
    }

    /**
     * Display a management listing of the resource.
     */
    public function manage()
    {
        $user = Auth::user();

        if ($user->isAdmin()) {
            $links = Link::with(['user', 'clicks', 'earnings'])
                ->withCount(['clicks'])
                ->latest()
                ->paginate(15);
        } else {
            $links = $user->links()
                ->withCount(['clicks'])
                ->latest()
                ->paginate(15);
        }

        return view('links.manage', compact('links'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Check if user can create links
        $this->authorize('create', Link::class);

        return view('links.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Check if user can create links
        $this->authorize('create', Link::class);

        $request->validate([
            'title' => 'required|string|max:255',
            'original_url' => 'required|url',
            'is_protected' => 'boolean',
            'password' => 'nullable|string|min:4|required_if:is_protected,1',
            'ad_type' => 'required|in:no_ads,short_ads,long_ads',
            'ad_duration' => 'nullable|integer|min:5|max:300',
            'is_monetized' => 'boolean',
            'daily_click_limit' => 'nullable|integer|min:1|max:1000',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $userCurrency = $user->preferred_currency ?? 'INR';

        // Get global settings
        $globalSettings = \App\Models\GlobalSetting::getSettings();

        $link = new Link();
        $link->title = strip_tags($request->title);
        $link->original_url = $request->original_url;
        $link->short_code = Link::generateShortCode();
        $link->is_protected = $request->boolean('is_protected');
        $link->password = $request->password ? Hash::make($request->password) : null;
        $link->ad_type = $request->ad_type;
        $link->ad_duration = $request->ad_duration;
        $link->currency = $userCurrency;
        $link->is_monetized = $request->boolean('is_monetized', true);
        $link->daily_click_limit = $request->daily_click_limit;
        $link->category = strip_tags($request->category);
        $link->description = strip_tags($request->description);
        $link->user_id = $user->id;

        // Get earning rates from global settings
        $isPremium = $user->isPremium();
        $link->earnings_per_click_inr = $globalSettings->getEarningRate($request->ad_type, 'INR', $isPremium);
        $link->earnings_per_click_usd = $globalSettings->getEarningRate($request->ad_type, 'USD', $isPremium);

        // Set legacy earnings_per_click field for backward compatibility
        $link->earnings_per_click = $userCurrency === 'INR' ? $link->earnings_per_click_inr : $link->earnings_per_click_usd;

        $link->save();

        $adTypeName = $link->getAdTypeDisplayName();
        return redirect()->route('links.index')->with('success', "Link created successfully with {$adTypeName}! You can now start earning money from clicks!");
    }

    /**
     * Display the specified resource.
     */
    public function show(Link $link)
    {
        // Check if user can view this link
        $this->authorize('view', $link);

        $link->load([
            'clicks' => function ($query) {
                $query->latest()->limit(10);
            },
            'earnings' => function ($query) {
                $query->latest()->limit(10);
            }
        ]);

        return view('links.show', compact('link'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Link $link)
    {
        // Check if user can update this link
        $this->authorize('update', $link);

        return view('links.edit', compact('link'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Link $link)
    {
        // Check if user can update this link
        $this->authorize('update', $link);

        $request->validate([
            'title' => 'required|string|max:255',
            'original_url' => 'required|url',
            'is_protected' => 'boolean',
            'password' => 'nullable|string|min:4|required_if:is_protected,1',
            'ad_type' => 'required|in:no_ads,short_ads,long_ads',
            'ad_duration' => 'nullable|integer|min:5|max:300',
            'is_monetized' => 'boolean',
            'daily_click_limit' => 'nullable|integer|min:1|max:1000',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:500',
        ]);

        $user = Auth::user();
        $userCurrency = $user->preferred_currency ?? 'INR';

        $link->title = $request->title;
        $link->original_url = $request->original_url;
        $link->is_protected = $request->boolean('is_protected');
        $link->ad_type = $request->ad_type;
        $link->ad_duration = $request->ad_duration;
        $link->currency = $userCurrency;
        $link->is_monetized = $request->boolean('is_monetized', true);
        $link->daily_click_limit = $request->daily_click_limit;
        $link->category = $request->category;
        $link->description = $request->description;

        // Update earning rates from global settings if ad type changed
        if ($link->isDirty('ad_type')) {
            $globalSettings = \App\Models\GlobalSetting::getSettings();
            $isPremium = $user->isPremium();

            $link->earnings_per_click_inr = $globalSettings->getEarningRate($request->ad_type, 'INR', $isPremium);
            $link->earnings_per_click_usd = $globalSettings->getEarningRate($request->ad_type, 'USD', $isPremium);
            $link->earnings_per_click = $userCurrency === 'INR' ? $link->earnings_per_click_inr : $link->earnings_per_click_usd;
        }

        if ($request->password) {
            $link->password = Hash::make($request->password);
        } elseif (!$request->boolean('is_protected')) {
            $link->password = null;
        }

        $link->save();

        $adTypeName = $link->getAdTypeDisplayName();
        return redirect()->route('links.index')->with('success', "Link updated successfully with {$adTypeName}!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Link $link)
    {
        // Check if user can delete this link
        $this->authorize('delete', $link);

        $link->delete();
        return redirect()->route('links.index')->with('success', 'Link deleted successfully!');
    }

    /**
     * Verify password for protected links
     */
    public function verifyPassword(Request $request, $shortCode)
    {
        $request->validate([
            'password' => 'required|string',
        ]);

        $link = Link::where('short_code', $shortCode)->firstOrFail();

        if (Hash::check($request->password, $link->password)) {
            // Redirect to monetization page
            return redirect()->route('link.redirect', $shortCode);
        }

        return back()->withErrors(['password' => 'Invalid password']);
    }

    /**
     * Toggle link status (active/inactive)
     */
    public function toggleStatus(Link $link)
    {
        $this->authorize('update', $link);

        $link->update(['is_active' => !$link->is_active]);

        $status = $link->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "Link {$status} successfully!");
    }

    /**
     * Show link analytics
     */
    public function analytics(Link $link)
    {
        $this->authorize('view', $link);

        $link->load([
            'clicks' => function ($query) {
                $query->latest()->limit(50);
            },
            'earnings'
        ]);

        $stats = [
            'total_clicks' => $link->total_clicks,
            'unique_clicks' => $link->unique_clicks,
            'total_earnings' => $link->total_earnings,
            'today_clicks' => $link->clicks()->today()->count(),
            'this_week_clicks' => $link->clicks()->thisWeek()->count(),
            'this_month_clicks' => $link->clicks()->thisMonth()->count(),
        ];

        return view('links.analytics', compact('link', 'stats'));
    }
}
