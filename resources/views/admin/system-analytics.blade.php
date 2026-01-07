<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('System Analytics') }}
        </h2>
    </x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">System Analytics</h2>
                    <a href="{{ route('admin.dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        Back to Dashboard
                    </a>
                </div>

                <!-- System Overview -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                        <div class="flex items-center">
                            <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-blue-100">Total Users</p>
                                <p class="text-2xl font-bold">{{ number_format($analytics['total_users']) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg p-6 text-white">
                        <div class="flex items-center">
                            <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-green-100">Total Clicks</p>
                                <p class="text-2xl font-bold">{{ number_format($analytics['total_clicks']) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 rounded-lg p-6 text-white">
                        <div class="flex items-center">
                            <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-yellow-100">Total Earnings</p>
                                <p class="text-2xl font-bold">₹{{ number_format($analytics['total_earnings'], 2) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg p-6 text-white">
                        <div class="flex items-center">
                            <div class="p-2 bg-white bg-opacity-20 rounded-lg">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-purple-100">System Health</p>
                                <p class="text-2xl font-bold">{{ $analytics['system_health'] }}%</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Performance Metrics -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Performance Metrics</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Average Response Time</span>
                                    <span class="font-semibold text-gray-900">{{ $analytics['avg_response_time'] }}ms</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Uptime</span>
                                    <span class="font-semibold text-gray-900">{{ $analytics['uptime'] }}%</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Active Sessions</span>
                                    <span class="font-semibold text-gray-900">{{ number_format($analytics['active_sessions']) }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Database Connections</span>
                                    <span class="font-semibold text-gray-900">{{ $analytics['db_connections'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Resource Usage</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-gray-600">CPU Usage</span>
                                        <span class="font-semibold text-gray-900">{{ $analytics['cpu_usage'] }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $analytics['cpu_usage'] }}%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-gray-600">Memory Usage</span>
                                        <span class="font-semibold text-gray-900">{{ $analytics['memory_usage'] }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-600 h-2 rounded-full" style="width: {{ $analytics['memory_usage'] }}%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-gray-600">Disk Usage</span>
                                        <span class="font-semibold text-gray-900">{{ $analytics['disk_usage'] }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-yellow-600 h-2 rounded-full" style="width: {{ $analytics['disk_usage'] }}%"></div>
                                    </div>
                                </div>
                                <div>
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-gray-600">Network Load</span>
                                        <span class="font-semibold text-gray-900">{{ $analytics['network_load'] }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $analytics['network_load'] }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Recent System Activity</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Details</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($analytics['recent_activity'] ?? [] as $activity)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ $activity['event'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $activity['user'] }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        {{ $activity['details'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $activity['time'] }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $activity['status'] === 'success' ? 'bg-green-100 text-green-800' : ($activity['status'] === 'warning' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($activity['status']) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        No recent activity
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- System Alerts -->
                @if(!empty($analytics['alerts']))
                <div class="mt-8 bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">System Alerts</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @foreach($analytics['alerts'] as $alert)
                            <div class="flex items-start space-x-3 p-4 {{ $alert['level'] === 'critical' ? 'bg-red-50 border border-red-200' : ($alert['level'] === 'warning' ? 'bg-yellow-50 border border-yellow-200' : 'bg-blue-50 border border-blue-200') }} rounded-lg">
                                <div class="flex-shrink-0">
                                    @if($alert['level'] === 'critical')
                                        <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                    @elseif($alert['level'] === 'warning')
                                        <svg class="w-5 h-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium {{ $alert['level'] === 'critical' ? 'text-red-800' : ($alert['level'] === 'warning' ? 'text-yellow-800' : 'text-blue-800') }}">
                                        {{ $alert['title'] }}
                                    </h4>
                                    <p class="text-sm {{ $alert['level'] === 'critical' ? 'text-red-700' : ($alert['level'] === 'warning' ? 'text-yellow-700' : 'text-blue-700') }}">
                                        {{ $alert['message'] }}
                                    </p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="text-xs {{ $alert['level'] === 'critical' ? 'text-red-600' : ($alert['level'] === 'warning' ? 'text-yellow-600' : 'text-blue-600') }}">
                                        {{ $alert['time'] }}
                                    </span>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
</x-app-layout>
