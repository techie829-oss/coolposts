<x-app-layout>
    <x-slot name="title">How It Works - CoolPosts | Blogging Platform</x-slot>
    <x-slot name="meta">
        <meta name="description"
            content="Learn how CoolPosts works - from creating your blog to growing your audience. Discover our AI-powered publishing tools and future monetization features.">
        <meta name="keywords"
            content="how it works, blogging platform, content creation, AI writing, audience growth, CoolPosts guide">
        <meta property="og:title" content="How It Works - CoolPosts | Blogging Platform">
        <meta property="og:description"
            content="Learn how CoolPosts works - from creating your blog to growing your audience. Discover our AI-powered publishing tools and future monetization features.">
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ route('blog.how-we-work') }}">
        <meta property="og:site_name" content="CoolPosts">
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:title" content="How It Works - CoolPosts | Blogging Platform">
        <meta property="twitter:description"
            content="Learn how CoolPosts works - from creating your blog to growing your audience. Discover our AI-powered publishing tools and future monetization features.">
    </x-slot>

    <!-- Hero Section -->
    <div class="relative bg-zinc-900 py-24 sm:py-32 overflow-hidden">
        <div class="absolute inset-0">
            <div class="absolute inset-0 bg-gradient-to-br from-zinc-900 via-zinc-800 to-zinc-900 opacity-90"></div>
            <div
                class="absolute inset-0 bg-[url('/img/grid.svg')] bg-center [mask-image:linear-gradient(180deg,white,rgba(255,255,255,0))]">
            </div>
        </div>

        <div class="relative max-w-7xl mx-auto px-6 lg:px-8 text-center z-10">
            <h1 class="text-4xl font-bold tracking-tight text-white sm:text-6xl mb-6">
                How CoolPosts Works
            </h1>
            <p class="mt-6 text-lg leading-8 text-zinc-300 max-w-2xl mx-auto">
                Simple, powerful, and built for creators. See how easy it is to start your professional blog and grow
                your audience today.
            </p>
            <div class="mt-10 flex items-center justify-center gap-x-6">
                <a href="{{ route('register') }}"
                    class="rounded-md bg-white px-6 py-3 text-sm font-semibold text-zinc-900 shadow-sm hover:bg-zinc-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white transition-all duration-200">
                    Start Blogging Now
                </a>
                <a href="#steps"
                    class="text-sm font-semibold leading-6 text-white hover:text-zinc-300 transition-colors">
                    Learn more <span aria-hidden="true">→</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Steps Section -->
    <div id="steps" class="py-24 sm:py-32 bg-white">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="mx-auto max-w-2xl lg:text-center">
                <h2 class="text-base font-semibold leading-7 text-indigo-600">The Process</h2>
                <p class="mt-2 text-3xl font-bold tracking-tight text-zinc-900 sm:text-4xl">
                    From Idea to Audience in 3 Steps
                </p>
                <p class="mt-6 text-lg leading-8 text-zinc-600">
                    We've streamlined the blogging process so you can focus on what matters most: creating great
                    content.
                </p>
            </div>

            <div class="mx-auto mt-16 max-w-2xl sm:mt-20 lg:mt-24 lg:max-w-none">
                <dl class="grid max-w-xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-3">
                    <!-- Step 1 -->
                    <div class="flex flex-col items-center text-center">
                        <div
                            class="mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-zinc-900 text-white shadow-lg">
                            <i class="fas fa-user-plus text-2xl"></i>
                        </div>
                        <dt class="text-xl font-bold leading-7 text-zinc-900">
                            1. Create Your Account
                        </dt>
                        <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-zinc-600">
                            <p class="flex-auto">Sign up in seconds. Setup your profile and get access to your personal
                                dashboard and writing tools immediately.</p>
                        </dd>
                    </div>

                    <!-- Step 2 -->
                    <div class="flex flex-col items-center text-center">
                        <div
                            class="mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-zinc-900 text-white shadow-lg">
                            <i class="fas fa-pen-fancy text-2xl"></i>
                        </div>
                        <dt class="text-xl font-bold leading-7 text-zinc-900">
                            2. Write & Publish
                        </dt>
                        <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-zinc-600">
                            <p class="flex-auto">Use our AI-assisted editor to craft engaging posts. Optimize for SEO
                                with built-in tools and publish to the world.</p>
                        </dd>
                    </div>

                    <!-- Step 3 -->
                    <div class="flex flex-col items-center text-center">
                        <div
                            class="mb-6 flex h-16 w-16 items-center justify-center rounded-2xl bg-zinc-900 text-white shadow-lg">
                            <i class="fas fa-chart-line text-2xl"></i>
                        </div>
                        <dt class="text-xl font-bold leading-7 text-zinc-900">
                            3. Grow & Prepare
                        </dt>
                        <dd class="mt-4 flex flex-auto flex-col text-base leading-7 text-zinc-600">
                            <p class="flex-auto">Build your readership and track your stats. Get ready for our upcoming
                                monetization program to earn from your success.</p>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="relative isolate overflow-hidden bg-zinc-900 py-16 sm:py-24 lg:py-32">
        <div class="mx-auto max-w-7xl px-6 lg:px-8">
            <div class="mx-auto grid max-w-2xl grid-cols-1 gap-x-8 gap-y-16 lg:max-w-none lg:grid-cols-2">
                <div class="max-w-xl lg:max-w-lg">
                    <h2 class="text-3xl font-bold tracking-tight text-white sm:text-4xl">Ready to Start Blogging?</h2>
                    <p class="mt-4 text-lg leading-8 text-zinc-300">
                        Join our community of creators today. No credit card required.
                    </p>
                    <div class="mt-6 flex max-w-md gap-x-4">
                        <a href="{{ route('register') }}"
                            class="flex-none rounded-md bg-white px-6 py-3.5 text-sm font-semibold text-zinc-900 shadow-sm hover:bg-zinc-200 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-white transition-all duration-200">
                            Get Started Free
                        </a>
                    </div>
                    <div class="mt-8 flex items-center gap-x-4">
                        <div class="flex items-center gap-x-2">
                            <div class="h-2 w-2 rounded-full bg-green-400"></div>
                            <span class="text-sm text-zinc-400">Free to join</span>
                        </div>
                        <div class="flex items-center gap-x-2">
                            <div class="h-2 w-2 rounded-full bg-blue-400"></div>
                            <span class="text-sm text-zinc-400">AI-Powered</span>
                        </div>
                        <div class="flex items-center gap-x-2">
                            <div class="h-2 w-2 rounded-full bg-amber-400"></div>
                            <span class="text-sm text-zinc-400">Coming Soon</span>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-x-8 gap-y-10 sm:grid-cols-2 lg:pt-2">
                    <div class="flex flex-col items-start bg-white/5 rounded-2xl p-6 ring-1 ring-white/10">
                        <div class="rounded-md bg-zinc-900 p-2 ring-1 ring-white/10">
                            <i class="fas fa-rocket text-white"></i>
                        </div>
                        <dt class="mt-4 font-semibold text-white">Instant Setup</dt>
                        <dd class="mt-2 leading-7 text-zinc-400">Start writing your first post in minutes.</dd>
                    </div>
                    <div class="flex flex-col items-start bg-white/5 rounded-2xl p-6 ring-1 ring-white/10">
                        <div class="rounded-md bg-zinc-900 p-2 ring-1 ring-white/10">
                            <i class="fas fa-shield-alt text-white"></i>
                        </div>
                        <dt class="mt-4 font-semibold text-white">Secure Platform</dt>
                        <dd class="mt-2 leading-7 text-zinc-400">Your content and data are always protected.</dd>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>