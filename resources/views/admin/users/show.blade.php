<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('User Details') }} - {{ $user->name }}
            </h2>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                <i class="fas fa-arrow-left mr-2"></i>Back to Users
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

            <!-- User Information -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-user mr-2 text-blue-600"></i>User Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">Basic Details</h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Name</label>
                                    <p class="text-sm text-gray-900">{{ $user->name }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Email</label>
                                    <p class="text-sm text-gray-900">{{ $user->email }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Role</label>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800' }}">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Joined</label>
                                    <p class="text-sm text-gray-900">{{ $user->created_at->format('M d, Y \a\t g:i A') }}</p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 mb-3">Account Status</h4>
                            <div class="space-y-3">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Email Verified</label>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->email_verified_at ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $user->email_verified_at ? 'Yes' : 'No' }}
                                    </span>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Last Login</label>
                                    <p class="text-sm text-gray-900">{{ $user->last_login_at ? $user->last_login_at->format('M d, Y \a\t g:i A') : 'Never' }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Preferred Currency</label>
                                    <p class="text-sm text-gray-900">{{ $user->currency ?? 'INR' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Statistics -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-chart-bar mr-2 text-green-600"></i>User Statistics
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ $userStats['total_links'] }}</div>
                            <div class="text-sm text-gray-600">Total Links</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">{{ $userStats['active_links'] }}</div>
                            <div class="text-sm text-gray-600">Active Links</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-purple-600">{{ $userStats['total_clicks'] }}</div>
                            <div class="text-sm text-gray-600">Total Clicks</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-yellow-600">{{ number_format($userStats['total_earnings'], 4) }}</div>
                            <div class="text-sm text-gray-600">Total Earnings</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- User Links -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-link mr-2 text-purple-600"></i>User Links
                    </h3>
                </div>
                <div class="p-6">
                    @if($user->links->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Link</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Clicks</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Earnings</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Created</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($user->links as $link)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">{{ $link->title ?: 'Untitled' }}</div>
                                                    <div class="text-sm text-gray-500">{{ $link->short_code }}</div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $link->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ $link->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $link->clicks()->count() }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ number_format($link->earnings()->where('status', 'approved')->sum('amount'), 4) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $link->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <a href="{{ route('admin.links.show', $link) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-gray-500 text-center py-4">No links found for this user.</p>
                    @endif
                </div>
            </div>

            <!-- User Earnings -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-coins mr-2 text-yellow-600"></i>User Earnings
                    </h3>
                </div>
                <div class="p-6">
                    @if($user->earnings->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Link</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($user->earnings->take(10) as $earning)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900">{{ $earning->link->title ?: 'Untitled' }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ number_format($earning->amount, 4) }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $earning->status === 'approved' ? 'bg-green-100 text-green-800' :
                                                       ($earning->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                    {{ ucfirst($earning->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $earning->created_at->format('M d, Y') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if($user->earnings->count() > 10)
                            <div class="mt-4 text-center">
                                <p class="text-sm text-gray-500">Showing 10 of {{ $user->earnings->count() }} earnings</p>
                            </div>
                        @endif
                    @else
                        <p class="text-gray-500 text-center py-4">No earnings found for this user.</p>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900">
                        <i class="fas fa-cog mr-2 text-gray-600"></i>Actions
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex flex-wrap gap-4">
                        <!-- Change Role -->
                        <form method="POST" action="{{ route('admin.users.update-role', $user) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="role" value="{{ $user->role === 'admin' ? 'user' : 'admin' }}">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                <i class="fas fa-user-edit mr-2"></i>
                                Make {{ $user->role === 'admin' ? 'User' : 'Admin' }}
                            </button>
                        </form>

                        <!-- Delete User (if no links) -->
                        @if($user->links->count() === 0)
                            <form method="POST" action="#" class="inline" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                                    <i class="fas fa-trash mr-2"></i>Delete User
                                </button>
                            </form>
                        @else
                            <button disabled class="bg-gray-400 text-white px-4 py-2 rounded-md text-sm font-medium cursor-not-allowed">
                                <i class="fas fa-trash mr-2"></i>Cannot Delete (Has Links)
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
