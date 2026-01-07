<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My Subscriptions') }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('subscriptions.plans') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-plus mr-2"></i>Upgrade Plan
                </a>
                <a href="{{ route('dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('info'))
                <div class="mb-6 bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded">
                    {{ session('info') }}
                </div>
            @endif

            <!-- Current Subscription Status -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Active Subscription Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-crown text-green-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4 flex-1">
                                <h3 class="text-lg font-medium text-gray-900">
                                    @if($activeSubscription)
                                        {{ $activeSubscription->plan->name }}
                                    @else
                                        No Active Plan
                                    @endif
                                </h3>
                                <p class="text-sm text-gray-600">
                                    @if($activeSubscription)
                                        {{ ucfirst($activeSubscription->plan->billing_cycle) }} Plan
                                    @else
                                        Free Plan
                                    @endif
                                </p>
                            </div>
                            <div class="ml-auto">
                                @if($activeSubscription)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                        <i class="fas fa-check mr-1"></i>Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800">
                                        <i class="fas fa-times mr-1"></i>Inactive
                                    </span>
                                @endif
                            </div>
                        </div>

                        @if($activeSubscription)
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="text-gray-600">Started:</span>
                                        <div class="font-medium">{{ $activeSubscription->starts_at->format('M d, Y') }}</div>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Expires:</span>
                                        <div class="font-medium">{{ $activeSubscription->ends_at->format('M d, Y') }}</div>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-600">Days Remaining:</span>
                                        <span class="font-medium text-blue-600">{{ $activeSubscription->daysRemaining() }} days</span>
                                    </div>
                                    <div class="mt-2 w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ $activeSubscription->getProgressPercentage() }}%"></div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Premium Benefits Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-star text-blue-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Premium Benefits</h3>
                                <p class="text-sm text-gray-600">Your current benefits</p>
                            </div>
                        </div>

                        <div class="space-y-2">
                            @if($activeSubscription)
                                @foreach($activeSubscription->plan->features as $feature)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <i class="fas fa-check text-green-500 mr-2"></i>
                                        {{ $feature }}
                                    </div>
                                @endforeach
                            @else
                                <div class="text-sm text-gray-500">
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-times text-red-500 mr-2"></i>
                                        No ads on links
                                    </div>
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-times text-red-500 mr-2"></i>
                                        Higher earning rates
                                    </div>
                                    <div class="flex items-center mb-2">
                                        <i class="fas fa-times text-red-500 mr-2"></i>
                                        Priority support
                                    </div>
                                    <div class="text-center mt-3">
                                        <a href="{{ route('subscriptions.plans') }}" class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                                            Upgrade to unlock benefits →
                                        </a>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-cog text-purple-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-medium text-gray-900">Quick Actions</h3>
                                <p class="text-sm text-gray-600">Manage your subscription</p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            @if($activeSubscription)
                                <button onclick="showCancelModal()" class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-times mr-2"></i>Cancel Subscription
                                </button>
                                <button onclick="showRenewalModal()" class="w-full bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-sync mr-2"></i>Renew Early
                                </button>
                            @else
                                <a href="{{ route('subscriptions.plans') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium text-center">
                                    <i class="fas fa-plus mr-2"></i>Subscribe Now
                                </a>
                            @endif
                            <a href="#payment-history" class="block w-full bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium text-center">
                                <i class="fas fa-history mr-2"></i>Payment History
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Subscription History -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Subscription History</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Plan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($subscriptions as $subscription)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                                <i class="fas fa-crown text-blue-600 text-sm"></i>
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $subscription->plan->name }}</div>
                                                <div class="text-sm text-gray-500">{{ ucfirst($subscription->plan->billing_cycle) }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $subscription->getStatusBadgeClass() }}">
                                            {{ $subscription->getStatusDisplayName() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $subscription->getFormattedAmount() }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div>{{ $subscription->starts_at->format('M d, Y') }}</div>
                                        <div>to {{ $subscription->ends_at->format('M d, Y') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        @if($subscription->isActive())
                                            <button onclick="showCancelModal({{ $subscription->id }})" class="text-red-600 hover:text-red-900">
                                                Cancel
                                            </button>
                                        @elseif($subscription->isExpired())
                                            <a href="{{ route('subscriptions.plans') }}" class="text-blue-600 hover:text-blue-900">
                                                Renew
                                            </a>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                        No subscription history found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Payment History -->
            <div id="payment-history" class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">Payment History</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transaction</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Gateway</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($transactions as $transaction)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $transaction->transaction_id }}</div>
                                        <div class="text-sm text-gray-500">{{ $transaction->type }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                                                        <div class="flex items-center">
                                    <div class="flex-shrink-0 w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                                        <i class="fas fa-{{ getGatewayIconHelper($transaction->gateway->slug) }} text-gray-600 text-sm"></i>
                                    </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">{{ $transaction->gateway->name }}</div>
                                                <div class="text-sm text-gray-500">{{ $transaction->gateway->environment }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div>{{ $transaction->getFormattedAmount() }}</div>
                                        @if($transaction->gateway_fee > 0)
                                            <div class="text-xs text-gray-500">Fee: {{ $transaction->getFormattedGatewayFee() }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $transaction->getStatusBadgeClass() }}">
                                            {{ $transaction->getStatusDisplayName() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $transaction->created_at->format('M d, Y H:i') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="showTransactionDetails({{ $transaction->id }})" class="text-blue-600 hover:text-blue-900">
                                            View Details
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        No payment transactions found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Subscription Modal -->
    <div id="cancelModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h3 class="text-lg font-medium text-gray-900">Cancel Subscription</h3>
                        <p class="text-sm text-gray-600">Are you sure you want to cancel your subscription?</p>
                    </div>
                </div>

                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-4">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-info-circle text-yellow-400"></i>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-yellow-800">What happens when you cancel?</h4>
                            <div class="mt-2 text-sm text-yellow-700">
                                <ul class="list-disc pl-5 space-y-1">
                                    <li>Your subscription will remain active until the end of the current billing period</li>
                                    <li>You'll lose access to premium features after expiration</li>
                                    <li>You can reactivate anytime by subscribing again</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <form id="cancelForm" method="POST" action="{{ route('subscriptions.cancel') }}" class="space-y-4">
                    @csrf
                    <input type="hidden" id="cancelSubscriptionId" name="subscription_id">

                    <div>
                        <label for="cancellation_reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Reason for cancellation (optional)
                        </label>
                        <select id="cancellation_reason" name="cancellation_reason" class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="">Select a reason...</option>
                            <option value="too_expensive">Too expensive</option>
                            <option value="not_using">Not using the service</option>
                            <option value="switching">Switching to another service</option>
                            <option value="temporary">Temporary cancellation</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="flex justify-end space-x-3 pt-4">
                        <button type="button" onclick="closeCancelModal()"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm font-medium">
                            Keep Subscription
                        </button>
                        <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                            <i class="fas fa-times mr-2"></i>Cancel Subscription
                        </button>
                    </div>
                </form>
            </div>
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
        function showCancelModal(subscriptionId = null) {
            if (subscriptionId) {
                document.getElementById('cancelSubscriptionId').value = subscriptionId;
            }
            document.getElementById('cancelModal').classList.remove('hidden');
        }

        function closeCancelModal() {
            document.getElementById('cancelModal').classList.add('hidden');
        }

        function showTransactionDetails(transactionId) {
            // Load transaction details via AJAX
            fetch(`/subscriptions/transactions/${transactionId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById('transactionDetails').innerHTML = data.html;
                        document.getElementById('transactionModal').classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error loading transaction details:', error);
                });
        }

        function closeTransactionModal() {
            document.getElementById('transactionModal').classList.add('hidden');
        }

        // Close modals when clicking outside
        document.getElementById('cancelModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeCancelModal();
            }
        });

        document.getElementById('transactionModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeTransactionModal();
            }
        });
    </script>

    @php
        function getGatewayIconHelper($slug) {
            $icons = [
                'stripe' => 'credit-card',
                'paypal' => 'paypal',
                'paytm' => 'wallet',
                'razorpay' => 'wallet'
            ];
            return $icons[$slug] ?? 'credit-card';
        }
    @endphp
</x-app-layout>
