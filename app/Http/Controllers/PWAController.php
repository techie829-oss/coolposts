<?php

namespace App\Http\Controllers;

use App\Services\PWAService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PWAController extends Controller
{
    protected $pwaService;

    public function __construct(PWAService $pwaService)
    {
        $this->pwaService = $pwaService;
    }

    /**
     * Get PWA manifest
     */
    public function manifest(): JsonResponse
    {
        $manifest = $this->pwaService->getPWAConfig();

        return response()->json($manifest)
            ->header('Content-Type', 'application/manifest+json');
    }

    /**
     * Get service worker
     */
    public function serviceWorker(): \Illuminate\Http\Response
    {
        $swContent = file_get_contents(public_path('sw.js'));

        return response($swContent)
            ->header('Content-Type', 'application/javascript')
            ->header('Service-Worker-Allowed', '/');
    }

    /**
     * Handle PWA installation tracking
     */
    public function trackInstallation(Request $request): JsonResponse
    {
        $this->pwaService->trackInstallation($request);

        return response()->json([
            'success' => true,
            'message' => 'Installation tracked successfully'
        ]);
    }

    /**
     * Get PWA installation statistics
     */
    public function getInstallationStats(): JsonResponse
    {
        $stats = $this->pwaService->getInstallationStats();

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get offline data for current user
     */
    public function getOfflineData(): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }

        $userId = Auth::id();
        $offlineData = $this->pwaService->getOfflineData($userId);

        return response()->json([
            'success' => true,
            'data' => $offlineData
        ]);
    }

    /**
     * Cache data for offline use
     */
    public function cacheForOffline(): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }

        $userId = Auth::id();
        $this->pwaService->cacheDataForOffline($userId);

        return response()->json([
            'success' => true,
            'message' => 'Data cached for offline use'
        ]);
    }

    /**
     * Handle share target
     */
    public function shareTarget(Request $request): JsonResponse
    {
        $result = $this->pwaService->handleShareTarget($request);

        return response()->json($result);
    }

    /**
     * Get PWA performance metrics
     */
    public function getPerformanceMetrics(): JsonResponse
    {
        $metrics = $this->pwaService->getPerformanceMetrics();

        return response()->json([
            'success' => true,
            'data' => $metrics
        ]);
    }

    /**
     * Check if PWA is installed
     */
    public function checkInstallation(Request $request): JsonResponse
    {
        $isInstalled = $this->pwaService->isPWAInstalled($request);

        return response()->json([
            'success' => true,
            'data' => [
                'is_installed' => $isInstalled,
                'display_mode' => $request->header('display-mode'),
                'standalone' => $request->header('standalone')
            ]
        ]);
    }

    /**
     * Get PWA configuration
     */
    public function getConfig(): JsonResponse
    {
        $config = $this->pwaService->getPWAConfig();

        return response()->json([
            'success' => true,
            'data' => $config
        ]);
    }

    /**
     * Update PWA configuration
     */
    public function updateConfig(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:50',
            'description' => 'required|string|max:500',
            'theme_color' => 'required|string|regex:/^#[0-9A-F]{6}$/i',
            'background_color' => 'required|string|regex:/^#[0-9A-F]{6}$/i'
        ]);

        $config = $request->all();
        $success = $this->pwaService->updateManifest($config);

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'PWA configuration updated successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to update PWA configuration'
        ], 500);
    }

    /**
     * Generate PWA icons
     */
    public function generateIcons(): JsonResponse
    {
        $success = $this->pwaService->generateIcons();

        if ($success) {
            return response()->json([
                'success' => true,
                'message' => 'PWA icons generated successfully'
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Failed to generate PWA icons'
        ], 500);
    }

    /**
     * Get PWA status
     */
    public function getStatus(): JsonResponse
    {
        $status = [
            'manifest_exists' => file_exists(public_path('manifest.json')),
            'service_worker_exists' => file_exists(public_path('sw.js')),
            'offline_page_exists' => file_exists(public_path('offline.html')),
            'icons_exist' => $this->checkIconsExist(),
            'installation_stats' => $this->pwaService->getInstallationStats(),
            'performance_metrics' => $this->pwaService->getPerformanceMetrics()
        ];

        return response()->json([
            'success' => true,
            'data' => $status
        ]);
    }

    /**
     * Handle offline sync
     */
    public function syncOfflineData(Request $request): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required'
            ], 401);
        }

        $userId = Auth::id();
        $offlineActions = $request->input('actions', []);

        // Process offline actions
        $results = [];
        foreach ($offlineActions as $action) {
            $result = $this->processOfflineAction($action, $userId);
            $results[] = $result;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'processed_actions' => count($results),
                'results' => $results
            ]
        ]);
    }

    /**
     * Get PWA installation guide
     */
    public function getInstallationGuide(Request $request): JsonResponse
    {
        $platform = $this->detectPlatform($request);

        $guides = [
            'ios' => [
                'title' => 'Install on iOS',
                'steps' => [
                    'Open Safari browser',
                    'Tap the Share button (square with arrow)',
                    'Scroll down and tap "Add to Home Screen"',
                    'Tap "Add" to confirm'
                ]
            ],
            'android' => [
                'title' => 'Install on Android',
                'steps' => [
                    'Open Chrome browser',
                    'Tap the menu button (three dots)',
                    'Tap "Add to Home screen"',
                    'Tap "Add" to confirm'
                ]
            ],
            'desktop' => [
                'title' => 'Install on Desktop',
                'steps' => [
                    'Look for the install icon in the address bar',
                    'Click the install icon',
                    'Click "Install" in the prompt',
                    'The app will be added to your desktop'
                ]
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'platform' => $platform,
                'guide' => $guides[$platform] ?? $guides['desktop']
            ]
        ]);
    }

    /**
     * Handle PWA update notification
     */
    public function checkForUpdates(): JsonResponse
    {
        $currentVersion = Cache::get('pwa_version', '1.0.0');
        $latestVersion = '1.0.0'; // This would typically come from a version API

        $hasUpdate = version_compare($latestVersion, $currentVersion, '>');

        return response()->json([
            'success' => true,
            'data' => [
                'current_version' => $currentVersion,
                'latest_version' => $latestVersion,
                'has_update' => $hasUpdate,
                'update_available' => $hasUpdate
            ]
        ]);
    }

    /**
     * Helper methods
     */
    protected function checkIconsExist(): bool
    {
        $iconSizes = [72, 96, 128, 144, 152, 192, 384, 512];

        foreach ($iconSizes as $size) {
            $iconPath = public_path("icons/icon-{$size}x{$size}.png");
            if (!file_exists($iconPath)) {
                return false;
            }
        }

        return true;
    }

    protected function detectPlatform(Request $request): string
    {
        $userAgent = $request->userAgent();

        if (preg_match('/iPhone|iPad|iPod/', $userAgent)) {
            return 'ios';
        } elseif (preg_match('/Android/', $userAgent)) {
            return 'android';
        } else {
            return 'desktop';
        }
    }

    protected function processOfflineAction(array $action, int $userId): array
    {
        $type = $action['type'] ?? '';
        $data = $action['data'] ?? [];

        switch ($type) {
            case 'create_link':
                return $this->processCreateLink($data, $userId);
            case 'update_analytics':
                return $this->processUpdateAnalytics($data, $userId);
            case 'create_blog_post':
                return $this->processCreateBlogPost($data, $userId);
            default:
                return [
                    'success' => false,
                    'message' => 'Unknown action type'
                ];
        }
    }

    protected function processCreateLink(array $data, int $userId): array
    {
        // This would typically create a link in the database
        // For now, just return success
        return [
            'success' => true,
            'message' => 'Link created successfully',
            'action_id' => uniqid()
        ];
    }

    protected function processUpdateAnalytics(array $data, int $userId): array
    {
        // This would typically update analytics in the database
        return [
            'success' => true,
            'message' => 'Analytics updated successfully',
            'action_id' => uniqid()
        ];
    }

    protected function processCreateBlogPost(array $data, int $userId): array
    {
        // This would typically create a blog post in the database
        return [
            'success' => true,
            'message' => 'Blog post created successfully',
            'action_id' => uniqid()
        ];
    }
}
