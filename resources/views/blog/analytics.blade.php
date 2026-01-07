<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Blog Analytics') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Overview Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-eye text-3xl text-blue-600"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Views</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ number_format($totalViews) }}</p>
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
                                <p class="text-sm font-medium text-gray-500">Total Visitors</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ number_format($totalVisitors) }}</p>
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
                                <p class="text-sm font-medium text-gray-500">Total Earnings</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ number_format($totalEarnings, 4) }}</p>
                                <p class="text-sm text-gray-500">{{ auth()->user()->preferred_currency ?? 'INR' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Blog Posts Performance -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-6">Blog Posts Performance</h3>

                    @if($posts->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Post
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Views
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Visitors
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Avg Time
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Earnings
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($posts as $post)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    @if($post->featured_image)
                                                        <img src="{{ Storage::url($post->featured_image) }}"
                                                             alt="{{ $post->title }}"
                                                             class="w-12 h-12 object-cover rounded">
                                                    @else
                                                        <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
                                                            <i class="fas fa-image text-gray-400"></i>
                                                        </div>
                                                    @endif
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            <a href="{{ route('blog.show', $post->slug) }}" class="hover:text-blue-600">
                                                                {{ $post->title }}
                                                            </a>
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $post->getTypeDisplayName() }}
                                                            @if($post->category)
                                                                • {{ $post->category }}
                                                            @endif
                                                        </div>
                                                        <div class="text-xs text-gray-400">
                                                            {{ $post->published_at ? $post->published_at->format('M j, Y') : 'Draft' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($post->status === 'published')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Published
                                                    </span>
                                                @elseif($post->status === 'draft')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        Draft
                                                    </span>
                                                @elseif($post->status === 'scheduled')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        Scheduled
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                        Archived
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ number_format($post->views) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ number_format($post->unique_visitors) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($post->avg_time_spent > 0)
                                                    {{ gmdate('i:s', $post->avg_time_spent) }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                @if($post->is_monetized)
                                                    <span class="text-green-600 font-medium">
                                                        {{ number_format($post->total_earnings, 4) }}
                                                    </span>
                                                @else
                                                    <span class="text-gray-400">Not monetized</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('blog.show', $post->slug) }}"
                                                       class="text-blue-600 hover:text-blue-900">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('blog.edit', $post) }}"
                                                       class="text-indigo-600 hover:text-indigo-900">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="{{ route('blog.post-analytics', $post) }}"
                                                       class="text-green-600 hover:text-green-900">
                                                        <i class="fas fa-chart-bar"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-newspaper text-6xl text-gray-300 mb-4"></i>
                            <h3 class="text-xl font-semibold text-gray-700 mb-2">No blog posts yet</h3>
                            <p class="text-gray-500 mb-6">Start creating blog posts to see analytics and earn money!</p>
                            <a href="{{ route('blog.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg">
                                <i class="fas fa-plus mr-2"></i>Create Your First Post
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-8 flex justify-center">
                <a href="{{ route('blog.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg mr-4">
                    <i class="fas fa-plus mr-2"></i>Create New Post
                </a>
                <a href="{{ route('blog.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg">
                    <i class="fas fa-list mr-2"></i>View All Posts
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
