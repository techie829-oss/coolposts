<x-guest-layout>
    <!-- Main Content Container -->
    <div class="relative z-10 w-full h-full flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <!-- Forgot Password Card - Full Width Responsive -->
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

            <!-- Forgot Password Card -->
            <div class="bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8">
                <!-- Header -->
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-white mb-2">Forgot Password?</h2>
                    <p class="text-purple-100">No worries, we'll send you reset instructions</p>
                </div>

                <!-- Session Status -->
                <div class="mb-6 p-4 bg-blue-500/20 border border-blue-500/30 rounded-2xl text-blue-200 backdrop-blur-sm">
                    <p class="text-sm">
                        Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.
                    </p>
                </div>

                <!-- Forgot Password Form -->
                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
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
                                   placeholder="Enter your email address"
                                   class="w-full pl-12 pr-4 py-4 bg-white/10 border border-white/20 rounded-2xl text-white placeholder-purple-200/60 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-300 backdrop-blur-sm">
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-red-300" />
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            class="w-full bg-gradient-to-r from-purple-600 via-pink-600 to-red-600 text-white font-bold py-4 px-6 rounded-2xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-transparent">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Email Password Reset Link
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-white/20"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-transparent text-purple-200 font-medium">Remember your password?</span>
                    </div>
                </div>

                <!-- Back to Login Button -->
                <a href="{{ route('login') }}"
                   class="w-full bg-white/10 backdrop-blur-sm text-white font-semibold py-4 px-6 rounded-2xl border border-white/20 hover:bg-white/20 transition-all duration-300 flex items-center justify-center group">
                    <i class="fas fa-arrow-left mr-2 group-hover:scale-110 transition-transform duration-200"></i>
                    Back to Login
                </a>
            </div>

            <!-- Help Section -->
            <div class="mt-12 text-center">
                <h3 class="text-xl font-bold text-white mb-6">Need Help?</h3>
                <div class="grid grid-cols-1 gap-4">
                    <div class="flex items-center justify-center text-purple-200">
                        <i class="fas fa-envelope text-blue-400 mr-3"></i>
                        <span>Check your spam folder</span>
                    </div>
                    <div class="flex items-center justify-center text-purple-200">
                        <i class="fas fa-clock text-yellow-400 mr-3"></i>
                        <span>Reset link expires in 60 minutes</span>
                    </div>
                    <div class="flex items-center justify-center text-purple-200">
                        <i class="fas fa-shield-alt text-green-400 mr-3"></i>
                        <span>Secure password reset process</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
