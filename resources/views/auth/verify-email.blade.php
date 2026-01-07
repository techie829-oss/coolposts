<x-guest-layout>
    <!-- Main Content Container -->
    <div class="relative z-10 w-full h-full flex items-center justify-center px-4 sm:px-6 lg:px-8">
        <!-- Verify Email Card - Full Width Responsive -->
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

            <!-- Verify Email Card -->
            <div class="bg-white/10 backdrop-blur-xl rounded-3xl shadow-2xl border border-white/20 p-8">
                <!-- Header -->
                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-white mb-2">Verify Email</h2>
                    <p class="text-purple-100">Thanks for signing up! Please verify your email</p>
                </div>

                <!-- Session Status -->
                <div class="mb-6 p-4 bg-green-500/20 border border-green-500/30 rounded-2xl text-green-200 backdrop-blur-sm">
                    <p class="text-sm">
                        Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.
                    </p>
                </div>

                <!-- Verification Status -->
                @if (session('status') == 'verification-link-sent')
                    <div class="mb-6 p-4 bg-blue-500/20 border border-blue-500/30 rounded-2xl text-blue-200 backdrop-blur-sm">
                        <p class="text-sm">
                            A new verification link has been sent to the email address you provided during registration.
                        </p>
                    </div>
                @endif

                <!-- Verification Form -->
                <form method="POST" action="{{ route('verification.send') }}" class="space-y-6">
                    @csrf

                    <!-- Resend Verification Button -->
                    <button type="submit"
                            class="w-full bg-gradient-to-r from-purple-600 via-pink-600 to-red-600 text-white font-bold py-4 px-6 rounded-2xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 focus:ring-offset-transparent">
                        <i class="fas fa-paper-plane mr-2"></i>
                        Resend Verification Email
                    </button>
                </form>

                <!-- Logout Form -->
                <form method="POST" action="{{ route('logout') }}" class="mt-4">
                    @csrf
                    <button type="submit"
                            class="w-full bg-white/10 backdrop-blur-sm text-white font-semibold py-4 px-6 rounded-2xl border border-white/20 hover:bg-white/20 transition-all duration-300 flex items-center justify-center group">
                        <i class="fas fa-sign-out-alt mr-2 group-hover:scale-110 transition-transform duration-200"></i>
                        Log Out
                    </button>
                </form>

                <!-- Divider -->
                <div class="relative my-8">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-white/20"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-4 bg-transparent text-purple-200 font-medium">Need help?</span>
                    </div>
                </div>

                <!-- Help Links -->
                <div class="space-y-3">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}"
                           class="w-full bg-white/10 backdrop-blur-sm text-white font-semibold py-4 px-6 rounded-2xl border border-white/20 hover:bg-white/20 transition-all duration-300 flex items-center justify-center group">
                            <i class="fas fa-key mr-2 group-hover:scale-110 transition-transform duration-200"></i>
                            Forgot Password?
                        </a>
                    @endif

                    <a href="{{ route('dashboard') }}"
                       class="w-full bg-white/10 backdrop-blur-sm text-white font-semibold py-4 px-6 rounded-2xl border border-white/20 hover:bg-white/20 transition-all duration-300 flex items-center justify-center group">
                        <i class="fas fa-arrow-left mr-2 group-hover:scale-110 transition-transform duration-200"></i>
                        Back to Dashboard
                    </a>
                </div>
            </div>

            <!-- Verification Steps -->
            <div class="mt-12 text-center">
                <h3 class="text-xl font-bold text-white mb-6">Verification Steps</h3>
                <div class="grid grid-cols-1 gap-4">
                    <div class="flex items-center justify-center text-purple-200">
                        <i class="fas fa-envelope text-blue-400 mr-3"></i>
                        <span>Check your email inbox</span>
                    </div>
                    <div class="flex items-center justify-center text-purple-200">
                        <i class="fas fa-mouse-pointer text-green-400 mr-3"></i>
                        <span>Click the verification link</span>
                    </div>
                    <div class="flex items-center justify-center text-purple-200">
                        <i class="fas fa-check-circle text-purple-400 mr-3"></i>
                        <span>Your account will be verified</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
