<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Branding Settings') }}
            </h2>
            <div class="flex gap-3">
                <a href="{{ route('global-settings') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium">
                    <i class="fas fa-cog mr-2"></i>Business Settings
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
            <div class="mb-6 bg-purple-50 border border-purple-200 rounded-lg p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-palette text-purple-400"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-purple-800">Brand Identity</h3>
                        <p class="text-sm text-purple-700 mt-1">
                            Customize your platform's brand name, logo, colors, and visual identity. Changes will be reflected across the entire application.
                        </p>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('branding-settings.update') }}" enctype="multipart/form-data" class="space-y-8">
                @csrf

                <!-- Brand Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-signature mr-2 text-blue-600"></i>Brand Information
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Configure your brand name and description</p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Brand Name -->
                            <div>
                                <label for="brand_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Brand Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="brand_name" name="brand_name" value="{{ old('brand_name', $branding->brand_name) }}"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                                @error('brand_name')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Brand Tagline -->
                            <div>
                                <label for="brand_tagline" class="block text-sm font-medium text-gray-700 mb-2">
                                    Brand Tagline
                                </label>
                                <input type="text" id="brand_tagline" name="brand_tagline" value="{{ old('brand_tagline', $branding->brand_tagline) }}"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500"
                                    placeholder="Link Monetization">
                                @error('brand_tagline')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Brand Description -->
                            <div class="md:col-span-2">
                                <label for="brand_description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Brand Description
                                </label>
                                <textarea id="brand_description" name="brand_description" rows="3"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">{{ old('brand_description', $branding->brand_description) }}</textarea>
                                @error('brand_description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Logo & Favicon -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-image mr-2 text-green-600"></i>Logo & Icons
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Upload your logo and favicon</p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Brand Logo -->
                            <div>
                                <label for="brand_logo" class="block text-sm font-medium text-gray-700 mb-2">
                                    Brand Logo
                                </label>
                                @if($branding->brand_logo)
                                    <div class="mb-4">
                                        <img src="{{ asset('storage/' . $branding->brand_logo) }}" alt="Current Logo" class="h-20 w-auto">
                                    </div>
                                @endif
                                <input type="file" id="brand_logo" name="brand_logo" accept="image/*"
                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                                @error('brand_logo')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Favicon -->
                            <div>
                                <label for="favicon" class="block text-sm font-medium text-gray-700 mb-2">
                                    Favicon
                                </label>
                                @if($branding->favicon)
                                    <div class="mb-4">
                                        <img src="{{ asset('storage/' . $branding->favicon) }}" alt="Current Favicon" class="h-16 w-16">
                                    </div>
                                @endif
                                <input type="file" id="favicon" name="favicon" accept="image/*"
                                    class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100">
                                @error('favicon')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Color Palette -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-palette mr-2 text-yellow-600"></i>Color Palette
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Customize your brand colors (HEX format: #RRGGBB)</p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Primary Color -->
                            <div>
                                <label for="primary_color" class="block text-sm font-medium text-gray-700 mb-2">
                                    Primary Color <span class="text-red-500">*</span>
                                </label>
                                <div class="flex gap-2">
                                    <input type="color" id="primary_color" name="primary_color" value="{{ old('primary_color', $branding->primary_color) }}"
                                        class="h-10 w-20 border border-gray-300 rounded cursor-pointer">
                                    <input type="text" value="{{ old('primary_color', $branding->primary_color) }}"
                                        class="flex-1 border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                                </div>
                                @error('primary_color')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Secondary Color -->
                            <div>
                                <label for="secondary_color" class="block text-sm font-medium text-gray-700 mb-2">
                                    Secondary Color <span class="text-red-500">*</span>
                                </label>
                                <div class="flex gap-2">
                                    <input type="color" id="secondary_color" name="secondary_color" value="{{ old('secondary_color', $branding->secondary_color) }}"
                                        class="h-10 w-20 border border-gray-300 rounded cursor-pointer">
                                    <input type="text" value="{{ old('secondary_color', $branding->secondary_color) }}"
                                        class="flex-1 border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                                </div>
                                @error('secondary_color')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Accent Color -->
                            <div>
                                <label for="accent_color" class="block text-sm font-medium text-gray-700 mb-2">
                                    Accent Color <span class="text-red-500">*</span>
                                </label>
                                <div class="flex gap-2">
                                    <input type="color" id="accent_color" name="accent_color" value="{{ old('accent_color', $branding->accent_color) }}"
                                        class="h-10 w-20 border border-gray-300 rounded cursor-pointer">
                                    <input type="text" value="{{ old('accent_color', $branding->accent_color) }}"
                                        class="flex-1 border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                                </div>
                                @error('accent_color')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gradient Colors -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">
                            <i class="fas fa-layer-group mr-2 text-indigo-600"></i>Gradient Colors
                        </h3>
                        <p class="text-sm text-gray-600 mt-1">Configure gradient colors for headers and backgrounds</p>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Gradient Start -->
                            <div>
                                <label for="gradient_start" class="block text-sm font-medium text-gray-700 mb-2">
                                    Gradient Start <span class="text-red-500">*</span>
                                </label>
                                <div class="flex gap-2">
                                    <input type="color" id="gradient_start" name="gradient_start" value="{{ old('gradient_start', $branding->gradient_start) }}"
                                        class="h-10 w-20 border border-gray-300 rounded cursor-pointer">
                                    <input type="text" value="{{ old('gradient_start', $branding->gradient_start) }}"
                                        class="flex-1 border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                                </div>
                                @error('gradient_start')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Gradient End -->
                            <div>
                                <label for="gradient_end" class="block text-sm font-medium text-gray-700 mb-2">
                                    Gradient Middle <span class="text-red-500">*</span>
                                </label>
                                <div class="flex gap-2">
                                    <input type="color" id="gradient_end" name="gradient_end" value="{{ old('gradient_end', $branding->gradient_end) }}"
                                        class="h-10 w-20 border border-gray-300 rounded cursor-pointer">
                                    <input type="text" value="{{ old('gradient_end', $branding->gradient_end) }}"
                                        class="flex-1 border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                                </div>
                                @error('gradient_end')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Gradient Third -->
                            <div>
                                <label for="gradient_third" class="block text-sm font-medium text-gray-700 mb-2">
                                    Gradient End <span class="text-red-500">*</span>
                                </label>
                                <div class="flex gap-2">
                                    <input type="color" id="gradient_third" name="gradient_third" value="{{ old('gradient_third', $branding->gradient_third) }}"
                                        class="h-10 w-20 border border-gray-300 rounded cursor-pointer">
                                    <input type="text" value="{{ old('gradient_third', $branding->gradient_third) }}"
                                        class="flex-1 border-gray-300 rounded-md shadow-sm focus:ring-purple-500 focus:border-purple-500">
                                </div>
                                @error('gradient_third')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Gradient Preview -->
                        <div class="mt-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Gradient Preview
                            </label>
                            <div class="h-20 rounded-lg" style="background: linear-gradient(to right, {{ $branding->gradient_start }}, {{ $branding->gradient_end }}, {{ $branding->gradient_third }})"></div>
                        </div>
                    </div>
                </div>

                <!-- Save Button -->
                <div class="flex justify-end">
                    <button type="submit"
                        class="bg-gradient-to-r from-purple-600 to-pink-600 hover:from-purple-700 hover:to-pink-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg hover:shadow-xl transition-all duration-200">
                        <i class="fas fa-save mr-2"></i>
                        Save Branding Settings
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

