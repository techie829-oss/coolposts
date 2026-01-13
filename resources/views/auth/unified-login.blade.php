<x-guest-layout>
    <x-slot name="title">Login or Sign Up - CoolPosts</x-slot>

    <div class="relative z-10 w-full h-full flex items-center justify-center px-4 sm:px-6 lg:px-8"
        x-data="{ tab: '{{ request()->routeIs('register') ? 'signup' : 'login' }}' }">
        <div class="w-full max-w-md mx-auto">
            <!-- Logo & Branding -->
            <div class="text-center mb-8">
                <div
                    class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-r from-purple-500 via-pink-500 to-red-500 rounded-2xl shadow-xl mb-4 transform hover:scale-110 transition-transform duration-300">
                    <i class="fas fa-link text-2xl text-white"></i>
                </div>
                <h1 class="text-3xl font-extrabold text-white mb-1 tracking-tight">CoolPosts</h1>
                <p class="text-purple-200 text-base font-medium">Link Monetization Platform</p>
            </div>

            <!-- Main Card -->
            <div class="bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 overflow-hidden">

                <!-- Helper method to check GlobalSettings if not passed directly -->
                @php
                    $globalSettings = \App\Models\GlobalSetting::getSettings();
                @endphp

                <!-- Tabs -->
                <div class="flex border-b border-white/10">
                    <button @click="tab = 'login'"
                        :class="{ 'text-white border-b-2 border-purple-500 bg-white/5': tab === 'login', 'text-purple-200 hover:text-white hover:bg-white/5': tab !== 'login' }"
                        class="flex-1 py-4 text-center font-semibold transition-all duration-200 focus:outline-none">
                        Login
                    </button>
                    <button @click="tab = 'signup'"
                        :class="{ 'text-white border-b-2 border-purple-500 bg-white/5': tab === 'signup', 'text-purple-200 hover:text-white hover:bg-white/5': tab !== 'signup' }"
                        class="flex-1 py-4 text-center font-semibold transition-all duration-200 focus:outline-none">
                        Sign Up
                    </button>
                </div>

                <div class="p-8">
                    <!-- Session Status -->
                    <x-auth-session-status
                        class="mb-6 p-4 bg-green-500/20 border border-green-500/30 rounded-2xl text-green-200 backdrop-blur-sm"
                        :status="session('status')" />

                    <!-- Social Login Buttons -->
                    <div class="space-y-3 mb-8">
                        <a href="{{ route('social.login', 'google') }}"
                            class="flex items-center justify-center w-full px-4 py-3 bg-white text-gray-700 font-semibold rounded-xl hover:bg-gray-50 transition-colors duration-200 shadow-md">
                            <img src="https://www.google.com/favicon.ico" alt="Google" class="w-5 h-5 mr-3">
                            <span x-text="tab === 'login' ? 'Sign in with Google' : 'Sign up with Google'"></span>
                        </a>
                        {{-- <a href="{{ route('social.login', 'linkedin') }}"
                            class="flex items-center justify-center w-full px-4 py-3 bg-[#0077b5] text-white font-semibold rounded-xl hover:bg-[#006097] transition-colors duration-200 shadow-md">
                            <i class="fab fa-linkedin text-xl mr-3"></i>
                            <span x-text="tab === 'login' ? 'Sign in with LinkedIn' : 'Sign up with LinkedIn'"></span>
                        </a> --}}
                    </div>

                    <div class="relative mb-8">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-white/20"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-4 bg-transparent text-purple-200 font-medium"
                                x-text="tab === 'login' ? 'Or sign in with email' : 'Or sign up with email'"></span>
                        </div>
                    </div>

                    <!-- Login Form -->
                    <div x-show="tab === 'login'" x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100">
                        <form method="POST" action="{{ route('login') }}" class="space-y-5">
                            @csrf
                            <div class="group">
                                <label class="block text-sm font-semibold text-purple-100 mb-2">Email Address</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-envelope text-purple-300"></i>
                                    </div>
                                    <input type="email" name="email" :value="old('email')" required autofocus
                                        autocomplete="username"
                                        class="w-full pl-12 pr-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-purple-200/60 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                                </div>
                                <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-300" />
                            </div>

                            <div class="group">
                                <label class="block text-sm font-semibold text-purple-100 mb-2">Password</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-lock text-purple-300"></i>
                                    </div>
                                    <input type="password" name="password" required autocomplete="current-password"
                                        class="w-full pl-12 pr-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-purple-200/60 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-300" />
                            </div>

                            <div class="flex items-center justify-between">
                                <label for="remember_me" class="flex items-center">
                                    <input id="remember_me" type="checkbox"
                                        class="w-4 h-4 text-purple-600 bg-white/10 border-white/20 rounded focus:ring-purple-500"
                                        name="remember">
                                    <span class="ml-2 text-sm text-purple-100">Remember me</span>
                                </label>
                                @if (Route::has('password.request'))
                                    <a class="text-sm text-purple-300 hover:text-purple-200 font-medium"
                                        href="{{ route('password.request') }}">Forgot password?</a>
                                @endif
                            </div>

                            <button type="submit"
                                class="w-full bg-gradient-to-r from-purple-600 via-pink-600 to-red-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                                Sign In
                            </button>
                        </form>
                    </div>

                    <!-- Signup Form -->
                    <div x-show="tab === 'signup'" style="display: none;"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform scale-95"
                        x-transition:enter-end="opacity-100 transform scale-100">
                        <form method="POST" action="{{ route('register') }}" class="space-y-5">
                            @csrf
                            <div class="group">
                                <label class="block text-sm font-semibold text-purple-100 mb-2">Full Name</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-user text-purple-300"></i>
                                    </div>
                                    <input type="text" name="name" :value="old('name')" required autofocus
                                        autocomplete="name"
                                        class="w-full pl-12 pr-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-purple-200/60 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                                </div>
                                <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-red-300" />
                            </div>

                            <div class="group">
                                <label class="block text-sm font-semibold text-purple-100 mb-2">Email Address</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-envelope text-purple-300"></i>
                                    </div>
                                    <input type="email" name="email" :value="old('email')" required
                                        autocomplete="username"
                                        class="w-full pl-12 pr-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-purple-200/60 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                                </div>
                                <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-300" />
                            </div>

                            <div class="group">
                                <label class="block text-sm font-semibold text-purple-100 mb-2">Password</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-lock text-purple-300"></i>
                                    </div>
                                    <input type="password" name="password" required autocomplete="new-password"
                                        class="w-full pl-12 pr-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-purple-200/60 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                                </div>
                                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-red-300" />
                            </div>

                            <div class="group">
                                <label class="block text-sm font-semibold text-purple-100 mb-2">Confirm
                                    Password</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <i class="fas fa-lock text-purple-300"></i>
                                    </div>
                                    <input type="password" name="password_confirmation" required
                                        autocomplete="new-password"
                                        class="w-full pl-12 pr-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-purple-200/60 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300">
                                </div>
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-red-300" />
                            </div>

                            @if ($globalSettings->isReferralsEnabled())
                                <div class="group">
                                    <label class="block text-sm font-semibold text-purple-100 mb-2">Referral Code <span
                                            class="text-purple-300 text-xs font-normal">(Optional)</span></label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-user-friends text-purple-300"></i>
                                        </div>
                                        <input type="text" name="referral_code"
                                            value="{{ old('referral_code', request()->get('ref')) }}"
                                            autocomplete="off" maxlength="20"
                                            class="w-full pl-12 pr-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-purple-200/60 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 uppercase">
                                    </div>
                                </div>
                            @endif

                            <div class="flex items-start">
                                <input id="terms" type="checkbox" required
                                    class="w-4 h-4 text-purple-600 bg-white/10 border-white/20 rounded focus:ring-purple-500 mt-1">
                                <label for="terms" class="ml-2 text-sm text-purple-100">
                                    I agree to the <a href="{{ route('legal.terms') }}"
                                        class="text-purple-300 hover:text-purple-200 underline">Terms of Service</a>
                                    and <a href="{{ route('legal.privacy') }}"
                                        class="text-purple-300 hover:text-purple-200 underline">Privacy Policy</a>
                                </label>
                            </div>

                            <button type="submit"
                                class="w-full bg-gradient-to-r from-purple-600 via-pink-600 to-red-600 text-white font-bold py-3 px-6 rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                                Create Account
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
