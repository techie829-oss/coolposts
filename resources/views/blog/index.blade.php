<x-app-layout>
    <x-slot name="title">Blog Posts - CoolPosts | Tech Articles & Community Content</x-slot>

    <x-slot name="head">
        <meta name="description" content="Discover amazing tech articles, tutorials, and community content on CoolPosts. Read the latest insights on web development, technology trends, and digital innovation.">
        <meta name="robots" content="index, follow">
        <link rel="canonical" href="{{ route('blog.index') }}">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ route('blog.index') }}">
        <meta property="og:title" content="Blog Posts - CoolPosts | Tech Articles & Community Content">
        <meta property="og:description" content="Discover amazing tech articles, tutorials, and community content on CoolPosts. Read the latest insights on web development, technology trends, and digital innovation.">
        <meta property="og:image" content="{{ asset('images/og-blog.jpg') }}">
        <meta property="og:site_name" content="CoolPosts">
        <meta property="og:locale" content="en_US">

        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="{{ route('blog.index') }}">
        <meta property="twitter:title" content="Blog Posts - CoolPosts | Tech Articles & Community Content">
        <meta property="twitter:description" content="Discover amazing tech articles, tutorials, and community content on CoolPosts. Read the latest insights on web development, technology trends, and digital innovation.">
        <meta property="twitter:image" content="{{ asset('images/og-blog.jpg') }}">
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header with Buttons -->
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 mb-2">Blog Posts</h1>
                    <p class="text-gray-600">Discover amazing content from our community</p>
                </div>
                @auth
                    <div class="flex space-x-4">
                        <a href="{{ route('blog.templates') }}"
                            class="bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 text-white font-bold py-3 px-6 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-file-alt mr-2"></i>Use Templates
                        </a>
                        <a href="{{ route('blog.create') }}"
                            class="bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold py-3 px-6 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                            <i class="fas fa-plus mr-2"></i>Create Post
                        </a>
                    </div>
                @else
                    <div class="flex space-x-3">
                        <a href="{{ route('blog.templates') }}"
                            class="bg-gradient-to-r from-green-600 to-teal-600 hover:from-green-700 hover:to-teal-700 text-white font-bold py-3 px-6 rounded-full shadow-lg hover:shadow-xl transition-all duration-300">
                            <i class="fas fa-file-alt mr-2"></i>Browse Templates
                        </a>
                        <a href="{{ route('login') }}"
                            class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-3 px-6 rounded-full shadow-lg hover:shadow-xl transition-all duration-300">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </a>
                        <a href="{{ route('register') }}"
                            class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-3 px-6 rounded-full shadow-lg hover:shadow-xl transition-all duration-300">
                            <i class="fas fa-user-plus mr-2"></i>Register
                        </a>
                    </div>
                @endauth
            </div>

            <!-- Search and Filters -->
            <div
                class="bg-white/80 backdrop-blur-sm overflow-hidden shadow-xl sm:rounded-2xl mb-8 border border-white/20">
                <div class="p-8">
                    <form method="GET" action="{{ route('blog.index') }}" class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                            <!-- Search -->
                            <div>
                                <label for="search" class="block text-sm font-semibold text-gray-700 mb-2">Search
                                    Posts</label>
                                <div class="relative">
                                    <i
                                        class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 z-10"></i>
                                    <input type="text" name="search" id="search" value="{{ request('search') }}"
                                        class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300"
                                        placeholder="Search posts...">
                                </div>
                            </div>

                            <!-- Type Filter -->
                            <div>
                                <label for="type"
                                    class="block text-sm font-semibold text-gray-700 mb-2">Type</label>
                                <select name="type" id="type"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                                    <option value="">All Types</option>
                                    @foreach ($types as $key => $type)
                                        <option value="{{ $key }}"
                                            {{ request('type') == $key ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Category Filter -->
                            <div>
                                <label for="category"
                                    class="block text-sm font-semibold text-gray-700 mb-2">Category</label>
                                <select name="category" id="category"
                                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                                    <option value="">All Categories</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category }}"
                                            {{ request('category') == $category ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Filter Buttons -->
                            <div class="flex items-end space-x-3">
                                <button type="submit"
                                    class="flex-1 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                                    <i class="fas fa-filter mr-2"></i>Apply Filters
                                </button>
                                @if (request('search') || request('type') || request('category'))
                                    <a href="{{ route('blog.index') }}"
                                        class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-4 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Quick Category/Tag Filters -->
            <div class="mb-8">
                <div class="flex flex-wrap gap-3">
                    <!-- Popular Categories -->
                    @foreach ($categories as $category)
                        <a href="{{ route('blog.index', ['category' => $category]) }}"
                            class="px-4 py-2 bg-white/80 backdrop-blur-sm border border-gray-200 rounded-full text-sm font-medium text-gray-700 hover:bg-purple-50 hover:border-purple-200 hover:text-purple-700 transition-all duration-300">
                            {{ $category }}
                        </a>
                    @endforeach

                    <!-- Popular Tags -->
                    @php
                        $popularTags = [
                            'javascript',
                            'css',
                            'php',
                            'laravel',
                            'web-development',
                            'tutorial',
                            'guide',
                            'tips',
                            'performance',
                            'optimization',
                        ];
                    @endphp
                    @foreach ($popularTags as $tag)
                        <a href="{{ route('blog.index', ['search' => $tag]) }}"
                            class="px-4 py-2 bg-purple-50 border border-purple-200 rounded-full text-sm font-medium text-purple-700 hover:bg-purple-100 hover:border-purple-300 transition-all duration-300">
                            #{{ $tag }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Blog Posts Grid -->
            @if ($posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($posts as $post)
                        <div class="group bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex flex-col h-full">
                            <!-- Featured Image -->
                            <div class="relative h-48 overflow-hidden">
                                @if ($post->featured_image)
                                    <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}"
                                        class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-indigo-50 to-purple-50 flex items-center justify-center group-hover:from-indigo-100 group-hover:to-purple-100 transition-colors duration-300">
                                        <i class="fas fa-newspaper text-4xl text-indigo-200 group-hover:text-indigo-300 transition-colors duration-300"></i>
                                    </div>
                                @endif
                                
                                <!-- Category Badge -->
                                <div class="absolute top-4 left-4">
                                    <span class="px-3 py-1 bg-white/95 backdrop-blur-sm text-xs font-bold text-slate-800 rounded-full shadow-sm border border-white/20">
                                        {{ $post->category ?? 'Article' }}
                                    </span>
                                </div>
                            </div>

                            <div class="p-6 flex-1 flex flex-col">
                                <!-- Post Meta -->
                                <div class="flex items-center text-xs text-slate-500 mb-3 space-x-2">
                                    <span><i class="far fa-calendar-alt mr-1"></i> {{ $post->published_at->format('M d, Y') }}</span>
                                    <span>&bull;</span>
                                    <span><i class="far fa-clock mr-1"></i> {{ ceil(str_word_count(strip_tags($post->content)) / 200) }} min read</span>
                                </div>

                                <!-- Title -->
                                <h3 class="text-xl font-bold text-slate-900 mb-3 line-clamp-2 group-hover:text-indigo-600 transition-colors duration-200">
                                    <a href="{{ route('blog.show', $post->slug) }}">
                                        {{ $post->title }}
                                    </a>
                                </h3>

                                <!-- Excerpt -->
                                <p class="text-slate-600 mb-6 line-clamp-3 text-sm flex-1 leading-relaxed">
                                    {{ $post->excerpt }}
                                </p>

                                <!-- Footer -->
                                <div class="flex items-center justify-between pt-4 border-t border-slate-100 mt-auto">
                                    <div class="flex items-center space-x-2">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-r from-purple-500 to-indigo-500 flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                            {{ substr($post->user->name, 0, 1) }}
                                        </div>
                                        <span class="text-xs font-medium text-slate-700">{{ $post->user->name }}</span>
                                    </div>
                                    <a href="{{ route('blog.show', $post->slug) }}" class="text-indigo-600 hover:text-indigo-700 font-semibold text-sm inline-flex items-center group-hover:translate-x-1 transition-transform duration-200">
                                        Read Article <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12">
                    {{ $posts->links() }}
                </div>
            @else
                <!-- No Posts Found -->
                <div
                    class="bg-white/80 backdrop-blur-sm overflow-hidden shadow-xl sm:rounded-2xl border border-white/20">
                    <div class="p-16 text-center">
                        <div
                            class="w-24 h-24 bg-gradient-to-r from-purple-100 to-blue-100 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-newspaper text-4xl text-purple-500"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-800 mb-3">No blog posts found</h3>
                        <p class="text-gray-600 mb-8 max-w-md mx-auto">
                            @if (request('search') || request('type') || request('category'))
                                Try adjusting your search criteria or filters to find what you're looking for.
                            @else
                                Be the first to create an amazing blog post and share your knowledge with the community!
                            @endif
                        </p>
                        @auth
                            <a href="{{ route('blog.create') }}"
                                class="bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold py-4 px-8 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 transform hover:scale-105">
                                <i class="fas fa-plus mr-2"></i>Create Your First Post
                            </a>
                        @else
                            <div class="space-y-4">
                                <p class="text-sm text-gray-600 font-medium">Want to create blog posts?</p>
                                <div class="flex space-x-4 justify-center">
                                    <a href="{{ route('login') }}"
                                        class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-3 px-6 rounded-full shadow-lg hover:shadow-xl transition-all duration-300">
                                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                                    </a>
                                    <a href="{{ route('register') }}"
                                        class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-3 px-6 rounded-full shadow-lg hover:shadow-xl transition-all duration-300">
                                        <i class="fas fa-user-plus mr-2"></i>Register
                                    </a>
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
