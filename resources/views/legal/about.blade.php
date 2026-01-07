<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('About Us') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Hero Section -->
            <div
                class="relative overflow-hidden bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 rounded-3xl shadow-xl mb-16 px-8 py-20 text-center">
                <div class="absolute inset-0 opacity-20">
                    <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <circle cx="0" cy="0" r="40" fill="white" />
                        <circle cx="100" cy="100" r="40" fill="white" />
                    </svg>
                </div>
                <div class="relative z-10">
                    <h1 class="text-5xl font-extrabold text-white mb-6 tracking-tight drop-shadow-md">About
                        {{ $platformName }}</h1>
                    <p class="text-xl text-blue-50 max-w-3xl mx-auto font-light leading-relaxed">
                        Empowering creators and businesses to monetize their links while providing a seamless sharing
                        experience for their audience.
                    </p>
                </div>
            </div>

            <!-- Mission Section -->
            <div class="bg-white rounded-lg shadow-lg p-8 mb-12">
                <div class="grid md:grid-cols-2 gap-8 items-center">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Our Mission</h2>
                        <p class="text-lg text-gray-700 mb-6">
                            We believe that every link shared should have the potential to generate value. Our platform
                            bridges the gap between content creators and their audience, making link sharing not just
                            convenient, but profitable.
                        </p>
                        <p class="text-gray-600">
                            Whether you're a content creator, marketer, or business owner, {{ $platformName }} provides
                            the tools you need to maximize the impact of your shared content while earning revenue from
                            your efforts.
                        </p>
                    </div>
                    <div class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg p-8 text-white">
                        <h3 class="text-2xl font-bold mb-4">Why Choose {{ $platformName }}?</h3>
                        <ul class="space-y-3">
                            <li class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Advanced Analytics & Insights
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Multiple Monetization Options
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                Secure & Reliable Platform
                            </li>
                            <li class="flex items-center">
                                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd"></path>
                                </svg>
                                24/7 Customer Support
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div class="grid md:grid-cols-3 gap-8 mb-16">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="text-blue-600 mb-4">
                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Link Shortening</h3>
                    <p class="text-gray-600">
                        Create clean, memorable short links that are perfect for social media, marketing campaigns, and
                        easy sharing across all platforms.
                    </p>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="text-green-600 mb-4">
                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Monetization</h3>
                    <p class="text-gray-600">
                        Earn revenue from your links through our flexible monetization options including ads,
                        subscriptions, and referral programs.
                    </p>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="text-purple-600 mb-4">
                        <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                            </path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900 mb-3">Analytics</h3>
                    <p class="text-gray-600">
                        Get detailed insights into your link performance with real-time analytics, click tracking, and
                        audience demographics.
                    </p>
                </div>
            </div>

            <!-- Stats Section -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg shadow-lg p-8 mb-12">
                <div class="grid md:grid-cols-4 gap-8 text-center text-white">
                    <div>
                        <div class="text-3xl font-bold mb-2">1M+</div>
                        <div class="text-blue-100">Links Created</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold mb-2">500K+</div>
                        <div class="text-blue-100">Active Users</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold mb-2">$2M+</div>
                        <div class="text-blue-100">Revenue Generated</div>
                    </div>
                    <div>
                        <div class="text-3xl font-bold mb-2">99.9%</div>
                        <div class="text-blue-100">Uptime</div>
                    </div>
                </div>
            </div>

            <!-- Team Section -->
            <div class="bg-white rounded-lg shadow-lg p-8 mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Our Team</h2>
                <div class="grid md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div
                            class="w-24 h-24 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <span class="text-white text-2xl font-bold">JD</span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">John Doe</h3>
                        <p class="text-gray-600 mb-2">CEO & Founder</p>
                        <p class="text-sm text-gray-500">
                            Passionate about creating innovative solutions that empower creators and businesses.
                        </p>
                    </div>
                    <div class="text-center">
                        <div
                            class="w-24 h-24 bg-gradient-to-br from-green-500 to-blue-600 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <span class="text-white text-2xl font-bold">JS</span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Jane Smith</h3>
                        <p class="text-gray-600 mb-2">CTO</p>
                        <p class="text-sm text-gray-500">
                            Leading our technical vision and ensuring platform reliability and security.
                        </p>
                    </div>
                    <div class="text-center">
                        <div
                            class="w-24 h-24 bg-gradient-to-br from-purple-500 to-pink-600 rounded-full mx-auto mb-4 flex items-center justify-center">
                            <span class="text-white text-2xl font-bold">MJ</span>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Mike Johnson</h3>
                        <p class="text-gray-600 mb-2">Head of Product</p>
                        <p class="text-sm text-gray-500">
                            Focused on delivering exceptional user experiences and innovative features.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Values Section -->
            <div class="bg-white rounded-lg shadow-lg p-8 mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Our Values</h2>
                <div class="grid md:grid-cols-2 gap-8">
                    <div class="flex items-start">
                        <div class="text-blue-600 mr-4 mt-1">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Transparency</h3>
                            <p class="text-gray-600">We believe in open communication and clear, honest relationships
                                with our users and partners.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="text-green-600 mr-4 mt-1">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Security</h3>
                            <p class="text-gray-600">Your data and privacy are our top priorities. We implement
                                industry-leading security measures.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="text-purple-600 mr-4 mt-1">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Innovation</h3>
                            <p class="text-gray-600">We continuously innovate to provide cutting-edge solutions that
                                meet evolving user needs.</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="text-red-600 mr-4 mt-1">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">User-Centric</h3>
                            <p class="text-gray-600">Every decision we make is driven by what's best for our users and
                                their success.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Section -->
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg shadow-lg p-8 text-center text-white">
                <h2 class="text-3xl font-bold mb-4">Ready to Get Started?</h2>
                <p class="text-xl mb-6">Join thousands of creators who are already monetizing their links with
                    {{ $platformName }}.</p>
                <div class="space-x-4">
                    <a href="{{ route('register') }}"
                        class="bg-white text-blue-600 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-200">
                        Get Started Free
                    </a>
                    <a href="{{ route('legal.contact') }}"
                        class="border-2 border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition duration-200">
                        Contact Us
                    </a>
                </div>
            </div>

            <!-- Navigation -->
            <div class="mt-8 text-center">
                <a href="{{ route('legal.terms') }}" class="text-blue-600 hover:text-blue-800 mr-4">Terms of Service</a>
                <a href="{{ route('legal.privacy') }}" class="text-blue-600 hover:text-blue-800 mr-4">Privacy Policy</a>
                <a href="{{ route('legal.faq') }}" class="text-blue-600 hover:text-blue-800 mr-4">FAQ</a>
                <a href="{{ route('legal.contact') }}" class="text-blue-600 hover:text-blue-800">Contact Us</a>
            </div>
        </div>
    </div>
</x-app-layout>