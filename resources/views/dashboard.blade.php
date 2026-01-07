<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Welcome Header -->
        <div class="mb-8">
            <div
                class="bg-gradient-to-r from-purple-600 via-pink-600 to-red-600 rounded-3xl p-8 text-white relative overflow-hidden">
                <!-- Background Pattern -->
                <div class="absolute inset-0 bg-black/10"></div>
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16">
                </div>
                <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/5 rounded-full translate-y-12 -translate-x-12">
                </div>

                <div class="relative z-10">
                    <h1 class="text-4xl font-bold text-white mb-2">
                        Welcome to CoolPosts, {{ Auth::user()->name }}! 🎉
                    </h1>
                    <p class="text-purple-100 text-lg">
                        @if($globalSettings->isEarningsEnabled())
                            Ready to monetize more links and grow your earnings?
                        @else
                            Ready to create more links and grow your audience?
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mb-8">
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('links.create') }}"
                    class="bg-white/80 backdrop-blur-sm border border-white/20 text-gray-700 px-6 py-3 rounded-2xl hover:bg-white hover:shadow-lg transition-all duration-200 flex items-center shadow-sm">
                    <i class="fas fa-plus mr-3 text-purple-600"></i>
                    New Link
                </a>
                <a href="{{ route('links.index') }}"
                    class="bg-white/80 backdrop-blur-sm border border-white/20 text-gray-700 px-6 py-3 rounded-2xl hover:bg-white hover:shadow-lg transition-all duration-200 flex items-center shadow-sm">
                    <i class="fas fa-list mr-3 text-blue-600"></i>
                    View All Links
                </a>
                <a href="{{ route('blog.create') }}"
                    class="bg-white/80 backdrop-blur-sm border border-white/20 text-gray-700 px-6 py-3 rounded-2xl hover:bg-white hover:shadow-lg transition-all duration-200 flex items-center shadow-sm">
                    <i class="fas fa-edit mr-3 text-green-600"></i>
                    Create Blog Post
                </a>
                <a href="{{ route('blog.index') }}"
                    class="bg-white/80 backdrop-blur-sm border border-white/20 text-gray-700 px-6 py-3 rounded-2xl hover:bg-white hover:shadow-lg transition-all duration-200 flex items-center shadow-sm">
                    <i class="fas fa-newspaper mr-3 text-orange-600"></i>
                    View Blog
                </a>
                @if($globalSettings->isEarningsEnabled())
                    <a href="{{ route('withdrawals.index') }}"
                        class="bg-white/80 backdrop-blur-sm border border-white/20 text-gray-700 px-6 py-3 rounded-2xl hover:bg-white hover:shadow-lg transition-all duration-200 flex items-center shadow-sm">
                        <i class="fas fa-dollar-sign mr-3 text-yellow-600"></i>
                        Withdraw Earnings
                    </a>
                @endif
            </div>
        </div>

        <!-- Statistics Cards -->
        @php
        $gridCols = $globalSettings->isEarningsEnabled() ? 'lg:grid-cols-5' : 'lg:grid-cols-3';
        <div class="mt-4 md:mt-0 flex gap-3">
            <a href="{{ route('blog.create') }}" class="btn-realistic bg-zinc-900 text-white hover:bg-zinc-800">
                <i class="fas fa-pen-fancy mr-2 text-xs"></i> Write Post
            </a>
        </div>
    </div>
    </div>

    <!-- Compact Statistics Grid -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Total Posts -->
        <div
            class="group bg-white rounded-lg border border-zinc-200 shadow-sm hover:shadow-md hover:border-zinc-300 transition-all duration-200">
            <div class="p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-zinc-500 uppercase tracking-wide">Published Posts</p>
                        @php
                            $totalPosts = \App\Models\BlogPost::where('user_id', Auth::id())->count();
                        @endphp
                        <h3 class="text-2xl font-bold text-zinc-900 mt-1">{{ $totalPosts }}</h3>
                    </div>
                    <div
                        class="w-8 h-8 rounded-md bg-zinc-50 border border-zinc-100 flex items-center justify-center text-zinc-400 group-hover:text-purple-600 transition-colors">
                        <i class="fas fa-file-alt text-sm"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Views -->
        <div
            class="group bg-white rounded-lg border border-zinc-200 shadow-sm hover:shadow-md hover:border-zinc-300 transition-all duration-200">
            <div class="p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-zinc-500 uppercase tracking-wide">Total Views</p>
                        <h3 class="text-2xl font-bold text-zinc-900 mt-1">
                            {{ number_format($stats['total_clicks'] ?? 0) }}
                        </h3>
                    </div>
                    <div
                        class="w-8 h-8 rounded-md bg-zinc-50 border border-zinc-100 flex items-center justify-center text-zinc-400 group-hover:text-blue-600 transition-colors">
                        <i class="fas fa-eye text-sm"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Earnings (Coming Soon) -->
        <div class="group bg-zinc-50 rounded-lg border border-zinc-200 shadow-sm relative overflow-hidden opacity-90">
            <div class="absolute inset-x-0 bottom-0 bg-zinc-100 border-t border-zinc-200 py-1 text-center">
                <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest">Coming Soon</p>
            </div>
            <div class="p-4 pb-8">
                <div class="flex items-center justify-between opacity-50">
                    <div>
                        <p class="text-xs font-semibold text-zinc-500 uppercase tracking-wide">Earnings</p>
                        <h3 class="text-2xl font-bold text-zinc-900 mt-1">
                            {{ $stats['currency_symbol'] ?? '$' }}0.00
                        </h3>
                    </div>
                    <div
                        class="w-8 h-8 rounded-md bg-zinc-100 border border-zinc-200 flex items-center justify-center text-zinc-400">
                        <i class="fas fa-dollar-sign text-sm"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending (Coming Soon) -->
        <div class="group bg-zinc-50 rounded-lg border border-zinc-200 shadow-sm relative overflow-hidden opacity-90">
            <div class="absolute inset-x-0 bottom-0 bg-zinc-100 border-t border-zinc-200 py-1 text-center">
                <p class="text-[10px] font-bold text-zinc-500 uppercase tracking-widest">Coming Soon</p>
            </div>
            <div class="p-4 pb-8">
                <div class="flex items-center justify-between opacity-50">
                    <div>
                        <p class="text-xs font-semibold text-zinc-500 uppercase tracking-wide">Pending</p>
                        <h3 class="text-2xl font-bold text-zinc-900 mt-1">
                            {{ $stats['currency_symbol'] ?? '$' }}0.00
                        </h3>
                    </div>
                    <div
                        class="w-8 h-8 rounded-md bg-zinc-100 border border-zinc-200 flex items-center justify-center text-zinc-400">
                        <i class="fas fa-clock text-sm"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Quick Actions -->
        <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-lg border border-white/20 p-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Quick Actions</h3>
            <div class="space-y-4">
                <a href="{{ route('blog.create') }}"
                    class="flex items-center p-4 bg-gradient-to-r from-zinc-50 to-zinc-100 rounded-2xl border border-zinc-200 hover:from-zinc-100 hover:to-zinc-200 transition-all duration-200 group">
                    <div
                        class="w-12 h-12 bg-zinc-800 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-200">
                        <i class="fas fa-pen-fancy text-white text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">Write New Post</h4>
                        <p class="text-sm text-gray-600">Create and publish content</p>
                    </div>
                </a>

                <a href="{{ route('blog.index') }}"
                    class="flex items-center p-4 bg-gradient-to-r from-zinc-50 to-zinc-100 rounded-2xl border border-zinc-200 hover:from-zinc-100 hover:to-zinc-200 transition-all duration-200 group">
                    <div
                        class="w-12 h-12 bg-white border border-zinc-200 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-200">
                        <i class="fas fa-newspaper text-zinc-600 text-lg"></i>
                    </div>
                    <div>
                        <h4 class="font-semibold text-gray-900">View Blog</h4>
                        <p class="text-sm text-gray-600">Browse all blog posts</p>
                    </div>
                </a>

                @if($globalSettings->isLinkCreationEnabled())
                    <a href="{{ route('links.create') }}"
                        class="flex items-center p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-2xl border border-purple-200 hover:from-purple-100 hover:to-purple-200 transition-all duration-200 group">
                        <div
                            class="w-12 h-12 bg-purple-500 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform duration-200">
                            <i class="fas fa-plus text-white text-lg"></i>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Create New Link</h4>
                            <p class="text-sm text-gray-600">Shorten a URL</p>
                        </div>
                    </a>
                @endif
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white/80 backdrop-blur-sm rounded-3xl shadow-lg border border-white/20 p-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-6">Recent Activity</h3>
            <div class="space-y-4">
                <div class="flex items-center p-4 bg-gray-50 rounded-2xl">
                    <div class="w-10 h-10 bg-zinc-500 rounded-full flex items-center justify-center mr-4">
                        <i class="fas fa-info text-white text-sm"></i>
                    </div>
                    <div class="flex-1">
                        <p class="font-medium text-gray-900">System Update</p>
                        <p class="text-sm text-gray-600">Dashboard updated to Blogger-First mode.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</x-app-layout>