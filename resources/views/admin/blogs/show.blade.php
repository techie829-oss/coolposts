<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Blog Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="mb-6 flex justify-between items-center">
                <a href="{{ route('admin.blogs.index') }}"
                    class="text-indigo-600 hover:text-indigo-900 flex items-center">
                    <i class="fas fa-arrow-left mr-2"></i> Back to List
                </a>

                <div class="flex space-x-3">
                    <a href="{{ route('blog.show', $blogPost->slug) }}" target="_blank"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 flex items-center">
                        <i class="fas fa-external-link-alt mr-2"></i> View Public
                    </a>

                    <form method="POST" action="{{ route('admin.blogs.destroy', $blogPost->id) }}"
                        onsubmit="return confirm('Are you sure you want to delete this post?');" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 flex items-center">
                            <i class="fas fa-trash-alt mr-2"></i> Delete
                        </button>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <!-- Meta Info Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8 border-b pb-8">
                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Author</h3>
                            <div class="mt-1 flex items-center">
                                <span
                                    class="bg-gray-100 rounded-full h-8 w-8 flex items-center justify-center text-gray-600 font-bold mr-2">
                                    {{ substr($blogPost->user->name, 0, 1) }}
                                </span>
                                <div>
                                    <div class="text-sm font-medium text-gray-900">{{ $blogPost->user->name }}</div>
                                    <div class="text-xs text-gray-500">{{ $blogPost->user->email }}</div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Status</h3>
                            <div class="mt-1 flex items-center justify-between">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $blogPost->status === 'published'
                                        ? 'bg-green-100 text-green-800'
                                        : ($blogPost->status === 'draft'
                                            ? 'bg-gray-100 text-gray-800'
                                            : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ ucfirst($blogPost->status) }}
                                </span>

                                <form method="POST" action="{{ route('admin.blogs.update-status', $blogPost->id) }}"
                                    class="flex items-center ml-2">
                                    @csrf
                                    @method('PATCH')
                                    <div class="relative mr-2">
                                        <select name="status"
                                            class="pl-4 pr-10 py-1.5 bg-gray-50/50 border border-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 focus:bg-white transition-all appearance-none cursor-pointer text-xs font-bold uppercase tracking-wider text-gray-700"
                                            required>
                                            <option value="draft"
                                                {{ $blogPost->status === 'draft' ? 'selected' : '' }}>
                                                Draft</option>
                                            <option value="published"
                                                {{ $blogPost->status === 'published' ? 'selected' : '' }}>Published
                                            </option>
                                            <option value="scheduled"
                                                {{ $blogPost->status === 'scheduled' ? 'selected' : '' }}>Scheduled
                                            </option>
                                            <option value="archived"
                                                {{ $blogPost->status === 'archived' ? 'selected' : '' }}>Archived
                                            </option>
                                        </select>
                                        <div
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-gray-400">
                                            <i class="fas fa-chevron-down text-[10px]"></i>
                                        </div>
                                    </div>
                                    <button type="submit"
                                        class="text-xs px-3 py-1.5 bg-purple-50 text-purple-700 font-bold uppercase tracking-wider rounded-lg hover:bg-purple-100 transition-colors">Update</button>
                                </form>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-sm font-medium text-gray-500">Metrics</h3>
                            <div class="mt-1 grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-xs text-gray-500">Views</span>
                                    <div class="text-sm font-bold">{{ number_format($blogPost->views) }}</div>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500">Published</span>
                                    <div class="text-sm">
                                        {{ $blogPost->published_at ? $blogPost->published_at->format('M d, Y') : 'Not published' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="space-y-6">
                        @if ($blogPost->featured_image)
                            <div class="w-full h-64 overflow-hidden rounded-lg mb-6">
                                <img src="{{ asset('storage/' . $blogPost->featured_image) }}" alt="Featured Image"
                                    class="w-full h-full object-cover">
                            </div>
                        @endif

                        <h1 class="text-3xl font-bold text-gray-900">{{ $blogPost->title }}</h1>

                        <div class="prose max-w-none text-gray-700">
                            <!-- Using a simplified render here. In production, ensure markdown is parsed or HTML is sanitized -->
                            @if ($blogPost->content_type === 'markdown')
                                {!! \Illuminate\Support\Str::markdown($blogPost->content) !!}
                            @else
                                {!! $blogPost->content !!}
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
