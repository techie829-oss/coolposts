<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Real-Time Analytics</h1>
                <p class="mt-1 text-sm text-gray-500">Live monitoring of system performance and user activity.</p>
            </div>
            <div class="flex items-center space-x-3">
                <div class="flex items-center space-x-2 bg-green-50 px-3 py-1 rounded-full border border-green-100">
                    <div class="w-2.5 h-2.5 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-sm font-medium text-green-700">Live System</span>
                </div>
                <button id="refreshBtn"
                    class="inline-flex items-center px-4 py-2 bg-white text-gray-700 border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 transition-all duration-200">
                    <i class="fas fa-rotate mr-2 text-gray-400 group-hover:text-purple-500"></i>
                    Refresh
                </button>
            </div>
        </div>

        <!-- Real-Time Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Active Users -->
            <div
                class="bg-white overflow-hidden rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-purple-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-purple-600 text-xl"></i>
                        </div>
                        <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded-full">+12%</span>
                    </div>
                    <div class="flex items-baseline">
                        <p class="text-3xl font-bold text-gray-900" id="liveVisitors">0</p>
                        <p class="ml-2 text-sm text-gray-500">active now</p>
                    </div>
                    <div class="mt-1">
                        <p class="text-xs text-gray-400">Updates instantly</p>
                    </div>
                </div>
            </div>

            <!-- Global Clicks -->
            <div
                class="bg-white overflow-hidden rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-arrow-pointer text-blue-600 text-xl"></i>
                        </div>
                        <span class="text-xs font-medium text-blue-600 bg-blue-50 px-2 py-1 rounded-full">Today</span>
                    </div>
                    <div class="flex items-baseline">
                        <p class="text-3xl font-bold text-gray-900" id="todayClicks">0</p>
                        <p class="ml-2 text-sm text-gray-500">clicks</p>
                    </div>
                    <div class="mt-1">
                        <p class="text-xs text-gray-400">Total global clicks</p>
                    </div>
                </div>
            </div>

            <!-- Global Earnings -->
            <div
                class="bg-white overflow-hidden rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-green-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                        </div>
                        <span class="text-xs font-medium text-green-600 bg-green-50 px-2 py-1 rounded-full">Today</span>
                    </div>
                    <div class="flex items-baseline">
                        <p class="text-3xl font-bold text-gray-900" id="todayEarnings">₹0</p>
                        <p class="ml-2 text-sm text-gray-500">earned</p>
                    </div>
                    <div class="mt-1">
                        <p class="text-xs text-gray-400">Gross revenue</p>
                    </div>
                </div>
            </div>

            <!-- New Users -->
            <div
                class="bg-white overflow-hidden rounded-xl shadow-sm border border-gray-100 hover:shadow-md transition-shadow duration-200">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-pink-50 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user-plus text-pink-600 text-xl"></i>
                        </div>
                        <span class="text-xs font-medium text-pink-600 bg-pink-50 px-2 py-1 rounded-full">Today</span>
                    </div>
                    <div class="flex items-baseline">
                        <p class="text-3xl font-bold text-gray-900" id="newUsers">0</p>
                        <p class="ml-2 text-sm text-gray-500">joined</p>
                    </div>
                    <div class="mt-1">
                        <p class="text-xs text-gray-400">New registrations</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- System Status -->
            <div class="lg:col-span-2 bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-900">System Health</h3>
                    <div class="text-sm text-gray-500">Last updated: <span id="lastUpdated" class="font-mono">-</span>
                    </div>
                </div>
                <div class="p-6">
                    <div class="space-y-4" id="serviceStatus">
                        <!-- Loading State -->
                        <div class="animate-pulse space-y-4">
                            <div class="h-12 bg-gray-100 rounded-lg w-full"></div>
                            <div class="h-12 bg-gray-100 rounded-lg w-full"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden h-fit">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-900">Quick Actions</h3>
                </div>
                <div class="p-6 space-y-3">
                    <a href="{{ route('admin.performance.report') }}"
                        class="flex items-center justify-between w-full px-4 py-3 bg-gray-50 hover:bg-white hover:shadow-md border border-transparent hover:border-gray-200 rounded-lg text-left transition-all duration-200 group">
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-3 group-hover:scale-110 transition-transform">
                                <i class="fas fa-gauge-high text-sm"></i>
                            </div>
                            <span class="text-gray-700 font-medium group-hover:text-gray-900">Performance Report</span>
                        </div>
                        <i
                            class="fas fa-chevron-right text-gray-400 text-sm group-hover:text-blue-500 group-hover:translate-x-1 transition-all"></i>
                    </a>

                    <a href="{{ route('admin.fraud-detection') }}"
                        class="flex items-center justify-between w-full px-4 py-3 bg-gray-50 hover:bg-white hover:shadow-md border border-transparent hover:border-gray-200 rounded-lg text-left transition-all duration-200 group">
                        <div class="flex items-center">
                            <div
                                class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center text-red-600 mr-3 group-hover:scale-110 transition-transform">
                                <i class="fas fa-shield-halved text-sm"></i>
                            </div>
                            <span class="text-gray-700 font-medium group-hover:text-gray-900">Fraud Detection</span>
                        </div>
                        <i
                            class="fas fa-chevron-right text-gray-400 text-sm group-hover:text-red-500 group-hover:translate-x-1 transition-all"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            let updateInterval;

            document.addEventListener('DOMContentLoaded', function() {
                startRealTimeUpdates();

                document.getElementById('refreshBtn').addEventListener('click', function() {
                    const btn = this;
                    btn.classList.add('opacity-75', 'cursor-not-allowed');
                    btn.innerHTML = '<i class="fas fa-circle-notch fa-spin mr-2"></i> Refreshing...';

                    loadAdminRealTimeData().finally(() => {
                        setTimeout(() => {
                            btn.classList.remove('opacity-75', 'cursor-not-allowed');
                            btn.innerHTML =
                                '<i class="fas fa-rotate mr-2 text-gray-400 group-hover:text-purple-500"></i> Refresh';
                        }, 500);
                    });
                });
            });

            function startRealTimeUpdates() {
                loadAdminRealTimeData();
                updateInterval = setInterval(loadAdminRealTimeData, 30000); // 30s update
            }

            function loadAdminRealTimeData() {
                return fetch('{{ route('admin.realtime.analytics.data') }}')
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            updateDashboard(data.data);
                        }
                    })
                    .catch(error => console.error('Error loading admin analytics:', error));
            }

            function updateDashboard(data) {
                // Global Stats
                if (data.global_stats) {
                    animateValue("todayClicks", parseInt(document.getElementById('todayClicks').innerText) || 0, data
                        .global_stats.today.clicks || 0, 1000);
                    document.getElementById('todayEarnings').textContent = '₹' + (data.global_stats.today.earnings || 0)
                        .toFixed(2);
                    animateValue("newUsers", parseInt(document.getElementById('newUsers').innerText) || 0, data.global_stats
                        .today.new_users || 0, 1000);
                }

                // Live Visitors
                if (data.live_visitors) {
                    animateValue("liveVisitors", parseInt(document.getElementById('liveVisitors').innerText) || 0, data
                        .live_visitors.active_visitors || 0, 1000);
                }

                // Service Status
                if (data.service_status) {
                    const statusHtml = `
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-100">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600">
                                <i class="fas fa-server"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Analytics Engine</p>
                                <p class="text-xs text-gray-500">Real-time data processing</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${data.service_status.available ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                            <span class="w-1.5 h-1.5 mr-1.5 rounded-full ${data.service_status.available ? 'bg-green-500' : 'bg-red-500'}"></span>
                            ${data.service_status.available ? 'Operational' : 'Offline'}
                        </span>
                    </div>

                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-100">
                        <div class="flex items-center space-x-3">
                            <div class="p-2 bg-orange-50 rounded-lg text-orange-600">
                                <i class="fas fa-database"></i>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">Redis Cache layer</p>
                                <p class="text-xs text-gray-500">High-speed data retrieval</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${data.service_status.cache_available ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'}">
                            <span class="w-1.5 h-1.5 mr-1.5 rounded-full ${data.service_status.cache_available ? 'bg-green-500' : 'bg-yellow-500'}"></span>
                            ${data.service_status.cache_available ? 'Connected' : 'Degraded'}
                        </span>
                    </div>
                `;
                    document.getElementById('serviceStatus').innerHTML = statusHtml;
                    document.getElementById('lastUpdated').textContent = new Date().toLocaleTimeString();
                }
            }

            function animateValue(id, start, end, duration) {
                if (start === end) return;
                const range = end - start;
                let current = start;
                const increment = end > start ? 1 : -1;
                const stepTime = Math.abs(Math.floor(duration / range));
                const obj = document.getElementById(id);
                const timer = setInterval(function() {
                    current += increment;
                    obj.textContent = current;
                    if (current == end) {
                        clearInterval(timer);
                    }
                }, stepTime);
            }

            // Cleanup
            window.addEventListener('beforeunload', function() {
                if (updateInterval) clearInterval(updateInterval);
            });
        </script>
    @endpush
</x-app-layout>
