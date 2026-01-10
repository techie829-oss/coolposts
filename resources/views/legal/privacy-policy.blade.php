<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Privacy Policy') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900">
                    <!-- Page Header -->
                    <div class="text-center mb-12">
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">Privacy Policy</h1>
                        <p class="text-lg text-gray-600">Last updated: January 09, 2026</p>
                    </div>

                    <!-- Content -->
                    <div class="prose max-w-none text-gray-600">
                        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">1. Overview</h2>
                        <p class="mb-4">Your privacy matters. This Privacy Policy explains how CoolPosts collects,
                            uses, and protects your information.</p>
                        <p class="mb-4">CoolPosts is an <strong>independently operated platform</strong>, not a
                            registered company.</p>

                        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">2. Information We Collect</h2>
                        <p class="mb-4">We may collect:</p>
                        <ul class="list-disc pl-6 mb-4 space-y-2">
                            <li>Basic account information (if you register)</li>
                            <li>Email address (for communication)</li>
                            <li>Usage data (pages visited, interactions)</li>
                            <li>Cookies and analytics data</li>
                        </ul>
                        <div class="bg-gray-50 p-4 rounded-lg border border-gray-100">
                            <p class="text-sm font-semibold text-gray-800">We do <strong>not</strong> sell personal
                                data.</p>
                        </div>

                        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">3. How We Use Information</h2>
                        <p class="mb-4">Information is used to:</p>
                        <ul class="list-disc pl-6 mb-4 space-y-2">
                            <li>Improve site performance</li>
                            <li>Provide relevant content</li>
                            <li>Communicate platform updates</li>
                            <li>Maintain security and prevent abuse</li>
                        </ul>

                        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">4. Cookies</h2>
                        <p class="mb-4">CoolPosts uses cookies to:</p>
                        <ul class="list-disc pl-6 mb-4 space-y-2">
                            <li>Understand user behavior</li>
                            <li>Improve user experience</li>
                            <li>Analyze traffic</li>
                        </ul>
                        <p class="mb-4">You may disable cookies in your browser settings.</p>

                        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">5. Third-Party Services</h2>
                        <p class="mb-4">We may use third-party tools such as analytics or advertising platforms. These
                            services may collect data according to their own privacy policies.</p>

                        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">6. Data Security</h2>
                        <p class="mb-4">We take reasonable steps to protect user data but cannot guarantee absolute
                            security.</p>
                    </div>

                    <!-- Contact Box -->
                    <div class="mt-12 bg-blue-50 rounded-xl p-8 text-center">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Privacy Questions?</h3>
                        <p class="text-gray-600 mb-6">Contact us regarding your personal data and privacy settings.</p>
                        <a href="mailto:support@coolposts.site"
                            class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200">
                            Contact Privacy Support
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
