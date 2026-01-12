<x-app-layout>
    <x-slot name="title">Explore Knowledge & Stories - CoolPosts</x-slot>

    <x-slot name="head">
        <meta name="description" content="Discover amazing tech articles, tutorials, and community content on CoolPosts.">
        <meta name="robots" content="index, follow">
        <link rel="canonical" href="{{ route('blog.index') }}">
    </x-slot>

    <div
        class="pt-32 pb-16 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-slate-50 via-white to-white min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Premium Hero Header -->
            <div class="relative mt-8 mb-12">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-8">
                    <div class="max-w-2xl">
                        <div class="flex items-center gap-2 mb-4">
                            <span
                                class="px-3 py-1 bg-purple-100 text-purple-700 text-[10px] font-bold uppercase tracking-widest rounded-full">Community
                                Hub</span>
                            <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                            <span class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Platform News &
                                Insights</span>
                        </div>
                        <h1 class="text-4xl lg:text-6xl font-extrabold text-gray-900 tracking-tight mb-6 leading-[1.1]">
                            Explore <span class="text-transparent bg-clip-text bg-brand-gradient inline-block"
                                style="-webkit-background-clip: text;">Knowledge</span> & Stories
                        </h1>
                        <p class="text-lg text-gray-600 leading-relaxed font-medium">
                            Discover the latest insights, tutorials, and success stories from our vibrant creator
                            community.
                        </p>
                    </div>
                    @auth
                        <div class="flex flex-wrap items-center gap-4">
                            <a href="{{ route('blog.templates') }}"
                                class="flex items-center gap-2.5 bg-white border border-gray-100 text-gray-700 font-bold py-3 px-6 rounded-2xl shadow-sm hover:shadow-md hover:border-purple-200 transition-all active:scale-95 group">
                                <div
                                    class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center text-green-600 group-hover:scale-110 transition-transform">
                                    <i class="fas fa-layer-group text-sm"></i>
                                </div>
                                <span>Use Templates</span>
                            </a>
                            <a href="{{ route('blog.create') }}"
                                class="flex items-center gap-2.5 bg-brand-gradient text-white font-bold py-3 px-7 rounded-2xl shadow-lg shadow-purple-200 hover:shadow-purple-300 hover:scale-[1.02] active:scale-95 transition-all">
                                <i class="fas fa-plus text-sm"></i>
                                <span>Create Post</span>
                            </a>
                        </div>
                    @else
                        <div class="flex flex-wrap items-center gap-3">
                            <a href="{{ route('blog.templates') }}"
                                class="flex items-center gap-2.5 bg-white border border-gray-100 text-gray-700 font-bold py-3 px-6 rounded-2xl shadow-sm hover:shadow-md transition-all active:scale-95">
                                <i class="fas fa-file-alt text-green-600"></i>
                                <span>Explore Templates</span>
                            </a>
                            <a href="{{ route('register') }}"
                                class="flex items-center gap-2.5 bg-brand-gradient text-white font-bold py-3 px-7 rounded-2xl shadow-lg shadow-purple-200 hover:shadow-purple-300 hover:scale-[1.02] active:scale-95 transition-all">
                                <span>Join Community</span>
                                <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    @endauth
                </div>
            </div>

            <!-- Sleek Filter Bar -->
            <div
                class="bg-white/70 backdrop-blur-xl border border-white/40 rounded-3xl shadow-xl shadow-slate-200/50 mb-10 overflow-hidden">
                <div class="p-6 lg:p-4">
                    <form method="GET" action="{{ route('blog.index') }}">
                        <div class="grid grid-cols-1 md:grid-cols-12 items-center gap-4">
                            <!-- Search -->
                            <div class="md:col-span-4 relative group">
                                <div
                                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-purple-500 transition-colors">
                                    <i class="fas fa-search text-sm"></i>
                                </div>
                                <input type="text" name="search" id="search" value="{{ request('search') }}"
                                    class="w-full pl-11 pr-4 py-3 bg-gray-50/50 border border-gray-100/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-purple-200 transition-all text-sm font-medium"
                                    placeholder="Search stories, topics, guides...">
                            </div>

                            <!-- Type Filter -->
                            <div class="md:col-span-3 relative group">
                                <div
                                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-purple-500 transition-colors">
                                    <i class="fas fa-shapes text-sm"></i>
                                </div>
                                <select name="type" id="type"
                                    class="w-full pl-11 pr-10 py-3 bg-gray-50/50 border border-gray-100/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-purple-200 transition-all appearance-none cursor-pointer text-sm font-medium text-gray-700">
                                    <option value="">All Types</option>
                                    @foreach ($types as $key => $type)
                                        <option value="{{ $key }}"
                                            {{ request('type') == $key ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400">
                                    <i class="fas fa-chevron-down text-[10px]"></i>
                                </div>
                            </div>

                            <!-- Category Filter -->
                            <div class="md:col-span-3 relative group">
                                <div
                                    class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-400 group-focus-within:text-purple-500 transition-colors">
                                    <i class="fas fa-folder text-sm"></i>
                                </div>
                                <select name="category" id="category"
                                    class="w-full pl-11 pr-10 py-3 bg-gray-50/50 border border-gray-100/50 rounded-2xl focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-purple-200 transition-all appearance-none cursor-pointer text-sm font-medium text-gray-700">
                                    <option value="">All Categories</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category }}"
                                            {{ request('category') == $category ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                                <div
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400">
                                    <i class="fas fa-chevron-down text-[10px]"></i>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="md:col-span-2 flex items-center gap-2">
                                <button type="submit"
                                    class="flex-1 bg-gray-900 text-white font-bold py-3 px-4 rounded-2xl hover:bg-black transition-all active:scale-95 shadow-lg shadow-gray-200 text-sm">
                                    Apply
                                </button>
                                @if (request('search') || request('type') || request('category'))
                                    <a href="{{ route('blog.index') }}"
                                        class="p-3 bg-gray-100 text-gray-500 rounded-2xl hover:bg-gray-200 transition-all active:scale-95">
                                        <i class="fas fa-times text-sm"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Premium Category/Tag Filters -->
            <div class="mb-12 overflow-x-auto pb-4 no-scrollbar">
                <div class="flex flex-nowrap md:flex-wrap items-center gap-3">
                    <span
                        class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mr-2 whitespace-nowrap">Filter
                        By:</span>
                    @foreach ($categories as $category)
                        <a href="{{ route('blog.index', ['category' => $category]) }}"
                            class="px-4 py-2 bg-white border border-gray-100 rounded-xl text-xs font-bold text-gray-600 hover:border-purple-200 hover:text-purple-600 hover:shadow-md hover:shadow-purple-500/5 transition-all whitespace-nowrap {{ request('category') == $category ? 'border-purple-500 text-purple-600 bg-purple-50/50' : '' }}">
                            {{ $category }}
                        </a>
                    @endforeach

                    <div class="h-4 w-px bg-gray-200 mx-2 hidden md:block"></div>

                    @foreach (['laravel', 'php', 'tutorial', 'guide'] as $tag)
                        <a href="{{ route('blog.index', ['search' => $tag]) }}"
                            class="px-4 py-2 bg-gray-50/50 border border-transparent rounded-xl text-xs font-bold text-gray-400 hover:text-gray-900 hover:bg-gray-100 transition-all whitespace-nowrap">
                            #{{ $tag }}
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Premium Blog Posts Grid -->
            @if ($posts->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                    @foreach ($posts as $post)
                        <article
                            class="group relative bg-white rounded-[2rem] shadow-sm hover:shadow-2xl hover:shadow-purple-500/10 hover:-translate-y-2 transition-all duration-500 overflow-hidden flex flex-col h-full border border-gray-100">
                            <!-- Featured Image -->
                            <div class="relative aspect-[16/10] overflow-hidden bg-gray-100">
                                @if ($post->featured_image)
                                    <img src="{{ $post->featured_image }}" alt="{{ $post->title }}"
                                        class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                @else
                                    <div
                                        class="w-full h-full bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 flex items-center justify-center">
                                        <i class="fas fa-image text-4xl text-white/40"></i>
                                    </div>
                                @endif

                                <!-- Glass Category Badge -->
                                <div class="absolute top-5 left-5">
                                    <span
                                        class="px-4 py-1.5 bg-white/60 backdrop-blur-md border border-white/40 text-[10px] font-extrabold text-gray-800 rounded-full shadow-sm uppercase tracking-wider">
                                        {{ $post->category ?? 'Article' }}
                                    </span>
                                </div>

                                <!-- Read Time Badge -->
                                <div class="absolute bottom-5 right-5">
                                    <span
                                        class="px-3 py-1 bg-black/50 backdrop-blur-md text-[10px] font-bold text-white rounded-lg flex items-center gap-1.5 uppercase tracking-wide">
                                        <i class="far fa-clock text-[8px]"></i>
                                        {{ ceil(str_word_count(strip_tags($post->content)) / 200) }} min
                                    </span>
                                </div>
                            </div>

                            <div class="p-8 flex-1 flex flex-col">
                                <!-- Date & Meta -->
                                <div class="flex items-center gap-2 mb-4">
                                    <span
                                        class="text-[10px] font-bold text-purple-600 uppercase tracking-widest">{{ $post->published_at->format('M d, Y') }}</span>
                                    <span class="w-1 h-1 bg-gray-300 rounded-full"></span>
                                    <span
                                        class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $post->type ?? 'Blog' }}</span>
                                </div>

                                <!-- Title -->
                                <h3
                                    class="text-xl lg:text-2xl font-extrabold text-gray-900 mb-4 line-clamp-2 leading-snug group-hover:text-purple-600 transition-colors">
                                    <a href="{{ route('blog.show', $post->slug) }}">
                                        {{ $post->title }}
                                    </a>
                                </h3>

                                <!-- Excerpt -->
                                <p class="text-gray-500 mb-8 line-clamp-3 text-sm flex-1 leading-relaxed font-medium">
                                    {{ $post->excerpt }}
                                </p>

                                <!-- Card Footer -->
                                <div class="flex items-center justify-between pt-6 border-t border-gray-50 mt-auto">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white text-sm font-bold shadow-lg shadow-purple-100 ring-2 ring-white">
                                            {{ substr($post->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="text-xs font-bold text-gray-900 leading-none mb-1">
                                                {{ $post->user->name }}</p>
                                            <p class="text-[10px] font-medium text-gray-400 uppercase tracking-wide">
                                                Curator</p>
                                        </div>
                                    </div>
                                    <a href="{{ route('blog.show', $post->slug) }}"
                                        class="w-10 h-10 rounded-xl bg-gray-50 flex items-center justify-center text-gray-400 hover:bg-purple-600 hover:text-white transition-all group-hover:scale-110 active:scale-95 shadow-sm">
                                        <i class="fas fa-arrow-right text-xs"></i>
                                    </a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="flex justify-center pb-20">
                    {{ $posts->links() }}
                </div>
            @else
                <!-- No Posts Found -->
                <div
                    class="bg-white/70 backdrop-blur-xl border border-white/40 rounded-[3rem] shadow-xl shadow-slate-200/50 overflow-hidden mb-20">
                    <div class="p-20 text-center">
                        <div
                            class="w-24 h-24 bg-gradient-to-br from-purple-50 to-indigo-50 rounded-3xl flex items-center justify-center mx-auto mb-8 shadow-inner shadow-indigo-100/50">
                            <i class="fas fa-scroll text-4xl text-purple-200"></i>
                        </div>
                        <h3 class="text-3xl font-extrabold text-gray-900 mb-4 tracking-tight">No stories found</h3>
                        <p class="text-lg text-gray-500 mb-10 max-w-lg mx-auto leading-relaxed font-medium">
                            @if (request('search') || request('type') || request('category'))
                                We couldn't find any posts matching your current filters. Try adjusting your search
                                criteria.
                            @else
                                Be the pioneer! Share your unique insights and start building your audience today.
                            @endif
                        </p>
                        @auth
                            <a href="{{ route('blog.create') }}"
                                class="inline-flex items-center gap-3 bg-brand-gradient text-white font-bold py-4 px-10 rounded-2xl shadow-lg shadow-purple-200 hover:shadow-purple-300 hover:scale-[1.02] active:scale-95 transition-all">
                                <i class="fas fa-plus text-sm"></i>
                                <span>Write Your First Story</span>
                            </a>
                        @else
                            <div class="flex flex-wrap justify-center gap-4">
                                <a href="{{ route('login') }}"
                                    class="bg-gray-900 text-white font-bold py-4 px-10 rounded-2xl shadow-lg shadow-gray-200 hover:bg-black transition-all active:scale-95">
                                    Login to Write
                                </a>
                                <a href="{{ route('register') }}"
                                    class="bg-white border border-gray-100 text-gray-700 font-bold py-4 px-10 rounded-2xl shadow-sm hover:shadow-md transition-all active:scale-95">
                                    Join Community
                                </a>
                            </div>
                        @endauth
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
