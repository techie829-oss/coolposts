<?php

namespace App\Http\Controllers;

use App\Models\UserEarning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EarningsController extends Controller
{
    /**
     * Display the earnings dashboard
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $userCurrency = $user->preferred_currency ?? 'INR';
        $currencySymbol = $userCurrency === 'INR' ? '₹' : '$';

        // Get filter parameters
        $status = $request->get('status', 'all');
        $source = $request->get('source', 'all'); // all, link, blog
        $dateRange = $request->get('date_range', 'all'); // all, today, week, month, year

        // Base query
        $earningsQuery = $user->earnings();

        // Filter by status
        if ($status !== 'all') {
            $earningsQuery->where('status', $status);
        }

        // Filter by source
        if ($source === 'link') {
            $earningsQuery->whereNotNull('link_id')->whereNull('blog_post_id');
        } elseif ($source === 'blog') {
            $earningsQuery->whereNotNull('blog_post_id')->whereNull('link_id');
        }

        // Filter by date range
        if ($dateRange !== 'all') {
            switch ($dateRange) {
                case 'today':
                    $earningsQuery->whereDate('created_at', today());
                    break;
                case 'week':
                    $earningsQuery->where('created_at', '>=', now()->startOfWeek());
                    break;
                case 'month':
                    $earningsQuery->whereMonth('created_at', now()->month)
                                  ->whereYear('created_at', now()->year);
                    break;
                case 'year':
                    $earningsQuery->whereYear('created_at', now()->year);
                    break;
            }
        }

        // Get paginated earnings
        $earnings = $earningsQuery->with(['link', 'blogPost'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        // Calculate statistics
        $stats = [
            'total_earnings' => $user->getTotalEarningsInCurrency($userCurrency),
            'pending_earnings' => $user->getPendingEarningsInCurrency($userCurrency),
            'available_balance' => $user->getBalanceInCurrency($userCurrency),
            'today_earnings' => $user->earnings()
                ->where('status', 'approved')
                ->where('currency', $userCurrency)
                ->whereDate('created_at', today())
                ->sum('amount'),
            'this_month_earnings' => $user->earnings()
                ->where('status', 'approved')
                ->where('currency', $userCurrency)
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount'),
            'total_transactions' => $user->earnings()->where('currency', $userCurrency)->count(),
            'pending_transactions' => $user->earnings()
                ->where('currency', $userCurrency)
                ->where('status', 'pending')
                ->count(),
        ];

        return view('earnings.index', compact('earnings', 'stats', 'userCurrency', 'currencySymbol', 'status', 'source', 'dateRange'));
    }
}
