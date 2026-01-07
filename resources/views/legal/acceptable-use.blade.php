<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Acceptable Use Policy') }}
        </h2>
    </x-slot>

<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">Acceptable Use Policy</h1>
                    <p class="text-lg text-gray-600">Guidelines for using our platform responsibly</p>
                </div>

                <div class="prose max-w-none">
                    <p class="text-gray-600 mb-6">This Acceptable Use Policy ("AUP") governs your use of {{ config('app.name') }} services. By using our platform, you agree to comply with this policy.</p>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Prohibited Activities</h2>
                    <p class="text-gray-600 mb-6">The following activities are strictly prohibited on our platform:</p>

                    <div class="bg-red-50 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Illegal Content</h3>
                        <ul class="list-disc list-inside text-gray-600 space-y-2">
                            <li>Content that violates any applicable laws or regulations</li>
                            <li>Copyright infringement or intellectual property violations</li>
                            <li>Distribution of malware, viruses, or harmful code</li>
                            <li>Phishing scams or fraudulent activities</li>
                        </ul>
                    </div>

                    <div class="bg-red-50 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Harmful Content</h3>
                        <ul class="list-disc list-inside text-gray-600 space-y-2">
                            <li>Explicit adult content or pornography</li>
                            <li>Violence, hate speech, or discrimination</li>
                            <li>Harassment, bullying, or threats</li>
                            <li>Content that promotes self-harm or dangerous activities</li>
                        </ul>
                    </div>

                    <div class="bg-red-50 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Spam and Abuse</h3>
                        <ul class="list-disc list-inside text-gray-600 space-y-2">
                            <li>Mass unsolicited messages or spam</li>
                            <li>Click fraud or artificial traffic generation</li>
                            <li>Automated bot activity or scraping</li>
                            <li>Circumventing rate limits or security measures</li>
                        </ul>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Content Guidelines</h2>
                    <p class="text-gray-600 mb-6">All content shared through our platform must comply with these guidelines:</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-green-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">✅ Allowed Content</h3>
                            <ul class="list-disc list-inside text-gray-600 space-y-1">
                                <li>Legitimate business and personal links</li>
                                <li>Educational and informational content</li>
                                <li>News and media sharing</li>
                                <li>Social media and networking</li>
                                <li>E-commerce and legitimate sales</li>
                            </ul>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">⚠️ Restricted Content</h3>
                            <ul class="list-disc list-inside text-gray-600 space-y-1">
                                <li>Controversial political content</li>
                                <li>Religious content that promotes hate</li>
                                <li>Gambling or betting sites</li>
                                <li>Cryptocurrency scams</li>
                                <li>Unverified health claims</li>
                            </ul>
                        </div>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Monetization Guidelines</h2>
                    <p class="text-gray-600 mb-6">When using our monetization features, you must follow these guidelines:</p>

                    <div class="bg-blue-50 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Monetization Rules</h3>
                        <ul class="list-disc list-inside text-gray-600 space-y-2">
                            <li>Only monetize legitimate, high-quality content</li>
                            <li>Do not use deceptive practices to increase clicks</li>
                            <li>Respect user experience and avoid excessive ads</li>
                            <li>Comply with advertising network policies</li>
                            <li>Do not manipulate traffic sources or locations</li>
                        </ul>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Account Security</h2>
                    <p class="text-gray-600 mb-6">You are responsible for maintaining the security of your account:</p>

                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <ul class="list-disc list-inside text-gray-600 space-y-2">
                            <li>Use strong, unique passwords</li>
                            <li>Enable two-factor authentication when available</li>
                            <li>Do not share your account credentials</li>
                            <li>Report suspicious activity immediately</li>
                            <li>Keep your contact information updated</li>
                        </ul>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Enforcement</h2>
                    <p class="text-gray-600 mb-6">We reserve the right to take action against violations of this policy:</p>

                    <div class="bg-orange-50 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Possible Actions</h3>
                        <ul class="list-disc list-inside text-gray-600 space-y-2">
                            <li>Warning and content removal</li>
                            <li>Temporary account suspension</li>
                            <li>Permanent account termination</li>
                            <li>Forfeiture of earnings</li>
                            <li>Legal action if necessary</li>
                        </ul>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Reporting Violations</h2>
                    <p class="text-gray-600 mb-6">If you encounter content that violates this policy, please report it:</p>

                    <div class="bg-blue-50 rounded-lg p-6 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h3 class="font-medium text-gray-900 mb-2">Email Report</h3>
                                <p class="text-gray-600">abuse@{{ config('app.domain') }}</p>
                                <p class="text-sm text-gray-500">For policy violations</p>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900 mb-2">Support Contact</h3>
                                <p class="text-gray-600">support@{{ config('app.domain') }}</p>
                                <p class="text-sm text-gray-500">For general issues</p>
                            </div>
                        </div>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Updates to Policy</h2>
                    <p class="text-gray-600 mb-6">We may update this Acceptable Use Policy from time to time. Continued use of our services after changes constitutes acceptance of the updated policy.</p>

                    <div class="bg-yellow-50 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Your Responsibility</h3>
                        <p class="text-gray-600">It is your responsibility to review this policy regularly and ensure your use of our platform complies with current guidelines.</p>
                    </div>
                </div>

                <div class="mt-8 text-center">
                    <a href="{{ route('legal.contact') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Report Violation
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
