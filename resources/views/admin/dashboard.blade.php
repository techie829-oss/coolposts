<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Header -->
            <div class="mb-8">
                <div
                    class="bg-gradient-to-r from-purple-600 via-pink-600 to-red-600 rounded-3xl p-8 text-white relative overflow-hidden">
                    <div class="absolute inset-0 bg-black/10"></div>
                    <div
                        class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16">
                    </div>
                    <div
                        class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full translate-y-12 -translate-x-12">
                    </div>

                    <div class="relative z-10">
                        <h1 class="text-4xl font-bold text-white mb-2">
                            Welcome to Admin Panel, {{ Auth::user()->name }}! 👑
                        </h1>
                        <p class="text-purple-100 text-lg">
                            Manage your CoolPosts platform from here
                        </p>
                    </div>
                </div>
            </div>

            <!-- System Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Users -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                    <i class="fas fa-users text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Total Users</div>
                                <div class="text-2xl font-semibold text-gray-900">
                                    {{ number_format($stats['total_users']) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Links -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                    <i class="fas fa-link text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Total Links</div>
                                <div class="text-2xl font-semibold text-gray-900">
                                    {{ number_format($stats['total_links']) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Clicks -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                    <i class="fas fa-mouse-pointer text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Total Clicks</div>
                                <div class="text-2xl font-semibold text-gray-900">
                                    {{ number_format($stats['total_clicks']) }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Earnings -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                    <i class="fas fa-dollar-sign text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Total Earnings</div>
                                <div class="text-2xl font-semibold text-gray-900">
                                    ${{ number_format($stats['total_earnings'], 4) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <a href="{{ route('admin.users.index') }}"
                    class="group p-6 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow border border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                <i class="fas fa-users text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900 group-hover:text-blue-600">Manage Users</h3>
                            <p class="text-sm text-gray-500">View and manage all users</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.links.index') }}"
                    class="group p-6 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow border border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <i class="fas fa-link text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900 group-hover:text-green-600">Manage Links</h3>
                            <p class="text-sm text-gray-500">View and manage all links</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('global-settings') }}"
                    class="group p-6 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow border border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                <i class="fas fa-coins text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900 group-hover:text-green-600">Business Settings
                            </h3>
                            <p class="text-sm text-gray-500">Monetization rates, subscriptions & withdrawals</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('branding-settings') }}"
                    class="group p-6 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow border border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                <i class="fas fa-palette text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900 group-hover:text-purple-600">Branding Settings
                            </h3>
                            <p class="text-sm text-gray-500">Logo, colors & visual identity</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('ai-settings') }}"
                    class="group p-6 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow border border-gray-200">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-indigo-500 rounded-md flex items-center justify-center">
                                <i class="fas fa-robot text-white"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-medium text-gray-900 group-hover:text-indigo-600">AI Settings
                            </h3>
                            <p class="text-sm text-gray-500">AI integrations & API keys</p>
                        </div>
                    </div>
                </a>

        <a href="{{ route('admin.user-management') }}"
            class="group p-6 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-orange-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-user-cog text-white"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900 group-hover:text-orange-600">User AI Controls</h3>
                    <p class="text-sm text-gray-500">Manage AI access per user</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.referral-settings') }}"
            class="group p-6 bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow border border-gray-200">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                        <i class="fas fa-users text-white"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900 group-hover:text-green-600">Referral Settings</h3>
                    <p class="text-sm text-gray-500">Configure referral system & commissions</p>
                </div>
            </div>
        </a>

                <a href="{{ route('admin.earnings.index') }}"
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-dollar-sign text-yellow-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-lg font-medium text-gray-900">Manage Earnings</div>
                                <div class="text-sm text-gray-500">Approve and manage earnings</div>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.withdrawals.index') }}"
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-money-bill-wave text-purple-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-lg font-medium text-gray-900">Manage Withdrawals</div>
                                <div class="text-sm text-gray-500">Process user withdrawal requests</div>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.settings') }}"
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-server text-blue-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-lg font-medium text-gray-900">Technical Settings</div>
                                <div class="text-sm text-gray-500">Security, notifications & platform config</div>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.payment-gateways.index') }}"
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-credit-card text-indigo-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-lg font-medium text-gray-900">Payment Gateways</div>
                                <div class="text-sm text-gray-500">Configure Stripe, PayPal, Paytm, Razorpay</div>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.payment-transactions.index') }}"
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-teal-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-receipt text-teal-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-lg font-medium text-gray-900">Payment Transactions</div>
                                <div class="text-sm text-gray-500">Monitor all payment activities</div>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.system-analytics') }}"
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-chart-line text-red-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-lg font-medium text-gray-900">Analytics</div>
                                <div class="text-sm text-gray-500">View detailed reports</div>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.fraud-detection') }}"
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-shield-alt text-orange-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-lg font-medium text-gray-900">Fraud Detection</div>
                                <div class="text-sm text-gray-500">Monitor and prevent fraud</div>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.performance') }}"
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-cyan-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-tachometer-alt text-cyan-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-lg font-medium text-gray-900">Performance</div>
                                <div class="text-sm text-gray-500">System optimization & monitoring</div>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.ad-networks.index') }}"
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-emerald-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-ad text-emerald-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-lg font-medium text-gray-900">Ad Networks</div>
                                <div class="text-sm text-gray-500">Manage ad network integrations</div>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.realtime.analytics') }}"
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-pink-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-broadcast-tower text-pink-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-lg font-medium text-gray-900">Real-time Analytics</div>
                                <div class="text-sm text-gray-500">Live system monitoring</div>
                            </div>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.system-health') }}"
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg hover:shadow-md transition-shadow duration-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-12 bg-lime-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-heartbeat text-lime-600 text-xl"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-lg font-medium text-gray-900">System Health</div>
                                <div class="text-sm text-gray-500">Monitor system status</div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Recent Activities -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Recent Users -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Users</h3>
                        <div class="space-y-3">
                            @forelse($recentUsers as $user)
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center">
                                            <span
                                                class="text-sm font-medium text-gray-600">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                    </div>
                                    <div class="ml-auto">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">No recent users</p>
                            @endforelse
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('admin.users.index') }}"
                                class="text-sm text-blue-600 hover:text-blue-500">View all users →</a>
                        </div>
                    </div>
                </div>

                <!-- Recent Links -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Links</h3>
                        <div class="space-y-3">
                            @forelse($recentLinks as $link)
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-link text-green-600 text-sm"></i>
                                        </div>
                                    </div>
                                    <div class="ml-3 flex-1 min-w-0">
                                        <div class="text-sm font-medium text-gray-900 truncate">{{ $link->title }}
                                        </div>
                                        <div class="text-sm text-gray-500">{{ $link->user->name }}</div>
                                    </div>
                                    <div class="ml-auto">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $link->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $link->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">No recent links</p>
                            @endforelse
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('admin.links.index') }}"
                                class="text-sm text-blue-600 hover:text-blue-500">View all links →</a>
                        </div>
                    </div>
                </div>

                <!-- Recent Earnings -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Earnings</h3>
                        <div class="space-y-3">
                            @forelse($recentEarnings as $earning)
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-dollar-sign text-yellow-600 text-sm"></i>
                                        </div>
                                    </div>
                                    <div class="ml-3 flex-1 min-w-0">
                                        <div class="text-sm font-medium text-gray-900">
                                            ${{ number_format($earning->amount, 4) }}</div>
                                        <div class="text-sm text-gray-500">{{ $earning->user->name }}</div>
                                    </div>
                                    <div class="ml-auto">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $earning->status === 'approved'
                                                ? 'bg-green-100 text-green-800'
                                                : ($earning->status === 'pending'
                                                    ? 'bg-yellow-100 text-yellow-800'
                                                    : 'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($earning->status) }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <p class="text-gray-500 text-sm">No recent earnings</p>
                            @endforelse
                        </div>
                        <div class="mt-4">
                            <a href="{{ route('admin.earnings.index') }}"
                                class="text-sm text-blue-600 hover:text-blue-500">View all earnings →</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
