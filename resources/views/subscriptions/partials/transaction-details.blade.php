<div class="space-y-4">
    <!-- Transaction Header -->
    <div class="bg-gray-50 p-4 rounded-md">
        <div class="flex items-center justify-between">
            <div>
                <h4 class="text-lg font-medium text-gray-900">{{ $transaction->transaction_id }}</h4>
                <p class="text-sm text-gray-600">{{ ucfirst($transaction->type) }} Transaction</p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $transaction->getStatusBadgeClass() }}">
                {{ $transaction->getStatusDisplayName() }}
            </span>
        </div>
    </div>

    <!-- Transaction Details -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <h5 class="text-sm font-medium text-gray-700 mb-2">Payment Information</h5>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Amount:</span>
                    <span class="font-medium">{{ $transaction->getFormattedAmount() }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Currency:</span>
                    <span class="font-medium">{{ $transaction->currency }}</span>
                </div>
                @if($transaction->gateway_fee > 0)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Gateway Fee:</span>
                        <span class="font-medium">{{ $transaction->getFormattedGatewayFee() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Net Amount:</span>
                        <span class="font-medium">{{ $transaction->getFormattedNetAmount() }}</span>
                    </div>
                @endif
            </div>
        </div>

        <div>
            <h5 class="text-sm font-medium text-gray-700 mb-2">Gateway Information</h5>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-600">Gateway:</span>
                    <span class="font-medium">{{ $transaction->gateway->name }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-600">Environment:</span>
                    <span class="font-medium">{{ ucfirst($transaction->gateway->environment) }}</span>
                </div>
                @if($transaction->gateway_transaction_id)
                    <div class="flex justify-between">
                        <span class="text-gray-600">Gateway ID:</span>
                        <span class="font-medium text-xs">{{ $transaction->gateway_transaction_id }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Payment Method -->
    @if($transaction->payment_method)
        <div>
            <h5 class="text-sm font-medium text-gray-700 mb-2">Payment Method</h5>
            <div class="text-sm">
                <span class="text-gray-600">Method:</span>
                <span class="font-medium ml-2">{{ ucfirst($transaction->payment_method) }}</span>
                @if($transaction->payment_method_details)
                    <span class="text-gray-500 ml-2">({{ $transaction->payment_method_details }})</span>
                @endif
            </div>
        </div>
    @endif

    <!-- Timestamps -->
    <div>
        <h5 class="text-sm font-medium text-gray-700 mb-2">Timeline</h5>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-600">Created:</span>
                <span class="font-medium">{{ $transaction->created_at->format('M d, Y H:i:s') }}</span>
            </div>
            @if($transaction->processed_at)
                <div class="flex justify-between">
                    <span class="text-gray-600">Processed:</span>
                    <span class="font-medium">{{ $transaction->processed_at->format('M d, Y H:i:s') }}</span>
                </div>
            @endif
            @if($transaction->failed_at)
                <div class="flex justify-between">
                    <span class="text-gray-600">Failed:</span>
                    <span class="font-medium">{{ $transaction->failed_at->format('M d, Y H:i:s') }}</span>
                </div>
            @endif
        </div>
    </div>

    <!-- Failure Reason -->
    @if($transaction->failure_reason)
        <div>
            <h5 class="text-sm font-medium text-gray-700 mb-2">Failure Reason</h5>
            <div class="bg-red-50 border border-red-200 rounded-md p-3">
                <p class="text-sm text-red-800">{{ $transaction->failure_reason }}</p>
            </div>
        </div>
    @endif

    <!-- Description -->
    @if($transaction->description)
        <div>
            <h5 class="text-sm font-medium text-gray-700 mb-2">Description</h5>
            <p class="text-sm text-gray-600">{{ $transaction->description }}</p>
        </div>
    @endif

    <!-- Metadata -->
    @if($transaction->metadata && count($transaction->metadata) > 0)
        <div>
            <h5 class="text-sm font-medium text-gray-700 mb-2">Additional Information</h5>
            <div class="bg-gray-50 border border-gray-200 rounded-md p-3">
                @foreach($transaction->metadata as $key => $value)
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                        <span class="font-medium">{{ is_array($value) ? json_encode($value) : $value }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Actions -->
    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
        @if($transaction->isPending())
            <button onclick="retryPayment({{ $transaction->id }})" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-redo mr-2"></i>Retry Payment
            </button>
        @endif
        
        @if($transaction->isCompleted())
            <button onclick="downloadReceipt({{ $transaction->id }})" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-download mr-2"></i>Download Receipt
            </button>
        @endif
        
        <button onclick="closeTransactionModal()" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm font-medium">
            Close
        </button>
    </div>
</div>

<script>
    function retryPayment(transactionId) {
        if (confirm('Are you sure you want to retry this payment?')) {
            fetch(`/subscriptions/transactions/${transactionId}/retry`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Failed to retry payment: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error retrying payment:', error);
                alert('An error occurred while retrying the payment.');
            });
        }
    }

    function downloadReceipt(transactionId) {
        window.open(`/subscriptions/transactions/${transactionId}/receipt`, '_blank');
    }
</script>
