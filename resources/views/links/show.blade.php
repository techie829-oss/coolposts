<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Link Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-eye mr-2 text-blue-600"></i>Link Details
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-1">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">Basic Information</h4>

                            <div class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Title</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $link->title }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1">
                                        @if($link->is_protected)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-lock mr-1"></i>Protected
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-unlock mr-1"></i>Public
                                            </span>
                                        @endif
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Created</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $link->created_at->format('F d, Y \a\t g:i A') }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $link->updated_at->format('F d, Y \a\t g:i A') }}</dd>
                                </div>

                                @if(Auth::user()->isAdmin())
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Created by</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $link->user->name }} ({{ $link->user->email }})</dd>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="md:col-span-2">
                            <h4 class="text-lg font-medium text-gray-900 mb-4">URLs & Settings</h4>

                            <div class="space-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 mb-2">Original URL</dt>
                                    <dd class="mt-1">
                                        <a href="{{ $link->original_url }}" target="_blank" class="text-blue-600 hover:text-blue-800 break-all">
                                            {{ $link->original_url }}
                                        </a>
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500 mb-2">Short URL</dt>
                                    <dd class="mt-1">
                                        <div class="flex">
                                            <input type="text" class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md bg-gray-50 text-gray-500"
                                                   value="{{ $link->short_url }}" readonly>
                                            <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white border border-blue-600 rounded-r-md transition-colors duration-200"
                                                    type="button" onclick="copyToClipboard('{{ $link->short_url }}')">
                                                <i class="fas fa-copy"></i> Copy
                                            </button>
                                        </div>
                                    </dd>
                                </div>

                                @if($link->is_monetized && $globalSettings->isEarningsEnabled())
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 mb-2">Monetization</dt>
                                        <dd class="mt-1 space-y-2">
                                            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                                <span class="text-sm text-gray-600">Earnings per click:</span>
                                                <span class="text-lg font-semibold text-green-600">{{ $link->currency === 'INR' ? '₹' : '$' }}{{ number_format($link->earnings_per_click, 4) }}</span>
                                            </div>
                                            @if($link->daily_click_limit)
                                                <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg">
                                                    <span class="text-sm text-gray-600">Daily click limit:</span>
                                                    <span class="text-lg font-semibold text-blue-600">{{ $link->daily_click_limit }}</span>
                                                </div>
                                            @endif
                                            @if($link->category)
                                                <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                                                    <span class="text-sm text-gray-600">Category:</span>
                                                    <span class="text-lg font-semibold text-purple-600">{{ $link->category }}</span>
                                                </div>
                                            @endif
                                        </dd>
                                    </div>
                                @endif

                                @if($link->description)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 mb-2">Description</dt>
                                        <dd class="mt-1 text-sm text-gray-900 p-3 bg-gray-50 rounded-lg">{{ $link->description }}</dd>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($globalSettings->isLinkCreationEnabled())
                    <div class="mt-8 pt-6 border-t border-gray-200">
                        <div class="flex flex-col sm:flex-row gap-3">
                            <a href="{{ route('links.edit', $link) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition-colors duration-200 inline-flex items-center justify-center">
                                <i class="fas fa-edit mr-2"></i>Edit Link
                            </a>
                            <a href="{{ route('links.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-md transition-colors duration-200 inline-flex items-center justify-center">
                                <i class="fas fa-arrow-left mr-2"></i>Back to Links
                            </a>
                            <form action="{{ route('links.destroy', $link) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-100 hover:bg-red-200 text-red-800 font-bold py-2 px-4 rounded-md transition-colors duration-200 inline-flex items-center justify-center"
                                        onclick="return confirm('Are you sure you want to delete this link?')">
                                    <i class="fas fa-trash mr-2"></i>Delete Link
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            const button = event.target.closest('button');
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check"></i> Copied!';
            button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
            button.classList.add('bg-green-500', 'hover:bg-green-600');

            setTimeout(() => {
                button.innerHTML = originalHTML;
                button.classList.remove('bg-green-500', 'hover:bg-green-600');
                button.classList.add('bg-blue-600', 'hover:bg-blue-700');
            }, 2000);
        });
    }
    </script>
</x-app-layout>
