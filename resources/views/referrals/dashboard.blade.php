<x-app-layout>
    <x-slot name="title">Referrals Dashboard</x-slot>
    
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Referrals Dashboard</h1>
                <p class="mt-2 text-gray-600">Earn money by referring others to our platform</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-md flex items-center justify-center">
                                    <i class="fas fa-users text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Referrals</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_referrals'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                    <i class="fas fa-check-circle text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Active Referrals</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['active_referrals'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                    <i class="fas fa-rupee-sign text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Earnings (INR)</p>
                                <p class="text-2xl font-semibold text-gray-900">₹{{ number_format($stats['total_earnings_inr'], 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-indigo-500 rounded-md flex items-center justify-center">
                                    <i class="fas fa-dollar-sign text-white"></i>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Earnings (USD)</p>
                                <p class="text-2xl font-semibold text-gray-900">${{ number_format($stats['total_earnings_usd'], 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Referral Code Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200 mb-8">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Your Referral Code</h2>
                    <div class="flex items-center space-x-4">
                        <div class="flex-1">
                            <input type="text" 
                                   value="{{ $stats['referral_code'] }}" 
                                   readonly 
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-50 font-mono text-lg">
                        </div>
                        <button onclick="copyReferralCode()" 
                                class="px-4 py-2 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
                            <i class="fas fa-copy mr-2"></i>Copy
                        </button>
                        <button onclick="shareReferralCode()" 
                                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors">
                            <i class="fas fa-share mr-2"></i>Share
                        </button>
                    </div>
                    <p class="mt-2 text-sm text-gray-600">
                        Share this code with friends to earn {{ $globalSettings->getReferralSignupBonus('INR') }}₹ when they sign up!
                    </p>
                </div>
            </div>

            <!-- Referrals List -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <div class="p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Your Referrals</h2>
                    
                    @if($referrals->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Earnings</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($referrals as $referral)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                                        <i class="fas fa-user text-gray-600"></i>
                                                    </div>
                                                    <div class="ml-3">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $referral->referred->name ?? 'Unknown User' }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $referral->referred->email ?? 'No email' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if($referral->status === 'completed') bg-green-100 text-green-800
                                                    @elseif($referral->status === 'pending') bg-yellow-100 text-yellow-800
                                                    @else bg-red-100 text-red-800
                                                    @endif">
                                                    {{ ucfirst($referral->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $referral->created_at->format('M d, Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($referral->status === 'completed')
                                                    ₹{{ number_format($globalSettings->getReferralSignupBonus('INR'), 2) }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="fas fa-users text-gray-400 text-4xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No referrals yet</h3>
                            <p class="text-gray-600 mb-4">Start sharing your referral code to earn money!</p>
                            <button onclick="shareReferralCode()" 
                                    class="px-6 py-3 bg-purple-600 text-white rounded-md hover:bg-purple-700 transition-colors">
                                <i class="fas fa-share mr-2"></i>Share Referral Code
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function copyReferralCode() {
            const referralCode = '{{ $stats["referral_code"] }}';
            navigator.clipboard.writeText(referralCode).then(function() {
                // Show success message
                const button = event.target.closest('button');
                const originalText = button.innerHTML;
                button.innerHTML = '<i class="fas fa-check mr-2"></i>Copied!';
                button.classList.add('bg-green-600', 'hover:bg-green-700');
                button.classList.remove('bg-purple-600', 'hover:bg-purple-700');
                
                setTimeout(() => {
                    button.innerHTML = originalText;
                    button.classList.remove('bg-green-600', 'hover:bg-green-700');
                    button.classList.add('bg-purple-600', 'hover:bg-purple-700');
                }, 2000);
            });
        }

        function shareReferralCode() {
            const referralCode = '{{ $stats["referral_code"] }}';
            const referralUrl = `{{ url('/register') }}?ref=${referralCode}`;
            const shareText = `Join me on {{ $brandingSettings->brand_name ?? 'CoolPosts' }}! Use my referral code: ${referralCode}\n\nSign up here: ${referralUrl}`;
            
            if (navigator.share) {
                navigator.share({
                    title: 'Join {{ $brandingSettings->brand_name ?? 'CoolPosts' }}',
                    text: shareText,
                    url: referralUrl
                });
            } else {
                // Fallback: copy to clipboard
                navigator.clipboard.writeText(shareText).then(function() {
                    alert('Referral link copied to clipboard!');
                });
            }
        }
    </script>
</x-app-layout>