<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Payment Gateway Management') }}
            </h2>
            <a href="{{ route('admin.dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Payment Gateways Overview -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                    <i class="fas fa-credit-card text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-2xl font-bold text-gray-900">{{ $gateways->count() }}</div>
                                <div class="text-sm text-gray-600">Total Gateways</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                    <i class="fas fa-check text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-2xl font-bold text-gray-900">{{ $gateways->where('is_active', true)->count() }}</div>
                                <div class="text-sm text-gray-600">Active Gateways</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                    <i class="fas fa-flask text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-2xl font-bold text-gray-900">{{ $gateways->where('is_test_mode', true)->count() }}</div>
                                <div class="text-sm text-gray-600">Test Mode</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                    <i class="fas fa-globe text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-2xl font-bold text-gray-900">{{ $gateways->where('is_test_mode', false)->count() }}</div>
                                <div class="text-sm text-gray-600">Live Mode</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Gateways List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-list mr-2 text-gray-600"></i>Payment Gateways
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    @if($gateways->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gateway</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Environment</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Currencies</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fees</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($gateways as $gateway)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-lg bg-gray-100 flex items-center justify-center">
                                                        <i class="fas fa-{{ $gateway->slug === 'stripe' ? 'credit-card' : ($gateway->slug === 'paypal' ? 'paypal' : 'wallet') }} text-gray-600"></i>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $gateway->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $gateway->description }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $gateway->getStatusBadgeClass() }}">
                                                {{ $gateway->getStatusDisplayName() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $gateway->getEnvironmentBadgeClass() }}">
                                                {{ $gateway->getEnvironmentDisplayName() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex flex-wrap gap-1">
                                                @foreach($gateway->supported_currencies as $currency)
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                        {{ $currency }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div>
                                                <div>{{ $gateway->transaction_fee_percentage }}% + {{ $gateway->getFormattedFixedFee() }}</div>
                                                <div class="text-xs text-gray-500">per transaction</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.payment-gateways.edit', $gateway) }}"
                                                   class="text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-edit mr-1"></i>Configure
                                                </a>
                                                <button onclick="toggleGatewayStatus('{{ $gateway->id }}', '{{ $gateway->is_active ? 'disable' : 'enable' }}')"
                                                        class="text-{{ $gateway->is_active ? 'red' : 'green' }}-600 hover:text-{{ $gateway->is_active ? 'red' : 'green' }}-900">
                                                    <i class="fas fa-{{ $gateway->is_active ? 'times' : 'check' }} mr-1"></i>
                                                    {{ $gateway->is_active ? 'Disable' : 'Enable' }}
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="p-6 text-center">
                            <p class="text-gray-500">No payment gateways found.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h4 class="text-lg font-medium text-gray-900 mb-4">Quick Actions</h4>
                    <div class="flex flex-wrap gap-4">
                        <button onclick="testAllGateways()"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-flask mr-2"></i>Test All Gateways
                        </button>
                        <button onclick="syncGatewayStatuses()"
                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-sync mr-2"></i>Sync Statuses
                        </button>
                        <button onclick="viewTransactionHistory()"
                                class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-history mr-2"></i>View Transactions
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Toggle Form -->
    <form id="toggleGatewayForm" method="POST" style="display: none;">
        @csrf
        @method('PATCH')
        <input type="hidden" id="gatewayId" name="gateway_id">
        <input type="hidden" id="action" name="action">
    </form>

    <script>
        function toggleGatewayStatus(gatewayId, action) {
            if (confirm(`Are you sure you want to ${action} this payment gateway?`)) {
                document.getElementById('gatewayId').value = gatewayId;
                document.getElementById('action').value = action;
                document.getElementById('toggleGatewayForm').submit();
            }
        }

        function testAllGateways() {
            if (confirm('Test all payment gateways? This will verify connectivity and configuration.')) {
                // Implement gateway testing logic
                alert('Gateway testing feature coming soon!');
            }
        }

        function syncGatewayStatuses() {
            if (confirm('Sync payment gateway statuses with external services?')) {
                // Implement status sync logic
                alert('Status sync feature coming soon!');
            }
        }

        function viewTransactionHistory() {
            // Redirect to transaction history page
            window.location.href = '{{ route("admin.payment-transactions.index") }}';
        }
    </script>
</x-app-layout>
