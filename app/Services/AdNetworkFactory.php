<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class AdNetworkFactory
{
    protected $adNetworks = [];
    protected $primaryNetwork;
    protected $fallbackNetworks = [];

    public function __construct()
    {
        $this->initializeNetworks();
    }

    /**
     * Initialize available ad networks
     */
    protected function initializeNetworks()
    {
        // Initialize AdSense
        if (config('services.adsense.enabled', false)) {
            $this->adNetworks['adsense'] = new AdSenseService();
        }

        // Initialize AdMob
        if (config('services.admob.enabled', false)) {
            $this->adNetworks['admob'] = new AdMobService();
        }

        // Set primary network (AdSense by default)
        $this->primaryNetwork = config('services.adsense.enabled', false) ? 'adsense' : 'admob';

        // Set fallback networks
        $this->fallbackNetworks = array_keys($this->adNetworks);
        unset($this->fallbackNetworks[array_search($this->primaryNetwork, $this->fallbackNetworks)]);
    }

    /**
     * Get the primary ad network
     */
    public function getPrimaryNetwork()
    {
        return $this->adNetworks[$this->primaryNetwork] ?? null;
    }

    /**
     * Get a specific ad network
     */
    public function getNetwork($networkName)
    {
        return $this->adNetworks[$networkName] ?? null;
    }

    /**
     * Get all available networks
     */
    public function getAllNetworks()
    {
        return $this->adNetworks;
    }

    /**
     * Get available network names
     */
    public function getAvailableNetworks()
    {
        return array_keys($this->adNetworks);
    }

    /**
     * Generate ad code with fallback
     */
    public function generateAdCode($adUnitId, $adFormat = 'auto', $preferredNetwork = null)
    {
        $networks = $this->getNetworkPriority($preferredNetwork);

        foreach ($networks as $networkName) {
            $network = $this->adNetworks[$networkName];

            if ($network && $network->isConfigured()) {
                try {
                    if ($networkName === 'adsense') {
                        return $network->generateAdCode($adUnitId, $adFormat);
                    } elseif ($networkName === 'admob') {
                        return $network->generateWebAdCode($adUnitId, $adFormat);
                    }
                } catch (\Exception $e) {
                    Log::error("Ad Network {$networkName} Error: " . $e->getMessage());
                    continue;
                }
            }
        }

        // Return placeholder ad if no networks are available
        return $this->generatePlaceholderAd($adFormat);
    }

    /**
     * Get earnings from all networks
     */
    public function getCombinedEarnings($startDate, $endDate)
    {
        $totalEarnings = [
            'total_earnings' => 0,
            'total_impressions' => 0,
            'total_clicks' => 0,
            'network_breakdown' => [],
        ];

        foreach ($this->adNetworks as $networkName => $network) {
            if ($network && $network->isConfigured()) {
                try {
                    $earnings = $this->getNetworkEarnings($network, $networkName, $startDate, $endDate);
                    if ($earnings) {
                        $totalEarnings['network_breakdown'][$networkName] = $earnings;
                        $totalEarnings['total_earnings'] += $earnings['earnings'] ?? 0;
                        $totalEarnings['total_impressions'] += $earnings['impressions'] ?? 0;
                        $totalEarnings['total_clicks'] += $earnings['clicks'] ?? 0;
                    }
                } catch (\Exception $e) {
                    Log::error("Error getting earnings from {$networkName}: " . $e->getMessage());
                }
            }
        }

        return $totalEarnings;
    }

    /**
     * Get real-time earnings from all networks
     */
    public function getRealTimeEarnings()
    {
        $totalEarnings = [
            'total_earnings' => 0,
            'total_impressions' => 0,
            'total_clicks' => 0,
            'network_breakdown' => [],
        ];

        foreach ($this->adNetworks as $networkName => $network) {
            if ($network && $network->isConfigured()) {
                try {
                    $earnings = $this->getNetworkRealTimeEarnings($network, $networkName);
                    if ($earnings) {
                        $totalEarnings['network_breakdown'][$networkName] = $earnings;
                        $totalEarnings['total_earnings'] += $earnings['earnings'] ?? 0;
                        $totalEarnings['total_impressions'] += $earnings['impressions'] ?? 0;
                        $totalEarnings['total_clicks'] += $earnings['clicks'] ?? 0;
                    }
                } catch (\Exception $e) {
                    Log::error("Error getting real-time earnings from {$networkName}: " . $e->getMessage());
                }
            }
        }

        return $totalEarnings;
    }

    /**
     * Get configuration status for all networks
     */
    public function getConfigurationStatus()
    {
        $status = [
            'overall_status' => 'not_configured',
            'networks' => [],
            'primary_network' => $this->primaryNetwork,
            'available_networks' => $this->getAvailableNetworks(),
        ];

        $configuredNetworks = 0;

        foreach ($this->adNetworks as $networkName => $network) {
            $networkStatus = $network->getConfigurationStatus();
            $status['networks'][$networkName] = $networkStatus;

            if ($networkStatus['status'] === 'active') {
                $configuredNetworks++;
            }
        }

        if ($configuredNetworks > 0) {
            $status['overall_status'] = 'active';
        } elseif ($configuredNetworks === 0 && count($this->adNetworks) > 0) {
            $status['overall_status'] = 'error';
        }

        return $status;
    }

    /**
     * Get network priority for fallback
     */
    protected function getNetworkPriority($preferredNetwork = null)
    {
        $networks = [];

        if ($preferredNetwork && isset($this->adNetworks[$preferredNetwork])) {
            $networks[] = $preferredNetwork;
        }

        if ($this->primaryNetwork && $this->primaryNetwork !== $preferredNetwork) {
            $networks[] = $this->primaryNetwork;
        }

        foreach ($this->fallbackNetworks as $fallbackNetwork) {
            if ($fallbackNetwork !== $preferredNetwork) {
                $networks[] = $fallbackNetwork;
            }
        }

        return array_unique($networks);
    }

    /**
     * Get earnings from a specific network
     */
    protected function getNetworkEarnings($network, $networkName, $startDate, $endDate)
    {
        $accountId = config("services.{$networkName}.account_id");

        if (!$accountId) {
            return null;
        }

        $earnings = $network->getEarningsReport($accountId, $startDate, $endDate);

        if ($earnings) {
            return [
                'earnings' => $earnings['earnings'] ?? 0,
                'impressions' => $earnings['impressions'] ?? 0,
                'clicks' => $earnings['clicks'] ?? 0,
                'currency' => $earnings['currency'] ?? 'USD',
                'network' => $networkName,
            ];
        }

        return null;
    }

    /**
     * Get real-time earnings from a specific network
     */
    protected function getNetworkRealTimeEarnings($network, $networkName)
    {
        $accountId = config("services.{$networkName}.account_id");

        if (!$accountId) {
            return null;
        }

        $earnings = $network->getRealTimeEarnings($accountId);

        if ($earnings) {
            return [
                'earnings' => $earnings['earnings'] ?? 0,
                'impressions' => $earnings['impressions'] ?? 0,
                'clicks' => $earnings['clicks'] ?? 0,
                'currency' => $earnings['currency'] ?? 'USD',
                'network' => $networkName,
            ];
        }

        return null;
    }

    /**
     * Generate placeholder ad when no networks are available
     */
    protected function generatePlaceholderAd($adFormat)
    {
        $formats = [
            'auto' => 'width: 100%; height: 90px;',
            'banner' => 'width: 728px; height: 90px;',
            'medium_rectangle' => 'width: 300px; height: 250px;',
            'large_rectangle' => 'width: 336px; height: 280px;',
            'leaderboard' => 'width: 728px; height: 90px;',
            'mobile_banner' => 'width: 320px; height: 50px;',
            'mobile_leaderboard' => 'width: 320px; height: 100px;',
        ];

        $style = $formats[$adFormat] ?? 'width: 100%; height: 90px;';

        return "
        <div class='ad-placeholder' style='{$style}; background: #f0f0f0; border: 1px solid #ddd; display: flex; align-items: center; justify-content: center; color: #666; font-size: 12px;'>
            <div style='text-align: center;'>
                <div style='font-weight: bold; margin-bottom: 5px;'>Ad Space</div>
                <div>Configure ad networks in admin panel</div>
            </div>
        </div>";
    }

    /**
     * Test all network connections
     */
    public function testAllConnections()
    {
        $results = [];

        foreach ($this->adNetworks as $networkName => $network) {
            $results[$networkName] = [
                'configured' => $network->isConfigured(),
                'connection_test' => false,
                'error' => null,
            ];

            if ($network->isConfigured()) {
                try {
                    $accountInfo = $network->getAccountInfo();
                    $results[$networkName]['connection_test'] = !empty($accountInfo);
                    $results[$networkName]['account_info'] = $accountInfo;
                } catch (\Exception $e) {
                    $results[$networkName]['error'] = $e->getMessage();
                }
            }
        }

        return $results;
    }

    /**
     * Get recommended ad format for device
     */
    public function getRecommendedAdFormat($deviceType = 'desktop')
    {
        $formats = [
            'desktop' => 'leaderboard',
            'tablet' => 'medium_rectangle',
            'mobile' => 'mobile_banner',
        ];

        return $formats[$deviceType] ?? 'auto';
    }

    /**
     * Check if any networks are available
     */
    public function hasAvailableNetworks()
    {
        return count($this->adNetworks) > 0;
    }

    /**
     * Get the best performing network
     */
    public function getBestPerformingNetwork()
    {
        $bestNetwork = null;
        $bestCTR = 0;

        foreach ($this->adNetworks as $networkName => $network) {
            if ($network && $network->isConfigured()) {
                // This would need to be implemented based on historical performance data
                // For now, return the primary network
                return $networkName;
            }
        }

        return $this->primaryNetwork;
    }
}
