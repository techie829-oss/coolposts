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
                    <form method="POST" action="{{ route('blog.update', $post) }}" enctype="multipart/form-data"
                        class="space-y-6">
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
                                    <input type="text" name="title" id="title"
                                        value="{{ old('title', $post->title) }}" required
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
                                        @foreach ($types as $key => $type)
                                            <option value="{{ $key }}"
                                                {{ old('type', $post->type) == $key ? 'selected' : '' }}>
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
                                        @foreach (\App\Models\BlogPost::CONTENT_TYPES as $type => $label)
                                            <option value="{{ $type }}"
                                                {{ old('content_type', $post->content_type ?? 'markdown') == $type ? 'selected' : '' }}>
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
                                    <input type="text" name="category" id="category"
                                        value="{{ old('category', $post->category) }}"
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
                                    <input type="text" name="tags" id="tags"
                                        value="{{ is_array($tags = old('tags', $post->tags)) ? implode(', ', $tags) : $tags }}"
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
                                    <p class="mt-1 text-sm text-gray-500">A short description that will appear in blog
                                        listings</p>
                                    @error('excerpt')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>


                        <!-- AI Writing Assistant Section -->
                        @php
                            $aiSettings = \App\Models\AiSetting::getSettings();
                            $user = auth()->user();
                            // User-specific AI access takes precedence over global settings
                            $userCanUseAI = $user->isAdmin() || ($user->canUseAi() && $user->canUseAiBlogGeneration());
                        @endphp
                        @if ($aiSettings->blog_generation_enabled && $userCanUseAI)
                            <div
                                class="mb-8 bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl p-6 border border-purple-200">
                                <div class="flex items-center mb-4">
                                    <i class="fas fa-magic text-purple-600 text-2xl mr-3"></i>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">AI Markdown Assistant</h3>
                                        <p class="text-xs text-gray-600">Optimize and improve specific sections of your
                                            content</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Action</label>
                                        <select id="ai_action"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm">
                                            <option value="generate_blog">📖 Generate Full Blog (Markdown)</option>
                                            <option value="improve">✨ Improve Selected Text</option>
                                            <option value="optimize">🚀 Optimize & Polish</option>
                                            <option value="expand">➕ Expand Section</option>
                                            <option value="simplify">🔧 Simplify Language</option>
                                            <option value="generate">📝 Generate New Section</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">AI Model</label>
                                        <select id="ai_model"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm">
                                            <option value="gemini-2.5-flash" selected>Gemini 2.5 Flash ⭐ (Recommended)
                                            </option>
                                            <option value="gemini-2.0-flash-exp">Gemini 2.0 Flash Exp</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Content
                                            Length</label>
                                        <select id="ai_length"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm">
                                            <option value="short">Short (300-500 words)</option>
                                            <option value="medium" selected>Medium (500-1000 words)</option>
                                            <option value="long">Long (1000-2000 words)</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Tone</label>
                                        <select id="ai_tone"
                                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm">
                                            <option value="professional" selected>Professional</option>
                                            <option value="casual">Casual</option>
                                            <option value="formal">Formal</option>
                                            <option value="friendly">Friendly</option>
                                            <option value="technical">Technical</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Context
                                        (Optional)</label>
                                    <textarea id="ai_context" rows="2" placeholder="Describe what you want to improve or generate..."
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm"></textarea>
                                    <p class="text-xs text-gray-500 mt-1">💡 Tip: Select text in the editor and choose
                                        an action, or describe what you need</p>
                                </div>

                                <button type="button" id="generateAIContent"
                                    class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 hover:from-purple-700 hover:to-indigo-700 text-white py-2 px-4 rounded-lg font-medium transition-all duration-200">
                                    <i class="fas fa-magic mr-2"></i>Apply AI Enhancement
                                </button>
                                <div id="ai_generating" class="hidden mt-3 text-center text-sm text-purple-600">
                                    <i class="fas fa-spinner fa-spin mr-2"></i>Optimizing content...
                                </div>
                            </div>
                        @elseif ($aiSettings->blog_generation_enabled && !$userCanUseAI)
                            <!-- AI Restricted Message -->
                            <div
                                class="mb-8 bg-gradient-to-r from-orange-50 to-yellow-50 rounded-xl p-6 border border-orange-200">
                                <div class="flex items-center mb-4">
                                    <i class="fas fa-user-shield text-orange-600 text-2xl mr-3"></i>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">AI Features Restricted</h3>
                                        <p class="text-xs text-gray-600">
                                            @if (!$user->canUseAi())
                                                Your AI access has been disabled by an administrator.
                                            @elseif (!$user->canUseAiBlogGeneration())
                                                Blog generation is disabled for your account.
                                            @else
                                                AI features are currently restricted to administrators only.
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Content Editor -->
                        <div class="bg-gray-50 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 px-6 pt-6">
                                <i class="fas fa-file-alt mr-2"></i>Content Editor
                            </h3>

                            <div class="px-6 pb-6">
                                <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                                    Post Content <span class="text-red-500">*</span>
                                </label>

                                <!-- Content Type Toolbar -->
                                <div id="markdownToolbar"
                                    class="bg-gray-50 border border-gray-200 rounded-t-lg p-3 flex flex-wrap gap-2">
                                    <button type="button" onclick="insertMarkdown('**', '**', 'Bold Text')"
                                        class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50"><i
                                            class="fas fa-bold"></i> Bold</button>
                                    <button type="button" onclick="insertMarkdown('*', '*', 'Italic Text')"
                                        class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50"><i
                                            class="fas fa-italic"></i> Italic</button>
                                    <button type="button" onclick="insertMarkdown('## ', '', 'Heading 2')"
                                        class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50"><i
                                            class="fas fa-heading"></i> H2</button>
                                    <button type="button" onclick="insertMarkdown('### ', '', 'Heading 3')"
                                        class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50"><i
                                            class="fas fa-heading"></i> H3</button>
                                    <button type="button" onclick="insertMarkdown('- ', '', 'List Item')"
                                        class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50"><i
                                            class="fas fa-list-ul"></i> List</button>
                                    <button type="button" onclick="insertMarkdown('1. ', '', 'Numbered Item')"
                                        class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50"><i
                                            class="fas fa-list-ol"></i> Numbered</button>
                                    <button type="button" onclick="insertMarkdown('```bash\n', '\n```', 'Bash Code')"
                                        class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50"><i
                                            class="fas fa-code"></i> Bash</button>
                                    <button type="button" onclick="insertMarkdown('```html\n', '\n```', 'HTML Code')"
                                        class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50"><i
                                            class="fas fa-code"></i> HTML</button>
                                    <button type="button" onclick="insertMarkdown('[', '](url)', 'Link')"
                                        class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50"><i
                                            class="fas fa-link"></i> Link</button>
                                    <button type="button" onclick="insertMarkdown('![', '](image-url)', 'Image')"
                                        class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50"><i
                                            class="fas fa-image"></i> Image</button>
                                </div>

                                <!-- HTML Toolbar -->
                                <div id="htmlToolbar"
                                    class="bg-gray-50 border border-gray-200 rounded-t-lg p-3 flex flex-wrap gap-2 hidden">
                                    <button type="button" onclick="insertHTML('<strong>', '</strong>', 'Bold Text')"
                                        class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50"><i
                                            class="fas fa-bold"></i> Bold</button>
                                    <button type="button" onclick="insertHTML('<em>', '</em>', 'Italic Text')"
                                        class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50"><i
                                            class="fas fa-italic"></i> Italic</button>
                                    <button type="button" onclick="insertHTML('<h2>', '</h2>', 'Heading 2')"
                                        class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50"><i
                                            class="fas fa-heading"></i> H2</button>
                                    <button type="button" onclick="insertHTML('<a href=\"\">', '</a>', 'Link')"
                                        class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50"><i
                                            class="fas fa-link"></i> Link</button>
                                </div>

                                <!-- Text Toolbar -->
                                <div id="textToolbar"
                                    class="bg-gray-50 border border-gray-200 rounded-t-lg p-3 flex flex-wrap gap-2 hidden">
                                    <div class="text-sm text-gray-600"><i class="fas fa-info-circle mr-2"></i>Plain
                                        text format.</div>
                                </div>

                                <textarea name="content" id="content" rows="20" required
                                    class="w-full px-4 py-3 border border-gray-300 rounded-b-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent font-mono text-sm"
                                    placeholder="Write your blog post content here...">{{ old('content', $post->content) }}</textarea>
                                @error('content')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror

                                <!-- Content Preview Toggle -->
                                <div class="mt-3 flex justify-end">
                                    <button type="button" id="previewToggle"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                        <i class="fas fa-eye mr-2"></i>Preview Content
                                    </button>
                                </div>

                                <!-- Content Preview -->
                                <div id="contentPreview" class="hidden mt-4 p-6 bg-gray-50 rounded-lg border">
                                    <h4 class="text-lg font-semibold text-gray-900 mb-3">Content Preview</h4>
                                    <div id="previewContent" class="prose prose-lg max-w-none"></div>
                                </div>
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
                                    @if ($post->featured_image)
                                        <div class="mb-3">
                                            <img src="{{ $post->featured_image }}"
                                                alt="Current featured image"
                                                class="w-32 h-32 object-cover rounded-lg border">
                                            <p class="text-sm text-gray-600 mt-1">Current featured image</p>
                                        </div>
                                    @endif
                                    <input type="file" name="featured_image" id="featured_image" accept="image/*"
                                        <div id="featured_image_preview" class="mb-3 hidden">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Live Preview:</p>
                                    <div
                                        class="relative w-full h-48 bg-gray-100 rounded-lg overflow-hidden border border-gray-300">
                                        <img src="" alt="Preview" class="w-full h-full object-cover">
                                        <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white text-xs p-1 text-center hidden"
                                            id="featured_image_info"></div>
                                    </div>
                                </div>
                                <input type="file" name="featured_image" id="featured_image" accept="image/*"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <p class="mt-1 text-sm text-gray-500">Recommended: 1200x630px, max 2MB (Auto-optimized)
                                </p>
                                @error('featured_image')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Gallery Images -->
                            <div>
                                <label for="gallery_images" class="block text-sm font-medium text-gray-700 mb-2">
                                    Gallery Images
                                </label>
                                @if ($post->gallery_images && count($post->gallery_images) > 0)
                                    <div class="mb-3">
                                        <div class="flex flex-wrap gap-2">
                                            @foreach ($post->gallery_images as $image)
                                                <img src="{{ $image }}" alt="Gallery image"
                                                    class="w-16 h-16 object-cover rounded border">
                                            @endforeach
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1">Current gallery images
                                            ({{ count($post->gallery_images) }})</p>
                                    </div>
                                @endif
                                <div id="gallery_preview_container" class="mb-3 hidden">
                                    <p class="text-sm font-medium text-gray-700 mb-2">New Images Preview:</p>
                                    <div class="flex flex-wrap gap-2" id="gallery_previews">
                                        <!-- Previews will be inserted here -->
                                    </div>
                                </div>
                                <input type="file" name="gallery_images[]" id="gallery_images" accept="image/*"
                                    multiple
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <p class="mt-1 text-sm text-gray-500">Select multiple images for gallery
                                    (Auto-optimized)</p>
                                @error('gallery_images')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Attachments -->
                            <div class="md:col-span-2">
                                <label for="attachments" class="block text-sm font-medium text-gray-700 mb-2">
                                    File Attachments
                                </label>
                                @if ($post->attachments && count($post->attachments) > 0)
                                    <div class="mb-3">
                                        <div class="space-y-2">
                                            @foreach ($post->attachments as $attachment)
                                                <div class="flex items-center justify-between p-2 bg-gray-100 rounded">
                                                    <div class="flex items-center">
                                                        <i class="fas fa-file mr-2 text-gray-500"></i>
                                                        <span class="text-sm">{{ $attachment['name'] }}</span>
                                                        <span
                                                            class="text-xs text-gray-500 ml-2">({{ number_format($attachment['size'] / 1024, 1) }}
                                                            KB)</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <p class="text-sm text-gray-600 mt-1">Current attachments
                                            ({{ count($post->attachments) }})</p>
                                    </div>
                                @endif
                                <input type="file" name="attachments[]" id="attachments" multiple
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                <p class="mt-1 text-sm text-gray-500">Upload PDFs, documents, or other files (max
                                    10MB each)</p>
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

                    @if (Auth::user()->isAdmin())
                        <!-- Admin Monetization Controls -->
                        <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-crown text-yellow-600 mr-2"></i>
                                <span class="text-sm font-medium text-yellow-800">Admin Controls - Custom
                                    Monetization</span>
                            </div>
                            <p class="text-xs text-yellow-700 mt-1">You can override global settings for this
                                specific blog post.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Enable Monetization -->
                            <div class="md:col-span-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="is_monetized" value="1"
                                        {{ old('is_monetized', $post->is_monetized) ? 'checked' : '' }}
                                        class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm font-medium text-green-900">Enable monetization
                                        for this post</span>
                                </label>
                                <p class="mt-1 text-sm text-green-700">Earn money from visitors based on their
                                    engagement time</p>
                            </div>

                            <!-- Monetization Type -->
                            <div>
                                <label for="monetization_type" class="block text-sm font-medium text-green-700 mb-2">
                                    Monetization Type <span class="text-red-500">*</span>
                                </label>
                                <select name="monetization_type" id="monetization_type" required
                                    class="w-full px-3 py-2 border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                    @foreach ($monetizationTypes as $key => $type)
                                        <option value="{{ $key }}"
                                            {{ old('monetization_type', $post->monetization_type) == $key ? 'selected' : '' }}>
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
                                    @foreach ($adTypes as $key => $type)
                                        <option value="{{ $key }}"
                                            {{ old('ad_type', $post->ad_type) == $key ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                                <!-- Ad Frequency -->
                                <div>
                                    <label for="ad_frequency" class="block text-sm font-medium text-green-700 mb-2">
                                        Ad Frequency (Para)
                                    </label>
                                    <input type="number" name="ad_frequency" id="ad_frequency"
                                        value="{{ old('ad_frequency', $post->ad_frequency) }}" min="1"
                                        max="10"
                                        class="w-full px-3 py-2 border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                                        placeholder="Every X paragraphs">
                                    <p class="mt-1 text-xs text-green-600">Insert ad after every X paragraphs
                                        (1-10)</p>
                                    @error('ad_frequency')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Earning Rates -->
                            <div class="md:col-span-2">
                                <h4 class="text-md font-medium text-green-800 mb-3">Time-Based Earning Rates
                                    (per visitor)</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label for="earning_rate_less_2min"
                                            class="block text-sm font-medium text-green-700 mb-1">
                                            Less than 2 minutes
                                        </label>
                                        <input type="number" name="earning_rate_less_2min"
                                            id="earning_rate_less_2min"
                                            value="{{ old('earning_rate_less_2min', $post->earning_rate_less_2min) }}"
                                            step="0.0001" min="0" max="1" required
                                            class="w-full px-3 py-2 border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                        <p class="text-xs text-green-600">₹{{ $post->earning_rate_less_2min }}
                                            /
                                            ${{ $globalSettings->default_blog_earning_rate_less_2min_usd ?? 0.001 }}
                                        </p>
                                    </div>
                                    <div>
                                        <label for="earning_rate_2_5min"
                                            class="block text-sm font-medium text-green-700 mb-1">
                                            2-5 minutes
                                        </label>
                                        <input type="number" name="earning_rate_2_5min" id="earning_rate_2_5min"
                                            value="{{ old('earning_rate_2_5min', $post->earning_rate_2_5min) }}"
                                            step="0.0001" min="0" max="1" required
                                            class="w-full px-3 py-2 border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                        <p class="text-xs text-green-600">₹{{ $post->earning_rate_2_5min }} /
                                            ${{ $globalSettings->default_blog_earning_rate_2_5min_usd ?? 0.003 }}
                                        </p>
                                    </div>
                                    <div>
                                        <label for="earning_rate_more_5min"
                                            class="block text-sm font-medium text-green-700 mb-1">
                                            More than 5 minutes
                                        </label>
                                        <input type="number" name="earning_rate_more_5min"
                                            id="earning_rate_more_5min"
                                            value="{{ old('earning_rate_more_5min', $post->earning_rate_more_5min) }}"
                                            step="0.0001" min="0" max="1" required
                                            class="w-full px-3 py-2 border border-green-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                                        <p class="text-xs text-green-600">₹{{ $post->earning_rate_more_5min }}
                                            /
                                            ${{ $globalSettings->default_blog_earning_rate_more_5min_usd ?? 0.006 }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Regular User - Global Settings Info -->
                        <div class="p-4 bg-blue-50 border border-blue-200 rounded-lg">
                            <div class="flex items-center">
                                <i class="fas fa-info-circle text-blue-600 mr-2"></i>
                                <span class="text-sm font-medium text-blue-800">Global Monetization Settings
                                    Applied</span>
                            </div>
                            <p class="text-xs text-blue-700 mt-1">
                                This blog post uses the global monetization settings configured by
                                administrators.
                            </p>
                            <div class="mt-3 text-xs text-blue-600">
                                <p><strong>Type:</strong> {{ $post->monetization_type }}</p>
                                <p><strong>Ad Type:</strong> {{ $post->ad_type }}</p>
                                <p><strong>Earning Rates:</strong> ₹{{ $post->earning_rate_less_2min }} -
                                    ₹{{ $post->earning_rate_more_5min }}</p>
                            </div>
                        </div>

                        <!-- Hidden fields for regular users - will use existing post values -->
                        <input type="hidden" name="is_monetized" value="{{ $post->is_monetized ? 1 : 0 }}">
                        <input type="hidden" name="monetization_type" value="{{ $post->monetization_type }}">
                        <input type="hidden" name="ad_type" value="{{ $post->ad_type }}">
                        <input type="hidden" name="earning_rate_less_2min"
                            value="{{ $post->earning_rate_less_2min }}">
                        <input type="hidden" name="earning_rate_2_5min" value="{{ $post->earning_rate_2_5min }}">
                        <input type="hidden" name="earning_rate_more_5min"
                            value="{{ $post->earning_rate_more_5min }}">
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
                            <input type="text" name="meta_title" id="meta_title"
                                value="{{ old('meta_title', $post->meta_title) }}"
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
                            @php
                                $metaKeywords = old('meta_keywords', $post->meta_keywords);
                                if (is_array($metaKeywords)) {
                                    $metaKeywords = implode(', ', $metaKeywords);
                                }
                            @endphp
                            <input type="text" name="meta_keywords" id="meta_keywords"
                                value="{{ $metaKeywords }}"
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
                            <input type="url" name="canonical_url" id="canonical_url"
                                value="{{ old('canonical_url', $post->canonical_url) }}"
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
                                <option value="draft"
                                    {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>Draft
                                </option>
                                <option value="published"
                                    {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>
                                    Published</option>
                                <option value="archived"
                                    {{ old('status', $post->status) == 'archived' ? 'selected' : '' }}>Archived
                                </option>
                                <option value="scheduled"
                                    {{ old('status', $post->status) == 'scheduled' ? 'selected' : '' }}>
                                    Scheduled</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Scheduled Date -->
                        <div id="scheduled_date_group"
                            class="{{ old('status', $post->status) == 'scheduled' ? '' : 'hidden' }}">
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


    <!-- AI Content Modal -->
    <div id="aiContentModal"
        class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg max-w-4xl w-full mx-4 max-h-[90vh] flex flex-col">
            <div class="flex items-center justify-between p-6 border-b">
                <h3 class="text-xl font-bold text-gray-900">AI Generated Content</h3>
                <div class="flex gap-2">
                    <button onclick="copyAIContent()"
                        class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                        <i class="fas fa-copy mr-2"></i>Copy Content
                    </button>
                    <button onclick="closeAIContentModal()"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="p-6 overflow-y-auto flex-1">
                <div id="aiGeneratedContent" class="prose max-w-none whitespace-pre-wrap"></div>
            </div>
            <div class="flex items-center justify-between p-6 border-t bg-gray-50">
                <button onclick="insertAIContent()"
                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                    <i class="fas fa-plus mr-2"></i>Insert into Editor
                </button>
                <button onclick="regenerateAIContent()"
                    class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                    <i class="fas fa-redo mr-2"></i>Regenerate
                </button>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
        <script>
            // AI Content Modal Functions
            let aiGeneratedContent = '';
            let aiGeneratedExcerpt = '';
            let currentAIAction = '';

            function showAIContentModal(content, excerpt, action) {
                aiGeneratedContent = content;
                aiGeneratedExcerpt = excerpt || '';
                currentAIAction = action;
                document.getElementById('aiGeneratedContent').textContent = content;
                document.getElementById('aiContentModal').classList.remove('hidden');
            }

            function closeAIContentModal() {
                document.getElementById('aiContentModal').classList.add('hidden');
            }

            function copyAIContent() {
                navigator.clipboard.writeText(aiGeneratedContent);
                alert('Content copied to clipboard');
            }

            function insertAIContent() {
                const contentField = document.getElementById('content');
                const currentContent = contentField.value;
                contentField.value = currentContent + (currentContent ? '\n\n' : '') + aiGeneratedContent;
                closeAIContentModal();
            }

            function regenerateAIContent() {
                closeAIContentModal();
                document.getElementById('generateAIContent').click();
            }

            // Markdown insertion functions
            function insertMarkdown(before, after, placeholder) {
                const textarea = document.getElementById('content');
                const start = textarea.selectionStart;
                const end = textarea.selectionEnd;
                const selectedText = textarea.value.substring(start, end);
                const replacement = before + (selectedText || placeholder) + after;
                textarea.value = textarea.value.substring(0, start) + replacement + textarea.value.substring(end);
                textarea.focus();
                textarea.setSelectionRange(start + before.length, start + before.length + (selectedText || placeholder).length);
            }

            function insertHTML(before, after, placeholder) {
                const textarea = document.getElementById('content');
                const start = textarea.selectionStart;
                const end = textarea.selectionEnd;
                const selectedText = textarea.value.substring(start, end);
                const replacement = before + (selectedText || placeholder) + after;
                textarea.value = textarea.value.substring(0, start) + replacement + textarea.value.substring(end);
                textarea.focus();
                textarea.setSelectionRange(start + before.length, start + before.length + (selectedText || placeholder).length);
            }

            // Initialize functionality on page load
            document.addEventListener('DOMContentLoaded', function() {
                // Schedule date toggle
                const statusSelect = document.getElementById('status');
                const scheduledGroup = document.getElementById('scheduled_date_group');

                function toggleScheduledDate() {
                    if (statusSelect.value === 'scheduled') {
                        scheduledGroup.classList.remove('hidden');
                    } else {
                        scheduledGroup.classList.add('hidden');
                    }
                }

                statusSelect.addEventListener('change', toggleScheduledDate);
                toggleScheduledDate(); // Initial check

                // Content Type Change Listener
                const contentTypeSelect = document.getElementById('content_type');
                if (contentTypeSelect) {
                    contentTypeSelect.addEventListener('change', function() {
                        const contentType = this.value;
                        const markdownToolbar = document.getElementById('markdownToolbar');
                        const htmlToolbar = document.getElementById('htmlToolbar');
                        const textToolbar = document.getElementById('textToolbar');
                        const previewToggle = document.getElementById('previewToggle');
                        const contentTextarea = document.getElementById('content');

                        if (markdownToolbar) markdownToolbar.classList.add('hidden');
                        if (htmlToolbar) htmlToolbar.classList.add('hidden');
                        if (textToolbar) textToolbar.classList.add('hidden');

                        switch (contentType) {
                            case 'markdown':
                                if (markdownToolbar) markdownToolbar.classList.remove('hidden');
                                if (previewToggle) previewToggle.classList.remove('hidden');
                                contentTextarea.placeholder =
                                    'Write your blog post content here using markdown...';
                                break;
                            case 'html':
                                if (htmlToolbar) htmlToolbar.classList.remove('hidden');
                                if (previewToggle) previewToggle.classList.remove('hidden');
                                contentTextarea.placeholder = 'Write your blog post content here using HTML...';
                                break;
                            case 'text':
                                if (textToolbar) textToolbar.classList.remove('hidden');
                                if (previewToggle) previewToggle.classList.add('hidden');
                                contentTextarea.placeholder =
                                    'Write your blog post content here as plain text...';
                                break;
                        }
                    });
                    // Trigger change to set initial state
                    contentTypeSelect.dispatchEvent(new Event('change'));
                }
            });

            // Content preview functionality
            const previewToggle = document.getElementById('previewToggle');
            if (previewToggle) {
                previewToggle.addEventListener('click', function() {
                    const preview = document.getElementById('contentPreview');
                    const previewContent = document.getElementById('previewContent');
                    const content = document.getElementById('content').value;
                    const contentType = document.getElementById('content_type').value;

                    if (preview.classList.contains('hidden')) {
                        // Show preview
                        let htmlContent = '';
                        switch (contentType) {
                            case 'markdown':
                                htmlContent = marked.parse(content);
                                break;
                            case 'html':
                                htmlContent = content; // Treat as raw HTML
                                break;
                            case 'text':
                                htmlContent = content.replace(/\n/g, '<br>');
                                break;
                        }
                        previewContent.innerHTML = htmlContent;
                        preview.classList.remove('hidden');
                        this.innerHTML = '<i class="fas fa-eye-slash mr-2"></i>Hide Preview';
                    } else {
                        // Hide preview
                        preview.classList.add('hidden');
                        this.innerHTML = '<i class="fas fa-eye mr-2"></i>Preview Content';
                    }
                });
            }

            // AI Generation Logic
            const generateAiBtn = document.getElementById('generateAIContent');
            if (generateAiBtn) {
                generateAiBtn.addEventListener('click', async function() {
                    const contentField = document.getElementById('content');
                    const action = document.getElementById('ai_action').value;
                    const context = document.getElementById('ai_context').value;
                    const aiModel = document.getElementById('ai_model').value;
                    const aiLength = document.getElementById('ai_length') ? document.getElementById('ai_length')
                        .value : 'medium';
                    const aiTone = document.getElementById('ai_tone') ? document.getElementById('ai_tone').value :
                        'professional';

                    const start = contentField.selectionStart;
                    const end = contentField.selectionEnd;
                    const selectedText = contentField.value.substring(start, end);

                    if (['improve', 'optimize', 'expand', 'simplify'].includes(action) && !selectedText) {
                        alert('Please select the text you want to ' + action);
                        return;
                    }

                    this.disabled = true;
                    document.getElementById('ai_generating').classList.remove('hidden');

                    try {
                        const requestBody = {
                            action: action,
                            context: context,
                            selected_text: selectedText,
                            post_context: {
                                title: document.getElementById('title').value,
                                type: document.getElementById('type').value,
                                category: document.getElementById('category').value
                            },
                            model: aiModel,
                            length: aiLength,
                            tone: aiTone
                        };

                        const response = await fetch('{{ route('ai.generate-blog') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(requestBody)
                        });

                        const data = await response.json();
                        if (data.success) {
                            showAIContentModal(data.content, data.excerpt, action);
                            document.getElementById('ai_context').value = '';
                        } else {
                            alert('Error: ' + data.message);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('An error occurred during AI generation');
                    } finally {
                        this.disabled = false;
                        document.getElementById('ai_generating').classList.add('hidden');
                    }
                });
            }
            // Image Upload Engine (Preview & Client-side Compression)
            document.addEventListener('DOMContentLoaded', function() {
                const MAX_WIDTH = 1920;
                const MAX_SIZE_MB = 2;

                // Handle Featured Image
                const featuredInput = document.getElementById('featured_image');
                const featuredPreview = document.getElementById('featured_image_preview');
                const featuredImg = featuredPreview ? featuredPreview.querySelector('img') : null;
                const featuredInfo = document.getElementById('featured_image_info');

                if (featuredInput && featuredPreview) {
                    featuredInput.addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        if (!file) return;

                        if (file.size > MAX_SIZE_MB * 1024 * 1024) {
                            // Compress
                            processImage(file, (compressedBlob) => {
                                // Create new file from blob
                                const newFile = new File([compressedBlob], file.name, {
                                    type: 'image/webp',
                                    lastModified: Date.now()
                                });

                                // Update input files (requires DataTransfer)
                                const dataTransfer = new DataTransfer();
                                dataTransfer.items.add(newFile);
                                featuredInput.files = dataTransfer.files;

                                // Show preview
                                showPreview(newFile, featuredPreview, featuredImg, featuredInfo, true);
                            });
                        } else {
                            // Just show preview
                            showPreview(file, featuredPreview, featuredImg, featuredInfo, false);
                        }
                    });
                }

                // Handle Gallery Images
                const galleryInput = document.getElementById('gallery_images');
                const galleryContainer = document.getElementById('gallery_preview_container');
                const galleryPreviews = document.getElementById('gallery_previews');

                if (galleryInput && galleryContainer) {
                    galleryInput.addEventListener('change', async function(e) {
                        const files = Array.from(e.target.files);
                        if (!files.length) return;

                        galleryPreviews.innerHTML = ''; // Clear previous
                        galleryContainer.classList.remove('hidden');

                        const dataTransfer = new DataTransfer();
                        let processedCount = 0;

                        for (const file of files) {
                            if (file.size > MAX_SIZE_MB * 1024 * 1024) {
                                // Compress
                                await new Promise(resolve => {
                                    processImage(file, (compressedBlob) => {
                                        const newFile = new File([compressedBlob], file
                                            .name, {
                                                type: 'image/webp',
                                                lastModified: Date.now()
                                            });
                                        dataTransfer.items.add(newFile);
                                        addGalleryPreview(newFile, galleryPreviews, true);
                                        resolve();
                                    });
                                });
                            } else {
                                dataTransfer.items.add(file);
                                addGalleryPreview(file, galleryPreviews, false);
                            }
                        }

                        // Update input with optimized files
                        galleryInput.files = dataTransfer.files;
                    });
                }

                function showPreview(file, container, img, infoStr, isOptimized) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        img.src = e.target.result;
                        container.classList.remove('hidden');
                        if (infoStr) {
                            infoStr.textContent = isOptimized ?
                                `Optimized: ${(file.size / 1024).toFixed(1)} KB (WebP)` :
                                `Original: ${(file.size / 1024).toFixed(1)} KB`;
                            infoStr.classList.remove('hidden');
                        }
                    }
                    reader.readAsDataURL(file);
                }

                function addGalleryPreview(file, container, isOptimized) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const div = document.createElement('div');
                        div.className = 'relative w-20 h-20 border rounded overflow-hidden group';
                        div.innerHTML = `
                            <img src="${e.target.result}" class="w-full h-full object-cover">
                            ${isOptimized ? '<div class="absolute bottom-0 inset-x-0 bg-green-500 text-white text-[10px] text-center">Optimized</div>' : ''}
                        `;
                        container.appendChild(div);
                    }
                    reader.readAsDataURL(file);
                }

                function processImage(file, callback) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        const img = new Image();
                        img.onload = function() {
                            const canvas = document.createElement('canvas');
                            let width = img.width;
                            let height = img.height;

                            if (width > MAX_WIDTH) {
                                height = Math.round(height * (MAX_WIDTH / width));
                                width = MAX_WIDTH;
                            }

                            canvas.width = width;
                            canvas.height = height;

                            const ctx = canvas.getContext('2d');
                            ctx.drawImage(img, 0, 0, width, height);

                            canvas.toBlob((blob) => {
                                callback(blob);
                            }, 'image/webp', 0.8);
                        }
                        img.src = event.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            });
        </script>
    @endpush
</x-app-layout>
