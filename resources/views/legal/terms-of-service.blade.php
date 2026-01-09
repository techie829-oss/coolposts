<x-app-layout>
    <x-slot name="title">Terms of Service - {{ config('app.name') }} | Legal Terms & Conditions</x-slot>

    <x-slot name="head">
        <meta name="description"
            content="Read {{ config('app.name') }} Terms of Service. Understand our terms and conditions for using our link monetization platform, user responsibilities, and service guidelines.">
        <meta name="keywords"
            content="terms of service, terms and conditions, legal terms, user agreement, service terms, {{ config('app.name') }} terms">
        <meta name="robots" content="index, follow">
        <link rel="canonical" href="{{ url('/terms-of-service') }}">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url('/terms-of-service') }}">
        <meta property="og:title" content="Terms of Service - {{ config('app.name') }} | Legal Terms & Conditions">
        <meta property="og:description"
            content="Read {{ config('app.name') }} Terms of Service. Understand our terms and conditions for using our link monetization platform, user responsibilities, and service guidelines.">
        <meta property="og:image" content="{{ asset('images/og-terms.jpg') }}">
        <meta property="og:site_name" content="{{ config('app.name') }}">
        <meta property="og:locale" content="en_US">

        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="{{ url('/terms-of-service') }}">
        <meta property="twitter:title"
            content="Terms of Service - {{ config('app.name') }} | Legal Terms & Conditions">
        <meta property="twitter:description"
            content="Read {{ config('app.name') }} Terms of Service. Understand our terms and conditions for using our link monetization platform, user responsibilities, and service guidelines.">
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
                            By accessing or using <strong>{{ config('app.name') }}</strong> ("the Service"), you agree
                            to be bound by these Terms of Service ("Terms"). If you do not agree to these Terms, you
                            must not access or use the Service.
                        </p>
                        <p class="text-gray-700 mb-4">
                            These Terms apply to all visitors, users, and others who access or use the Service.
                        </p>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">2. Description of Service</h2>
                        <p class="text-gray-700 mb-4">
                            {{ config('app.name') }} is a link sharing and monetization platform that allows users to:
                        </p>
                        <ul class="list-disc pl-6 text-gray-700 mb-4">
                            <li>Create shortened links for easier sharing</li>
                            <li>Monetize links through advertising</li>
                            <li>Track link performance and analytics</li>
                            <li>Earn revenue from eligible link clicks</li>
                            <li>Manage subscription plans and payments</li>
                        </ul>
                        <p class="text-gray-700 mb-4">
                            We reserve the right to modify, suspend, or discontinue any part of the Service at any time.
                        </p>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">3. User Accounts</h2>
                        <p class="text-gray-700 mb-4">
                            To access certain features, you must create an account. By creating an account, you agree
                            to:
                        </p>
                        <ul class="list-disc pl-6 text-gray-700 mb-4">
                            <li>Provide accurate, current, and complete information</li>
                            <li>Maintain the confidentiality of your login credentials</li>
                            <li>Notify us immediately of any unauthorized access</li>
                            <li>Accept responsibility for all activities conducted through your account</li>
                        </ul>
                        <p class="text-gray-700 mb-4">
                            You are responsible for ensuring your account information remains up to date.
                        </p>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">4. Acceptable Use Policy</h2>
                        <p class="text-gray-700 mb-4">
                            You agree <strong>not</strong> to use the Service to:
                        </p>
                        <ul class="list-disc pl-6 text-gray-700 mb-4">
                            <li>Share illegal, harmful, or offensive content</li>
                            <li>Violate any applicable laws or regulations</li>
                            <li>Infringe intellectual property or privacy rights</li>
                            <li>Distribute malware, phishing links, or harmful software</li>
                            <li>Engage in spam, misleading, or deceptive practices</li>
                            <li>Manipulate or generate artificial or fraudulent traffic</li>
                            <li>Attempt to bypass security, rate limits, or safeguards</li>
                        </ul>
                        <p class="text-gray-700 mb-4">
                            Violation of this section may result in immediate account suspension or termination.
                        </p>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">5. Link Content & User Responsibility</h2>
                        <p class="text-gray-700 mb-4">
                            You are solely responsible for:
                        </p>
                        <ul class="list-disc pl-6 text-gray-700 mb-4">
                            <li>The content and destination of all links you create</li>
                            <li>Ensuring compliance with applicable laws and policies</li>
                            <li>Obtaining permissions or licenses for linked content</li>
                            <li>Any damages, claims, or liabilities arising from your links</li>
                        </ul>
                        <p class="text-gray-700 mb-4">
                            {{ config('app.name') }} does <strong>not</strong> review or endorse third-party content.
                        </p>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">6. Monetization & Payments</h2>
                        <p class="text-gray-700 mb-4">
                            {{ config('app.name') }} may offer monetization features including:
                        </p>
                        <ul class="list-disc pl-6 text-gray-700 mb-4">
                            <li>Ad-supported link monetization</li>
                            <li>Subscription-based premium features</li>
                            <li>Referral or commission programs</li>
                            <li>Secure third-party payment processing</li>
                        </ul>
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                            <p class="text-sm text-yellow-700">
                                <strong>⚠️ Important Notes:</strong>
                            </p>
                            <ul class="list-disc pl-6 text-sm text-yellow-700 mt-2">
                                <li>Earnings are <strong>not guaranteed</strong></li>
                                <li>Payout thresholds, rates, and eligibility may change</li>
                                <li>Fraudulent or invalid traffic will not be paid</li>
                                <li>We reserve the right to withhold payments for policy violations</li>
                            </ul>
                        </div>
                        <p class="text-gray-700 mb-4">
                            All payments are subject to verification and compliance review.
                        </p>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">7. Privacy & Data Protection</h2>
                        <p class="text-gray-700 mb-4">
                            Your privacy matters to us. Please review our <a href="{{ route('legal.privacy') }}"
                                class="text-blue-600 hover:text-blue-800">Privacy Policy</a> to understand how we
                            collect, use, store, and protect your information.
                        </p>
                        <p class="text-gray-700 mb-4">
                            By using the Service, you consent to data processing as described in the Privacy Policy.
                        </p>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">8. Intellectual Property Rights</h2>
                        <p class="text-gray-700 mb-4">
                            All content, features, branding, and functionality of {{ config('app.name') }} are owned by
                            {{ config('app.name') }} and protected under applicable intellectual property laws.
                        </p>
                        <p class="text-gray-700 mb-4">
                            You may not copy, modify, distribute, or exploit any part of the Service without prior
                            written permission.
                        </p>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">9. Account Termination</h2>
                        <p class="text-gray-700 mb-4">
                            We reserve the right to suspend or terminate accounts immediately and without prior notice
                            if we determine that a user:
                        </p>
                        <ul class="list-disc pl-6 text-gray-700 mb-4">
                            <li>Violates these Terms</li>
                            <li>Engages in harmful or abusive behavior</li>
                            <li>Uses the Service fraudulently</li>
                            <li>Poses legal or reputational risk</li>
                        </ul>
                        <p class="text-gray-700 mb-4">
                            Upon termination, access to earnings, links, or data may be revoked.
                        </p>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">10. Disclaimers</h2>
                        <p class="text-gray-700 mb-4">
                            The Service is provided <strong>"as is"</strong> and <strong>"as available"</strong>,
                            without warranties of any kind.
                        </p>
                        <p class="text-gray-700 mb-4">
                            We disclaim all warranties, express or implied, including but not limited to:
                        </p>
                        <ul class="list-disc pl-6 text-gray-700 mb-4">
                            <li>Merchantability</li>
                            <li>Fitness for a particular purpose</li>
                            <li>Non-infringement</li>
                            <li>Availability or error-free operation</li>
                        </ul>
                        <p class="text-gray-700 mb-4">
                            Use of the Service is at your own risk.
                        </p>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">11. Limitation of Liability</h2>
                        <p class="text-gray-700 mb-4">
                            To the maximum extent permitted by law, {{ config('app.name') }} shall not be liable for
                            any indirect, incidental, special, consequential, or punitive damages, including but not
                            limited to:
                        </p>
                        <ul class="list-disc pl-6 text-gray-700 mb-4">
                            <li>Loss of revenue or profits</li>
                            <li>Loss of data or business opportunities</li>
                            <li>Service interruptions</li>
                            <li>Unauthorized access or misuse</li>
                        </ul>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">12. Changes to These Terms</h2>
                        <p class="text-gray-700 mb-4">
                            We may update these Terms from time to time. Material changes will be communicated via email
                            or within the Service.
                        </p>
                        <p class="text-gray-700 mb-4">
                            Continued use of {{ config('app.name') }} after changes constitutes acceptance of the
                            updated Terms.
                        </p>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">13. Governing Law</h2>
                        <p class="text-gray-700 mb-4">
                            These Terms shall be governed and interpreted in accordance with the laws applicable in the
                            jurisdiction where {{ config('app.name') }} operates, without regard to conflict of law
                            principles.
                        </p>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">14. Contact Information</h2>
                        <p class="text-gray-700 mb-4">
                            For questions regarding these Terms, contact us at:
                        </p>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-700">
                                <strong>Email:</strong> {{ 'support@' . config('app.domain') }}
                            </p>
                        </div>
                    </div>

                    <div class="border-t pt-8">
                        <p class="text-sm text-gray-500 text-center">
                            By using {{ config('app.name') }}, you confirm that you have read, understood, and agreed
                            to these Terms of Service.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="mt-8 text-center">
                <a href="{{ route('legal.privacy') }}" class="text-blue-600 hover:text-blue-800 mr-4">Privacy
                    Policy</a>
                <a href="{{ route('legal.about') }}" class="text-blue-600 hover:text-blue-800 mr-4">About Us</a>
                <a href="{{ route('legal.contact') }}" class="text-blue-600 hover:text-blue-800">Contact Us</a>
            </div>
        </div>
    </div>
</x-app-layout>
