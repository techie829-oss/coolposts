<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class FraudDetectionService
{
    protected $globalSettings;

    public function __construct()
    {
        $this->globalSettings = \App\Models\GlobalSetting::getSettings();
    }

    /**
     * Risk thresholds
     */
    const RISK_THRESHOLDS = [
        'low' => 0.3,
        'medium' => 0.6,
        'high' => 0.8,
    ];

    /**
     * Check if request is suspicious
     */
    public function analyzeRequest(Request $request, array $context = []): array
    {
        $riskScore = 0;
        $flags = [];
        $details = [];

        // Check for bot signatures
        $botCheck = $this->detectBot($request);
        if ($botCheck['is_bot']) {
            $riskScore += $this->globalSettings->fraud_bot_penalty_score; // Dynamic score
            $flags[] = 'bot_detected';
            $details['bot'] = $botCheck;
        }

        // Check for VPN/Proxy
        $vpnCheck = $this->detectVPN($request);
        if ($vpnCheck['is_vpn']) {
            $riskScore += $this->globalSettings->fraud_vpn_penalty_score; // Dynamic score
            $flags[] = 'vpn_detected';
            $details['vpn'] = $vpnCheck;
        }

        // Check for duplicate clicks
        $duplicateCheck = $this->detectDuplicateClick($request, $context);
        if ($duplicateCheck['is_duplicate']) {
            $riskScore += 0.5; // Keeping this fixed for now or could add to DB
            $flags[] = 'duplicate_click';
            $details['duplicate'] = $duplicateCheck;
        }

        // Check for suspicious patterns
        $patternCheck = $this->detectSuspiciousPatterns($request, $context);
        if ($patternCheck['is_suspicious']) {
            $riskScore += 0.2; // Keeping fixed
            $flags[] = 'suspicious_pattern';
            $details['pattern'] = $patternCheck;
        }

        // Check for adblock
        $adblockCheck = $this->detectAdblock($request);
        if ($adblockCheck['is_adblock']) {
            $riskScore += 0.3; // Keeping fixed
            $flags[] = 'adblock_detected';
            $details['adblock'] = $adblockCheck;
        }

        return [
            'risk_score' => min($riskScore, 1.0),
            'risk_level' => $this->getRiskLevel($riskScore),
            'flags' => $flags,
            'details' => $details,
            'is_safe' => $riskScore < self::RISK_THRESHOLDS['medium'],
            'should_block' => $riskScore >= self::RISK_THRESHOLDS['high'],
        ];
    }

    /**
     * Detect bot signatures
     */
    private function detectBot(Request $request): array
    {
        $userAgent = $request->userAgent();
        $isBot = false;
        $botType = null;

        // Common bot signatures
        $botSignatures = [
            'bot',
            'crawler',
            'spider',
            'scraper',
            'curl',
            'wget',
            'python',
            'java',
            'perl',
            'ruby',
            'php',
            'go',
            'headless',
            'phantom',
            'selenium',
            'puppeteer',
            'googlebot',
            'bingbot',
            'yandex',
            'baiduspider',
        ];

        foreach ($botSignatures as $signature) {
            if (stripos($userAgent, $signature) !== false) {
                $isBot = true;
                $botType = $signature;
                break;
            }
        }

        // Check for missing or suspicious headers
        $suspiciousHeaders = [
            'accept-language' => $request->header('accept-language'),
            'accept-encoding' => $request->header('accept-encoding'),
            'dnt' => $request->header('dnt'),
        ];

        $missingHeaders = 0;
        foreach ($suspiciousHeaders as $header => $value) {
            if (empty($value)) {
                $missingHeaders++;
            }
        }

        if ($missingHeaders >= 2) {
            $isBot = true;
            $botType = 'missing_headers';
        }

        return [
            'is_bot' => $isBot,
            'bot_type' => $botType,
            'user_agent' => $userAgent,
            'missing_headers' => $missingHeaders,
        ];
    }

    /**
     * Detect VPN/Proxy usage
     */
    private function detectVPN(Request $request): array
    {
        $ip = $request->ip();
        $isVPN = false;
        $vpnType = null;

        // Check for common VPN/Proxy IP ranges
        $vpnRanges = [
            '10.0.0.0/8',
            '172.16.0.0/12',
            '192.168.0.0/16', // Private IPs
            '127.0.0.0/8', // Localhost
        ];

        foreach ($vpnRanges as $range) {
            if ($this->ipInRange($ip, $range)) {
                $isVPN = true;
                $vpnType = 'private_ip';
                break;
            }
        }

        // Check for known VPN providers (basic check)
        $vpnProviders = [
            'nordvpn',
            'expressvpn',
            'surfshark',
            'protonvpn',
            'windscribe',
            'cyberghost',
            'tunnelbear',
        ];

        // This would typically involve a more sophisticated check
        // For now, we'll use a simple heuristic
        $hostname = gethostbyaddr($ip);
        foreach ($vpnProviders as $provider) {
            if (stripos($hostname, $provider) !== false) {
                $isVPN = true;
                $vpnType = $provider;
                break;
            }
        }

        return [
            'is_vpn' => $isVPN,
            'vpn_type' => $vpnType,
            'ip' => $ip,
            'hostname' => $hostname,
        ];
    }

    /**
     * Detect duplicate clicks
     */
    private function detectDuplicateClick(Request $request, array $context): array
    {
        $ip = $request->ip();
        $linkId = $context['link_id'] ?? null;
        $isDuplicate = false;
        $timeWindow = 3600; // 1 hour

        if ($linkId) {
            $cacheKey = "click_{$linkId}_{$ip}";
            $lastClick = Cache::get($cacheKey);

            if ($lastClick) {
                $timeDiff = time() - $lastClick;
                if ($timeDiff < $timeWindow) {
                    $isDuplicate = true;
                }
            }

            // Store this click
            Cache::put($cacheKey, time(), $timeWindow);
        }

        return [
            'is_duplicate' => $isDuplicate,
            'ip' => $ip,
            'link_id' => $linkId,
            'time_window' => $timeWindow,
        ];
    }

    /**
     * Detect suspicious patterns
     */
    private function detectSuspiciousPatterns(Request $request, array $context): array
    {
        $isSuspicious = false;
        $patterns = [];

        // Check for rapid clicking
        $ip = $request->ip();
        $cacheKey = "rapid_clicks_{$ip}";
        $clicks = Cache::get($cacheKey, []);
        $clicks[] = time();

        // Keep only clicks from last N seconds (dynamic)
        $timeWindow = $this->globalSettings->fraud_click_time_window;
        $clicks = array_filter($clicks, function ($click) use ($timeWindow) {
            return $click > (time() - $timeWindow);
        });

        Cache::put($cacheKey, $clicks, $timeWindow);

        if (count($clicks) > $this->globalSettings->fraud_rapid_click_threshold) { // Dynamic threshold
            $isSuspicious = true;
            $patterns[] = 'rapid_clicking';
        }

        // Check for suspicious referrer
        $referrer = $request->header('referer');
        if ($referrer) {
            $suspiciousReferrers = [
                'localhost',
                '127.0.0.1',
                'test.com',
                'example.com',
                'admin',
                'wp-admin',
                'phpmyadmin',
            ];

            foreach ($suspiciousReferrers as $suspicious) {
                if (stripos($referrer, $suspicious) !== false) {
                    $isSuspicious = true;
                    $patterns[] = 'suspicious_referrer';
                    break;
                }
            }
        }

        return [
            'is_suspicious' => $isSuspicious,
            'patterns' => $patterns,
            'click_count' => count($clicks),
            'referrer' => $referrer,
        ];
    }

    /**
     * Detect adblock usage
     */
    private function detectAdblock(Request $request): array
    {
        // This is a basic implementation
        // In production, you'd want more sophisticated detection
        $isAdblock = false;
        $detectionMethod = null;

        // Check for adblock signatures in headers
        $headers = $request->headers->all();
        $adblockSignatures = [
            'x-adblock',
            'x-ublock',
            'x-adguard',
        ];

        foreach ($adblockSignatures as $signature) {
            if (isset($headers[$signature])) {
                $isAdblock = true;
                $detectionMethod = 'header_signature';
                break;
            }
        }

        return [
            'is_adblock' => $isAdblock,
            'detection_method' => $detectionMethod,
        ];
    }

    /**
     * Get risk level based on score
     */
    private function getRiskLevel(float $score): string
    {
        if ($score < self::RISK_THRESHOLDS['low']) {
            return 'low';
        } elseif ($score < self::RISK_THRESHOLDS['medium']) {
            return 'medium';
        } elseif ($score < self::RISK_THRESHOLDS['high']) {
            return 'high';
        } else {
            return 'critical';
        }
    }

    /**
     * Check if IP is in range
     */
    private function ipInRange(string $ip, string $range): bool
    {
        if (strpos($range, '/') !== false) {
            list($subnet, $mask) = explode('/', $range);
            $ipLong = ip2long($ip);
            $subnetLong = ip2long($subnet);
            $maskLong = -1 << (32 - $mask);
            return ($ipLong & $maskLong) == ($subnetLong & $maskLong);
        }
        return $ip === $range;
    }

    /**
     * Log fraud attempt
     */
    public function logFraudAttempt(array $analysis, Request $request, array $context = []): void
    {
        Log::warning('Fraud attempt detected', [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'risk_score' => $analysis['risk_score'],
            'risk_level' => $analysis['risk_level'],
            'flags' => $analysis['flags'],
            'context' => $context,
            'timestamp' => now(),
        ]);
    }

    /**
     * Get fraud statistics
     */
    public function getFraudStats(string $period = 'today'): array
    {
        // This would integrate with your analytics system
        return [
            'total_attempts' => rand(100, 1000),
            'blocked_attempts' => rand(50, 200),
            'bot_attempts' => rand(30, 100),
            'vpn_attempts' => rand(20, 80),
            'duplicate_attempts' => rand(10, 50),
        ];
    }
}
