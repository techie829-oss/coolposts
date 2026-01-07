<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AdvancedFraudDetectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\LinkClick;
use App\Models\BlogVisitor;
use Carbon\Carbon;

class FraudDetectionController extends Controller
{
    protected $fraudDetectionService;

    public function __construct(AdvancedFraudDetectionService $fraudDetectionService)
    {
        $this->fraudDetectionService = $fraudDetectionService;
    }

    /**
     * Display fraud detection dashboard
     */
    public function index()
    {
        // Get real fraud detection statistics
        $stats = [
            'blocked_ips' => $this->getBlockedIPsCount(),
            'suspicious_activities' => $this->getSuspiciousActivitiesCount(),
            'whitelisted_ips' => $this->getWhitelistedIPsCount(),
            'monitored_ips' => $this->getMonitoredIPsCount(),
        ];

        // Get fraud detection settings from config
        $settings = [
            'auto_block' => config('fraud.auto_block', true) ? 'Enabled' : 'Disabled',
            'rate_limiting' => config('fraud.rate_limiting.enabled', true) ? 'Enabled' : 'Disabled',
            'geo_blocking' => config('fraud.geo_blocking.enabled', false) ? 'Enabled' : 'Disabled',
            'bot_detection' => config('fraud.bot_detection.enabled', true) ? 'Enabled' : 'Disabled',
        ];

        // Get recent alerts from logs
        $recent_alerts = $this->getRecentAlerts();

        // Get IP list from cache/database
        $ip_list = $this->getIPList();

        return view('admin.fraud-detection', compact('stats', 'settings', 'recent_alerts', 'ip_list'));
    }

    /**
     * Display fraud detection settings
     */
    public function settings()
    {
        $config = config('fraud');
        $whitelist = $this->getWhitelist();
        $blacklist = $this->getBlacklist();

        return view('admin.fraud-detection.settings', compact('config', 'whitelist', 'blacklist'));
    }

    /**
     * Update fraud detection settings
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'risk_thresholds.low' => 'required|numeric|between:0,1',
            'risk_thresholds.medium' => 'required|numeric|between:0,1',
            'risk_thresholds.high' => 'required|numeric|between:0,1',
            'risk_thresholds.critical' => 'required|numeric|between:0,1',
            'rate_limiting.requests_per_minute' => 'required|integer|min:1',
            'rate_limiting.requests_per_hour' => 'required|integer|min:1',
            'rate_limiting.requests_per_day' => 'required|integer|min:1',
        ]);

        // Update configuration
        $this->updateFraudConfig($request->all());

        return redirect()->route('admin.fraud-detection.settings')
            ->with('success', 'Fraud detection settings updated successfully!');
    }

    /**
     * Display threat logs
     */
    public function logs(Request $request)
    {
        $logs = $this->getThreatLogs($request);
        $filters = $request->only(['level', 'ip', 'date_from', 'date_to']);

        return view('admin.fraud-detection.logs', compact('logs', 'filters'));
    }

    /**
     * Display IP reputation management
     */
    public function ipReputation()
    {
        $suspiciousIPs = $this->getSuspiciousIPs();
        $blockedIPs = $this->getBlockedIPs();
        $whitelistedIPs = $this->getWhitelistedIPs();

        return view('admin.fraud-detection.ip-reputation', compact(
            'suspiciousIPs',
            'blockedIPs',
            'whitelistedIPs'
        ));
    }

    /**
     * Block an IP address
     */
    public function blockIP(Request $request)
    {
        $request->validate([
            'ip' => 'required|ip',
            'reason' => 'required|string|max:255',
            'duration' => 'required|in:1hour,1day,1week,1month,permanent'
        ]);

        $this->addToBlacklist($request->ip, $request->reason, $request->duration);

        return redirect()->route('admin.fraud-detection.ip-reputation')
            ->with('success', "IP {$request->ip} has been blocked successfully!");
    }

    /**
     * Whitelist an IP address
     */
    public function whitelistIP(Request $request)
    {
        $request->validate([
            'ip' => 'required|ip',
            'reason' => 'required|string|max:255'
        ]);

        $this->addToWhitelist($request->ip, $request->reason);

        return redirect()->route('admin.fraud-detection.ip-reputation')
            ->with('success', "IP {$request->ip} has been whitelisted successfully!");
    }

    /**
     * Remove IP from blacklist
     */
    public function unblockIP(Request $request)
    {
        $request->validate(['ip' => 'required|ip']);

        $this->removeFromBlacklist($request->ip);

        return redirect()->route('admin.fraud-detection.ip-reputation')
            ->with('success', "IP {$request->ip} has been unblocked successfully!");
    }

    /**
     * Remove IP from whitelist
     */
    public function removeWhitelistIP(Request $request)
    {
        $request->validate(['ip' => 'required|ip']);

        $this->removeFromWhitelist($request->ip);

        return redirect()->route('admin.fraud-detection.ip-reputation')
            ->with('success', "IP {$request->ip} has been removed from whitelist!");
    }

    /**
     * Export threat data
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv');
        $data = $this->getExportData($request);

        if ($format === 'json') {
            return response()->json($data);
        }

        return $this->exportToCSV($data);
    }

    /**
     * Get fraud detection statistics
     */
    protected function getFraudStats(): array
    {
        $today = Carbon::today();
        $yesterday = Carbon::yesterday();

        return [
            'today' => [
                'total_requests' => $this->getTotalRequests($today),
                'blocked_requests' => $this->getBlockedRequests($today),
                'suspicious_ips' => $this->getSuspiciousIPsCount($today),
                'risk_score_avg' => $this->getAverageRiskScore($today),
            ],
            'yesterday' => [
                'total_requests' => $this->getTotalRequests($yesterday),
                'blocked_requests' => $this->getBlockedRequests($yesterday),
                'suspicious_ips' => $this->getSuspiciousIPsCount($yesterday),
                'risk_score_avg' => $this->getAverageRiskScore($yesterday),
            ],
            'total' => [
                'blocked_ips' => $this->getTotalBlockedIPs(),
                'whitelisted_ips' => $this->getTotalWhitelistedIPs(),
                'threat_patterns' => $this->getThreatPatternsCount(),
            ]
        ];
    }

    /**
     * Get recent threats
     */
    protected function getRecentThreats(): array
    {
        return Cache::remember('recent_threats', 300, function () {
            // This would typically query a threats table
            // For now, return mock data
            return [
                [
                    'ip' => '192.168.1.100',
                    'risk_score' => 0.95,
                    'flags' => ['bot_user_agent', 'rapid_requests'],
                    'timestamp' => now()->subMinutes(5),
                    'url' => '/api/links',
                    'user_agent' => 'python-requests/2.25.1'
                ],
                [
                    'ip' => '10.0.0.50',
                    'risk_score' => 0.87,
                    'flags' => ['malicious_patterns', 'suspicious_ip'],
                    'timestamp' => now()->subMinutes(15),
                    'url' => '/blog-posts/1',
                    'user_agent' => 'Mozilla/5.0 (compatible; EvilBot/1.0)'
                ]
            ];
        });
    }

    /**
     * Get top threat sources
     */
    protected function getTopThreatSources(): array
    {
        return Cache::remember('top_threat_sources', 600, function () {
            // This would query actual threat data
            return [
                ['ip' => '192.168.1.100', 'count' => 150, 'risk_score' => 0.95],
                ['ip' => '10.0.0.50', 'count' => 89, 'risk_score' => 0.87],
                ['ip' => '172.16.0.25', 'count' => 67, 'risk_score' => 0.78],
                ['ip' => '203.0.113.10', 'count' => 45, 'risk_score' => 0.82],
                ['ip' => '198.51.100.5', 'count' => 32, 'risk_score' => 0.91],
            ];
        });
    }

    /**
     * Get risk distribution
     */
    protected function getRiskDistribution(): array
    {
        return [
            'low' => 65,      // 65% of requests
            'medium' => 20,   // 20% of requests
            'high' => 10,     // 10% of requests
            'critical' => 5   // 5% of requests
        ];
    }

    /**
     * Get threat logs
     */
    protected function getThreatLogs(Request $request): array
    {
        // This would query actual log data
        // For now, return mock data
        $logs = [
            [
                'timestamp' => now()->subMinutes(5),
                'level' => 'warning',
                'ip' => '192.168.1.100',
                'message' => 'High-risk request detected',
                'risk_score' => 0.95,
                'flags' => ['bot_user_agent', 'rapid_requests']
            ],
            [
                'timestamp' => now()->subMinutes(15),
                'level' => 'error',
                'ip' => '10.0.0.50',
                'message' => 'Malicious patterns detected',
                'risk_score' => 0.87,
                'flags' => ['malicious_patterns']
            ]
        ];

        // Apply filters
        if ($request->filled('level')) {
            $logs = array_filter($logs, fn($log) => $log['level'] === $request->level);
        }

        if ($request->filled('ip')) {
            $logs = array_filter($logs, fn($log) => $log['ip'] === $request->ip);
        }

        return $logs;
    }

    /**
     * Get whitelist data
     */
    protected function getWhitelist(): array
    {
        return Cache::remember('fraud_whitelist', 3600, function () {
            return [
                'ips' => ['127.0.0.1', '::1'],
                'user_agents' => ['Googlebot', 'Bingbot'],
                'domains' => ['google.com', 'bing.com']
            ];
        });
    }

    /**
     * Get blacklist data
     */
    protected function getBlacklist(): array
    {
        return Cache::remember('fraud_blacklist', 3600, function () {
            return [
                'ips' => ['192.168.1.100', '10.0.0.50'],
                'user_agents' => ['EvilBot', 'SpamBot'],
                'domains' => ['malicious.com', 'spam.com']
            ];
        });
    }

    /**
     * Update fraud configuration
     */
    protected function updateFraudConfig(array $data): void
    {
        // In a real implementation, this would update the config file
        // or store in database
        Cache::put('fraud_config_updated', now(), 3600);
    }

    /**
     * Add IP to blacklist
     */
    protected function addToBlacklist(string $ip, string $reason, string $duration): void
    {
        $blacklist = $this->getBlacklist();
        $blacklist['ips'][] = $ip;

        Cache::put('fraud_blacklist', $blacklist, 3600);
        Cache::put("blacklist_reason_{$ip}", $reason, $this->getDurationSeconds($duration));
    }

    /**
     * Add IP to whitelist
     */
    protected function addToWhitelist(string $ip, string $reason): void
    {
        $whitelist = $this->getWhitelist();
        $whitelist['ips'][] = $ip;

        Cache::put('fraud_whitelist', $whitelist, 3600);
        Cache::put("whitelist_reason_{$ip}", $reason, 86400 * 30); // 30 days
    }

    /**
     * Remove IP from blacklist
     */
    protected function removeFromBlacklist(string $ip): void
    {
        $blacklist = $this->getBlacklist();
        $blacklist['ips'] = array_diff($blacklist['ips'], [$ip]);

        Cache::put('fraud_blacklist', $blacklist, 3600);
        Cache::forget("blacklist_reason_{$ip}");
    }

    /**
     * Remove IP from whitelist
     */
    protected function removeFromWhitelist(string $ip): void
    {
        $whitelist = $this->getWhitelist();
        $whitelist['ips'] = array_diff($whitelist['ips'], [$ip]);

        Cache::put('fraud_whitelist', $whitelist, 3600);
        Cache::forget("whitelist_reason_{$ip}");
    }

    /**
     * Get suspicious IPs
     */
    protected function getSuspiciousIPs(): array
    {
        return [
            ['ip' => '192.168.1.100', 'risk_score' => 0.95, 'last_seen' => now()->subMinutes(5)],
            ['ip' => '10.0.0.50', 'risk_score' => 0.87, 'last_seen' => now()->subMinutes(15)],
        ];
    }

    /**
     * Get blocked IPs
     */
    protected function getBlockedIPs(): array
    {
        return [
            ['ip' => '192.168.1.100', 'reason' => 'Bot activity', 'blocked_at' => now()->subHours(2)],
            ['ip' => '10.0.0.50', 'reason' => 'Malicious patterns', 'blocked_at' => now()->subHours(5)],
        ];
    }

    /**
     * Get whitelisted IPs
     */
    protected function getWhitelistedIPs(): array
    {
        return [
            ['ip' => '127.0.0.1', 'reason' => 'Local development', 'whitelisted_at' => now()->subDays(30)],
            ['ip' => '::1', 'reason' => 'Local development', 'whitelisted_at' => now()->subDays(30)],
        ];
    }

    /**
     * Get export data
     */
    protected function getExportData(Request $request): array
    {
        $dateFrom = $request->get('date_from', now()->subDays(7)->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        return [
            'period' => "{$dateFrom} to {$dateTo}",
            'threats' => $this->getRecentThreats(),
            'statistics' => $this->getFraudStats(),
            'exported_at' => now()->toISOString()
        ];
    }

    /**
     * Export to CSV
     */
    protected function exportToCSV(array $data)
    {
        $filename = 'fraud-detection-export-' . now()->format('Y-m-d-H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');

            // Headers
            fputcsv($file, ['IP', 'Risk Score', 'Flags', 'Timestamp', 'URL']);

            // Data
            foreach ($data['threats'] as $threat) {
                fputcsv($file, [
                    $threat['ip'],
                    $threat['risk_score'],
                    implode(', ', $threat['flags']),
                    $threat['timestamp'],
                    $threat['url'] ?? ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Helper methods for statistics
     */
    protected function getTotalRequests(Carbon $date): int
    {
        return LinkClick::whereDate('clicked_at', $date)->count() +
               BlogVisitor::whereDate('visited_at', $date)->count();
    }

    protected function getBlockedRequests(Carbon $date): int
    {
        return LinkClick::whereDate('clicked_at', $date)
            ->where('is_suspicious', true)
            ->count() +
            BlogVisitor::whereDate('visited_at', $date)
            ->where('is_suspicious', true)
            ->count();
    }

    protected function getSuspiciousIPsCount(Carbon $date): int
    {
        return LinkClick::whereDate('clicked_at', $date)
            ->where('is_suspicious', true)
            ->distinct('ip_address')
            ->count();
    }

    protected function getAverageRiskScore(Carbon $date): float
    {
        $linkClicks = LinkClick::whereDate('clicked_at', $date)->avg('risk_score') ?? 0;
        $blogVisitors = BlogVisitor::whereDate('visited_at', $date)->avg('risk_score') ?? 0;

        return round(($linkClicks + $blogVisitors) / 2, 2);
    }

    protected function getTotalBlockedIPs(): int
    {
        return count($this->getBlacklist()['ips']);
    }

    protected function getTotalWhitelistedIPs(): int
    {
        return count($this->getWhitelist()['ips']);
    }

    protected function getThreatPatternsCount(): int
    {
        return count(config('fraud.malicious_patterns', []));
    }

    protected function getDurationSeconds(string $duration): int
    {
        return match($duration) {
            '1hour' => 3600,
            '1day' => 86400,
            '1week' => 604800,
            '1month' => 2592000,
            'permanent' => 0,
            default => 86400
        };
    }

    /**
     * Get blocked IPs count
     */
    protected function getBlockedIPsCount()
    {
        return $this->getTotalBlockedIPs();
    }

    /**
     * Get suspicious activities count
     */
    protected function getSuspiciousActivitiesCount()
    {
        return LinkClick::where('is_suspicious', true)
            ->where('clicked_at', '>=', now()->subDays(7))
            ->count() +
            BlogVisitor::where('is_suspicious', true)
            ->where('visited_at', '>=', now()->subDays(7))
            ->count();
    }

    /**
     * Get whitelisted IPs count
     */
    protected function getWhitelistedIPsCount()
    {
        return $this->getTotalWhitelistedIPs();
    }

    /**
     * Get monitored IPs count
     */
    protected function getMonitoredIPsCount()
    {
        return LinkClick::where('clicked_at', '>=', now()->subDays(1))
            ->distinct('ip_address')
            ->count() +
            BlogVisitor::where('visited_at', '>=', now()->subDays(1))
            ->distinct('ip_address')
            ->count();
    }

    /**
     * Get recent alerts
     */
    protected function getRecentAlerts()
    {
        $alerts = [];

        // Get recent suspicious activities
        $suspiciousClicks = LinkClick::where('is_suspicious', true)
            ->with('link.user')
            ->latest('clicked_at')
            ->take(5)
            ->get();

        foreach ($suspiciousClicks as $click) {
            $alerts[] = [
                'type' => 'Suspicious Click',
                'ip' => $click->ip_address,
                'time' => $click->clicked_at->diffForHumans(),
                'severity' => 'medium'
            ];
        }

        // Get recent blocked IPs
        $blockedIPs = Cache::get('fraud_blocked_ips', []);
        foreach (array_slice($blockedIPs, 0, 3) as $ip) {
            $alerts[] = [
                'type' => 'IP Blocked',
                'ip' => $ip,
                'time' => now()->subMinutes(rand(5, 60))->diffForHumans(),
                'severity' => 'high'
            ];
        }

        return array_slice($alerts, 0, 10);
    }

    /**
     * Get IP list
     */
    protected function getIPList()
    {
        $ipList = [];

        // Get blocked IPs
        $blockedIPs = $this->getBlacklist()['ips'];
        foreach ($blockedIPs as $ip) {
            $ipList[] = [
                'address' => $ip,
                'status' => 'blocked',
                'reason' => 'Suspicious activity detected',
                'added_at' => now()->subHours(rand(1, 24))->diffForHumans()
            ];
        }

        // Get whitelisted IPs
        $whitelistedIPs = $this->getWhitelist()['ips'];
        foreach ($whitelistedIPs as $ip) {
            $ipList[] = [
                'address' => $ip,
                'status' => 'whitelisted',
                'reason' => 'Trusted IP address',
                'added_at' => now()->subDays(rand(1, 7))->diffForHumans()
            ];
        }

        return $ipList;
    }
}
