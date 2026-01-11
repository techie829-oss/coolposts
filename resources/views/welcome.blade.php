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
    <div class="relative bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 overflow-hidden">
        <!-- Background Pattern -->
        <div
            class="absolute inset-0 bg-[url('/img/grid.svg')] bg-center [mask-image:linear-gradient(180deg,white,rgba(255,255,255,0))] opacity-20">
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

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <!-- Main Icon -->
                <div
                    class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-r from-blue-400 to-purple-400 rounded-full shadow-2xl mb-8">
                    <i class="fas fa-link text-4xl text-white"></i>
                </div>

                <div class="text-center">
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

                    @php
                        $settings = \App\Models\GlobalSetting::getSettings();
                    @endphp
                    @if (!$settings->isEarningsEnabled())
                        <!-- Earnings Coming Soon Banner -->
                        <div
                            class="relative overflow-hidden bg-slate-800/50 backdrop-blur-md rounded-2xl p-1 mb-10 max-w-2xl mx-auto shadow-2xl border border-slate-700/50 group hover:border-slate-600/50 transition-all duration-300">
                            <div
                                class="absolute inset-0 bg-gradient-to-r from-yellow-500/10 to-orange-500/10 opacity-50">
                            </div>
                            <div class="relative bg-slate-900/80 rounded-xl p-6 sm:p-8">
                                <div class="flex items-center justify-center mb-4">
                                    <div
                                        class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center mr-4 shadow-lg shadow-orange-500/30">
                                        <i class="fas fa-coins text-xl text-white"></i>
                                    </div>
                                    <h3 class="text-xl sm:text-2xl font-bold text-white tracking-wide">Earnings Feature
                                        <span
                                            class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-orange-400">Coming
                                            Soon</span>
                                    </h3>
                                </div>
                                <p class="text-slate-300 text-sm sm:text-base text-center mb-6 leading-relaxed">
                                    We’re building a fair and transparent monetization system.
                                    <span class="font-semibold text-white">Earnings and payouts will be enabled once our
                                        ad
                                        network integration is live.</span>
                                </p>
                                <div class="flex items-center justify-center space-x-2">
                                    <span
                                        class="px-3 py-1 rounded-full bg-slate-800 text-slate-400 text-xs font-medium border border-slate-700">
                                        <i class="fas fa-clock mr-1.5"></i> Stay tuned for updates
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif
                    @guest
                        <div class="flex flex-col sm:flex-row gap-5 justify-center mb-20">
                            <a href="{{ route('register') }}"
                                class="group relative inline-flex items-center justify-center px-8 py-4 text-base font-bold text-white transition-all duration-200 bg-indigo-600 font-pj rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-600/30 hover:shadow-indigo-600/50 hover:-translate-y-1">
                                <i class="fas fa-pen-fancy mr-3"></i>
                                Start Blogging Free
                                <div
                                    class="absolute inset-0 -z-10 rounded-full ring-4 ring-white/10 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                </div>
                            </a>
                            <a href="{{ route('blog.templates') }}"
                                class="inline-flex items-center justify-center px-8 py-4 text-base font-bold text-white transition-all duration-200 bg-gradient-to-r from-green-600 to-teal-600 rounded-full font-pj hover:from-green-700 hover:to-teal-700 hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600 shadow-lg shadow-green-600/30">
                                <i class="fas fa-file-alt mr-3"></i>
                                Browse Templates
                            </a>
                            <a href="{{ route('login') }}"
                                class="inline-flex items-center justify-center px-8 py-4 text-base font-bold text-white transition-all duration-200 bg-slate-800 border border-slate-700 rounded-full font-pj hover:bg-slate-700 hover:border-slate-600 hover:-translate-y-1 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-700">
                                <i class="fas fa-sign-in-alt mr-3"></i>
                                Sign In
                            </a>
                        </div>
                    @else
                        <div class="flex flex-col sm:flex-row gap-6 justify-center mb-16">
                            <a href="{{ route('links.create') }}"
                                class="group relative px-8 py-4 bg-gradient-to-r from-green-500 to-teal-600 text-white font-bold text-lg rounded-full shadow-2xl hover:shadow-green-500/25 transition-all duration-300 transform hover:scale-105 hover:-translate-y-1">
                                <span class="relative z-10 flex items-center justify-center">
                                    <i class="fas fa-plus mr-3 text-yellow-300"></i>
                                    Create New Link
                                </span>
                                <div
                                    class="absolute inset-0 bg-gradient-to-r from-green-600 to-teal-700 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                </div>
                            </a>

                            <a href="{{ route('dashboard') }}"
                                class="px-8 py-4 bg-white/10 backdrop-blur-sm border border-white/20 text-white font-bold text-lg rounded-full hover:bg-white/20 transition-all duration-300 transform hover:scale-105">
                                <i class="fas fa-chart-line mr-3"></i>
                                View Dashboard
                            </a>
                        </div>
                    @endguest

                    <!-- Trust Indicators -->
                    <!-- Trust Indicators -->
                    <div
                        class="grid grid-cols-2 md:grid-cols-4 gap-8 max-w-5xl mx-auto border-t border-slate-700/50 pt-12">
                        <div class="text-center group">
                            <div
                                class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-blue-500/10 text-blue-400 mb-4 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-bolt text-xl"></i>
                            </div>
                            <div class="font-bold text-white mb-1">Fast & Reliable</div>
                            <div class="text-slate-400 text-sm">Platform</div>
                        </div>
                        <div class="text-center group">
                            <div
                                class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-purple-500/10 text-purple-400 mb-4 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-robot text-xl"></i>
                            </div>
                            <div class="font-bold text-white mb-1">AI-Assisted</div>
                            <div class="text-slate-400 text-sm">Writing</div>
                        </div>
                        <div class="text-center group">
                            <div
                                class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-pink-500/10 text-pink-400 mb-4 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-chart-bar text-xl"></i>
                            </div>
                            <div class="font-bold text-white mb-1">Performance</div>
                            <div class="text-slate-400 text-sm">Insights</div>
                        </div>
                        <div class="text-center group">
                            <div
                                class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-teal-500/10 text-teal-400 mb-4 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-lock text-xl"></i>
                            </div>
                            <div class="font-bold text-white mb-1">Secure</div>
                            <div class="text-slate-400 text-sm">Creator-Focused</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Wave Divider -->
            <div class="absolute bottom-0 left-0 right-0">
                <svg class="w-full h-16 text-white" viewBox="0 0 1200 120" preserveAspectRatio="none">
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
        <div class="py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Perfect for Bloggers & Content Creators</h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        CoolPosts is designed for writers, bloggers, and creators who want to publish meaningful
                        content, grow their audience, and prepare for monetization — without distractions.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Feature 1 -->
                    <div
                        class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-indigo-500/10 hover:-translate-y-1 transition-all duration-300 group">
                        <div
                            class="w-14 h-14 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                            <i class="fas fa-pen-nib text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Smart Blogging</h3>
                        <p class="text-slate-600 leading-relaxed">Create and manage blog posts with a clean editor and
                            AI-powered content assistance.</p>
                    </div>

                    <!-- Feature 2 -->
                    <div
                        class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-pink-500/10 hover:-translate-y-1 transition-all duration-300 group">
                        <div
                            class="w-14 h-14 bg-pink-100 text-pink-600 rounded-xl flex items-center justify-center mb-6 group-hover:bg-pink-600 group-hover:text-white transition-colors duration-300">
                            <i class="fas fa-users text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Audience Insights</h3>
                        <p class="text-slate-600 leading-relaxed">Track how readers engage with your content using
                            built-in analytics.</p>
                    </div>

                    <!-- Feature 3 -->
                    <div
                        class="bg-white p-8 rounded-2xl shadow-sm border border-slate-100 hover:shadow-xl hover:shadow-teal-500/10 hover:-translate-y-1 transition-all duration-300 group">
                        <div
                            class="w-14 h-14 bg-teal-100 text-teal-600 rounded-xl flex items-center justify-center mb-6 group-hover:bg-teal-600 group-hover:text-white transition-colors duration-300">
                            <i class="fas fa-rocket text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Growth-Ready Platform</h3>
                        <p class="text-slate-600 leading-relaxed">Designed to support future monetization once earnings
                            features are enabled.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Featured Blog Posts Section -->
        <div class="py-24 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Latest from Our Blog</h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Discover tips, insights, and strategies for bloggers to grow their content, audience, and
                        long-term monetization potential.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @php
                        $featuredPosts = \App\Models\BlogPost::published()->latest('published_at')->take(3)->get();
                    @endphp

                    @forelse($featuredPosts as $post)
                        <div
                            class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 border border-gray-100 overflow-hidden">
                            <a href="{{ route('blog.show', $post->slug) }}"
                                class="block group-hover:opacity-90 transition-opacity relative z-10">
                                @if ($post->featured_image)
                                    <div class="aspect-w-16 aspect-h-9">
                                        <img src="{{ $post->featured_image }}"
                                            alt="{{ $post->title }}" class="w-full h-48 object-cover">
                                    </div>
                                @else
                                    <div
                                        class="w-full h-48 bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center">
                                        <i class="fas fa-newspaper text-4xl text-gray-400"></i>
                                    </div>
                                @endif
                            </a>

                            <div class="p-6 relative z-10">
                                <div class="flex items-center text-sm text-gray-500 mb-3">
                                    <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                        {{ $post->getTypeDisplayName() }}
                                    </span>
                                    <span class="ml-3">{{ $post->published_at->diffForHumans() }}</span>
                                </div>

                                <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2">
                                    <a href="{{ route('blog.show', $post->slug) }}"
                                        class="hover:text-purple-600 transition-colors">
                                        {{ $post->title }}
                                    </a>
                                </h3>

                                <p class="text-gray-600 mb-4 line-clamp-3">
                                    {{ $post->excerpt ?: Str::limit(strip_tags($post->content), 120) }}
                                </p>

                                <div class="flex items-center justify-between">
                                    <div class="flex items-center text-sm text-gray-500">
                                        <i class="fas fa-user mr-2"></i>
                                        {{ $post->user->name }}
                                    </div>
                                    <a href="{{ route('blog.show', $post->slug) }}"
                                        class="text-purple-600 hover:text-purple-700 font-medium text-sm flex items-center relative z-10">
                                        Read More
                                        <i class="fas fa-arrow-right ml-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 text-center py-12">
                            <div
                                class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-newspaper text-2xl text-gray-400"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Blog Posts Yet</h3>
                            <p class="text-gray-600 mb-6">We're working on creating amazing content for you!</p>
                            <a href="{{ route('blog.index') }}"
                                class="inline-flex items-center px-6 py-3 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition-colors relative z-10">
                                <i class="fas fa-newspaper mr-2"></i>
                                Visit Blog
                            </a>
                        </div>
                    @endforelse
                </div>

                @if ($featuredPosts->count() > 0)
                    <div class="text-center mt-12">
                        <a href="{{ route('blog.index') }}"
                            class="inline-flex items-center px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold rounded-full hover:from-purple-700 hover:to-pink-700 transition-all duration-300 transform hover:scale-105 shadow-lg relative z-10">
                            <i class="fas fa-newspaper mr-3"></i>
                            View All Blog Posts
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- How It Works Section -->
        <div class="py-24 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">How It Works</h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Get started in just 3 simple steps to publish content, grow your audience, and enable
                        monetization when it launches.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Step 1 -->
                    <div class="text-center">
                        <div class="relative mb-8">
                            <div
                                class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto text-2xl font-bold text-white">
                                1
                            </div>
                            <div
                                class="absolute -top-2 -right-2 w-8 h-8 bg-yellow-400 rounded-full flex items-center justify-center">
                                <i class="fas fa-pen text-sm text-white"></i>
                            </div>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Write & Publish</h3>
                        <p class="text-gray-600">
                            Create high-quality blog posts using AI-assisted tools and a distraction-free editor.
                        </p>
                    </div>

                    <!-- Step 2 -->
                    <div class="text-center">
                        <div class="relative mb-8">
                            <div
                                class="w-20 h-20 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full flex items-center justify-center mx-auto text-2xl font-bold text-white">
                                2
                            </div>
                            <div
                                class="absolute -top-2 -right-2 w-8 h-8 bg-green-400 rounded-full flex items-center justify-center">
                                <i class="fas fa-users text-sm text-white"></i>
                            </div>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Grow Your Audience</h3>
                        <p class="text-gray-600">
                            Share your content and track engagement with built-in analytics.
                        </p>
                    </div>

                    <!-- Step 3 -->
                    <div class="text-center">
                        <div class="relative mb-8">
                            <div
                                class="w-20 h-20 bg-gradient-to-br from-green-500 to-teal-600 rounded-full flex items-center justify-center mx-auto text-2xl font-bold text-white">
                                3
                            </div>
                            <div
                                class="absolute -top-2 -right-2 w-8 h-8 bg-blue-400 rounded-full flex items-center justify-center">
                                <i class="fas fa-dollar-sign text-sm text-white"></i>
                            </div>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">
                            Enable Monetization
                            <span class="text-sm bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">Coming
                                Soon</span>
                        </h3>
                        <p class="text-gray-600">
                            Once live, you’ll be able to monetize your content through our integrated earning system.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        @guest
            <div class="py-24 relative overflow-hidden">
                <div class="absolute inset-0 bg-slate-900"></div>
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-900/50 to-purple-900/50"></div>
                <div
                    class="absolute inset-0 bg-[url('/img/grid.svg')] bg-center [mask-image:linear-gradient(180deg,white,rgba(255,255,255,0))] opacity-10">
                </div>

                <div class="relative max-w-4xl mx-auto text-center px-4 sm:px-6 lg:px-8">
                    <h2 class="text-4xl font-extrabold text-white mb-6">
                        Ready to Start Blogging?
                    </h2>
                    <p class="text-xl text-blue-100 mb-8">
                        Join creators who are building content today and preparing for future monetization.
                    </p>
                    <a href="{{ route('register') }}"
                        class="inline-flex items-center px-10 py-4 bg-white text-blue-600 font-bold text-lg rounded-full shadow-2xl hover:shadow-white/25 transition-all duration-300 transform hover:scale-105">
                        <i class="fas fa-rocket mr-3"></i>
                        Get Started Free
                    </a>
                    <div class="mt-4 flex flex-col items-center">
                        <p class="text-blue-200 text-sm mb-2">No credit card required</p>
                        <span
                            class="inline-block bg-yellow-400 text-yellow-900 text-xs font-bold px-2 py-1 rounded-full opacity-90">
                            Monetization launching soon
                        </span>
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
