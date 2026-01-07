<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Business Settings') }}
            </h2>
            <div class="flex gap-3">
                <a href="{{ route('branding-settings') }}" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-palette mr-2"></i>Branding
                </a>
                <a href="{{ route('ai-settings') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-robot mr-2"></i>AI Settings
                </a>
                <a href="{{ route('admin.dashboard') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Page Description -->
            <div class="mb-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-blue-800">Business Settings</h3>
                        <p class="text-sm text-blue-700 mt-1">
                            Configure monetization rates, subscription pricing, withdrawal settings, and business-related configurations.
                            For technical settings like security and notifications, use <a href="{{ route('admin.settings') }}" class="underline">Technical Settings</a>.
                        </p>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('global-settings.update') }}" class="space-y-8">
                @csrf

                <!-- 3-Tier Monetization Rates -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-coins mr-2 text-yellow-600"></i>3-Tier Monetization Rates
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Set global earning rates for different ad types</p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- No Ads -->
                            <div class="border rounded-lg p-4 bg-gray-50">
                                <h4 class="font-medium text-gray-900 mb-3">🚫 No Ads (Free)</h4>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">INR Rate</label>
                                        <input type="number" step="0.01" min="0" max="1000" name="no_ads_rate_inr"
                                               value="{{ $settings->no_ads_rate_inr }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">USD Rate</label>
                                        <input type="number" step="0.0001" min="0" max="100" name="no_ads_rate_usd"
                                               value="{{ $settings->no_ads_rate_usd }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                    </div>
                                </div>
                            </div>

                            <!-- Short Ads -->
                            <div class="border rounded-lg p-4 bg-blue-50">
                                <h4 class="font-medium text-gray-900 mb-3">⏱️ Short Ads (10-30s)</h4>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">INR Rate</label>
                                        <input type="number" step="0.01" min="0" max="1000" name="short_ads_rate_inr"
                                               value="{{ $settings->short_ads_rate_inr }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">USD Rate</label>
                                        <input type="number" step="0.0001" min="0" max="100" name="short_ads_rate_usd"
                                               value="{{ $settings->short_ads_rate_usd }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                    </div>
                                </div>
                            </div>

                            <!-- Long Ads -->
                            <div class="border rounded-lg p-4 bg-purple-50">
                                <h4 class="font-medium text-gray-900 mb-3">⏰ Long Ads (30s-1min)</h4>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">INR Rate</label>
                                        <input type="number" step="0.01" min="0" max="1000" name="long_ads_rate_inr"
                                               value="{{ $settings->long_ads_rate_inr }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">USD Rate</label>
                                        <input type="number" step="0.0001" min="0" max="100" name="long_ads_rate_usd"
                                               value="{{ $settings->long_ads_rate_usd }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Blog Monetization Settings -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-newspaper mr-2 text-indigo-600"></i>Blog Monetization Settings
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Configure default monetization settings for blog posts</p>
                    </div>
                    <div class="p-4 bg-indigo-50 border-l-4 border-indigo-400">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-indigo-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-indigo-700">
                                    <strong>Blog Monetization:</strong> These settings will be applied as defaults when users create new blog posts.
                                    Users can still override these settings for individual posts if needed.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Default Monetization Type</label>
                                <select name="default_blog_monetization_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option value="time_based" {{ $settings->default_blog_monetization_type == 'time_based' ? 'selected' : '' }}>Time-based Earnings</option>
                                    <option value="ad_based" {{ $settings->default_blog_monetization_type == 'ad_based' ? 'selected' : '' }}>Ad-based Earnings</option>
                                    <option value="both" {{ $settings->default_blog_monetization_type == 'both' ? 'selected' : '' }}>Both Time and Ad-based</option>
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Default monetization method for new blog posts</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Default Ad Type</label>
                                <select name="default_blog_ad_type" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                    <option value="no_ads" {{ $settings->default_blog_ad_type == 'no_ads' ? 'selected' : '' }}>No Ads</option>
                                    <option value="banner_ads" {{ $settings->default_blog_ad_type == 'banner_ads' ? 'selected' : '' }}>Banner Ads</option>
                                    <option value="interstitial_ads" {{ $settings->default_blog_ad_type == 'interstitial_ads' ? 'selected' : '' }}>Interstitial Ads</option>
                                    <option value="both" {{ $settings->default_blog_ad_type == 'both' ? 'selected' : '' }}>Both Banner and Interstitial</option>
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Default ad format for new blog posts</p>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="font-medium text-gray-900 mb-3">Default Earning Rates for Blog Posts</h4>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Less than 2 min (INR)</label>
                                    <input type="number" step="0.01" min="0" max="100" name="default_blog_earning_rate_less_2min_inr"
                                           value="{{ $settings->default_blog_earning_rate_less_2min_inr ?? 0.10 }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">2-5 min (INR)</label>
                                    <input type="number" step="0.01" min="0" max="100" name="default_blog_earning_rate_2_5min_inr"
                                           value="{{ $settings->default_blog_earning_rate_2_5min_inr ?? 0.25 }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">More than 5 min (INR)</label>
                                    <input type="number" step="0.01" min="0" max="100" name="default_blog_earning_rate_more_5min_inr"
                                           value="{{ $settings->default_blog_earning_rate_more_5min_inr ?? 0.50 }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                </div>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Less than 2 min (USD)</label>
                                    <input type="number" step="0.001" min="0" max="10" name="default_blog_earning_rate_less_2min_usd"
                                           value="{{ $settings->default_blog_earning_rate_less_2min_usd ?? 0.001 }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">2-5 min (USD)</label>
                                    <input type="number" step="0.001" min="0" max="10" name="default_blog_earning_rate_2_5min_usd"
                                           value="{{ $settings->default_blog_earning_rate_2_5min_usd ?? 0.003 }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">More than 5 min (USD)</label>
                                    <input type="number" step="0.001" min="0" max="10" name="default_blog_earning_rate_more_5min_usd"
                                           value="{{ $settings->default_blog_earning_rate_more_5min_usd ?? 0.006 }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ad Duration Settings -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-clock mr-2 text-blue-600"></i>Ad Duration Settings
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Configure time ranges for each ad type</p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-medium text-gray-900 mb-3">Short Ads Duration</h4>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Min (seconds)</label>
                                        <input type="number" min="5" max="60" name="short_ads_min_duration"
                                               value="{{ $settings->short_ads_min_duration }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Max (seconds)</label>
                                        <input type="number" min="10" max="60" name="short_ads_max_duration"
                                               value="{{ $settings->short_ads_max_duration }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 mb-3">Long Ads Duration</h4>
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Min (seconds)</label>
                                        <input type="number" min="30" max="120" name="long_ads_min_duration"
                                               value="{{ $settings->long_ads_min_duration }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">Max (seconds)</label>
                                        <input type="number" min="30" max="300" name="long_ads_max_duration"
                                               value="{{ $settings->long_ads_max_duration }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Premium Subscription Settings -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-crown mr-2 text-yellow-600"></i>Premium Subscription Settings
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Set pricing for premium subscriptions</p>
                    </div>
                    <div class="p-4 bg-yellow-50 border-l-4 border-yellow-400">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    <strong>Premium Multiplier System:</strong> Premium users get higher earnings through multipliers.
                                    A multiplier of 1.5x means they earn 50% more, 2.0x means they earn 100% more, etc.
                                    Regular users always get the base rate.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-medium text-gray-900 mb-3">Monthly Plans</h4>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">INR Price</label>
                                        <input type="number" step="0.01" min="0" max="10000" name="premium_monthly_price_inr"
                                               value="{{ $settings->premium_monthly_price_inr }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">USD Price</label>
                                        <input type="number" step="0.01" min="0" max="100" name="premium_monthly_price_usd"
                                               value="{{ $settings->premium_monthly_price_usd }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                    </div>
                                </div>
                            </div>
                            <div>
                                <h4 class="font-medium text-gray-900 mb-3">Yearly Plans</h4>
                                <div class="space-y-3">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">INR Price</label>
                                        <input type="number" step="0.01" min="0" max="100000" name="premium_yearly_price_inr"
                                               value="{{ $settings->premium_yearly_price_inr }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">USD Price</label>
                                        <input type="number" step="0.01" min="0" max="1000" name="premium_yearly_price_usd"
                                               value="{{ $settings->premium_yearly_price_usd }}"
                                               class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Premium Multipliers -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="font-medium text-gray-900 mb-3">Premium Earning Multipliers</h4>
                            <p class="text-sm text-gray-600 mb-4">Set earning multipliers for premium users. Values must be greater than 1.0 to provide benefits.</p>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Short Ads Multiplier</label>
                                    <input type="number" step="0.1" min="1.1" max="10" name="premium_short_ads_multiplier"
                                           value="{{ $settings->premium_short_ads_multiplier }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                    <p class="text-xs text-gray-500 mt-1">Premium users get this multiplier for short ads (must be > 1.0)</p>
                                    <p class="text-xs text-blue-600 mt-1">Example: 1.5x means premium users earn 50% more on short ads</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Long Ads Multiplier</label>
                                    <input type="number" step="0.1" min="1.1" max="10" name="premium_long_ads_multiplier"
                                           value="{{ $settings->premium_long_ads_multiplier }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md">
                                    <p class="text-xs text-gray-500 mt-1">Premium users get this multiplier for long ads (must be > 1.0)</p>
                                    <p class="text-xs text-blue-600 mt-1">Example: 2.0x means premium users earn 100% more on long ads</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Withdrawal Settings -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-money-bill-wave mr-2 text-green-600"></i>Withdrawal Settings
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Configure minimum withdrawal amounts and fees</p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Min Withdrawal (INR)</label>
                                <input type="number" step="0.01" min="0" max="10000" name="min_withdrawal_inr"
                                       value="{{ $settings->min_withdrawal_inr }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Min Withdrawal (USD)</label>
                                <input type="number" step="0.01" min="0" max="100" name="min_withdrawal_usd"
                                       value="{{ $settings->min_withdrawal_usd }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Withdrawal Fee (%)</label>
                                <input type="number" step="0.01" min="0" max="10" name="withdrawal_fee_percentage"
                                       value="{{ $settings->withdrawal_fee_percentage }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Feature Toggles -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-toggle-on mr-2 text-green-600"></i>Feature Toggles
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Enable or disable major features on your platform</p>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium text-gray-900">Earnings Feature</h4>
                                    <p class="text-sm text-gray-600">Allow users to track and withdraw earnings</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="earnings_enabled" value="0">
                                    <input type="checkbox" name="earnings_enabled" value="1"
                                           {{ $settings->earnings_enabled ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium text-gray-900">Monetization Feature</h4>
                                    <p class="text-sm text-gray-600">Enable ad-based monetization for links</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="monetization_enabled" value="0">
                                    <input type="checkbox" name="monetization_enabled" value="1"
                                           {{ $settings->monetization_enabled ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium text-gray-900">Ads Feature</h4>
                                    <p class="text-sm text-gray-600">Display advertisements to users</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="hidden" name="ads_enabled" value="0">
                                    <input type="checkbox" name="ads_enabled" value="1"
                                           {{ $settings->ads_enabled ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- System Settings -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-cog mr-2 text-gray-600"></i>System Settings
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Control platform features and maintenance</p>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium text-gray-900">Maintenance Mode</h4>
                                    <p class="text-sm text-gray-600">Temporarily disable the platform</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="maintenance_mode" value="1"
                                           {{ $settings->maintenance_mode ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium text-gray-900">New Registrations</h4>
                                    <p class="text-sm text-gray-600">Allow new users to register</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="new_registrations" value="1"
                                           {{ $settings->new_registrations ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>

                            <div class="flex items-center justify-between">
                                <div>
                                    <h4 class="font-medium text-gray-900">Link Creation</h4>
                                    <p class="text-sm text-gray-600">Allow users to create new links</p>
                                </div>
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="link_creation_enabled" value="1"
                                           {{ $settings->link_creation_enabled ? 'checked' : '' }}
                                           class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                                </label>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Maintenance Message</label>
                                <textarea name="maintenance_message" rows="3"
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                          placeholder="Message to display when maintenance mode is active">{{ $settings->maintenance_message }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-md font-medium">
                        <i class="fas fa-save mr-2"></i>Save All Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
