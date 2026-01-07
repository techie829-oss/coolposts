<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Frequently Asked Questions') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h1>
                        <p class="text-lg text-gray-600">Find answers to common questions about our link sharing platform
                        </p>
                    </div>

                    <div class="space-y-6">
                        <!-- Getting Started -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Getting Started</h2>

                            <div class="space-y-4">
                                <div class="border-b border-gray-200 pb-4">
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">How do I create my first link?
                                    </h3>
                                    <p class="text-gray-600">Sign up for an account, then click "Create Link" in your
                                        dashboard. Enter your original URL, customize the short code if desired, and
                                        save. Your shortened link will be ready to use immediately.</p>
                                </div>

                                <div class="border-b border-gray-200 pb-4">
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Is there a limit to how many
                                        links I can create?</h3>
                                    <p class="text-gray-600">Free accounts can create up to 10 links. Premium
                                        subscribers get unlimited link creation and additional features like custom
                                        domains and advanced analytics.</p>
                                </div>

                                <div class="border-b border-gray-200 pb-4">
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Can I customize my short links?
                                    </h3>
                                    <p class="text-gray-600">Yes! You can choose custom short codes for your links
                                        (e.g., yourdomain.com/mybrand). Premium users can also use custom domains.</p>
                                </div>

                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">How long do my links stay active?
                                    </h3>
                                    <p class="text-gray-600">Your links remain active indefinitely as long as your
                                        account is in good standing. You can also set expiration dates for specific
                                        links if needed.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Monetization -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Monetization & Earnings</h2>

                            <div class="space-y-4">
                                <div class="border-b border-gray-200 pb-4">
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">How does link monetization work?
                                    </h3>
                                    <p class="text-gray-600">When someone clicks your monetized link, they'll see a
                                        brief advertisement before being redirected to your original URL. You earn money
                                        based on the number of clicks and your location's CPM rates.</p>
                                </div>

                                <div class="border-b border-gray-200 pb-4">
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">How much can I earn per click?
                                    </h3>
                                    <p class="text-gray-600">Earnings vary by location and traffic quality. Typically,
                                        you can earn between $0.001 to $0.05 per click, with higher rates for premium
                                        traffic sources and certain geographic locations.</p>
                                </div>

                                <div class="border-b border-gray-200 pb-4">
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">When do I get paid?</h3>
                                    <p class="text-gray-600">We process payments monthly. You can request withdrawals
                                        once you reach the minimum threshold (₹100 for Indian users, $10 for
                                        international users).</p>
                                </div>

                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">What payment methods do you
                                        support?</h3>
                                    <p class="text-gray-600">We support PayPal, Stripe, bank transfers, UPI (India), and
                                        cryptocurrency payments. Payment options may vary by region.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Analytics -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Analytics & Tracking</h2>

                            <div class="space-y-4">
                                <div class="border-b border-gray-200 pb-4">
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">What analytics do you provide?
                                    </h3>
                                    <p class="text-gray-600">We provide comprehensive analytics including click counts,
                                        geographic data, device information, referrer sources, and real-time tracking.
                                        Premium users get access to advanced analytics and custom reports.</p>
                                </div>

                                <div class="border-b border-gray-200 pb-4">
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">How accurate are the click
                                        statistics?</h3>
                                    <p class="text-gray-600">Our system filters out bot traffic and invalid clicks to
                                        provide accurate statistics. We use advanced fraud detection to ensure data
                                        quality.</p>
                                </div>

                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Can I export my analytics data?
                                    </h3>
                                    <p class="text-gray-600">Yes! You can export your analytics data in CSV, JSON, or
                                        PDF formats. Premium users get access to API endpoints for automated data
                                        retrieval.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Security & Privacy -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Security & Privacy</h2>

                            <div class="space-y-4">
                                <div class="border-b border-gray-200 pb-4">
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Is my data secure?</h3>
                                    <p class="text-gray-600">Absolutely! We use industry-standard encryption, secure
                                        servers, and follow GDPR compliance guidelines. Your data is never sold to third
                                        parties.</p>
                                </div>

                                <div class="border-b border-gray-200 pb-4">
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Can I password-protect my links?
                                    </h3>
                                    <p class="text-gray-600">Yes! Premium users can add password protection to their
                                        links, ensuring only authorized users can access the content.</p>
                                </div>

                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">What happens if I violate the
                                        terms of service?</h3>
                                    <p class="text-gray-600">We have a zero-tolerance policy for spam, malware, or
                                        illegal content. Violations may result in account suspension or termination.
                                        Please review our <a href="{{ route('legal.acceptable-use') }}"
                                            class="text-blue-600 hover:text-blue-800">Acceptable Use Policy</a>.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Technical Support -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h2 class="text-xl font-semibold text-gray-900 mb-4">Technical Support</h2>

                            <div class="space-y-4">
                                <div class="border-b border-gray-200 pb-4">
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">What if my link isn't working?
                                    </h3>
                                    <p class="text-gray-600">First, check if your original URL is still accessible. If
                                        the issue persists, contact our support team with your link details and we'll
                                        investigate immediately.</p>
                                </div>

                                <div class="border-b border-gray-200 pb-4">
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">Do you have an API?</h3>
                                    <p class="text-gray-600">Yes! We provide a comprehensive REST API for developers.
                                        Premium users get higher rate limits and additional endpoints. Check our <a
                                            href="{{ route('api.docs') }}" class="text-blue-600 hover:text-blue-800">API
                                            Documentation</a>.</p>
                                </div>

                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">How can I contact support?</h3>
                                    <p class="text-gray-600">You can reach our support team through our <a
                                            href="{{ route('legal.contact') }}"
                                            class="text-blue-600 hover:text-blue-800">contact form</a>, email at
                                        support@{{ config('app.domain') }}, or through the help section in your dashboard.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Section -->
                    <div class="mt-12 text-center bg-blue-50 rounded-lg p-8">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Still Have Questions?</h2>
                        <p class="text-gray-600 mb-6">Our support team is here to help you get the most out of our
                            platform.</p>
                        <div class="space-x-4">
                            <a href="{{ route('legal.contact') }}"
                                class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                                Contact Support
                            </a>
                            <a href="{{ route('legal.help') }}"
                                class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Help Center
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
