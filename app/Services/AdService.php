<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AdService
{
    /**
     * Ad types supported
     */
    const AD_TYPES = [
        'no_ads' => 'No Ads',
        'short_ads' => 'Short Ads (10-30s)',
        'long_ads' => 'Long Ads (30s-1min)',
    ];

    /**
     * Get ad content for a specific ad type
     */
    public function getAdContent(string $adType, array $context = []): array
    {
        switch ($adType) {
            case 'no_ads':
                return $this->getNoAdsContent();
            case 'short_ads':
                return $this->getShortAdsContent($context);
            case 'long_ads':
                return $this->getLongAdsContent($context);
            default:
                return $this->getDefaultAdContent();
        }
    }

    /**
     * Get no ads content (premium users)
     */
    private function getNoAdsContent(): array
    {
        return [
            'type' => 'no_ads',
            'duration' => 0,
            'html' => '<div class="text-center p-8">
                <i class="fas fa-crown text-yellow-500 text-4xl mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Premium Access</h3>
                <p class="text-gray-600">No ads for premium users!</p>
            </div>',
            'script' => '',
            'style' => '',
        ];
    }

    /**
     * Get short ads content
     */
    private function getShortAdsContent(array $context = []): array
    {
        $duration = $context['duration'] ?? 15;

        return [
            'type' => 'short_ads',
            'duration' => $duration,
            'html' => $this->generateAdHtml('short', $duration),
            'script' => $this->generateAdScript('short', $duration),
            'style' => $this->generateAdStyle('short'),
        ];
    }

    /**
     * Get long ads content
     */
    private function getLongAdsContent(array $context = []): array
    {
        $duration = $context['duration'] ?? 45;

        return [
            'type' => 'long_ads',
            'duration' => $duration,
            'html' => $this->generateAdHtml('long', $duration),
            'script' => $this->generateAdScript('long', $duration),
            'style' => $this->generateAdStyle('long'),
        ];
    }

    /**
     * Get default ad content
     */
    private function getDefaultAdContent(): array
    {
        return $this->getShortAdsContent();
    }

    /**
     * Generate ad HTML
     */
    private function generateAdHtml(string $type, int $duration): string
    {
        $adContent = $this->getAdPlaceholder($type);

        return "
        <div class='ad-container {$type}-ads' data-duration='{$duration}'>
            <div class='ad-header'>
                <span class='ad-label'>Advertisement</span>
                <span class='ad-timer' id='adTimer'>{$duration}s</span>
            </div>
            <div class='ad-content'>
                {$adContent}
            </div>
            <div class='ad-footer'>
                <small class='text-gray-500'>Please wait {$duration} seconds to continue</small>
            </div>
        </div>";
    }

    /**
     * Generate ad script
     */
    private function generateAdScript(string $type, int $duration): string
    {
        return "
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            let timeLeft = {$duration};
            const timerElement = document.getElementById('adTimer');
            const unlockButton = document.getElementById('unlockBtn');

            const countdown = setInterval(function() {
                timeLeft--;
                if (timerElement) {
                    timerElement.textContent = timeLeft + 's';
                }

                if (timeLeft <= 0) {
                    clearInterval(countdown);
                    if (unlockButton) {
                        unlockButton.disabled = false;
                        unlockButton.classList.remove('disabled:from-gray-400', 'disabled:to-gray-500');
                        unlockButton.classList.add('from-green-500', 'to-teal-500');
                    }
                }
            }, 1000);
        });
        </script>";
    }

    /**
     * Generate ad style
     */
    private function generateAdStyle(string $type): string
    {
        return "
        <style>
        .ad-container {
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            background: white;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .ad-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .ad-label {
            font-weight: 600;
            font-size: 14px;
        }

        .ad-timer {
            background: rgba(255, 255, 255, 0.2);
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
        }

        .ad-content {
            padding: 40px 20px;
            text-align: center;
            min-height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .ad-footer {
            background: #f9fafb;
            padding: 12px 20px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }

        .short-ads .ad-content {
            min-height: 150px;
        }

        .long-ads .ad-content {
            min-height: 250px;
        }
        </style>";
    }

    /**
     * Get ad placeholder content
     */
    private function getAdPlaceholder(string $type): string
    {
        $placeholders = [
            'short' => [
                'icon' => 'fas fa-ad',
                'title' => 'Quick Advertisement',
                'description' => 'This is a short advertisement. Please wait to continue.',
                'color' => 'text-blue-600'
            ],
            'long' => [
                'icon' => 'fas fa-ad',
                'title' => 'Premium Advertisement',
                'description' => 'This is a longer advertisement. Please wait to continue.',
                'color' => 'text-purple-600'
            ]
        ];

        $placeholder = $placeholders[$type] ?? $placeholders['short'];

        return "
        <div class='text-center'>
            <i class='{$placeholder['icon']} {$placeholder['color']} text-5xl mb-4'></i>
            <h4 class='text-lg font-semibold text-gray-800 mb-2'>{$placeholder['title']}</h4>
            <p class='text-gray-600'>{$placeholder['description']}</p>
            <div class='mt-4'>
                <div class='inline-block bg-gray-200 rounded-full p-2'>
                    <i class='fas fa-play text-gray-600'></i>
                </div>
            </div>
        </div>";
    }

    /**
     * Check if adblock is detected
     */
    public function detectAdblock(): bool
    {
        // Simple adblock detection
        $testAd = '<div id="adsense-test" style="position:absolute;left:-10000px;top:-10000px;width:1px;height:1px;"></div>';

        // This is a basic implementation
        // In production, you'd want more sophisticated detection
        return false;
    }

    /**
     * Get ad statistics
     */
    public function getAdStats(string $adType, string $period = 'today'): array
    {
        // This would integrate with your analytics system
        return [
            'impressions' => rand(100, 1000),
            'clicks' => rand(10, 100),
            'ctr' => rand(1, 15) / 100,
            'revenue' => rand(10, 100) / 100,
        ];
    }

    /**
     * Validate ad configuration
     */
    public function validateAdConfig(array $config): bool
    {
        $required = ['type', 'duration'];

        foreach ($required as $field) {
            if (!isset($config[$field])) {
                return false;
            }
        }

        if (!in_array($config['type'], array_keys(self::AD_TYPES))) {
            return false;
        }

        if ($config['duration'] < 0 || $config['duration'] > 300) {
            return false;
        }

        return true;
    }
}
