<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">Manage Links</h1>
            <a href="{{ route('links.create') }}"
                class="flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                <i class="fas fa-plus mr-2"></i>
                Create New Link
            </a>
        </div>

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            @if ($links->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr
                                class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-500 font-semibold">
                                <th class="px-6 py-4">Title & URL</th>
                                <th class="px-6 py-4 text-center">Stats</th>
                                <th class="px-6 py-4 text-center">Earnings</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-center">Created</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($links as $link)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900 mb-1">{{ $link->title }}</div>
                                        <div class="flex items-center text-sm text-gray-500">
                                            <span class="truncate max-w-xs block">{{ $link->short_url }}</span>
                                            <button onclick="copyToClipboard('{{ $link->short_url }}')"
                                                class="ml-2 text-gray-400 hover:text-purple-600 transition"
                                                title="Copy">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="text-sm font-medium text-gray-900">{{ $link->clicks_count }}</div>
                                        <div class="text-xs text-gray-500">Clicks</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($link->is_monetized)
                                            <div class="text-sm font-medium text-green-600">
                                                {{ $link->currency === 'INR' ? '₹' : '$' }}{{ number_format($link->total_earnings ?? 0, 2) }}
                                            </div>
                                        @else
                                            <span class="text-xs text-gray-400">Not Monetized</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($link->is_active)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Active
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-500">
                                        {{ $link->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('links.analytics', $link) }}"
                                                class="p-2 text-gray-400 hover:text-blue-600 transition"
                                                title="Analytics">
                                                <i class="fas fa-chart-bar"></i>
                                            </a>
                                            <a href="{{ route('links.edit', $link) }}"
                                                class="p-2 text-gray-400 hover:text-green-600 transition"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('links.destroy', $link) }}" method="POST"
                                                class="inline" onsubmit="return confirm('Are you sure?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="p-2 text-gray-400 hover:text-red-600 transition"
                                                    title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $links->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-link text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No links found</h3>
                    <p class="text-gray-500 mb-6">Create your first link to get started.</p>
                    <a href="{{ route('links.create') }}"
                        class="inline-flex items-center px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                        Create Link
                    </a>
                </div>
            @endif
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Optional toast or feedback
                alert('Copied to clipboard!'); // Replace with better UI if available
            });
        }
    </script>
</x-app-layout>
