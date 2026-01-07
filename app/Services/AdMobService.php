<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AdMobService
{
    protected $clientId;
    protected $clientSecret;
    protected $refreshToken;
    protected $accessToken;
    protected $baseUrl = 'https://admob.googleapis.com/v1';

    public function __construct()
    {
        $this->clientId = config('services.admob.client_id');
        $this->clientSecret = config('services.admob.client_secret');
        $this->refreshToken = config('services.admob.refresh_token');
    }

    /**
     * Get AdMob account information
     */
    public function getAccountInfo()
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
                'Content-Type' => 'application/json',
            ])->get($this->baseUrl . '/accounts');

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('AdMob API Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('AdMob Service Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get ad units for the account
     */
    public function getAdUnits($accountId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
                'Content-Type' => 'application/json',
            ])->get($this->baseUrl . "/accounts/{$accountId}/adUnits");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('AdMob Ad Units Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('AdMob Service Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get reports for earnings
     */
    public function getEarningsReport($accountId, $startDate, $endDate)
    {
        try {
            $params = [
                'dateRange' => [
                    'startDate' => [
                        'year' => $startDate->year,
                        'month' => $startDate->month,
                        'day' => $startDate->day,
                    ],
                    'endDate' => [
                        'year' => $endDate->year,
                        'month' => $endDate->month,
                        'day' => $endDate->day,
                    ],
                ],
                'metrics' => ['ESTIMATED_EARNINGS', 'IMPRESSIONS', 'CLICKS'],
                'dimensions' => ['DATE'],
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . "/accounts/{$accountId}/reports", $params);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('AdMob Reports Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('AdMob Service Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate AdMob ad code for web
     */
    public function generateWebAdCode($adUnitId, $adFormat = 'banner')
    {
        $formats = [
            'banner' => 'BANNER',
            'medium_rectangle' => 'MEDIUM_RECTANGLE',
            'large_rectangle' => 'LARGE_RECTANGLE',
            'leaderboard' => 'LEADERBOARD',
            'mobile_banner' => 'BANNER',
            'mobile_leaderboard' => 'LEADERBOARD',
        ];

        $format = $formats[$adFormat] ?? 'BANNER';

        return "
        <ins class='adsbygoogle'
             style='display:block'
             data-ad-client='ca-{$adUnitId}'
             data-ad-slot='{$adUnitId}'
             data-ad-format='{$format}'
             data-full-width-responsive='true'></ins>
        <script>
             (adsbygoogle = window.adsbygoogle || []).push({});
        </script>";
    }

    /**
     * Generate AdMob ad code for mobile apps
     */
    public function generateMobileAdCode($adUnitId, $adFormat = 'banner', $platform = 'android')
    {
        $formats = [
            'banner' => 'BANNER',
            'medium_rectangle' => 'MEDIUM_RECTANGLE',
            'large_rectangle' => 'LARGE_RECTANGLE',
            'interstitial' => 'INTERSTITIAL',
            'rewarded' => 'REWARDED',
        ];

        $format = $formats[$adFormat] ?? 'BANNER';

        if ($platform === 'ios') {
            return [
                'ad_unit_id' => $adUnitId,
                'format' => $format,
                'platform' => 'ios',
                'implementation' => 'Use iOS AdMob SDK with ad unit ID: ' . $adUnitId
            ];
        }

        return [
            'ad_unit_id' => $adUnitId,
            'format' => $format,
            'platform' => 'android',
            'implementation' => 'Use Android AdMob SDK with ad unit ID: ' . $adUnitId
        ];
    }

    /**
     * Get access token using refresh token
     */
    protected function getAccessToken()
    {
        // Check if we have a cached access token
        if (Cache::has('admob_access_token')) {
            return Cache::get('admob_access_token');
        }

        try {
            $response = Http::asForm()->post('https://oauth2.googleapis.com/token', [
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
                'refresh_token' => $this->refreshToken,
                'grant_type' => 'refresh_token',
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $accessToken = $data['access_token'];

                // Cache the access token for 50 minutes (tokens expire in 1 hour)
                Cache::put('admob_access_token', $accessToken, 3000);

                return $accessToken;
            }

            Log::error('AdMob Token Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('AdMob Token Service Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create a new ad unit
     */
    public function createAdUnit($accountId, $name, $adFormat = 'BANNER')
    {
        try {
            $data = [
                'name' => $name,
                'adFormat' => $adFormat,
                'state' => 'ACTIVE',
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . "/accounts/{$accountId}/adUnits", $data);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('AdMob Create Ad Unit Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('AdMob Service Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get real-time earnings
     */
    public function getRealTimeEarnings($accountId)
    {
        try {
            $params = [
                'dateRange' => [
                    'startDate' => [
                        'year' => now()->year,
                        'month' => now()->month,
                        'day' => now()->day,
                    ],
                    'endDate' => [
                        'year' => now()->year,
                        'month' => now()->month,
                        'day' => now()->day,
                    ],
                ],
                'metrics' => ['ESTIMATED_EARNINGS', 'IMPRESSIONS', 'CLICKS'],
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . "/accounts/{$accountId}/reports", $params);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('AdMob Real-time Earnings Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('AdMob Service Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Check if AdMob is properly configured
     */
    public function isConfigured()
    {
        return !empty($this->clientId) &&
               !empty($this->clientSecret) &&
               !empty($this->refreshToken);
    }

    /**
     * Get AdMob configuration status
     */
    public function getConfigurationStatus()
    {
        if (!$this->isConfigured()) {
            return [
                'status' => 'not_configured',
                'message' => 'AdMob is not properly configured',
                'missing' => [
                    'client_id' => empty($this->clientId),
                    'client_secret' => empty($this->clientSecret),
                    'refresh_token' => empty($this->refreshToken),
                ]
            ];
        }

        // Test API connection
        $accountInfo = $this->getAccountInfo();

        if ($accountInfo) {
            return [
                'status' => 'active',
                'message' => 'AdMob is properly configured and connected',
                'account_info' => $accountInfo
            ];
        }

        return [
            'status' => 'error',
            'message' => 'AdMob is configured but API connection failed',
        ];
    }
}
