<x-guest-layout>
    <x-slot name="title">Login - CoolPosts | Sign In to Your Account</x-slot>

    <x-slot name="head">
        <meta name="description" content="Sign in to your CoolPosts account to manage your links, track analytics, and access all features. Secure login for link monetization platform.">
        <meta name="keywords" content="login, sign in, CoolPosts, account access, link management, user authentication">
        <meta name="robots" content="noindex, nofollow">
        <link rel="canonical" href="{{ url('/login') }}">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url('/login') }}">
        <meta property="og:title" content="Login - CoolPosts | Sign In to Your Account">
        <meta property="og:description" content="Sign in to your CoolPosts account to manage your links, track analytics, and access all features. Secure login for link monetization platform.">
        <meta property="og:image" content="{{ asset('images/og-login.jpg') }}">
        <meta property="og:site_name" content="CoolPosts">
        <meta property="og:locale" content="en_US">

        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="{{ url('/login') }}">
        <meta property="twitter:title" content="Login - CoolPosts | Sign In to Your Account">
        <meta property="twitter:description" content="Sign in to your CoolPosts account to manage your links, track analytics, and access all features. Secure login for link monetization platform.">
        <meta property="twitter:image" content="{{ asset('images/og-login.jpg') }}">
    </x-slot>

    <!-- Main Content Container -->
    <div class="relative z-10 w-full h-full flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <!-- Login Card - Full Width Responsive -->
        <div class="w-full max-w-md mx-auto">
            <!-- Logo & Branding -->
            <div class="text-center mb-12">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-purple-500 via-pink-500 to-red-500 rounded-3xl shadow-2xl mb-6 transform hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-link text-3xl text-white"></i>
                </div>
                <h1 class="text-4xl font-extrabold text-white mb-2 tracking-tight">
                    CoolPosts
                </h1>
                <p class="text-purple-200 text-lg font-medium">
                    Link Monetization Platform
                </p>
            </div>

            <!-- Login Card -->
            <div class="bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8">
                <!-- Header -->
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-white mb-2">Welcome Back</h2>
                    <p class="text-purple-100">Sign in to your account</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-6 p-4 bg-green-500/20 border border-green-500/30 rounded-2xl text-green-200 backdrop-blur-sm" :status="session('status')" />

                <!-- Login Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email Field -->
                    <div class="group">
                        <label for="email" class="block text-sm font-semibold text-purple-100 mb-3">Email Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-purple-300 group-focus-within:text-purple-400 transition-colors duration-200"></i>
                            </div>
                            <input id="email"
                                   type="email"
                                   name="email"
                                   :value="old('email')"
                                   required
                                   autofocus
                                   autocomplete="username"
                                   placeholder="Enter your email"
                                   class="w-full pl-12 pr-4 py-4 bg-white/10 border border-white/20 rounded-2xl text-white placeholder-purple-200/60 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 backdrop-blur-sm">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-300" />
                    </div>

                    <!-- Password Field -->
                    <div class="group">
                        <label for="password" class="block text-sm font-semibold text-purple-100 mb-3">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-lock text-purple-300 group-focus-within:text-purple-400 transition-colors duration-200"></i>
                            </div>
                            <input id="password"
                                   type="password"
                                   name="password"
                                   required
                                   autocomplete="current-password"
                                   placeholder="Enter your password"
                                   class="w-full pl-12 pr-4 py-4 bg-white/10 border border-white/20 rounded-2xl text-white placeholder-purple-200/60 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 backdrop-blur-sm">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-300" />
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="flex items-center">
                            <input id="remember_me"
                                   type="checkbox"
                                   class="w-4 h-4 text-purple-600 bg-white/10 border-white/20 rounded focus:ring-purple-500 focus:ring-offset-0"
                                   name="remember">
                            <span class="ml-2 text-sm text-purple-100">{{ __('Remember me') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-sm text-purple-300 hover:text-purple-200 font-medium transition-colors duration-200"
                               href="{{ route('password.request') }}">
                                {{ __('Forgot password?') }}
                            </a>
                        @endif
                    </div>

                    <!-- Sign In Button -->
                    <button type="submit"
                            class="w-full bg-gradient-to-r from-purple-600 via-pink-600 to-red-600 text-white font-bold py-4 px-6 rounded-2xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-transparent">
                        <i class="fas fa-arrow-right mr-2"></i>
                        Sign In
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-white/20"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-transparent text-purple-200 font-medium">New to CoolPosts?</span>
                    </div>
                </div>

                <!-- Create Account Button -->
                <a href="{{ route('register') }}"
                   class="w-full bg-white/10 backdrop-blur-sm text-white font-semibold py-4 px-6 rounded-2xl border border-white/20 hover:bg-white/20 transition-all duration-300 flex items-center justify-center group">
                    <i class="fas fa-user-plus mr-2 group-hover:scale-110 transition-transform duration-200"></i>
                    Create Account
                </a>
            </div>

            <!-- Feature Highlights -->
            <div class="mt-12 grid grid-cols-3 gap-4 text-center">
                <div class="text-white">
                    <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-link text-xl"></i>
                    </div>
                    <p class="text-sm font-medium">Short Links</p>
                </div>
                <div class="text-white">
                    <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-emerald-500 rounded-xl flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-dollar-sign text-xl"></i>
                    </div>
                    <p class="text-sm font-medium">Earn Money</p>
                </div>
                <div class="text-white">
                    <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center mx-auto mb-2">
                        <i class="fas fa-chart-line text-xl"></i>
                    </div>
                    <p class="text-sm font-medium">Analytics</p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
