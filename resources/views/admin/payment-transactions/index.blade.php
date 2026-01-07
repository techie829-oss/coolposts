<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Payment Transactions') }}
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

            <!-- Search and Filters -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.payment-transactions.index') }}" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                                <input type="text" id="search" name="search" value="{{ request('search') }}" 
                                       placeholder="Transaction ID, User, Email..." 
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">All Statuses</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
                                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
                                </select>
                            </div>
                            <div>
                                <label for="gateway" class="block text-sm font-medium text-gray-700">Gateway</label>
                                <select id="gateway" name="gateway" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                    <option value="">All Gateways</option>
                                    @foreach($gateways as $gateway)
                                        <option value="{{ $gateway->id }}" {{ request('gateway') == $gateway->id ? 'selected' : '' }}>
                                            {{ $gateway->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-end">
                                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-search mr-2"></i>Filter
                                </button>
                                <a href="{{ route('admin.payment-transactions.index') }}" class="ml-2 bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-times mr-2"></i>Clear
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Transactions Summary -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                    <i class="fas fa-list text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-2xl font-bold text-gray-900">{{ $transactions->total() }}</div>
                                <div class="text-sm text-gray-600">Total Transactions</div>
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
                                <div class="text-2xl font-bold text-gray-900">{{ $transactions->where('status', 'completed')->count() }}</div>
                                <div class="text-sm text-gray-600">Completed</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                    <i class="fas fa-clock text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-2xl font-bold text-gray-900">{{ $transactions->where('status', 'pending')->count() }}</div>
                                <div class="text-sm text-gray-600">Pending</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-red-500 rounded-md flex items-center justify-center">
                                    <i class="fas fa-times text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-2xl font-bold text-gray-900">{{ $transactions->where('status', 'failed')->count() }}</div>
                                <div class="text-sm text-gray-600">Failed</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Transactions List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-history mr-2 text-gray-600"></i>Transaction History
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    @if($transactions->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gateway</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($transactions as $transaction)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $transaction->transaction_id }}</div>
                                                @if($transaction->gateway_transaction_id)
                                                    <div class="text-sm text-gray-500">{{ $transaction->gateway_transaction_id }}</div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-8 w-8">
                                                    <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center">
                                                        <i class="fas fa-user text-gray-600 text-sm"></i>
                                                    </div>
                                                </div>
                                                <div class="ml-3">
                                                    <div class="text-sm font-medium text-gray-900">{{ $transaction->user->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $transaction->user->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-6 w-6">
                                                    <div class="h-6 w-6 rounded bg-gray-100 flex items-center justify-center">
                                                        <i class="fas fa-{{ $transaction->gateway->slug === 'stripe' ? 'credit-card' : ($transaction->gateway->slug === 'paypal' ? 'paypal' : 'wallet') }} text-gray-600 text-xs"></i>
                                                    </div>
                                                </div>
                                                <div class="ml-2">
                                                    <div class="text-sm text-gray-900">{{ $transaction->gateway->name }}</div>
                                                    <div class="text-xs text-gray-500">{{ $transaction->gateway->getEnvironmentDisplayName() }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                <div class="text-sm font-medium text-gray-900">{{ $transaction->getFormattedAmount() }}</div>
                                                @if($transaction->gateway_fee > 0)
                                                    <div class="text-xs text-gray-500">Fee: {{ $transaction->getFormattedGatewayFee() }}</div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $transaction->getStatusBadgeClass() }}">
                                                {{ $transaction->getStatusDisplayName() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div>
                                                <div class="text-sm text-gray-900">{{ $transaction->getTypeDisplayName() }}</div>
                                                @if($transaction->payment_method)
                                                    <div class="text-xs text-gray-500">{{ $transaction->getPaymentMethodDisplayName() }}</div>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div>
                                                <div>{{ $transaction->created_at->format('M d, Y') }}</div>
                                                <div class="text-xs text-gray-500">{{ $transaction->created_at->format('H:i') }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <button onclick="viewTransactionDetails('{{ $transaction->id }}')" 
                                                        class="text-blue-600 hover:text-blue-900">
                                                    <i class="fas fa-eye mr-1"></i>View
                                                </button>
                                                @if($transaction->status === 'pending')
                                                    <button onclick="updateTransactionStatus('{{ $transaction->id }}', 'completed')" 
                                                            class="text-green-600 hover:text-green-900">
                                                        <i class="fas fa-check mr-1"></i>Approve
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="p-6 text-center">
                            <p class="text-gray-500">No payment transactions found.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Pagination -->
            @if($transactions->hasPages())
                <div class="mt-6">
                    {{ $transactions->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Transaction Details Modal -->
    <div id="transactionModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Transaction Details</h3>
                    <button onclick="closeTransactionModal()" class="text-gray-400 hover:text-gray-600">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="transactionDetails" class="space-y-4">
                    <!-- Transaction details will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <script>
        function viewTransactionDetails(transactionId) {
            // Load transaction details via AJAX
            fetch(`/admin/payment-transactions/${transactionId}/details`)
                .then(response => response.json())
                .then(data => {
                    document.getElementById('transactionDetails').innerHTML = data.html;
                    document.getElementById('transactionModal').classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading transaction details');
                });
        }

        function closeTransactionModal() {
            document.getElementById('transactionModal').classList.add('hidden');
        }

        function updateTransactionStatus(transactionId, status) {
            if (confirm(`Are you sure you want to mark this transaction as ${status}?`)) {
                // Implement status update logic
                alert('Status update feature coming soon!');
            }
        }

        // Close modal when clicking outside
        document.getElementById('transactionModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeTransactionModal();
            }
        });
    </script>
</x-app-layout>
