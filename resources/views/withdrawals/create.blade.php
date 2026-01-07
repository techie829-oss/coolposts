<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Request Withdrawal') }}
            </h2>
            <a href="{{ route('withdrawals.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Withdrawals
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Balance Info -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                <div class="flex items-center">
                    <i class="fas fa-wallet text-blue-600 text-2xl mr-4"></i>
                    <div>
                        <h3 class="text-lg font-medium text-blue-900">Available Balance</h3>
                        <p class="text-2xl font-bold text-blue-800">{{ $userCurrency === 'INR' ? '₹' : '$' }}{{ number_format($availableBalance, 2) }}</p>
                        <p class="text-sm text-blue-600 mt-1">Minimum withdrawal: {{ $userCurrency === 'INR' ? '₹' : '$' }}{{ number_format($minWithdrawal, 2) }}</p>
                    </div>
                </div>
            </div>

            <!-- Withdrawal Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('withdrawals.store') }}" method="POST" id="withdrawalForm">
                        @csrf

                        <!-- Amount -->
                        <div class="mb-6">
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">Withdrawal Amount</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-500">
                                    {{ $userCurrency === 'INR' ? '₹' : '$' }}
                                </span>
                                <input type="number"
                                       name="amount"
                                       id="amount"
                                       step="0.01"
                                       min="{{ $minWithdrawal }}"
                                       max="{{ $availableBalance }}"
                                       value="{{ old('amount') }}"
                                       class="pl-8 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('amount') border-red-500 @enderror"
                                       placeholder="Enter amount">
                            </div>
                            @error('amount')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Available: {{ $userCurrency === 'INR' ? '₹' : '$' }}{{ number_format($availableBalance, 2) }}</p>
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-6">
                            <label for="method" class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                            <select name="method" id="method" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('method') border-red-500 @enderror">
                                <option value="">Select payment method</option>
                                <option value="paypal" {{ old('method') == 'paypal' ? 'selected' : '' }}>PayPal</option>
                                <option value="stripe" {{ old('method') == 'stripe' ? 'selected' : '' }}>Stripe</option>
                                <option value="bank_transfer" {{ old('method') == 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                                <option value="crypto" {{ old('method') == 'crypto' ? 'selected' : '' }}>Cryptocurrency</option>
                                <option value="upi" {{ old('method') == 'upi' ? 'selected' : '' }}>UPI</option>
                            </select>
                            @error('method')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Payment Details (Dynamic) -->
                        <div id="paymentDetails" class="mb-6" style="display: none;">
                            <!-- PayPal Details -->
                            <div id="paypalDetails" class="payment-method-details" style="display: none;">
                                <h4 class="text-lg font-medium text-gray-900 mb-4">PayPal Details</h4>
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label for="paypal_email" class="block text-sm font-medium text-gray-700 mb-2">PayPal Email</label>
                                        <input type="email" name="payment_details[email]" id="paypal_email" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="your@email.com">
                                    </div>
                                </div>
                            </div>

                            <!-- Stripe Details -->
                            <div id="stripeDetails" class="payment-method-details" style="display: none;">
                                <h4 class="text-lg font-medium text-gray-900 mb-4">Stripe Details</h4>
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label for="stripe_account_id" class="block text-sm font-medium text-gray-700 mb-2">Stripe Account ID</label>
                                        <input type="text" name="payment_details[account_id]" id="stripe_account_id" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="acct_xxxxxxxxxxxxx">
                                    </div>
                                </div>
                            </div>

                            <!-- Bank Transfer Details -->
                            <div id="bankTransferDetails" class="payment-method-details" style="display: none;">
                                <h4 class="text-lg font-medium text-gray-900 mb-4">Bank Transfer Details</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="bank_account_number" class="block text-sm font-medium text-gray-700 mb-2">Account Number</label>
                                        <input type="text" name="payment_details[account_number]" id="bank_account_number" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="1234567890">
                                    </div>
                                    <div>
                                        <label for="bank_ifsc_code" class="block text-sm font-medium text-gray-700 mb-2">IFSC Code</label>
                                        <input type="text" name="payment_details[ifsc_code]" id="bank_ifsc_code" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="SBIN0001234">
                                    </div>
                                    <div class="md:col-span-2">
                                        <label for="bank_account_holder" class="block text-sm font-medium text-gray-700 mb-2">Account Holder Name</label>
                                        <input type="text" name="payment_details[account_holder]" id="bank_account_holder" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="John Doe">
                                    </div>
                                </div>
                            </div>

                            <!-- Crypto Details -->
                            <div id="cryptoDetails" class="payment-method-details" style="display: none;">
                                <h4 class="text-lg font-medium text-gray-900 mb-4">Cryptocurrency Details</h4>
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label for="crypto_wallet_address" class="block text-sm font-medium text-gray-700 mb-2">Wallet Address</label>
                                        <input type="text" name="payment_details[wallet_address]" id="crypto_wallet_address" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="1A1zP1eP5QGefi2DMPTfTL5SLmv7DivfNa">
                                        <p class="mt-1 text-sm text-gray-500">Enter your Bitcoin, Ethereum, or other cryptocurrency wallet address</p>
                                    </div>
                                </div>
                            </div>

                            <!-- UPI Details -->
                            <div id="upiDetails" class="payment-method-details" style="display: none;">
                                <h4 class="text-lg font-medium text-gray-900 mb-4">UPI Details</h4>
                                <div class="grid grid-cols-1 gap-4">
                                    <div>
                                        <label for="upi_id" class="block text-sm font-medium text-gray-700 mb-2">UPI ID</label>
                                        <input type="text" name="payment_details[upi_id]" id="upi_id" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500" placeholder="username@upi">
                                        <p class="mt-1 text-sm text-gray-500">Enter your UPI ID (e.g., username@upi, username@bank)</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                <i class="fas fa-paper-plane mr-2"></i>Submit Withdrawal Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Information -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mt-8">
                <div class="flex">
                    <i class="fas fa-info-circle text-yellow-600 text-xl mr-3 mt-0.5"></i>
                    <div>
                        <h3 class="text-lg font-medium text-yellow-900">Important Information</h3>
                        <ul class="mt-2 text-sm text-yellow-800 space-y-1">
                            <li>• Withdrawal requests are processed within 24-48 hours</li>
                            <li>• You can only have one pending withdrawal at a time</li>
                            <li>• Minimum withdrawal amount: {{ $userCurrency === 'INR' ? '₹' : '$' }}{{ number_format($minWithdrawal, 2) }}</li>
                            <li>• You can cancel pending withdrawals before they are processed</li>
                            <li>• Processing fees may apply depending on the payment method</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('method').addEventListener('change', function() {
            const method = this.value;
            const paymentDetails = document.getElementById('paymentDetails');
            const methodDetails = document.querySelectorAll('.payment-method-details');

            // Hide all method details
            methodDetails.forEach(detail => detail.style.display = 'none');
            paymentDetails.style.display = 'none';

            if (method) {
                paymentDetails.style.display = 'block';
                const targetDetail = document.getElementById(method + 'Details');
                if (targetDetail) {
                    targetDetail.style.display = 'block';
                }
            }
        });

        // Show method details if method is pre-selected (e.g., on validation errors)
        const selectedMethod = document.getElementById('method').value;
        if (selectedMethod) {
            document.getElementById('method').dispatchEvent(new Event('change'));
        }
    </script>
</x-app-layout>
