<x-app-layout>
    <x-slot name="title">{{ $post->title }} - CoolPosts</x-slot>

    <x-slot name="head">
        <meta name="description" content="{{ $post->excerpt ?: Str::limit(strip_tags($post->content), 160) }}">
        <meta name="keywords" content="{{ $post->meta_keywords ? implode(', ', $post->meta_keywords) : ($post->tags ? implode(', ', $post->tags) : '') }}">
        <meta name="robots" content="index, follow">
        <link rel="canonical" href="{{ url('/blog/' . $post->slug) }}">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="article">
        <meta property="og:url" content="{{ url('/blog/' . $post->slug) }}">
        <meta property="og:title" content="{{ $post->title }} - CoolPosts">
        <meta property="og:description" content="{{ $post->excerpt ?: Str::limit(strip_tags($post->content), 160) }}">
        <meta property="og:image" content="{{ $post->featured_image ? Storage::url($post->featured_image) : asset('images/og-blog.jpg') }}">
        <meta property="og:site_name" content="CoolPosts">
        <meta property="og:locale" content="en_US">
        <meta property="article:published_time" content="{{ $post->published_at->toISOString() }}">
        <meta property="article:author" content="{{ $post->user->name }}">
        @if($post->tags && is_array($post->tags))
            @foreach($post->tags as $tag)
                <meta property="article:tag" content="{{ trim($tag) }}">
            @endforeach
        @endif

        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="{{ url('/blog/' . $post->slug) }}">
        <meta property="twitter:title" content="{{ $post->title }} - CoolPosts">
        <meta property="twitter:description" content="{{ $post->excerpt ?: Str::limit(strip_tags($post->content), 160) }}">
        <meta property="twitter:image" content="{{ $post->featured_image ? Storage::url($post->featured_image) : asset('images/og-blog.jpg') }}">
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $post->title }}
            </h2>
            @auth
                @if (auth()->id() === $post->user_id || auth()->user()->isAdmin())
                    <div class="flex space-x-3">
                        <a href="{{ route('blog.edit', $post) }}"
                            class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-3 px-6 rounded-full shadow-lg hover:shadow-xl transition-all duration-300">
                            <i class="fas fa-edit mr-2"></i>Edit
                        </a>
                        <form method="POST" action="{{ route('blog.destroy', $post) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this post?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold py-3 px-6 rounded-full shadow-lg hover:shadow-xl transition-all duration-300">
                                <i class="fas fa-trash mr-2"></i>Delete
                            </button>
                        </form>
                        <a href="{{ route('blog.post-analytics', $post) }}"
                            class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-3 px-6 rounded-full shadow-lg hover:shadow-xl transition-all duration-300">
                            <i class="fas fa-chart-bar mr-2"></i>Analytics
                        </a>
                    </div>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 xl:grid-cols-4 lg:grid-cols-1 gap-6 lg:gap-8">
                <!-- Main Content Area (75% width) -->
                <div class="xl:col-span-3 lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-white/20">
                        <!-- Featured Image Section -->
                        @if($post->featured_image)
                            <div class="relative overflow-hidden">
                                <img src="{{ asset('storage/' . $post->featured_image) }}"
                                     alt="{{ $post->title }}"
                                     class="w-full h-96 object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                            </div>
                        @else
                            <div class="w-full h-96 bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center">
                                <i class="fas fa-newspaper text-6xl text-gray-400"></i>
                            </div>
                        @endif

                        <!-- Post Content -->
                        <div class="p-8">
                            <!-- Post Header -->
                            <div class="mb-8">
                                <!-- Type and Category Pills -->
                                <div class="flex flex-wrap gap-3 mb-4">
                                    <span class="px-3 py-1 bg-gradient-to-r from-purple-500 to-blue-600 text-white text-sm font-bold rounded-full">
                                        {{ ucfirst($post->type) }}
                                    </span>
                                    @if($post->category)
                                        <span class="px-3 py-1 bg-gray-100 text-gray-700 text-sm font-semibold rounded-full">
                                            {{ $post->category }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Title -->
                                <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $post->title }}</h1>

                                <!-- Excerpt -->
                                @if($post->excerpt)
                                    <p class="text-gray-600 text-lg mb-6">{{ $post->excerpt }}</p>
                                @endif

                                <!-- Author and Date -->
                                <div class="border-t border-gray-200 pt-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-3">
                                                <i class="fas fa-user text-white text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="font-semibold text-gray-900">{{ $post->user->name ?? 'Admin User' }}</p>
                                                <p class="text-xs text-gray-500">{{ $post->published_at ? $post->published_at->diffForHumans() : 'Draft' }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="flex items-center space-x-4">
                                                <div class="bg-gray-50 px-4 py-2 rounded-full">
                                                    <div class="text-sm text-gray-500">
                                                        <i class="fas fa-eye mr-2 text-purple-500"></i>{{ number_format($post->views) }} views
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
                            @if(!empty($post->tags))
                                <div class="border-t border-gray-200 pt-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-3">Tags</h3>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($post->tags as $tag)
                                            <span class="px-3 py-1 bg-purple-100 text-purple-700 text-sm rounded-full border border-purple-200 hover:bg-purple-200 transition-colors cursor-pointer">
                                                #{{ $tag }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Gallery Images -->
                            @if($post->gallery_images && count($post->gallery_images) > 0)
                                <div class="border-t border-gray-200 pt-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Gallery</h3>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                        @foreach($post->gallery_images as $image)
                                            <div class="aspect-w-1 aspect-h-1 rounded-lg overflow-hidden shadow-lg hover:shadow-xl transition-shadow duration-300">
                                                <img src="{{ asset('storage/' . $image) }}" alt="Gallery image" class="w-full h-full object-cover">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Attachments -->
                            @if($post->attachments && count($post->attachments) > 0)
                                <div class="border-t border-gray-200 pt-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Attachments</h3>
                                    <div class="space-y-3">
                                        @foreach($post->attachments as $attachment)
                                            <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg hover:bg-gray-100 transition-colors duration-300">
                                                <div class="flex items-center">
                                                    <i class="fas fa-file-alt text-gray-500 mr-3"></i>
                                                    <div>
                                                        <p class="text-sm font-medium text-gray-900">{{ $attachment['name'] }}</p>
                                                        <p class="text-xs text-gray-500">{{ number_format($attachment['size'] / 1024, 1) }} KB</p>
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
                        @if($relatedPosts->count() > 0)
                            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-sm border border-white/20 overflow-hidden">
                                <div class="p-5 border-b border-gray-100/50 bg-gradient-to-r from-purple-50/50 to-blue-50/50">
                                    <h3 class="text-base font-bold text-gray-900 flex items-center">
                                        <i class="fas fa-link text-purple-600 mr-2 text-sm"></i>
                                        Related Posts
                                    </h3>
                                </div>
                                <div class="p-4 space-y-3">
                                    @foreach($relatedPosts->take(3) as $relatedPost)
                                        <div class="p-3 bg-white rounded-xl hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 border border-gray-50 group">
                                            <div class="flex items-start space-x-3">
                                                <div class="w-10 h-10 bg-gradient-to-br from-blue-100 to-purple-100 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                                                    <i class="fas fa-file-alt text-blue-600 text-xs"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <h4 class="font-semibold text-gray-900 text-sm leading-tight mb-1">
                                                        <a href="{{ route('blog.show', $relatedPost->slug) }}" class="group-hover:text-purple-600 transition-colors">
                                                            {{ Str::limit($relatedPost->title, 50) }}
                                                        </a>
                                                    </h4>
                                                    <p class="text-xs text-gray-500">{{ $relatedPost->published_at ? $relatedPost->published_at->diffForHumans() : 'Draft' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Popular Posts Widget -->
                        @if($popularPosts->count() > 0)
                            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-sm border border-white/20 overflow-hidden">
                                <div class="p-5 border-b border-gray-100/50 bg-gradient-to-r from-orange-50/50 to-red-50/50">
                                    <h3 class="text-base font-bold text-gray-900 flex items-center">
                                        <i class="fas fa-fire text-orange-500 mr-2 text-sm"></i>
                                        Popular Posts
                                    </h3>
                                </div>
                                <div class="p-4 space-y-3">
                                    @foreach($popularPosts->take(4) as $popularPost)
                                        <div class="p-3 bg-white rounded-xl hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 border border-gray-50 group">
                                            <div class="flex items-start space-x-3">
                                                <div class="w-10 h-10 bg-gradient-to-br from-orange-100 to-red-100 rounded-lg flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                                                    <i class="fas fa-newspaper text-orange-600 text-sm"></i>
                                                </div>
                                                <div class="flex-1 min-w-0">
                                                    <h4 class="text-sm font-semibold text-gray-900 leading-tight mb-1">
                                                        <a href="{{ route('blog.show', $popularPost->slug) }}" class="group-hover:text-orange-600 transition-colors">
                                                            {{ Str::limit($popularPost->title, 40) }}
                                                        </a>
                                                    </h4>
                                                    <p class="text-xs text-gray-500">{{ $popularPost->published_at ? $popularPost->published_at->diffForHumans() : 'Draft' }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Categories Widget -->
                        @if($categories->count() > 0)
                            <div class="bg-white/80 backdrop-blur-sm rounded-2xl shadow-sm border border-white/20 overflow-hidden">
                                <div class="p-5 border-b border-gray-100/50 bg-gradient-to-r from-blue-50/50 to-cyan-50/50">
                                    <h3 class="text-base font-bold text-gray-900 flex items-center">
                                        <i class="fas fa-folder text-blue-500 mr-2 text-sm"></i>
                                        Categories
                                    </h3>
                                </div>
                                <div class="p-4 space-y-2">
                                    @foreach($categories as $category => $count)
                                        <div class="p-2 bg-transparent hover:bg-blue-50 rounded-lg transition-colors duration-200">
                                            <div class="flex justify-between items-center">
                                                <a href="{{ route('blog.index', ['category' => $category]) }}" class="text-gray-700 font-medium text-sm hover:text-blue-600 transition-colors flex items-center">
                                                    <i class="far fa-folder-open mr-2 text-gray-400 text-xs"></i>
                                                    {{ $category }}
                                                </a>
                                                <span class="px-2.5 py-0.5 bg-gray-100 text-gray-600 text-xs font-semibold rounded-full border border-gray-200">{{ $count }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Popular Tags Widget -->
                        @if(count($popularTags) > 0)
                            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
                                <div class="p-4 border-b border-gray-100">
                                    <h3 class="text-base font-bold text-gray-900 flex items-center">
                                        <i class="fas fa-tags text-green-500 mr-2 text-sm"></i>
                                        Popular Tags
                                    </h3>
                                </div>
                                <div class="p-4">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($popularTags as $tag => $count)
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

    <style>
        /* Custom content styling to match the reference images */
        .content-body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.7;
            color: #374151;
        }

        /* Ensure markdown-generated HTML is properly styled */
        .content-body h1,
        .content-body h2,
        .content-body h3,
        .content-body h4,
        .content-body h5,
        .content-body h6 {
            margin-top: 2rem;
            margin-bottom: 1rem;
            font-weight: 600;
            color: #111827;
            line-height: 1.3;
        }

        .content-body h1 {
            font-size: 2.25rem;
            font-weight: 700;
        }

        .content-body h2 {
            font-size: 1.875rem;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 0.5rem;
        }

        .content-body h3 {
            font-size: 1.5rem;
        }

        .content-body h4 {
            font-size: 1.25rem;
        }

        .content-body p {
            font-size: 1.125rem;
            margin-bottom: 1.25rem;
            color: #4b5563;
            line-height: 1.7;
        }

        .content-body ul,
        .content-body ol {
            margin-bottom: 1.25rem;
            padding-left: 1.5rem;
        }

        .content-body li {
            margin-bottom: 0.5rem;
            color: #4b5563;
            font-size: 1.125rem;
        }

        .content-body ul li {
            list-style-type: disc;
        }

        .content-body ol li {
            list-style-type: decimal;
        }

        .content-body strong {
            font-weight: 600;
            color: #111827;
        }

        .content-body em {
            font-style: italic;
            color: #4b5563;
        }

        .content-body a {
            color: #7c3aed;
            text-decoration: underline;
            text-decoration-thickness: 2px;
            text-underline-offset: 2px;
        }

        .content-body a:hover {
            color: #6d28d9;
        }

        .content-body blockquote {
            border-left: 4px solid #7c3aed;
            padding-left: 1rem;
            margin: 1.5rem 0;
            font-style: italic;
            color: #6b7280;
            background-color: #f9fafb;
            padding: 1rem;
            border-radius: 0.5rem;
        }

        .content-body code {
            background-color: #f3f4f6;
            color: #1f2937;
            padding: 0.25rem 0.5rem;
            border-radius: 0.375rem;
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            font-size: 0.875rem;
            border: 1px solid #e5e7eb;
        }

        .content-body pre {
            background-color: #1f2937;
            color: #f9fafb;
            padding: 1.5rem;
            border-radius: 0.75rem;
            overflow-x: auto;
            margin: 1.5rem 0;
            border: 1px solid #374151;
        }

        .content-body pre code {
            background-color: transparent;
            color: inherit;
            padding: 0;
            border: none;
            font-size: 0.875rem;
            line-height: 1.6;
        }



        .content-body img {
            max-width: 100%;
            height: auto;
            border-radius: 0.75rem;
            margin: 1.5rem 0;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        }

        .content-body table {
            width: 100%;
            border-collapse: collapse;
            margin: 1.5rem 0;
            background-color: white;
            border-radius: 0.75rem;
            overflow: hidden;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .content-body th {
            background-color: #f9fafb;
            padding: 0.75rem 1rem;
            text-align: left;
            font-weight: 600;
            color: #111827;
            border-bottom: 1px solid #e5e7eb;
        }

        .content-body td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #f3f4f6;
            color: #4b5563;
        }

        .content-body tr:hover {
            background-color: #f9fafb;
        }

        /* Special styling for code blocks */
        .content-body pre[class*="language-"],
        .content-body pre[class*="lang-"] {
            background-color: #1f2937;
            color: #f9fafb;
        }

        .content-body pre[class*="language-bash"],
        .content-body pre[class*="lang-bash"] {
            background-color: #1f2937;
            color: #f9fafb;
        }

        .content-body pre[class*="language-nginx"],
        .content-body pre[class*="lang-nginx"] {
            background-color: #1f2937;
            color: #f9fafb;
        }

        .content-body pre[class*="language-html"],
        .content-body pre[class*="lang-html"] {
            background-color: #1f2937;
            color: #f9fafb;
        }

        .content-body pre[class*="language-javascript"],
        .content-body pre[class*="lang-javascript"] {
            background-color: #1f2937;
            color: #f9fafb;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .content-body h1 {
                font-size: 1.875rem;
            }

            .content-body h2 {
                font-size: 1.5rem;
            }

            .content-body h3 {
                font-size: 1.25rem;
            }

            .content-body p, .content-body li {
                font-size: 1rem;
            }
        }
    </style>

    <!-- Smart Tracking JavaScript -->
    <script>
        class SmartTracking {
            constructor() {
                this.isTabActive = true;
                this.lastActivity = new Date();
                this.inactivityTimer = null;
                this.activityTimeout = 30000; // 30 seconds
                this.trackingData = {
                    tab_active: true,
                    last_activity: this.lastActivity.toISOString(),
                    self_visit: false,
                    smart_tracking_enabled: true
                };

                this.init();
            }

            init() {
                this.setupTabVisibilityTracking();
                this.setupActivityTracking();
                this.setupInactivityTimer();
                this.setupPageUnloadTracking();
                this.sendTrackingData();
            }

            setupTabVisibilityTracking() {
                // Track when tab becomes hidden/visible
                document.addEventListener('visibilitychange', () => {
                    this.isTabActive = !document.hidden;
                    this.trackingData.tab_active = this.isTabActive;

                    if (this.isTabActive) {
                        this.updateLastActivity();
                        this.sendTrackingData();
                    } else {
                        console.log('Tab became inactive, pausing tracking');
                    }
                });

                // Track when window loses focus
                window.addEventListener('blur', () => {
                    this.isTabActive = false;
                    this.trackingData.tab_active = false;
                    console.log('Window lost focus, pausing tracking');
                });

                window.addEventListener('focus', () => {
                    this.isTabActive = true;
                    this.trackingData.tab_active = true;
                    this.updateLastActivity();
                    this.sendTrackingData();
                    console.log('Window gained focus, resuming tracking');
                });
            }

            setupActivityTracking() {
                // Track user interactions
                const activityEvents = [
                    'mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart',
                    'click', 'input', 'focus', 'wheel'
                ];

                activityEvents.forEach(event => {
                    document.addEventListener(event, () => {
                        this.updateLastActivity();
                    }, { passive: true });
                });

                // Track scroll depth
                let maxScrollDepth = 0;
                window.addEventListener('scroll', () => {
                    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                    const scrollHeight = document.documentElement.scrollHeight - window.innerHeight;
                    const scrollDepth = Math.round((scrollTop / scrollHeight) * 100);

                    if (scrollDepth > maxScrollDepth) {
                        maxScrollDepth = scrollDepth;
                        this.trackingData.scroll_depth = maxScrollDepth;
                    }

                    this.updateLastActivity();
                }, { passive: true });
            }

            setupInactivityTimer() {
                this.resetInactivityTimer();
            }

            resetInactivityTimer() {
                if (this.inactivityTimer) {
                    clearTimeout(this.inactivityTimer);
                }

                this.inactivityTimer = setTimeout(() => {
                    console.log('User inactive for 30 seconds, pausing tracking');
                    this.trackingData.tab_active = false;
                    this.sendTrackingData();
                }, this.activityTimeout);
            }

            updateLastActivity() {
                this.lastActivity = new Date();
                this.trackingData.last_activity = this.lastActivity.toISOString();
                this.resetInactivityTimer();
            }

            sendTrackingData() {
                // Send tracking data to server
                fetch('{{ route("blog.track-visitor", $post) }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(this.trackingData)
                }).catch(error => {
                    console.log('Tracking data sent:', this.trackingData);
                });
            }

            setupPageUnloadTracking() {
                window.addEventListener('beforeunload', () => {
                    // Send final tracking data
                    this.trackingData.page_unload = true;
                    this.trackingData.time_spent = this.getTimeSpent();

                    // Use sendBeacon for reliable data transmission on page unload
                    if (navigator.sendBeacon) {
                        const data = new FormData();
                        data.append('tracking_data', JSON.stringify(this.trackingData));
                        data.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

                        navigator.sendBeacon('{{ route("blog.track-leave", $post) }}', data);
                    }
                });
            }

            getTimeSpent() {
                return Math.round((new Date() - this.lastActivity) / 1000);
            }
        }

        // Initialize smart tracking when page loads
        document.addEventListener('DOMContentLoaded', () => {
            // Check if this is a self visit
            const currentUserId = {{ auth()->id() ?? 'null' }};
            const postUserId = {{ $post->user_id }};

            if (currentUserId && currentUserId === postUserId) {
                console.log('Self visit detected, tracking disabled');
                return;
            }

            // Initialize smart tracking
            window.smartTracking = new SmartTracking();
        });
    </script>
</x-app-layout>
