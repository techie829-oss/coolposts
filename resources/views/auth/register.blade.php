<x-guest-layout>
    <x-slot name="title">Register - CoolPosts | Create Your Account</x-slot>

    <x-slot name="head">
        <meta name="description"
            content="Join CoolPosts and start monetizing your links today. Create your free account to access link shortening, analytics, and earning features.">
        <meta name="keywords"
            content="register, sign up, create account, CoolPosts, link monetization, free account, URL shortener">
        <meta name="robots" content="noindex, nofollow">
        <link rel="canonical" href="{{ url('/register') }}">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url('/register') }}">
        <meta property="og:title" content="Register - CoolPosts | Create Your Account">
        <meta property="og:description"
            content="Join CoolPosts and start monetizing your links today. Create your free account to access link shortening, analytics, and earning features.">
        <meta property="og:image" content="{{ asset('images/og-register.jpg') }}">
        <meta property="og:site_name" content="CoolPosts">
        <meta property="og:locale" content="en_US">

        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="{{ url('/register') }}">
        <meta property="twitter:title" content="Register - CoolPosts | Create Your Account">
        <meta property="twitter:description"
            content="Join CoolPosts and start monetizing your links today. Create your free account to access link shortening, analytics, and earning features.">
        <meta property="twitter:image" content="{{ asset('images/og-register.jpg') }}">
    </x-slot>

    <!-- Main Content Container -->
    <div class="relative z-10 w-full h-full flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <!-- Register Card - Full Width Responsive -->
        <div class="w-full max-w-md mx-auto">
            <!-- Logo & Branding -->
            <div class="text-center mb-12">
                <div
                    class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-purple-500 via-pink-500 to-red-500 rounded-3xl shadow-2xl mb-6 transform hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-link text-3xl text-white"></i>
                </div>
                <h1 class="text-4xl font-extrabold text-white mb-2 tracking-tight">
                    CoolPosts
                </h1>
                <p class="text-purple-200 text-lg font-medium">
                    Link Monetization Platform
                </p>
            </div>

            <!-- Register Card -->
            <div class="bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8">
                <!-- Header -->
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-white mb-2">Create Account</h2>
                    <p class="text-purple-100">Join CoolPosts and start monetizing your links</p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status
                    class="mb-6 p-4 bg-green-500/20 border border-green-500/30 rounded-2xl text-green-200 backdrop-blur-sm"
                    :status="session('status')" />

                <!-- Register Form -->
                <form method="POST" action="{{ route('register') }}" class="space-y-6">
                    @csrf

                    <!-- Name Field -->
                    <div class="group">
                        <label for="name" class="block text-sm font-semibold text-purple-100 mb-3">Full Name</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i
                                    class="fas fa-user text-purple-300 group-focus-within:text-purple-400 transition-colors duration-200"></i>
                            </div>
                            <input id="name" type="text" name="name" :value="old('name')" required autofocus
                                autocomplete="name" placeholder="Enter your full name"
                                class="w-full pl-12 pr-4 py-4 bg-white/10 border border-white/20 rounded-2xl text-white placeholder-purple-200/60 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 backdrop-blur-sm">
                        </div>
                        <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-300" />
                    </div>

                    <!-- Email Field -->
                    <div class="group">
                        <label for="email" class="block text-sm font-semibold text-purple-100 mb-3">Email
                            Address</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i
                                    class="fas fa-envelope text-purple-300 group-focus-within:text-purple-400 transition-colors duration-200"></i>
                            </div>
                            <input id="email" type="email" name="email" :value="old('email')" required
                                autocomplete="username" placeholder="Enter your email"
                                class="w-full pl-12 pr-4 py-4 bg-white/10 border border-white/20 rounded-2xl text-white placeholder-purple-200/60 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 backdrop-blur-sm">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-300" />
                    </div>

                    <!-- Password Field -->
                    <div class="group">
                        <label for="password" class="block text-sm font-semibold text-purple-100 mb-3">Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i
                                    class="fas fa-lock text-purple-300 group-focus-within:text-purple-400 transition-colors duration-200"></i>
                            </div>
                            <input id="password" type="password" name="password" required autocomplete="new-password"
                                placeholder="Create a strong password"
                                class="w-full pl-12 pr-4 py-4 bg-white/10 border border-white/20 rounded-2xl text-white placeholder-purple-200/60 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 backdrop-blur-sm">
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-300" />
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="group">
                        <label for="password_confirmation"
                            class="block text-sm font-semibold text-purple-100 mb-3">Confirm Password</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i
                                    class="fas fa-lock text-purple-300 group-focus-within:text-purple-400 transition-colors duration-200"></i>
                            </div>
                            <input id="password_confirmation" type="password" name="password_confirmation" required
                                autocomplete="new-password" placeholder="Confirm your password"
                                class="w-full pl-12 pr-4 py-4 bg-white/10 border border-white/20 rounded-2xl text-white placeholder-purple-200/60 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 backdrop-blur-sm">
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')"
                            class="mt-2 text-sm text-red-300" />
                    </div>

                    @if($globalSettings->isReferralsEnabled())
                        <!-- Referral Code Field (Optional) -->
                        <div class="group">
                            <label for="referral_code" class="block text-sm font-semibold text-purple-100 mb-3">
                                Referral Code <span class="text-purple-300 text-xs font-normal">(Optional)</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i
                                        class="fas fa-user-friends text-purple-300 group-focus-within:text-purple-400 transition-colors duration-200"></i>
                                </div>
                                <input id="referral_code" type="text" name="referral_code"
                                    value="{{ old('referral_code', request()->get('ref')) }}" autocomplete="off"
                                    placeholder="Enter referral code" maxlength="20"
                                    class="w-full pl-12 pr-4 py-4 bg-white/10 border border-white/20 rounded-2xl text-white placeholder-purple-200/60 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 backdrop-blur-sm uppercase">
                            </div>
                            <p class="mt-2 text-xs text-purple-200/80">
                                <i class="fas fa-gift mr-1"></i>
                                Get a signup bonus when you use a referral code!
                            </p>
                            <x-input-error :messages="$errors->get('referral_code')" class="mt-2 text-sm text-red-300" />
                        </div>
                    @endif

                    <!-- Terms Agreement -->
                    <div class="flex items-start">
                        <input id="terms" type="checkbox" required
                            class="w-4 h-4 text-purple-600 bg-white/10 border-white/20 rounded focus:ring-purple-500 focus:ring-offset-0 mt-1">
                        <label for="terms" class="ml-2 text-sm text-purple-100">
                            I agree to the <a href="{{ route('legal.terms') }}"
                                class="text-purple-300 hover:text-purple-200 underline">Terms of Service</a> and <a
                                href="{{ route('legal.privacy') }}"
                                class="text-purple-300 hover:text-purple-200 underline">Privacy Policy</a>
                        </label>
                    </div>

                    <!-- Create Account Button -->
                    <button type="submit"
                        class="w-full bg-gradient-to-r from-purple-600 via-pink-600 to-red-600 text-white font-bold py-4 px-6 rounded-2xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-transparent">
                        <i class="fas fa-user-plus mr-2"></i>
                        Create Account
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-white/20"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-transparent text-purple-200 font-medium">Already have an account?</span>
                    </div>
                </div>

                <!-- Sign In Button -->
                <a href="{{ route('login') }}"
                    class="w-full bg-white/10 backdrop-blur-sm text-white font-semibold py-4 px-6 rounded-2xl border border-white/20 hover:bg-white/20 transition-all duration-300 flex items-center justify-center group">
                    <i class="fas fa-sign-in-alt mr-2 group-hover:scale-110 transition-transform duration-200"></i>
                    Sign In
                </a>
            </div>

            <!-- Benefits Section -->
            <div class="mt-12 text-center">
                <h3 class="text-xl font-bold text-white mb-6">Why Choose CoolPosts?</h3>
                <div class="grid grid-cols-1 gap-4">
                    @if($globalSettings->isEarningsEnabled())
                        <div class="flex items-center justify-center text-purple-200">
                            <i class="fas fa-check-circle text-green-400 mr-3"></i>
                            <span>Earn money from every click</span>
                        </div>
                    @endif
                    <div class="flex items-center justify-center text-purple-200">
                        <i class="fas fa-check-circle text-green-400 mr-3"></i>
                        <span>Advanced analytics and insights</span>
                    </div>
                    <div class="flex items-center justify-center text-purple-200">
                        <i class="fas fa-check-circle text-green-400 mr-3"></i>
                        <span>Secure link protection</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>