<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Complete Payment') }}
            </h2>
            <a href="{{ route('subscriptions.plans') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Plans
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <!-- Payment Summary -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Summary</h3>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Amount:</span>
                            <span class="font-medium">₹{{ number_format($result['amount'], 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Currency:</span>
                            <span class="font-medium">{{ $result['currency'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Transaction ID:</span>
                            <span class="font-medium text-sm">{{ $result['transaction_id'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Order ID:</span>
                            <span class="font-medium text-sm">{{ $result['order_id'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Paytm Payment Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Paytm Payment</h3>

                    <form id="paytm-form" action="{{ $result['paytm_url'] }}" method="POST" class="space-y-6">
                        @foreach($result['payment_params'] as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <input type="hidden" name="CHECKSUMHASH" value="{{ $result['checksum'] }}">

                        <div class="text-center">
                            <div class="mb-4">
                                <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center mx-auto mb-3">
                                    <i class="fas fa-wallet text-blue-600 text-2xl"></i>
                                </div>
                                <h4 class="text-lg font-medium text-gray-900">Paytm Payment Gateway</h4>
                                <p class="text-gray-600">Secure payment through Paytm</p>
                            </div>

                            <div class="bg-gray-50 p-4 rounded-md mb-6">
                                <div class="text-2xl font-bold text-gray-900 mb-2">
                                    ₹{{ number_format($result['amount'], 2) }}
                                </div>
                                <p class="text-sm text-gray-600">Amount to be paid</p>
                            </div>

                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-md transition duration-200">
                                <i class="fas fa-arrow-right mr-2"></i>Proceed to Paytm
                            </button>
                        </div>
                    </form>

                    <!-- Payment Methods Info -->
                    <div class="mt-6 p-4 bg-blue-50 rounded-md">
                        <h4 class="text-sm font-medium text-blue-900 mb-2">Available Payment Methods:</h4>
                        <div class="grid grid-cols-2 gap-2 text-sm text-blue-800">
                            <div class="flex items-center">
                                <i class="fas fa-mobile-alt mr-2"></i>UPI
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-credit-card mr-2"></i>Cards
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-university mr-2"></i>Net Banking
                            </div>
                            <div class="flex items-center">
                                <i class="fas fa-wallet mr-2"></i>Paytm Wallet
                            </div>
                        </div>
                    </div>

                    <!-- Security Notice -->
                    <div class="mt-6 p-4 bg-gray-50 rounded-md">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-shield-alt text-green-600"></i>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-gray-900">Secure Payment</h4>
                                <p class="text-sm text-gray-600">
                                    Your payment information is encrypted and secure. We use Paytm for secure payment processing.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Auto-submit form after a short delay to show payment summary
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                document.getElementById('paytm-form').submit();
            }, 3000); // 3 second delay
        });
    </script>
</x-app-layout>
