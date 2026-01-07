<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class PerformanceOptimizationService
{
    protected $cacheService;

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Optimize database queries
     */
    public function optimizeDatabaseQueries(): array
    {
        $results = [];

        try {
            // Analyze table statistics
            $results['table_stats'] = $this->analyzeTableStatistics();

            // Optimize indexes
            $results['index_optimization'] = $this->optimizeIndexes();

            // Optimize queries
            $results['query_optimization'] = $this->optimizeQueries();

            // Vacuum database (for SQLite)
            $results['database_vacuum'] = $this->vacuumDatabase();

            // Update statistics
            $results['statistics_update'] = $this->updateStatistics();

        } catch (\Exception $e) {
            Log::error('Database optimization error: ' . $e->getMessage());
            $results['error'] = $e->getMessage();
        }

        return $results;
    }

    /**
     * Analyze table statistics
     */
    public function analyzeTableStatistics(): array
    {
        $stats = [];

        try {
            $tables = ['users', 'links', 'link_clicks', 'blog_posts', 'blog_visitors', 'user_earnings'];

            foreach ($tables as $table) {
                $count = DB::table($table)->count();
                $size = $this->getTableSize($table);
                $indexes = $this->getTableIndexes($table);

                $stats[$table] = [
                    'row_count' => $count,
                    'table_size' => $size,
                    'indexes' => $indexes,
                    'last_analyzed' => now()->toISOString(),
                ];
            }

        } catch (\Exception $e) {
            Log::error('Table statistics analysis error: ' . $e->getMessage());
            $stats['error'] = $e->getMessage();
        }

        return $stats;
    }

    /**
     * Optimize database indexes
     */
    public function optimizeIndexes(): array
    {
        $results = [];

        try {
            // Check for missing indexes on frequently queried columns
            $missingIndexes = $this->findMissingIndexes();

            // Create recommended indexes
            foreach ($missingIndexes as $index) {
                try {
                    DB::statement($index['sql']);
                    $results['created_indexes'][] = $index['name'];
                } catch (\Exception $e) {
                    $results['failed_indexes'][] = [
                        'name' => $index['name'],
                        'error' => $e->getMessage()
                    ];
                }
            }

            // Analyze existing indexes
            $results['index_analysis'] = $this->analyzeExistingIndexes();

        } catch (\Exception $e) {
            Log::error('Index optimization error: ' . $e->getMessage());
            $results['error'] = $e->getMessage();
        }

        return $results;
    }

    /**
     * Optimize slow queries
     */
    public function optimizeQueries(): array
    {
        $results = [];

        try {
            // Get slow query log
            $slowQueries = $this->getSlowQueries();

            // Analyze and optimize each query
            foreach ($slowQueries as $query) {
                $optimization = $this->optimizeQuery($query);
                $results['optimizations'][] = $optimization;
            }

            // Cache frequently used queries
            $results['query_caching'] = $this->cacheFrequentQueries();

        } catch (\Exception $e) {
            Log::error('Query optimization error: ' . $e->getMessage());
            $results['error'] = $e->getMessage();
        }

        return $results;
    }

    /**
     * Optimize application performance
     */
    public function optimizeApplication(): array
    {
        $results = [];

        try {
            // Clear application cache
            $results['cache_clear'] = $this->clearApplicationCache();

            // Optimize autoloader
            $results['autoloader_optimization'] = $this->optimizeAutoloader();

            // Optimize configuration
            $results['config_optimization'] = $this->optimizeConfiguration();

            // Optimize routes
            $results['route_optimization'] = $this->optimizeRoutes();

            // Optimize views
            $results['view_optimization'] = $this->optimizeViews();

        } catch (\Exception $e) {
            Log::error('Application optimization error: ' . $e->getMessage());
            $results['error'] = $e->getMessage();
        }

        return $results;
    }

    /**
     * Monitor system performance
     */
    public function monitorPerformance(): array
    {
        $metrics = [];

        try {
            // Database performance metrics
            $metrics['database'] = $this->getDatabaseMetrics();

            // Cache performance metrics
            $metrics['cache'] = $this->cacheService->getCacheStats();

            // Application performance metrics
            $metrics['application'] = $this->getApplicationMetrics();

            // System resource metrics
            $metrics['system'] = $this->getSystemMetrics();

            // Response time metrics
            $metrics['response_times'] = $this->getResponseTimeMetrics();

        } catch (\Exception $e) {
            Log::error('Performance monitoring error: ' . $e->getMessage());
            $metrics['error'] = $e->getMessage();
        }

        return $metrics;
    }

    /**
     * Get performance recommendations
     */
    public function getPerformanceRecommendations(): array
    {
        $recommendations = [];

        try {
            $metrics = $this->monitorPerformance();

            // Database recommendations
            if (isset($metrics['database']['slow_queries']) && $metrics['database']['slow_queries'] > 0) {
                $recommendations[] = [
                    'category' => 'database',
                    'priority' => 'high',
                    'message' => 'Found ' . $metrics['database']['slow_queries'] . ' slow queries. Consider optimizing indexes.',
                    'action' => 'Run query optimization'
                ];
            }

            // Cache recommendations
            if (isset($metrics['cache']['hit_rate']) && $metrics['cache']['hit_rate'] < 80) {
                $recommendations[] = [
                    'category' => 'cache',
                    'priority' => 'medium',
                    'message' => 'Cache hit rate is low (' . $metrics['cache']['hit_rate'] . '%). Consider caching more data.',
                    'action' => 'Review cache strategy'
                ];
            }

            // Memory recommendations
            if (isset($metrics['system']['memory_usage']) && $metrics['system']['memory_usage'] > 80) {
                $recommendations[] = [
                    'category' => 'system',
                    'priority' => 'high',
                    'message' => 'High memory usage detected. Consider optimizing memory usage.',
                    'action' => 'Review memory usage'
                ];
            }

            // Response time recommendations
            if (isset($metrics['response_times']['average']) && $metrics['response_times']['average'] > 1000) {
                $recommendations[] = [
                    'category' => 'performance',
                    'priority' => 'high',
                    'message' => 'Average response time is high. Consider optimizing queries and caching.',
                    'action' => 'Optimize application performance'
                ];
            }

        } catch (\Exception $e) {
            Log::error('Performance recommendations error: ' . $e->getMessage());
            $recommendations[] = [
                'category' => 'error',
                'priority' => 'high',
                'message' => 'Error generating recommendations: ' . $e->getMessage(),
                'action' => 'Check system logs'
            ];
        }

        return $recommendations;
    }

    /**
     * Get table size
     */
    protected function getTableSize(string $table): string
    {
        try {
            $result = DB::select("SELECT page_count * page_size as size FROM pragma_page_count('{$table}'), pragma_page_size('{$table}')");
            $size = $result[0]->size ?? 0;
            return $this->formatBytes($size);
        } catch (\Exception $e) {
            return 'unknown';
        }
    }

    /**
     * Get table indexes
     */
    protected function getTableIndexes(string $table): array
    {
        try {
            $indexes = DB::select("PRAGMA index_list('{$table}')");
            return array_map(function($index) {
                return $index->name;
            }, $indexes);
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * Find missing indexes
     */
    protected function findMissingIndexes(): array
    {
        $missingIndexes = [];

        // Common missing indexes for our application
        $suggestions = [
            [
                'name' => 'idx_link_clicks_created_at',
                'sql' => 'CREATE INDEX IF NOT EXISTS idx_link_clicks_created_at ON link_clicks(created_at)',
                'table' => 'link_clicks',
                'column' => 'created_at'
            ],
            [
                'name' => 'idx_blog_visitors_visited_at',
                'sql' => 'CREATE INDEX IF NOT EXISTS idx_blog_visitors_visited_at ON blog_visitors(visited_at)',
                'table' => 'blog_visitors',
                'column' => 'visited_at'
            ],
            [
                'name' => 'idx_user_earnings_created_at',
                'sql' => 'CREATE INDEX IF NOT EXISTS idx_user_earnings_created_at ON user_earnings(created_at)',
                'table' => 'user_earnings',
                'column' => 'created_at'
            ],
            [
                'name' => 'idx_links_user_id',
                'sql' => 'CREATE INDEX IF NOT EXISTS idx_links_user_id ON links(user_id)',
                'table' => 'links',
                'column' => 'user_id'
            ],
            [
                'name' => 'idx_blog_posts_user_id',
                'sql' => 'CREATE INDEX IF NOT EXISTS idx_blog_posts_user_id ON blog_posts(user_id)',
                'table' => 'blog_posts',
                'column' => 'user_id'
            ],
        ];

        foreach ($suggestions as $suggestion) {
            if (!$this->indexExists($suggestion['table'], $suggestion['name'])) {
                $missingIndexes[] = $suggestion;
            }
        }

        return $missingIndexes;
    }

    /**
     * Check if index exists
     */
    protected function indexExists(string $table, string $indexName): bool
    {
        try {
            $indexes = DB::select("PRAGMA index_list('{$table}')");
            foreach ($indexes as $index) {
                if ($index->name === $indexName) {
                    return true;
                }
            }
            return false;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Analyze existing indexes
     */
    protected function analyzeExistingIndexes(): array
    {
        $analysis = [];

        try {
            $tables = ['users', 'links', 'link_clicks', 'blog_posts', 'blog_visitors', 'user_earnings'];

            foreach ($tables as $table) {
                $indexes = DB::select("PRAGMA index_list('{$table}')");
                $analysis[$table] = [
                    'index_count' => count($indexes),
                    'indexes' => array_map(function($index) {
                        return [
                            'name' => $index->name,
                            'unique' => $index->unique,
                            'origin' => $index->origin
                        ];
                    }, $indexes)
                ];
            }

        } catch (\Exception $e) {
            Log::error('Index analysis error: ' . $e->getMessage());
            $analysis['error'] = $e->getMessage();
        }

        return $analysis;
    }

    /**
     * Get slow queries
     */
    protected function getSlowQueries(): array
    {
        // In a real application, you would query the slow query log
        // For now, return common slow query patterns
        return [
            'SELECT * FROM link_clicks WHERE created_at > ? ORDER BY created_at DESC',
            'SELECT * FROM blog_visitors WHERE visited_at > ? ORDER BY visited_at DESC',
            'SELECT * FROM user_earnings WHERE created_at > ? ORDER BY created_at DESC',
        ];
    }

    /**
     * Optimize a specific query
     */
    protected function optimizeQuery(string $query): array
    {
        return [
            'original_query' => $query,
            'optimized_query' => $this->suggestOptimization($query),
            'recommendations' => $this->getQueryRecommendations($query),
        ];
    }

    /**
     * Suggest query optimization
     */
    protected function suggestOptimization(string $query): string
    {
        // Simple query optimization suggestions
        $optimized = $query;

        // Replace SELECT * with specific columns
        if (strpos($optimized, 'SELECT *') !== false) {
            $optimized = str_replace('SELECT *', 'SELECT id, created_at', $optimized);
        }

        // Add LIMIT clause
        if (strpos($optimized, 'ORDER BY') !== false && strpos($optimized, 'LIMIT') === false) {
            $optimized .= ' LIMIT 100';
        }

        return $optimized;
    }

    /**
     * Get query recommendations
     */
    protected function getQueryRecommendations(string $query): array
    {
        $recommendations = [];

        if (strpos($query, 'SELECT *') !== false) {
            $recommendations[] = 'Use specific columns instead of SELECT *';
        }

        if (strpos($query, 'ORDER BY') !== false && strpos($query, 'LIMIT') === false) {
            $recommendations[] = 'Add LIMIT clause to prevent large result sets';
        }

        if (strpos($query, 'WHERE') !== false) {
            $recommendations[] = 'Ensure WHERE clause columns are indexed';
        }

        return $recommendations;
    }

    /**
     * Cache frequent queries
     */
    protected function cacheFrequentQueries(): array
    {
        $results = [];

        try {
            // Cache global settings
            $this->cacheService->getGlobalSettings(function() {
                return \App\Models\GlobalSetting::getSettings()->toArray();
            });

            $results['global_settings_cached'] = true;

            // Cache user analytics
            $users = \App\Models\User::take(10)->get();
            foreach ($users as $user) {
                $this->cacheService->getUserAnalytics($user->id, function() use ($user) {
                    return $this->getUserAnalyticsData($user->id);
                });
            }

            $results['user_analytics_cached'] = count($users);

        } catch (\Exception $e) {
            Log::error('Query caching error: ' . $e->getMessage());
            $results['error'] = $e->getMessage();
        }

        return $results;
    }

    /**
     * Get user analytics data
     */
    protected function getUserAnalyticsData(int $userId): array
    {
        return [
            'total_links' => \App\Models\Link::where('user_id', $userId)->count(),
            'total_clicks' => \App\Models\LinkClick::whereHas('link', function($q) use ($userId) {
                $q->where('user_id', $userId);
            })->count(),
            'total_earnings' => \App\Models\UserEarning::where('user_id', $userId)->sum('amount'),
        ];
    }

    /**
     * Vacuum database
     */
    protected function vacuumDatabase(): array
    {
        try {
            DB::statement('VACUUM');
            return ['success' => true, 'message' => 'Database vacuumed successfully'];
        } catch (\Exception $e) {
            Log::error('Database vacuum error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Update statistics
     */
    protected function updateStatistics(): array
    {
        try {
            DB::statement('ANALYZE');
            return ['success' => true, 'message' => 'Statistics updated successfully'];
        } catch (\Exception $e) {
            Log::error('Statistics update error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Clear application cache
     */
    protected function clearApplicationCache(): array
    {
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');

            return ['success' => true, 'message' => 'Application cache cleared successfully'];
        } catch (\Exception $e) {
            Log::error('Cache clear error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Optimize autoloader
     */
    protected function optimizeAutoloader(): array
    {
        try {
            Artisan::call('optimize');
            return ['success' => true, 'message' => 'Autoloader optimized successfully'];
        } catch (\Exception $e) {
            Log::error('Autoloader optimization error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Optimize configuration
     */
    protected function optimizeConfiguration(): array
    {
        try {
            Artisan::call('config:cache');
            return ['success' => true, 'message' => 'Configuration optimized successfully'];
        } catch (\Exception $e) {
            Log::error('Configuration optimization error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Optimize routes
     */
    protected function optimizeRoutes(): array
    {
        try {
            Artisan::call('route:cache');
            return ['success' => true, 'message' => 'Routes optimized successfully'];
        } catch (\Exception $e) {
            Log::error('Route optimization error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Optimize views
     */
    protected function optimizeViews(): array
    {
        try {
            Artisan::call('view:cache');
            return ['success' => true, 'message' => 'Views optimized successfully'];
        } catch (\Exception $e) {
            Log::error('View optimization error: ' . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Get database metrics
     */
    protected function getDatabaseMetrics(): array
    {
        try {
            $tables = ['users', 'links', 'link_clicks', 'blog_posts', 'blog_visitors', 'user_earnings'];
            $totalRows = 0;

            foreach ($tables as $table) {
                $totalRows += DB::table($table)->count();
            }

            return [
                'total_tables' => count($tables),
                'total_rows' => $totalRows,
                'slow_queries' => 0, // Would be tracked in production
                'connection_count' => 1, // Would be tracked in production
            ];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }

    /**
     * Get application metrics
     */
    protected function getApplicationMetrics(): array
    {
        return [
            'memory_usage' => memory_get_usage(true),
            'peak_memory_usage' => memory_get_peak_usage(true),
            'request_count' => 0, // Would be tracked in production
            'error_count' => 0, // Would be tracked in production
        ];
    }

    /**
     * Get system metrics
     */
    protected function getSystemMetrics(): array
    {
        return [
            'memory_usage' => $this->getMemoryUsage(),
            'disk_usage' => $this->getDiskUsage(),
            'cpu_usage' => $this->getCpuUsage(),
        ];
    }

    /**
     * Get response time metrics
     */
    protected function getResponseTimeMetrics(): array
    {
        return [
            'average' => 0, // Would be tracked in production
            'min' => 0,
            'max' => 0,
            'p95' => 0,
        ];
    }

    /**
     * Get memory usage
     */
    protected function getMemoryUsage(): float
    {
        return memory_get_usage(true) / 1024 / 1024; // MB
    }

    /**
     * Get disk usage
     */
    protected function getDiskUsage(): float
    {
        $path = storage_path();
        $total = disk_total_space($path);
        $free = disk_free_space($path);
        return (($total - $free) / $total) * 100;
    }

    /**
     * Get CPU usage
     */
    protected function getCpuUsage(): float
    {
        // Simple CPU usage estimation
        return 0.0; // Would be calculated in production
    }

    /**
     * Format bytes to human readable format
     */
    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
