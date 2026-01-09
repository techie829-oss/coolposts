<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Refund Policy') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="text-center mb-8">
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">Refund Policy</h1>
                        <p class="text-lg text-gray-600">Our policy on refunds and cancellations</p>
                    </div>

                    <div class="prose max-w-none">
                        <p class="text-gray-600 mb-6">At {{ config('app.name') }}, we strive to provide excellent
                            service and value to our users. This refund policy outlines the terms and conditions for
                            refunds and cancellations.</p>

                        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Refund Scope & Eligibility</h2>
                        <p class="text-gray-600 mb-6">Refunds apply ONLY to <strong>digital subscription plans</strong>
                            and <strong>paid features</strong> purchased directly on CoolPosts. Refunds do NOT apply to
                            content consumption, tipped content, or any other non-subscription payments.</p>

                        <div class="bg-green-50 rounded-lg p-6 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">7-Day Limited Refund Window</h3>
                            <ul class="list-disc list-inside text-gray-600 space-y-2">
                                <li>Refund requests must be made within <strong>7 days</strong> of original purchase.
                                </li>
                                <li>Refunds are only issued if the paid features <strong>have NOT been used</strong>.
                                </li>
                                <li>We reserve the right to decline refunds for accounts with significant usage.</li>
                            </ul>
                        </div>

                        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Abuse & Policy Violations</h2>
                        <p class="text-gray-600 mb-4">To protect our platform and community, we strictly enforce the
                            following:</p>
                        <ul class="list-disc pl-6 text-gray-600 mb-6">
                            <li><strong>Suspended Accounts:</strong> No refunds will be issued for accounts suspended
                                due to violation of our <a href="{{ route('legal.terms') }}"
                                    class="text-blue-600 hover:text-blue-800">Terms of Service</a> or <a
                                    href="{{ route('legal.acceptable-use') }}"
                                    class="text-blue-600 hover:text-blue-800">Acceptable Use Policy</a>.</li>
                            <li><strong>Abuse:</strong> Repeated refund requests or chargeback abuse will result in
                                permanent account termination.</li>
                        </ul>

                        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">How to Request a Refund</h2>
                        <div class="bg-gray-50 rounded-lg p-6 mb-6">
                            <ol class="list-decimal list-inside space-y-3 text-gray-600">
                                <li><strong>Contact Support:</strong> Send an email to <a
                                        href="mailto:support@coolposts.site"
                                        class="text-blue-600 hover:text-blue-800">support@coolposts.site</a>
                                    with the subject "Refund Request"</li>
                                <li><strong>Provide Details:</strong> Include your account email and reason for refund
                                </li>
                                <li><strong>Review Process:</strong> Our team will review your request within 24 hours
                                </li>
                                <li><strong>Refund Processing:</strong> If approved, refund will be processed within 5-7
                                    business days</li>
                            </ol>
                        </div>

                        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Refund Methods & Timeline</h2>
                        <p class="text-gray-600 mb-6">Approved refunds will be processed back to the <strong>original
                                payment method only</strong>. Please allow 5-10 business days for the funds to appear in
                            your account, depending on your bank or payment provider.</p>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                            <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
                                <div
                                    class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="font-medium text-gray-900">Credit/Debit Cards</h3>
                                <p class="text-sm text-gray-600">3-5 business days</p>
                            </div>
                            <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
                                <div
                                    class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="font-medium text-gray-900">PayPal</h3>
                                <p class="text-sm text-gray-600">1-3 business days</p>
                            </div>
                            <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
                                <div
                                    class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1">
                                        </path>
                                    </svg>
                                </div>
                                <h3 class="font-medium text-gray-900">Bank Transfer</h3>
                                <p class="text-sm text-gray-600">5-10 business days</p>
                            </div>
                        </div>

                        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Cancellation Policy</h2>
                        <p class="text-gray-600 mb-6">You may cancel your subscription at any time. Cancellation will
                            take effect at the end of your current billing period:</p>

                        <div class="bg-yellow-50 rounded-lg p-6 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Important Notes</h3>
                            <ul class="list-disc list-inside text-gray-600 space-y-2">
                                <li>Cancellation does not automatically trigger a refund</li>
                                <li>You will continue to have access until the end of your billing period</li>
                                <li>No partial refunds for unused portions of billing periods</li>
                                <li>You can reactivate your subscription at any time</li>
                            </ul>
                        </div>

                        <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Contact Information</h2>
                        <p class="text-gray-600 mb-6">For refund requests or questions about this policy, please contact
                            us:</p>

                        <div class="bg-blue-50 rounded-lg p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h3 class="font-medium text-gray-900 mb-2">Email Support</h3>
                                    <p class="text-gray-600"><a href="mailto:support@coolposts.site"
                                            class="text-blue-600 hover:text-blue-800">support@coolposts.site</a></p>
                                    <p class="text-sm text-gray-500">Response within 24 hours</p>
                                </div>
                                <div>
                                    <h3 class="font-medium text-gray-900 mb-2">Refund Processing</h3>
                                    <p class="text-gray-600"><a href="mailto:support@coolposts.site"
                                            class="text-blue-600 hover:text-blue-800">support@coolposts.site</a></p>
                                    <p class="text-sm text-gray-500">For refund-specific inquiries</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 text-center">
                        <a href="{{ route('legal.contact') }}"
                            class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Contact Support
                        </a>
                        <p class="mt-4 text-sm text-gray-500">
                            By requesting a refund, you agree to our <a href="{{ route('legal.terms') }}"
                                class="text-blue-600 hover:text-blue-800">Terms of Service</a> and <a
                                href="{{ route('legal.privacy') }}" class="text-blue-600 hover:text-blue-800">Privacy
                                Policy</a>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
