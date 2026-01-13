<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <!-- Welcome Header -->
        <div class="mb-6">
            <div
                class="bg-gradient-to-r from-purple-600 via-pink-600 to-red-600 rounded-2xl p-6 text-white relative overflow-hidden shadow-lg">
                <!-- Background Pattern -->
                <div class="absolute inset-0 bg-black/10"></div>
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16">
                </div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full translate-y-12 -translate-x-12">
                </div>

                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl md:text-3xl font-bold text-white mb-1">
                            Welcome, {{ Auth::user()->name }}! 🎉
                        </h1>
                        <p class="text-purple-100 text-sm md:text-base">
                            @if ($globalSettings->isEarningsEnabled())
                                Ready to monetize?
                            @else
                                Ready to create?
                            @endif
                        </p>
                    </div>
                    <div class="flex gap-3">
                        <a href="{{ route('blog.create') }}"
                            class="px-4 py-2 bg-white/20 hover:bg-white/30 backdrop-blur-sm border border-white/20 rounded-xl text-white text-sm font-bold transition-all flex items-center">
                            <i class="fas fa-plus mr-2"></i> New Post
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Toolbar -->
        <div class="mb-6">
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('links.create') }}"
                    class="bg-white border border-gray-200 text-gray-700 px-4 py-2.5 rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 flex items-center shadow-sm text-sm font-medium">
                    <i class="fas fa-plus mr-2 text-purple-600"></i>
                    New Link
                </a>
                <a href="{{ route('links.manage') }}"
                    class="bg-white border border-gray-200 text-gray-700 px-4 py-2.5 rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 flex items-center shadow-sm text-sm font-medium">
                    <i class="fas fa-list mr-2 text-blue-600"></i>
                    Links
                </a>
                <a href="{{ route('blog.create') }}"
                    class="bg-white border border-gray-200 text-gray-700 px-4 py-2.5 rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 flex items-center shadow-sm text-sm font-medium">
                    <i class="fas fa-edit mr-2 text-green-600"></i>
                    Post
                </a>
                <a href="{{ route('blog.manage') }}"
                    class="bg-white border border-gray-200 text-gray-700 px-4 py-2.5 rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 flex items-center shadow-sm text-sm font-medium">
                    <i class="fas fa-newspaper mr-2 text-orange-600"></i>
                    Manage Blog
                </a>
                @if ($globalSettings->isEarningsEnabled())
                    <a href="{{ route('withdrawals.index') }}"
                        class="bg-white border border-gray-200 text-gray-700 px-4 py-2.5 rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all duration-200 flex items-center shadow-sm text-sm font-medium">
                        <i class="fas fa-dollar-sign mr-2 text-yellow-600"></i>
                        Withdraw
                    </a>
                @endif
            </div>
        </div>

        <!-- Statistics Cards -->
        @php
            $gridCols = $globalSettings->isEarningsEnabled() ? 'lg:grid-cols-5' : 'lg:grid-cols-3';
        @endphp

        <!-- Compact Statistics Grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <!-- Total Posts -->
            <div
                class="group bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md hover:border-gray-200 transition-all duration-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Posts</p>
                        @php
                            $totalPosts = \App\Models\BlogPost::where('user_id', Auth::id())->count();
                        @endphp
                        <h3 class="text-xl font-bold text-gray-900 mt-0.5">{{ $totalPosts }}</h3>
                    </div>
                    <div
                        class="w-8 h-8 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-file-alt text-xs"></i>
                    </div>
                </div>
            </div>

            <!-- Total Views -->
            <div
                class="group bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-md hover:border-gray-200 transition-all duration-200 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Views</p>
                        <h3 class="text-xl font-bold text-gray-900 mt-0.5">
                            {{ number_format($stats['total_clicks'] ?? 0) }}
                        </h3>
                    </div>
                    <div
                        class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-eye text-xs"></i>
                    </div>
                </div>
            </div>

            <!-- Earnings (Coming Soon) -->
            <div
                class="group bg-gray-50 rounded-xl border border-gray-100 shadow-sm relative overflow-hidden opacity-90 p-4">
                <div class="flex items-center justify-between opacity-50">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Earnings</p>
                        <h3 class="text-xl font-bold text-gray-900 mt-0.5">
                            {{ $stats['currency_symbol'] ?? '$' }}0.00
                        </h3>
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-gray-200 text-gray-400 flex items-center justify-center">
                        <i class="fas fa-dollar-sign text-xs"></i>
                    </div>
                </div>
                <div class="absolute inset-0 flex items-center justify-center bg-white/60 backdrop-blur-[1px]">
                    <span
                        class="text-[10px] font-bold bg-gray-100 text-gray-500 px-2 py-1 rounded-full uppercase tracking-widest">Coming
                        Soon</span>
                </div>
            </div>

            <!-- Pending (Coming Soon) -->
            <div
                class="group bg-gray-50 rounded-xl border border-gray-100 shadow-sm relative overflow-hidden opacity-90 p-4">
                <div class="flex items-center justify-between opacity-50">
                    <div>
                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">Pending</p>
                        <h3 class="text-xl font-bold text-gray-900 mt-0.5">
                            {{ $stats['currency_symbol'] ?? '$' }}0.00
                        </h3>
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-gray-200 text-gray-400 flex items-center justify-center">
                        <i class="fas fa-clock text-xs"></i>
                    </div>
                </div>
                <div class="absolute inset-0 flex items-center justify-center bg-white/60 backdrop-blur-[1px]">
                    <span
                        class="text-[10px] font-bold bg-gray-100 text-gray-500 px-2 py-1 rounded-full uppercase tracking-widest">Coming
                        Soon</span>
                </div>
            </div>
        </div>

        <!-- Charts and Activity Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Analytics Chart -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-chart-line text-blue-500 mr-2"></i>
                    Analytics Overview
                    <span class="ml-auto text-xs font-normal text-gray-500">Last 7 Days</span>
                </h3>
                <div class="h-64">
                    <canvas id="dashboardChart"></canvas>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <i class="fas fa-history text-purple-500 mr-2"></i>
                    Recent Activity
                </h3>
                <div class="space-y-4">
                    @forelse($recentActivity as $activity)
                        <div
                            class="flex items-center p-3 hover:bg-gray-50 rounded-xl border border-transparent hover:border-gray-100 transition-all">
                            <div
                                class="w-8 h-8 rounded-full flex items-center justify-center mr-3
                                {{ $activity['type'] == 'post' ? 'bg-purple-100 text-purple-600' : 'bg-blue-100 text-blue-600' }}">
                                <i class="fas {{ $activity['type'] == 'post' ? 'fa-pen' : 'fa-link' }} text-xs"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <a href="{{ $activity['url'] }}"
                                    class="text-sm font-bold text-gray-900 hover:text-purple-600 truncate block">
                                    {{ $activity['title'] }}
                                </a>
                                <p class="text-xs text-gray-500">
                                    {{ $activity['type'] == 'post' ? 'Published' : 'Created' }}
                                    {{ $activity['time']->diffForHumans() }}
                                </p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-400">
                            <p class="text-sm">No recent activity found.</p>
                            <a href="{{ route('blog.create') }}"
                                class="text-xs text-purple-600 hover:underline mt-2 inline-block">Start creating!</a>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Logic -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('dashboardChart').getContext('2d');

            // Stats Data passed via controller
            const labels = {!! json_encode($chartData['labels']) !!};
            const data = {!! json_encode($chartData['data']) !!};

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Post Views',
                        data: data,
                        borderColor: '#8b5cf6', // purple-500
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#8b5cf6',
                        pointHoverBackgroundColor: '#8b5cf6',
                        pointHoverBorderColor: '#fff',
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
                            grid: {
                                color: '#f3f4f6',
                                borderDash: [2, 2]
                            },
                            ticks: {
                                stepSize: 1
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
