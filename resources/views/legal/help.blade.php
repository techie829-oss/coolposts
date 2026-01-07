<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Help Center') }}
        </h2>
    </x-slot>

<div class="py-12">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4">Help Center</h1>
                    <p class="text-lg text-gray-600">Find the help you need to get the most out of our platform</p>
                </div>

                <!-- Search Section -->
                <div class="mb-8">
                    <div class="max-w-2xl mx-auto">
                        <div class="relative">
                            <input type="text" id="help-search" placeholder="Search for help articles..." class="w-full px-4 py-3 pl-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Help Categories -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-12">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-6 text-white">
                        <div class="flex items-center mb-4">
                            <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                            <h3 class="text-xl font-semibold">Getting Started</h3>
                        </div>
                        <p class="text-blue-100 mb-4">Learn the basics of creating and managing your links</p>
                        <a href="#getting-started" class="inline-flex items-center text-blue-100 hover:text-white">
                            Learn More
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>

                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-6 text-white">
                        <div class="flex items-center mb-4">
                            <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                            </svg>
                            <h3 class="text-xl font-semibold">Monetization</h3>
                        </div>
                        <p class="text-green-100 mb-4">Maximize your earnings with our monetization features</p>
                        <a href="#monetization" class="inline-flex items-center text-green-100 hover:text-white">
                            Learn More
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>

                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-lg p-6 text-white">
                        <div class="flex items-center mb-4">
                            <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <h3 class="text-xl font-semibold">Analytics</h3>
                        </div>
                        <p class="text-purple-100 mb-4">Understand your link performance and audience insights</p>
                        <a href="#analytics" class="inline-flex items-center text-purple-100 hover:text-white">
                            Learn More
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>

                    <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-lg p-6 text-white">
                        <div class="flex items-center mb-4">
                            <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <h3 class="text-xl font-semibold">Account Settings</h3>
                        </div>
                        <p class="text-yellow-100 mb-4">Manage your account, preferences, and security settings</p>
                        <a href="#account-settings" class="inline-flex items-center text-yellow-100 hover:text-white">
                            Learn More
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>

                    <div class="bg-gradient-to-br from-red-500 to-red-600 rounded-lg p-6 text-white">
                        <div class="flex items-center mb-4">
                            <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 109.75 9.75A9.75 9.75 0 0012 2.25z"></path>
                            </svg>
                            <h3 class="text-xl font-semibold">Troubleshooting</h3>
                        </div>
                        <p class="text-red-100 mb-4">Solve common issues and get back to creating links</p>
                        <a href="#troubleshooting" class="inline-flex items-center text-red-100 hover:text-white">
                            Learn More
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>

                    <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg p-6 text-white">
                        <div class="flex items-center mb-4">
                            <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <h3 class="text-xl font-semibold">API & Integration</h3>
                        </div>
                        <p class="text-indigo-100 mb-4">Integrate our platform with your applications</p>
                        <a href="{{ route('api.docs') }}" class="inline-flex items-center text-indigo-100 hover:text-white">
                            Learn More
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>

                <!-- Help Articles -->
                <div class="space-y-8">
                    <!-- Getting Started Section -->
                    <div id="getting-started" class="bg-gray-50 rounded-lg p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Getting Started</h2>

                        <div class="space-y-6">
                            <div class="bg-white rounded-lg p-6 shadow-sm">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Creating Your First Link</h3>
                                <div class="prose max-w-none">
                                    <ol class="list-decimal list-inside space-y-2 text-gray-600">
                                        <li>Sign up for an account or log in to your existing account</li>
                                        <li>Click "Create Link" in your dashboard</li>
                                        <li>Enter your original URL in the "Original URL" field</li>
                                        <li>Optionally customize your short code (e.g., "mybrand")</li>
                                        <li>Choose your monetization settings</li>
                                        <li>Click "Create Link" to generate your shortened URL</li>
                                    </ol>
                                </div>
                            </div>

                            <div class="bg-white rounded-lg p-6 shadow-sm">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Understanding Your Dashboard</h3>
                                <p class="text-gray-600 mb-4">Your dashboard provides an overview of your link performance and earnings:</p>
                                <ul class="list-disc list-inside space-y-2 text-gray-600">
                                    <li><strong>Total Links:</strong> Number of active links you've created</li>
                                    <li><strong>Total Clicks:</strong> Combined clicks across all your links</li>
                                    <li><strong>Total Earnings:</strong> Your accumulated earnings from monetized links</li>
                                    <li><strong>Pending Earnings:</strong> Earnings not yet available for withdrawal</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Monetization Section -->
                    <div id="monetization" class="bg-gray-50 rounded-lg p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Monetization Guide</h2>

                        <div class="space-y-6">
                            <div class="bg-white rounded-lg p-6 shadow-sm">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">How Monetization Works</h3>
                                <p class="text-gray-600 mb-4">When you enable monetization on a link, visitors will see a brief advertisement before being redirected to your original URL. You earn money based on:</p>
                                <ul class="list-disc list-inside space-y-2 text-gray-600">
                                    <li>Number of valid clicks</li>
                                    <li>Geographic location of visitors (CPM rates vary by country)</li>
                                    <li>Traffic quality and engagement</li>
                                    <li>Advertiser demand and competition</li>
                                </ul>
                            </div>

                            <div class="bg-white rounded-lg p-6 shadow-sm">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Optimizing Your Earnings</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <h4 class="font-medium text-gray-900 mb-2">Best Practices:</h4>
                                        <ul class="list-disc list-inside space-y-1 text-sm text-gray-600">
                                            <li>Share links on high-traffic platforms</li>
                                            <li>Use descriptive link titles</li>
                                            <li>Target audiences in high-CPM countries</li>
                                            <li>Maintain link quality and relevance</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900 mb-2">Avoid:</h4>
                                        <ul class="list-disc list-inside space-y-1 text-sm text-gray-600">
                                            <li>Click farming or artificial traffic</li>
                                            <li>Spam or misleading content</li>
                                            <li>Violating platform terms of service</li>
                                            <li>Using VPNs to manipulate location</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Analytics Section -->
                    <div id="analytics" class="bg-gray-50 rounded-lg p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Analytics & Insights</h2>

                        <div class="space-y-6">
                            <div class="bg-white rounded-lg p-6 shadow-sm">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Understanding Your Analytics</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <h4 class="font-medium text-gray-900 mb-2">Key Metrics:</h4>
                                        <ul class="list-disc list-inside space-y-1 text-sm text-gray-600">
                                            <li><strong>Clicks:</strong> Total number of link visits</li>
                                            <li><strong>Unique Visitors:</strong> Individual users who clicked</li>
                                            <li><strong>Conversion Rate:</strong> Percentage of clicks that completed monetization</li>
                                            <li><strong>Earnings per Click:</strong> Average revenue per visit</li>
                                        </ul>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900 mb-2">Geographic Data:</h4>
                                        <ul class="list-disc list-inside space-y-1 text-sm text-gray-600">
                                            <li>Country and city breakdown</li>
                                            <li>Device and browser information</li>
                                            <li>Referrer sources</li>
                                            <li>Time-based patterns</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Settings Section -->
                    <div id="account-settings" class="bg-gray-50 rounded-lg p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Account Settings</h2>

                        <div class="space-y-6">
                            <div class="bg-white rounded-lg p-6 shadow-sm">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Profile Management</h3>
                                <p class="text-gray-600 mb-4">Keep your account information up to date:</p>
                                <ul class="list-disc list-inside space-y-2 text-gray-600">
                                    <li>Update your email address and password</li>
                                    <li>Set your preferred currency for earnings</li>
                                    <li>Configure notification preferences</li>
                                    <li>Manage your payment information</li>
                                </ul>
                            </div>

                            <div class="bg-white rounded-lg p-6 shadow-sm">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Security Features</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <h4 class="font-medium text-gray-900 mb-2">Two-Factor Authentication:</h4>
                                        <p class="text-sm text-gray-600">Enable 2FA for enhanced account security using authenticator apps or SMS.</p>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900 mb-2">Login History:</h4>
                                        <p class="text-sm text-gray-600">Monitor your account activity and detect unauthorized access attempts.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Troubleshooting Section -->
                    <div id="troubleshooting" class="bg-gray-50 rounded-lg p-6">
                        <h2 class="text-2xl font-bold text-gray-900 mb-6">Troubleshooting</h2>

                        <div class="space-y-6">
                            <div class="bg-white rounded-lg p-6 shadow-sm">
                                <h3 class="text-lg font-semibold text-gray-900 mb-3">Common Issues & Solutions</h3>

                                <div class="space-y-4">
                                    <div class="border-l-4 border-red-500 pl-4">
                                        <h4 class="font-medium text-gray-900">Link Not Working</h4>
                                        <p class="text-sm text-gray-600 mt-1">Check if your original URL is still accessible. If the issue persists, contact support.</p>
                                    </div>

                                    <div class="border-l-4 border-yellow-500 pl-4">
                                        <h4 class="font-medium text-gray-900">Low Earnings</h4>
                                        <p class="text-sm text-gray-600 mt-1">Earnings depend on traffic quality and location. Focus on organic, high-quality traffic sources.</p>
                                    </div>

                                    <div class="border-l-4 border-blue-500 pl-4">
                                        <h4 class="font-medium text-gray-900">Analytics Not Updating</h4>
                                        <p class="text-sm text-gray-600 mt-1">Analytics update in real-time. If you don't see updates, try refreshing the page.</p>
                                    </div>

                                    <div class="border-l-4 border-green-500 pl-4">
                                        <h4 class="font-medium text-gray-900">Payment Issues</h4>
                                        <p class="text-sm text-gray-600 mt-1">Ensure your payment information is correct and you've reached the minimum withdrawal threshold.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Support -->
                <div class="mt-12 text-center bg-blue-50 rounded-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">Need More Help?</h2>
                    <p class="text-gray-600 mb-6">Our support team is available 24/7 to assist you with any questions or issues.</p>
                    <div class="space-x-4">
                        <a href="{{ route('legal.contact') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                            Contact Support
                        </a>
                        <a href="{{ route('legal.faq') }}" class="inline-flex items-center px-6 py-3 border border-gray-300 text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            View FAQ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('help-search').addEventListener('input', function(e) {
    const searchTerm = e.target.value.toLowerCase();
    const sections = document.querySelectorAll('[id$="-section"]');

    sections.forEach(section => {
        const title = section.querySelector('h2, h3').textContent.toLowerCase();
        const content = section.textContent.toLowerCase();

        if (title.includes(searchTerm) || content.includes(searchTerm)) {
            section.style.display = 'block';
        } else {
            section.style.display = 'none';
        }
    });
});
</script>
</x-app-layout>
