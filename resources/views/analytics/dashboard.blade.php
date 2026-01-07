<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Analytics Dashboard') }}
            </h2>
            <div class="flex space-x-2">
                <button onclick="refreshRealTimeData()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-sync-alt mr-2"></i>Refresh
                </button>
                <button onclick="exportAnalytics()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-download mr-2"></i>Export
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Real-time Stats Bar -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-gradient-to-r from-blue-500 to-purple-600 text-white">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold" id="real-time-clicks">-</div>
                            <div class="text-sm opacity-90">Today's Clicks</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold" id="real-time-earnings">-</div>
                            <div class="text-sm opacity-90">Today's Earnings</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold" id="real-time-hour-clicks">-</div>
                            <div class="text-sm opacity-90">This Hour</div>
                        </div>
                        <div class="text-center">
                            <div class="text-sm opacity-90">Last Updated</div>
                            <div class="text-lg font-semibold" id="last-updated">-</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Overall Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-link text-3xl text-blue-600"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Total Links</div>
                                <div class="text-2xl font-bold text-gray-900">{{ $overallStats['total_links'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-mouse-pointer text-3xl text-green-600"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Total Clicks</div>
                                <div class="text-2xl font-bold text-gray-900">{{ number_format($overallStats['total_clicks']) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-coins text-3xl text-yellow-600"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Total Earnings</div>
                                <div class="text-2xl font-bold text-gray-900">
                                    @if($overallStats['currency'] === 'INR')
                                        ₹{{ number_format($overallStats['total_earnings'], 2) }}
                                    @else
                                        ${{ number_format($overallStats['total_earnings'], 2) }}
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-chart-line text-3xl text-purple-600"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Conversion Rate</div>
                                <div class="text-2xl font-bold text-gray-900">{{ $overallStats['conversion_rate'] }}%</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Daily Clicks Chart -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Daily Clicks (Last 30 Days)</h3>
                        <canvas id="dailyClicksChart" width="400" height="200"></canvas>
                    </div>
                </div>

                <!-- Daily Earnings Chart -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Daily Earnings (Last 30 Days)</h3>
                        <canvas id="dailyEarningsChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- Link Performance & Referral Analytics -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Top Performing Links -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Performing Links</h3>
                        <div class="space-y-3">
                            @forelse($linkAnalytics['top_performers'] as $link)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex-1">
                                        <div class="font-medium text-gray-900">{{ Str::limit($link->title, 30) }}</div>
                                        <div class="text-sm text-gray-500">{{ $link->clicks_count }} clicks</div>
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-medium text-gray-900">
                                            @if($overallStats['currency'] === 'INR')
                                                ₹{{ number_format($link->earnings_sum ?? 0, 2) }}
                                            @else
                                                ${{ number_format($link->earnings_sum ?? 0, 2) }}
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500">earnings</div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-500">
                                    <i class="fas fa-chart-bar text-4xl mb-2"></i>
                                    <p>No link performance data available</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Referral Analytics -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Referral Analytics</h3>
                        <div class="space-y-4">
                            <div class="grid grid-cols-2 gap-4">
                                <div class="text-center p-3 bg-blue-50 rounded-lg">
                                    <div class="text-2xl font-bold text-blue-600">{{ $referralAnalytics['total_referrals'] }}</div>
                                    <div class="text-sm text-blue-800">Total Referrals</div>
                                </div>
                                <div class="text-center p-3 bg-green-50 rounded-lg">
                                    <div class="text-2xl font-bold text-green-600">{{ $referralAnalytics['completed_referrals'] }}</div>
                                    <div class="text-sm text-green-800">Completed</div>
                                </div>
                            </div>
                            <div class="text-center p-3 bg-purple-50 rounded-lg">
                                <div class="text-2xl font-bold text-purple-600">{{ $referralAnalytics['completion_rate'] }}%</div>
                                <div class="text-sm text-purple-800">Completion Rate</div>
                            </div>
                            <div class="text-center p-3 bg-yellow-50 rounded-lg">
                                <div class="text-2xl font-bold text-yellow-600">
                                    @if($overallStats['currency'] === 'INR')
                                        ₹{{ number_format($referralAnalytics['referral_earnings']['inr'], 2) }}
                                    @else
                                        ${{ number_format($referralAnalytics['referral_earnings']['usd'], 2) }}
                                    @endif
                                </div>
                                <div class="text-sm text-yellow-800">Referral Earnings</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Geographic & Device Analytics -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Geographic Analytics -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Top Countries</h3>
                        <div class="space-y-3">
                            @foreach($geoAnalytics['top_countries'] as $country)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                                        <span class="text-gray-700">{{ $country['country'] }}</span>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-medium text-gray-900">{{ $country['clicks'] }}</div>
                                        <div class="text-sm text-gray-500">{{ $country['percentage'] }}%</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Device Analytics -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Device Distribution</h3>
                        <div class="space-y-3">
                            @foreach($deviceAnalytics['devices'] as $device)
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                                        <span class="text-gray-700">{{ $device['device'] }}</span>
                                    </div>
                                    <div class="text-right">
                                        <div class="font-medium text-gray-900">{{ $device['clicks'] }}</div>
                                        <div class="text-sm text-gray-500">{{ $device['percentage'] }}%</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Export Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Export Analytics Data</h3>
                    <form id="exportForm" class="flex flex-wrap gap-4 items-end">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                            <input type="date" id="start_date" name="start_date" value="{{ $startDate->format('Y-m-d') }}" 
                                   class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                            <input type="date" id="end_date" name="end_date" value="{{ $endDate->format('Y-m-d') }}" 
                                   class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label for="format" class="block text-sm font-medium text-gray-700 mb-1">Format</label>
                            <select id="format" name="format" class="border-gray-300 focus:border-blue-500 focus:ring-blue-500 rounded-md shadow-sm">
                                <option value="json">JSON</option>
                                <option value="csv">CSV</option>
                                <option value="xlsx">Excel</option>
                            </select>
                        </div>
                        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-download mr-2"></i>Export Data
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Initialize charts when page loads
        document.addEventListener('DOMContentLoaded', function() {
            initializeCharts();
            refreshRealTimeData();
            
            // Auto-refresh every 30 seconds
            setInterval(refreshRealTimeData, 30000);
        });

        // Initialize charts
        function initializeCharts() {
            // Daily Clicks Chart
            const clicksCtx = document.getElementById('dailyClicksChart').getContext('2d');
            new Chart(clicksCtx, {
                type: 'line',
                data: {
                    labels: @json(array_column($timeAnalytics['daily'], 'formatted_date')),
                    datasets: [{
                        label: 'Clicks',
                        data: @json(array_column($timeAnalytics['daily'], 'clicks')),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });

            // Daily Earnings Chart
            const earningsCtx = document.getElementById('dailyEarningsChart').getContext('2d');
            new Chart(earningsCtx, {
                type: 'line',
                data: {
                    labels: @json(array_column($timeAnalytics['daily'], 'formatted_date')),
                    datasets: [{
                        label: 'Earnings',
                        data: @json(array_column($timeAnalytics['daily'], 'earnings')),
                        borderColor: 'rgb(16, 185, 129)',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Refresh real-time data
        function refreshRealTimeData() {
            fetch('{{ route("analytics.real-time") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('real-time-clicks').textContent = data.data.today_clicks;
                        document.getElementById('real-time-earnings').textContent = 
                            '{{ $overallStats["currency"] === "INR" ? "₹" : "$" }}' + data.data.today_earnings;
                        document.getElementById('real-time-hour-clicks').textContent = data.data.this_hour_clicks;
                        document.getElementById('last-updated').textContent = data.data.last_updated;
                    }
                })
                .catch(error => console.error('Error fetching real-time data:', error));
        }

        // Export analytics data
        function exportAnalytics() {
            const form = document.getElementById('exportForm');
            const formData = new FormData(form);
            
            fetch('{{ route("analytics.export") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Create download link for JSON
                    const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'analytics-data.json';
                    a.click();
                    window.URL.revokeObjectURL(url);
                }
            })
            .catch(error => console.error('Error exporting data:', error));
        }

        // Handle export form submission
        document.getElementById('exportForm').addEventListener('submit', function(e) {
            e.preventDefault();
            exportAnalytics();
        });
    </script>
    @endpush
</x-app-layout>
