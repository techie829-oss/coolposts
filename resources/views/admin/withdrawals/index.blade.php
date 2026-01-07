<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Withdrawal Management') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Pending</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_pending'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-cog text-blue-600 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Processing</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_processing'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Completed</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_completed'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <i class="fas fa-list text-gray-600 text-2xl"></i>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Requests</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_requests'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Withdrawals Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Withdrawal Requests</h3>

                    @if($withdrawals->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Requested</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($withdrawals as $withdrawal)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                #{{ $withdrawal->id }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                                                            <span class="text-sm font-medium text-gray-700">
                                                                {{ strtoupper(substr($withdrawal->user->name, 0, 2)) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $withdrawal->user->name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $withdrawal->user->email }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $withdrawal->formatted_amount }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <i class="{{ $withdrawal->method_icon }} text-gray-400 mr-2"></i>
                                                    <span class="text-sm text-gray-900">{{ $withdrawal->method_display_name }}</span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $withdrawal->status_badge_class }}">
                                                    {{ ucfirst($withdrawal->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $withdrawal->requested_at->format('M d, Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-2">
                                                    <a href="{{ route('admin.withdrawals.show', $withdrawal) }}" class="text-blue-600 hover:text-blue-900">
                                                        <i class="fas fa-eye"></i> View
                                                    </a>

                                                    @if($withdrawal->status === 'pending')
                                                        <form action="{{ route('admin.withdrawals.process', $withdrawal) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" class="text-blue-600 hover:text-blue-900">
                                                                <i class="fas fa-cog"></i> Process
                                                            </button>
                                                        </form>
                                                    @endif

                                                    @if(in_array($withdrawal->status, ['pending', 'processing']))
                                                        <form action="{{ route('admin.withdrawals.complete', $withdrawal) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" class="text-green-600 hover:text-green-900" onclick="return confirm('Mark this withdrawal as completed?')">
                                                                <i class="fas fa-check"></i> Complete
                                                            </button>
                                                        </form>

                                                        <form action="{{ route('admin.withdrawals.cancel', $withdrawal) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Cancel this withdrawal and refund the user?')">
                                                                <i class="fas fa-times"></i> Cancel
                                                            </button>
                                                        </form>

                                                        <form action="{{ route('admin.withdrawals.fail', $withdrawal) }}" method="POST" class="inline">
                                                            @csrf
                                                            <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Mark this withdrawal as failed?')">
                                                                <i class="fas fa-exclamation-triangle"></i> Fail
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $withdrawals->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-money-bill-wave text-gray-400 text-4xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No Withdrawal Requests</h3>
                            <p class="text-gray-500">There are no withdrawal requests to process at the moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
