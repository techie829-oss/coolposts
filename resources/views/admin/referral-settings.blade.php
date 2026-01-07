<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Referral Settings') }}
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Back to Dashboard
                </a>
                <a href="{{ route('global-settings') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-cog mr-2"></i>Global Settings
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.referral-settings.update') }}" class="space-y-8">
                @csrf

                <!-- Referral System Enable/Disable -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Referral System Control</h3>

                        <div class="flex items-center">
                            <input type="checkbox" id="referrals_enabled" name="referrals_enabled" value="1"
                                   {{ $globalSettings->referrals_enabled ? 'checked' : '' }}
                                   class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            <label for="referrals_enabled" class="ml-2 block text-sm text-gray-900">
                                Enable Referral System
                            </label>
                        </div>
                        <p class="mt-1 text-sm text-gray-500">Allow users to refer others and earn commissions</p>
                    </div>
                </div>

                <!-- Signup Bonuses -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Signup Bonuses</h3>
                        <p class="text-sm text-gray-500 mb-4">Immediate bonus paid to referrer when someone signs up using their code</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="referral_signup_bonus_inr" class="block text-sm font-medium text-gray-700">Signup Bonus (INR)</label>
                                <input type="number" step="0.01" min="0" id="referral_signup_bonus_inr" name="referral_signup_bonus_inr"
                                       value="{{ $globalSettings->referral_signup_bonus_inr }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="referral_signup_bonus_usd" class="block text-sm font-medium text-gray-700">Signup Bonus (USD)</label>
                                <input type="number" step="0.001" min="0" id="referral_signup_bonus_usd" name="referral_signup_bonus_usd"
                                       value="{{ $globalSettings->referral_signup_bonus_usd }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Commission Settings -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Commission Settings</h3>

                        <!-- Commission Type -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Commission Type</label>
                            <div class="flex space-x-4">
                                <label class="flex items-center">
                                    <input type="radio" name="referral_commission_type" value="percentage"
                                           {{ $globalSettings->referral_commission_type === 'percentage' ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700">Percentage of Referred User's Earnings</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="referral_commission_type" value="flat"
                                           {{ $globalSettings->referral_commission_type === 'flat' ? 'checked' : '' }}
                                           class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300">
                                    <span class="ml-2 text-sm text-gray-700">Flat Rate per Earning</span>
                                </label>
                            </div>
                        </div>

                        <!-- Percentage Commission -->
                        <div id="percentage-commission" class="mb-6">
                            <label for="referral_commission_rate" class="block text-sm font-medium text-gray-700">Commission Rate (%)</label>
                            <input type="number" step="0.01" min="0" max="100" id="referral_commission_rate" name="referral_commission_rate"
                                   value="{{ $globalSettings->referral_commission_rate }}"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            <p class="mt-1 text-sm text-gray-500">Percentage of referred user's earnings (e.g., 10% = ₹1 for every ₹10 earned)</p>
                        </div>

                        <!-- Flat Commission -->
                        <div id="flat-commission" class="mb-6" style="display: none;">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="referral_commission_flat_inr" class="block text-sm font-medium text-gray-700">Flat Commission (INR)</label>
                                    <input type="number" step="0.01" min="0" id="referral_commission_flat_inr" name="referral_commission_flat_inr"
                                           value="{{ $globalSettings->referral_commission_flat_inr }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label for="referral_commission_flat_usd" class="block text-sm font-medium text-gray-700">Flat Commission (USD)</label>
                                    <input type="number" step="0.001" min="0" id="referral_commission_flat_usd" name="referral_commission_flat_usd"
                                           value="{{ $globalSettings->referral_commission_flat_usd }}"
                                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Fixed amount earned for each earning by referred user</p>
                        </div>

                        <!-- Commission Rules -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="referral_minimum_earnings" class="block text-sm font-medium text-gray-700">Minimum Earnings Threshold</label>
                                <input type="number" step="0.01" min="0" id="referral_minimum_earnings" name="referral_minimum_earnings"
                                       value="{{ $globalSettings->referral_minimum_earnings }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <p class="mt-1 text-sm text-gray-500">Minimum earnings before commission kicks in</p>
                            </div>
                            <div>
                                <label for="referral_commission_duration" class="block text-sm font-medium text-gray-700">Commission Duration (Days)</label>
                                <input type="number" min="1" max="365" id="referral_commission_duration" name="referral_commission_duration"
                                       value="{{ $globalSettings->referral_commission_duration }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <p class="mt-1 text-sm text-gray-500">How long to earn commission from referrals</p>
                            </div>
                            <div>
                                <label for="referral_max_referrals_per_user" class="block text-sm font-medium text-gray-700">Max Referrals per User</label>
                                <input type="number" min="1" max="1000" id="referral_max_referrals_per_user" name="referral_max_referrals_per_user"
                                       value="{{ $globalSettings->referral_max_referrals_per_user }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <p class="mt-1 text-sm text-gray-500">Maximum referrals allowed per user</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Premium Upgrade Bonuses -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Premium Upgrade Bonuses</h3>
                        <p class="text-sm text-gray-500 mb-4">Bonus paid when referred user upgrades to premium</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="referral_premium_upgrade_bonus_inr" class="block text-sm font-medium text-gray-700">Premium Upgrade Bonus (INR)</label>
                                <input type="number" step="0.01" min="0" id="referral_premium_upgrade_bonus_inr" name="referral_premium_upgrade_bonus_inr"
                                       value="{{ $globalSettings->referral_premium_upgrade_bonus_inr }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label for="referral_premium_upgrade_bonus_usd" class="block text-sm font-medium text-gray-700">Premium Upgrade Bonus (USD)</label>
                                <input type="number" step="0.001" min="0" id="referral_premium_upgrade_bonus_usd" name="referral_premium_upgrade_bonus_usd"
                                       value="{{ $globalSettings->referral_premium_upgrade_bonus_usd }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Multi-Tier Referral System -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Multi-Tier Referral System</h3>
                        <p class="text-sm text-gray-500 mb-4">Enable additional commission tiers for referrals of referrals</p>

                        <!-- Tier 2 -->
                        <div class="mb-6 p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center mb-3">
                                <input type="checkbox" id="referral_tier_2_enabled" name="referral_tier_2_enabled" value="1"
                                       {{ $globalSettings->referral_tier_2_enabled ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="referral_tier_2_enabled" class="ml-2 block text-sm font-medium text-gray-900">
                                    Enable Tier 2 Referrals (Referrals of Referrals)
                                </label>
                            </div>
                            <div class="ml-6">
                                <label for="referral_tier_2_rate" class="block text-sm font-medium text-gray-700">Tier 2 Commission Rate (%)</label>
                                <input type="number" step="0.01" min="0" max="100" id="referral_tier_2_rate" name="referral_tier_2_rate"
                                       value="{{ $globalSettings->referral_tier_2_rate }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <p class="mt-1 text-sm text-gray-500">Commission rate for referrals made by your referrals</p>
                            </div>
                        </div>

                        <!-- Tier 3 -->
                        <div class="p-4 border border-gray-200 rounded-lg">
                            <div class="flex items-center mb-3">
                                <input type="checkbox" id="referral_tier_3_enabled" name="referral_tier_3_enabled" value="1"
                                       {{ $globalSettings->referral_tier_3_enabled ? 'checked' : '' }}
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                                <label for="referral_tier_3_enabled" class="ml-2 block text-sm font-medium text-gray-900">
                                    Enable Tier 3 Referrals (3rd Level Referrals)
                                </label>
                            </div>
                            <div class="ml-6">
                                <label for="referral_tier_3_rate" class="block text-sm font-medium text-gray-700">Tier 3 Commission Rate (%)</label>
                                <input type="number" step="0.01" min="0" max="100" id="referral_tier_3_rate" name="referral_tier_3_rate"
                                       value="{{ $globalSettings->referral_tier_3_rate }}"
                                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                                <p class="mt-1 text-sm text-gray-500">Commission rate for 3rd level referrals</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-md text-sm font-medium">
                        <i class="fas fa-save mr-2"></i>Save Referral Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const commissionTypeRadios = document.querySelectorAll('input[name="referral_commission_type"]');
            const percentageDiv = document.getElementById('percentage-commission');
            const flatDiv = document.getElementById('flat-commission');

            function toggleCommissionType() {
                const selectedType = document.querySelector('input[name="referral_commission_type"]:checked').value;

                if (selectedType === 'percentage') {
                    percentageDiv.style.display = 'block';
                    flatDiv.style.display = 'none';
                } else {
                    percentageDiv.style.display = 'none';
                    flatDiv.style.display = 'block';
                }
            }

            commissionTypeRadios.forEach(radio => {
                radio.addEventListener('change', toggleCommissionType);
            });

            // Initial state
            toggleCommissionType();
        });
    </script>
</x-app-layout>
