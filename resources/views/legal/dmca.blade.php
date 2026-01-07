<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('DMCA Policy') }}
        </h2>
    </x-slot>

<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">DMCA Policy</h1>
                    <p class="text-lg text-gray-600">Digital Millennium Copyright Act compliance</p>
                </div>

                <div class="prose max-w-none">
                    <p class="text-gray-600 mb-6">{{ config('app.name') }} respects the intellectual property rights of others and expects its users to do the same. We comply with the Digital Millennium Copyright Act (DMCA) and respond to notices of alleged copyright infringement.</p>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Filing a DMCA Notice</h2>
                    <p class="text-gray-600 mb-6">If you believe that your copyrighted work has been copied in a way that constitutes copyright infringement, please provide us with the following information:</p>

                    <div class="bg-blue-50 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Required Information</h3>
                        <ol class="list-decimal list-inside space-y-3 text-gray-600">
                            <li><strong>Identification:</strong> A description of the copyrighted work you claim has been infringed</li>
                            <li><strong>Location:</strong> The specific URL or location where the infringing material is located</li>
                            <li><strong>Contact:</strong> Your contact information (name, address, phone number, email)</li>
                            <li><strong>Statement:</strong> A statement that you have a good faith belief that use is not authorized</li>
                            <li><strong>Accuracy:</strong> A statement that the information is accurate and you are authorized to act</li>
                            <li><strong>Signature:</strong> Your physical or electronic signature</li>
                        </ol>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">DMCA Contact Information</h2>
                    <div class="bg-gray-50 rounded-lg p-6 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="font-medium text-gray-900 mb-2">DMCA Agent</h3>
                                <p class="text-gray-600">Legal Department</p>
                                <p class="text-gray-600">{{ config('app.name') }}</p>
                                <p class="text-gray-600">Email: dmca@{{ config('app.domain') }}</p>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900 mb-2">Response Time</h3>
                                <p class="text-gray-600">We will respond to valid DMCA notices within 24-48 hours</p>
                                <p class="text-sm text-gray-500 mt-2">Counter-notices may take up to 10 business days</p>
                            </div>
                        </div>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Our Response Process</h2>
                    <div class="space-y-4 mb-6">
                        <div class="bg-green-50 rounded-lg p-4">
                            <h3 class="font-medium text-gray-900 mb-2">1. Review</h3>
                            <p class="text-gray-600">We review each DMCA notice for completeness and validity</p>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-4">
                            <h3 class="font-medium text-gray-900 mb-2">2. Action</h3>
                            <p class="text-gray-600">If valid, we remove or disable access to the infringing content</p>
                        </div>
                        <div class="bg-blue-50 rounded-lg p-4">
                            <h3 class="font-medium text-gray-900 mb-2">3. Notification</h3>
                            <p class="text-gray-600">We notify the user who posted the content and provide counter-notice information</p>
                        </div>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Counter-Notice</h2>
                    <p class="text-gray-600 mb-6">If you believe your content was removed in error, you may file a counter-notice:</p>

                    <div class="bg-yellow-50 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Counter-Notice Requirements</h3>
                        <ul class="list-disc list-inside space-y-2 text-gray-600">
                            <li>Your contact information</li>
                            <li>Identification of the removed content and its location</li>
                            <li>A statement under penalty of perjury that you have a good faith belief the content was removed by mistake</li>
                            <li>Consent to local federal court jurisdiction</li>
                            <li>Your physical or electronic signature</li>
                        </ul>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Repeat Infringers</h2>
                    <p class="text-gray-600 mb-6">We maintain a policy of terminating accounts of users who are repeat infringers of copyright or other intellectual property rights.</p>

                    <div class="bg-red-50 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Repeat Infringer Policy</h3>
                        <ul class="list-disc list-inside space-y-2 text-gray-600">
                            <li>First violation: Warning and content removal</li>
                            <li>Second violation: Temporary account suspension</li>
                            <li>Third violation: Permanent account termination</li>
                            <li>Appeals process available for each action</li>
                        </ul>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">False Claims</h2>
                    <p class="text-gray-600 mb-6">Please be aware that filing a false DMCA notice may result in legal consequences. You may be liable for damages, including costs and attorneys' fees.</p>

                    <div class="bg-orange-50 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-3">Penalties for False Claims</h3>
                        <ul class="list-disc list-inside space-y-2 text-gray-600">
                            <li>Monetary damages</li>
                            <li>Legal fees and costs</li>
                            <li>Potential criminal penalties</li>
                            <li>Loss of account privileges</li>
                        </ul>
                    </div>

                    <h2 class="text-2xl font-bold text-gray-900 mt-8 mb-4">Contact Information</h2>
                    <div class="bg-blue-50 rounded-lg p-6 mb-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <h3 class="font-medium text-gray-900 mb-2">DMCA Inquiries</h3>
                                <p class="text-gray-600">dmca@{{ config('app.domain') }}</p>
                                <p class="text-sm text-gray-500">For copyright infringement reports</p>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900 mb-2">Legal Department</h3>
                                <p class="text-gray-600">legal@{{ config('app.domain') }}</p>
                                <p class="text-sm text-gray-500">For other legal matters</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 text-center">
                    <a href="mailto:dmca@{{ config('app.domain') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                        File DMCA Notice
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
