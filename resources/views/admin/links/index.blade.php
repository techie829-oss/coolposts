<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manage All Links') }}
            </h2>
            <a href="{{ route('admin.dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search and Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.links.index') }}" class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-64">
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Search by title, URL, or user..."
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="flex gap-2">
                            <select name="ad_type" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Ad Types</option>
                                <option value="no_ads" {{ request('ad_type') === 'no_ads' ? 'selected' : '' }}>No Ads</option>
                                <option value="short_ads" {{ request('ad_type') === 'short_ads' ? 'selected' : '' }}>Short Ads</option>
                                <option value="long_ads" {{ request('ad_type') === 'long_ads' ? 'selected' : '' }}>Long Ads</option>
                            </select>
                            <select name="status" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Status</option>
                                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md">
                                <i class="fas fa-search mr-2"></i>Search
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Links Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($links->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Link</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Owner</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ad Type</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Earnings</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Clicks</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($links as $link)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div class="h-10 w-10 rounded-full bg-gradient-to-r from-green-500 to-blue-600 flex items-center justify-center text-white">
                                                            <i class="fas fa-link"></i>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ Str::limit($link->title, 30) }}</div>
                                                        <div class="text-sm text-gray-500">{{ Str::limit($link->short_url, 40) }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $link->user->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $link->user->email }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $link->ad_type === 'no_ads' ? 'bg-gray-100 text-gray-800' :
                                                       ($link->ad_type === 'short_ads' ? 'bg-blue-100 text-blue-800' : 'bg-purple-100 text-purple-800') }}">
                                                    {{ $link->getAdTypeDisplayName() }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($link->currency === 'USD')
                                                    ${{ number_format($link->earnings_per_click_usd, 4) }}
                                                @else
                                                    ₹{{ number_format($link->earnings_per_click_inr, 4) }}
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <div class="text-sm text-gray-900">{{ $link->clicks_count ?? 0 }}</div>
                                                <div class="text-sm text-gray-500">{{ $link->unique_clicks_count ?? 0 }} unique</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $link->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $link->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $link->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('admin.links.show', $link) }}"
                                                       class="text-blue-600 hover:text-blue-900">
                                                        <i class="fas fa-eye mr-1"></i>View
                                                    </a>
                                                    <button onclick="toggleLinkStatus({{ $link->id }})"
                                                            class="text-yellow-600 hover:text-yellow-900">
                                                        <i class="fas fa-toggle-on mr-1"></i>Toggle
                                                    </button>
                                                    <a href="{{ route('links.analytics', $link) }}"
                                                       class="text-green-600 hover:text-green-900">
                                                        <i class="fas fa-chart-line mr-1"></i>Analytics
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $links->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-link text-6xl text-gray-300 mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No links found</h3>
                            <p class="text-gray-500">No links match your search criteria.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleLinkStatus(linkId) {
            if (confirm('Are you sure you want to toggle this link\'s status?')) {
                fetch(`/admin/links/${linkId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                }).then(() => {
                    window.location.reload();
                });
            }
        }
    </script>
</x-app-layout>
