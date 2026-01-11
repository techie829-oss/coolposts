<x-app-layout>
    <div class="min-h-screen bg-gray-50">
        <!-- Top Bar -->
        <div class="bg-white border-b border-gray-200 sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('blog.manage') }}" class="text-gray-600 hover:text-gray-900">
                            <i class="fas fa-arrow-left mr-2"></i>Back to Posts
                        </a>
                        <span class="text-gray-300">|</span>
                        <h1 class="text-lg font-semibold text-gray-900">Create New Post</h1>
                    </div>
                    <div class="flex items-center space-x-3">
                        <button type="submit" name="status" value="draft" form="blog-form"
                            class="px-4 py-2 text-gray-700 hover:text-gray-900 font-medium">
                            <i class="fas fa-file mr-2"></i>Save as Draft
                        </button>
                        <button type="submit" name="status" value="published" form="blog-form"
                            class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 font-medium transition">
                            <i class="fas fa-rocket mr-2"></i>Publish Now
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('blog.store') }}" enctype="multipart/form-data" id="blog-form">
            @csrf

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Main Content Area (Left Side) -->
                    <div class="lg:col-span-2 space-y-6">
                        <!-- Title -->
                        <div class="bg-white rounded-lg shadow-sm p-8">
                            <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                class="w-full text-4xl font-bold border-0 focus:ring-0 placeholder-gray-300"
                                placeholder="Post Title">
                            @error('title')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Content Editor -->
                        <div class="bg-white rounded-lg shadow-sm p-8">
                            <textarea name="content" id="content" rows="20" required
                                class="w-full border-0 focus:ring-0 text-lg leading-relaxed placeholder-gray-300 resize-none"
                                placeholder="Write your story...">{{ old('content') }}</textarea>
                            @error('content')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Excerpt -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-align-left mr-2"></i>Excerpt
                            </label>
                            <textarea name="excerpt" rows="3"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                placeholder="Brief summary of your post...">{{ old('excerpt') }}</textarea>
                            <p class="mt-1 text-xs text-gray-500">Optional: A short description shown in listings</p>
                        </div>

                        <!-- Featured Image -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                <i class="fas fa-image mr-2"></i>Featured Image
                            </label>
                            <input type="file" name="featured_image" id="featured_image" accept="image/*"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                            <div id="featured_image_preview" class="mt-4 hidden"></div>
                            <p class="mt-2 text-xs text-gray-500">Max 10MB • Auto-optimized to WebP</p>
                        </div>
                    </div>

                    <!-- Sidebar (Right Side) -->
                    <div class="lg:col-span-1 space-y-6">
                        <!-- Publish Status Card -->
                        <div class="bg-white rounded-lg shadow-sm p-6 sticky top-24">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-circle-dot mr-2 text-purple-600"></i>Publish Options
                            </h3>

                            <div class="space-y-4">
                                <!-- Quick Publish Info -->
                                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                                    <p class="text-sm text-purple-800 mb-2">
                                        <i class="fas fa-info-circle mr-2"></i>Choose how to publish:
                                    </p>
                                    <ul class="text-xs text-purple-700 space-y-1 ml-4">
                                        <li>• <strong>Draft:</strong> Save for later</li>
                                        <li>• <strong>Publish:</strong> Go live immediately</li>
                                        <li>• <strong>Schedule:</strong> Set a future date</li>
                                    </ul>
                                </div>

                                <!-- Status Radio Buttons -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Status:</label>
                                    <div class="space-y-2">
                                        <label
                                            class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                            <input type="radio" name="status" value="draft"
                                                class="h-4 w-4 text-purple-600" checked>
                                            <span class="ml-3 text-sm">📝 Draft</span>
                                        </label>
                                        <label
                                            class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                            <input type="radio" name="status" value="published"
                                                class="h-4 w-4 text-purple-600">
                                            <span class="ml-3 text-sm">✅ Published</span>
                                        </label>
                                        <label
                                            class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50">
                                            <input type="radio" name="status" value="scheduled"
                                                class="h-4 w-4 text-purple-600">
                                            <span class="ml-3 text-sm">⏰ Scheduled</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Schedule Date -->
                                <div id="schedule-date" class="hidden">
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Publish Date:</label>
                                    <input type="datetime-local" name="scheduled_at" value="{{ old('scheduled_at') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm">
                                </div>
                            </div>
                        </div>

                        <!-- Post Settings Card -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-cog mr-2 text-gray-600"></i>Post Settings
                            </h3>

                            <div class="space-y-4">
                                <!-- Type -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Type</label>
                                    <select name="type" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm">
                                        <option value="">Select Type</option>
                                        @foreach ($types as $key => $type)
                                            <option value="{{ $key }}"
                                                {{ old('type') == $key ? 'selected' : '' }}>{{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Content Format -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Format</label>
                                    <select name="content_type" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm">
                                        @foreach (\App\Models\BlogPost::CONTENT_TYPES as $type => $label)
                                            <option value="{{ $type }}"
                                                {{ old('content_type', 'markdown') == $type ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Category -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Category</label>
                                    <input type="text" name="category" value="{{ old('category') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm"
                                        placeholder="e.g., Technology">
                                </div>

                                <!-- Tags -->
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Tags</label>
                                    <input type="text" name="tags" value="{{ old('tags') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm"
                                        placeholder="Separate with commas">
                                    <p class="mt-1 text-xs text-gray-500">e.g., AI, Machine Learning, Tutorial</p>
                                </div>
                            </div>
                        </div>

                        <!-- SEO Card -->
                        <div class="bg-white rounded-lg shadow-sm p-6">
                            <h3 class="text-sm font-semibold text-gray-900 mb-4 flex items-center">
                                <i class="fas fa-search mr-2 text-gray-600"></i>SEO & Meta
                            </h3>

                            <div class="space-y-4">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Meta Title</label>
                                    <input type="text" name="meta_title" value="{{ old('meta_title') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm"
                                        placeholder="SEO title">
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Meta
                                        Description</label>
                                    <textarea name="meta_description" rows="3"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm"
                                        placeholder="SEO description">{{ old('meta_description') }}</textarea>
                                </div>

                                <div>
                                    <label class="block text-xs font-medium text-gray-700 mb-2">Meta Keywords</label>
                                    <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 text-sm"
                                        placeholder="Separate with commas">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            // Show/hide schedule date based on status
            document.querySelectorAll('[name="status"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    const scheduleDiv = document.getElementById('schedule-date');
                    if (this.value === 'scheduled') {
                        scheduleDiv.classList.remove('hidden');
                    } else {
                        scheduleDiv.classList.add('hidden');
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
