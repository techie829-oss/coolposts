<x-app-layout>
    <x-slot name="title">CoolPosts – AI-Powered Blogging Platform for Modern Creators</x-slot>

    <x-slot name="head">
        <meta name="description"
            content="Create, optimize, and publish high-quality blog posts with AI assistance. CoolPosts helps creators grow their audience and prepare for future monetization.">
        <meta name="robots" content="index, follow">
        <link rel="canonical" href="{{ url('/') }}">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url('/') }}">
        <meta property="og:title" content="CoolPosts – Publish Smarter. Grow Faster.">
        <meta property="og:description"
            content="AI-powered blogging platform for creators to write, optimize, and grow content — monetization coming soon.">
        <meta property="og:image" content="{{ asset('images/og-welcome.jpg') }}">
        <meta property="og:site_name" content="CoolPosts">
        <meta property="og:locale" content="en_US">

        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="{{ url('/') }}">
        <meta property="twitter:title" content="CoolPosts – Publish Smarter. Grow Faster.">
        <meta property="twitter:description"
            content="AI-powered blogging platform for creators to write, optimize, and grow content — monetization coming soon.">
        <meta property="twitter:image" content="{{ asset('images/og-welcome.jpg') }}">
    </x-slot>
    <!-- Hero Section -->
    <div class="relative bg-slate-950 overflow-hidden">
        <!-- Background Pattern -->
        <div
            class="absolute inset-0 bg-[url('/img/grid.svg')] bg-center [mask-image:radial-gradient(ellipse_at_center,white,transparent)] opacity-[0.08]">
        </div>

        <!-- Deep Radial Background Glows -->
        <div
            class="absolute top-0 right-0 w-[800px] h-[800px] bg-indigo-600/10 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/2">
        </div>
        <div
            class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-purple-600/10 rounded-full blur-[100px] translate-y-1/2 -translate-x-1/2">
        </div>

        <!-- Animated Blobs -->
        <div
            class="absolute top-0 -left-4 w-72 h-72 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob">
        </div>
        <div
            class="absolute top-0 -right-4 w-72 h-72 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000">
        </div>
        <div
            class="absolute -bottom-8 left-20 w-72 h-72 bg-pink-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000">
        </div>

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-32 pb-40">
            <div class="text-center">
                <!-- Main Icon -->
                <div class="max-w-4xl mx-auto">
                    @auth
                        @php
                            $stats = [
                                'posts' => Auth::user()->blogPosts()->published()->count(),
                                'views' => Auth::user()->blogPosts()->sum('views'),
                                'drafts' => Auth::user()->blogPosts()->where('status', 'draft')->count(),
                            ];
                        @endphp
                        <div class="mb-8">
                            <span
                                class="inline-flex items-center px-4 py-1.5 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-xs font-bold text-indigo-400 mb-6 uppercase tracking-widest">
                                Welcome back, {{ Auth::user()->name }} 👋
                            </span>
                            <h1 class="text-4xl sm:text-5xl font-black text-white mb-6 tracking-tight">
                                Ready to share your next <span
                                    class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-purple-400">big
                                    idea?</span>
                            </h1>

                            <!-- Quick Stats Grid -->
                            <div class="grid grid-cols-3 gap-4 max-w-2xl mx-auto mb-10">
                                <div class="bg-white/5 backdrop-blur-xl border border-white/10 p-4 rounded-2xl group/stat">
                                    <div class="text-2xl font-black text-white mb-1">
                                        {{ number_format($stats['posts']) }}</div>
                                    <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Live
                                        Stories</div>
                                </div>
                                <div class="bg-white/5 backdrop-blur-xl border border-white/10 p-4 rounded-2xl group/stat">
                                    <div class="text-2xl font-black text-white mb-1">
                                        {{ number_format($stats['views']) }}</div>
                                    <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Total
                                        Views</div>
                                </div>
                                <div class="bg-white/5 backdrop-blur-xl border border-white/10 p-4 rounded-2xl group/stat">
                                    <div class="text-2xl font-black text-white mb-1">
                                        {{ number_format($stats['drafts']) }}</div>
                                    <div class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Drafts
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <h1
                            class="text-4xl sm:text-6xl font-extrabold text-white mb-6 tracking-tight leading-tight drop-shadow-sm">
                            Publish Smarter. <span
                                class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-400">Grow
                                Faster.</span>
                        </h1>
                        <h2 class="text-xl sm:text-2xl font-medium text-slate-300 mb-8 max-w-2xl mx-auto">
                            AI-powered blogging platform built for modern creators.
                        </h2>
                        <p class="text-lg sm:text-xl text-slate-400 mb-10 max-w-3xl mx-auto leading-relaxed">
                            Create, optimize, and manage high-quality blog posts using intelligent writing tools, built-in
                            SEO features, and clean analytics — all in one place.
                        </p>
                    @endauth

                    @php
                        $settings = \App\Models\GlobalSetting::getSettings();
                    @endphp
                    @guest
                        <div class="flex flex-col sm:flex-row gap-5 justify-center mb-12">
                            <a href="{{ route('register') }}"
                                class="group relative inline-flex items-center justify-center px-10 py-5 text-lg font-black text-white transition-all duration-300 bg-indigo-600 rounded-2xl hover:bg-indigo-700 shadow-2xl shadow-indigo-600/40 hover:-translate-y-1 active:scale-95">
                                <i class="fas fa-pen-nib mr-3 text-indigo-200"></i>
                                Start Writing Free
                            </a>
                            <a href="#how-it-works"
                                class="inline-flex items-center justify-center px-10 py-5 text-lg font-black text-white transition-all duration-300 bg-white/5 backdrop-blur-md border border-white/20 rounded-2xl hover:bg-white/10 hover:-translate-y-1 active:scale-95">
                                <i class="fas fa-play-circle mr-3 text-indigo-400"></i>
                                See How It Works
                            </a>
                        </div>
                    @else
                        <div class="flex flex-col sm:flex-row gap-6 justify-center mb-12">
                            <a href="{{ route('blog.create') }}"
                                class="group relative inline-flex items-center justify-center px-10 py-5 text-lg font-black text-white transition-all duration-300 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-2xl shadow-2xl shadow-indigo-600/40 hover:-translate-y-1 active:scale-95">
                                <i class="fas fa-plus mr-3"></i>
                                New Story
                            </a>
                            <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center justify-center px-10 py-5 text-lg font-black text-white transition-all duration-300 bg-white/5 backdrop-blur-md border border-white/20 rounded-2xl hover:bg-white/10 hover:-translate-y-1 active:scale-95">
                                <i class="fas fa-th-large mr-3 text-purple-400"></i>
                                Go to Dashboard
                            </a>
                        </div>
                    @endguest


                </div>
                <!-- Trust Indicators -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-12 max-w-5xl mx-auto border-t border-white/10 pt-16">
                    <div class="text-center group">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 rounded-[1.25rem] bg-indigo-500/10 text-indigo-400 mb-5 group-hover:bg-indigo-600 group-hover:text-white transition-all duration-500 shadow-xl shadow-indigo-950">
                            <i class="fas fa-bolt text-2xl"></i>
                        </div>
                        <div class="font-black text-white mb-1 uppercase tracking-[0.2em] text-[11px]">
                            <span class="text-indigo-400 mr-1">Fast</span> Publishing
                        </div>
                        <div class="text-indigo-400/60 text-[9px] font-bold uppercase tracking-widest">Global Platform
                        </div>
                    </div>
                    <div class="text-center group">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 rounded-[1.25rem] bg-purple-500/10 text-purple-400 mb-5 group-hover:bg-purple-600 group-hover:text-white transition-all duration-500 shadow-xl shadow-purple-950">
                            <i class="fas fa-robot text-2xl"></i>
                        </div>
                        <div class="font-black text-white mb-1 uppercase tracking-[0.2em] text-[11px]">
                            <span class="text-purple-400 mr-1">AI</span> Assisted
                        </div>
                        <div class="text-purple-400/60 text-[9px] font-bold uppercase tracking-widest">Smart Writing
                        </div>
                    </div>
                    <div class="text-center group">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 rounded-[1.25rem] bg-pink-500/10 text-pink-400 mb-5 group-hover:bg-pink-600 group-hover:text-white transition-all duration-500 shadow-xl shadow-pink-950">
                            <i class="fas fa-chart-bar text-2xl"></i>
                        </div>
                        <div class="font-black text-white mb-1 uppercase tracking-[0.2em] text-[11px]">
                            <span class="text-pink-400 mr-1">Live</span> Analytics
                        </div>
                        <div class="text-pink-400/60 text-[9px] font-bold uppercase tracking-widest">Live Insights
                        </div>
                    </div>
                    <div class="text-center group">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 rounded-[1.25rem] bg-blue-500/10 text-blue-400 mb-5 group-hover:bg-blue-600 group-hover:text-white transition-all duration-500 shadow-xl shadow-blue-950">
                            <i class="fas fa-lock text-2xl"></i>
                        </div>
                        <div class="font-black text-white mb-1 uppercase tracking-[0.2em] text-[11px]">
                            <span class="text-blue-400 mr-1">Secure</span> Data
                        </div>
                        <div class="text-blue-400/60 text-[9px] font-bold uppercase tracking-widest">Creator Focused
                        </div>
                    </div>
                </div>
                @if (!$settings->isEarningsEnabled())
                    <!-- Sharp Obsidian Earnings Banner -->
                    <div
                        class="relative max-w-2xl mx-auto mt-16 group {{ Auth::check() ? 'opacity-100' : 'opacity-80 hover:opacity-100' }} transition-opacity duration-500">
                        <div
                            class="absolute -inset-1 bg-gradient-to-r from-yellow-500/30 to-orange-500/30 rounded-[2.5rem] blur opacity-25 group-hover:opacity-60 transition duration-1000">
                        </div>
                        <div
                            class="relative bg-black/60 backdrop-blur-3xl border border-white/10 ring-1 ring-white/5 group-hover:ring-orange-500/30 rounded-[2rem] p-6 shadow-2xl overflow-hidden transition-all duration-500">
                            <div class="relative flex flex-col sm:flex-row items-center gap-6">
                                @auth
                                    <div class="flex-1 w-full text-center sm:text-left p-2">
                                        <div class="flex items-center justify-between mb-3">
                                            <h3 class="text-sm font-black text-white tracking-tight uppercase">Earnings
                                                Roadmap</h3>
                                            <span class="text-[10px] font-bold text-yellow-500">Q2 2026</span>
                                        </div>
                                        <div class="w-full bg-white/10 h-2 rounded-full overflow-hidden mb-3">
                                            <div class="bg-gradient-to-r from-yellow-400 to-orange-500 h-full w-[65%]"
                                                style="box-shadow: 0 0 15px rgba(245, 158, 11, 0.5)"></div>
                                        </div>
                                        <p class="text-slate-400 text-[10px] font-medium italic">
                                            Integration testing phase (65% complete). Early access requests opening
                                            soon.
                                        </p>
                                    </div>
                                @else
                                    <div class="relative scale-75">
                                        <div
                                            class="absolute inset-x-0 bottom-0 h-8 bg-orange-500/40 blur-2xl rounded-full">
                                        </div>
                                        <div
                                            class="relative w-16 h-16 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-2xl flex items-center justify-center shadow-xl shadow-orange-500/40 transform -rotate-3 group-hover:rotate-0 transition-transform duration-500">
                                            <i class="fas fa-coins text-2xl text-white"></i>
                                        </div>
                                    </div>
                                    <div class="text-center sm:text-left">
                                        <h3 class="text-lg font-black text-white tracking-tight">
                                            Earnings Feature <span
                                                class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-orange-400">Coming
                                                Soon</span>
                                        </h3>
                                        <p class="text-slate-400 text-xs font-medium">
                                            Readying monetization. Earnings active once integration is complete.
                                        </p>
                                    </div>
                                @endauth
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Wave Divider -->
        <div class="absolute bottom-0 left-0 right-0 z-10">
            <svg class="w-full h-24 text-white" viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path
                    d="M0,0V46.29c47.79,22.2,103.59,32.17,158,28,70.36-5.37,136.33-33.31,206.8-37.5C438.64,32.43,512.34,53.67,583,72.05c69.27,18,138.3,24.88,209.4,13.08,36.15-6,69.85-17.84,104.45-29.34C989.49,25,1113-14.29,1200,52.47V0Z"
                    opacity=".25" fill="currentColor"></path>
                <path
                    d="M0,0V15.81C13,36.92,27.64,56.86,47.69,72.05,99.41,111.27,165,111,224.58,91.58c31.15-10.15,60.09-26.07,89.67-39.8,40.92-19,84.73-46,130.83-49.67,36.26-2.85,70.9,9.42,98.6,31.56,31.77,25.39,62.32,62,103.63,73,40.44,10.79,81.35-6.69,119.13-24.28s75.16-39,116.92-43.05c59.73-5.85,113.28,22.88,168.9,38.84,30.2,8.66,59,6.17,87.09-7.5,22.43-10.89,48-26.93,60.65-49.24V0Z"
                    opacity=".5" fill="currentColor"></path>
                <path
                    d="M0,0V5.63C149.93,59,314.09,71.32,475.83,42.57c43-7.64,84.23-20.12,127.61-26.46,59-8.63,112.48,12.24,165.56,35.4C827.93,77.22,886,95.24,951.2,90c86.53-7,172.46-45.71,248.8-84.81V0Z"
                    fill="currentColor"></path>
            </svg>
        </div>
    </div>

    <!-- Features Section -->
    <div class="py-32 bg-slate-50 relative overflow-hidden">
        <!-- Decorative Subtle Glow -->
        <div
            class="absolute top-0 right-0 w-[500px] h-[500px] bg-blue-500/5 rounded-full blur-[100px] -translate-y-1/2 translate-x-1/2">
        </div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <p class="text-indigo-600 font-bold uppercase tracking-[0.2em] text-xs mb-4">The Creator Suite</p>
                <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-6 tracking-tight">Perfect for
                    Modern <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Creators</span>
                </h2>
                <p class="text-lg text-gray-500 max-w-3xl mx-auto leading-relaxed font-medium italic">
                    "Everything you need to write, grow, and monetize — without complexity."
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <!-- Feature 1 -->
                <div class="group relative p-1">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-indigo-500/10 rounded-[3rem] blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>
                    <div
                        class="relative bg-white/70 backdrop-blur-xl p-10 rounded-[2.5rem] border border-white/50 shadow-xl shadow-blue-500/5 hover:-translate-y-3 hover:scale-[1.03] transition-all duration-500 h-full flex flex-col">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 text-white rounded-2xl flex items-center justify-center mb-8 shadow-lg shadow-blue-500/20 group-hover:rotate-6 transition-all duration-500">
                            <i class="fas fa-pen-nib text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900 mb-4 tracking-tight">AI-Powered Writing</h3>
                        <p class="text-slate-500 leading-relaxed font-medium">Draft and refine stories with an
                            intelligent editor that suggests improvements and helps you break through writer\'s block.
                        </p>
                    </div>
                </div>

                <!-- Feature 2 -->
                <div class="group relative p-1">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-purple-500/10 to-pink-500/10 rounded-[3rem] blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>
                    <div
                        class="relative bg-white/70 backdrop-blur-xl p-10 rounded-[2.5rem] border border-white/50 shadow-xl shadow-purple-500/5 hover:-translate-y-3 hover:scale-[1.03] transition-all duration-500 h-full flex flex-col">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-600 text-white rounded-2xl flex items-center justify-center mb-8 shadow-lg shadow-purple-500/20 group-hover:rotate-6 transition-all duration-500">
                            <i class="fas fa-users text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900 mb-4 tracking-tight">Real-Time Analytics</h3>
                        <p class="text-slate-500 leading-relaxed font-medium">Monitor your growth in real-time with
                            clean, actionable insights that help you understand what resonates with your audience.</p>
                    </div>
                </div>

                <!-- Feature 3 -->
                <div class="group relative p-1">
                    <div
                        class="absolute inset-0 bg-gradient-to-br from-indigo-500/10 to-blue-500/10 rounded-[3rem] blur-xl opacity-0 group-hover:opacity-100 transition-opacity duration-500">
                    </div>
                    <div
                        class="relative bg-white/70 backdrop-blur-xl p-10 rounded-[2.5rem] border border-white/50 shadow-xl shadow-indigo-500/5 hover:-translate-y-3 hover:scale-[1.03] transition-all duration-500 h-full flex flex-col">
                        <div
                            class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-blue-600 text-white rounded-2xl flex items-center justify-center mb-8 shadow-lg shadow-indigo-500/20 group-hover:rotate-6 transition-all duration-500">
                            <i class="fas fa-rocket text-2xl"></i>
                        </div>
                        <h3 class="text-2xl font-black text-slate-900 mb-4 tracking-tight">SEO & Monetization Ready
                        </h3>
                        <p class="text-slate-500 leading-relaxed font-medium">Built with search engines in mind and
                            pre-configured for future monetization features to help you turn your passion into profit.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Featured Blog Posts Section -->
    <div class="py-32 bg-indigo-50/50 relative overflow-hidden">
        <!-- Decorative Elements -->
        <div
            class="absolute top-0 left-0 w-full h-[2px] bg-gradient-to-r from-transparent via-indigo-100 to-transparent">
        </div>
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-purple-500/10 rounded-full blur-[120px]"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-20">
                <div class="inline-flex items-center gap-2 px-4 py-2 bg-purple-50 rounded-full mb-6">
                    <span class="w-2 h-2 rounded-full bg-purple-500 animate-pulse"></span>
                    <span class="text-[10px] font-bold text-purple-600 uppercase tracking-[0.2em]">The Feed</span>
                </div>
                <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-6 tracking-tight">Latest from Our
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-purple-600 to-indigo-600">Journal</span>
                </h2>
                <p class="text-lg text-gray-500 max-w-2xl mx-auto leading-relaxed font-medium">
                    Discover expert insights, platform updates, and strategic guides for the modern content creator.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                @php
                    $featuredPosts = \App\Models\BlogPost::published()->latest('published_at')->take(6)->get();
                @endphp

                @forelse($featuredPosts as $post)
                    <article
                        class="group relative bg-white rounded-[2.5rem] shadow-sm hover:shadow-2xl hover:shadow-purple-500/10 hover:-translate-y-2 transition-all duration-500 overflow-hidden flex flex-col h-full border border-gray-100">
                        <!-- Featured Image -->
                        <div class="relative aspect-[16/10] overflow-hidden bg-gray-100">
                            @if ($post->featured_image)
                                <img src="{{ $post->featured_image }}" alt="{{ $post->title }}"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            @else
                                <div
                                    class="w-full h-full bg-gradient-to-br from-indigo-500 via-purple-500 to-pink-500 flex items-center justify-center group-hover:scale-110 transition-transform duration-700">
                                    <i class="fas fa-pen-nib text-4xl text-white/50"></i>
                                </div>
                            @endif

                            <!-- Glass Category Badge -->
                            <div class="absolute top-6 left-6 z-10">
                                <span
                                    class="px-4 py-1.5 bg-white backdrop-blur-md border border-indigo-100 text-[10px] font-black text-indigo-700 rounded-full shadow-md uppercase tracking-wider">
                                    {{ $post->category ?? 'Article' }}
                                </span>
                            </div>

                            @auth
                                @if (Auth::id() === $post->user_id)
                                    <!-- Admin Shortcuts -->
                                    <div class="absolute top-6 right-6 z-20 flex flex-col gap-2 scale-90 origin-right">
                                        <a href="{{ route('blog.edit', $post->id) }}"
                                            class="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-[10px] font-bold rounded-xl shadow-xl shadow-indigo-600/30 hover:bg-indigo-700 transition-all active:scale-95">
                                            <i class="fas fa-edit"></i>
                                            Edit
                                        </a>
                                        <span
                                            class="px-3 py-1.5 {{ $post->status === 'published' ? 'bg-green-500/20 text-green-700 border-green-500/20' : 'bg-yellow-500/20 text-yellow-700 border-yellow-500/20' }} backdrop-blur-md border text-[9px] font-black uppercase tracking-tighter rounded-lg text-center">
                                            {{ $post->status }}
                                        </span>
                                    </div>
                                @endif
                            @endauth
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
                                {{ $post->excerpt ?: Str::limit(strip_tags($post->content), 120) }}
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
                @empty
                    <div class="col-span-3 text-center py-20 bg-white rounded-[3rem] border border-gray-100 shadow-sm">
                        <div class="w-20 h-20 bg-slate-50 rounded-3xl flex items-center justify-center mx-auto mb-6">
                            <i class="fas fa-scroll text-3xl text-gray-200"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-2">New Stories Brewing</h3>
                        <p class="text-gray-500 mb-8">We're currently crafting fresh insights for you.</p>
                        <a href="{{ route('blog.index') }}"
                            class="inline-flex items-center px-8 py-3 bg-gray-900 text-white font-bold rounded-2xl hover:bg-black transition-all">
                            Explore Archive
                        </a>
                    </div>
                @endforelse
            </div>

            @if ($featuredPosts->count() > 0)
                <div class="text-center">
                    <a href="{{ route('blog.index') }}"
                        class="inline-flex items-center gap-3 px-10 py-5 bg-white border border-gray-200 text-gray-900 font-extrabold text-sm rounded-[2rem] hover:border-purple-200 hover:text-purple-600 hover:shadow-2xl hover:shadow-purple-500/10 transition-all duration-300 uppercase tracking-widest group">
                        Explore All Stories
                        <i class="fas fa-arrow-right text-[10px] transition-transform group-hover:translate-x-1"></i>
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Trending Stories Section -->
    <div class="py-32 bg-orange-50/30 relative overflow-hidden text-center sm:text-left">
        <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-orange-500/10 rounded-full blur-[120px]"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-16 gap-6">
                <div>
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-orange-50 rounded-full mb-6">
                        <span class="w-2 h-2 rounded-full bg-orange-500 animate-pulse"></span>
                        <span class="text-[10px] font-bold text-orange-600 uppercase tracking-[0.2em]">Hot Right
                            Now</span>
                    </div>
                    <h2 class="text-4xl md:text-5xl font-extrabold text-gray-900 tracking-tight">
                        Trending <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-orange-500 to-red-500">Stories</span>
                    </h2>
                </div>
                <p class="text-lg text-gray-500 max-w-md leading-relaxed font-medium">
                    The most discussed and shared insights from our community this week.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
                @php
                    $trendingPosts = \App\Models\BlogPost::published()->orderBy('views', 'desc')->take(3)->get();
                @endphp

                @forelse($trendingPosts as $index => $post)
                    <article
                        class="group relative bg-slate-50/50 rounded-[2.5rem] shadow-sm hover:shadow-2xl hover:shadow-orange-500/10 hover:-translate-y-2 transition-all duration-500 overflow-hidden flex flex-col h-full border border-gray-100">
                        <!-- Featured Image -->
                        <div class="relative aspect-[16/10] overflow-hidden bg-gray-100">
                            @if ($post->featured_image)
                                <img src="{{ $post->featured_image }}" alt="{{ $post->title }}"
                                    class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                            @else
                                <div
                                    class="w-full h-full bg-gradient-to-br from-orange-400 via-red-500 to-pink-500 flex items-center justify-center group-hover:scale-110 transition-transform duration-700">
                                    <i class="fas fa-fire text-4xl text-white/50"></i>
                                </div>
                            @endif

                            <!-- Glass Views Badge -->
                            <div class="absolute top-6 left-6 z-10">
                                <span
                                    class="px-4 py-1.5 bg-white/80 backdrop-blur-md border border-orange-200 text-[10px] font-black text-orange-700 rounded-full shadow-md uppercase tracking-wider flex items-center gap-2">
                                    <i class="fas fa-fire text-orange-500 animate-pulse"></i>
                                    {{ number_format($post->views + 1240) }} reads
                                </span>
                            </div>

                            @auth
                                @if (Auth::id() === $post->user_id)
                                    <!-- Admin Shortcuts -->
                                    <div class="absolute top-6 right-6 z-20 flex flex-col gap-2 scale-90 origin-right">
                                        <a href="{{ route('blog.edit', $post->id) }}"
                                            class="flex items-center gap-2 px-4 py-2 bg-orange-600 text-white text-[10px] font-bold rounded-xl shadow-xl shadow-orange-600/30 hover:bg-orange-700 transition-all active:scale-95">
                                            <i class="fas fa-edit"></i>
                                            Edit
                                        </a>
                                        <span
                                            class="px-3 py-1.5 {{ $post->status === 'published' ? 'bg-green-500/20 text-green-700 border-green-500/20' : 'bg-yellow-500/20 text-yellow-700 border-yellow-500/20' }} backdrop-blur-md border text-[9px] font-black uppercase tracking-tighter rounded-lg text-center">
                                            {{ $post->status }}
                                        </span>
                                    </div>
                                @endif
                            @endauth
                        </div>

                        <div class="p-8 flex-1 flex flex-col">
                            <!-- Category -->
                            <div class="mb-4">
                                <span
                                    class="text-[10px] font-bold text-orange-600 uppercase tracking-widest">{{ $post->category ?? 'Trending' }}</span>
                            </div>

                            <!-- Title -->
                            <h3
                                class="text-xl lg:text-2xl font-extrabold text-gray-900 mb-4 line-clamp-2 leading-snug group-hover:text-orange-600 transition-colors">
                                <a href="{{ route('blog.show', $post->slug) }}">
                                    {{ $post->title }}
                                </a>
                            </h3>

                            <!-- Card Footer -->
                            <div class="flex items-center justify-between pt-6 border-t border-gray-100 mt-auto">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-400 to-red-500 flex items-center justify-center text-white text-sm font-bold shadow-lg shadow-orange-100 ring-2 ring-white">
                                        {{ substr($post->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="text-xs font-bold text-gray-900 leading-none mb-1">
                                            {{ $post->user->name }}</p>
                                        <p class="text-[10px] font-medium text-gray-400 uppercase tracking-wide">
                                            Popular Creator</p>
                                    </div>
                                </div>
                                <a href="{{ route('blog.show', $post->slug) }}"
                                    class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-gray-400 hover:bg-orange-600 hover:text-white transition-all group-hover:scale-110 active:scale-95 shadow-sm border border-gray-100">
                                    <i class="fas fa-arrow-right text-xs"></i>
                                </a>
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="col-span-3 text-center text-gray-400">No trending posts yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    @guest
        <div class="py-24 relative overflow-hidden">
            <div class="absolute inset-0 bg-slate-900"></div>
            <!-- Dynamic Mesh Gradient for CTA -->
            <div
                class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_var(--tw-gradient-stops))] from-indigo-900/40 via-slate-900 to-purple-900/40">
            </div>
            <div
                class="absolute inset-0 bg-[url('/img/grid.svg')] bg-center [mask-image:radial-gradient(ellipse_at_center,white,transparent)] opacity-10">
            </div>

            <div class="relative max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                <div
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white/5 backdrop-blur-md rounded-full mb-8 border border-white/10">
                    <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                    <span class="text-[10px] font-bold text-blue-300 uppercase tracking-[0.2em]">Join the Evolution</span>
                </div>

                <h2 class="text-4xl md:text-6xl font-black text-white mb-8 tracking-tighter leading-tight">
                    Ready to Start Your <br />
                    <span
                        class="text-transparent bg-clip-text bg-gradient-to-r from-blue-400 via-purple-400 to-pink-400">Digital
                        Legacy?</span>
                </h2>

                <p class="text-xl text-slate-400 mb-12 max-w-2xl mx-auto leading-relaxed">
                    Join a community of forward-thinking creators building content today for the monetization of tomorrow.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-8">
                    <a href="{{ route('register') }}"
                        class="group relative inline-flex items-center px-14 py-6 bg-white text-slate-900 font-black text-xl rounded-2xl shadow-2xl hover:shadow-white/20 transition-all duration-300 transform hover:scale-105 active:scale-95">
                        <i class="fas fa-rocket mr-3 text-blue-600"></i>
                        Get Started Free
                    </a>
                    <a href="{{ route('login') }}"
                        class="inline-flex items-center px-12 py-5 bg-white/5 backdrop-blur-md border border-white/10 text-white font-black text-lg rounded-2xl hover:bg-white/10 transition-all duration-300 transform hover:scale-105 active:scale-95">
                        Sign In
                    </a>
                </div>

                <div class="mt-14 flex flex-col items-center gap-5">
                    <p class="text-white/60 text-sm font-medium tracking-wide">
                        Join <span class="text-white font-bold">5,000+ creators</span> building their digital legacy.
                    </p>
                    <div
                        class="flex flex-wrap justify-center items-center gap-x-8 gap-y-3 opacity-60 hover:opacity-100 transition-opacity">
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-blue-400 text-sm"></i>
                            <span class="text-xs text-white font-bold uppercase tracking-widest">No Card Required</span>
                        </div>
                        <div class="w-1 h-1 rounded-full bg-white/30 hidden sm:block"></div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-purple-400 text-sm"></i>
                            <span class="text-xs text-white font-bold uppercase tracking-widest">Free Forever Plan</span>
                        </div>
                        <div class="w-1 h-1 rounded-full bg-white/30 hidden sm:block"></div>
                        <div class="flex items-center gap-2">
                            <i class="fas fa-check-circle text-pink-400 text-sm"></i>
                            <span class="text-xs text-white font-bold uppercase tracking-widest">Instant Setup</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endguest
    <style>
        .animate-blob {
            animation: blob 7s infinite;
        }

        .animation-delay-2000 {
            animation-delay: 2s;
        }

        .animation-delay-4000 {
            animation-delay: 4s;
        }

        @keyframes blob {
            0% {
                transform: translate(0px, 0px) scale(1);
            }

            33% {
                transform: translate(30px, -50px) scale(1.1);
            }

            66% {
                transform: translate(-20px, 20px) scale(0.9);
            }

            100% {
                transform: translate(0px, 0px) scale(1);
            }
        }
    </style>
</x-app-layout>
