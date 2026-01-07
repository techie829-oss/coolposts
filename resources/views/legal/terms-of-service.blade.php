<x-app-layout>
    <x-slot name="title">Terms of Service - CoolPosts | Legal Terms & Conditions</x-slot>

    <x-slot name="head">
        <meta name="description"
            content="Read CoolPosts Terms of Service. Understand our terms and conditions for using our link monetization platform, user responsibilities, and service guidelines.">
        <meta name="keywords"
            content="terms of service, terms and conditions, legal terms, user agreement, service terms, CoolPosts terms">
        <meta name="robots" content="index, follow">
        <link rel="canonical" href="{{ url('/terms-of-service') }}">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url('/terms-of-service') }}">
        <meta property="og:title" content="Terms of Service - CoolPosts | Legal Terms & Conditions">
        <meta property="og:description"
            content="Read CoolPosts Terms of Service. Understand our terms and conditions for using our link monetization platform, user responsibilities, and service guidelines.">
        <meta property="og:image" content="{{ asset('images/og-terms.jpg') }}">
        <meta property="og:site_name" content="CoolPosts">
        <meta property="og:locale" content="en_US">

        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="{{ url('/terms-of-service') }}">
        <meta property="twitter:title" content="Terms of Service - CoolPosts | Legal Terms & Conditions">
        <meta property="twitter:description"
            content="Read CoolPosts Terms of Service. Understand our terms and conditions for using our link monetization platform, user responsibilities, and service guidelines.">
        <meta property="twitter:image" content="{{ asset('images/og-terms.jpg') }}">
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Terms of Service') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <!-- Header -->
                <div
                    class="relative overflow-hidden bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-500 px-6 py-12 sm:px-12">
                    <div class="absolute inset-0 opacity-20">
                        <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                            <path d="M0 100 C 20 0 50 0 100 100 Z" fill="white" />
                        </svg>
                    </div>
                    <div class="relative z-10 text-center">
                        <h1 class="text-4xl font-extrabold text-white tracking-tight mb-3 drop-shadow-md">Terms of
                            Service</h1>
                        <div
                            class="inline-flex items-center space-x-2 bg-white/20 backdrop-blur-md rounded-full px-4 py-1 border border-white/30">
                            <i class="far fa-clock text-blue-100 text-sm"></i>
                            <p class="text-blue-50 font-medium text-sm">Last updated: {{ date('F d, Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="px-6 py-8 prose prose-lg max-w-none">
                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">1. Acceptance of Terms</h2>
                        <p class="text-gray-700 mb-4">
                            By accessing and using {{ $platformName }} ("the Service"), you accept and agree to be bound
                            by the terms and provision of this agreement. If you do not agree to abide by the above,
                            please do not use this service.
                        </p>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">2. Description of Service</h2>
                        <p class="text-gray-700 mb-4">
                            {{ $platformName }} is a link sharing and monetization platform that allows users to:
                        </p>
                        <ul class="list-disc pl-6 text-gray-700 mb-4">
                            <li>Create shortened links for easy sharing</li>
                            <li>Monetize links through advertising</li>
                            <li>Track link performance and analytics</li>
                            <li>Earn revenue from link clicks</li>
                            <li>Manage subscription plans and payments</li>
                        </ul>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">3. User Accounts</h2>
                        <p class="text-gray-700 mb-4">
                            To use certain features of the Service, you must register for an account. You agree to:
                        </p>
                        <ul class="list-disc pl-6 text-gray-700 mb-4">
                            <li>Provide accurate and complete information</li>
                            <li>Maintain the security of your account credentials</li>
                            <li>Notify us immediately of any unauthorized use</li>
                            <li>Accept responsibility for all activities under your account</li>
                        </ul>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">4. Acceptable Use</h2>
                        <p class="text-gray-700 mb-4">
                            You agree not to use the Service to:
                        </p>
                        <ul class="list-disc pl-6 text-gray-700 mb-4">
                            <li>Share illegal, harmful, or offensive content</li>
                            <li>Violate any applicable laws or regulations</li>
                            <li>Infringe on intellectual property rights</li>
                            <li>Distribute malware or harmful software</li>
                            <li>Engage in spam or deceptive practices</li>
                            <li>Attempt to circumvent security measures</li>
                            <li>Generate artificial or fraudulent traffic</li>
                        </ul>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">5. Link Content and Responsibility</h2>
                        <p class="text-gray-700 mb-4">
                            You are solely responsible for:
                        </p>
                        <ul class="list-disc pl-6 text-gray-700 mb-4">
                            <li>The content and destination of your links</li>
                            <li>Ensuring your links comply with our policies</li>
                            <li>Obtaining necessary permissions for linked content</li>
                            <li>Any damages resulting from your links</li>
                        </ul>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">6. Monetization and Payments</h2>
                        <p class="text-gray-700 mb-4">
                            Our monetization features include:
                        </p>
                        <ul class="list-disc pl-6 text-gray-700 mb-4">
                            <li>Ad-supported link monetization</li>
                            <li>Subscription-based premium features</li>
                            <li>Referral commission program</li>
                            <li>Payment processing through secure gateways</li>
                        </ul>
                        <p class="text-gray-700 mb-4">
                            Payment terms and minimum payout amounts are subject to change with notice.
                        </p>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">7. Privacy and Data Protection</h2>
                        <p class="text-gray-700 mb-4">
                            Your privacy is important to us. Please review our <a href="{{ route('legal.privacy') }}"
                                class="text-blue-600 hover:text-blue-800">Privacy Policy</a> to understand how we
                            collect, use, and protect your information.
                        </p>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">8. Intellectual Property</h2>
                        <p class="text-gray-700 mb-4">
                            The Service and its original content, features, and functionality are owned by
                            {{ $platformName }} and are protected by international copyright, trademark, patent, trade
                            secret, and other intellectual property laws.
                        </p>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">9. Termination</h2>
                        <p class="text-gray-700 mb-4">
                            We may terminate or suspend your account immediately, without prior notice, for conduct that
                            we believe violates these Terms of Service or is harmful to other users, us, or third
                            parties.
                        </p>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">10. Disclaimers</h2>
                        <p class="text-gray-700 mb-4">
                            The Service is provided "as is" without warranties of any kind. We disclaim all warranties,
                            express or implied, including but not limited to warranties of merchantability, fitness for
                            a particular purpose, and non-infringement.
                        </p>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">11. Limitation of Liability</h2>
                        <p class="text-gray-700 mb-4">
                            In no event shall {{ $platformName }} be liable for any indirect, incidental, special,
                            consequential, or punitive damages, including without limitation, loss of profits, data,
                            use, goodwill, or other intangible losses.
                        </p>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">12. Changes to Terms</h2>
                        <p class="text-gray-700 mb-4">
                            We reserve the right to modify these terms at any time. We will notify users of any material
                            changes via email or through the Service. Your continued use of the Service after changes
                            constitutes acceptance of the new terms.
                        </p>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">13. Governing Law</h2>
                        <p class="text-gray-700 mb-4">
                            These Terms shall be governed by and construed in accordance with the laws of the
                            jurisdiction in which {{ $platformName }} operates, without regard to its conflict of law
                            provisions.
                        </p>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">14. Contact Information</h2>
                        <p class="text-gray-700 mb-4">
                            If you have any questions about these Terms of Service, please contact us at:
                        </p>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-700">
                                <strong>Email:</strong> techie829@gmail.com<br>
                                {{-- <strong>Address:</strong> [Your Business Address]<br>
                                <strong>Phone:</strong> [Your Phone Number] --}}
                            </p>
                        </div>
                    </div>

                    <div class="border-t pt-8">
                        <p class="text-sm text-gray-500 text-center">
                            By using {{ $platformName }}, you acknowledge that you have read, understood, and agree to
                            be bound by these Terms of Service.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="mt-8 text-center">
                <a href="{{ route('legal.privacy') }}" class="text-blue-600 hover:text-blue-800 mr-4">Privacy Policy</a>
                <a href="{{ route('legal.about') }}" class="text-blue-600 hover:text-blue-800 mr-4">About Us</a>
                <a href="{{ route('legal.contact') }}" class="text-blue-600 hover:text-blue-800">Contact Us</a>
            </div>
        </div>
    </div>
</x-app-layout>