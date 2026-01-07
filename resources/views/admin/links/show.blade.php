<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Link Details') }} - {{ $link->title ?: 'Untitled' }}
            </h2>
            <a href="{{ route('admin.links.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Links
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Link Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-link mr-2 text-blue-600"></i>Link Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">Basic Details</h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Title</label>
                                    <p class="text-sm text-gray-900">{{ $link->title ?: 'Untitled' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Short Code</label>
                                    <p class="text-sm text-gray-900">{{ $link->short_code }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Original URL</label>
                                    <a href="{{ $link->original_url }}" target="_blank" class="text-sm text-blue-600 hover:text-blue-800 break-all">
                                        {{ $link->original_url }}
                                    </a>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Status</label>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $link->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $link->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">Monetization Details</h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Ad Type</label>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $link->ad_type === 'no_ads' ? 'bg-gray-100 text-gray-800' :
                                           ($link->ad_type === 'short_ads' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800') }}">
                                        {{ $link->getAdTypeDisplayName() }}
                                    </span>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Ad Duration</label>
                                    <p class="text-sm text-gray-900">{{ $link->getAdDuration() }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Earnings per Click (INR)</label>
                                    <p class="text-sm text-gray-900">₹{{ number_format($link->earnings_per_click_inr, 4) }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Earnings per Click (USD)</label>
                                    <p class="text-sm text-gray-900">${{ number_format($link->earnings_per_click_usd, 4) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Created By</label>
                                <a href="{{ route('admin.users.show', $link->user) }}" class="text-sm text-blue-600 hover:text-blue-800">
                                    {{ $link->user->name }}
                                </a>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Created Date</label>
                                <p class="text-sm text-gray-900">{{ $link->created_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Last Updated</label>
                                <p class="text-sm text-gray-900">{{ $link->updated_at->format('M d, Y \a\t g:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Link Statistics -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-chart-bar mr-2 text-green-600"></i>Link Statistics
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $linkStats['total_clicks'] }}</div>
                            <div class="text-sm text-gray-600">Total Clicks</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $linkStats['unique_clicks'] }}</div>
                            <div class="text-sm text-gray-600">Unique Clicks</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-yellow-600">{{ number_format($linkStats['total_earnings'], 4) }}</div>
                            <div class="text-sm text-gray-600">Total Earnings</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600">{{ number_format($linkStats['pending_earnings'], 4) }}</div>
                            <div class="text-sm text-gray-600">Pending Earnings</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Clicks -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-mouse-pointer mr-2 text-purple-600"></i>Recent Clicks
                    </h3>
                </div>
                <div class="p-6">
                    @if($link->clicks->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User Agent</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Unique</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($link->clicks->take(10) as $click)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $click->ip_address }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <div class="max-w-xs truncate" title="{{ $click->user_agent }}">
                                                    {{ $click->user_agent }}
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $click->is_unique ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                    {{ $click->is_unique ? 'Yes' : 'No' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $click->created_at->format('M d, Y g:i A') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($link->clicks->count() > 10)
                            <div class="mt-4 text-center">
                                <p class="text-sm text-gray-500">Showing 10 of {{ $link->clicks->count() }} clicks</p>
                            </div>
                        @endif
                    @else
                        <p class="text-gray-500 text-center py-4">No clicks found for this link.</p>
                    @endif
                </div>
            </div>

            <!-- Recent Earnings -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-coins mr-2 text-yellow-600"></i>Recent Earnings
                    </h3>
                </div>
                <div class="p-6">
                    @if($link->earnings->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($link->earnings->take(10) as $earning)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <a href="{{ route('admin.users.show', $earning->user) }}" class="text-sm text-blue-600 hover:text-blue-800">
                                                    {{ $earning->user->name }}
                                                </a>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ number_format($earning->amount, 4) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $earning->status === 'approved' ? 'bg-green-100 text-green-800' :
                                                       ($earning->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                    {{ ucfirst($earning->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $earning->created_at->format('M d, Y g:i A') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($link->earnings->count() > 10)
                            <div class="mt-4 text-center">
                                <p class="text-sm text-gray-500">Showing 10 of {{ $link->earnings->count() }} earnings</p>
                            </div>
                        @endif
                    @else
                        <p class="text-gray-500 text-center py-4">No earnings found for this link.</p>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-cog mr-2 text-gray-600"></i>Actions
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex flex-wrap gap-4">
                        <!-- Toggle Status -->
                        <form method="POST" action="{{ route('admin.links.toggle-status', $link) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-toggle-{{ $link->is_active ? 'off' : 'on' }} mr-2"></i>
                                {{ $link->is_active ? 'Deactivate' : 'Activate' }} Link
                            </button>
                        </form>

                        <!-- View Analytics -->
                        <a href="{{ route('links.analytics', $link) }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-chart-line mr-2"></i>View Analytics
                        </a>

                        <!-- Test Link -->
                        <a href="{{ route('link.redirect', $link->short_code) }}" target="_blank" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-external-link-alt mr-2"></i>Test Link
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
