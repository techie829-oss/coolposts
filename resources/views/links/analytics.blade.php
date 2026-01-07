<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Link Analytics') }}
                </h2>
                <p class="text-sm text-gray-600 mt-1">{{ $link->title ?: $link->original_url }}</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('links.show', $link) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-eye mr-2"></i>View Link
                </a>
                <a href="{{ route('links.edit', $link) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-edit mr-2"></i>Edit Link
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-mouse-pointer text-3xl text-blue-600"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Total Clicks</div>
                                <div class="text-2xl font-semibold text-gray-900">{{ number_format($stats['total_clicks']) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-users text-3xl text-green-600"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Unique Clicks</div>
                                <div class="text-2xl font-semibold text-gray-900">{{ number_format($stats['unique_clicks']) }}</div>
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
                                <div class="text-2xl font-semibold text-gray-900">{{ $link->currency === 'INR' ? '₹' : '$' }}{{ number_format($stats['total_earnings'], 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-calendar-day text-3xl text-purple-600"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Today's Clicks</div>
                                <div class="text-2xl font-semibold text-gray-900">{{ number_format($stats['today_clicks']) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Clicks -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Clicks</h3>
                    @if($link->clicks->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Country</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Device</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Earnings</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($link->clicks as $click)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $click->created_at->format('M d, Y H:i:s') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $click->ip_address }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $click->country ?: 'Unknown' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $click->device_type ?: 'Unknown' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($click->earnings > 0)
                                                    <span class="text-green-600 font-medium">
                                                        {{ $link->currency === 'INR' ? '₹' : '$' }}{{ number_format($click->earnings, 4) }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">-</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-chart-line text-4xl text-gray-400 mb-4"></i>
                            <p class="text-gray-500 text-lg">No clicks recorded yet</p>
                            <p class="text-gray-400 text-sm">Start sharing your link to see analytics data</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
