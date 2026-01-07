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
                            <span class="font-medium">{{ $result['currency'] === 'INR' ? '₹' : '$' }}{{ number_format($result['amount'], 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Currency:</span>
                            <span class="font-medium">{{ $result['currency'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Transaction ID:</span>
                            <span class="font-medium text-sm">{{ $result['transaction_id'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Enter Payment Details</h3>

                    <form id="payment-form" class="space-y-6">
                        <div>
                            <label for="card-element" class="block text-sm font-medium text-gray-700 mb-2">
                                Credit or Debit Card
                            </label>
                            <div id="card-element" class="border border-gray-300 rounded-md p-3 focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500">
                                <!-- Stripe Elements will be inserted here -->
                            </div>
                            <div id="card-errors" class="mt-2 text-sm text-red-600 hidden">
                                <!-- Error messages will be displayed here -->
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <input type="checkbox" id="save-card" class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="save-card" class="ml-2 text-sm text-gray-700">
                                    Save card for future payments
                                </label>
                            </div>
                        </div>

                        <button type="submit" id="submit-button"
                                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-4 rounded-md transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span id="button-text">
                                <i class="fas fa-lock mr-2"></i>Pay {{ $result['currency'] === 'INR' ? '₹' : '$' }}{{ number_format($result['amount'], 2) }}
                            </span>
                            <span id="spinner" class="hidden">
                                <i class="fas fa-spinner fa-spin mr-2"></i>Processing...
                            </span>
                        </button>
                    </form>

                    <!-- Security Notice -->
                    <div class="mt-6 p-4 bg-gray-50 rounded-md">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-shield-alt text-green-600"></i>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-gray-900">Secure Payment</h4>
                                <p class="text-sm text-gray-600">
                                    Your payment information is encrypted and secure. We use Stripe for secure payment processing.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stripe.js -->
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        // Initialize Stripe
        const stripe = Stripe('{{ $result["publishable_key"] }}');
        const elements = stripe.elements();

        // Create card element
        const cardElement = elements.create('card', {
            style: {
                base: {
                    fontSize: '16px',
                    color: '#424770',
                    '::placeholder': {
                        color: '#aab7c4',
                    },
                },
                invalid: {
                    color: '#9e2146',
                },
            },
        });

        // Mount card element
        cardElement.mount('#card-element');

        // Handle form submission
        const form = document.getElementById('payment-form');
        const submitButton = document.getElementById('submit-button');
        const buttonText = document.getElementById('button-text');
        const spinner = document.getElementById('spinner');

        form.addEventListener('submit', async (event) => {
            event.preventDefault();

            // Disable submit button
            submitButton.disabled = true;
            buttonText.classList.add('hidden');
            spinner.classList.remove('hidden');

            // Confirm payment with Stripe
            const { error, paymentIntent } = await stripe.confirmCardPayment('{{ $result["client_secret"] }}', {
                payment_method: {
                    card: cardElement,
                    billing_details: {
                        name: '{{ auth()->user()->name }}',
                        email: '{{ auth()->user()->email }}',
                    },
                },
            });

            if (error) {
                // Show error message
                const errorElement = document.getElementById('card-errors');
                errorElement.textContent = error.message;
                errorElement.classList.remove('hidden');

                // Re-enable submit button
                submitButton.disabled = false;
                buttonText.classList.remove('hidden');
                spinner.classList.add('hidden');
            } else {
                // Payment successful
                if (paymentIntent.status === 'succeeded') {
                    // Redirect to success page
                    window.location.href = '{{ route("payments.stripe.success") }}';
                }
            }
        });

        // Handle card element errors
        cardElement.addEventListener('change', ({ error }) => {
            const errorElement = document.getElementById('card-errors');
            if (error) {
                errorElement.textContent = error.message;
                errorElement.classList.remove('hidden');
            } else {
                errorElement.textContent = '';
                errorElement.classList.add('hidden');
            }
        });
    </script>
</x-app-layout>
