<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('GDPR Policy') }}
        </h2>
    </x-slot>

<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">GDPR Privacy Policy</h1>
                    <p class="text-lg text-gray-600">General Data Protection Regulation compliance</p>
                </div>

                <div class="prose max-w-none">
                    <p class="text-gray-600 mb-6">This GDPR Privacy Policy explains how {{ config('app.name') }} collects, uses, and protects your personal data in compliance with the General Data Protection Regulation (GDPR).</p>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Data Controller</h2>
                    <div class="bg-blue-50 rounded-lg p-6 mb-6">
                        <p class="text-gray-600 mb-3"><strong>{{ config('app.name') }}</strong> is the data controller for your personal information.</p>
                        <p class="text-gray-600">Contact: privacy@{{ config('app.domain') }}</p>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Personal Data We Collect</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-green-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Account Information</h3>
                            <ul class="list-disc list-inside text-gray-600 space-y-1">
                                <li>Name and email address</li>
                                <li>Account preferences and settings</li>
                                <li>Payment information (encrypted)</li>
                                <li>Profile information</li>
                            </ul>
                        </div>
                        <div class="bg-blue-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Usage Data</h3>
                            <ul class="list-disc list-inside text-gray-600 space-y-1">
                                <li>Link clicks and analytics</li>
                                <li>Device and browser information</li>
                                <li>IP address and location data</li>
                                <li>Session and interaction data</li>
                            </ul>
                        </div>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Legal Basis for Processing</h2>
                    <p class="text-gray-600 mb-6">We process your personal data based on the following legal grounds:</p>

                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <div class="space-y-4">
                            <div>
                                <h3 class="font-medium text-gray-900 mb-2">Consent</h3>
                                <p class="text-gray-600">You have given clear consent for us to process your personal data for specific purposes.</p>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900 mb-2">Contract</h3>
                                <p class="text-gray-600">Processing is necessary for the performance of our service agreement with you.</p>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900 mb-2">Legitimate Interest</h3>
                                <p class="text-gray-600">Processing is necessary for our legitimate interests, such as improving our services.</p>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900 mb-2">Legal Obligation</h3>
                                <p class="text-gray-600">Processing is necessary for compliance with legal obligations.</p>
                            </div>
                        </div>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Your Rights Under GDPR</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div class="bg-yellow-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Access & Portability</h3>
                            <ul class="list-disc list-inside text-gray-600 space-y-1">
                                <li>Right to access your personal data</li>
                                <li>Right to data portability</li>
                                <li>Right to know how we process your data</li>
                                <li>Right to receive a copy of your data</li>
                            </ul>
                        </div>
                        <div class="bg-green-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-3">Control & Correction</h3>
                            <ul class="list-disc list-inside text-gray-600 space-y-1">
                                <li>Right to rectify inaccurate data</li>
                                <li>Right to erasure ("right to be forgotten")</li>
                                <li>Right to restrict processing</li>
                                <li>Right to object to processing</li>
                            </ul>
                        </div>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Data Retention</h2>
                    <p class="text-gray-600 mb-6">We retain your personal data only for as long as necessary to fulfill the purposes outlined in this policy:</p>

                    <div class="bg-blue-50 rounded-lg p-6 mb-6">
                        <div class="space-y-4">
                            <div>
                                <h3 class="font-medium text-gray-900 mb-2">Account Data</h3>
                                <p class="text-gray-600">Retained while your account is active and for 30 days after deletion</p>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900 mb-2">Analytics Data</h3>
                                <p class="text-gray-600">Retained for 2 years for service improvement purposes</p>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900 mb-2">Payment Data</h3>
                                <p class="text-gray-600">Retained for 7 years for legal and tax compliance</p>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900 mb-2">Log Data</h3>
                                <p class="text-gray-600">Retained for 90 days for security and troubleshooting</p>
                            </div>
                        </div>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Data Security</h2>
                    <p class="text-gray-600 mb-6">We implement appropriate technical and organizational measures to protect your personal data:</p>

                    <div class="bg-green-50 rounded-lg p-6 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h3 class="font-medium text-gray-900 mb-2">Technical Measures</h3>
                                <ul class="list-disc list-inside text-gray-600 space-y-1">
                                    <li>Encryption in transit and at rest</li>
                                    <li>Secure data centers</li>
                                    <li>Regular security audits</li>
                                    <li>Access controls and authentication</li>
                                </ul>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900 mb-2">Organizational Measures</h3>
                                <ul class="list-disc list-inside text-gray-600 space-y-1">
                                    <li>Employee training on data protection</li>
                                    <li>Data protection policies</li>
                                    <li>Incident response procedures</li>
                                    <li>Regular policy reviews</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Data Transfers</h2>
                    <p class="text-gray-600 mb-6">Your personal data may be transferred to and processed in countries outside the European Economic Area (EEA):</p>

                    <div class="bg-yellow-50 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">International Transfers</h3>
                        <ul class="list-disc list-inside text-gray-600 space-y-2">
                            <li>We ensure adequate protection through Standard Contractual Clauses</li>
                            <li>Transfers only to countries with adequate data protection laws</li>
                            <li>Third-party processors are bound by data protection agreements</li>
                            <li>You can request information about specific transfers</li>
                        </ul>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Exercising Your Rights</h2>
                    <p class="text-gray-600 mb-6">To exercise your GDPR rights, please contact us:</p>

                    <div class="bg-blue-50 rounded-lg p-6 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="font-medium text-gray-900 mb-2">Data Protection Officer</h3>
                                <p class="text-gray-600">dpo@{{ config('app.domain') }}</p>
                                <p class="text-sm text-gray-500">For GDPR-specific inquiries</p>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900 mb-2">Privacy Team</h3>
                                <p class="text-gray-600">privacy@{{ config('app.domain') }}</p>
                                <p class="text-sm text-gray-500">For general privacy questions</p>
                            </div>
                        </div>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Response Time</h2>
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <ul class="list-disc list-inside text-gray-600 space-y-2">
                            <li>We will respond to your request within 30 days</li>
                            <li>Complex requests may take up to 60 days</li>
                            <li>We will notify you if we need additional time</li>
                            <li>No fee for reasonable requests</li>
                        </ul>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Supervisory Authority</h2>
                    <p class="text-gray-600 mb-6">You have the right to lodge a complaint with your local data protection supervisory authority if you believe we have not addressed your concerns adequately.</p>

                    <div class="bg-red-50 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Contact Your Authority</h3>
                        <p class="text-gray-600">Find your local data protection authority at: <a href="https://edpb.europa.eu/about-edpb/board/members_en" class="text-blue-600 hover:text-blue-800">European Data Protection Board</a></p>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Updates to This Policy</h2>
                    <p class="text-gray-600 mb-6">We may update this GDPR Privacy Policy from time to time. We will notify you of any material changes by email or through our website.</p>

                    <div class="bg-yellow-50 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Last Updated</h3>
                        <p class="text-gray-600">This policy was last updated on {{ now()->format('F j, Y') }}</p>
                    </div>
                </div>

                <div class="mt-8 text-center">
                    <a href="mailto:privacy@{{ config('app.domain') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        Contact Privacy Team
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
