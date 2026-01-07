<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                API Documentation
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('api.docs.json') }}" target="_blank"
                    class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300">
                    <i class="fas fa-download mr-2"></i>Download JSON
                </a>
                <a href="{{ route('api.docs.postman') }}" target="_blank"
                    class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white font-bold py-2 px-4 rounded-lg shadow-lg hover:shadow-xl transition-all duration-300">
                    <i class="fab fa-postman mr-2"></i>Postman Collection
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gradient-to-br from-gray-50 to-blue-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- API Overview -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-white/20 mb-8">
                <div class="p-8">
                    <div class="flex items-center mb-6">
                        <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mr-4">
                            <i class="fas fa-code text-white text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Link Sharing App API</h1>
                            <p class="text-gray-600">Version 1.0.0 - Comprehensive REST API for external integrations</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                        <div class="bg-gradient-to-r from-green-50 to-green-100 p-6 rounded-xl border border-green-200">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-shield-alt text-green-600 text-xl mr-3"></i>
                                <h3 class="font-semibold text-green-800">Secure</h3>
                            </div>
                            <p class="text-green-700 text-sm">Bearer token authentication with rate limiting</p>
                        </div>

                        <div class="bg-gradient-to-r from-blue-50 to-blue-100 p-6 rounded-xl border border-blue-200">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-bolt text-blue-600 text-xl mr-3"></i>
                                <h3 class="font-semibold text-blue-800">Fast</h3>
                            </div>
                            <p class="text-blue-700 text-sm">Optimized endpoints with caching support</p>
                        </div>

                        <div class="bg-gradient-to-r from-purple-50 to-purple-100 p-6 rounded-xl border border-purple-200">
                            <div class="flex items-center mb-3">
                                <i class="fas fa-code text-purple-600 text-xl mr-3"></i>
                                <h3 class="font-semibold text-purple-800">RESTful</h3>
                            </div>
                            <p class="text-purple-700 text-sm">Standard REST API with JSON responses</p>
                        </div>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-xl">
                        <h3 class="font-semibold text-gray-900 mb-4">Base URL</h3>
                        <div class="flex items-center space-x-4">
                            <code class="bg-gray-800 text-green-400 px-4 py-2 rounded-lg font-mono text-sm">
                                {{ url('/api') }}
                            </code>
                            <button onclick="copyToClipboard('{{ url('/api') }}')"
                                class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-2 rounded-lg text-sm transition-colors">
                                <i class="fas fa-copy mr-1"></i>Copy
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Authentication Section -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-white/20 mb-8">
                <div class="p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-key text-blue-600 mr-3"></i>
                        Authentication
                    </h2>

                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 mb-6">
                        <h3 class="font-semibold text-blue-900 mb-3">Bearer Token Authentication</h3>
                        <p class="text-blue-800 mb-4">All protected endpoints require a Bearer token in the Authorization header.</p>

                        <div class="bg-gray-800 text-green-400 p-4 rounded-lg font-mono text-sm">
                            Authorization: Bearer YOUR_API_TOKEN
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3">Getting Your API Token</h4>
                            <ol class="list-decimal list-inside space-y-2 text-gray-700">
                                <li>Go to your <a href="{{ route('profile.edit') }}" class="text-blue-600 hover:underline">Profile Settings</a></li>
                                <li>Navigate to the API section</li>
                                <li>Generate a new API token</li>
                                <li>Copy the token and use it in your requests</li>
                            </ol>
                        </div>

                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3">Rate Limiting</h4>
                            <ul class="space-y-2 text-gray-700">
                                <li><strong>Standard endpoints:</strong> 60 requests per minute</li>
                                <li><strong>Admin endpoints:</strong> 200 requests per minute</li>
                                <li><strong>Webhook endpoints:</strong> No limit (signature verification)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Endpoints Section -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-white/20 mb-8">
                <div class="p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-list text-green-600 mr-3"></i>
                        API Endpoints
                    </h2>

                    <!-- Links Endpoints -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-link text-blue-600 mr-2"></i>
                            Links Management
                        </h3>

                        <div class="space-y-4">
                            <!-- Get Links -->
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center space-x-3">
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold">GET</span>
                                        <code class="text-sm font-mono">/api/links</code>
                                    </div>
                                    <button onclick="testEndpoint('GET', '/api/links')"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                        Test
                                    </button>
                                </div>
                                <p class="text-gray-600 text-sm mb-2">Get all links for the authenticated user</p>
                                <div class="bg-gray-50 p-3 rounded text-xs">
                                    <strong>Query Parameters:</strong> status, type, search, per_page
                                </div>
                            </div>

                            <!-- Create Link -->
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center space-x-3">
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs font-semibold">POST</span>
                                        <code class="text-sm font-mono">/api/links</code>
                                    </div>
                                    <button onclick="testEndpoint('POST', '/api/links')"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                        Test
                                    </button>
                                </div>
                                <p class="text-gray-600 text-sm mb-2">Create a new link</p>
                                <div class="bg-gray-50 p-3 rounded text-xs">
                                    <strong>Body:</strong> title, original_url, description, type, status
                                </div>
                            </div>

                            <!-- Get Single Link -->
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center space-x-3">
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold">GET</span>
                                        <code class="text-sm font-mono">/api/links/{id}</code>
                                    </div>
                                    <button onclick="testEndpoint('GET', '/api/links/1')"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                        Test
                                    </button>
                                </div>
                                <p class="text-gray-600 text-sm mb-2">Get details of a specific link</p>
                            </div>
                        </div>
                    </div>

                    <!-- Analytics Endpoints -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-chart-bar text-purple-600 mr-2"></i>
                            Analytics
                        </h3>

                        <div class="space-y-4">
                            <!-- Get Analytics -->
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center space-x-3">
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold">GET</span>
                                        <code class="text-sm font-mono">/api/analytics</code>
                                    </div>
                                    <button onclick="testEndpoint('GET', '/api/analytics')"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                        Test
                                    </button>
                                </div>
                                <p class="text-gray-600 text-sm mb-2">Get comprehensive analytics data</p>
                                <div class="bg-gray-50 p-3 rounded text-xs">
                                    <strong>Query Parameters:</strong> period (default: 30 days)
                                </div>
                            </div>

                            <!-- Real-time Analytics -->
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center space-x-3">
                                        <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold">GET</span>
                                        <code class="text-sm font-mono">/api/analytics/realtime</code>
                                    </div>
                                    <button onclick="testEndpoint('GET', '/api/analytics/realtime')"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                        Test
                                    </button>
                                </div>
                                <p class="text-gray-600 text-sm mb-2">Get real-time analytics data</p>
                            </div>
                        </div>
                    </div>

                    <!-- Earnings Endpoints -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                            <i class="fas fa-dollar-sign text-green-600 mr-2"></i>
                            Earnings
                        </h3>

                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center space-x-3">
                                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-semibold">GET</span>
                                    <code class="text-sm font-mono">/api/earnings</code>
                                </div>
                                <button onclick="testEndpoint('GET', '/api/earnings')"
                                    class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                    Test
                                </button>
                            </div>
                            <p class="text-gray-600 text-sm mb-2">Get user earnings data</p>
                            <div class="bg-gray-50 p-3 rounded text-xs">
                                <strong>Query Parameters:</strong> period, per_page
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Response Examples -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-white/20 mb-8">
                <div class="p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-code text-orange-600 mr-3"></i>
                        Response Examples
                    </h2>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Success Response -->
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-3">Success Response</h3>
                            <pre class="bg-gray-800 text-green-400 p-4 rounded-lg text-sm overflow-x-auto"><code>{
  "status": "success",
  "data": {
    "id": 1,
    "title": "Example Link",
    "original_url": "https://example.com",
    "short_code": "ABC123",
    "clicks_count": 150,
    "created_at": "2025-08-27T10:00:00.000000Z"
  },
  "summary": {
    "total_links": 25,
    "active_links": 20,
    "total_clicks": 1500
  }
}</code></pre>
                        </div>

                        <!-- Error Response -->
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-3">Error Response</h3>
                            <pre class="bg-gray-800 text-red-400 p-4 rounded-lg text-sm overflow-x-auto"><code>{
  "status": "error",
  "message": "Validation failed",
  "errors": {
    "title": ["The title field is required."],
    "original_url": ["The original url must be a valid URL."]
  }
}</code></pre>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Testing Section -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl border border-white/20">
                <div class="p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <i class="fas fa-flask text-purple-600 mr-3"></i>
                        API Testing
                    </h2>

                    <div class="bg-gray-50 p-6 rounded-xl">
                        <h3 class="font-semibold text-gray-900 mb-4">Test API Endpoints</h3>
                        <p class="text-gray-600 mb-4">Use the interactive testing tool below to test API endpoints directly from your browser.</p>

                        <div id="api-test-results" class="mb-4"></div>

                        <div class="text-sm text-gray-500">
                            <p><strong>Note:</strong> Testing requires authentication. Make sure you're logged in and have a valid API token.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(() => {
                // Show success message
                const button = event.target;
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check mr-1"></i>Copied!';
                button.classList.add('bg-green-600');
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.classList.remove('bg-green-600');
                }, 2000);
            });
        }

        function testEndpoint(method, endpoint) {
            const resultsDiv = document.getElementById('api-test-results');
            resultsDiv.innerHTML = '<div class="bg-blue-100 text-blue-800 p-3 rounded">Testing endpoint...</div>';

            fetch(endpoint, {
                method: method,
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                resultsDiv.innerHTML = `
                    <div class="bg-green-100 text-green-800 p-3 rounded mb-2">
                        <strong>Success!</strong> Endpoint responded successfully.
                    </div>
                    <pre class="bg-gray-800 text-green-400 p-4 rounded text-sm overflow-x-auto">${JSON.stringify(data, null, 2)}</pre>
                `;
            })
            .catch(error => {
                resultsDiv.innerHTML = `
                    <div class="bg-red-100 text-red-800 p-3 rounded mb-2">
                        <strong>Error!</strong> ${error.message}
                    </div>
                `;
            });
        }
    </script>
</x-app-layout>
