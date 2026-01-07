<?php

namespace App\Http\Controllers;

use App\Services\RealTimeAnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RealTimeAnalyticsController extends Controller
{
    protected $realTimeService;

    public function __construct(RealTimeAnalyticsService $realTimeService)
    {
        $this->realTimeService = $realTimeService;
    }

    /**
     * Get user dashboard data
     */
    public function getUserDashboard(Request $request): JsonResponse
    {
        $userId = auth()->id();
        $data = $this->realTimeService->getUserDashboardData($userId);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get global statistics
     */
    public function getGlobalStatistics(): JsonResponse
    {
        $data = $this->realTimeService->getGlobalStatistics();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get link analytics
     */
    public function getLinkAnalytics(Request $request): JsonResponse
    {
        $linkId = $request->input('link_id');

        if (!$linkId) {
            return response()->json([
                'success' => false,
                'message' => 'Link ID is required',
            ], 400);
        }

        $data = $this->realTimeService->getLinkAnalytics($linkId);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get blog analytics
     */
    public function getBlogAnalytics(Request $request): JsonResponse
    {
        $blogId = $request->input('blog_id');

        if (!$blogId) {
            return response()->json([
                'success' => false,
                'message' => 'Blog ID is required',
            ], 400);
        }

        $data = $this->realTimeService->getBlogAnalytics($blogId);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get live visitor count
     */
    public function getLiveVisitorCount(): JsonResponse
    {
        $data = $this->realTimeService->getLiveVisitorCount();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get real-time earnings summary
     */
    public function getEarningsSummary(Request $request): JsonResponse
    {
        $userId = $request->input('user_id', auth()->id());
        $data = $this->realTimeService->getRealTimeEarningsSummary($userId);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get user notifications
     */
    public function getUserNotifications(): JsonResponse
    {
        $userId = auth()->id();
        $data = $this->realTimeService->getUserNotifications($userId);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get real-time service status
     */
    public function getServiceStatus(): JsonResponse
    {
        $status = $this->realTimeService->getStatus();

        return response()->json([
            'success' => true,
            'data' => $status,
        ]);
    }

    /**
     * Get real-time analytics for admin
     */
    public function getAdminAnalytics(): JsonResponse
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $data = [
            'global_stats' => $this->realTimeService->getGlobalStatistics(),
            'live_visitors' => $this->realTimeService->getLiveVisitorCount(),
            'earnings_summary' => $this->realTimeService->getRealTimeEarningsSummary(),
            'service_status' => $this->realTimeService->getStatus(),
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get real-time analytics for a specific user (admin only)
     */
    public function getUserAnalytics(Request $request): JsonResponse
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $userId = $request->input('user_id');

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'User ID is required',
            ], 400);
        }

        $data = [
            'dashboard' => $this->realTimeService->getUserDashboardData($userId),
            'earnings' => $this->realTimeService->getRealTimeEarningsSummary($userId),
            'notifications' => $this->realTimeService->getUserNotifications($userId),
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get real-time analytics for a specific link (admin only)
     */
    public function getLinkAnalyticsAdmin(Request $request): JsonResponse
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $linkId = $request->input('link_id');

        if (!$linkId) {
            return response()->json([
                'success' => false,
                'message' => 'Link ID is required',
            ], 400);
        }

        $data = $this->realTimeService->getLinkAnalytics($linkId);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get real-time analytics for a specific blog (admin only)
     */
    public function getBlogAnalyticsAdmin(Request $request): JsonResponse
    {
        if (!auth()->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized',
            ], 403);
        }

        $blogId = $request->input('blog_id');

        if (!$blogId) {
            return response()->json([
                'success' => false,
                'message' => 'Blog ID is required',
            ], 400);
        }

        $data = $this->realTimeService->getBlogAnalytics($blogId);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get real-time analytics summary
     */
    public function getAnalyticsSummary(): JsonResponse
    {
        $userId = auth()->id();

        $data = [
            'dashboard' => $this->realTimeService->getUserDashboardData($userId),
            'earnings' => $this->realTimeService->getRealTimeEarningsSummary($userId),
            'live_visitors' => $this->realTimeService->getLiveVisitorCount(),
            'notifications' => $this->realTimeService->getUserNotifications($userId),
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get real-time analytics for mobile app
     */
    public function getMobileAnalytics(): JsonResponse
    {
        $userId = auth()->id();

        $data = [
            'dashboard' => $this->realTimeService->getUserDashboardData($userId),
            'earnings' => $this->realTimeService->getRealTimeEarningsSummary($userId),
            'notifications' => $this->realTimeService->getUserNotifications($userId),
            'service_status' => $this->realTimeService->getStatus(),
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get real-time analytics for public display
     */
    public function getPublicAnalytics(): JsonResponse
    {
        $data = [
            'global_stats' => $this->realTimeService->getGlobalStatistics(),
            'live_visitors' => $this->realTimeService->getLiveVisitorCount(),
        ];

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get real-time analytics for embedded widgets
     */
    public function getWidgetAnalytics(Request $request): JsonResponse
    {
        $type = $request->input('type', 'summary');
        $userId = auth()->id();

        switch ($type) {
            case 'dashboard':
                $data = $this->realTimeService->getUserDashboardData($userId);
                break;
            case 'earnings':
                $data = $this->realTimeService->getRealTimeEarningsSummary($userId);
                break;
            case 'visitors':
                $data = $this->realTimeService->getLiveVisitorCount();
                break;
            case 'notifications':
                $data = $this->realTimeService->getUserNotifications($userId);
                break;
            default:
                $data = [
                    'dashboard' => $this->realTimeService->getUserDashboardData($userId),
                    'earnings' => $this->realTimeService->getRealTimeEarningsSummary($userId),
                    'live_visitors' => $this->realTimeService->getLiveVisitorCount(),
                ];
        }

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get real-time analytics for API consumption
     */
    public function getApiAnalytics(Request $request): JsonResponse
    {
        $endpoint = $request->input('endpoint');
        $userId = auth()->id();

        switch ($endpoint) {
            case 'user_dashboard':
                $data = $this->realTimeService->getUserDashboardData($userId);
                break;
            case 'global_stats':
                $data = $this->realTimeService->getGlobalStatistics();
                break;
            case 'link_analytics':
                $linkId = $request->input('link_id');
                if (!$linkId) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Link ID is required',
                    ], 400);
                }
                $data = $this->realTimeService->getLinkAnalytics($linkId);
                break;
            case 'blog_analytics':
                $blogId = $request->input('blog_id');
                if (!$blogId) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Blog ID is required',
                    ], 400);
                }
                $data = $this->realTimeService->getBlogAnalytics($blogId);
                break;
            case 'live_visitors':
                $data = $this->realTimeService->getLiveVisitorCount();
                break;
            case 'earnings_summary':
                $data = $this->realTimeService->getRealTimeEarningsSummary($userId);
                break;
            case 'notifications':
                $data = $this->realTimeService->getUserNotifications($userId);
                break;
            default:
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid endpoint',
                ], 400);
        }

        return response()->json([
            'success' => true,
            'data' => $data,
            'timestamp' => now()->toISOString(),
        ]);
    }
}
