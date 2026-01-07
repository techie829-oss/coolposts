<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Withdrawal Details') }}
        </h2>
    </x-slot>

<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">Withdrawal #{{ $withdrawal->id }}</h2>
                    <a href="{{ route('admin.withdrawals.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                        Back to Withdrawals
                    </a>
                </div>

                <!-- Status Badge -->
                <div class="mb-6">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        {{ $withdrawal->status === 'completed' ? 'bg-green-100 text-green-800' :
                           ($withdrawal->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                           ($withdrawal->status === 'processing' ? 'bg-blue-100 text-blue-800' :
                           ($withdrawal->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800'))) }}">
                        {{ ucfirst($withdrawal->status) }}
                    </span>
                </div>

                <!-- Withdrawal Details -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Withdrawal Information</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Amount:</span>
                                <span class="font-semibold text-gray-900">{{ $withdrawal->currency }} {{ number_format($withdrawal->amount, 2) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Method:</span>
                                <span class="font-semibold text-gray-900">{{ ucfirst(str_replace('_', ' ', $withdrawal->method)) }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Requested:</span>
                                <span class="font-semibold text-gray-900">{{ $withdrawal->requested_at->format('M j, Y g:i A') }}</span>
                            </div>
                            @if($withdrawal->processed_at)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Processed:</span>
                                <span class="font-semibold text-gray-900">{{ $withdrawal->processed_at->format('M j, Y g:i A') }}</span>
                            </div>
                            @endif
                            @if($withdrawal->notes)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Notes:</span>
                                <span class="font-semibold text-gray-900">{{ $withdrawal->notes }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">User Information</h3>
                        <div class="space-y-4">
                            <div class="flex justify-between">
                                <span class="text-gray-600">User:</span>
                                <span class="font-semibold text-gray-900">{{ $withdrawal->user->name }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Email:</span>
                                <span class="font-semibold text-gray-900">{{ $withdrawal->user->email }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">User ID:</span>
                                <span class="font-semibold text-gray-900">{{ $withdrawal->user->id }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Joined:</span>
                                <span class="font-semibold text-gray-900">{{ $withdrawal->user->created_at->format('M j, Y') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Details -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Payment Details</h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($withdrawal->payment_details as $key => $value)
                            <div class="flex justify-between">
                                <span class="text-gray-600 capitalize">{{ str_replace('_', ' ', $key) }}:</span>
                                <span class="font-semibold text-gray-900">
                                    @if($key === 'email' || $key === 'account_number')
                                        {{ substr($value, 0, 4) . '****' . substr($value, -4) }}
                                    @else
                                        {{ $value }}
                                    @endif
                                </span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                @if($withdrawal->status === 'pending')
                <div class="bg-yellow-50 rounded-lg p-6 mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Process Withdrawal</h3>
                    <div class="flex flex-wrap gap-4">
                        <form action="{{ route('admin.withdrawals.process', $withdrawal) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                Mark as Processing
                            </button>
                        </form>
                        <form action="{{ route('admin.withdrawals.complete', $withdrawal) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                Mark as Completed
                            </button>
                        </form>
                        <form action="{{ route('admin.withdrawals.fail', $withdrawal) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                Mark as Failed
                            </button>
                        </form>
                    </div>
                </div>
                @endif

                @if($withdrawal->status === 'processing')
                <div class="bg-blue-50 rounded-lg p-6 mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Complete Processing</h3>
                    <div class="flex flex-wrap gap-4">
                        <form action="{{ route('admin.withdrawals.complete', $withdrawal) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                Mark as Completed
                            </button>
                        </form>
                        <form action="{{ route('admin.withdrawals.fail', $withdrawal) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                Mark as Failed
                            </button>
                        </form>
                    </div>
                </div>
                @endif

                <!-- Transaction History -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900">Transaction History</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Withdrawal Requested</p>
                                    <p class="text-sm text-gray-500">{{ $withdrawal->requested_at->format('M j, Y g:i A') }}</p>
                                </div>
                            </div>
                            @if($withdrawal->status !== 'pending')
                            <div class="flex items-center space-x-3">
                                <div class="w-3 h-3 {{ $withdrawal->status === 'completed' ? 'bg-green-500' : ($withdrawal->status === 'failed' ? 'bg-red-500' : 'bg-yellow-500') }} rounded-full"></div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Status Updated to {{ ucfirst($withdrawal->status) }}</p>
                                    <p class="text-sm text-gray-500">{{ $withdrawal->updated_at->format('M j, Y g:i A') }}</p>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
