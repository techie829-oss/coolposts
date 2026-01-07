<x-app-layout>
    <x-slot name="title">Privacy Policy - CoolPosts | Data Protection & Privacy</x-slot>

    <x-slot name="head">
        <meta name="description"
            content="Read CoolPosts Privacy Policy. Learn how we protect your data, handle personal information, and ensure your privacy while using our link monetization platform.">
        <meta name="keywords"
            content="privacy policy, data protection, privacy rights, personal data, GDPR compliance, data security, CoolPosts privacy">
        <meta name="robots" content="index, follow">
        <link rel="canonical" href="{{ url('/privacy-policy') }}">

        <!-- Open Graph / Facebook -->
        <meta property="og:type" content="website">
        <meta property="og:url" content="{{ url('/privacy-policy') }}">
        <meta property="og:title" content="Privacy Policy - CoolPosts | Data Protection & Privacy">
        <meta property="og:description"
            content="Read CoolPosts Privacy Policy. Learn how we protect your data, handle personal information, and ensure your privacy while using our link monetization platform.">
        <meta property="og:image" content="{{ asset('images/og-privacy.jpg') }}">
        <meta property="og:site_name" content="CoolPosts">
        <meta property="og:locale" content="en_US">

        <!-- Twitter -->
        <meta property="twitter:card" content="summary_large_image">
        <meta property="twitter:url" content="{{ url('/privacy-policy') }}">
        <meta property="twitter:title" content="Privacy Policy - CoolPosts | Data Protection & Privacy">
        <meta property="twitter:description"
            content="Read CoolPosts Privacy Policy. Learn how we protect your data, handle personal information, and ensure your privacy while using our link monetization platform.">
        <meta property="twitter:image" content="{{ asset('images/og-privacy.jpg') }}">
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Privacy Policy') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <!-- Header -->
                <div
                    class="relative overflow-hidden bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-500 px-6 py-12 sm:px-12">
                    <div class="absolute inset-0 opacity-20">
                        <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                            <path d="M100 0 C 80 100 50 100 0 0 Z" fill="white" />
                        </svg>
                    </div>
                    <div class="relative z-10 text-center">
                        <h1 class="text-4xl font-extrabold text-white tracking-tight mb-3 drop-shadow-md">Privacy Policy
                        </h1>
                        <div
                            class="inline-flex items-center space-x-2 bg-white/20 backdrop-blur-md rounded-full px-4 py-1 border border-white/30">
                            <i class="fas fa-user-shield text-emerald-100 text-sm"></i>
                            <p class="text-emerald-50 font-medium text-sm">Last updated: {{ date('F d, Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="px-6 py-8 prose prose-lg max-w-none">
                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">1. Introduction</h2>
                        <p class="text-gray-700 mb-4">
                            {{ $platformName }} ("we," "our," or "us") is committed to protecting your privacy. This
                            Privacy Policy explains how we collect, use, disclose, and safeguard your information when
                            you use our link sharing and monetization platform.
                        </p>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">2. Information We Collect</h2>

                        <h3 class="text-xl font-semibold text-gray-800 mb-3">2.1 Personal Information</h3>
                        <p class="text-gray-700 mb-4">We collect information you provide directly to us:</p>
                        <ul class="list-disc pl-6 text-gray-700 mb-4">
                            <li>Name and email address when you register</li>
                            <li>Payment information for subscriptions</li>
                            <li>Profile information and preferences</li>
                            <li>Communication with our support team</li>
                        </ul>

                        <h3 class="text-xl font-semibold text-gray-800 mb-3">2.2 Usage Information</h3>
                        <p class="text-gray-700 mb-4">We automatically collect certain information:</p>
                        <ul class="list-disc pl-6 text-gray-700 mb-4">
                            <li>IP address and device information</li>
                            <li>Browser type and operating system</li>
                            <li>Pages visited and time spent</li>
                            <li>Link clicks and interactions</li>
                            <li>Referral sources and campaigns</li>
                        </ul>

                        <h3 class="text-xl font-semibold text-gray-800 mb-3">2.3 Cookies and Tracking</h3>
                        <p class="text-gray-700 mb-4">We use cookies and similar technologies to:</p>
                        <ul class="list-disc pl-6 text-gray-700 mb-4">
                            <li>Remember your preferences and settings</li>
                            <li>Analyze website traffic and usage</li>
                            <li>Provide personalized content and ads</li>
                            <li>Improve our services and user experience</li>
                        </ul>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">3. How We Use Your Information</h2>
                        <p class="text-gray-700 mb-4">We use the collected information for:</p>
                        <ul class="list-disc pl-6 text-gray-700 mb-4">
                            <li>Providing and maintaining our services</li>
                            <li>Processing payments and subscriptions</li>
                            <li>Analyzing usage patterns and improving features</li>
                            <li>Personalizing your experience</li>
                            <li>Communicating with you about updates and offers</li>
                            <li>Preventing fraud and ensuring security</li>
                            <li>Complying with legal obligations</li>
                        </ul>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">4. Information Sharing and Disclosure</h2>
                        <p class="text-gray-700 mb-4">We do not sell, trade, or rent your personal information. We may
                            share information in the following circumstances:</p>

                        <h3 class="text-xl font-semibold text-gray-800 mb-3">4.1 Service Providers</h3>
                        <p class="text-gray-700 mb-4">We may share information with trusted third-party service
                            providers who assist us in operating our platform, such as:</p>
                        <ul class="list-disc pl-6 text-gray-700 mb-4">
                            <li>Payment processors (Stripe, PayPal, etc.)</li>
                            <li>Analytics services (Google Analytics)</li>
                            <li>Email service providers</li>
                            <li>Cloud hosting and storage services</li>
                        </ul>

                        <h3 class="text-xl font-semibold text-gray-800 mb-3">4.2 Legal Requirements</h3>
                        <p class="text-gray-700 mb-4">We may disclose information when required by law or to protect our
                            rights and safety.</p>

                        <h3 class="text-xl font-semibold text-gray-800 mb-3">4.3 Business Transfers</h3>
                        <p class="text-gray-700 mb-4">In the event of a merger, acquisition, or sale of assets, user
                            information may be transferred as part of the transaction.</p>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">5. Data Security</h2>
                        <p class="text-gray-700 mb-4">We implement appropriate security measures to protect your
                            information:</p>
                        <ul class="list-disc pl-6 text-gray-700 mb-4">
                            <li>Encryption of sensitive data in transit and at rest</li>
                            <li>Regular security audits and updates</li>
                            <li>Access controls and authentication</li>
                            <li>Secure payment processing</li>
                            <li>Regular backups and disaster recovery</li>
                        </ul>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">6. Your Rights and Choices</h2>

                        <h3 class="text-xl font-semibold text-gray-800 mb-3">6.1 Access and Control</h3>
                        <p class="text-gray-700 mb-4">You have the right to:</p>
                        <ul class="list-disc pl-6 text-gray-700 mb-4">
                            <li>Access your personal information</li>
                            <li>Update or correct your information</li>
                            <li>Delete your account and data</li>
                            <li>Export your data in a portable format</li>
                            <li>Opt-out of marketing communications</li>
                        </ul>

                        <h3 class="text-xl font-semibold text-gray-800 mb-3">6.2 Cookie Preferences</h3>
                        <p class="text-gray-700 mb-4">You can control cookie settings through your browser preferences.
                            However, disabling certain cookies may affect functionality.</p>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">7. GDPR Compliance (EU Users)</h2>
                        <p class="text-gray-700 mb-4">For users in the European Union, we comply with the General Data
                            Protection Regulation (GDPR):</p>
                        <ul class="list-disc pl-6 text-gray-700 mb-4">
                            <li><strong>Legal Basis:</strong> We process data based on consent, contract performance,
                                and legitimate interests</li>
                            <li><strong>Data Retention:</strong> We retain data only as long as necessary for the
                                purposes outlined</li>
                            <li><strong>Data Transfer:</strong> We ensure adequate protection for international data
                                transfers</li>
                            <li><strong>Breach Notification:</strong> We notify authorities and users of data breaches
                                within 72 hours</li>
                        </ul>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">8. Children's Privacy</h2>
                        <p class="text-gray-700 mb-4">
                            Our service is not intended for children under 13 years of age. We do not knowingly collect
                            personal information from children under 13. If you believe we have collected such
                            information, please contact us immediately.
                        </p>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">9. Third-Party Links</h2>
                        <p class="text-gray-700 mb-4">
                            Our platform contains links to third-party websites. We are not responsible for the privacy
                            practices of these external sites. We encourage you to review their privacy policies.
                        </p>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">10. Data Retention</h2>
                        <p class="text-gray-700 mb-4">We retain your information for as long as necessary to:</p>
                        <ul class="list-disc pl-6 text-gray-700 mb-4">
                            <li>Provide our services</li>
                            <li>Comply with legal obligations</li>
                            <li>Resolve disputes</li>
                            <li>Enforce our agreements</li>
                        </ul>
                        <p class="text-gray-700 mb-4">Account data is typically retained for 3 years after account
                            deletion, unless required by law.</p>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">11. Changes to This Policy</h2>
                        <p class="text-gray-700 mb-4">
                            We may update this Privacy Policy from time to time. We will notify you of any material
                            changes via email or through our platform. Your continued use of our service after changes
                            constitutes acceptance of the updated policy.
                        </p>
                    </div>

                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">12. Contact Us</h2>
                        <p class="text-gray-700 mb-4">
                            If you have questions about this Privacy Policy or our data practices, please contact us:
                        </p>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-gray-700">
                                <strong>Email:</strong> techie829@gmail.com<br>
                                {{-- <strong>Address:</strong> [Your Business Address]<br>
                                <strong>Phone:</strong> [Your Phone Number]<br> --}}
                                <strong>Data Protection Officer:</strong> techie829@gmail.com
                            </p>
                        </div>
                    </div>

                    <div class="border-t pt-8">
                        <p class="text-sm text-gray-500 text-center">
                            This Privacy Policy is effective as of {{ date('F d, Y') }} and will remain in effect except
                            with respect to any changes in its provisions in the future.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="mt-8 text-center">
                <a href="{{ route('legal.terms') }}" class="text-blue-600 hover:text-blue-800 mr-4">Terms of Service</a>
                <a href="{{ route('legal.cookies') }}" class="text-blue-600 hover:text-blue-800 mr-4">Cookie Policy</a>
                <a href="{{ route('legal.gdpr') }}" class="text-blue-600 hover:text-blue-800 mr-4">GDPR</a>
                <a href="{{ route('legal.contact') }}" class="text-blue-600 hover:text-blue-800">Contact Us</a>
            </div>
        </div>
    </div>
</x-app-layout>