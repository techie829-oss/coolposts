<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3 text-xs font-medium text-gray-500">
                        <li class="inline-flex items-center">
                            <a href="{{ route('dashboard') }}" class="hover:text-purple-600 transition-colors">
                                <i class="fas fa-home mr-2"></i>Dashboard
                            </a>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2 text-[10px]"></i>
                                <span class="text-gray-900">Manage Posts</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h2 class="font-extrabold text-3xl text-gray-900 leading-tight tracking-tight">
                    {{ __('Management Studio') }}
                </h2>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('blog.templates') }}"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">
                    <i class="fas fa-layer-group mr-2 text-gray-400 text-xs"></i>
                    Templates
                </a>
                <a href="{{ route('blog.create') }}"
                    class="inline-flex items-center px-6 py-2.5 bg-brand-gradient text-white rounded-xl text-sm font-bold shadow-lg shadow-purple-200 hover:shadow-purple-300 hover:scale-[1.02] active:scale-[0.98] transition-all">
                    <i class="fas fa-plus mr-2"></i>
                    Write New Story
                </a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

        <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            @if ($posts->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr
                                class="bg-gray-50 border-b border-gray-100 text-xs uppercase text-gray-500 font-semibold">
                                <th class="px-6 py-4">Title</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-center">Views</th>
                                <th class="px-6 py-4 text-center">Category</th>
                                <th class="px-6 py-4 text-center">Date</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($posts as $post)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-6 py-4">
                                        <div class="font-medium text-gray-900 mb-1">
                                            <a href="{{ route('blog.show', $post->slug) }}"
                                                class="hover:text-purple-600 transition" target="_blank">
                                                {{ $post->title }}
                                            </a>
                                        </div>
                                        <div class="text-xs text-gray-500 truncate max-w-xs">{{ $post->excerpt }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($post->status === 'published')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Published
                                            </span>
                                        @elseif($post->status === 'draft')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Draft
                                            </span>
                                        @elseif($post->status === 'scheduled')
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Scheduled
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                {{ ucfirst($post->status) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="text-sm font-medium text-gray-900">{{ number_format($post->views) }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($post->category)
                                            <span
                                                class="inline-block bg-gray-100 px-2 py-1 rounded text-xs text-gray-600">
                                                {{ $post->category }}
                                            </span>
                                        @else
                                            <span class="text-xs text-gray-400">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-500">
                                        {{ $post->created_at->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            <a href="{{ route('blog.post-analytics', $post) }}"
                                                class="p-2 text-gray-400 hover:text-blue-600 transition"
                                                title="Analytics">
                                                <i class="fas fa-chart-line"></i>
                                            </a>
                                            <a href="{{ route('blog.edit', $post) }}"
                                                class="p-2 text-gray-400 hover:text-green-600 transition"
                                                title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('blog.destroy', $post) }}" method="POST"
                                                class="inline"
                                                onsubmit="return confirm('Are you sure you want to delete this post?');">
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
                    {{ $posts->links() }}
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-newspaper text-2xl text-gray-400"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No posts found</h3>
                    <p class="text-gray-500 mb-6">Start writing your first blog post today.</p>
                    <a href="{{ route('blog.create') }}"
                        class="inline-flex items-center px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                        Write Post
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
