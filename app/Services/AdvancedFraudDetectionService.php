<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\LinkClick;
use Carbon\Carbon;

class AdvancedFraudDetectionService
{
    protected $riskThresholds = [
        'low' => 0.3,
        'medium' => 0.6,
        'high' => 0.8,
        'critical' => 0.95
    ];

    /**
     * Advanced fraud analysis with multiple detection layers
     */
    public function analyzeRequest(Request $request, array $context = []): array
    {
        $analysis = [
            'risk_score' => 0.0,
            'flags' => [],
            'should_block' => false,
            'confidence' => 0.0,
            'detection_methods' => [],
            'recommendations' => []
        ];

        // Layer 1: Basic fraud detection
        $basicAnalysis = $this->basicFraudDetection($request, $context);
        $analysis['risk_score'] += $basicAnalysis['risk_score'] * 0.3;
        $analysis['flags'] = array_merge($analysis['flags'], $basicAnalysis['flags']);

        // Layer 2: Behavioral analysis
        $behavioralAnalysis = $this->behavioralAnalysis($request, $context);
        $analysis['risk_score'] += $behavioralAnalysis['risk_score'] * 0.4;
        $analysis['flags'] = array_merge($analysis['flags'], $behavioralAnalysis['flags']);

        // Layer 3: Machine learning analysis
        $mlAnalysis = $this->machineLearningAnalysis($request, $context);
        $analysis['risk_score'] += $mlAnalysis['risk_score'] * 0.3;
        $analysis['flags'] = array_merge($analysis['flags'], $mlAnalysis['flags']);

        // Normalize risk score to 0-1 range
        $analysis['risk_score'] = min(1.0, $analysis['risk_score']);

        // Determine if request should be blocked
        $analysis['should_block'] = $this->shouldBlockRequest($analysis['risk_score'], $analysis['flags']);

        // Calculate confidence level
        $analysis['confidence'] = $this->calculateConfidence($analysis);

        // Generate recommendations
        $analysis['recommendations'] = $this->generateRecommendations($analysis);

        // Log high-risk requests
        if ($analysis['risk_score'] > $this->riskThresholds['high']) {
            $this->logHighRiskRequest($request, $analysis);
        }

        return $analysis;
    }

    /**
     * Basic fraud detection
     */
    protected function basicFraudDetection(Request $request, array $context): array
    {
        $riskScore = 0.0;
        $flags = [];

        $ip = $request->ip();
        $userAgent = $request->userAgent();

        // Check for suspicious IP patterns
        if ($this->isSuspiciousIP($ip)) {
            $riskScore += 0.4;
            $flags[] = 'suspicious_ip';
        }

        // Check for bot user agents
        if ($this->isBotUserAgent($userAgent)) {
            $riskScore += 0.3;
            $flags[] = 'bot_user_agent';
        }

        // Check for rapid requests
        if ($this->isRapidRequest($ip)) {
            $riskScore += 0.5;
            $flags[] = 'rapid_requests';
        }

        // Check for known malicious patterns
        if ($this->hasMaliciousPatterns($request)) {
            $riskScore += 0.6;
            $flags[] = 'malicious_patterns';
        }

        return ['risk_score' => $riskScore, 'flags' => $flags];
    }

    /**
     * Behavioral analysis
     */
    protected function behavioralAnalysis(Request $request, array $context): array
    {
        $riskScore = 0.0;
        $flags = [];

        $ip = $request->ip();
        $sessionId = $request->session()->getId();

        // Analyze click patterns
        $clickPatterns = $this->analyzeClickPatterns($ip, $context);
        if ($clickPatterns['anomaly_detected']) {
            $riskScore += $clickPatterns['risk_score'];
            $flags[] = 'anomalous_click_pattern';
        }

        // Analyze session behavior
        $sessionAnalysis = $this->analyzeSessionBehavior($sessionId, $ip);
        if ($sessionAnalysis['suspicious']) {
            $riskScore += $sessionAnalysis['risk_score'];
            $flags[] = 'suspicious_session_behavior';
        }

        return ['risk_score' => $riskScore, 'flags' => $flags];
    }

    /**
     * Machine learning analysis
     */
    protected function machineLearningAnalysis(Request $request, array $context): array
    {
        $riskScore = 0.0;
        $flags = [];

        // Extract features for ML model
        $features = $this->extractFeatures($request, $context);

        // Predict risk using ML model
        $prediction = $this->predictRisk($features);
        $riskScore = $prediction['risk_score'];

        if ($prediction['anomaly_detected']) {
            $flags[] = 'ml_anomaly_detected';
        }

        return ['risk_score' => $riskScore, 'flags' => $flags];
    }

    /**
     * Analyze click patterns for anomalies
     */
    protected function analyzeClickPatterns(string $ip, array $context): array
    {
        $recentClicks = LinkClick::where('ip_address', $ip)
            ->where('clicked_at', '>=', now()->subHours(1))
            ->get();

        if ($recentClicks->count() < 5) {
            return ['anomaly_detected' => false, 'risk_score' => 0.0];
        }

        // Calculate click intervals
        $intervals = [];
        $sortedClicks = $recentClicks->sortBy('clicked_at');

        for ($i = 1; $i < $sortedClicks->count(); $i++) {
            $interval = $sortedClicks[$i]->clicked_at->diffInSeconds($sortedClicks[$i-1]->clicked_at);
            $intervals[] = $interval;
        }

        $avgInterval = array_sum($intervals) / count($intervals);
        $variance = $this->calculateVariance($intervals, $avgInterval);

        $anomalyDetected = false;
        $riskScore = 0.0;

        // Too regular intervals (bot-like behavior)
        if ($variance < 2 && $avgInterval < 10) {
            $anomalyDetected = true;
            $riskScore = 0.7;
        }

        return [
            'anomaly_detected' => $anomalyDetected,
            'risk_score' => $riskScore
        ];
    }

    /**
     * Analyze session behavior
     */
    protected function analyzeSessionBehavior(string $sessionId, string $ip): array
    {
        $sessionData = Cache::get("session_analysis_{$sessionId}", []);

        if (empty($sessionData)) {
            $sessionData = [
                'requests' => 0,
                'start_time' => now(),
                'unique_ips' => [$ip]
            ];
        }

        $sessionData['requests']++;

        if (!in_array($ip, $sessionData['unique_ips'])) {
            $sessionData['unique_ips'][] = $ip;
        }

        Cache::put("session_analysis_{$sessionId}", $sessionData, 3600);

        $suspicious = false;
        $riskScore = 0.0;

        // Multiple IPs in same session
        if (count($sessionData['unique_ips']) > 3) {
            $suspicious = true;
            $riskScore += 0.4;
        }

        // Too many requests in short time
        $sessionDuration = now()->diffInMinutes($sessionData['start_time']);
        if ($sessionDuration > 0 && $sessionData['requests'] / $sessionDuration > 10) {
            $suspicious = true;
            $riskScore += 0.5;
        }

        return ['suspicious' => $suspicious, 'risk_score' => $riskScore];
    }

    /**
     * Extract features for ML model
     */
    protected function extractFeatures(Request $request, array $context): array
    {
        $ip = $request->ip();
        $userAgent = $request->userAgent();

        return [
            'hour_of_day' => now()->hour,
            'day_of_week' => now()->dayOfWeek,
            'user_agent_length' => strlen($userAgent),
            'has_js_enabled' => $request->hasHeader('X-Requested-With'),
            'referrer_present' => $request->hasHeader('Referer'),
            'request_frequency' => $this->getRequestFrequency($ip),
            'unique_links_clicked' => $this->getUniqueLinksClicked($ip)
        ];
    }

    /**
     * Predict risk using ML model
     */
    protected function predictRisk(array $features): array
    {
        $riskScore = 0.0;
        $anomalyDetected = false;

        // Rule 1: Unusual hour of day
        if ($features['hour_of_day'] < 6 || $features['hour_of_day'] > 23) {
            $riskScore += 0.2;
        }

        // Rule 2: No JavaScript enabled
        if (!$features['has_js_enabled']) {
            $riskScore += 0.3;
            $anomalyDetected = true;
        }

        // Rule 3: High request frequency
        if ($features['request_frequency'] > 100) {
            $riskScore += 0.4;
        }

        return [
            'risk_score' => min(1.0, $riskScore),
            'anomaly_detected' => $anomalyDetected
        ];
    }

    /**
     * Determine if request should be blocked
     */
    protected function shouldBlockRequest(float $riskScore, array $flags): bool
    {
        if ($riskScore >= $this->riskThresholds['critical']) {
            return true;
        }

        if ($riskScore >= $this->riskThresholds['high'] &&
            (in_array('malicious_patterns', $flags) || in_array('rapid_requests', $flags))) {
            return true;
        }

        if (count(array_intersect($flags, ['bot_user_agent', 'suspicious_ip'])) >= 2) {
            return true;
        }

        return false;
    }

    /**
     * Calculate confidence level
     */
    protected function calculateConfidence(array $analysis): float
    {
        $confidence = 0.5;
        $flagCount = count($analysis['flags']);
        $confidence += min(0.3, $flagCount * 0.1);
        return min(1.0, $confidence);
    }

    /**
     * Generate recommendations
     */
    protected function generateRecommendations(array $analysis): array
    {
        $recommendations = [];

        if ($analysis['risk_score'] > $this->riskThresholds['high']) {
            $recommendations[] = 'Consider implementing CAPTCHA for this IP';
        }

        if (in_array('bot_user_agent', $analysis['flags'])) {
            $recommendations[] = 'Block known bot user agents';
        }

        if (in_array('rapid_requests', $analysis['flags'])) {
            $recommendations[] = 'Implement stricter rate limiting';
        }

        return $recommendations;
    }

    /**
     * Helper methods
     */
    protected function isSuspiciousIP(string $ip): bool
    {
        return Cache::remember("suspicious_ip_{$ip}", 3600, function () use ($ip) {
            $maliciousIPs = config('fraud.malicious_ips', []);
            return in_array($ip, $maliciousIPs);
        });
    }

    protected function isBotUserAgent(string $userAgent): bool
    {
        $botPatterns = ['bot', 'crawler', 'spider', 'scraper', 'curl', 'wget'];
        $userAgentLower = strtolower($userAgent);

        foreach ($botPatterns as $pattern) {
            if (strpos($userAgentLower, $pattern) !== false) {
                return true;
            }
        }
        return false;
    }

    protected function isRapidRequest(string $ip): bool
    {
        $key = "rapid_requests_{$ip}";
        $requests = Cache::get($key, 0);
        Cache::put($key, $requests + 1, 60);
        return $requests > 50;
    }

    protected function hasMaliciousPatterns(Request $request): bool
    {
        $suspiciousPatterns = ['union', 'select', '<script', 'javascript:', '..\\'];
        $input = json_encode($request->all()) . $request->getQueryString();

        foreach ($suspiciousPatterns as $pattern) {
            if (stripos($input, $pattern) !== false) {
                return true;
            }
        }
        return false;
    }

    protected function calculateVariance(array $values, float $mean): float
    {
        $variance = 0;
        foreach ($values as $value) {
            $variance += pow($value - $mean, 2);
        }
        return $variance / count($values);
    }

    protected function getRequestFrequency(string $ip): int
    {
        return Cache::get("request_frequency_{$ip}", 0);
    }

    protected function getUniqueLinksClicked(string $ip): int
    {
        return LinkClick::where('ip_address', $ip)
            ->where('clicked_at', '>=', now()->subDay())
            ->distinct('link_id')
            ->count();
    }

    protected function logHighRiskRequest(Request $request, array $analysis): void
    {
        Log::warning('High-risk request detected', [
            'ip' => $request->ip(),
            'risk_score' => $analysis['risk_score'],
            'flags' => $analysis['flags'],
            'url' => $request->fullUrl()
        ]);
    }
}
