<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Create New Blog Post') }}
            </h2>
            <a href="{{ route('blog.index') }}"
                class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>Back to Blog
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-2xl">
                <form method="POST" action="{{ route('blog.store') }}" enctype="multipart/form-data" class="p-8">
                    @csrf

                    <!-- Basic Information Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Basic Information</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Title -->
                            <div class="md:col-span-2">
                                <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Post Title
                                    *</label>
                                <input type="text" name="title" id="title" value="{{ old('title') }}" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    placeholder="Enter a compelling title for your blog post">
                                @error('title')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Excerpt -->
                            <div class="md:col-span-2">
                                <label for="excerpt"
                                    class="block text-sm font-medium text-gray-700 mb-2">Excerpt/Summary *</label>
                                <textarea name="excerpt" id="excerpt" rows="3" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    placeholder="Write a brief summary of your blog post">{{ old('excerpt') }}</textarea>
                                @error('excerpt')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Type -->
                            <div>
                                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">Post Type
                                    *</label>
                                <select name="type" id="type" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    <option value="">Select Type</option>
                                    @foreach (\App\Models\BlogPost::TYPES as $key => $label)
                                        <option value="{{ $key }}" {{ old('type') == $key ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Content Type -->
                            <div>
                                <label for="content_type" class="block text-sm font-medium text-gray-700 mb-2">Content
                                    Format *</label>
                                <select name="content_type" id="content_type" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    <option value="">Select Format</option>
                                    @foreach (\App\Models\BlogPost::CONTENT_TYPES as $type => $label)
                                        <option value="{{ $type }}"
                                            {{ old('content_type', 'markdown') == $type ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="text-sm text-gray-500 mt-1">
                                    <strong>Markdown:</strong> Use # for headings, ``` for code blocks<br>
                                    <strong>HTML:</strong> Write raw HTML code<br>
                                    <strong>Text:</strong> Plain text with automatic line breaks
                                </p>
                                @error('content_type')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Category -->
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Category
                                    *</label>
                                <input type="text" name="category" id="category" value="{{ old('category') }}"
                                    required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    placeholder="e.g., Web Development, Programming">
                                @error('category')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Tags -->
                            <div class="md:col-span-2">
                                <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">Tags</label>
                                <input type="text" name="tags" id="tags" value="{{ old('tags') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                    placeholder="Enter tags separated by commas (e.g., laravel, php, tutorial)">
                                <p class="text-sm text-gray-500 mt-1">Separate multiple tags with commas</p>
                                @error('tags')
                                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- AI Writing Assistant Section -->
                    @php
                        $aiSettings = \App\Models\AiSetting::getSettings();
                        $user = auth()->user();
                        // User-specific AI access takes precedence over global settings
                        $userCanUseAI = $user->isAdmin() ||
                                       ($user->canUseAi() && $user->canUseAiBlogGeneration());
                    @endphp
                    @if ($aiSettings->blog_generation_enabled && $userCanUseAI)
                        <div
                            class="mb-8 bg-gradient-to-r from-purple-50 to-indigo-50 rounded-xl p-6 border border-purple-200">
                            <div class="flex items-center mb-4">
                                <i class="fas fa-magic text-purple-600 text-2xl mr-3"></i>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">AI Markdown Assistant</h3>
                                    <p class="text-xs text-gray-600">Optimize and improve specific sections of your content</p>
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
                                        <option value="gemini-2.5-flash" selected>Gemini 2.5 Flash ⭐ (Recommended)</option>
                                        <option value="gemini-2.0-flash-exp">Gemini 2.0 Flash Exp</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Content Length</label>
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
                                <label class="block text-sm font-medium text-gray-700 mb-2">Context (Optional)</label>
                                <textarea id="ai_context" rows="2"
                                    placeholder="Describe what you want to improve or generate..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm"></textarea>
                                <p class="text-xs text-gray-500 mt-1">💡 Tip: Select text in the editor and choose an action, or describe what you need</p>
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
                        <div class="mb-8 bg-gradient-to-r from-orange-50 to-yellow-50 rounded-xl p-6 border border-orange-200">
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
                            <div class="flex items-center gap-2">
                                <i class="fas fa-info-circle text-orange-500 mr-2"></i>
                                <span class="text-sm text-gray-700">
                                    @if (!$user->canUseAi())
                                        Contact an administrator to enable AI access for your account.
                                    @elseif (!$user->canUseAiBlogGeneration())
                                        Contact an administrator to enable blog generation for your account.
                                    @else
                                        Contact an administrator to enable AI features for all users.
                                    @endif
                                </span>
                            </div>
                        </div>
                    @endif

                    <!-- Content Editor Section -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Content Editor</h3>

                        <!-- Content Type Toolbar -->
                        <div id="markdownToolbar"
                            class="bg-gray-50 border border-gray-200 rounded-t-lg p-3 flex flex-wrap gap-2">
                            <button type="button" onclick="insertMarkdown('**', '**', 'Bold Text')"
                                class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50">
                                <i class="fas fa-bold"></i> Bold
                            </button>
                            <button type="button" onclick="insertMarkdown('*', '*', 'Italic Text')"
                                class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50">
                                <i class="fas fa-italic"></i> Italic
                            </button>
                            <button type="button" onclick="insertMarkdown('## ', '', 'Heading 2')"
                                class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50">
                                <i class="fas fa-heading"></i> H2
                            </button>
                            <button type="button" onclick="insertMarkdown('### ', '', 'Heading 3')"
                                class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50">
                                <i class="fas fa-heading"></i> H3
                            </button>
                            <button type="button" onclick="insertMarkdown('- ', '', 'List Item')"
                                class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50">
                                <i class="fas fa-list-ul"></i> List
                            </button>
                            <button type="button" onclick="insertMarkdown('1. ', '', 'Numbered Item')"
                                class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50">
                                <i class="fas fa-list-ol"></i> Numbered
                            </button>
                            <button type="button" onclick="insertMarkdown('```bash\n', '\n```', 'Bash Code')"
                                class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50">
                                <i class="fas fa-code"></i> Bash
                            </button>
                            <button type="button" onclick="insertMarkdown('```nginx\n', '\n```', 'Nginx Config')"
                                class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50">
                                <i class="fas fa-code"></i> Nginx
                            </button>
                            <button type="button" onclick="insertMarkdown('```html\n', '\n```', 'HTML Code')"
                                class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50">
                                <i class="fas fa-code"></i> HTML
                            </button>
                            <button type="button"
                                onclick="insertMarkdown('```javascript\n', '\n```', 'JavaScript Code')"
                                class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50">
                                <i class="fas fa-code"></i> JS
                            </button>
                            <button type="button" onclick="insertMarkdown('[', '](url)', 'Link')"
                                class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50">
                                <i class="fas fa-link"></i> Link
                            </button>
                            <button type="button" onclick="insertMarkdown('![', '](image-url)', 'Image')"
                                class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50">
                                <i class="fas fa-image"></i> Image
                            </button>
                        </div>

                        <!-- HTML Toolbar -->
                        <div id="htmlToolbar"
                            class="bg-gray-50 border border-gray-200 rounded-t-lg p-3 flex flex-wrap gap-2 hidden">
                            <button type="button" onclick="insertHTML('<strong>', '</strong>', 'Bold Text')"
                                class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50">
                                <i class="fas fa-bold"></i> Bold
                            </button>
                            <button type="button" onclick="insertHTML('<em>', '</em>', 'Italic Text')"
                                class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50">
                                <i class="fas fa-italic"></i> Italic
                            </button>
                            <button type="button" onclick="insertHTML('<h2>', '</h2>', 'Heading 2')"
                                class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50">
                                <i class="fas fa-heading"></i> H2
                            </button>
                            <button type="button" onclick="insertHTML('<h3>', '</h3>', 'Heading 3')"
                                class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50">
                                <i class="fas fa-heading"></i> H3
                            </button>
                            <button type="button" onclick="insertHTML('<ul><li>', '</li></ul>', 'List Item')"
                                class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50">
                                <i class="fas fa-list-ul"></i> List
                            </button>
                            <button type="button" onclick="insertHTML('<ol><li>', '</li></ol>', 'Numbered Item')"
                                class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50">
                                <i class="fas fa-list-ol"></i> Numbered
                            </button>
                            <button type="button"
                                onclick="insertHTML('<pre><code class=\"language-bash\">', '</code></pre>', 'Bash Code')"
                                class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50">
                                <i class="fas fa-code"></i> Bash
                            </button>
                            <button type="button"
                                onclick="insertHTML('<pre><code class=\"language-html\">', '</code></pre>', 'HTML Code')"
                                class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50">
                                <i class="fas fa-code"></i> HTML
                            </button>
                            <button type="button" onclick="insertHTML('<a href=\"\">', '</a>', 'Link')"
                                class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50">
                                <i class="fas fa-link"></i> Link
                            </button>
                            <button type="button" onclick="insertHTML('<img src=\"\" alt=\"\">', '', 'Image')"
                                class="px-3 py-1 bg-white border border-gray-300 rounded text-sm hover:bg-gray-50">
                                <i class="fas fa-image"></i> Image
                            </button>
                        </div>

                        <!-- Text Toolbar -->
                        <div id="textToolbar"
                            class="bg-gray-50 border border-gray-200 rounded-t-lg p-3 flex flex-wrap gap-2 hidden">
                            <div class="text-sm text-gray-600">
                                <i class="fas fa-info-circle mr-2"></i>
                                Plain text format. Line breaks will be automatically converted to HTML.
                            </div>
                        </div>

                        <!-- Content Textarea -->
                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">Content
                                *</label>
                            <textarea name="content" id="content" rows="25" required
                                class="w-full px-4 py-3 border border-gray-300 rounded-b-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent font-mono text-sm"
                                placeholder="Write your blog post content here using markdown...">{{ old('content') }}</textarea>
                            @error('content')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Content Preview Toggle -->
                        <div class="mt-3 flex justify-end">
                            <button type="button" id="previewToggle"
                                class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                                <i class="fas fa-eye mr-2"></i>Preview Content
                            </button>
                        </div>

                        <!-- Content Preview -->
                        <div id="contentPreview" class="hidden mt-4 p-6 bg-gray-50 rounded-lg border">
                            <h4 class="text-lg font-semibold text-gray-900 mb-3">Content Preview</h4>
                            <div id="previewContent" class="prose prose-lg max-w-none"></div>
                        </div>
                    </div>

                    <!-- Image Upload Section -->
                    <div class="mb-8 bg-gradient-to-r from-green-50 to-blue-50 rounded-xl p-6 border border-green-200">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <i class="fas fa-image text-green-600 text-2xl mr-3"></i>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900">Upload Images</h3>
                                    <p class="text-xs text-gray-600">Upload images for your blog post</p>
                                </div>
                            </div>
                        </div>

                        <!-- Upload Section -->
                        <div class="bg-white p-4 rounded-lg border">
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Choose Image</label>
                                <input type="file" id="imageUpload" accept="image/*"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            </div>
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Image Context
                                    (Optional)</label>
                                <input type="text" id="imageContext"
                                    placeholder="Describe what this image shows..."
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                                <p class="text-xs text-gray-500 mt-1">Help provide better captions</p>
                            </div>
                            <button type="button" id="analyzeUploadBtn"
                                class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-lg">
                                <i class="fas fa-upload mr-2"></i>Upload Image
                            </button>
                        </div>

                        <!-- Uploaded Images Gallery -->
                        <div id="imageGallery" class="hidden mt-4">
                            <h4 class="text-sm font-semibold text-gray-900 mb-3">Uploaded Images</h4>
                            <div id="imageGalleryContent" class="grid grid-cols-2 gap-4">
                                <!-- Images will be inserted here -->
                            </div>
                        </div>
                    </div>

                    <!-- Monetization Settings Section -->
                    @if (auth()->user()->isAdmin())
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Monetization Settings
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Monetization Type -->
                                <div>
                                    <label for="monetization_type"
                                        class="block text-sm font-medium text-gray-700 mb-2">Monetization Type</label>
                                    <select name="monetization_type" id="monetization_type"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                        @foreach (\App\Models\BlogPost::MONETIZATION_TYPES as $type)
                                            <option value="{{ $type }}"
                                                {{ old('monetization_type') == $type ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('_', ' ', $type)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Ad Type -->
                                <div>
                                    <label for="ad_type" class="block text-sm font-medium text-gray-700 mb-2">Ad
                                        Type</label>
                                    <select name="ad_type" id="ad_type"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                        @foreach (\App\Models\BlogPost::AD_TYPES as $type)
                                            <option value="{{ $type }}"
                                                {{ old('ad_type') == $type ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('_', ' ', $type)) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Earning Rates -->
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-3">Earning Rates (per
                                        view)</label>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                        <div>
                                            <label for="earning_rate_less_2min"
                                                class="block text-xs text-gray-600 mb-1">Less than 2 min</label>
                                            <input type="number" name="earning_rate_less_2min"
                                                id="earning_rate_less_2min"
                                                value="{{ old('earning_rate_less_2min') }}" step="0.0001"
                                                min="0"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                        </div>
                                        <div>
                                            <label for="earning_rate_2_5min"
                                                class="block text-xs text-gray-600 mb-1">2-5 minutes</label>
                                            <input type="number" name="earning_rate_2_5min" id="earning_rate_2_5min"
                                                value="{{ old('earning_rate_2_5min') }}" step="0.0001"
                                                min="0"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                        </div>
                                        <div>
                                            <label for="earning_rate_more_5min"
                                                class="block text-xs text-gray-600 mb-1">More than 5 min</label>
                                            <input type="number" name="earning_rate_more_5min"
                                                id="earning_rate_more_5min"
                                                value="{{ old('earning_rate_more_5min') }}" step="0.0001"
                                                min="0"
                                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- For non-admin users, show global settings summary -->
                        <div class="mb-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Monetization Settings
                            </h3>
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <p class="text-sm text-blue-800 mb-2">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    Global monetization settings will be applied to your blog post:
                                </p>
                                <div class="text-sm text-blue-700 space-y-1">
                                    <p><strong>Type:</strong>
                                        {{ $globalSettings->default_blog_monetization_type ?? 'Time-based' }}</p>
                                    <p><strong>Ad Type:</strong>
                                        {{ $globalSettings->default_blog_ad_type ?? 'Banner ads' }}</p>
                                    <p><strong>Earning Rates:</strong>
                                        {{ $globalSettings->default_blog_earning_rate_less_2min_inr ?? '0.001' }} -
                                        {{ $globalSettings->default_blog_earning_rate_more_5min_inr ?? '0.005' }} per
                                        view</p>
                                </div>
                            </div>

                            <!-- Hidden fields for global settings -->
                            <input type="hidden" name="monetization_type"
                                value="{{ $globalSettings->default_blog_monetization_type ?? 'time_based' }}">
                            <input type="hidden" name="ad_type"
                                value="{{ $globalSettings->default_blog_ad_type ?? 'banner_ads' }}">
                            <input type="hidden" name="earning_rate_less_2min"
                                value="{{ $globalSettings->default_blog_earning_rate_less_2min_inr ?? '0.001' }}">
                            <input type="hidden" name="earning_rate_2_5min"
                                value="{{ $globalSettings->default_blog_earning_rate_2_5min_inr ?? '0.002' }}">
                            <input type="hidden" name="earning_rate_more_5min"
                                value="{{ $globalSettings->default_blog_earning_rate_more_5min_inr ?? '0.005' }}">
                        </div>
                    @endif

                    <!-- Publishing Options -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 border-b pb-2">Publishing Options</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Status -->
                            <div>
                                <label for="status"
                                    class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                <select name="status" id="status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft
                                    </option>
                                    <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>
                                        Published</option>
                                </select>
                            </div>

                            <!-- Publish Date -->
                            <div>
                                <label for="published_at" class="block text-sm font-medium text-gray-700 mb-2">Publish
                                    Date</label>
                                <input type="datetime-local" name="published_at" id="published_at"
                                    value="{{ old('published_at') }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="saveDraft()"
                            class="px-6 py-3 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition-colors">
                            <i class="fas fa-save mr-2"></i>Save as Draft
                        </button>
                        <button type="submit"
                            class="px-6 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                            <i class="fas fa-paper-plane mr-2"></i>Publish Post
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Markdown Preview Script -->
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script>
        // Load template from localStorage if available
        document.addEventListener('DOMContentLoaded', function() {
            const templateData = localStorage.getItem('blogTemplate');
            if (templateData) {
                try {
                    const data = JSON.parse(templateData);
                    console.log('Loading template:', data.type);

                    // Auto-select post type based on template type
                    if (data.type) {
                        const typeMapping = {
                            'tutorial': 'tutorial',
                            'howto': 'guide',
                            'review': 'review',
                            'news': 'news',
                            'list': 'list',
                            'casestudy': 'case_study',
                            'businesspage': 'business_page',
                            'companyportfolio': 'company_portfolio',
                            'personalportfolio': 'personal_portfolio'
                        };

                        const postType = typeMapping[data.type] || 'article';
                        const typeSelect = document.getElementById('type');

                        // Check if the option exists before setting
                        if (Array.from(typeSelect.options).some(opt => opt.value === postType)) {
                            typeSelect.value = postType;
                            console.log('Auto-selected post type:', postType);
                        } else {
                            console.warn('Post type not found in options:', postType);
                            console.log('Available options:', Array.from(typeSelect.options).map(o => o.value));
                        }
                    }

                    // Load title if there's one in the template
                    if (data.title) {
                        const titleMatch = data.content.match(/^# (.+)/);
                        if (titleMatch) {
                            document.getElementById('title').value = titleMatch[1].replace('| Step-by-Step Guide',
                                    '').replace('Complete Tutorial Template', '').replace('How-to Guide Template',
                                    '').replace('Product/Service Review Template', '').replace(
                                    'News Article Template', '').replace('Top 10 List Template', '').replace(
                                    'Case Study Template', '').replace('Business Page Template', '').replace(
                                    'Company Portfolio Template', '').replace('Personal Portfolio Template', '')
                                .trim();
                        }
                    }

                    // Load content
                    document.getElementById('content').value = data.content;

                    // Set excerpt based on first paragraph
                    const excerptMatch = data.content.match(/\n\n\[([^\]]+)\]/);
                    if (excerptMatch) {
                        document.getElementById('excerpt').value = excerptMatch[1];
                    }

                    // Remove template data from localStorage
                    localStorage.removeItem('blogTemplate');

                    console.log('Template loaded successfully');
                } catch (error) {
                    console.error('Error loading template:', error);
                }
            }
        });

        // AI Markdown Assistant
        document.getElementById('generateAIContent').addEventListener('click', async function() {
            const contentField = document.getElementById('content');
            const action = document.getElementById('ai_action').value;
            const context = document.getElementById('ai_context').value;
            const aiModel = document.getElementById('ai_model').value;

            // Get length and tone for full blog generation
            const aiLength = document.getElementById('ai_length') ? document.getElementById('ai_length').value : 'medium';
            const aiTone = document.getElementById('ai_tone') ? document.getElementById('ai_tone').value : 'professional';

            // Get selected text
            const start = contentField.selectionStart;
            const end = contentField.selectionEnd;
            const selectedText = contentField.value.substring(start, end);

            // Validation
            if (action === 'generate_blog') {
                const title = document.getElementById('title').value;
                if (!title) {
                    alert('Please enter a title first');
                    return;
                }
            } else if (['improve', 'optimize', 'expand', 'simplify'].includes(action) && !selectedText) {
                alert('Please select the text you want to ' + action + ' or choose "Generate New Section"');
                return;
            } else if (action === 'generate' && !context) {
                alert('Please describe what section you want to generate');
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

                console.log('Sending AI request:', requestBody);

                const response = await fetch('{{ route('ai.generate-blog') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(requestBody)
                });

                // Check if response is OK
                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('Server error response:', errorText);
                    throw new Error('Server returned error: ' + response.status);
                }

                const data = await response.json();
                console.log('AI response:', data);

                if (data.success) {
                    // Show content in modal with copy button
                    showAIContentModal(data.content, data.excerpt, action);

                    // Clear context field
                    document.getElementById('ai_context').value = '';
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Full error details:', error);
                console.error('Error message:', error.message);
                console.error('Error stack:', error.stack);
                alert('Error: ' + error.message);
            } finally {
                this.disabled = false;
                document.getElementById('ai_generating').classList.add('hidden');
            }
        });

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

        // HTML insertion functions
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

        // Content type change handler
        document.getElementById('content_type').addEventListener('change', function() {
            const contentType = this.value;
            const markdownToolbar = document.getElementById('markdownToolbar');
            const htmlToolbar = document.getElementById('htmlToolbar');
            const textToolbar = document.getElementById('textToolbar');
            const contentTextarea = document.getElementById('content');
            const previewToggle = document.getElementById('previewToggle');

            // Hide all toolbars
            markdownToolbar.classList.add('hidden');
            htmlToolbar.classList.add('hidden');
            textToolbar.classList.add('hidden');

            // Show appropriate toolbar
            switch (contentType) {
                case 'markdown':
                    markdownToolbar.classList.remove('hidden');
                    previewToggle.classList.remove('hidden');
                    contentTextarea.placeholder = 'Write your blog post content here using markdown...';
                    break;
                case 'html':
                    htmlToolbar.classList.remove('hidden');
                    previewToggle.classList.remove('hidden');
                    contentTextarea.placeholder = 'Write your blog post content here using HTML...';
                    break;
                case 'text':
                    textToolbar.classList.remove('hidden');
                    previewToggle.classList.add('hidden');
                    contentTextarea.placeholder = 'Write your blog post content here as plain text...';
                    break;
            }
        });

        // Content preview functionality
        document.getElementById('previewToggle').addEventListener('click', function() {
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
                        // Check if content looks like markdown (contains #, **, etc.)
                        const hasMarkdownSyntax = /^#+\s|^\*\*|```|\[.*\]\(.*\)/.test(content);
                        if (hasMarkdownSyntax) {
                            // Convert markdown to HTML for preview
                            htmlContent = marked.parse(content);
                        } else {
                            // Treat as raw HTML
                            htmlContent = content;
                        }
                        break;
                    case 'text':
                        htmlContent = content.replace(/\n/g, '<br>'); // Convert line breaks to <br>
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

        // Auto-save draft functionality
        let autoSaveTimer;
        document.getElementById('content').addEventListener('input', function() {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(function() {
                // Auto-save draft logic here
                console.log('Auto-saving draft...');
            }, 3000);
        });

        // Save draft function
        function saveDraft() {
            document.getElementById('status').value = 'draft';
            document.querySelector('form').submit();
        }

        // Enhanced textarea with line numbers
        const textarea = document.getElementById('content');
        textarea.addEventListener('scroll', function() {
            // Sync scroll position if needed
        });


        // Upload and Analyze Image
        document.getElementById('analyzeUploadBtn').addEventListener('click', async function() {
            const fileInput = document.getElementById('imageUpload');
            const context = document.getElementById('imageContext').value;

            if (!fileInput.files[0]) {
                alert('Please select an image to upload');
                return;
            }

            this.disabled = true;
            this.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Uploading image...';

            const formData = new FormData();
            formData.append('image', fileInput.files[0]);
            formData.append('context', context);

            try {
                const response = await fetch('{{ route('ai.analyze-image') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    displayImageResult(data.imagePath, data.caption, data.altText);
                    alert('Image uploaded successfully!');
                    // Clear file input
                    fileInput.value = '';
                    document.getElementById('imageContext').value = '';
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('An error occurred while uploading the image');
            } finally {
                this.disabled = false;
                this.innerHTML = '<i class="fas fa-upload mr-2"></i>Upload Image';
            }
        });

        // Display image result
        function displayImageResult(imagePath, caption, altText) {
            console.log('Displaying image:', imagePath);

            const gallery = document.getElementById('imageGalleryContent');
            const imageDiv = document.createElement('div');
            imageDiv.className = 'bg-white p-3 rounded-lg border';

            // Create image container with fallback
            const imageContainer = document.createElement('div');
            imageContainer.className = 'w-full h-40 rounded mb-2 overflow-hidden bg-gray-100 relative';

            const img = document.createElement('img');
            img.src = imagePath;
            img.alt = altText || 'Generated image';
            img.className = 'w-full h-full object-cover';
            img.onerror = function() {
                console.error('Failed to load image:', imagePath);
                // Replace with placeholder
                const placeholder = document.createElement('div');
                placeholder.className = 'w-full h-full bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center';
                placeholder.innerHTML = '<span class="text-gray-500 text-xs">Image loading...</span>';
                imageContainer.replaceChild(placeholder, img);
            };

            imageContainer.appendChild(img);

            const captionDiv = document.createElement('div');
            captionDiv.className = 'text-xs text-gray-600 mb-2';
            captionDiv.innerHTML = '<strong>Caption:</strong> ' + (caption || 'No caption');

            const altDiv = document.createElement('div');
            altDiv.className = 'text-xs text-gray-500 mb-2';
            altDiv.innerHTML = '<strong>Alt Text:</strong> ' + (altText || 'No alt text');

            const buttonDiv = document.createElement('div');
            buttonDiv.className = 'flex gap-2';

            const insertBtn = document.createElement('button');
            insertBtn.className = 'flex-1 bg-green-600 text-white text-xs py-1 px-2 rounded hover:bg-green-700';
            insertBtn.innerHTML = '<i class="fas fa-plus mr-1"></i>Insert';
            insertBtn.onclick = function() {
                insertImageIntoContent(imagePath, altText || 'Generated image');
            };

            const deleteBtn = document.createElement('button');
            deleteBtn.className = 'bg-red-600 text-white text-xs py-1 px-2 rounded hover:bg-red-700';
            deleteBtn.innerHTML = '<i class="fas fa-trash"></i>';
            deleteBtn.onclick = function() {
                imageDiv.remove();
            };

            buttonDiv.appendChild(insertBtn);
            buttonDiv.appendChild(deleteBtn);

            imageDiv.appendChild(imageContainer);
            imageDiv.appendChild(captionDiv);
            imageDiv.appendChild(altDiv);
            imageDiv.appendChild(buttonDiv);

            gallery.appendChild(imageDiv);
            document.getElementById('imageGallery').classList.remove('hidden');
        }

        // Insert image into content
        window.insertImageIntoContent = function(imagePath, altText) {
            const contentField = document.getElementById('content');
            const contentType = document.getElementById('content_type').value;

            let imageMarkdown = '';
            if (contentType === 'markdown') {
                imageMarkdown = `![${altText}](${imagePath})`;
            } else if (contentType === 'html') {
                imageMarkdown = `<img src="${imagePath}" alt="${altText}" class="w-full rounded-lg">`;
            } else {
                imageMarkdown = `Image: ${imagePath} (${altText})`;
            }

            const cursorPos = contentField.selectionStart;
            const textBefore = contentField.value.substring(0, cursorPos);
            const textAfter = contentField.value.substring(cursorPos);
            contentField.value = textBefore + '\n' + imageMarkdown + '\n' + textAfter;

            alert('Image inserted into content!');
        };
    </script>

    <style>
        /* Custom styling for the markdown toolbar */
        .markdown-toolbar button:hover {
            background-color: #f3f4f6;
        }

        /* Enhanced textarea styling */
        #content {
            font-family: 'Monaco', 'Menlo', 'Ubuntu Mono', monospace;
            line-height: 1.6;
            tab-size: 4;
        }

        /* Preview styling */
        #previewContent {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }

        #previewContent pre {
            background: #1f2937;
            color: #f9fafb;
            padding: 16px;
            border-radius: 8px;
            overflow-x: auto;
        }

        #previewContent code {
            background: #f3f4f6;
            padding: 2px 6px;
            border-radius: 4px;
            font-family: monospace;
        }
    </style>

    <!-- AI Content Modal -->
    <div id="aiContentModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden flex items-center justify-center">
        <div class="bg-white rounded-lg max-w-4xl w-full mx-4 max-h-[90vh] flex flex-col">
            <div class="flex items-center justify-between p-6 border-b">
                <h3 class="text-xl font-bold text-gray-900">AI Generated Content</h3>
                <div class="flex gap-2">
                    <button onclick="copyAIContent()" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                        <i class="fas fa-copy mr-2"></i>Copy Content
                    </button>
                    <button onclick="closeAIContentModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="p-6 overflow-y-auto flex-1">
                <div id="aiGeneratedContent" class="prose max-w-none whitespace-pre-wrap"></div>
            </div>
            <div class="flex items-center justify-between p-6 border-t bg-gray-50">
                <div class="flex gap-2">
                    <button onclick="insertAIContent()" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        <i class="fas fa-plus mr-2"></i>Insert into Editor
                    </button>
                    <button onclick="replaceAIContent()" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i class="fas fa-sync mr-2"></i>Replace Content
                    </button>
                </div>
                <button onclick="regenerateAIContent()" class="px-6 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                    <i class="fas fa-redo mr-2"></i>Regenerate
                </button>
            </div>
        </div>
    </div>

    <script>
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
            const btn = event.target.closest('button');
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-check mr-2"></i>Copied!';
            btn.classList.add('bg-green-600');
            btn.classList.remove('bg-purple-600');
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.classList.remove('bg-green-600');
                btn.classList.add('bg-purple-600');
            }, 2000);
        }

        function insertAIContent() {
            const contentField = document.getElementById('content');
            const currentContent = contentField.value;
            contentField.value = currentContent + (currentContent ? '\n\n' : '') + aiGeneratedContent;

            if (aiGeneratedExcerpt) {
                document.getElementById('excerpt').value = aiGeneratedExcerpt;
            }

            closeAIContentModal();
        }

        function replaceAIContent() {
            document.getElementById('content').value = aiGeneratedContent;

            if (aiGeneratedExcerpt) {
                document.getElementById('excerpt').value = aiGeneratedExcerpt;
            }

            closeAIContentModal();
        }

        function regenerateAIContent() {
            closeAIContentModal();
            document.getElementById('generateAIContent').click();
        }

        // Close modal on outside click
        document.getElementById('aiContentModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAIContentModal();
            }
        });
    </script>

</x-app-layout>
