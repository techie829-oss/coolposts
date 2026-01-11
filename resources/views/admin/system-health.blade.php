<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('System Health Status') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Status -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">System Status</h3>
                        <p class="text-sm text-gray-500">Last updated:
                            {{ \Carbon\Carbon::parse($health['timestamp'])->format('M d, Y H:i:s') }}</p>
                    </div>
                    <div class="flex items-center">
                        <span
                            class="inline-flex items-center px-4 py-2 rounded-full text-sm font-bold {{ $health['overall_status'] === 'healthy' ? 'bg-green-100 text-green-800' : ($health['overall_status'] === 'warning' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            @if ($health['overall_status'] === 'healthy')
                                <i class="fas fa-check-circle mr-2"></i>
                            @elseif($health['overall_status'] === 'warning')
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                            @else
                                <i class="fas fa-times-circle mr-2"></i>
                            @endif
                            {{ ucfirst($health['overall_status']) }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <!-- Database Health -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                        <h4 class="font-medium text-gray-900 flex items-center">
                            <i class="fas fa-database text-blue-500 mr-2"></i> Database
                        </h4>
                        <span
                            class="px-2 py-1 text-xs rounded-full {{ $health['database']['status'] === 'healthy' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($health['database']['status']) }}
                        </span>
                    </div>
                    <div class="p-4 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Response Time</span>
                            <span
                                class="font-medium text-gray-900">{{ $health['database']['response_time'] ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Connections</span>
                            <span
                                class="font-medium text-gray-900">{{ $health['database']['connections'] ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Users</span>
                            <span
                                class="font-medium text-gray-900">{{ number_format($health['database']['user_count'] ?? 0) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Links</span>
                            <span
                                class="font-medium text-gray-900">{{ number_format($health['database']['link_count'] ?? 0) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Cache Health -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                        <h4 class="font-medium text-gray-900 flex items-center">
                            <i class="fas fa-bolt text-yellow-500 mr-2"></i> Cache
                        </h4>
                        <span
                            class="px-2 py-1 text-xs rounded-full {{ ($health['services']['cache']['status'] ?? '') === 'healthy' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ ucfirst($health['services']['cache']['status'] ?? 'Unknown') }}
                        </span>
                    </div>
                    <div class="p-4 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Driver</span>
                            <span
                                class="font-medium text-gray-900">{{ $health['services']['cache']['driver'] ?? 'N/A' }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Response Time</span>
                            <span
                                class="font-medium text-gray-900">{{ $health['services']['cache']['response_time'] ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Services -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div class="p-4 bg-gray-50 border-b border-gray-200 flex justify-between items-center">
                        <h4 class="font-medium text-gray-900 flex items-center">
                            <i class="fas fa-credit-card text-purple-500 mr-2"></i> Payments
                        </h4>
                        <span
                            class="px-2 py-1 text-xs rounded-full {{ ($health['services']['payment']['status'] ?? '') === 'healthy' ? 'bg-green-100 text-green-800' : ($health['services']['payment']['status'] ?? '' === 'warning' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ ucfirst($health['services']['payment']['status'] ?? 'Unknown') }}
                        </span>
                    </div>
                    <div class="p-4 space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Active Gateways</span>
                            <span
                                class="font-medium text-gray-900">{{ $health['services']['payment']['gateways_active'] ?? 0 }}
                                / {{ $health['services']['payment']['gateways_total'] ?? 0 }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-500">Pending Transactions</span>
                            <span
                                class="font-medium text-gray-900">{{ number_format($health['services']['payment']['transactions_pending'] ?? 0) }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Performance & Checks -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 h-full">
                <!-- Performance Metrics -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="p-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="font-medium text-gray-900">Performance Metrics</h3>
                    </div>
                    <div class="p-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-3 bg-gray-50 rounded-lg text-center">
                                <p class="text-xs text-gray-500 mb-1">Memory Usage</p>
                                <p class="text-lg font-bold text-gray-900">
                                    {{ $health['performance']['memory_usage'] ?? 'N/A' }}</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-lg text-center">
                                <p class="text-xs text-gray-500 mb-1">Peak Memory</p>
                                <p class="text-lg font-bold text-gray-900">
                                    {{ $health['performance']['peak_memory'] ?? 'N/A' }}</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-lg text-center">
                                <p class="text-xs text-gray-500 mb-1">Execution Time</p>
                                <p class="text-lg font-bold text-gray-900">
                                    {{ $health['performance']['execution_time'] ?? 'N/A' }}</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-lg text-center">
                                <p class="text-xs text-gray-500 mb-1">PHP Version</p>
                                <p class="text-lg font-bold text-gray-900">
                                    {{ $health['performance']['php_version'] ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Checks -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 h-full">
                    <div class="p-4 bg-gray-50 border-b border-gray-200">
                        <h3 class="font-medium text-gray-900">Environment Checks</h3>
                    </div>
                    <div class="divide-y divide-gray-100">
                        @foreach ($health['checks'] as $check => $status)
                            <div class="flex items-center justify-between p-4 hover:bg-gray-50 transition-colors">
                                <span class="text-sm font-medium text-gray-700 capitalize">
                                    {{ str_replace('_', ' ', $check) }}
                                </span>
                                @if ($status)
                                    <span class="text-green-600"><i class="fas fa-check-circle"></i> Pass</span>
                                @else
                                    <span class="text-red-600"><i class="fas fa-times-circle"></i> Fail</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
