<x-app-layout>
    <x-slot name="title">{{ $post->title }} - CoolPosts</x-slot>

    <x-slot name="head">
        @php
            $schemaDescription = $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 160);
            $schema = [
                '@context' => 'https://schema.org',
                '@type' => 'BlogPosting',
                'headline' => $post->title,
                'description' => $schemaDescription,
                'author' => [
                    '@type' => 'Person',
                    'name' => $post->user->name ?? 'Admin',
                ],
                'publisher' => [
                    '@type' => 'Organization',
                    'name' => 'CoolPosts',
                    'logo' => [
                        '@type' => 'ImageObject',
                        'url' => asset('img/logo.png'),
                    ],
                ],
                'datePublished' => $post->published_at->toIso8601String(),
                'dateModified' => $post->updated_at->toIso8601String(),
                'image' => [
                    '@type' => 'ImageObject',
                    'url' => $post->featured_image ? $post->featured_image : asset('images/og-blog.jpg'),
                ],
                'mainEntityOfPage' => [
                    '@type' => 'WebPage',
                    '@id' => route('blog.show', $post),
                ],
            ];

            $breadcrumbs = [
                '@context' => 'https://schema.org',
                '@type' => 'BreadcrumbList',
                'itemListElement' => [
                    [
                        '@type' => 'ListItem',
                        'position' => 1,
                        'name' => 'Blog Posts',
                        'item' => route('blog.index'),
                    ],
                    [
                        '@type' => 'ListItem',
                        'position' => 2,
                        'name' => $post->title,
                    ],
                ],
            ];
        @endphp

        <meta name="description" content="{{ $post->excerpt ?: Str::limit(strip_tags($post->content), 160) }}">

        <meta name="robots" content="index, follow">
        <link rel="canonical" href="{{ route('blog.show', $post) }}">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="article">
        <meta property="og:url" content="{{ route('blog.show', $post) }}">
        <meta property="og:title" content="{{ $post->title }} - CoolPosts">
        <meta property="og:description" content="{{ $post->excerpt ?: Str::limit(strip_tags($post->content), 160) }}">
        <meta property="og:image"
            content="{{ $post->featured_image ? $post->featured_image : asset('images/og-blog.jpg') }}">
        <meta property="og:site_name" content="CoolPosts">
        <meta property="og:locale" content="en_US">
        <meta property="article:published_time" content="{{ $post->published_at->toISOString() }}">
        @if ($post->tags && is_array($post->tags))
            @foreach ($post->tags as $tag)
                <meta property="article:tag" content="{{ trim($tag) }}">
            @endforeach
        @endif

        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="{{ route('blog.show', $post) }}">
        <meta property="twitter:title" content="{{ $post->title }} - CoolPosts">
        <meta property="twitter:description"
            content="{{ $post->excerpt ?: Str::limit(strip_tags($post->content), 160) }}">
        <meta property="twitter:image"
            content="{{ $post->featured_image ? $post->featured_image : asset('images/og-blog.jpg') }}">
        <!-- Article Structured Data -->
        <script type="application/ld+json">
            {!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        </script>
        <script type="application/ld+json">
            {!! json_encode($breadcrumbs, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT) !!}
        </script>
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $post->title }}
            </h2>


        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 xl:grid-cols-4 lg:grid-cols-1 gap-6 lg:gap-8">
                <!-- Main Content Area (75% width) -->
                <div class="xl:col-span-3 lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-white/20">
                        <!-- Featured Image Section -->
                        @if ($post->featured_image)
                            <div class="relative overflow-hidden">
                                <img src="{{ asset('storage/' . $post->featured_image) }}" alt="{{ $post->title }}"
                                    class="w-full h-96 object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                            </div>
                        @else
                            <div
                                class="w-full h-96 bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center">
                                <i class="fas fa-newspaper text-6xl text-gray-400"></i>
                            </div>
                        @endif

                        <!-- Post Content -->
                        <div class="p-8">
                            <!-- Post Header -->
                            <div class="mb-8">
                                <!-- Type and Category Pills -->
                                <div class="flex flex-wrap gap-3 mb-4">
                                    <span
                                        class="px-3 py-1 bg-gradient-to-r from-purple-500 to-blue-600 text-white text-sm font-bold rounded-full">
                                        {{ ucfirst($post->type) }}
                                    </span>
                                    @if ($post->category)
                                        <span
                                            class="px-3 py-1 bg-gray-100 text-gray-700 text-sm font-semibold rounded-full">
                                            {{ $post->category }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Title -->
                                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $post->title }}</h1>

                                <!-- Excerpt -->
                                @if ($post->excerpt)
                                    <p class="text-gray-600 text-lg mb-6">{{ $post->excerpt }}</p>
                                @endif

                                <!-- Author and Date -->
                                <div class="border-t border-gray-200 pt-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div
                                                class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-3">
                                                <i class="fas fa-user text-white text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900">
                                                    {{ $post->user->name ?? 'Admin User' }}</p>
                                                <p class="text-xs text-gray-500">
                                                    {{ $post->published_at ? $post->published_at->diffForHumans() : 'Draft' }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="flex items-center space-x-4">
                                                <div class="bg-gray-50 px-4 py-2 rounded-full">
                                                    <div class="text-sm text-gray-500">
                                                        <i
                                                            class="fas fa-eye mr-2 text-purple-500"></i>{{ number_format($post->views) }}
                                                        views
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Main Content -->
                            <div class="content-body mb-8">
                                {!! $post->getFormattedContent() !!}
                            </div>

                            <!-- Tags Section -->
                            @if (!empty($post->tags))
                                <div class="border-t border-gray-200 pt-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Tags</h3>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($post->tags as $tag)
                                            <span
                                                class="px-3 py-1 bg-purple-100 text-purple-700 text-sm rounded-full border border-purple-200 hover:bg-purple-200 transition-colors cursor-pointer">
                                                #{{ $tag }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Gallery Images -->
                            @if ($post->gallery_images && count($post->gallery_images) > 0)
                                <div class="border-t border-gray-200 pt-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Gallery</h3>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                        @foreach ($post->gallery_images as $image)
                                            <div
                                                class="aspect-w-1 aspect-h-1 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300">
                                                <img src="{{ asset('storage/' . $image) }}" alt="Gallery image"
                                                    class="w-full h-full object-cover">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Attachments -->
                            @if ($post->attachments && count($post->attachments) > 0)
                                <div class="border-t border-gray-200 pt-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Attachments</h3>
                                    <div class="space-y-3">
                                        @foreach ($post->attachments as $attachment)
                                            <div
                                                class="flex items-center justify-between bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition-colors duration-300">
                                                <div class="flex items-center">
                                                    <i class="fas fa-file-alt text-gray-500 mr-3"></i>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900">
                                                            {{ $attachment['name'] }}</p>
                                                        <p class="text-xs text-gray-500">
                                                            {{ number_format($attachment['size'] / 1024, 1) }} KB</p>
                                                    </div>
                                                </div>
                                                <a href="{{ asset('storage/' . $attachment['path']) }}"
                                                    class="bg-purple-500 hover:bg-purple-600 text-white px-4 py-2 rounded-full text-sm font-medium transition-colors duration-300">
                                                    <i class="fas fa-download mr-2"></i>Download
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar (25% width) -->
                <div class="xl:col-span-1 lg:col-span-1">
                    <div class="space-y-6">
                        <!-- Related Posts Widget -->
                        @if ($relatedPosts->count() > 0)
                            <div
                                class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-sm border border-white/20 overflow-hidden">
                                <div
                                    class="p-5 border-b border-gray-100/50 bg-gradient-to-r from-purple-50/50 to-blue-50/50">
                                    <h3 class="text-base font-bold text-gray-900 flex items-center">
                                        <i class="fas fa-link text-purple-600 mr-2 text-sm"></i>
                                        Related Posts
                                    </h3>
                                </div>
                                <div class="p-4 space-y-3">
                                    @foreach ($relatedPosts->take(3) as $relatedPost)
                                        <div
                                            class="p-3 bg-white rounded-xl hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 border border-gray-50 group">
                                            <div class="flex items-start space-x-3">
                                                <div
                                                    class="w-10 h-10 bg-gradient-to-br from-blue-100 to-purple-100 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                                                    <i class="fas fa-file-alt text-blue-600 text-xs"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <h4 class="font-semibold text-gray-900 text-sm leading-tight mb-1">
                                                        <a href="{{ route('blog.show', $relatedPost->slug) }}"
                                                            class="group-hover:text-purple-600 transition-colors">
                                                            {{ Str::limit($relatedPost->title, 50) }}
                                                        </a>
                                                    </h4>
                                                    <p class="text-xs text-gray-500">
                                                        {{ $relatedPost->published_at ? $relatedPost->published_at->diffForHumans() : 'Draft' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Popular Posts Widget -->
                        @if ($popularPosts->count() > 0)
                            <div
                                class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-sm border border-white/20 overflow-hidden">
                                <div
                                    class="p-5 border-b border-gray-100/50 bg-gradient-to-r from-orange-50/50 to-red-50/50">
                                    <h3 class="text-base font-bold text-gray-900 flex items-center">
                                        <i class="fas fa-fire text-orange-500 mr-2 text-sm"></i>
                                        Popular Posts
                                    </h3>
                                </div>
                                <div class="p-4 space-y-3">
                                    @foreach ($popularPosts->take(4) as $popularPost)
                                        <div
                                            class="p-3 bg-white rounded-xl hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 border border-gray-50 group">
                                            <div class="flex items-start space-x-3">
                                                <div
                                                    class="w-10 h-10 bg-gradient-to-br from-orange-100 to-red-100 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                                                    <i class="fas fa-newspaper text-orange-600 text-sm"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <h4 class="text-sm font-semibold text-gray-900 leading-tight mb-1">
                                                        <a href="{{ route('blog.show', $popularPost->slug) }}"
                                                            class="group-hover:text-orange-600 transition-colors">
                                                            {{ Str::limit($popularPost->title, 40) }}
                                                        </a>
                                                    </h4>
                                                    <p class="text-xs text-gray-500">
                                                        {{ $popularPost->published_at ? $popularPost->published_at->diffForHumans() : 'Draft' }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Categories Widget -->
                        @if ($categories->count() > 0)
                            <div
                                class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-sm border border-white/20 overflow-hidden">
                                <div
                                    class="p-5 border-b border-gray-100/50 bg-gradient-to-r from-blue-50/50 to-cyan-50/50">
                                    <h3 class="text-base font-bold text-gray-900 flex items-center">
                                        <i class="fas fa-folder text-blue-500 mr-2 text-sm"></i>
                                        Categories
                                    </h3>
                                </div>
                                <div class="p-4 space-y-2">
                                    @foreach ($categories as $category => $count)
                                        <div
                                            class="p-2 bg-transparent hover:bg-blue-50 rounded-lg transition-colors duration-200">
                                            <div class="flex justify-between items-center">
                                                <a href="{{ route('blog.index', ['category' => $category]) }}"
                                                    class="text-gray-700 font-medium text-sm hover:text-blue-600 transition-colors flex items-center">
                                                    <i class="far fa-folder-open mr-2 text-gray-400 text-xs"></i>
                                                    {{ $category }}
                                                </a>
                                                <span
                                                    class="px-2.5 py-0.5 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full border border-gray-200">{{ $count }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Popular Tags Widget -->
                        @if (count($popularTags) > 0)
                            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                                <div class="p-4 border-b border-gray-100">
                                    <h3 class="text-base font-bold text-gray-900 flex items-center">
                                        <i class="fas fa-tags text-green-500 mr-2 text-sm"></i>
                                        Popular Tags
                                    </h3>
                                </div>
                                <div class="p-4">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($popularTags as $tag => $count)
                                            <a href="{{ route('blog.index', ['search' => $tag]) }}"
                                                class="px-2 py-1 bg-green-100 text-green-700 text-xs rounded-full border border-green-200 hover:bg-green-200 transition-colors">
                                                #{{ $tag }}
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>



    <!-- Smart Tracking JavaScript -->
    <script>
        class SmartTracking {
            constructor() {
                this.pageLoadTime = new Date();
                this.maxScrollDepth = 0;
                this.scrollMilestones = [25, 50, 75, 100];
                this.sentMilestones = new Set();

                this.trackingData = {
                    scroll_depth: 0,
                    time_spent: 0
                };

                this.init();
            }

            init() {
                this.setupScrollTracking();
                this.setupPageUnloadTracking();
            }

            setupScrollTracking() {
                let scrollTimeout;

                // Track scroll depth with throttling
                window.addEventListener('scroll', () => {
                    if (scrollTimeout) return;

                    scrollTimeout = setTimeout(() => {
                        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                        const scrollHeight = document.documentElement.scrollHeight - window.innerHeight;

                        if (scrollHeight <= 0) return;

                        let scrollPercent = Math.round((scrollTop / scrollHeight) * 100);
                        if (scrollPercent > 100) scrollPercent = 100;

                        if (scrollPercent > this.maxScrollDepth) {
                            this.maxScrollDepth = scrollPercent;
                            this.trackingData.scroll_depth = this.maxScrollDepth;
                            this.checkMilestones(scrollPercent);
                        }

                        scrollTimeout = null;
                    }, 500); // Check every 500ms max
                }, {
                    passive: true
                });
            }

            checkMilestones(percent) {
                this.scrollMilestones.forEach(milestone => {
                    if (percent >= milestone && !this.sentMilestones.has(milestone)) {
                        this.sentMilestones.add(milestone);
                        // Console log for debug, actual data sent on unload
                        console.log(`Scroll milestone reached: ${milestone}%`);
                    }
                });
            }

            setupPageUnloadTracking() {
                // Handle page unload
                const handleUnload = () => {
                    this.trackingData.time_spent = this.getTimeSpent();
                    this.trackingData.scroll_depth = this.maxScrollDepth;

                    // Use sendBeacon for reliable data transmission on page unload
                    if (navigator.sendBeacon) {
                        const data = new FormData();
                        data.append('tracking_data', JSON.stringify(this.trackingData));
                        data.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'));

                        const url = '{{ route('blog.track-leave', $post) }}';
                        navigator.sendBeacon(url, data);
                    }
                };

                window.addEventListener('beforeunload', handleUnload);

                // Fallback for mobile/other browsers
                document.addEventListener('visibilitychange', () => {
                    if (document.visibilityState === 'hidden') {
                        handleUnload();
                    }
                });
            }

            getTimeSpent() {
                const now = new Date();
                return Math.round((now - this.pageLoadTime) / 1000);
            }
        }

        // Initialize smart tracking when page loads
        document.addEventListener('DOMContentLoaded', () => {
            // Check if this is a self visit (Server-side flag)
            const isAuthor = @json(auth()->id() === $post->user_id || auth()->user()?->isAdmin());

            if (isAuthor) {
                console.log('Self visit detected (Author/Admin), tracking disabled');
                return;
            }

            // Initialize smart tracking
            window.smartTracking = new SmartTracking();
        });
    </script>

</x-app-layout>
