<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Configure') }} {{ $gateway->name }}
            </h2>
            <a href="{{ route('admin.payment-gateways.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Gateways
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Gateway Status Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ $gateway->name }}</h3>
                            <p class="text-sm text-gray-600">{{ $gateway->description }}</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <div class="text-right">
                                <div class="text-sm text-gray-600">Status</div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $gateway->getStatusBadgeClass() }}">
                                    {{ $gateway->getStatusDisplayName() }}
                                </span>
                            </div>
                            <div class="text-right">
                                <div class="text-sm text-gray-600">Environment</div>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $gateway->getEnvironmentBadgeClass() }}">
                                    {{ $gateway->getEnvironmentDisplayName() }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Configuration Form -->
            <form method="POST" action="{{ route('admin.payment-gateways.update', $gateway) }}" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Basic Settings -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-cog mr-2 text-gray-600"></i>Basic Settings
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="is_active" class="block text-sm font-medium text-gray-700">Gateway Status</label>
                                <select id="is_active" name="is_active" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="1" {{ $gateway->is_active ? 'selected' : '' }}>Active</option>
                                    <option value="0" {{ !$gateway->is_active ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                            <div>
                                <label for="is_test_mode" class="block text-sm font-medium text-gray-700">Environment</label>
                                <select id="is_test_mode" name="is_test_mode" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="1" {{ $gateway->is_test_mode ? 'selected' : '' }}>Test Mode</option>
                                    <option value="0" {{ !$gateway->is_test_mode ? 'selected' : '' }}>Live Mode</option>
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="transaction_fee_percentage" class="block text-sm font-medium text-gray-700">Transaction Fee (%)</label>
                                <input type="number" step="0.01" min="0" max="100" id="transaction_fee_percentage" name="transaction_fee_percentage"
                                       value="{{ $gateway->transaction_fee_percentage }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <p class="mt-1 text-sm text-gray-500">Percentage fee per transaction</p>
                            </div>
                            <div>
                                <label for="transaction_fee_fixed" class="block text-sm font-medium text-gray-700">Fixed Fee ({{ $gateway->supported_currencies[0] ?? 'USD' }})</label>
                                <input type="number" step="0.01" min="0" id="transaction_fee_fixed" name="transaction_fee_fixed"
                                       value="{{ $gateway->transaction_fee_fixed }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <p class="mt-1 text-sm text-gray-500">Fixed fee per transaction</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gateway-Specific Configuration -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-key mr-2 text-gray-600"></i>{{ $gateway->name }} Configuration
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        @if($gateway->slug === 'stripe')
                            <!-- Stripe Configuration -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="publishable_key" class="block text-sm font-medium text-gray-700">Publishable Key</label>
                                    <input type="text" id="publishable_key" name="config[publishable_key]"
                                           value="{{ $gateway->getConfig('publishable_key') }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <p class="mt-1 text-sm text-gray-500">pk_test_... or pk_live_...</p>
                                </div>
                                <div>
                                    <label for="secret_key" class="block text-sm font-medium text-gray-700">Secret Key</label>
                                    <input type="password" id="secret_key" name="config[secret_key]"
                                           value="{{ $gateway->getConfig('secret_key') }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <p class="mt-1 text-sm text-gray-500">sk_test_... or sk_live_...</p>
                                </div>
                            </div>
                            <div>
                                <label for="webhook_secret" class="block text-sm font-medium text-gray-700">Webhook Secret</label>
                                <input type="password" id="webhook_secret" name="config[webhook_secret]"
                                       value="{{ $gateway->getConfig('webhook_secret') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <p class="mt-1 text-sm text-gray-500">whsec_... from Stripe webhook settings</p>
                            </div>

                        @elseif($gateway->slug === 'paypal')
                            <!-- PayPal Configuration -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="client_id" class="block text-sm font-medium text-gray-700">Client ID</label>
                                    <input type="text" id="client_id" name="config[client_id]"
                                           value="{{ $gateway->getConfig('client_id') }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <p class="mt-1 text-sm text-gray-500">PayPal App Client ID</p>
                                </div>
                                <div>
                                    <label for="client_secret" class="block text-sm font-medium text-gray-700">Client Secret</label>
                                    <input type="password" id="client_secret" name="config[client_secret]"
                                           value="{{ $gateway->getConfig('client_secret') }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <p class="mt-1 text-sm text-gray-500">PayPal App Client Secret</p>
                                </div>
                            </div>
                            <div>
                                <label for="webhook_id" class="block text-sm font-medium text-gray-700">Webhook ID</label>
                                <input type="text" id="webhook_id" name="config[webhook_id]"
                                       value="{{ $gateway->getConfig('webhook_id') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <p class="mt-1 text-sm text-gray-500">Webhook ID from PayPal Developer Dashboard</p>
                            </div>

                        @elseif($gateway->slug === 'paytm')
                            <!-- Paytm Configuration -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="merchant_id" class="block text-sm font-medium text-gray-700">Merchant ID</label>
                                    <input type="text" id="merchant_id" name="config[merchant_id]"
                                           value="{{ $gateway->getConfig('merchant_id') }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <p class="mt-1 text-sm text-gray-500">Paytm Merchant ID</p>
                                </div>
                                <div>
                                    <label for="merchant_key" class="block text-sm font-medium text-gray-700">Merchant Key</label>
                                    <input type="password" id="merchant_key" name="config[merchant_key]"
                                           value="{{ $gateway->getConfig('merchant_key') }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <p class="mt-1 text-sm text-gray-500">Paytm Merchant Key</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="website" class="block text-sm font-medium text-gray-700">Website</label>
                                    <select id="website" name="config[website]" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        <option value="WEBSTAGING" {{ $gateway->getConfig('website') === 'WEBSTAGING' ? 'selected' : '' }}>Test Mode (WEBSTAGING)</option>
                                        <option value="DEFAULT" {{ $gateway->getConfig('website') === 'DEFAULT' ? 'selected' : '' }}>Live Mode (DEFAULT)</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="industry_type" class="block text-sm font-medium text-gray-700">Industry Type</label>
                                    <select id="industry_type" name="config[industry_type]" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                        <option value="Retail" {{ $gateway->getConfig('industry_type') === 'Retail' ? 'selected' : '' }}>Retail</option>
                                        <option value="Ecommerce" {{ $gateway->getConfig('industry_type') === 'Ecommerce' ? 'selected' : '' }}>Ecommerce</option>
                                        <option value="Services" {{ $gateway->getConfig('industry_type') === 'Services' ? 'selected' : '' }}>Services</option>
                                    </select>
                                </div>
                            </div>

                        @elseif($gateway->slug === 'razorpay')
                            <!-- Razorpay Configuration -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label for="key_id" class="block text-sm font-medium text-gray-700">Key ID</label>
                                    <input type="text" id="key_id" name="config[key_id]"
                                           value="{{ $gateway->getConfig('key_id') }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <p class="mt-1 text-sm text-gray-500">rzp_test_... or rzp_live_...</p>
                                </div>
                                <div>
                                    <label for="key_secret" class="block text-sm font-medium text-gray-700">Key Secret</label>
                                    <input type="password" id="key_secret" name="config[key_secret]"
                                           value="{{ $gateway->getConfig('key_secret') }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <p class="mt-1 text-sm text-gray-500">Razorpay Key Secret</p>
                                </div>
                            </div>
                            <div>
                                <label for="webhook_secret" class="block text-sm font-medium text-gray-700">Webhook Secret</label>
                                <input type="password" id="webhook_secret" name="config[webhook_secret]"
                                       value="{{ $gateway->getConfig('webhook_secret') }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <p class="mt-1 text-sm text-gray-500">Webhook secret from Razorpay Dashboard</p>
                            </div>
                        @endif

                        <!-- Webhook URL Display -->
                        <div class="bg-gray-50 p-4 rounded-md">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Webhook URL</label>
                            <div class="flex">
                                <input type="text" value="{{ url($gateway->webhook_url) }}" readonly
                                       class="flex-1 bg-gray-100 border-gray-300 rounded-l-md shadow-sm text-gray-600">
                                <button type="button" onclick="copyToClipboard('{{ url($gateway->webhook_url) }}')"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-r-md text-sm font-medium">
                                    <i class="fas fa-copy mr-1"></i>Copy
                                </button>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Add this URL to your {{ $gateway->name }} webhook settings</p>
                        </div>
                    </div>
                </div>

                <!-- Supported Currencies & Countries -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-globe mr-2 text-gray-600"></i>Supported Currencies & Countries
                        </h3>
                    </div>
                    <div class="p-6 space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Supported Currencies</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                @foreach(['USD', 'EUR', 'GBP', 'CAD', 'AUD', 'INR'] as $currency)
                                    <label class="flex items-center">
                                        <input type="checkbox" name="supported_currencies[]" value="{{ $currency }}"
                                               {{ in_array($currency, $gateway->supported_currencies ?? []) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700">{{ $currency }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Supported Countries</label>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2">
                                @foreach(['US', 'CA', 'GB', 'AU', 'DE', 'FR', 'IN'] as $country)
                                    <label class="flex items-center">
                                        <input type="checkbox" name="supported_countries[]" value="{{ $country }}"
                                               {{ in_array($country, $gateway->supported_countries ?? []) ? 'checked' : '' }}
                                               class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700">{{ $country }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Leave empty to support all countries</p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex justify-end space-x-4">
                    <button type="button" onclick="testGateway()"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-flask mr-2"></i>Test Gateway
                    </button>
                    <button type="submit"
                            class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-save mr-2"></i>Save Configuration
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Show success message
                const button = event.target;
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check mr-1"></i>Copied!';
                button.classList.remove('bg-blue-600', 'hover:bg-blue-700');
                button.classList.add('bg-green-600', 'hover:bg-green-700');

                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.classList.remove('bg-green-600', 'hover:bg-green-700');
                    button.classList.add('bg-blue-600', 'hover:bg-blue-700');
                }, 2000);
            });
        }

        function testGateway() {
            if (confirm('Test this payment gateway? This will verify connectivity and configuration.')) {
                // Implement gateway testing logic
                alert('Gateway testing feature coming soon!');
            }
        }
    </script>
</x-app-layout>
