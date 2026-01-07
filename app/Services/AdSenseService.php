<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AdSenseService
{
    protected $clientId;
    protected $clientSecret;
    protected $refreshToken;
    protected $accessToken;
    protected $baseUrl = 'https://www.googleapis.com/adsense/v2';

    public function __construct()
    {
        $this->clientId = config('services.adsense.client_id');
        $this->clientSecret = config('services.adsense.client_secret');
        $this->refreshToken = config('services.adsense.refresh_token');
    }

    /**
     * Get AdSense account information
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

            Log::error('AdSense API Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('AdSense Service Error: ' . $e->getMessage());
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
            ])->get($this->baseUrl . "/accounts/{$accountId}/adclients/ca-{$accountId}/adunits");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('AdSense Ad Units Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('AdSense Service Error: ' . $e->getMessage());
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
                'dateRange' => 'CUSTOM',
                'startDate.year' => $startDate->year,
                'startDate.month' => $startDate->month,
                'startDate.day' => $startDate->day,
                'endDate.year' => $endDate->year,
                'endDate.month' => $endDate->month,
                'endDate.day' => $endDate->day,
                'metrics' => 'EARNINGS,IMPRESSIONS,CLICKS',
                'dimensions' => 'DATE',
            ];

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
                'Content-Type' => 'application/json',
            ])->get($this->baseUrl . "/accounts/{$accountId}/reports", $params);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('AdSense Reports Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('AdSense Service Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Generate AdSense ad code
     */
    public function generateAdCode($adUnitId, $adFormat = 'auto')
    {
        $formats = [
            'auto' => 'auto',
            'banner' => '728x90',
            'medium_rectangle' => '300x250',
            'large_rectangle' => '336x280',
            'leaderboard' => '728x90',
            'mobile_banner' => '320x50',
            'mobile_leaderboard' => '320x100',
        ];

        $format = $formats[$adFormat] ?? 'auto';

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
     * Get access token using refresh token
     */
    protected function getAccessToken()
    {
        // Check if we have a cached access token
        if (Cache::has('adsense_access_token')) {
            return Cache::get('adsense_access_token');
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
                Cache::put('adsense_access_token', $accessToken, 3000);

                return $accessToken;
            }

            Log::error('AdSense Token Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('AdSense Token Service Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Create a new ad unit
     */
    public function createAdUnit($accountId, $name, $adFormat = 'auto')
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
            ])->post($this->baseUrl . "/accounts/{$accountId}/adclients/ca-{$accountId}/adunits", $data);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('AdSense Create Ad Unit Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('AdSense Service Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get real-time earnings
     */
    public function getRealTimeEarnings($accountId)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->getAccessToken(),
                'Content-Type' => 'application/json',
            ])->get($this->baseUrl . "/accounts/{$accountId}/reports", [
                'dateRange' => 'TODAY',
                'metrics' => 'EARNINGS,IMPRESSIONS,CLICKS',
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('AdSense Real-time Earnings Error: ' . $response->body());
            return null;
        } catch (\Exception $e) {
            Log::error('AdSense Service Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Check if AdSense is properly configured
     */
    public function isConfigured()
    {
        return !empty($this->clientId) &&
               !empty($this->clientSecret) &&
               !empty($this->refreshToken);
    }

    /**
     * Get AdSense configuration status
     */
    public function getConfigurationStatus()
    {
        if (!$this->isConfigured()) {
            return [
                'status' => 'not_configured',
                'message' => 'AdSense is not properly configured',
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
                'message' => 'AdSense is properly configured and connected',
                'account_info' => $accountInfo
            ];
        }

        return [
            'status' => 'error',
            'message' => 'AdSense is configured but API connection failed',
        ];
    }
}
