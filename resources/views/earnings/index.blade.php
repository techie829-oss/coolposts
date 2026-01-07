<x-app-layout>
    <x-slot name="title">Earnings Dashboard</x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Earnings Dashboard</h1>
                <p class="mt-2 text-gray-600">Track and manage your earnings</p>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Earnings -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-green-500 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-dollar-sign text-white text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Earnings</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $currencySymbol }}{{ number_format($stats['total_earnings'], 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Available Balance -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-blue-500 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-wallet text-white text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Available Balance</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $currencySymbol }}{{ number_format($stats['available_balance'], 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Earnings -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-yellow-500 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-clock text-white text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Pending Earnings</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $currencySymbol }}{{ number_format($stats['pending_earnings'], 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- This Month -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-calendar-alt text-white text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">This Month</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $currencySymbol }}{{ number_format($stats['this_month_earnings'], 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <form method="GET" action="{{ route('earnings.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select name="status" id="status" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Status</option>
                            <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                    </div>

                    <!-- Source Filter -->
                    <div>
                        <label for="source" class="block text-sm font-medium text-gray-700 mb-2">Source</label>
                        <select name="source" id="source" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            <option value="all" {{ $source === 'all' ? 'selected' : '' }}>All Sources</option>
                            <option value="link" {{ $source === 'link' ? 'selected' : '' }}>Links</option>
                            <option value="blog" {{ $source === 'blog' ? 'selected' : '' }}>Blog Posts</option>
                        </select>
                    </div>

                    <!-- Date Range Filter -->
                    <div>
                        <label for="date_range" class="block text-sm font-medium text-gray-700 mb-2">Date Range</label>
                        <select name="date_range" id="date_range" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                            <option value="all" {{ $dateRange === 'all' ? 'selected' : '' }}>All Time</option>
                            <option value="today" {{ $dateRange === 'today' ? 'selected' : '' }}>Today</option>
                            <option value="week" {{ $dateRange === 'week' ? 'selected' : '' }}>This Week</option>
                            <option value="month" {{ $dateRange === 'month' ? 'selected' : '' }}>This Month</option>
                            <option value="year" {{ $dateRange === 'year' ? 'selected' : '' }}>This Year</option>
                        </select>
                    </div>

                    <!-- Filter Button -->
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                            <i class="fas fa-filter mr-2"></i>Filter
                        </button>
                    </div>
                </form>
            </div>

            <!-- Earnings Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Earnings History</h2>

                    @if($earnings->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Source</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Country</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($earnings as $earning)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    @if($earning->link_id)
                                                        <i class="fas fa-link text-blue-500 mr-2"></i>
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ $earning->link->title ?? 'Untitled Link' }}
                                                            </div>
                                                            <div class="text-xs text-gray-500">Link</div>
                                                        </div>
                                                    @elseif($earning->blog_post_id)
                                                        <i class="fas fa-newspaper text-purple-500 mr-2"></i>
                                                        <div>
                                                            <div class="text-sm font-medium text-gray-900">
                                                                {{ $earning->blogPost->title ?? 'Untitled Post' }}
                                                            </div>
                                                            <div class="text-xs text-gray-500">Blog Post</div>
                                                        </div>
                                                    @else
                                                        <i class="fas fa-question-circle text-gray-400 mr-2"></i>
                                                        <div class="text-sm text-gray-500">Unknown Source</div>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-semibold text-gray-900">
                                                    {{ $currencySymbol }}{{ number_format($earning->amount, 2) }}
                                                </div>
                                                <div class="text-xs text-gray-500">{{ $earning->currency }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if($earning->status === 'approved') bg-green-100 text-green-800
                                                    @elseif($earning->status === 'pending') bg-yellow-100 text-yellow-800
                                                    @else bg-red-100 text-red-800
                                                    @endif">
                                                    {{ ucfirst($earning->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $earning->created_at->format('M d, Y') }}
                                                <div class="text-xs text-gray-400">{{ $earning->created_at->format('h:i A') }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($earning->country_name)
                                                    <i class="fas fa-globe mr-1"></i>
                                                    {{ $earning->country_name }}
                                                @else
                                                    <span class="text-gray-400">N/A</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $earnings->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="fas fa-chart-line text-gray-400 text-5xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No earnings found</h3>
                            <p class="text-gray-600 mb-4">Start creating links or blog posts to earn money!</p>
                            <div class="flex justify-center gap-4">
                                <a href="{{ route('links.create') }}" class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition-colors">
                                    <i class="fas fa-plus mr-2"></i>Create Link
                                </a>
                                <a href="{{ route('blog.create') }}" class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                    <i class="fas fa-edit mr-2"></i>Create Blog Post
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            @if($globalSettings->isEarningsEnabled())
            <div class="mt-6 bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('withdrawals.index') }}" class="flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <i class="fas fa-money-bill-wave mr-2"></i>
                        Withdraw Earnings
                    </a>
                    <a href="{{ route('links.index') }}" class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-link mr-2"></i>
                        Manage Links
                    </a>
                    <a href="{{ route('blog.index') }}" class="flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        <i class="fas fa-newspaper mr-2"></i>
                        Manage Blog Posts
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
