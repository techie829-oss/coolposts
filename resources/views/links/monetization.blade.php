<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Accessing') }} {{ $link->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Header Section -->
                <div
                    class="relative overflow-hidden bg-gradient-to-r from-violet-600 via-fuchsia-600 to-rose-500 rounded-t-2xl p-10 text-center">
                    <div class="absolute inset-0 opacity-20">
                        <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                            <path d="M0 100 Q 50 0 100 100" stroke="white" stroke-width="2" fill="none" />
                        </svg>
                    </div>
                    <div class="relative z-10">
                        <div
                            class="mb-4 inline-block bg-white/20 backdrop-blur-md rounded-full p-4 shadow-lg ring-1 ring-white/30">
                            <i class="fas fa-link text-3xl text-white"></i>
                        </div>
                        <h1 class="text-3xl font-bold text-white mb-2 tracking-tight drop-shadow-md">
                            CoolPosts
                        </h1>
                        <h3 class="text-xl text-white/90 font-medium mb-4">{{ $link->title }}</h3>
                        <div
                            class="inline-flex items-center space-x-2 bg-black/20 backdrop-blur-sm rounded-full px-4 py-1.5 border border-white/10">
                            <i class="fas fa-shield-alt text-emerald-300 text-sm"></i>
                            <p class="text-white font-medium text-sm">Secure Redirect</p>
                        </div>
                    </div>
                </div>

                <!-- Countdown Section -->
                <div class="bg-white p-8 text-center border-b border-gray-100">
                    <div class="mb-6">
                        <h4 class="text-lg font-semibold text-gray-800 flex items-center justify-center">
                            <span class="flex h-3 w-3 relative mr-3">
                              <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-violet-400 opacity-75"></span>
                              <span class="relative inline-flex rounded-full h-3 w-3 bg-violet-500"></span>
                            </span>
                            Verifying your access...
                        </h4>
                    </div>

                    <div class="relative w-32 h-32 mx-auto mb-6 flex items-center justify-center">
                        <!-- Circular Progress Container -->
                        <div class="absolute inset-0 rounded-full border-4 border-gray-100"></div>
                        <div class="text-5xl font-bold text-transparent bg-clip-text bg-gradient-to-r from-violet-600 to-fuchsia-600" id="countdown">10</div>
                    </div>

                    <div class="w-full max-w-md mx-auto bg-gray-100 rounded-full h-2 mb-6 overflow-hidden">
                        <div class="bg-gradient-to-r from-violet-500 via-fuchsia-500 to-rose-500 h-2 rounded-full transition-all duration-1000 ease-linear shadow-[0_0_10px_rgba(139,92,246,0.3)]"
                             id="progress" style="width: 0%"></div>
                    </div>

                    <p class="text-gray-500 text-sm font-medium">
                        <i class="fas fa-hand-sparkles mr-1 text-violet-500"></i>
                        Ensuring a safe browsing experience
                    </p>
                </div>

                <!-- reCAPTCHA Section -->
                <div class="p-8 bg-slate-50 border-b border-slate-200" id="recaptchaSection" style="display: none;">
                    <div class="text-center mb-6">
                        <div class="inline-block p-3 bg-blue-100 rounded-full mb-4">
                            <i class="fas fa-shield-alt text-2xl text-blue-600"></i>
                        </div>
                        <h5 class="text-lg font-bold text-slate-800">Security Verification</h5>
                        <p class="text-slate-500">Please complete the challenge below</p>
                    </div>

                    <div class="text-center">
                        <div id="recaptchaContainer" class="mb-6 flex justify-center"></div>
                        <button
                            class="group relative inline-flex items-center justify-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl transition-all duration-200 shadow-lg hover:shadow-blue-500/30 overflow-hidden"
                            id="verifyRecaptchaBtn">
                            <span class="absolute w-0 h-0 transition-all duration-500 ease-out bg-white rounded-full group-hover:w-56 group-hover:h-56 opacity-10"></span>
                            <span class="relative flex items-center">
                                <i class="fas fa-check-circle mr-2"></i>
                                Verify Security
                            </span>
                        </button>
                    </div>
                </div>

                <!-- Ad Section -->
                <div class="p-8">
                    <h5 class="text-center text-lg font-semibold text-gray-800 mb-6">
                        <i class="fas fa-ad mr-2"></i>
                        {{ $adContent['type'] === 'no_ads' ? 'Premium Access' : 'Advertisement' }}
                    </h5>

                    <div class="mb-6" id="adContainer">
                        {!! $adContent['html'] !!}
                    </div>

                    <div class="text-center">
                        <button
                            class="bg-gradient-to-r from-green-500 to-teal-500 hover:from-green-600 hover:to-teal-600 disabled:from-gray-400 disabled:to-gray-500 text-white font-bold py-4 px-8 rounded-full transition-all duration-300 transform hover:scale-105 disabled:transform-none disabled:cursor-not-allowed"
                            id="unlockBtn" {{ $adContent['duration'] > 0 ? 'disabled' : '' }}>
                            <i class="fas fa-unlock mr-2"></i>
                            {{ $adContent['type'] === 'no_ads' ? 'Continue to Link' : 'Unlock Link' }}
                        </button>
                    </div>
                </div>

                <!-- Ad Styles and Scripts -->
                {!! $adContent['style'] !!}
                {!! $adContent['script'] !!}

                <!-- Info Section -->
                <div class="bg-gray-50 p-8 border-t border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div>
                            <h6 class="text-lg font-semibold text-gray-800 mb-4">
                                <i class="fas fa-star mr-2 text-yellow-500"></i>
                                Why this wait?
                            </h6>
                            <ul class="space-y-3">
                                <li class="flex items-center text-gray-700">
                                    <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                    Prevents automated abuse
                                </li>
                                <li class="flex items-center text-gray-700">
                                    <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                    Ensures quality traffic
                                </li>
                                <li class="flex items-center text-gray-700">
                                    <i class="fas fa-check-circle text-green-500 mr-3"></i>
                                    Supports content creators
                                </li>
                            </ul>
                        </div>
                        <div>
                            <h6 class="text-lg font-semibold text-gray-800 mb-4">
                                <i class="fas fa-chart-line mr-2 text-blue-500"></i>
                                Your visit helps
                            </h6>
                            <ul class="space-y-3">
                                <li class="flex items-center text-gray-700">
                                    <i class="fas fa-heart text-red-500 mr-3"></i>
                                    Support content creators
                                </li>
                                <li class="flex items-center text-gray-700">
                                    <i class="fas fa-coins text-yellow-500 mr-3"></i>
                                    Generate revenue
                                </li>
                                <li class="flex items-center text-gray-700">
                                    <i class="fas fa-users text-blue-500 mr-3"></i>
                                    Maintain platform quality
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- reCAPTCHA Script -->
    <script src="https://www.google.com/recaptcha/api.js?render={{ config('recaptcha.site_key') }}"></script>

    <script>
        // Global variables
        let timeLeft = 10;
        let recaptchaVerified = false;
        const countdownElement = document.getElementById('countdown');
        const progressElement = document.getElementById('progress');
        const unlockBtn = document.getElementById('unlockBtn');
        const recaptchaSection = document.getElementById('recaptchaSection');
        const verifyRecaptchaBtn = document.getElementById('verifyRecaptchaBtn');

        // reCAPTCHA configuration
        const recaptchaSiteKey = '{{ config("recaptcha.site_key") }}';
        const isRecaptchaConfigured = '{{ config("recaptcha.site_key") }}' !== '';

        // Countdown functionality
        function updateCountdown() {
            if (timeLeft > 0) {
                timeLeft--;
                countdownElement.textContent = timeLeft;

                // Update progress bar
                const progress = ((10 - timeLeft) / 10) * 100;
                progressElement.style.width = progress + '%';

                setTimeout(updateCountdown, 1000);
            } else {
                // Countdown finished
                countdownElement.textContent = '0';
                progressElement.style.width = '100%';

                // Show reCAPTCHA if configured, otherwise enable unlock button
                if (isRecaptchaConfigured && !recaptchaVerified) {
                    showRecaptcha();
                } else {
                    enableUnlockButton();
                }
            }
        }

        // Show reCAPTCHA section
        function showRecaptcha() {
            recaptchaSection.style.display = 'block';
            recaptchaSection.scrollIntoView({ behavior: 'smooth' });
        }

        // Enable unlock button
        function enableUnlockButton() {
            unlockBtn.disabled = false;
            unlockBtn.innerHTML = '<i class="fas fa-unlock mr-2"></i>Access Link Now';

            // Show success message
            document.querySelector('.bg-gray-50.p-8.text-center.border-b.border-gray-200').innerHTML = `
                <div class="text-center">
                    <i class="fas fa-check-circle text-5xl text-green-500 mb-4"></i>
                    <h4 class="text-2xl font-bold text-green-600 mb-2">Access Granted!</h4>
                    <p class="text-gray-600">Click the button below to continue to your destination</p>
                </div>
            `;
        }

        // Handle reCAPTCHA verification
        verifyRecaptchaBtn.addEventListener('click', function () {
            if (recaptchaVerified) {
                enableUnlockButton();
                return;
            }

            // Show loading state
            verifyRecaptchaBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Verifying...';
            verifyRecaptchaBtn.disabled = true;

            // Execute reCAPTCHA
            grecaptcha.execute(recaptchaSiteKey, { action: 'monetization' }).then(function (token) {
                // Send token to backend for verification
                fetch('{{ route("monetization.verify-recaptcha") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        token: token
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            recaptchaVerified = true;
                            verifyRecaptchaBtn.innerHTML = '<i class="fas fa-check mr-2"></i>Verified ✓';
                            verifyRecaptchaBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                            verifyRecaptchaBtn.classList.add('bg-green-600', 'hover:bg-green-700');

                            // Enable unlock button after a short delay
                            setTimeout(() => {
                                enableUnlockButton();
                            }, 1000);
                        } else {
                            // Show error and retry
                            verifyRecaptchaBtn.innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i>Verification Failed';
                            verifyRecaptchaBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                            verifyRecaptchaBtn.classList.add('bg-red-600', 'hover:bg-red-700');

                            setTimeout(() => {
                                verifyRecaptchaBtn.innerHTML = '<i class="fas fa-shield-alt mr-2"></i>Retry Verification';
                                verifyRecaptchaBtn.disabled = false;
                                verifyRecaptchaBtn.classList.remove('bg-red-600', 'hover:bg-red-700');
                                verifyRecaptchaBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');
                            }, 2000);
                        }
                    })
                    .catch(error => {
                        console.error('reCAPTCHA verification error:', error);
                        verifyRecaptchaBtn.innerHTML = '<i class="fas fa-exclamation-triangle mr-2"></i>Error - Retry';
                        verifyRecaptchaBtn.disabled = false;
                    });
            });
        });

        // Handle unlock button click
        unlockBtn.addEventListener('click', function () {
            if (!unlockBtn.disabled) {
                // Show loading state
                unlockBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Redirecting...';
                unlockBtn.disabled = true;

                // Redirect to target URL
                setTimeout(() => {
                    window.location.href = '{{ $link->original_url }}';
                }, 1000);
            }
        });

        // Simulate ad loading (replace with actual ad code)
        setTimeout(() => {
            const adContainer = document.getElementById('adContainer');
            adContainer.innerHTML = `
                <div class="text-center">
                    <i class="fas fa-ad text-3xl text-blue-500 mb-2"></i>
                    <p class="text-blue-600 mb-1 font-medium">Advertisement</p>
                    <small class="text-gray-500">Ad content loaded successfully</small>
                </div>
            `;
        }, 2000);

        // Start countdown when page loads
        document.addEventListener('DOMContentLoaded', function () {
            updateCountdown();
        });
    </script>
</x-app-layout>