<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Performance Report') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header & Controls -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">System Performance Analysis</h3>
                        <p class="text-sm text-gray-500">
                            Report Period: {{ \Carbon\Carbon::parse($report['period']['start'])->format('M d, Y') }} -
                            {{ \Carbon\Carbon::parse($report['period']['end'])->format('M d, Y') }}
                        </p>
                    </div>
                    <div class="flex items-center gap-3">
                        <form method="GET" action="{{ route('admin.performance.report') }}"
                            class="flex items-center gap-2">
                            <input type="date" name="start_date" value="{{ $report['period']['start'] }}"
                                class="rounded-md border-gray-300 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50 text-sm">
                            <span class="text-gray-500">-</span>
                            <input type="date" name="end_date" value="{{ $report['period']['end'] }}"
                                class="rounded-md border-gray-300 shadow-sm focus:border-purple-300 focus:ring focus:ring-purple-200 focus:ring-opacity-50 text-sm">
                            <button type="submit"
                                class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-md text-sm font-medium transition-colors">
                                Update
                            </button>
                        </form>
                        <button onclick="window.print()"
                            class="px-3 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-md text-sm font-medium transition-colors">
                            <i class="fas fa-download mr-1"></i> Export
                        </button>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Overall Health Score (Placeholder/Derived) -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">System Health</p>
                        <h4
                            class="text-2xl font-bold {{ ($report['system_health']['overall_status'] ?? 'healthy') === 'healthy' ? 'text-green-600' : 'text-red-600' }}">
                            {{ ucfirst($report['system_health']['overall_status'] ?? 'Unknown') }}
                        </h4>
                    </div>
                    <div
                        class="p-3 rounded-full {{ ($report['system_health']['overall_status'] ?? 'healthy') === 'healthy' ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                        <i class="fas fa-heartbeat text-xl"></i>
                    </div>
                </div>

                <!-- Memory Usage -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Memory Usage</p>
                        <h4 class="text-2xl font-bold text-gray-900">
                            {{ $report['metrics']['memory']['current'] ?? 'N/A' }}</h4>
                        <p class="text-xs text-gray-500">Peak: {{ $report['metrics']['memory']['peak'] ?? 'N/A' }}</p>
                    </div>
                    <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                        <i class="fas fa-memory text-xl"></i>
                    </div>
                </div>

                <!-- Cache Status -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Cache Hit Rate</p>
                        <h4 class="text-2xl font-bold text-gray-900">{{ $report['cache_stats']['hit_rate'] ?? '0' }}%
                        </h4>
                        <p class="text-xs text-gray-500">Driver: {{ $report['cache_stats']['driver'] ?? 'N/A' }}</p>
                    </div>
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-600">
                        <i class="fas fa-bolt text-xl"></i>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Recommendations -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 h-full">
                    <div class="p-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="font-medium text-gray-900"><i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                            Optimization Recommendations</h3>
                    </div>
                    <div class="p-4">
                        @if (empty($report['recommendations']))
                            <div class="text-center py-8 text-gray-500">
                                <i class="fas fa-check-circle text-4xl text-green-200 mb-2"></i>
                                <p>No optimizations needed at this time.</p>
                            </div>
                        @else
                            <ul class="space-y-4">
                                @foreach ($report['recommendations'] as $rec)
                                    <li class="flex items-start p-3 bg-blue-50 rounded-lg">
                                        <i class="fas fa-info-circle text-blue-500 mt-1 mr-3"></i>
                                        <div>
                                            <p class="font-medium text-blue-900">
                                                {{ $rec['title'] ?? 'Recommendation' }}</p>
                                            <p class="text-sm text-blue-700 mt-1">{{ $rec['description'] ?? '' }}</p>
                                            @if (isset($rec['action']))
                                                <button
                                                    class="mt-2 text-xs font-medium bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700 transition-colors">
                                                    {{ $rec['action_label'] ?? 'Fix This' }}
                                                </button>
                                            @endif
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <!-- Detailed Metrics -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 h-full">
                    <div class="p-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="font-medium text-gray-900"><i class="fas fa-chart-bar text-purple-500 mr-2"></i>
                            Detailed Metrics</h3>
                    </div>
                    <div class="p-4 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <tbody class="divide-y divide-gray-200">
                                <tr>
                                    <td class="py-3 text-sm font-medium text-gray-500">CPU Load</td>
                                    <td class="py-3 text-sm text-gray-900">
                                        {{ $report['metrics']['cpu_load'] ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="py-3 text-sm font-medium text-gray-500">Disk Space</td>
                                    <td class="py-3 text-sm text-gray-900">
                                        {{ $report['metrics']['disk_free'] ?? 'N/A' }} free /
                                        {{ $report['metrics']['disk_total'] ?? 'N/A' }} total</td>
                                </tr>
                                <tr>
                                    <td class="py-3 text-sm font-medium text-gray-500">Database Size</td>
                                    <td class="py-3 text-sm text-gray-900">
                                        {{ $report['metrics']['database_size'] ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <td class="py-3 text-sm font-medium text-gray-500">Slow Queries</td>
                                    <td class="py-3 text-sm text-gray-900">
                                        {{ $report['metrics']['slow_queries'] ?? 0 }} (Last 24h)</td>
                                </tr>
                                <tr>
                                    <td class="py-3 text-sm font-medium text-gray-500">Queue Status</td>
                                    <td class="py-3 text-sm text-gray-900">{{ $report['metrics']['queue_size'] ?? 0 }}
                                        jobs pending</td>
                                </tr>
                                <tr>
                                    <td class="py-3 text-sm font-medium text-gray-500">Failed Jobs</td>
                                    <td
                                        class="py-3 text-sm {{ ($report['metrics']['failed_jobs'] ?? 0) > 0 ? 'text-red-600 font-bold' : 'text-gray-900' }}">
                                        {{ $report['metrics']['failed_jobs'] ?? 0 }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- System Health Details -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="p-4 border-b border-gray-200 bg-gray-50">
                    <h3 class="font-medium text-gray-900">System Checklist</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-4">
                    @if (isset($report['system_health']['checks']))
                        @foreach ($report['system_health']['checks'] as $check => $status)
                            <div
                                class="flex items-center p-3 rounded-lg border {{ $status ? 'border-green-200 bg-green-50' : 'border-red-200 bg-red-50' }}">
                                <div class="flex-shrink-0 mr-3">
                                    <i
                                        class="fas {{ $status ? 'fa-check-circle text-green-500' : 'fa-times-circle text-red-500' }} text-lg"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium {{ $status ? 'text-green-800' : 'text-red-800' }}">
                                        {{ ucwords(str_replace('_', ' ', $check)) }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <div class="mt-6 text-center text-xs text-gray-400">
                Report Generated: {{ $report['generated_at'] }}
            </div>
        </div>
    </div>
</x-app-layout>
