<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Blog Post') }}: {{ $post->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('blog.update', $post) }}" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Basic Information -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-edit mr-2"></i>Basic Information
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Title -->
                                <div class="md:col-span-2">
                                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                        Post Title <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="title" id="title" value="{{ old('title', $post->title) }}" required
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="Enter your blog post title">
                                    @error('title')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Type -->
                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                                        Post Type <span class="text-red-500">*</span>
                                    </label>
                                    <select name="type" id="type" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select Type</option>
                                        @foreach($types as $key => $type)
                                            <option value="{{ $key }}" {{ old('type', $post->type) == $key ? 'selected' : '' }}>
                                                {{ $type }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('type')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Content Type -->
                                <div>
                                    <label for="content_type" class="block text-sm font-medium text-gray-700 mb-2">
                                        Content Format <span class="text-red-500">*</span>
                                    </label>
                                    <select name="content_type" id="content_type" required
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <option value="">Select Format</option>
                                        @foreach(\App\Models\BlogPost::CONTENT_TYPES as $type => $label)
                                            <option value="{{ $type }}" {{ old('content_type', $post->content_type ?? 'markdown') == $type ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="mt-1 text-sm text-gray-500">
                                        <strong>Markdown:</strong> Use # for headings, ``` for code blocks<br>
                                        <strong>HTML:</strong> Write raw HTML code<br>
                                        <strong>Text:</strong> Plain text with automatic line breaks
                                    </p>
                                    @error('content_type')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Category -->
                                <div>
                                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                                        Category
                                    </label>
                                    <input type="text" name="category" id="category" value="{{ old('category', $post->category) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="e.g., Technology, Business, Lifestyle">
                                    @error('category')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Tags -->
                                <div class="md:col-span-2">
                                    <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tags
                                    </label>
                                    <input type="text" name="tags" id="tags" value="{{ old('tags', is_array($post->tags) ? implode(', ', $post->tags) : $post->tags) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="Enter tags separated by commas (e.g., laravel, php, web-development)">
                                    <p class="mt-1 text-sm text-gray-500">Separate multiple tags with commas</p>
                                    @error('tags')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Excerpt -->
                                <div class="md:col-span-2">
                                    <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-2">
                                        Excerpt
                                    </label>
                                    <textarea name="excerpt" id="excerpt" rows="3"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                              placeholder="Brief summary of your blog post (optional)">{{ old('excerpt', $post->excerpt) }}</textarea>
                                    <p class="mt-1 text-sm text-gray-500">A short description that will appear in blog listings</p>
                                    @error('excerpt')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Content Editor -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-file-alt mr-2"></i>Content
                            </h3>

                            <div>
                                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                                    Post Content <span class="text-red-500">*</span>
                                </label>
                                <textarea name="content" id="content" rows="15" required
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 font-mono"
                                          placeholder="Write your blog post content here...">{{ old('content', $post->content) }}</textarea>
                                <p class="mt-1 text-sm text-gray-500">Supports Markdown formatting</p>
                                @error('content')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Media Uploads -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                <i class="fas fa-images mr-2"></i>Media & Attachments
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Featured Image -->
                                <div>
                                    <label for="featured_image" class="block text-sm font-medium text-gray-700 mb-2">
                                        Featured Image
                                    </label>
                                    @if($post->featured_image)
                                        <div class="mb-3">
                                            <img src="{{ Storage::url($post->featured_image) }}" alt="Current featured image"
                                                 class="w-32 h-32 object-cover rounded-lg border">
                                            <p class="text-sm text-gray-600 mt-1">Current featured image</p>
                                        </div>
                                    @endif
                                    <input type="file" name="featured_image" id="featured_image" accept="image/*"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <p class="mt-1 text-sm text-gray-500">Recommended: 1200x630px, max 2MB</p>
                                    @error('featured_image')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Gallery Images -->
                                <div>
                                    <label for="gallery_images" class="block text-sm font-medium text-gray-700 mb-2">
                                        Gallery Images
                                    </label>
                                    @if($post->gallery_images && count($post->gallery_images) > 0)
                                        <div class="mb-3">
                                            <div class="flex flex-wrap gap-2">
                                                @foreach($post->gallery_images as $image)
                                                    <img src="{{ Storage::url($image) }}" alt="Gallery image"
                                                         class="w-16 h-16 object-cover rounded border">
                                                @endforeach
                                            </div>
                                            <p class="text-sm text-gray-600 mt-1">Current gallery images ({{ count($post->gallery_images) }})</p>
                                        </div>
                                    @endif
                                    <input type="file" name="gallery_images[]" id="gallery_images" accept="image/*" multiple
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <p class="mt-1 text-sm text-gray-500">Select multiple images for gallery</p>
                                    @error('gallery_images')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Attachments -->
                                <div class="md:col-span-2">
                                    <label for="attachments" class="block text-sm font-medium text-gray-700 mb-2">
                                        File Attachments
                                    </label>
                                    @if($post->attachments && count($post->attachments) > 0)
                                        <div class="mb-3">
                                            <div class="space-y-2">
                                                @foreach($post->attachments as $attachment)
                                                    <div class="flex items-center justify-between p-2 bg-gray-100 rounded">
                                                        <div class="flex items-center">
                                                            <i class="fas fa-file mr-2 text-gray-500"></i>
                                                            <span class="text-sm">{{ $attachment['name'] }}</span>
                                                            <span class="text-xs text-gray-500 ml-2">({{ number_format($attachment['size'] / 1024, 1) }} KB)</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                            <p class="text-sm text-gray-600 mt-1">Current attachments ({{ count($post->attachments) }})</p>
                                        </div>
                                    @endif
                                    <input type="file" name="attachments[]" id="attachments" multiple
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <p class="mt-1 text-sm text-gray-500">Upload PDFs, documents, or other files (max 10MB each)</p>
                                    @error('attachments')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Monetization Settings -->
                        <div class="bg-green-50 p-6 rounded-lg border border-green-200">
                            <h3 class="text-lg font-semibold text-green-900 mb-4">
                                <i class="fas fa-coins mr-2"></i>Monetization Settings
                            </h3>

                            @if(Auth::user()->isAdmin())
                                <!-- Admin Monetization Controls -->
                                <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                                    <div class="flex items-center">
                                        <i class="fas fa-crown text-yellow-600 mr-2"></i>
                                        <span class="text-sm font-medium text-yellow-800">Admin Controls - Custom Monetization</span>
                                    </div>
                                    <p class="text-xs text-yellow-700 mt-1">You can override global settings for this specific blog post.</p>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Enable Monetization -->
                                    <div class="md:col-span-2">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="is_monetized" value="1" {{ old('is_monetized', $post->is_monetized) ? 'checked' : '' }}
                                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm font-medium text-green-900">Enable monetization for this post</span>
                                        </label>
                                        <p class="mt-1 text-sm text-green-700">Earn money from visitors based on their engagement time</p>
                                    </div>

                                    <!-- Monetization Type -->
                                    <div>
                                        <label for="monetization_type" class="block text-sm font-medium text-green-700 mb-2">
                                            Monetization Type <span class="text-red-500">*</span>
                                        </label>
                                        <select name="monetization_type" id="monetization_type" required
                                                class="w-full px-3 py-2 border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                            @foreach($monetizationTypes as $key => $type)
                                                <option value="{{ $key }}" {{ old('monetization_type', $post->monetization_type) == $key ? 'selected' : '' }}>
                                                    {{ $type }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('monetization_type')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Ad Type -->
                                    <div>
                                        <label for="ad_type" class="block text-sm font-medium text-green-700 mb-2">
                                            Ad Type <span class="text-red-500">*</span>
                                        </label>
                                        <select name="ad_type" id="ad_type" required
                                                class="w-full px-3 py-2 border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                            @foreach($adTypes as $key => $type)
                                                <option value="{{ $key }}" {{ old('ad_type', $post->ad_type) == $key ? 'selected' : '' }}>
                                                    {{ $type }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('ad_type')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Earning Rates -->
                                    <div class="md:col-span-2">
                                        <h4 class="text-md font-medium text-green-800 mb-3">Time-Based Earning Rates (per visitor)</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div>
                                                <label for="earning_rate_less_2min" class="block text-sm font-medium text-green-700 mb-1">
                                                    Less than 2 minutes
                                                </label>
                                                <input type="number" name="earning_rate_less_2min" id="earning_rate_less_2min"
                                                       value="{{ old('earning_rate_less_2min', $post->earning_rate_less_2min) }}" step="0.0001" min="0" max="1" required
                                                       class="w-full px-3 py-2 border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                                <p class="text-xs text-green-600">₹{{ $post->earning_rate_less_2min }} / ${{ $globalSettings->default_blog_earning_rate_less_2min_usd ?? 0.0010 }}</p>
                                            </div>
                                        <div>
                                            <label for="earning_rate_2_5min" class="block text-sm font-medium text-green-700 mb-1">
                                                2-5 minutes
                                            </label>
                                            <input type="number" name="earning_rate_2_5min" id="earning_rate_2_5min"
                                                   value="{{ old('earning_rate_2_5min', $post->earning_rate_2_5min) }}" step="0.0001" min="0" max="1" required
                                                   class="w-full px-3 py-2 border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                            <p class="text-xs text-green-600">₹{{ $post->earning_rate_2_5min }} / ${{ $globalSettings->default_blog_earning_rate_2_5min_usd ?? 0.0030 }}</p>
                                        </div>
                                        <div>
                                            <label for="earning_rate_more_5min" class="block text-sm font-medium text-green-700 mb-1">
                                                More than 5 minutes
                                            </label>
                                            <input type="number" name="earning_rate_more_5min" id="earning_rate_more_5min"
                                                   value="{{ old('earning_rate_more_5min', $post->earning_rate_more_5min) }}" step="0.0001" min="0" max="1" required
                                                   class="w-full px-3 py-2 border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                            <p class="text-xs text-green-600">₹{{ $post->earning_rate_more_5min }} / ${{ $globalSettings->default_blog_earning_rate_more_5min_usd ?? 0.0060 }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Regular User - Global Settings Info -->
                            <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                                <div class="flex items-center">
                                    <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                                    <span class="text-sm font-medium text-blue-800">Global Monetization Settings Applied</span>
                                </div>
                                <p class="text-xs text-blue-700 mt-1">
                                    This blog post uses the global monetization settings configured by administrators.
                                </p>
                                <div class="mt-3 text-xs text-blue-600">
                                    <p><strong>Type:</strong> {{ $post->monetization_type }}</p>
                                    <p><strong>Ad Type:</strong> {{ $post->ad_type }}</p>
                                    <p><strong>Earning Rates:</strong> ₹{{ $post->earning_rate_less_2min }} - ₹{{ $post->earning_rate_more_5min }}</p>
                                </div>
                            </div>

                            <!-- Hidden fields for regular users - will use existing post values -->
                            <input type="hidden" name="is_monetized" value="{{ $post->is_monetized ? 1 : 0 }}">
                            <input type="hidden" name="monetization_type" value="{{ $post->monetization_type }}">
                            <input type="hidden" name="ad_type" value="{{ $post->ad_type }}">
                            <input type="hidden" name="earning_rate_less_2min" value="{{ $post->earning_rate_less_2min }}">
                            <input type="hidden" name="earning_rate_2_5min" value="{{ $post->earning_rate_2_5min }}">
                            <input type="hidden" name="earning_rate_more_5min" value="{{ $post->earning_rate_more_5min }}">
                        @endif
                        </div>

                        <!-- SEO Settings -->
                        <div class="bg-blue-50 p-6 rounded-lg border border-blue-200">
                            <h3 class="text-lg font-semibold text-blue-900 mb-4">
                                <i class="fas fa-search mr-2"></i>SEO Settings
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Meta Title -->
                                <div>
                                    <label for="meta_title" class="block text-sm font-medium text-blue-700 mb-2">
                                        Meta Title
                                    </label>
                                    <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $post->meta_title) }}"
                                           class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="SEO title for search engines">
                                    <p class="mt-1 text-sm text-blue-600">Leave empty to use post title</p>
                                    @error('meta_title')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Meta Description -->
                                <div>
                                    <label for="meta_description" class="block text-sm font-medium text-blue-700 mb-2">
                                        Meta Description
                                    </label>
                                    <textarea name="meta_description" id="meta_description" rows="3"
                                              class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                              placeholder="Brief description for search results">{{ old('meta_description', $post->meta_description) }}</textarea>
                                    <p class="mt-1 text-sm text-blue-600">Leave empty to use post excerpt</p>
                                    @error('meta_description')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Meta Keywords -->
                                <div>
                                    <label for="meta_keywords" class="block text-sm font-medium text-blue-700 mb-2">
                                        Meta Keywords
                                    </label>
                                    <input type="text" name="meta_keywords" id="meta_keywords" value="{{ old('meta_keywords', is_array($post->meta_keywords) ? implode(', ', $post->meta_keywords) : $post->meta_keywords) }}"
                                           class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="Keywords separated by commas">
                                    <p class="mt-1 text-sm text-blue-600">Separate with commas</p>
                                    @error('meta_keywords')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Canonical URL -->
                                <div>
                                    <label for="canonical_url" class="block text-sm font-medium text-blue-700 mb-2">
                                        Canonical URL
                                    </label>
                                    <input type="url" name="canonical_url" id="canonical_url" value="{{ old('canonical_url', $post->canonical_url) }}"
                                           class="w-full px-3 py-2 border border-blue-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                           placeholder="https://example.com/original-post">
                                    <p class="mt-1 text-sm text-blue-600">If this is a repost</p>
                                    @error('canonical_url')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Publishing Settings -->
                        <div class="bg-purple-50 p-6 rounded-lg border border-purple-200">
                            <h3 class="text-lg font-semibold text-purple-900 mb-4">
                                <i class="fas fa-paper-plane mr-2"></i>Publishing Settings
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Status -->
                                <div>
                                    <label for="status" class="block text-sm font-medium text-purple-700 mb-2">
                                        Publication Status <span class="text-red-500">*</span>
                                    </label>
                                    <select name="status" id="status" required
                                            class="w-full px-3 py-2 border border-purple-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                                        <option value="draft" {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="published" {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>Published</option>
                                        <option value="archived" {{ old('status', $post->status) == 'archived' ? 'selected' : '' }}>Archived</option>
                                        <option value="scheduled" {{ old('status', $post->status) == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Scheduled Date -->
                                <div id="scheduled_date_group" class="{{ old('status', $post->status) == 'scheduled' ? '' : 'hidden' }}">
                                    <label for="scheduled_at" class="block text-sm font-medium text-purple-700 mb-2">
                                        Schedule Date & Time
                                    </label>
                                    <input type="datetime-local" name="scheduled_at" id="scheduled_at"
                                           value="{{ old('scheduled_at', $post->scheduled_at ? $post->scheduled_at->format('Y-m-d\TH:i') : '') }}"
                                           class="w-full px-3 py-2 border border-purple-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    @error('scheduled_at')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('blog.show', $post->slug) }}"
                               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                                <i class="fas fa-times mr-2"></i>Cancel
                            </a>
                            <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-colors">
                                <i class="fas fa-save mr-2"></i>Update Post
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Show/hide scheduled date based on status
        document.getElementById('status').addEventListener('change', function() {
            const scheduledGroup = document.getElementById('scheduled_date_group');
            if (this.value === 'scheduled') {
                scheduledGroup.classList.remove('hidden');
            } else {
                scheduledGroup.classList.add('hidden');
            }
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('status');
            if (statusSelect.value === 'scheduled') {
                document.getElementById('scheduled_date_group').classList.remove('hidden');
            }
        });
    </script>
    @endpush
</x-app-layout>
