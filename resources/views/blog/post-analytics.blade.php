<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Analytics: ') . $post->title }}
            </h2>
            <div class="flex space-x-3">
                <a href="{{ route('blog.manage') }}"
                    class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Manage
                </a>
                <a href="{{ route('blog.show', $post) }}" target="_blank"
                    class="bg-purple-600 hover:bg-purple-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200">
                    <i class="fas fa-external-link-alt mr-2"></i>View Post
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Key Metrics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <!-- Total Views -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-blue-50 text-blue-600 mr-4">
                            <i class="fas fa-eye text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Views</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($post->views) }}</p>
                        </div>
                    </div>
                </div>

                <!-- Avg Time Spent -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-50 text-green-600 mr-4">
                            <i class="fas fa-clock text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Avg. Time Spent</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $post->average_time_spent_minutes }} min</p>
                        </div>
                    </div>
                </div>

                <!-- Bounce Rate -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-red-50 text-red-600 mr-4">
                            <i class="fas fa-sign-out-alt text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Bounce Rate</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $post->bounce_rate_percentage }}%</p>
                        </div>
                    </div>
                </div>

                <!-- Total Earnings -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-50 text-purple-600 mr-4">
                            <i class="fas fa-dollar-sign text-2xl"></i>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500">Total Earnings</p>
                            <p class="text-2xl font-bold text-gray-900">
                                {{ Auth::user()->preferred_currency ?? 'INR' }}
                                {{ number_format($post->total_earnings, 2) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                <!-- Engagement Breakdown -->
                <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-6 lg:col-span-2">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Engagement & Earnings Breakdown</h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-semibold">
                                <tr>
                                    <th class="px-4 py-3 rounded-l-lg">Time Category</th>
                                    <th class="px-4 py-3">Visitor Count</th>
                                    <th class="px-4 py-3">Earnings Generated
                                        ({{ Auth::user()->preferred_currency ?? 'INR' }})</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 text-sm">
                                @forelse ($timeCategories as $category)
                                    <tr>
                                        <td class="px-4 py-3 font-medium text-gray-900">
                                            @if ($category->time_category === 'less_2min')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                    Less than 2 mins
                                                </span>
                                            @elseif($category->time_category === '2_5min')
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    2 - 5 mins
                                                </span>
                                            @else
                                                <span
                                                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    More than 5 mins
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-3 text-gray-600">{{ number_format($category->count) }}</td>
                                        <td class="px-4 py-3 font-semibold text-gray-900">
                                            {{ number_format(Auth::user()->preferred_currency === 'USD' ? $category->total_earnings_usd : $category->total_earnings_inr, 2) }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-4 py-8 text-center text-gray-500">No engagement
                                            data recorded yet.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Scroll Depth -->
                <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">Avg. Scroll Depth</h3>
                    <div class="flex flex-col items-center justify-center h-48">
                        <div class="relative w-32 h-32">
                            <svg class="w-full h-full" viewBox="0 0 36 36">
                                <path d="M18 2.0845
                                      a 15.9155 15.9155 0 0 1 0 31.831
                                      a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#E5E7EB"
                                    stroke-width="3" />
                                <path d="M18 2.0845
                                      a 15.9155 15.9155 0 0 1 0 31.831
                                      a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="#8B5CF6"
                                    stroke-width="3" stroke-dasharray="{{ $post->scroll_depth_percentage }}, 100" />
                            </svg>
                            <div class="absolute inset-0 flex items-center justify-center flex-col">
                                <span
                                    class="text-3xl font-bold text-purple-600">{{ $post->scroll_depth_percentage }}%</span>
                                <span class="text-xs text-gray-500 uppercase">Read</span>
                            </div>
                        </div>
                        <p class="text-sm text-gray-500 mt-4 text-center">Average page scroll percentage across all
                            visitors.</p>
                    </div>
                </div>
            </div>

            <!-- Recent Visitors Table -->
            <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100">
                <div class="p-6 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900">Recent Visitors</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-semibold">
                            <tr>
                                <th class="px-6 py-4">Date</th>
                                <th class="px-6 py-4">IP Address</th>
                                <th class="px-6 py-4 text-center">Time Spent</th>
                                <th class="px-6 py-4 text-center">Scroll Depth</th>
                                <th class="px-6 py-4 text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse ($visitors as $visitor)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $visitor->visited_at->format('M d, Y H:i') }}
                                        <div class="text-xs text-gray-400">{{ $visitor->visited_at->diffForHumans() }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm font-mono text-gray-500">
                                        {{ Str::mask($visitor->ip_address, '*', -4) }}
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-900">
                                        {{ gmdate('H:i:s', $visitor->time_spent_seconds) }}
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex items-center justify-center">
                                            <div class="w-16 bg-gray-200 rounded-full h-1.5 mr-2">
                                                <div class="bg-blue-500 h-1.5 rounded-full"
                                                    style="width: {{ $visitor->scroll_depth_percentage }}%"></div>
                                            </div>
                                            <span
                                                class="text-xs text-gray-600">{{ $visitor->scroll_depth_percentage }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($visitor->is_suspicious)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                Suspicious
                                            </span>
                                        @elseif($visitor->is_bounce)
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                Bounce
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Valid
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                        <i class="fas fa-users text-4xl mb-3 text-gray-200"></i>
                                        <p>No recent visitors found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-6 py-4 border-t border-gray-100">
                    {{ $visitors->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
