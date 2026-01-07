<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class CacheService
{
    protected $redis;
    protected $defaultTtl = 3600; // 1 hour default

    public function __construct()
    {
        try {
            $this->redis = Redis::connection();
        } catch (\Exception $e) {
            // If Redis is not available, set redis to null
            $this->redis = null;
            Log::warning('Redis connection failed, falling back to default cache: ' . $e->getMessage());
        }
    }

    /**
     * Cache frequently accessed data with intelligent TTL
     */
    public function cacheData(string $key, $data, int $ttl = null, string $prefix = 'app'): bool
    {
        try {
            $fullKey = $this->buildKey($key, $prefix);
            $ttl = $ttl ?? $this->defaultTtl;

            return Cache::put($fullKey, $data, $ttl);
        } catch (\Exception $e) {
            Log::error('Cache write error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get cached data with fallback
     */
    public function getCachedData(string $key, callable $fallback = null, int $ttl = null, string $prefix = 'app')
    {
        try {
            $fullKey = $this->buildKey($key, $prefix);
            $cached = Cache::get($fullKey);

            if ($cached !== null) {
                return $cached;
            }

            if ($fallback) {
                $data = $fallback();
                $this->cacheData($key, $data, $ttl, $prefix);
                return $data;
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Cache read error: ' . $e->getMessage());
            return $fallback ? $fallback() : null;
        }
    }

    /**
     * Cache user analytics data
     */
    public function cacheUserAnalytics(int $userId, array $data, int $ttl = 1800): bool
    {
        return $this->cacheData("user_analytics_{$userId}", $data, $ttl, 'analytics');
    }

    /**
     * Get cached user analytics
     */
    public function getUserAnalytics(int $userId, callable $fallback = null)
    {
        return $this->getCachedData("user_analytics_{$userId}", $fallback, 1800, 'analytics');
    }

    /**
     * Cache link analytics data
     */
    public function cacheLinkAnalytics(int $linkId, array $data, int $ttl = 900): bool
    {
        return $this->cacheData("link_analytics_{$linkId}", $data, $ttl, 'analytics');
    }

    /**
     * Get cached link analytics
     */
    public function getLinkAnalytics(int $linkId, callable $fallback = null)
    {
        return $this->getCachedData("link_analytics_{$linkId}", $fallback, 900, 'analytics');
    }

    /**
     * Cache blog analytics data
     */
    public function cacheBlogAnalytics(int $blogId, array $data, int $ttl = 900): bool
    {
        return $this->cacheData("blog_analytics_{$blogId}", $data, $ttl, 'analytics');
    }

    /**
     * Get cached blog analytics
     */
    public function getBlogAnalytics(int $blogId, callable $fallback = null)
    {
        return $this->getCachedData("blog_analytics_{$blogId}", $fallback, 900, 'analytics');
    }

    /**
     * Cache global settings
     */
    public function cacheGlobalSettings(array $settings, int $ttl = 7200): bool
    {
        return $this->cacheData('global_settings', $settings, $ttl, 'settings');
    }

    /**
     * Get cached global settings
     */
    public function getGlobalSettings(callable $fallback = null)
    {
        return $this->getCachedData('global_settings', $fallback, 7200, 'settings');
    }

    /**
     * Cache ad network data
     */
    public function cacheAdNetworkData(string $network, array $data, int $ttl = 1800): bool
    {
        return $this->cacheData("ad_network_{$network}", $data, $ttl, 'ads');
    }

    /**
     * Get cached ad network data
     */
    public function getAdNetworkData(string $network, callable $fallback = null)
    {
        return $this->getCachedData("ad_network_{$network}", $fallback, 1800, 'ads');
    }

    /**
     * Cache geographic data
     */
    public function cacheGeographicData(string $ip, array $data, int $ttl = 86400): bool
    {
        return $this->cacheData("geo_{$ip}", $data, $ttl, 'geoip');
    }

    /**
     * Get cached geographic data
     */
    public function getGeographicData(string $ip, callable $fallback = null)
    {
        return $this->getCachedData("geo_{$ip}", $fallback, 86400, 'geoip');
    }

    /**
     * Cache query results
     */
    public function cacheQuery(string $query, array $params, $result, int $ttl = 1800): bool
    {
        $key = $this->buildQueryKey($query, $params);
        return $this->cacheData($key, $result, $ttl, 'query');
    }

    /**
     * Get cached query results
     */
    public function getCachedQuery(string $query, array $params, callable $fallback = null)
    {
        $key = $this->buildQueryKey($query, $params);
        return $this->getCachedData($key, $fallback, 1800, 'query');
    }

    /**
     * Cache session data
     */
    public function cacheSession(string $sessionId, array $data, int $ttl = 3600): bool
    {
        return $this->cacheData("session_{$sessionId}", $data, $ttl, 'session');
    }

    /**
     * Get cached session data
     */
    public function getSessionData(string $sessionId, callable $fallback = null)
    {
        return $this->getCachedData("session_{$sessionId}", $fallback, 3600, 'session');
    }

    /**
     * Cache API responses
     */
    public function cacheApiResponse(string $endpoint, array $params, $response, int $ttl = 300): bool
    {
        $key = $this->buildApiKey($endpoint, $params);
        return $this->cacheData($key, $response, $ttl, 'api');
    }

    /**
     * Get cached API response
     */
    public function getCachedApiResponse(string $endpoint, array $params, callable $fallback = null)
    {
        $key = $this->buildApiKey($endpoint, $params);
        return $this->getCachedData($key, $fallback, 300, 'api');
    }

    /**
     * Cache with tags for better organization
     */
    public function cacheWithTags(string $key, $data, array $tags, int $ttl = null): bool
    {
        try {
            $ttl = $ttl ?? $this->defaultTtl;
            return Cache::tags($tags)->put($key, $data, $ttl);
        } catch (\Exception $e) {
            Log::error('Tagged cache write error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get cached data with tags
     */
    public function getWithTags(string $key, array $tags, callable $fallback = null, int $ttl = null)
    {
        try {
            $cached = Cache::tags($tags)->get($key);

            if ($cached !== null) {
                return $cached;
            }

            if ($fallback) {
                $data = $fallback();
                $this->cacheWithTags($key, $data, $tags, $ttl);
                return $data;
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Tagged cache read error: ' . $e->getMessage());
            return $fallback ? $fallback() : null;
        }
    }

    /**
     * Flush cache by tags
     */
    public function flushByTags(array $tags): bool
    {
        try {
            Cache::tags($tags)->flush();
            return true;
        } catch (\Exception $e) {
            Log::error('Tagged cache flush error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Cache database query with automatic invalidation
     */
    public function cacheQueryWithInvalidation(string $query, array $params, callable $fallback, array $tables, int $ttl = 1800)
    {
        $key = $this->buildQueryKey($query, $params);
        $tags = array_map(function($table) { return "table_{$table}"; }, $tables);

        return $this->getWithTags($key, $tags, $fallback, $ttl);
    }

    /**
     * Invalidate cache for specific tables
     */
    public function invalidateTableCache(array $tables): bool
    {
        $tags = array_map(function($table) { return "table_{$table}"; }, $tables);
        return $this->flushByTags($tags);
    }

    /**
     * Cache with automatic refresh
     */
    public function cacheWithRefresh(string $key, callable $fallback, int $ttl = 3600, int $refreshThreshold = 300): mixed
    {
        $cached = Cache::get($key);

        if ($cached === null) {
            $data = $fallback();
            Cache::put($key, $data, $ttl);
            return $data;
        }

        // Check if we need to refresh (background refresh)
        $metadata = Cache::get("{$key}_metadata");
        if (!$metadata || (time() - $metadata['last_refresh']) > $refreshThreshold) {
            // Refresh in background
            dispatch(function() use ($key, $fallback, $ttl) {
                try {
                    $data = $fallback();
                    Cache::put($key, $data, $ttl);
                    Cache::put("{$key}_metadata", ['last_refresh' => time()], $ttl);
                } catch (\Exception $e) {
                    Log::error("Background cache refresh failed for key {$key}: " . $e->getMessage());
                }
            })->afterResponse();
        }

        return $cached;
    }

    /**
     * Get cache statistics
     */
    public function getCacheStats(): array
    {
        if (!$this->redis) {
            return ['error' => 'Redis not available'];
        }

        try {
            $info = $this->redis->info();

            return [
                'redis_version' => $info['redis_version'] ?? 'unknown',
                'connected_clients' => $info['connected_clients'] ?? 0,
                'used_memory_human' => $info['used_memory_human'] ?? 'unknown',
                'total_commands_processed' => $info['total_commands_processed'] ?? 0,
                'keyspace_hits' => $info['keyspace_hits'] ?? 0,
                'keyspace_misses' => $info['keyspace_misses'] ?? 0,
                'hit_rate' => $this->calculateHitRate($info),
                'uptime_days' => $info['uptime_in_days'] ?? 0,
            ];
        } catch (\Exception $e) {
            Log::error('Cache stats error: ' . $e->getMessage());
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Clear all cache
     */
    public function clearAllCache(): bool
    {
        try {
            Cache::flush();
            return true;
        } catch (\Exception $e) {
            Log::error('Cache clear error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Clear cache by pattern
     */
    public function clearCacheByPattern(string $pattern): bool
    {
        if (!$this->redis) {
            return false;
        }

        try {
            $keys = $this->redis->keys($pattern);
            if (!empty($keys)) {
                $this->redis->del($keys);
            }
            return true;
        } catch (\Exception $e) {
            Log::error('Pattern cache clear error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Optimize cache performance
     */
    public function optimizeCache(): array
    {
        $results = [];

        if (!$this->redis) {
            $results['error'] = 'Redis not available';
            return $results;
        }

        try {
            // Clear expired keys
            $this->redis->eval('return redis.call("EVAL", "local keys = redis.call(\'keys\', ARGV[1]) for i=1,#keys,5000 do redis.call(\'del\', unpack(keys, i, math.min(i+4999, #keys))) end return #keys", 0, "*")');
            $results['expired_cleared'] = true;

            // Optimize memory
            $this->redis->memory('PURGE');
            $results['memory_optimized'] = true;

            // Update statistics
            $results['stats'] = $this->getCacheStats();

        } catch (\Exception $e) {
            Log::error('Cache optimization error: ' . $e->getMessage());
            $results['error'] = $e->getMessage();
        }

        return $results;
    }

    /**
     * Build cache key with prefix
     */
    protected function buildKey(string $key, string $prefix): string
    {
        return "{$prefix}:{$key}";
    }

    /**
     * Build query cache key
     */
    protected function buildQueryKey(string $query, array $params): string
    {
        $hash = md5($query . serialize($params));
        return "query:{$hash}";
    }

    /**
     * Build API cache key
     */
    protected function buildApiKey(string $endpoint, array $params): string
    {
        $hash = md5($endpoint . serialize($params));
        return "api:{$hash}";
    }

    /**
     * Calculate cache hit rate
     */
    protected function calculateHitRate(array $info): float
    {
        $hits = $info['keyspace_hits'] ?? 0;
        $misses = $info['keyspace_misses'] ?? 0;
        $total = $hits + $misses;

        return $total > 0 ? round(($hits / $total) * 100, 2) : 0;
    }

    /**
     * Check if Redis is available
     */
    public function isRedisAvailable(): bool
    {
        if (!$this->redis) {
            return false;
        }

        try {
            $this->redis->ping();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get cache health status
     */
    public function getHealthStatus(): array
    {
        $redisAvailable = $this->isRedisAvailable();
        $stats = $redisAvailable ? $this->getCacheStats() : [];

        return [
            'redis_available' => $redisAvailable,
            'stats' => $stats,
            'timestamp' => now()->toISOString(),
        ];
    }
}
