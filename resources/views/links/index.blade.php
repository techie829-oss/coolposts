<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                @if(Auth::user()->isAdmin())
                    All Links
                @else
                    My Links
                @endif
            </h2>
            @if($globalSettings->isLinkCreationEnabled())
            <a href="{{ route('links.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Create New Link
            </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($links->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($links as $link)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow">
                            <div class="p-6">
                                <!-- Link Header -->
                                <div class="flex justify-between items-start mb-3">
                                    <h3 class="text-lg font-semibold text-gray-900 truncate">{{ $link->title }}</h3>
                                    <div class="flex space-x-2">
                                        @if($link->is_protected)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                                </svg>
                                                Protected
                                            </span>
                                        @endif
                                        @if(!$link->is_active)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Inactive
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Link Description -->
                                @if($link->description)
                                    <p class="text-gray-600 text-sm mb-3">{{ Str::limit($link->description, 80) }}</p>
                                @endif

                                <!-- Original URL -->
                                <p class="text-gray-500 text-xs mb-3 truncate">
                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                                    </svg>
                                    {{ Str::limit($link->original_url, 50) }}
                                </p>

                                <!-- Short URL -->
                                <div class="mb-3">
                                    <label class="block text-xs font-medium text-gray-700 mb-1">Short URL:</label>
                                    <div class="flex">
                                        <input type="text" class="flex-1 text-xs border-gray-300 rounded-l-md"
                                               value="{{ $link->short_url }}" readonly>
                                        <button class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs rounded-r-md border-l-0"
                                                onclick="copyToClipboard('{{ $link->short_url }}')">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                @if($globalSettings->isEarningsEnabled())
                                <!-- Monetization Stats -->
                                <div class="grid grid-cols-2 gap-3 mb-4">
                                    @if($link->is_monetized)
                                        <div class="text-center p-2 bg-green-50 rounded">
                                            <p class="text-lg font-bold text-green-600">
                                                {{ $link->currency === 'INR' ? '₹' : '$' }}{{ number_format($link->earnings_per_click ?? 0, 4) }}
                                            </p>
                                            <p class="text-xs text-green-700">Per Click Earnings</p>
                                        </div>
                                    @else
                                        <div class="text-center p-2 bg-gray-50 rounded">
                                            <p class="text-lg font-bold text-gray-500">
                                                Not Monetized
                                            </p>
                                            <p class="text-xs text-gray-600">Per Click Earnings</p>
                                        </div>
                                    @endif
                                    <div class="text-center p-2 bg-blue-50 rounded">
                                        <p class="text-lg font-bold text-blue-600">{{ $link->unique_clicks_count ?? 0 }}</p>
                                        <p class="text-xs text-blue-700">Unique Clicks</p>
                                    </div>
                                </div>

                                <!-- Total Stats -->
                                <div class="grid grid-cols-2 gap-3 mb-4">
                                    @if($link->is_monetized)
                                        <div class="text-center p-2 bg-yellow-50 rounded">
                                            <p class="text-lg font-bold text-yellow-600">
                                                {{ $link->currency === 'INR' ? '₹' : '$' }}{{ number_format($link->total_earnings ?? 0, 2) }}
                                            </p>
                                            <p class="text-xs text-yellow-700">Total Earnings</p>
                                        </div>
                                    @else
                                        <div class="text-center p-2 bg-gray-50 rounded">
                                            <p class="text-lg font-bold text-gray-500">
                                                --
                                            </p>
                                            <p class="text-xs text-gray-600">Total Earnings</p>
                                        </div>
                                    @endif
                                    <div class="text-center p-2 bg-purple-50 rounded">
                                        <p class="text-lg font-bold text-purple-600">{{ $link->clicks_count ?? 0 }}</p>
                                        <p class="text-xs text-purple-700">Total Clicks</p>
                                    </div>
                                </div>
                                @else
                                <!-- Click Stats Only (when earnings disabled) -->
                                <div class="grid grid-cols-2 gap-3 mb-4">
                                    <div class="text-center p-2 bg-blue-50 rounded">
                                        <p class="text-lg font-bold text-blue-600">{{ $link->unique_clicks_count ?? 0 }}</p>
                                        <p class="text-xs text-blue-700">Unique Clicks</p>
                                    </div>
                                    <div class="text-center p-2 bg-purple-50 rounded">
                                        <p class="text-lg font-bold text-purple-600">{{ $link->clicks_count ?? 0 }}</p>
                                        <p class="text-xs text-purple-700">Total Clicks</p>
                                    </div>
                                </div>
                                @endif

                                <!-- Category & Status -->
                                <div class="flex justify-between items-center mb-4 text-xs text-gray-500">
                                    @if($link->category)
                                        <span class="bg-gray-100 px-2 py-1 rounded">{{ $link->category }}</span>
                                    @endif
                                    <span>{{ $link->created_at->format('M d, Y') }}</span>
                                </div>

                                <!-- Admin Info -->
                                @if(Auth::user()->isAdmin())
                                    <div class="mb-4 p-2 bg-gray-50 rounded text-xs">
                                        <p class="text-gray-600">
                                            <span class="font-medium">Created by:</span> {{ $link->user->name }}
                                        </p>
                                    </div>
                                @endif

                                <!-- Action Buttons -->
                                <div class="flex flex-wrap gap-2">
                                    @if($globalSettings->isLinkCreationEnabled())
                                    <a href="{{ route('links.edit', $link) }}" class="inline-flex items-center px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded-md">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Edit
                                    </a>

                                    <form action="{{ route('links.toggle-status', $link) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-3 py-2 {{ $link->is_active ? 'bg-orange-600 hover:bg-orange-700' : 'bg-green-600 hover:bg-green-700' }} text-white text-xs rounded-md">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            {{ $link->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                    </form>
                                    @endif

                                    <a href="{{ route('links.show', $link) }}" class="inline-flex items-center px-3 py-2 bg-gray-600 hover:bg-gray-700 text-white text-xs rounded-md">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        View
                                    </a>

                                    @if($link->clicks_count > 0)
                                        <a href="{{ route('links.analytics', $link) }}" class="inline-flex items-center px-3 py-2 bg-green-600 hover:bg-green-700 text-white text-xs rounded-md">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                            </svg>
                                            Analytics
                                        </a>
                                    @endif

                                    @if($globalSettings->isLinkCreationEnabled())
                                    <form action="{{ route('links.destroy', $link) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-3 py-2 bg-red-600 hover:bg-red-700 text-white text-xs rounded-md"
                                                onclick="return confirm('Are you sure you want to delete this link?')">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $links->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No links found</h3>
                    <p class="mt-1 text-sm text-gray-500">Get started by creating your first monetized link!</p>
                    <div class="mt-6">
                        <a href="{{ route('links.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Create Your First Link
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            // Show temporary success message
            const button = event.target.closest('button');
            const originalHTML = button.innerHTML;
            button.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
            button.classList.remove('bg-gray-100', 'hover:bg-gray-200', 'text-gray-700');
            button.classList.add('bg-green-100', 'text-green-700');

            setTimeout(() => {
                button.innerHTML = originalHTML;
                button.classList.remove('bg-green-100', 'text-green-700');
                button.classList.add('bg-gray-100', 'hover:bg-gray-200', 'text-gray-700');
            }, 1000);
        });
    }
    </script>
</x-app-layout>
