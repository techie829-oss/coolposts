<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Link') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">
                        <i class="fas fa-edit mr-2 text-blue-600"></i>Edit Link
                    </h3>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('links.update', $link) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-6">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Link Title</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror"
                                   id="title" name="title" value="{{ old('title', $link->title) }}" required autofocus>
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="original_url" class="block text-sm font-medium text-gray-700 mb-2">Original URL</label>
                            <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('original_url') border-red-500 @enderror"
                                   id="original_url" name="original_url" value="{{ old('original_url', $link->original_url) }}"
                                   required>
                            @error('original_url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Short URL</label>
                            <div class="flex">
                                <input type="text" class="flex-1 px-3 py-2 border border-gray-300 rounded-l-md bg-gray-50 text-gray-500"
                                       value="{{ $link->short_url }}" readonly>
                                <button class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 border border-gray-300 rounded-r-md transition-colors duration-200"
                                        type="button" onclick="copyToClipboard('{{ $link->short_url }}')">
                                    <i class="fas fa-copy"></i> Copy
                                </button>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">This is your short URL that you can share</p>
                        </div>

                        <div class="mb-6">
                            <div class="flex items-center">
                                <input class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                       type="checkbox" id="is_protected" name="is_protected" value="1"
                                       {{ old('is_protected', $link->is_protected) ? 'checked' : '' }}>
                                <label class="ml-2 block text-sm text-gray-900" for="is_protected">
                                    <i class="fas fa-lock mr-1 text-blue-600"></i>Protect this link with a password
                                </label>
                            </div>
                        </div>

                        <div class="mb-6" id="password-field" style="display: {{ $link->is_protected ? 'block' : 'none' }};">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <input type="password" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('password') border-red-500 @enderror"
                                   id="password" name="password" value="{{ old('password') }}">
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">
                                @if($link->is_protected)
                                    Leave blank to keep the current password, or enter a new one
                                @else
                                    Minimum 4 characters
                                @endif
                            </p>
                        </div>

                        <div class="mb-6">
                            <label for="earnings_per_click" class="block text-sm font-medium text-gray-700 mb-2">Earnings Per Click</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">$</span>
                                </div>
                                <input type="number" step="0.0001" min="0.0001" max="1.0000"
                                       class="w-full pl-7 pr-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('earnings_per_click') border-red-500 @enderror"
                                       id="earnings_per_click" name="earnings_per_click"
                                       value="{{ old('earnings_per_click', $link->earnings_per_click) }}" required>
                            </div>
                            @error('earnings_per_click')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Amount you earn per unique click (0.0001 - 1.0000)</p>
                        </div>

                        <div class="mb-6">
                            <div class="flex items-center">
                                <input class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                       type="checkbox" id="is_monetized" name="is_monetized" value="1"
                                       {{ old('is_monetized', $link->is_monetized) ? 'checked' : '' }}>
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
                                   value="{{ old('daily_click_limit', $link->daily_click_limit) }}" placeholder="Leave empty for unlimited">
                            @error('daily_click_limit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Maximum clicks per day (1-1000, leave empty for unlimited)</p>
                        </div>

                        <div class="mb-6">
                            <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category (Optional)</label>
                            <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('category') border-red-500 @enderror"
                                   id="category" name="category" value="{{ old('category', $link->category) }}" placeholder="e.g., Technology, Entertainment">
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Categorize your link for better organization</p>
                        </div>

                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description (Optional)</label>
                            <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                                      id="description" name="description" rows="3" placeholder="Brief description of your link">{{ old('description', $link->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">Optional description for your link</p>
                        </div>

                        <div class="flex gap-3">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md transition-colors duration-200 inline-flex items-center">
                                <i class="fas fa-save mr-2"></i>Update Link
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
            passwordInput.required = false; // Not required when editing
        } else {
            passwordField.style.display = 'none';
            passwordInput.required = false;
            passwordInput.value = '';
        }
    });

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            const button = event.target.closest('button');
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check"></i> Copied!';
            button.classList.remove('bg-gray-300', 'hover:bg-gray-400');
            button.classList.add('bg-green-500', 'hover:bg-green-600', 'text-white');

            setTimeout(() => {
                button.innerHTML = originalHTML;
                button.classList.remove('bg-green-500', 'hover:bg-green-600', 'text-white');
                button.classList.add('bg-gray-300', 'hover:bg-gray-400');
            }, 2000);
        });
    }
    </script>
</x-app-layout>
