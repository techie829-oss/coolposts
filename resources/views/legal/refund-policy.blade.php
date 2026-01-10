<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Refund Policy') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 text-gray-900">
                    <!-- Page Header -->
                    <div class="text-center mb-12">
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">Refund Policy</h1>
                        <p class="text-lg text-gray-600">Last updated: January 09, 2026</p>
                    </div>

                    <!-- Content -->
                    <div class="prose max-w-none text-gray-600">
                        <div class="bg-gray-50 rounded-xl p-8 text-center mb-8 border border-gray-100">
                            <p class="text-lg font-medium text-gray-900 mb-2">At this time, <strong>CoolPosts does not
                                    offer paid subscriptions or products</strong>.</p>
                            <p class="text-gray-600">Therefore, no refunds are processed currently.</p>
                        </div>

                        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Future Monetization</h2>
                        <p class="mb-4">If paid features are introduced in the future:</p>
                        <ul class="list-disc pl-6 mb-4 space-y-2">
                            <li>Refund terms will be clearly stated</li>
                            <li>Policies will be updated accordingly</li>
                        </ul>
                        <p class="mb-4">Until then, no refunds apply.</p>
                    </div>

                    <!-- Contact Box -->
                    <div class="mt-12 bg-blue-50 rounded-xl p-8 text-center">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Questions?</h3>
                        <p class="text-gray-600 mb-6">Contact us if you have any questions about billing or payments.
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
