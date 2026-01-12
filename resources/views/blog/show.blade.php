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
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3 text-xs font-medium text-gray-500">
                        <li class="inline-flex items-center">
                            <a href="{{ route('dashboard') }}" class="hover:text-purple-600 transition-colors">
                                <i class="fas fa-home mr-2"></i>Dashboard
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2 text-[10px]"></i>
                                <a href="{{ route('blog.index') }}"
                                    class="hover:text-purple-600 transition-colors uppercase tracking-widest font-bold text-[10px]">Blog</a>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2 text-[10px]"></i>
                                <span
                                    class="text-gray-900 truncate max-w-[200px] font-bold text-[10px] uppercase tracking-widest">{{ $post->title }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('blog.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-xs font-bold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm uppercase tracking-widest">
                    <i class="fas fa-arrow-left mr-2 text-gray-400 text-[10px]"></i>
                    Back to Feed
                </a>
                @if (auth()->id() === $post->user_id || (auth()->user() && auth()->user()->isAdmin()))
                    <a href="{{ route('blog.edit', $post) }}"
                        class="inline-flex items-center px-6 py-2.5 bg-brand-gradient text-white rounded-xl text-xs font-bold shadow-lg shadow-purple-200 hover:shadow-purple-300 hover:scale-[1.02] active:scale-[0.98] transition-all uppercase tracking-widest">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Story
                    </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-50/50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Article Header / Hero -->
            <div class="mb-12">
                <div
                    class="relative rounded-[2.5rem] overflow-hidden shadow-2xl shadow-purple-900/10 min-h-[400px] flex flex-col justify-end p-8 md:p-16">
                    <!-- Featured Image Background -->
                    @if ($post->featured_image)
                        <img src="{{ $post->featured_image }}" alt="{{ $post->title }}"
                            class="absolute inset-0 w-full h-full object-cover">
                    @else
                        <div class="absolute inset-0 bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600">
                        </div>
                        <div class="absolute inset-0 bg-[url('/img/grid.svg')] bg-center opacity-20"></div>
                    @endif

                    <!-- Gradient Overlay -->
                    <div class="absolute inset-0 bg-gradient-to-t from-gray-900 via-gray-900/40 to-transparent"></div>

                    <!-- Content In Hero -->
                    <div class="relative z-10 max-w-4xl">
                        <div class="flex flex-wrap gap-3 mb-6">
                            <span
                                class="px-4 py-1.5 bg-white/20 backdrop-blur-md border border-white/30 text-white text-[10px] font-extrabold rounded-full uppercase tracking-wider">
                                {{ $post->category ?? 'Article' }}
                            </span>
                            <span
                                class="px-4 py-1.5 bg-purple-500/80 backdrop-blur-md border border-purple-400/30 text-white text-[10px] font-extrabold rounded-full uppercase tracking-wider">
                                {{ $post->type ?? 'Post' }}
                            </span>
                        </div>

                        <h1 class="text-4xl md:text-6xl font-extrabold text-white mb-6 tracking-tight leading-[1.1]">
                            {{ $post->title }}
                        </h1>

                        <div class="flex items-center gap-6">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-12 h-12 rounded-2xl bg-gradient-to-br from-purple-400 to-indigo-500 p-0.5 shadow-lg">
                                    <div
                                        class="w-full h-full rounded-[0.85rem] bg-gray-900 flex items-center justify-center text-white font-bold text-sm">
                                        {{ substr($post->user->name, 0, 1) }}
                                    </div>
                                </div>
                                <div>
                                    <p class="text-sm font-bold text-white">{{ $post->user->name }}</p>
                                    <p
                                        class="text-[10px] font-bold text-gray-400 uppercase tracking-widest leading-none mt-1">
                                        Author / Curator</p>
                                </div>
                            </div>
                            <div class="h-8 w-px bg-white/20 hidden sm:block"></div>
                            <div class="hidden sm:block">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Published
                                </p>
                                <p class="text-sm font-bold text-white">
                                    {{ $post->published_at ? $post->published_at->format('M d, Y') : 'Draft' }}</p>
                            </div>
                            <div class="h-8 w-px bg-white/20 hidden sm:block"></div>
                            <div class="hidden sm:block">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Views</p>
                                <p class="text-sm font-bold text-white flex items-center gap-2">
                                    <i class="fas fa-eye text-purple-400"></i>
                                    {{ number_format($post->views) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-4 gap-12">
                <!-- Main Content Area -->
                <div class="xl:col-span-3">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-3xl border border-gray-100">
                        <div class="p-8 md:p-12">
                            <!-- Excerpt if exists -->
                            @if ($post->excerpt)
                                <div
                                    class="mb-12 p-8 bg-slate-50/50 rounded-3xl border border-slate-100 flex gap-6 italic text-gray-600 text-lg md:text-xl font-medium leading-relaxed">
                                    <i class="fas fa-quote-left text-purple-200 text-4xl mt-1"></i>
                                    <p>{{ $post->excerpt }}</p>
                                </div>
                            @endif

                            <!-- Article Content - Keeping original classes as requested -->
                            <div class="content-body mb-8">
                                {!! $post->getFormattedContent() !!}
                            </div>

                            <!-- Tags Section -->
                            @if (!empty($post->tags))
                                <div class="mt-16 pt-10 border-t border-slate-100">
                                    <h3
                                        class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                                        <i class="fas fa-tags text-purple-400"></i>
                                        Exploration Tags
                                    </h3>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach ($post->tags as $tag)
                                            <span
                                                class="px-5 py-2.5 bg-slate-50 text-slate-600 text-[11px] font-bold rounded-xl border border-slate-100 hover:border-purple-200 hover:text-purple-600 hover:bg-purple-50 transition-all cursor-pointer uppercase tracking-wider">
                                                #{{ $tag }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Gallery Images -->
                            @if ($post->gallery_images && count($post->gallery_images) > 0)
                                <div class="mt-16 pt-10 border-t border-slate-100">
                                    <h3
                                        class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                                        <i class="fas fa-images text-purple-400"></i>
                                        Visual Gallery
                                    </h3>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                                        @foreach ($post->gallery_images as $image)
                                            <div
                                                class="group relative aspect-square rounded-3xl overflow-hidden shadow-lg hover:shadow-2xl hover:shadow-purple-500/10 transition-all duration-500">
                                                <img src="{{ $image }}" alt="Gallery image"
                                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                                                <div
                                                    class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Attachments -->
                            @if ($post->attachments && count($post->attachments) > 0)
                                <div class="mt-16 pt-10 border-t border-slate-100">
                                    <h3
                                        class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                                        <i class="fas fa-paperclip text-purple-400"></i>
                                        Resource Assets
                                    </h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @foreach ($post->attachments as $attachment)
                                            <div
                                                class="flex items-center justify-between bg-slate-50/50 p-5 rounded-3xl border border-slate-100 hover:border-purple-200 hover:bg-white transition-all group">
                                                <div class="flex items-center gap-4">
                                                    <div
                                                        class="w-12 h-12 rounded-2xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 group-hover:text-purple-500 transition-colors shadow-sm">
                                                        <i class="fas fa-file-alt text-lg"></i>
                                                    </div>
                                                    <div>
                                                        <p class="text-sm font-bold text-slate-900 leading-tight mb-1">
                                                            {{ Str::limit($attachment['name'], 25) }}</p>
                                                        <p
                                                            class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                                            {{ number_format($attachment['size'] / 1024, 1) }} KB /
                                                            ASSET
                                                        </p>
                                                    </div>
                                                </div>
                                                <a href="{{ asset('storage/' . $attachment['path']) }}"
                                                    class="w-10 h-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:bg-brand-gradient hover:text-white hover:border-transparent transition-all shadow-sm active:scale-95">
                                                    <i class="fas fa-download text-xs"></i>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar -->
                <div class="xl:col-span-1 space-y-8">
                    <!-- Related Posts Widget -->
                    @if ($relatedPosts->count() > 0)
                        <div
                            class="bg-white/70 backdrop-blur-xl border border-white/20 rounded-[2.5rem] shadow-xl shadow-slate-200/50 overflow-hidden">
                            <div class="p-6 border-b border-slate-50 flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600">
                                    <i class="fas fa-link text-sm"></i>
                                </div>
                                <h3
                                    class="text-xs font-extrabold text-gray-900 tracking-tight uppercase tracking-widest">
                                    Similar Stories</h3>
                            </div>
                            <div class="p-4 space-y-3">
                                @foreach ($relatedPosts->take(4) as $relatedPost)
                                    <a href="{{ route('blog.show', $relatedPost->slug) }}"
                                        class="group block p-3 bg-slate-50/50 hover:bg-white border border-transparent hover:border-slate-100 rounded-2xl transition-all">
                                        <h4
                                            class="text-sm font-bold text-gray-900 leading-tight group-hover:text-purple-600 transition-colors line-clamp-2">
                                            {{ $relatedPost->title }}
                                        </h4>
                                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">
                                            {{ $relatedPost->published_at ? $relatedPost->published_at->format('M d') : 'Draft' }}
                                        </p>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Popular Posts Widget -->
                    @if ($popularPosts->count() > 0)
                        <div
                            class="bg-white/70 backdrop-blur-xl border border-white/20 rounded-[2.5rem] shadow-xl shadow-slate-200/50 overflow-hidden">
                            <div class="p-6 border-b border-slate-50 flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-lg bg-orange-50 flex items-center justify-center text-orange-600">
                                    <i class="fas fa-fire text-sm"></i>
                                </div>
                                <h3
                                    class="text-xs font-extrabold text-gray-900 tracking-tight uppercase tracking-widest">
                                    Hot Right Now</h3>
                            </div>
                            <div class="p-4 space-y-3">
                                @foreach ($popularPosts->take(4) as $popularPost)
                                    <a href="{{ route('blog.show', $popularPost->slug) }}"
                                        class="group block p-3 bg-slate-50/50 hover:bg-white border border-transparent hover:border-slate-100 rounded-2xl transition-all">
                                        <h4
                                            class="text-sm font-bold text-gray-900 leading-tight group-hover:text-orange-600 transition-colors line-clamp-2">
                                            {{ $popularPost->title }}
                                        </h4>
                                        <p class="text-[9px] font-bold text-gray-400 uppercase tracking-widest mt-1">
                                            <i class="fas fa-eye mr-1"></i>{{ number_format($popularPost->views) }}
                                            Views
                                        </p>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Categories Widget -->
                    @if ($categories->count() > 0)
                        <div
                            class="bg-white/70 backdrop-blur-xl border border-white/20 rounded-[2.5rem] shadow-xl shadow-slate-200/50 overflow-hidden">
                            <div class="p-6 border-b border-slate-50 flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                                    <i class="fas fa-folder text-sm"></i>
                                </div>
                                <h3
                                    class="text-xs font-extrabold text-gray-900 tracking-tight uppercase tracking-widest">
                                    Collections</h3>
                            </div>
                            <div class="p-4 space-y-1">
                                @foreach ($categories as $category => $count)
                                    <a href="{{ route('blog.index', ['category' => $category]) }}"
                                        class="flex justify-between items-center p-2 rounded-xl hover:bg-slate-50 transition-all group">
                                        <span
                                            class="text-xs font-bold text-gray-600 group-hover:text-blue-600 transition-colors">{{ $category }}</span>
                                        <span class="text-[10px] font-bold text-gray-400">{{ $count }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Track unique views
            document.addEventListener('DOMContentLoaded', function() {
                const postId = {{ $post->id }};
                const viewedPosts = JSON.parse(localStorage.getItem('viewed_posts') || '[]');

                if (!viewedPosts.includes(postId)) {
                    fetch(`/blog/${postId}/view`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    }).then(() => {
                        viewedPosts.push(postId);
                        localStorage.setItem('viewed_posts', JSON.stringify(viewedPosts));
                    });
                }
            });
        </script>
    @endpush
</x-app-layout>
