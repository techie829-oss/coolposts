<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PWAService
{
    protected $manifestPath = '/manifest.json';
    protected $swPath = '/sw.js';
    protected $offlinePath = '/offline.html';

    /**
     * Get PWA configuration
     */
    public function getPWAConfig(): array
    {
        return [
            'name' => 'Link Sharing App',
            'short_name' => 'LinkShare',
            'description' => 'Professional link sharing and monetization platform',
            'start_url' => '/',
            'display' => 'standalone',
            'background_color' => '#ffffff',
            'theme_color' => '#8b5cf6',
            'orientation' => 'portrait-primary',
            'scope' => '/',
            'lang' => 'en',
            'categories' => ['business', 'productivity', 'utilities'],
            'icons' => $this->getIcons(),
            'shortcuts' => $this->getShortcuts(),
            'screenshots' => $this->getScreenshots(),
            'features' => $this->getFeatures(),
            'capabilities' => $this->getCapabilities()
        ];
    }

    /**
     * Get PWA icons configuration
     */
    protected function getIcons(): array
    {
        return [
            [
                'src' => '/icons/icon-72x72.png',
                'sizes' => '72x72',
                'type' => 'image/png',
                'purpose' => 'maskable any'
            ],
            [
                'src' => '/icons/icon-96x96.png',
                'sizes' => '96x96',
                'type' => 'image/png',
                'purpose' => 'maskable any'
            ],
            [
                'src' => '/icons/icon-128x128.png',
                'sizes' => '128x128',
                'type' => 'image/png',
                'purpose' => 'maskable any'
            ],
            [
                'src' => '/icons/icon-144x144.png',
                'sizes' => '144x144',
                'type' => 'image/png',
                'purpose' => 'maskable any'
            ],
            [
                'src' => '/icons/icon-152x152.png',
                'sizes' => '152x152',
                'type' => 'image/png',
                'purpose' => 'maskable any'
            ],
            [
                'src' => '/icons/icon-192x192.png',
                'sizes' => '192x192',
                'type' => 'image/png',
                'purpose' => 'maskable any'
            ],
            [
                'src' => '/icons/icon-384x384.png',
                'sizes' => '384x384',
                'type' => 'image/png',
                'purpose' => 'maskable any'
            ],
            [
                'src' => '/icons/icon-512x512.png',
                'sizes' => '512x512',
                'type' => 'image/png',
                'purpose' => 'maskable any'
            ]
        ];
    }

    /**
     * Get PWA shortcuts configuration
     */
    protected function getShortcuts(): array
    {
        return [
            [
                'name' => 'Dashboard',
                'short_name' => 'Dashboard',
                'description' => 'View your dashboard and analytics',
                'url' => '/dashboard',
                'icons' => [
                    [
                        'src' => '/icons/dashboard-96x96.png',
                        'sizes' => '96x96'
                    ]
                ]
            ],
            [
                'name' => 'Create Link',
                'short_name' => 'New Link',
                'description' => 'Create a new shortened link',
                'url' => '/links/create',
                'icons' => [
                    [
                        'src' => '/icons/create-link-96x96.png',
                        'sizes' => '96x96'
                    ]
                ]
            ],
            [
                'name' => 'Analytics',
                'short_name' => 'Analytics',
                'description' => 'View your analytics and earnings',
                'url' => '/analytics',
                'icons' => [
                    [
                        'src' => '/icons/analytics-96x96.png',
                        'sizes' => '96x96'
                    ]
                ]
            ],
            [
                'name' => 'Blog Posts',
                'short_name' => 'Blog',
                'description' => 'Manage your blog posts',
                'url' => '/blog-posts',
                'icons' => [
                    [
                        'src' => '/icons/blog-96x96.png',
                        'sizes' => '96x96'
                    ]
                ]
            ]
        ];
    }

    /**
     * Get PWA screenshots configuration
     */
    protected function getScreenshots(): array
    {
        return [
            [
                'src' => '/screenshots/mobile-dashboard.png',
                'sizes' => '390x844',
                'type' => 'image/png',
                'form_factor' => 'narrow',
                'label' => 'Dashboard view on mobile'
            ],
            [
                'src' => '/screenshots/mobile-analytics.png',
                'sizes' => '390x844',
                'type' => 'image/png',
                'form_factor' => 'narrow',
                'label' => 'Analytics view on mobile'
            ],
            [
                'src' => '/screenshots/desktop-dashboard.png',
                'sizes' => '1280x720',
                'type' => 'image/png',
                'form_factor' => 'wide',
                'label' => 'Dashboard view on desktop'
            ]
        ];
    }

    /**
     * Get PWA features
     */
    protected function getFeatures(): array
    {
        return [
            'offline_support' => true,
            'push_notifications' => true,
            'background_sync' => true,
            'install_prompt' => true,
            'native_app_experience' => true,
            'responsive_design' => true,
            'fast_loading' => true,
            'secure_connections' => true
        ];
    }

    /**
     * Get PWA capabilities
     */
    protected function getCapabilities(): array
    {
        return [
            'file_handling' => [
                'enabled' => true,
                'types' => ['text/plain', 'text/html', 'application/json']
            ],
            'protocol_handling' => [
                'enabled' => true,
                'protocols' => ['web+linkshare']
            ],
            'share_target' => [
                'enabled' => true,
                'action' => '/share-target',
                'method' => 'POST',
                'enctype' => 'multipart/form-data',
                'params' => [
                    'title' => 'title',
                    'text' => 'text',
                    'url' => 'url'
                ]
            ]
        ];
    }

    /**
     * Check if PWA is installed
     */
    public function isPWAInstalled(Request $request): bool
    {
        $displayMode = $request->header('display-mode');
        $standalone = $request->header('standalone');

        return $displayMode === 'standalone' || $standalone === '1';
    }

    /**
     * Get installation statistics
     */
    public function getInstallationStats(): array
    {
        return Cache::remember('pwa_installation_stats', 3600, function () {
            return [
                'total_installs' => $this->getTotalInstalls(),
                'mobile_installs' => $this->getMobileInstalls(),
                'desktop_installs' => $this->getDesktopInstalls(),
                'recent_installs' => $this->getRecentInstalls(),
                'installation_rate' => $this->getInstallationRate()
            ];
        });
    }

    /**
     * Track PWA installation
     */
    public function trackInstallation(Request $request): void
    {
        $installData = [
            'user_agent' => $request->userAgent(),
            'ip_address' => $request->ip(),
            'platform' => $this->detectPlatform($request),
            'timestamp' => now()->toISOString(),
            'display_mode' => $request->header('display-mode'),
            'standalone' => $request->header('standalone')
        ];

        // Store installation data
        $this->storeInstallationData($installData);

        // Update statistics
        Cache::forget('pwa_installation_stats');
    }

    /**
     * Get offline data for user
     */
    public function getOfflineData(int $userId): array
    {
        return Cache::remember("offline_data_{$userId}", 3600, function () use ($userId) {
            return [
                'links' => $this->getUserLinks($userId),
                'analytics' => $this->getUserAnalytics($userId),
                'blog_posts' => $this->getUserBlogPosts($userId),
                'earnings' => $this->getUserEarnings($userId),
                'settings' => $this->getUserSettings($userId)
            ];
        });
    }

    /**
     * Cache data for offline use
     */
    public function cacheDataForOffline(int $userId): void
    {
        $offlineData = $this->getOfflineData($userId);

        // Store in IndexedDB equivalent (using cache for now)
        Cache::put("offline_cache_{$userId}", $offlineData, 86400); // 24 hours
    }

    /**
     * Handle share target
     */
    public function handleShareTarget(Request $request): array
    {
        $sharedData = [
            'title' => $request->input('title'),
            'text' => $request->input('text'),
            'url' => $request->input('url'),
            'timestamp' => now()->toISOString()
        ];

        // Process shared content
        $processedData = $this->processSharedContent($sharedData);

        return [
            'success' => true,
            'data' => $processedData,
            'message' => 'Content shared successfully'
        ];
    }

    /**
     * Get PWA performance metrics
     */
    public function getPerformanceMetrics(): array
    {
        return [
            'load_time' => $this->getAverageLoadTime(),
            'cache_hit_rate' => $this->getCacheHitRate(),
            'offline_usage' => $this->getOfflineUsage(),
            'installation_success_rate' => $this->getInstallationSuccessRate(),
            'user_engagement' => $this->getUserEngagement()
        ];
    }

    /**
     * Update PWA manifest
     */
    public function updateManifest(array $config): bool
    {
        try {
            $manifestContent = json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
            Storage::disk('public')->put('manifest.json', $manifestContent);

            // Clear cache
            Cache::forget('pwa_config');

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to update PWA manifest: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Generate PWA icons
     */
    public function generateIcons(): bool
    {
        try {
            // This would typically use an image processing library
            // For now, we'll just create placeholder files
            $iconSizes = [72, 96, 128, 144, 152, 192, 384, 512];

            foreach ($iconSizes as $size) {
                $iconPath = "icons/icon-{$size}x{$size}.png";
                // Create placeholder icon
                $this->createPlaceholderIcon($iconPath, $size);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to generate PWA icons: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Helper methods
     */
    protected function detectPlatform(Request $request): string
    {
        $userAgent = $request->userAgent();

        if (preg_match('/iPhone|iPad|iPod/', $userAgent)) {
            return 'ios';
        } elseif (preg_match('/Android/', $userAgent)) {
            return 'android';
        } elseif (preg_match('/Windows/', $userAgent)) {
            return 'windows';
        } elseif (preg_match('/Mac/', $userAgent)) {
            return 'macos';
        } elseif (preg_match('/Linux/', $userAgent)) {
            return 'linux';
        }

        return 'unknown';
    }

    protected function storeInstallationData(array $data): void
    {
        // Store in database or cache
        $installations = Cache::get('pwa_installations', []);
        $installations[] = $data;
        Cache::put('pwa_installations', $installations, 86400 * 30); // 30 days
    }

    protected function getTotalInstalls(): int
    {
        $installations = Cache::get('pwa_installations', []);
        return count($installations);
    }

    protected function getMobileInstalls(): int
    {
        $installations = Cache::get('pwa_installations', []);
        return count(array_filter($installations, fn($install) =>
            in_array($install['platform'], ['ios', 'android'])
        ));
    }

    protected function getDesktopInstalls(): int
    {
        $installations = Cache::get('pwa_installations', []);
        return count(array_filter($installations, fn($install) =>
            in_array($install['platform'], ['windows', 'macos', 'linux'])
        ));
    }

    protected function getRecentInstalls(): int
    {
        $installations = Cache::get('pwa_installations', []);
        $recentDate = now()->subDays(7);

        return count(array_filter($installations, fn($install) =>
            \Carbon\Carbon::parse($install['timestamp'])->isAfter($recentDate)
        ));
    }

    protected function getInstallationRate(): float
    {
        $totalVisits = Cache::get('total_visits', 1000);
        $totalInstalls = $this->getTotalInstalls();

        return $totalVisits > 0 ? ($totalInstalls / $totalVisits) * 100 : 0;
    }

    protected function getUserLinks(int $userId): array
    {
        // This would query the database
        return [];
    }

    protected function getUserAnalytics(int $userId): array
    {
        // This would query the database
        return [];
    }

    protected function getUserBlogPosts(int $userId): array
    {
        // This would query the database
        return [];
    }

    protected function getUserEarnings(int $userId): array
    {
        // This would query the database
        return [];
    }

    protected function getUserSettings(int $userId): array
    {
        // This would query the database
        return [];
    }

    protected function processSharedContent(array $data): array
    {
        // Process shared content (e.g., create link, save to bookmarks)
        return $data;
    }

    protected function getAverageLoadTime(): float
    {
        return Cache::get('avg_load_time', 2.5);
    }

    protected function getCacheHitRate(): float
    {
        return Cache::get('cache_hit_rate', 85.5);
    }

    protected function getOfflineUsage(): int
    {
        return Cache::get('offline_usage', 150);
    }

    protected function getInstallationSuccessRate(): float
    {
        return Cache::get('installation_success_rate', 92.3);
    }

    protected function getUserEngagement(): float
    {
        return Cache::get('user_engagement', 78.9);
    }

    protected function createPlaceholderIcon(string $path, int $size): void
    {
        // Create a simple placeholder icon
        $image = imagecreatetruecolor($size, $size);
        $bgColor = imagecolorallocate($image, 139, 92, 246); // Purple
        $textColor = imagecolorallocate($image, 255, 255, 255); // White

        imagefill($image, 0, 0, $bgColor);

        // Add text
        $text = 'LS';
        $fontSize = $size / 4;
        $fontFile = storage_path('fonts/arial.ttf');

        if (file_exists($fontFile)) {
            imagettftext($image, $fontSize, 0, $size/3, $size/2, $textColor, $fontFile, $text);
        } else {
            imagestring($image, 5, $size/3, $size/3, $text, $textColor);
        }

        // Save image
        $fullPath = storage_path("app/public/{$path}");
        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        imagepng($image, $fullPath);
        imagedestroy($image);
    }
}
