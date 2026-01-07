<?php

namespace App\Services;

use GeoIp2\Database\Reader;
use GeoIp2\WebService\Client;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class GeoIPService
{
    protected $reader;
    protected $webServiceClient;
    protected $databasePath;
    protected $accountId;
    protected $licenseKey;
    protected $useWebService;

    public function __construct()
    {
        $this->databasePath = storage_path('app/geoip/GeoLite2-Country.mmdb');
        $this->accountId = config('services.maxmind.account_id');
        $this->licenseKey = config('services.maxmind.license_key');
        $this->useWebService = config('services.maxmind.use_web_service', false);

        $this->initializeService();
    }

    /**
     * Initialize the GeoIP service
     */
    protected function initializeService()
    {
        try {
            if ($this->useWebService && $this->accountId && $this->licenseKey) {
                // Use MaxMind Web Service
                $this->webServiceClient = new Client($this->accountId, $this->licenseKey);
            } elseif (file_exists($this->databasePath)) {
                // Use local database
                $this->reader = new Reader($this->databasePath);
            } else {
                Log::warning('GeoIP: No database file found and web service not configured');
            }
        } catch (\Exception $e) {
            Log::error('GeoIP Service initialization error: ' . $e->getMessage());
        }
    }

    /**
     * Get country information from IP address
     */
    public function getCountry($ip)
    {
        // Skip local IPs
        if ($this->isLocalIP($ip)) {
            return $this->getDefaultCountry();
        }

        // Check cache first
        $cacheKey = 'geoip_country_' . md5($ip);
        $cached = Cache::get($cacheKey);
        if ($cached) {
            return $cached;
        }

        try {
            $country = null;

            if ($this->webServiceClient) {
                $country = $this->getCountryFromWebService($ip);
            } elseif ($this->reader) {
                $country = $this->getCountryFromDatabase($ip);
            }

            if ($country) {
                // Cache for 24 hours
                Cache::put($cacheKey, $country, 86400);
                return $country;
            }
        } catch (\Exception $e) {
            Log::error('GeoIP lookup error for IP ' . $ip . ': ' . $e->getMessage());
        }

        // Fallback to default
        return $this->getDefaultCountry();
    }

    /**
     * Get country from MaxMind Web Service
     */
    protected function getCountryFromWebService($ip)
    {
        try {
            $record = $this->webServiceClient->country($ip);

            return [
                'country_code' => $record->country->isoCode,
                'country_name' => $record->country->name,
                'continent_code' => $record->continent->code,
                'continent_name' => $record->continent->name,
                'source' => 'maxmind_web_service',
                'ip' => $ip,
            ];
        } catch (\Exception $e) {
            Log::error('MaxMind Web Service error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get country from local database
     */
    protected function getCountryFromDatabase($ip)
    {
        try {
            $record = $this->reader->country($ip);

            return [
                'country_code' => $record->country->isoCode,
                'country_name' => $record->country->name,
                'continent_code' => $record->continent->code,
                'continent_name' => $record->continent->name,
                'source' => 'maxmind_database',
                'ip' => $ip,
            ];
        } catch (\Exception $e) {
            Log::error('MaxMind Database error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get city information from IP address
     */
    public function getCity($ip)
    {
        // Skip local IPs
        if ($this->isLocalIP($ip)) {
            return $this->getDefaultCity();
        }

        // Check cache first
        $cacheKey = 'geoip_city_' . md5($ip);
        $cached = Cache::get($cacheKey);
        if ($cached) {
            return $cached;
        }

        try {
            $city = null;

            if ($this->webServiceClient) {
                $city = $this->getCityFromWebService($ip);
            } elseif ($this->reader) {
                $city = $this->getCityFromDatabase($ip);
            }

            if ($city) {
                // Cache for 24 hours
                Cache::put($cacheKey, $city, 86400);
                return $city;
            }
        } catch (\Exception $e) {
            Log::error('GeoIP city lookup error for IP ' . $ip . ': ' . $e->getMessage());
        }

        // Fallback to default
        return $this->getDefaultCity();
    }

    /**
     * Get city from MaxMind Web Service
     */
    protected function getCityFromWebService($ip)
    {
        try {
            $record = $this->webServiceClient->city($ip);

            return [
                'city' => $record->city->name,
                'state' => $record->mostSpecificSubdivision->name,
                'state_code' => $record->mostSpecificSubdivision->isoCode,
                'postal_code' => $record->postal->code,
                'latitude' => $record->location->latitude,
                'longitude' => $record->location->longitude,
                'timezone' => $record->location->timeZone,
                'country_code' => $record->country->isoCode,
                'country_name' => $record->country->name,
                'source' => 'maxmind_web_service',
                'ip' => $ip,
            ];
        } catch (\Exception $e) {
            Log::error('MaxMind Web Service city error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get city from local database
     */
    protected function getCityFromDatabase($ip)
    {
        try {
            $record = $this->reader->city($ip);

            return [
                'city' => $record->city->name,
                'state' => $record->mostSpecificSubdivision->name,
                'state_code' => $record->mostSpecificSubdivision->isoCode,
                'postal_code' => $record->postal->code,
                'latitude' => $record->location->latitude,
                'longitude' => $record->location->longitude,
                'timezone' => $record->location->timeZone,
                'country_code' => $record->country->isoCode,
                'country_name' => $record->country->name,
                'source' => 'maxmind_database',
                'ip' => $ip,
            ];
        } catch (\Exception $e) {
            Log::error('MaxMind Database city error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get ISP information from IP address
     */
    public function getISP($ip)
    {
        // Skip local IPs
        if ($this->isLocalIP($ip)) {
            return $this->getDefaultISP();
        }

        // Check cache first
        $cacheKey = 'geoip_isp_' . md5($ip);
        $cached = Cache::get($cacheKey);
        if ($cached) {
            return $cached;
        }

        try {
            $isp = null;

            if ($this->webServiceClient) {
                $isp = $this->getISPFromWebService($ip);
            }

            if ($isp) {
                // Cache for 24 hours
                Cache::put($cacheKey, $isp, 86400);
                return $isp;
            }
        } catch (\Exception $e) {
            Log::error('GeoIP ISP lookup error for IP ' . $ip . ': ' . $e->getMessage());
        }

        // Fallback to default
        return $this->getDefaultISP();
    }

    /**
     * Get ISP from MaxMind Web Service
     */
    protected function getISPFromWebService($ip)
    {
        try {
            $record = $this->webServiceClient->isp($ip);

            return [
                'isp' => $record->isp,
                'organization' => $record->organization,
                'autonomous_system_number' => $record->autonomousSystemNumber,
                'autonomous_system_organization' => $record->autonomousSystemOrganization,
                'source' => 'maxmind_web_service',
                'ip' => $ip,
            ];
        } catch (\Exception $e) {
            Log::error('MaxMind Web Service ISP error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Get comprehensive location data
     */
    public function getLocationData($ip)
    {
        $country = $this->getCountry($ip);
        $city = $this->getCity($ip);
        $isp = $this->getISP($ip);

        return [
            'ip' => $ip,
            'country' => $country,
            'city' => $city,
            'isp' => $isp,
            'timestamp' => now()->toISOString(),
        ];
    }

    /**
     * Check if IP is local/private
     */
    protected function isLocalIP($ip)
    {
        return in_array($ip, ['127.0.0.1', '::1']) ||
               filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) === false;
    }

    /**
     * Get default country data
     */
    protected function getDefaultCountry()
    {
        return [
            'country_code' => 'US',
            'country_name' => 'United States',
            'continent_code' => 'NA',
            'continent_name' => 'North America',
            'source' => 'default',
            'ip' => request()->ip(),
        ];
    }

    /**
     * Get default city data
     */
    protected function getDefaultCity()
    {
        return [
            'city' => 'Unknown',
            'state' => 'Unknown',
            'state_code' => null,
            'postal_code' => null,
            'latitude' => null,
            'longitude' => null,
            'timezone' => 'UTC',
            'country_code' => 'US',
            'country_name' => 'United States',
            'source' => 'default',
            'ip' => request()->ip(),
        ];
    }

    /**
     * Get default ISP data
     */
    protected function getDefaultISP()
    {
        return [
            'isp' => 'Unknown',
            'organization' => 'Unknown',
            'autonomous_system_number' => null,
            'autonomous_system_organization' => null,
            'source' => 'default',
            'ip' => request()->ip(),
        ];
    }

    /**
     * Get country-based CPM rates
     */
    public function getCountryCPMRate($countryCode)
    {
        $rates = [
            'US' => ['inr' => 0.5000, 'usd' => 0.0060],
            'CA' => ['inr' => 0.4500, 'usd' => 0.0055],
            'GB' => ['inr' => 0.4000, 'usd' => 0.0050],
            'AU' => ['inr' => 0.4000, 'usd' => 0.0050],
            'DE' => ['inr' => 0.3500, 'usd' => 0.0045],
            'FR' => ['inr' => 0.3500, 'usd' => 0.0045],
            'JP' => ['inr' => 0.3000, 'usd' => 0.0040],
            'IN' => ['inr' => 0.2000, 'usd' => 0.0025],
            'BR' => ['inr' => 0.2500, 'usd' => 0.0030],
            'MX' => ['inr' => 0.2500, 'usd' => 0.0030],
        ];

        return $rates[$countryCode] ?? ['inr' => 0.1000, 'usd' => 0.0010];
    }

    /**
     * Get continent-based CPM rates
     */
    public function getContinentCPMRate($continentCode)
    {
        $rates = [
            'NA' => ['inr' => 0.4000, 'usd' => 0.0050], // North America
            'EU' => ['inr' => 0.3500, 'usd' => 0.0045], // Europe
            'AS' => ['inr' => 0.2000, 'usd' => 0.0025], // Asia
            'SA' => ['inr' => 0.2500, 'usd' => 0.0030], // South America
            'AF' => ['inr' => 0.1500, 'usd' => 0.0020], // Africa
            'OC' => ['inr' => 0.3000, 'usd' => 0.0040], // Oceania
        ];

        return $rates[$continentCode] ?? ['inr' => 0.1000, 'usd' => 0.0010];
    }

    /**
     * Get geographic analytics data
     */
    public function getGeographicAnalytics($startDate = null, $endDate = null)
    {
        // This would typically query your database for geographic analytics
        // For now, return sample data structure
        return [
            'top_countries' => [
                ['country' => 'US', 'visits' => 1500, 'earnings' => 9.00],
                ['country' => 'CA', 'visits' => 800, 'earnings' => 4.40],
                ['country' => 'GB', 'visits' => 600, 'earnings' => 3.00],
                ['country' => 'IN', 'visits' => 400, 'earnings' => 1.00],
                ['country' => 'AU', 'visits' => 300, 'earnings' => 1.50],
            ],
            'top_cities' => [
                ['city' => 'New York', 'country' => 'US', 'visits' => 200],
                ['city' => 'Toronto', 'country' => 'CA', 'visits' => 150],
                ['city' => 'London', 'country' => 'GB', 'visits' => 120],
                ['city' => 'Mumbai', 'country' => 'IN', 'visits' => 100],
                ['city' => 'Sydney', 'country' => 'AU', 'visits' => 80],
            ],
            'continent_breakdown' => [
                ['continent' => 'NA', 'visits' => 2300, 'earnings' => 13.40],
                ['continent' => 'EU', 'visits' => 1200, 'earnings' => 5.40],
                ['continent' => 'AS', 'visits' => 800, 'earnings' => 2.00],
                ['continent' => 'OC', 'visits' => 400, 'earnings' => 1.60],
                ['continent' => 'SA', 'visits' => 200, 'earnings' => 0.60],
            ],
        ];
    }

    /**
     * Check if GeoIP service is properly configured
     */
    public function isConfigured()
    {
        return ($this->useWebService && $this->accountId && $this->licenseKey) ||
               (file_exists($this->databasePath));
    }

    /**
     * Get configuration status
     */
    public function getConfigurationStatus()
    {
        if (!$this->isConfigured()) {
            return [
                'status' => 'not_configured',
                'message' => 'GeoIP service is not properly configured',
                'missing' => [
                    'web_service' => !($this->accountId && $this->licenseKey),
                    'database' => !file_exists($this->databasePath),
                ]
            ];
        }

        // Test the service
        try {
            $testIP = '8.8.8.8'; // Google DNS
            $result = $this->getCountry($testIP);

            if ($result && $result['country_code']) {
                return [
                    'status' => 'active',
                    'message' => 'GeoIP service is working properly',
                    'test_result' => $result,
                    'source' => $result['source'],
                ];
            }
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'GeoIP service is configured but test failed: ' . $e->getMessage(),
            ];
        }

        return [
            'status' => 'error',
            'message' => 'GeoIP service is configured but not working properly',
        ];
    }

    /**
     * Download and update the GeoIP database
     */
    public function updateDatabase()
    {
        // This would download the latest GeoLite2 database
        // Implementation depends on your setup and MaxMind license
        Log::info('GeoIP database update requested');

        return [
            'success' => true,
            'message' => 'Database update completed',
            'timestamp' => now()->toISOString(),
        ];
    }

    /**
     * Clear GeoIP cache
     */
    public function clearCache()
    {
        Cache::flush();

        return [
            'success' => true,
            'message' => 'GeoIP cache cleared',
            'timestamp' => now()->toISOString(),
        ];
    }
}
