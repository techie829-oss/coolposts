<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Performance Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">System Performance & Optimization</h2>
                        <a href="{{ route('admin.dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            Back to Dashboard
                        </a>
                    </div>

                    <!-- Performance Overview -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                            <div class="flex items-center">
                                <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                                    <i class="fas fa-tachometer-alt text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-blue-100">Response Time</p>
                                    <p class="text-2xl font-bold">{{ $metrics['avg_response_time'] ?? 150 }}ms</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
                            <div class="flex items-center">
                                <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                                    <i class="fas fa-server text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-green-100">Uptime</p>
                                    <p class="text-2xl font-bold">{{ $metrics['uptime'] ?? 99.9 }}%</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg p-6 text-white">
                            <div class="flex items-center">
                                <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                                    <i class="fas fa-memory text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-yellow-100">Memory Usage</p>
                                    <p class="text-2xl font-bold">{{ $metrics['memory_usage'] ?? 45 }}%</p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white">
                            <div class="flex items-center">
                                <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                                    <i class="fas fa-database text-xl"></i>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-purple-100">DB Connections</p>
                                    <p class="text-2xl font-bold">{{ $metrics['db_connections'] ?? 5 }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Performance Actions -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Cache Management</h3>
                            </div>
                            <div class="p-6">
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Cache Status</span>
                                        <span class="font-semibold text-gray-900">{{ $cache_stats['status'] ?? 'Active' }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Cache Hit Rate</span>
                                        <span class="font-semibold text-gray-900">{{ $cache_stats['hit_rate'] ?? 85 }}%</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Cache Size</span>
                                        <span class="font-semibold text-gray-900">{{ $cache_stats['size'] ?? '2.5 MB' }}</span>
                                    </div>
                                    <div class="pt-4">
                                        <form action="{{ route('admin.performance.clear-cache') }}" method="POST" class="inline">
                                            @csrf
                                            <input type="hidden" name="type" value="all">
                                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md transition-colors duration-200" onclick="return confirm('Are you sure you want to clear all cache?')">
                                                <i class="fas fa-trash mr-2"></i>Clear Cache
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Database Optimization</h3>
                            </div>
                            <div class="p-6">
                                <div class="space-y-4">
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Database Size</span>
                                        <span class="font-semibold text-gray-900">{{ $db_stats['size'] ?? '15.2 MB' }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Tables</span>
                                        <span class="font-semibold text-gray-900">{{ $db_stats['tables'] ?? 12 }}</span>
                                    </div>
                                    <div class="flex justify-between items-center">
                                        <span class="text-gray-600">Last Optimized</span>
                                        <span class="font-semibold text-gray-900">{{ $db_stats['last_optimized'] ?? '2 days ago' }}</span>
                                    </div>
                                    <div class="pt-4">
                                        <form action="{{ route('admin.performance.optimize-database') }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md transition-colors duration-200" onclick="return confirm('Are you sure you want to optimize the database? This may take a few minutes.')">
                                                <i class="fas fa-database mr-2"></i>Optimize Database
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Performance Actions -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Application Optimization</h3>
                            </div>
                            <div class="p-6">
                                <p class="text-gray-600 text-sm mb-4">Optimize application performance and clear compiled views.</p>
                                <form action="{{ route('admin.performance.optimize-application') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md transition-colors duration-200">
                                        <i class="fas fa-cogs mr-2"></i>Optimize Application
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Cache Optimization</h3>
                            </div>
                            <div class="p-6">
                                <p class="text-gray-600 text-sm mb-4">Optimize cache configuration and rebuild cache indexes.</p>
                                <form action="{{ route('admin.performance.optimize-cache') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-md transition-colors duration-200">
                                        <i class="fas fa-magic mr-2"></i>Optimize Cache
                                    </button>
                                </form>
                            </div>
                        </div>

                        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Full Optimization</h3>
                            </div>
                            <div class="p-6">
                                <p class="text-gray-600 text-sm mb-4">Run complete system optimization including all components.</p>
                                <form action="{{ route('admin.performance.full-optimization') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md transition-colors duration-200" onclick="return confirm('This will run a full system optimization. This may take several minutes. Continue?')">
                                        <i class="fas fa-rocket mr-2"></i>Full Optimization
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- System Health -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">System Health Check</h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <div class="space-y-3">
                                    <h4 class="font-medium text-gray-900">Application Health</h4>
                                    <div class="space-y-2">
                                        <div class="flex items-center">
                                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                            <span class="text-sm text-gray-600">Laravel Framework</span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                            <span class="text-sm text-gray-600">Database Connection</span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                            <span class="text-sm text-gray-600">Cache System</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <h4 class="font-medium text-gray-900">Service Status</h4>
                                    <div class="space-y-2">
                                        <div class="flex items-center">
                                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                            <span class="text-sm text-gray-600">Web Server</span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                            <span class="text-sm text-gray-600">Queue System</span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
                                            <span class="text-sm text-gray-600">Redis Cache</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-3">
                                    <h4 class="font-medium text-gray-900">Security Status</h4>
                                    <div class="space-y-2">
                                        <div class="flex items-center">
                                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                            <span class="text-sm text-gray-600">SSL Certificate</span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                            <span class="text-sm text-gray-600">Firewall</span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                                            <span class="text-sm text-gray-600">Rate Limiting</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Success/Error Messages -->
                    @if(session('success'))
                        <div class="mt-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mt-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for AJAX operations -->
    <script>
        // Add loading states to buttons
        document.addEventListener('DOMContentLoaded', function() {
            const forms = document.querySelectorAll('form');

            forms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const button = form.querySelector('button[type="submit"]');
                    const originalText = button.innerHTML;

                    // Show loading state
                    button.disabled = true;
                    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Processing...';

                    // Re-enable button after 5 seconds as fallback
                    setTimeout(() => {
                        button.disabled = false;
                        button.innerHTML = originalText;
                    }, 5000);
                });
            });
        });
    </script>
</x-app-layout>
