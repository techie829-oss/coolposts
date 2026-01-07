<x-guest-layout>
    <!-- Main Content Container -->
    <div class="relative z-10 w-full h-full flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <!-- Confirm Password Card - Full Width Responsive -->
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

            <!-- Confirm Password Card -->
            <div class="bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8">
                <!-- Header -->
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-white mb-2">Confirm Password</h2>
                    <p class="text-purple-100">This is a secure area of the application</p>
                </div>

                <!-- Session Status -->
                <div class="mb-6 p-4 bg-yellow-500/20 border border-yellow-500/30 rounded-2xl text-yellow-200 backdrop-blur-sm">
                    <p class="text-sm">
                        This is a secure area of the application. Please confirm your password before continuing.
                    </p>
                </div>

                <!-- Confirm Password Form -->
                <form method="POST" action="{{ route('password.confirm') }}" class="space-y-6">
                    @csrf

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

                    <!-- Confirm Button -->
                    <button type="submit"
                            class="w-full bg-gradient-to-r from-purple-600 via-pink-600 to-red-600 text-white font-bold py-4 px-6 rounded-2xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-transparent">
                        <i class="fas fa-check mr-2"></i>
                        Confirm
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-white/20"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-transparent text-purple-200 font-medium">Need to go back?</span>
                    </div>
                </div>

                <!-- Back Button -->
                <a href="{{ route('dashboard') }}"
                   class="w-full bg-white/10 backdrop-blur-sm text-white font-semibold py-4 px-6 rounded-2xl border border-white/20 hover:bg-white/20 transition-all duration-300 flex items-center justify-center group">
                    <i class="fas fa-arrow-left mr-2 group-hover:scale-110 transition-transform duration-200"></i>
                    Back to Dashboard
                </a>
            </div>

            <!-- Security Info -->
            <div class="mt-12 text-center">
                <h3 class="text-xl font-bold text-white mb-6">Security Features</h3>
                <div class="grid grid-cols-1 gap-4">
                    <div class="flex items-center justify-center text-purple-200">
                        <i class="fas fa-shield-alt text-green-400 mr-3"></i>
                        <span>Secure password confirmation</span>
                    </div>
                    <div class="flex items-center justify-center text-purple-200">
                        <i class="fas fa-lock text-blue-400 mr-3"></i>
                        <span>Session-based security</span>
                    </div>
                    <div class="flex items-center justify-center text-purple-200">
                        <i class="fas fa-user-shield text-purple-400 mr-3"></i>
                        <span>User authentication required</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
