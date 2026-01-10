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
                    <div class="text-center mb-10">
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">Frequently Asked Questions</h1>
                        <p class="text-lg text-gray-600">Common questions about using CoolPosts.</p>
                    </div>

                    <div class="space-y-8">
                        <!-- About the Platform -->
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-4 border-b pb-2">About CoolPosts</h2>
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">What is CoolPosts?</h3>
                                    <p class="text-gray-600">CoolPosts is an AI-powered blogging and content creation
                                        platform. It allows users to create, publish, and share content easily, with
                                        optional tools for monetization.</p>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">How does monetization work?
                                    </h3>
                                    <p class="text-gray-600">When monetization is enabled on your links or content, ads
                                        may be displayed to visitors. You earn revenue based on valid engagement and
                                        traffic quality.</p>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Is CoolPosts free to use?</h3>
                                    <p class="text-gray-600">Yes. Creating an account, publishing blog posts, and
                                        shortening links is completely free. Monetization features are optional.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Usage -->
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-4 border-b pb-2">Usage</h2>
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">How do I create a shortened
                                        link?</h3>
                                    <p class="text-gray-600">From your dashboard, click "Create Link," paste your
                                        destination URL, and click "Shorten." You can then share this link immediately.
                                    </p>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">How are views counted?</h3>
                                    <p class="text-gray-600">Premium users get higher rate limits and additional
                                        endpoints. Check our <a href="{{ route('docs.index') }}"
                                            class="text-blue-600 hover:text-blue-800">API
                                            Documentation</a>.</p>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">When do earnings appear?</h3>
                                    <p class="text-gray-600">Earnings are estimated in real-time but may take up to 24
                                        hours to be fully validated and reflected in your final balance.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Account & Payments -->
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 mb-4 border-b pb-2">Account & Payments</h2>
                            <div class="space-y-6">
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Do I need an account?</h3>
                                    <p class="text-gray-600">Yes, you need an account to manage your links, track
                                        analytics, and accrue earnings. Sign up is quick and easy.</p>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2">How do payouts work?</h3>
                                    <p class="text-gray-600">Once your earnings reach the minimum withdrawal amount, you
                                        can request a payout via your preferred payment method from the dashboard.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="mt-12 pt-8 border-t border-gray-200 text-center">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Still have questions?</h3>
                        <p class="text-gray-600 mb-4">You can reach us via the <a href="{{ route('legal.contact') }}"
                                class="text-blue-600 hover:text-blue-800 font-medium">contact page</a>.</p>
                        <p class="text-sm text-gray-500">
                            By using our service, you agree to our <a href="{{ route('legal.terms') }}"
                                class="text-gray-600 hover:text-gray-900 underline">Terms of Service</a>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
