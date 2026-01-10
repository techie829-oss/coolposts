<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Terms of Service') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900">
                    <!-- Page Header -->
                    <div class="text-center mb-12">
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">Terms of Service</h1>
                        <p class="text-lg text-gray-600">Last updated: January 09, 2026</p>
                    </div>

                    <!-- Content -->
                    <div class="prose max-w-none text-gray-600">
                        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">1. Acceptance of Terms</h2>
                        <p class="mb-4">By accessing or using <strong>CoolPosts</strong> (“the Service”), you agree to
                            be bound by these Terms of Service. If you do not agree with any part of these terms, please
                            do not use the Service.</p>

                        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">2. About the Platform</h2>
                        <p class="mb-4">CoolPosts is an <strong>independently operated online platform</strong> that
                            provides:</p>
                        <ul class="list-disc pl-6 mb-4 space-y-2">
                            <li>Blog and content publishing</li>
                            <li>Link sharing and content discovery</li>
                            <li>Educational and informational resources</li>
                            <li>Future monetization and creator tools</li>
                        </ul>
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-r mb-6">
                            <p class="text-yellow-800">CoolPosts is <strong>not currently a registered company</strong>
                                and is operated as an early-stage independent project.</p>
                        </div>

                        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">3. User Responsibility</h2>
                        <p class="mb-4">By using CoolPosts, you agree that you will:</p>
                        <ul class="list-disc pl-6 mb-4 space-y-2">
                            <li>Provide accurate information where required</li>
                            <li>Use the platform lawfully</li>
                            <li>Respect applicable local and international laws</li>
                            <li>Take full responsibility for any content you publish or share</li>
                        </ul>

                        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">4. Acceptable Use</h2>
                        <p class="mb-4">You agree <strong>not</strong> to use CoolPosts for:</p>
                        <ul class="list-disc pl-6 mb-4 space-y-2">
                            <li>Illegal or unlawful purposes</li>
                            <li>Copyright infringement</li>
                            <li>Malware, phishing, or spam</li>
                            <li>Fraud, misleading content, or artificial traffic generation</li>
                            <li>Abuse, harassment, or harmful activities</li>
                        </ul>
                        <p class="mb-4">Violation may result in content removal or access restriction.</p>

                        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">5. Content Responsibility</h2>
                        <p class="mb-4">All content published on CoolPosts is the responsibility of the user who
                            publishes it.</p>
                        <p class="mb-4">CoolPosts does <strong>not</strong> actively monitor all content but reserves
                            the right to remove content that violates these terms or applicable laws.</p>

                        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">6. Monetization & Payments</h2>
                        <p class="mb-4">Some features may include advertising or monetization in the future.</p>
                        <ul class="list-disc pl-6 mb-4 space-y-2">
                            <li>Monetization terms may change as the platform evolves</li>
                            <li>Payment features will be clearly communicated when introduced</li>
                            <li>No guaranteed earnings are implied</li>
                        </ul>

                        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">7. Intellectual Property</h2>
                        <p class="mb-4">All original platform design, branding, and software belong to CoolPosts.</p>
                        <p class="mb-4">Users retain ownership of their own content but grant CoolPosts permission to
                            display and distribute it through the platform.</p>

                        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">8. Disclaimer</h2>
                        <p class="mb-4">The Service is provided <strong>“as is”</strong> and <strong>“as
                                available”</strong> without warranties of any kind.</p>
                        <p class="mb-4">CoolPosts makes no guarantees regarding accuracy, availability, or
                            uninterrupted access.</p>

                        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">9. Limitation of Liability</h2>
                        <p class="mb-4">CoolPosts shall not be liable for any indirect, incidental, or consequential
                            damages arising from the use of the Service.</p>

                        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">10. Changes to Terms</h2>
                        <p class="mb-4">These Terms may be updated from time to time. Continued use of the Service
                            means acceptance of updated terms.</p>
                    </div>

                    <!-- Contact Box -->
                    <div class="mt-12 bg-blue-50 rounded-xl p-8 text-center">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Questions about our Terms?</h3>
                        <p class="text-gray-600 mb-6">We're here to help clarify any aspect of our service agreement.
                        </p>
                        <a href="mailto:support@coolposts.site"
                            class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200">
                            Contact Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
