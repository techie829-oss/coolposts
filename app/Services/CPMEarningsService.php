<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class CPMEarningsService
{
    protected $geoIPService;

    public function __construct(GeoIPService $geoIPService)
    {
        $this->geoIPService = $geoIPService;
    }

    /**
     * CPM rates by country (USD per 1000 impressions)
     */
    const CPM_RATES = [
        'US' => 2.50,    // United States
        'CA' => 2.20,    // Canada
        'GB' => 2.10,    // United Kingdom
        'DE' => 1.90,    // Germany
        'FR' => 1.80,    // France
        'AU' => 1.70,    // Australia
        'JP' => 1.60,    // Japan
        'NL' => 1.50,    // Netherlands
        'SE' => 1.40,    // Sweden
        'NO' => 1.30,    // Norway
        'DK' => 1.20,    // Denmark
        'CH' => 1.10,    // Switzerland
        'IN' => 0.80,    // India
        'BR' => 0.70,    // Brazil
        'MX' => 0.60,    // Mexico
        'RU' => 0.50,    // Russia
        'CN' => 0.40,    // China
        'ID' => 0.30,    // Indonesia
        'NG' => 0.25,    // Nigeria
        'PK' => 0.20,    // Pakistan
    ];

    /**
     * Device multipliers
     */
    const DEVICE_MULTIPLIERS = [
        'desktop' => 1.0,
        'mobile' => 0.8,
        'tablet' => 0.9,
    ];

    /**
     * Time-based multipliers
     */
    const TIME_MULTIPLIERS = [
        'peak_hours' => 1.2,    // 9 AM - 5 PM
        'off_peak' => 0.8,      // 12 AM - 6 AM
        'normal' => 1.0,        // Other hours
    ];

    /**
     * Calculate earnings based on CPM and context
     */
    public function calculateEarnings(array $context = []): array
    {
        $country = $context['country'] ?? 'US';
        $device = $context['device'] ?? 'desktop';
        $timeOfDay = $context['time_of_day'] ?? 'normal';
        $adType = $context['ad_type'] ?? 'short_ads';
        $isPremium = $context['is_premium'] ?? false;

        // Get base CPM rate for country
        $baseCPM = $this->getCPMRate($country);

        // Apply device multiplier
        $deviceMultiplier = $this->getDeviceMultiplier($device);

        // Apply time-based multiplier
        $timeMultiplier = $this->getTimeMultiplier($timeOfDay);

        // Apply ad type multiplier
        $adTypeMultiplier = $this->getAdTypeMultiplier($adType);

        // Apply premium multiplier
        $premiumMultiplier = $isPremium ? 1.5 : 1.0;

        // Calculate final CPM
        $finalCPM = $baseCPM * $deviceMultiplier * $timeMultiplier * $adTypeMultiplier * $premiumMultiplier;

        // Convert CPM to per-click earnings (assuming 1% CTR)
        $ctr = 0.01; // 1% click-through rate
        $earningsPerClick = ($finalCPM / 1000) * $ctr;

        return [
            'base_cpm' => $baseCPM,
            'final_cpm' => $finalCPM,
            'earnings_per_click' => $earningsPerClick,
            'country' => $country,
            'device' => $device,
            'time_of_day' => $timeOfDay,
            'ad_type' => $adType,
            'is_premium' => $isPremium,
            'multipliers' => [
                'device' => $deviceMultiplier,
                'time' => $timeMultiplier,
                'ad_type' => $adTypeMultiplier,
                'premium' => $premiumMultiplier,
                'total' => $deviceMultiplier * $timeMultiplier * $adTypeMultiplier * $premiumMultiplier,
            ],
        ];
    }

    /**
     * Get CPM rate for a country
     */
    public function getCPMRate(string $country): float
    {
        return self::CPM_RATES[strtoupper($country)] ?? 0.50; // Default rate
    }

    /**
     * Get CPM rate based on IP address using GeoIP service
     */
    public function getCPMRateByIP(string $ip): array
    {
        $countryData = $this->geoIPService->getCountry($ip);
        $countryCode = $countryData['country_code'] ?? 'US';

        $cpmRate = $this->getCPMRate($countryCode);
        $countryRates = $this->geoIPService->getCountryCPMRate($countryCode);

        return [
            'cpm_rate' => $cpmRate,
            'country_code' => $countryCode,
            'country_name' => $countryData['country_name'] ?? 'Unknown',
            'continent_code' => $countryData['continent_code'] ?? 'NA',
            'continent_name' => $countryData['continent_name'] ?? 'North America',
            'inr_rate' => $countryRates['inr'],
            'usd_rate' => $countryRates['usd'],
            'geoip_source' => $countryData['source'] ?? 'default',
        ];
    }

    /**
     * Calculate earnings with geographic data
     */
    public function calculateEarningsWithGeoIP(string $ip, array $context = []): array
    {
        $geoData = $this->getCPMRateByIP($ip);
        $locationData = $this->geoIPService->getLocationData($ip);

        $context['country'] = $geoData['country_code'];
        $context['continent'] = $geoData['continent_code'];

        $earnings = $this->calculateEarnings($context);

        return array_merge($earnings, [
            'geo_data' => $geoData,
            'location_data' => $locationData,
        ]);
    }

    /**
     * Get device multiplier
     */
    public function getDeviceMultiplier(string $device): float
    {
        return self::DEVICE_MULTIPLIERS[$device] ?? 1.0;
    }

    /**
     * Get time-based multiplier
     */
    public function getTimeMultiplier(string $timeOfDay): float
    {
        return self::TIME_MULTIPLIERS[$timeOfDay] ?? 1.0;
    }

    /**
     * Get ad type multiplier
     */
    public function getAdTypeMultiplier(string $adType): float
    {
        return match ($adType) {
            'no_ads' => 0.0,      // No ads = no earnings
            'short_ads' => 1.0,   // Base rate
            'long_ads' => 1.5,    // 50% more for longer ads
            default => 1.0,
        };
    }

    /**
     * Detect device type from user agent
     */
    public function detectDevice(string $userAgent): string
    {
        $userAgent = strtolower($userAgent);

        if (strpos($userAgent, 'mobile') !== false || strpos($userAgent, 'android') !== false || strpos($userAgent, 'iphone') !== false) {
            return 'mobile';
        }

        if (strpos($userAgent, 'tablet') !== false || strpos($userAgent, 'ipad') !== false) {
            return 'tablet';
        }

        return 'desktop';
    }

    /**
     * Get time of day category
     */
    public function getTimeOfDay(): string
    {
        $hour = (int) date('H');

        if ($hour >= 9 && $hour <= 17) {
            return 'peak_hours';
        } elseif ($hour >= 0 && $hour <= 6) {
            return 'off_peak';
        } else {
            return 'normal';
        }
    }

    /**
     * Get country from IP address using GeoIP service
     */
    public function getCountryFromIP(string $ip): string
    {
        $countryData = $this->geoIPService->getCountry($ip);
        return $countryData['country_code'] ?? 'US';
    }

    /**
     * Calculate earnings for multiple currencies
     */
    public function calculateMultiCurrencyEarnings(float $earningsUSD, array $currencies = ['USD', 'INR']): array
    {
        $currencyService = app(CurrencyService::class);
        $result = [];

        foreach ($currencies as $currency) {
            if ($currency === 'USD') {
                $result[$currency] = $earningsUSD;
            } else {
                $result[$currency] = $currencyService->convert($earningsUSD, 'USD', $currency);
            }
        }

        return $result;
    }

    /**
     * Get earnings statistics
     */
    public function getEarningsStats(string $period = 'today'): array
    {
        // This would integrate with your analytics system
        return [
            'total_earnings' => rand(100, 1000) / 100,
            'total_clicks' => rand(1000, 10000),
            'average_cpm' => rand(150, 300) / 100,
            'top_countries' => [
                'US' => rand(20, 40),
                'CA' => rand(10, 20),
                'GB' => rand(8, 15),
                'DE' => rand(5, 12),
                'FR' => rand(3, 8),
            ],
            'device_breakdown' => [
                'desktop' => rand(40, 60),
                'mobile' => rand(30, 50),
                'tablet' => rand(5, 15),
            ],
        ];
    }

    /**
     * Update CPM rates (admin function)
     */
    public function updateCPMRates(array $rates): bool
    {
        try {
            // Validate rates
            foreach ($rates as $country => $rate) {
                if (!is_numeric($rate) || $rate < 0) {
                    throw new \InvalidArgumentException("Invalid rate for country: {$country}");
                }
            }

            // Store in cache for quick access
            Cache::put('cpm_rates', $rates, 3600); // 1 hour

            Log::info('CPM rates updated', ['rates' => $rates]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to update CPM rates', ['error' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Get current CPM rates
     */
    public function getCurrentCPMRates(): array
    {
        return Cache::get('cpm_rates', self::CPM_RATES);
    }

    /**
     * Calculate revenue projection
     */
    public function calculateRevenueProjection(int $impressions, string $country = 'US'): array
    {
        $cpm = $this->getCPMRate($country);
        $revenue = ($impressions / 1000) * $cpm;

        return [
            'impressions' => $impressions,
            'cpm' => $cpm,
            'revenue_usd' => $revenue,
            'revenue_inr' => app(CurrencyService::class)->convert($revenue, 'USD', 'INR'),
        ];
    }
}
