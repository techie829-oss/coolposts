<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Link') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-plus mr-2 text-blue-600"></i>Create New Link
                    </h3>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('links.store') }}">
                        @csrf

                        <div class="mb-6">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Link Title</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror"
                                   id="title" name="title" value="{{ old('title') }}" required autofocus>
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Give your link a descriptive name</p>
                        </div>

                        <div class="mb-6">
                            <label for="original_url" class="block text-sm font-medium text-gray-700 mb-2">Original URL</label>
                            <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('original_url') border-red-500 @enderror"
                                   id="original_url" name="original_url" value="{{ old('original_url') }}"
                                   placeholder="https://example.com" required>
                            @error('original_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">The full URL you want to shorten</p>
                        </div>

                        <div class="mb-6">
                            <div class="flex items-center">
                                <input class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                       type="checkbox" id="is_protected" name="is_protected" value="1"
                                       {{ old('is_protected') ? 'checked' : '' }}>
                                <label class="ml-2 block text-sm text-gray-900" for="is_protected">
                                    <i class="fas fa-lock mr-1 text-blue-600"></i>Protect this link with a password
                                </label>
                            </div>
                        </div>

                        <div class="mb-6" id="password-field" style="display: none;">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <input type="password" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror"
                                   id="password" name="password" value="{{ old('password') }}">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Minimum 4 characters</p>
                        </div>

                        <div class="mb-6">
                            <label for="ad_type" class="block text-sm font-medium text-gray-700 mb-2">
                                Ad Type & Earning Rate <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full">Coming Soon</span>
                            </label>
                            <select id="ad_type" name="ad_type" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('ad_type') border-red-500 @enderror" required>
                                <option value="">Select Ad Type</option>
                                <option value="no_ads" {{ old('ad_type') === 'no_ads' ? 'selected' : '' }}>
                                    🚫 No Ads - Free (₹0.00 / $0.00 per click)
                                </option>
                                <option value="short_ads" {{ old('ad_type') === 'short_ads' ? 'selected' : '' }}>
                                    ⏱️ Short Ads - 10-30 seconds (₹0.50 / $0.006 per click) <span class="text-yellow-600">[Coming Soon]</span>
                                </option>
                                <option value="long_ads" {{ old('ad_type') === 'long_ads' ? 'selected' : '' }}>
                                    ⏰ Long Ads - 30-60 seconds (₹1.00 / $0.012 per click) <span class="text-yellow-600">[Coming Soon]</span>
                                </option>
                            </select>
                            @error('ad_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-500">
                                <span class="font-semibold text-yellow-600">Earnings feature coming soon!</span> Choose the ad type for your link. Premium users will get higher earning rates when the feature launches.
                            </p>
                        </div>

                        <div class="mb-6" id="custom-duration-field" style="display: none;">
                            <label for="ad_duration" class="block text-sm font-medium text-gray-700 mb-2">
                                Custom Ad Duration (Optional)
                            </label>
                            <input type="number" min="5" max="300" step="5"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('ad_duration') border-red-500 @enderror"
                                   id="ad_duration" name="ad_duration" value="{{ old('ad_duration') }}" placeholder="Leave empty to use default duration">
                            @error('ad_duration')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-500">
                                Custom duration in seconds (5-300). Leave empty to use global default.
                            </p>
                        </div>

                        <div class="mb-6">
                            <div class="flex items-center">
                                <input class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                       type="checkbox" id="is_monetized" name="is_monetized" value="1"
                                       {{ old('is_monetized', '1') ? 'checked' : '' }}>
                                <label class="ml-2 block text-sm text-gray-900" for="is_monetized">
                                    <i class="fas fa-dollar-sign mr-1 text-green-600"></i>Enable monetization for this link
                                </label>
                            </div>
                        </div>

                        <div class="mb-6">
                            <label for="daily_click_limit" class="block text-sm font-medium text-gray-700 mb-2">Daily Click Limit (Optional)</label>
                            <input type="number" min="1" max="1000"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('daily_click_limit') border-red-500 @enderror"
                                   id="daily_click_limit" name="daily_click_limit"
                                   value="{{ old('daily_click_limit') }}" placeholder="Leave empty for unlimited">
                            @error('daily_click_limit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Maximum clicks per day (1-1000, leave empty for unlimited)</p>
                        </div>

                        <div class="mb-6">
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category (Optional)</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('category') border-red-500 @enderror"
                                   id="category" name="category" value="{{ old('category') }}" placeholder="e.g., Technology, Entertainment">
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Categorize your link for better organization</p>
                        </div>

                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                                      id="description" name="description" rows="3" placeholder="Brief description of your link">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Optional description for your link</p>
                        </div>

                        <div class="flex gap-3">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition-colors duration-200 inline-flex items-center">
                                <i class="fas fa-save mr-2"></i>Create Link
                            </button>
                            <a href="{{ route('links.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-md transition-colors duration-200 inline-flex items-center">
                                <i class="fas fa-arrow-left mr-2"></i>Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.getElementById('is_protected').addEventListener('change', function() {
        const passwordField = document.getElementById('password-field');
        const passwordInput = document.getElementById('password');

        if (this.checked) {
            passwordField.style.display = 'block';
            passwordInput.required = true;
        } else {
            passwordField.style.display = 'none';
            passwordInput.required = false;
            passwordInput.value = '';
        }
    });

    document.getElementById('ad_type').addEventListener('change', function() {
        const customDurationField = document.getElementById('custom-duration-field');
        const adDurationInput = document.getElementById('ad_duration');

        if (this.value === 'short_ads' || this.value === 'long_ads') {
            customDurationField.style.display = 'block';
            adDurationInput.required = false; // Optional field
        } else {
            customDurationField.style.display = 'none';
            adDurationInput.required = false;
            adDurationInput.value = '';
        }
    });

    // Show password field if it was checked on form submission error
    if (document.getElementById('is_protected').checked) {
        document.getElementById('password-field').style.display = 'block';
        document.getElementById('password').required = true;
    }
    </script>
</x-app-layout>
