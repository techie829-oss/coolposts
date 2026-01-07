<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Protected Link') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-yellow-50 border-b border-yellow-200">
                    <h3 class="text-lg font-medium text-yellow-800 mb-4 text-center">
                        <i class="fas fa-lock mr-2"></i>Protected Link
                    </h3>
                </div>
                <div class="p-6 text-center">
                    <div class="mb-6">
                        <div class="text-5xl text-yellow-500 mb-4">
                            <i class="fas fa-shield-alt"></i>
                        </div>
                        <h4 class="text-xl font-semibold text-gray-900 mb-2">This link is password protected</h4>
                        <p class="text-gray-600">{{ $link->title }}</p>
                    </div>

                    <form method="POST" action="{{ route('link.verify', $link->short_code) }}">
                        @csrf

                        <div class="mb-6">
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Enter Password</label>
                            <input type="password" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-yellow-500 focus:border-yellow-500 @error('password') border-red-500 @enderror"
                                   id="password" name="password" required autofocus>
                            @error('password')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <button type="submit" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-3 px-4 rounded-md transition-colors duration-200 inline-flex items-center justify-center">
                                <i class="fas fa-unlock mr-2"></i>Access Link
                            </button>
                        </div>
                    </form>

                    <div class="mt-6">
                        <a href="/" class="text-gray-500 hover:text-gray-700 text-sm inline-flex items-center">
                            <i class="fas fa-arrow-left mr-1"></i>Go Back
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
