<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Premium Subscription Plans') }}
            </h2>
            <a href="{{ route('dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
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
            @if($activeSubscription)
                <div class="mb-8 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-crown text-green-600 text-xl"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-green-900">
                                Active Premium Subscription
                            </h3>
                            <p class="text-green-700">
                                You currently have an active <strong>{{ $activeSubscription->plan->name }}</strong>
                                subscription ({{ $activeSubscription->plan->billing_cycle }}).
                            </p>
                            <p class="text-sm text-green-600 mt-1">
                                Expires: {{ $activeSubscription->ends_at->format('M d, Y') }}
                                ({{ $activeSubscription->daysRemaining() }} days remaining)
                            </p>
                        </div>
                        <div class="ml-auto">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <i class="fas fa-check mr-1"></i>Active
                            </span>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Subscription Plans -->
            <div class="space-y-8">
                @foreach($plans as $billingCycle => $cyclePlans)
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">
                            {{ ucfirst($billingCycle) }} Plans
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach($cyclePlans as $plan)
                                <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden {{ $plan->is_popular ? 'ring-2 ring-blue-500' : '' }}">
                                    @if($plan->is_popular)
                                        <div class="bg-blue-500 text-white text-center py-2 text-sm font-medium">
                                            <i class="fas fa-star mr-1"></i>Most Popular
                                        </div>
                                    @endif

                                    <div class="p-6">
                                        <div class="text-center">
                                            <h4 class="text-xl font-bold text-gray-900">{{ $plan->name }}</h4>
                                            <p class="text-gray-600 mt-2">{{ $plan->description }}</p>

                                            <div class="mt-4">
                                                <span class="text-3xl font-bold text-gray-900">
                                                    {{ $plan->getFormattedPrice(auth()->user()->preferred_currency) }}
                                                </span>
                                                <span class="text-gray-600">/{{ $plan->getBillingCycleDisplayName() }}</span>
                                            </div>

                                            @if($plan->isYearly() && $plan->getYearlySavings())
                                                <div class="mt-2">
                                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Save {{ $plan->getYearlySavings() }}%
                                                    </span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="mt-6">
                                            <h5 class="text-sm font-medium text-gray-900 mb-3">Features:</h5>
                                            <ul class="space-y-2">
                                                @foreach($plan->features as $feature)
                                                    <li class="flex items-center text-sm text-gray-600">
                                                        <i class="fas fa-check text-green-500 mr-2"></i>
                                                        {{ $feature }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>

                                        <div class="mt-6">
                                            <button onclick="selectPlan({{ $plan->id }}, '{{ $plan->name }}')"
                                                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-200">
                                                <i class="fas fa-credit-card mr-2"></i>Subscribe Now
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Payment Gateway Selection Modal -->
            <div id="paymentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
                <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
                    <div class="mt-3">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">
                                Select Payment Method for <span id="selectedPlanName"></span>
                            </h3>
                            <button onclick="closePaymentModal()" class="text-gray-400 hover:text-gray-600">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>

                        <form id="paymentForm" method="POST" action="{{ route('subscriptions.payment') }}" class="space-y-4">
                            @csrf
                            <input type="hidden" id="selectedPlanId" name="plan_id">

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Gateway</label>
                                <div id="gatewayOptions" class="space-y-3">
                                    <!-- Gateway options will be loaded here -->
                                </div>
                            </div>

                            <div class="flex justify-end space-x-3 pt-4">
                                <button type="button" onclick="closePaymentModal()"
                                        class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md text-sm font-medium">
                                    Cancel
                                </button>
                                <button type="submit"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-credit-card mr-2"></i>Proceed to Payment
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function selectPlan(planId, planName) {
            document.getElementById('selectedPlanId').value = planId;
            document.getElementById('selectedPlanName').textContent = planName;

            // Load available payment gateways
            loadPaymentGateways();

            // Show modal
            document.getElementById('paymentModal').classList.remove('hidden');
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').classList.add('hidden');
        }

        function loadPaymentGateways() {
            fetch('{{ route("subscriptions.gateways") }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        displayGatewayOptions(data.gateways);
                    } else {
                        console.error('Failed to load gateways:', data.error);
                    }
                })
                .catch(error => {
                    console.error('Error loading gateways:', error);
                });
        }

        function displayGatewayOptions(gateways) {
            const container = document.getElementById('gatewayOptions');
            container.innerHTML = '';

            if (gateways.length === 0) {
                container.innerHTML = '<p class="text-gray-500 text-center py-4">No payment gateways available for your currency.</p>';
                return;
            }

            gateways.forEach(gateway => {
                const gatewayDiv = document.createElement('div');
                gatewayDiv.className = 'flex items-center p-3 border border-gray-200 rounded-md hover:bg-gray-50';

                const radio = document.createElement('input');
                radio.type = 'radio';
                radio.name = 'gateway';
                radio.value = gateway.slug;
                radio.id = `gateway_${gateway.id}`;
                radio.required = true;
                radio.className = 'h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300';

                const label = document.createElement('label');
                label.htmlFor = `gateway_${gateway.id}`;
                label.className = 'ml-3 flex-1 cursor-pointer';

                const gatewayInfo = document.createElement('div');
                gatewayInfo.innerHTML = `
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-${getGatewayIcon(gateway.slug)} text-gray-600"></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-medium text-gray-900">${gateway.name}</div>
                            <div class="text-xs text-gray-500">${gateway.description}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-xs text-gray-500">
                                Fee: ${gateway.transaction_fee_percentage}% + ${gateway.transaction_fee_fixed > 0 ? gateway.transaction_fee_fixed : '0'}
                            </div>
                            ${gateway.is_test_mode ? '<div class="text-xs text-yellow-600">Test Mode</div>' : ''}
                        </div>
                    </div>
                `;

                label.appendChild(gatewayInfo);
                gatewayDiv.appendChild(radio);
                gatewayDiv.appendChild(label);
                container.appendChild(gatewayDiv);
            });

            // Select first gateway by default
            if (gateways.length > 0) {
                document.getElementById(`gateway_${gateways[0].id}`).checked = true;
            }
        }

        function getGatewayIcon(slug) {
            const icons = {
                'stripe': 'credit-card',
                'paypal': 'paypal',
                'paytm': 'wallet',
                'razorpay': 'wallet'
            };
            return icons[slug] || 'credit-card';
        }

        // Close modal when clicking outside
        document.getElementById('paymentModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePaymentModal();
            }
        });
    </script>
</x-app-layout>
