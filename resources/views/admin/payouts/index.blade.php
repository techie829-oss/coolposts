<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Payouts Management') }}
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
                    <form method="GET" action="{{ route('admin.payouts.index') }}" class="flex flex-wrap gap-4">
                        <div class="flex-1 min-w-64">
                            <input type="text" name="search" value="{{ request('search') }}"
                                   placeholder="Search by user name or email..."
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div>
                            <select name="currency" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                <option value="">All Currencies</option>
                                <option value="INR" {{ request('currency') === 'INR' ? 'selected' : '' }}>INR</option>
                                <option value="USD" {{ request('currency') === 'USD' ? 'selected' : '' }}>USD</option>
                            </select>
                        </div>
                        <div class="flex gap-2">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-search mr-2"></i>Search
                            </button>
                            <a href="{{ route('admin.payouts.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-times mr-2"></i>Clear
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Payouts Summary -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                    <i class="fas fa-users text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-2xl font-bold text-gray-900">{{ $users->count() }}</div>
                                <div class="text-sm text-gray-600">Eligible Users</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                    <i class="fas fa-rupee-sign text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-2xl font-bold text-gray-900">₹{{ number_format($users->sum('balance_inr'), 2) }}</div>
                                <div class="text-sm text-gray-600">Total INR Balance</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                    <i class="fas fa-dollar-sign text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-2xl font-bold text-gray-900">${{ number_format($users->sum('balance_usd'), 2) }}</div>
                                <div class="text-sm text-gray-600">Total USD Balance</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                    <i class="fas fa-coins text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-2xl font-bold text-gray-900">{{ $users->where('balance_inr', '>=', 100)->count() + $users->where('balance_usd', '>=', 1)->count() }}</div>
                                <div class="text-sm text-gray-600">Above Min Threshold</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payouts Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-list mr-2 text-gray-600"></i>Users Eligible for Payouts
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    @if($users->count() > 0)
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balances</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Eligibility</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10">
                                                    <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                        <span class="text-sm font-medium text-gray-700">{{ substr($user->name, 0, 2) }}</span>
                                                    </div>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                                    <div class="text-xs text-gray-400">Joined {{ $user->created_at->format('M d, Y') }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="space-y-2">
                                                <div class="flex items-center justify-between">
                                                    <span class="text-sm text-gray-600">INR:</span>
                                                    <span class="text-sm font-medium text-gray-900">₹{{ number_format($user->balance_inr, 2) }}</span>
                                                </div>
                                                <div class="flex items-center justify-between">
                                                    <span class="text-sm text-gray-600">USD:</span>
                                                    <span class="text-sm font-medium text-gray-900">${{ number_format($user->balance_usd, 2) }}</span>
                                                </div>
                                                <div class="text-xs text-gray-500">Preferred: {{ $user->currency ?? 'INR' }}</div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="space-y-2">
                                                @if($user->balance_inr >= 100)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <i class="fas fa-check mr-1"></i>INR Eligible
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        <i class="fas fa-times mr-1"></i>INR: ₹{{ 100 - $user->balance_inr }} more needed
                                                    </span>
                                                @endif

                                                @if($user->balance_usd >= 1)
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        <i class="fas fa-check mr-1"></i>USD Eligible
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                        <i class="fas fa-times mr-1"></i>USD: ${{ 1 - $user->balance_usd }} more needed
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                @if($user->balance_inr >= 100)
                                                    <button onclick="openPayoutModal('{{ $user->id }}', 'INR', {{ $user->balance_inr }})"
                                                            class="text-blue-600 hover:text-blue-900">
                                                        <i class="fas fa-rupee-sign mr-1"></i>Process INR
                                                    </button>
                                                @endif

                                                @if($user->balance_usd >= 1)
                                                    <button onclick="openPayoutModal('{{ $user->id }}', 'USD', {{ $user->balance_usd }})"
                                                            class="text-green-600 hover:text-green-900">
                                                        <i class="fas fa-dollar-sign mr-1"></i>Process USD
                                                    </button>
                                                @endif

                                                @if($user->balance_inr < 100 && $user->balance_usd < 1)
                                                    <span class="text-gray-400">Not eligible</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="px-6 py-4 border-t border-gray-200">
                            {{ $users->links() }}
                        </div>
                    @else
                        <div class="p-6 text-center">
                            <p class="text-gray-500">No users found with balances above the minimum threshold.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Payout Modal -->
    <div id="payoutModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Process Payout</h3>
                <form id="payoutForm" method="POST" class="space-y-4">
                    @csrf
                    <input type="hidden" id="payoutUserId" name="user_id">
                    <input type="hidden" id="payoutCurrency" name="currency">

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">User</label>
                        <p id="payoutUserName" class="text-sm text-gray-900"></p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Available Balance</label>
                        <p id="payoutBalance" class="text-sm text-gray-900"></p>
                    </div>

                    <div>
                        <label for="payoutAmount" class="block text-sm font-medium text-gray-700 mb-1">Payout Amount</label>
                        <input type="number" id="payoutAmount" name="amount" step="0.01" min="0.01"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="payoutNotes" class="block text-sm font-medium text-gray-700 mb-1">Notes (Optional)</label>
                        <textarea id="payoutNotes" name="notes" rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Add any notes about this payout..."></textarea>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closePayoutModal()"
                                class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            Cancel
                        </button>
                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                            Process Payout
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openPayoutModal(userId, currency, balance) {
            document.getElementById('payoutUserId').value = userId;
            document.getElementById('payoutCurrency').value = currency;
            document.getElementById('payoutBalance').textContent = currency === 'INR' ? `₹${balance.toFixed(2)}` : `$${balance.toFixed(2)}`;
            document.getElementById('payoutAmount').value = balance.toFixed(2);
            document.getElementById('payoutAmount').max = balance;
            document.getElementById('payoutModal').classList.remove('hidden');
        }

        function closePayoutModal() {
            document.getElementById('payoutModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('payoutModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePayoutModal();
            }
        });
    </script>
</x-app-layout>
