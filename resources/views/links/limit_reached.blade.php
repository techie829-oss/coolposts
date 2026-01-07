<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Daily Limit Reached') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <!-- Header Section -->
                <div
                    class="relative overflow-hidden bg-gradient-to-r from-rose-600 via-red-500 to-orange-500 text-white p-10 text-center">
                    <div class="absolute inset-0 opacity-20">
                        <svg class="h-full w-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                            <path d="M0 0 L100 100 M100 0 L0 100" stroke="white" stroke-width="2" />
                        </svg>
                    </div>
                    <div class="relative z-10">
                        <div
                            class="mb-4 inline-block bg-white/20 backdrop-blur-md rounded-full p-4 shadow-lg ring-1 ring-white/30">
                            <i class="fas fa-exclamation-triangle text-3xl text-white"></i>
                        </div>
                        <h2 class="text-3xl font-bold mb-2 drop-shadow-md">Daily Limit Reached</h2>
                        <p class="text-white/90 font-medium">
                            <i class="fas fa-clock mr-2"></i>
                            This link has reached its daily click limit
                        </p>
                    </div>
                </div>

                <!-- Content Section -->
                <div class="p-8 text-center">
                    <h3 class="text-2xl text-gray-700 mb-6">{{ $link->title }}</h4>

                        <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-6 mb-6 text-left">
                            <h6 class="text-lg font-semibold text-gray-800 mb-3">
                                <i class="fas fa-info-circle mr-2 text-red-500"></i>
                                Why is this happening?
                            </h6>
                            <p class="text-gray-700">
                                To maintain quality and prevent abuse, this link has a daily limit of
                                <strong>{{ $link->daily_click_limit ?? 'unlimited' }}</strong> clicks.
                                The limit resets every day at midnight UTC.
                            </p>
                        </div>

                        <!-- Statistics -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                            <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
                                <div class="text-2xl font-bold text-blue-600">{{ $link->clicks()->today()->count() }}
                                </div>
                                <div class="text-sm text-gray-600">Today's Clicks</div>
                            </div>
                            <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
                                <div class="text-2xl font-bold text-purple-600">{{ $link->daily_click_limit ?? '∞' }}
                                </div>
                                <div class="text-sm text-gray-600">Daily Limit</div>
                            </div>
                            <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
                                <div class="text-2xl font-bold text-green-600">{{ $link->total_clicks }}</div>
                                <div class="text-sm text-gray-600">Total Clicks</div>
                            </div>
                            <div class="bg-white border border-gray-200 rounded-lg p-4 text-center">
                                <div class="text-2xl font-bold text-yellow-600">
                                    ${{ number_format($link->total_earnings, 4) }}</div>
                                <div class="text-sm text-gray-600">Total Earnings</div>
                            </div>
                        </div>

                        <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-6 mb-6 text-left">
                            <h6 class="text-lg font-semibold text-gray-800 mb-3">
                                <i class="fas fa-lightbulb mr-2 text-blue-500"></i>
                                What can you do?
                            </h6>
                            <ul class="space-y-2 text-gray-700">
                                <li class="flex items-center">
                                    <i class="fas fa-arrow-right text-blue-500 mr-2"></i>
                                    Try again tomorrow when the limit resets
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-arrow-right text-blue-500 mr-2"></i>
                                    Share the link with others to increase reach
                                </li>
                                <li class="flex items-center">
                                    <i class="fas fa-arrow-right text-blue-500 mr-2"></i>
                                    Contact the link owner for premium access
                                </li>
                            </ul>
                        </div>

                        <div class="mt-8">
                            <a href="/"
                                class="bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-700 hover:to-purple-700 text-white font-bold py-3 px-6 rounded-full transition-all duration-300 transform hover:scale-105 inline-flex items-center">
                                <i class="fas fa-home mr-2"></i>
                                Back to Home
                            </a>
                        </div>

                        <div class="mt-6">
                            <small class="text-gray-500">
                                <i class="fas fa-shield-alt mr-1"></i>
                                This helps maintain platform quality and prevent abuse
                            </small>
                        </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>