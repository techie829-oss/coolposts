<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <nav class="flex mb-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3 text-xs font-medium text-gray-500">
                        <li class="inline-flex items-center">
                            <a href="{{ route('dashboard') }}" class="hover:text-purple-600 transition-colors">
                                <i class="fas fa-home mr-2"></i>Dashboard
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2 text-[10px]"></i>
                                <a href="{{ route('blog.manage') }}"
                                    class="hover:text-purple-600 transition-colors">Manage Posts</a>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <i class="fas fa-chevron-right text-gray-400 mx-2 text-[10px]"></i>
                                <span class="text-gray-900 truncate">Create New Post</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h2 class="font-extrabold text-3xl text-gray-900 leading-tight tracking-tight">
                    {{ __('Create Blog Post') }}
                </h2>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('blog.manage') }}"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">
                    <i class="fas fa-tasks mr-2 text-gray-400 text-xs"></i>
                    Manage Posts
                </a>
                <button type="submit" form="blog-form"
                    class="inline-flex items-center px-6 py-2.5 bg-brand-gradient text-white rounded-xl text-sm font-bold shadow-lg shadow-purple-200 hover:shadow-purple-300 hover:scale-[1.02] active:scale-[0.98] transition-all">
                    <i class="fas fa-save mr-2"></i>
                    Save Draft
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Global Error Summary -->
            @if ($errors->any())
                <div class="mb-8 rounded-2xl bg-red-50 p-4 border border-red-100 shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-red-800">
                                Whoops! There were some problems with your input.
                            </h3>
                            <div class="mt-2 text-sm text-red-700">
                                <ul role="list" class="list-disc pl-5 space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('blog.store') }}" enctype="multipart/form-data" id="blog-form">
                @csrf

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    <!-- LEFT COLUMN: Main Content (8 cols) -->
                    <div class="lg:col-span-8 space-y-8">

                        <!-- Main Post Section -->
                        <div
                            class="bg-white/70 backdrop-blur-xl border border-white/20 rounded-2xl shadow-xl shadow-slate-200/50 overflow-hidden">
                            <div class="p-6 space-y-6">
                                <!-- Title Input -->
                                <div>
                                    <label for="title"
                                        class="block text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-2 px-1">
                                        Post Title <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                                        required
                                        class="w-full px-4 py-2.5 text-lg font-bold text-gray-900 bg-gray-50/50 border border-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 focus:bg-white transition-all placeholder:text-gray-300"
                                        placeholder="Enter a captivating title...">
                                    @error('title')
                                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                    <!-- Type -->
                                    <div class="space-y-3">
                                        <label for="type"
                                            class="block text-sm font-bold text-gray-500 uppercase tracking-widest px-1">
                                            Post Type <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative group">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 group-focus-within:text-purple-500 transition-colors">
                                                <i class="fas fa-th-large"></i>
                                            </div>
                                            <select name="type" id="type" required
                                                class="w-full pl-10 pr-4 py-2 bg-gray-50/50 border border-gray-100 rounded-lg focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 focus:bg-white transition-all appearance-none cursor-pointer text-sm font-semibold">
                                                <option value="">Select Type</option>
                                                @foreach (\App\Models\BlogPost::TYPES as $key => $label)
                                                    <option value="{{ $key }}"
                                                        {{ old('type') == $key ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div
                                                class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400">
                                                <i class="fas fa-chevron-down text-xs"></i>
                                            </div>
                                        </div>
                                        @error('type')
                                            <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Content Format -->
                                    <div class="space-y-3">
                                        <label for="content_type"
                                            class="block text-sm font-bold text-gray-500 uppercase tracking-widest px-1">
                                            Content Format <span class="text-red-500">*</span>
                                        </label>
                                        <div class="relative group">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 group-focus-within:text-purple-500 transition-colors">
                                                <i class="fas fa-code"></i>
                                            </div>
                                            <select name="content_type" id="content_type" required
                                                class="w-full pl-10 pr-4 py-2 bg-gray-50/50 border border-gray-100 rounded-lg focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 focus:bg-white transition-all appearance-none cursor-pointer text-sm font-semibold">
                                                @foreach (\App\Models\BlogPost::CONTENT_TYPES as $type => $label)
                                                    <option value="{{ $type }}"
                                                        {{ old('content_type', 'markdown') == $type ? 'selected' : '' }}>
                                                        {{ $label }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <div
                                                class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400">
                                                <i class="fas fa-chevron-down text-xs"></i>
                                            </div>
                                        </div>
                                        @error('content_type')
                                            <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Category -->
                                    <div class="space-y-3">
                                        <label for="category"
                                            class="block text-sm font-bold text-gray-500 uppercase tracking-widest px-1">
                                            Category
                                        </label>
                                        <div class="relative group">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 group-focus-within:text-purple-500 transition-colors">
                                                <i class="fas fa-tag"></i>
                                            </div>
                                            <input type="text" name="category" id="category"
                                                value="{{ old('category') }}"
                                                class="w-full pl-10 pr-4 py-2 bg-gray-50/50 border border-gray-100 rounded-lg focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 focus:bg-white transition-all text-sm font-semibold"
                                                placeholder="e.g., Technology">
                                        </div>
                                        @error('category')
                                            <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Tags -->
                                    <div class="space-y-3">
                                        <label for="tags"
                                            class="block text-sm font-bold text-gray-500 uppercase tracking-widest px-1">
                                            Tags
                                        </label>
                                        <div class="relative group">
                                            <div
                                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 group-focus-within:text-purple-500 transition-colors">
                                                <i class="fas fa-hashtag"></i>
                                            </div>
                                            <input type="text" name="tags" id="tags"
                                                value="{{ is_array(old('tags')) ? implode(', ', old('tags')) : old('tags') }}"
                                                class="w-full pl-10 pr-4 py-2 bg-gray-50/50 border border-gray-100 rounded-lg focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 focus:bg-white transition-all text-sm font-semibold"
                                                placeholder="laravel, php, web-development">
                                        </div>
                                        @error('tags')
                                            <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Excerpt -->
                                <div class="space-y-3 px-1 pt-2">
                                    <label for="excerpt"
                                        class="block text-sm font-bold text-gray-500 uppercase tracking-widest">
                                        Short Excerpt
                                    </label>
                                    <textarea name="excerpt" id="excerpt" rows="3"
                                        class="w-full px-4 py-3 bg-gray-50/50 border border-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 focus:bg-white transition-all resize-none placeholder:text-gray-400 text-sm font-medium"
                                        placeholder="A brief summary of your post for link listings...">{{ old('excerpt') }}</textarea>
                                    @error('excerpt')
                                        <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- AI Writing Assistant Section -->
                        @php
                            $aiSettings = \App\Models\AiSetting::getSettings();
                            $user = auth()->user();
                            $userCanUseAI = $user->isAdmin() || ($user->canUseAi() && $user->canUseAiBlogGeneration());
                        @endphp
                        @if ($aiSettings->blog_generation_enabled && $userCanUseAI)
                            <div class="relative overflow-hidden group">
                                <div
                                    class="absolute inset-0 bg-brand-gradient opacity-[0.03] group-hover:opacity-[0.05] transition-opacity duration-500">
                                </div>
                                <div
                                    class="absolute -right-12 -top-12 w-48 h-48 bg-purple-500/10 rounded-full blur-3xl">
                                </div>
                                <div
                                    class="absolute -left-12 -bottom-12 w-48 h-48 bg-pink-500/10 rounded-full blur-3xl">
                                </div>

                                <div
                                    class="relative p-6 border border-purple-100 rounded-2xl bg-white/40 backdrop-blur-md shadow-sm">
                                    <div class="flex items-center justify-between mb-6">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-xl bg-brand-gradient flex items-center justify-center shadow-lg shadow-purple-200">
                                                <i class="fas fa-magic text-white text-lg"></i>
                                            </div>
                                            <div>
                                                <h3 class="text-lg font-extrabold text-gray-900 tracking-tight">AI
                                                    Assistant</h3>
                                                <p class="text-[10px] text-gray-500 font-medium">Craft perfect content
                                                    seamlessly</p>
                                            </div>
                                        </div>
                                        <span
                                            class="px-3 py-1 bg-purple-100 text-purple-700 text-xs font-bold rounded-full uppercase tracking-widest">Pro
                                            Feature</span>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                                        <div class="space-y-2">
                                            <label
                                                class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest px-1">I
                                                want to...</label>
                                            <select id="ai_action"
                                                class="w-full px-3 py-2 bg-white border border-gray-100 rounded-lg focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 text-xs font-semibold transition-all shadow-sm">
                                                <option value="generate_blog">📖 Generate Blog</option>
                                                <option value="improve">✨ Improve Selection</option>
                                                <option value="optimize">🚀 Optimize content</option>
                                                <option value="expand">➕ Expand section</option>
                                                <option value="simplify">🔧 Simplify words</option>
                                                <option value="generate">📝 New section</option>
                                            </select>
                                        </div>
                                        <div class="space-y-2">
                                            <label
                                                class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest px-1">Using
                                                Model</label>
                                            <select id="ai_model"
                                                class="w-full px-3 py-2 bg-white border border-gray-100 rounded-lg focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 text-xs font-semibold transition-all shadow-sm">
                                                <option value="gemini-2.5-flash" selected>Gemini 1.5 Pro</option>
                                                <option value="gemini-2.0-flash-exp">Gemini 2.0 Exp</option>
                                            </select>
                                        </div>
                                        <div class="space-y-2">
                                            <label
                                                class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest px-1">Desired
                                                Length</label>
                                            <select id="ai_length"
                                                class="w-full px-3 py-2 bg-white border border-gray-100 rounded-lg focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 text-xs font-semibold transition-all shadow-sm">
                                                <option value="short">Short</option>
                                                <option value="medium" selected>Medium</option>
                                                <option value="long">Long</option>
                                            </select>
                                        </div>
                                        <div class="space-y-2">
                                            <label
                                                class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest px-1">Writing
                                                Tone</label>
                                            <select id="ai_tone"
                                                class="w-full px-3 py-2 bg-white border border-gray-100 rounded-lg focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 text-xs font-semibold transition-all shadow-sm">
                                                <option value="professional" selected>Professional</option>
                                                <option value="casual">Casual</option>
                                                <option value="formal">Formal</option>
                                                <option value="friendly">Friendly</option>
                                                <option value="technical">Technical</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label
                                            class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest px-1 mb-2">Instructions
                                            / Context</label>
                                        <div class="relative group">
                                            <textarea id="ai_context" rows="2" placeholder="Tell the AI what to focus on..."
                                                class="w-full px-4 py-2.5 bg-white border border-gray-100 rounded-xl focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 text-xs transition-all shadow-sm resize-none"></textarea>
                                            <div
                                                class="absolute right-3 bottom-2.5 text-[9px] text-gray-400 font-bold uppercase tracking-wider">
                                                Alt + Enter to Generate</div>
                                        </div>
                                    </div>

                                    <button type="button" id="generateAIContent"
                                        class="w-full bg-brand-gradient hover:shadow-md hover:shadow-purple-100 text-white py-3 px-6 rounded-xl font-bold flex items-center justify-center gap-2 transition-all transform hover:scale-[1.01] active:translate-y-0.5 text-sm">
                                        <i class="fas fa-magic text-xs"></i>
                                        <span>Magically Generate Content</span>
                                    </button>

                                    <div id="ai_generating" class="hidden mt-4">
                                        <div class="flex items-center justify-center gap-3 text-purple-600 font-bold">
                                            <div class="flex space-x-1">
                                                <div class="w-1.5 h-1.5 bg-purple-500 rounded-full animate-bounce"
                                                    style="animation-delay: 0.1s"></div>
                                                <div class="w-1.5 h-1.5 bg-purple-500 rounded-full animate-bounce"
                                                    style="animation-delay: 0.2s"></div>
                                                <div class="w-1.5 h-1.5 bg-purple-500 rounded-full animate-bounce"
                                                    style="animation-delay: 0.3s"></div>
                                            </div>
                                            <span class="text-sm uppercase tracking-widest">Optimizing
                                                masterpiece...</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Content Editor -->
                        <div
                            class="bg-white/70 backdrop-blur-xl border border-white/20 rounded-2xl shadow-xl shadow-slate-200/50 overflow-hidden">
                            <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-9 h-9 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600">
                                        <i class="fas fa-file-alt text-base"></i>
                                    </div>
                                    <h3 class="text-lg font-extrabold text-gray-900 tracking-tight">Content Editor</h3>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button type="button" id="previewToggle"
                                        class="inline-flex items-center px-4 py-2 bg-purple-50 text-purple-700 rounded-xl text-sm font-bold hover:bg-purple-100 transition-all font-semibold">
                                        <i class="fas fa-eye mr-2"></i>Preview
                                    </button>
                                </div>
                            </div>

                            <div class="p-6">
                                <label for="content"
                                    class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-4 px-1">
                                    Storytelling Area <span class="text-red-500">*</span>
                                </label>

                                <div class="relative mb-0 z-10">
                                    <!-- Toolbars -->
                                    <div id="markdownToolbar"
                                        class="flex flex-wrap gap-1.5 p-2 bg-gray-50/80 backdrop-blur-sm border border-gray-100 rounded-t-2xl">
                                        <button type="button" onclick="insertMarkdown('**', '**', 'Bold')"
                                            class="w-9 h-9 flex items-center justify-center bg-white border border-gray-100 rounded-lg text-gray-600 hover:text-purple-600 hover:border-purple-200 transition-all shadow-sm">
                                            <i class="fas fa-bold"></i>
                                        </button>
                                        <button type="button" onclick="insertMarkdown('*', '*', 'Italic')"
                                            class="w-9 h-9 flex items-center justify-center bg-white border border-gray-100 rounded-lg text-gray-600 hover:text-purple-600 hover:border-purple-200 transition-all shadow-sm">
                                            <i class="fas fa-italic"></i>
                                        </button>
                                        <div class="w-px h-6 bg-gray-200 mx-1 my-auto"></div>
                                        <button type="button" onclick="insertMarkdown('## ', '', 'Heading 2')"
                                            class="w-9 h-9 flex items-center justify-center bg-white border border-gray-100 rounded-lg text-gray-600 hover:text-purple-600 hover:border-purple-200 transition-all shadow-sm">
                                            <i class="fas fa-heading"></i>
                                        </button>
                                        <button type="button" onclick="insertMarkdown('### ', '', 'Heading 3')"
                                            class="w-9 h-9 flex items-center justify-center bg-white border border-gray-100 rounded-lg text-gray-600 hover:text-purple-600 hover:border-purple-200 transition-all shadow-sm text-xs">
                                            <i class="fas fa-heading scale-75"></i>
                                        </button>
                                        <div class="w-px h-6 bg-gray-200 mx-1 my-auto"></div>
                                        <button type="button" onclick="insertMarkdown('- ', '', 'List item')"
                                            class="w-9 h-9 flex items-center justify-center bg-white border border-gray-100 rounded-lg text-gray-600 hover:text-purple-600 hover:border-purple-200 transition-all shadow-sm">
                                            <i class="fas fa-list-ul"></i>
                                        </button>
                                        <button type="button" onclick="insertMarkdown('1. ', '', 'Numbered item')"
                                            class="w-9 h-9 flex items-center justify-center bg-white border border-gray-100 rounded-lg text-gray-600 hover:text-purple-600 hover:border-purple-200 transition-all shadow-sm">
                                            <i class="fas fa-list-ol"></i>
                                        </button>
                                        <div class="w-px h-6 bg-gray-200 mx-1 my-auto"></div>
                                        <button type="button" onclick="insertMarkdown('```bash\n', '\n```', 'code')"
                                            class="w-9 h-9 flex items-center justify-center bg-white border border-gray-100 rounded-lg text-gray-600 hover:text-purple-600 hover:border-purple-200 transition-all shadow-sm">
                                            <i class="fas fa-code"></i>
                                        </button>
                                        <button type="button" onclick="insertMarkdown('[', '](url)', 'Link')"
                                            class="w-9 h-9 flex items-center justify-center bg-white border border-gray-100 rounded-lg text-gray-600 hover:text-purple-600 hover:border-purple-200 transition-all shadow-sm">
                                            <i class="fas fa-link text-xs"></i>
                                        </button>
                                        <button type="button"
                                            onclick="insertMarkdown('![', '](image-url)', 'Alt text')"
                                            class="w-9 h-9 flex items-center justify-center bg-white border border-gray-100 rounded-lg text-gray-600 hover:text-purple-600 hover:border-purple-200 transition-all shadow-sm">
                                            <i class="fas fa-image text-xs"></i>
                                        </button>
                                    </div>

                                    <textarea name="content" id="content" rows="20" required
                                        class="w-full px-6 py-6 border-x border-b border-gray-100 rounded-b-xl focus:outline-none focus:ring-8 focus:ring-purple-500/5 focus:border-purple-200 focus:bg-white transition-all font-mono text-xs leading-relaxed text-gray-800 placeholder:text-gray-300 shadow-inner"
                                        placeholder="Start writing your masterpiece here...">{{ old('content') }}</textarea>
                                </div>
                                @error('content')
                                    <p class="mt-2 text-sm text-red-600 font-medium px-1">{{ $message }}</p>
                                @enderror

                                <!-- Content Preview Area -->
                                <div id="contentPreview"
                                    class="hidden mt-8 transition-all animate-in fade-in slide-in-from-top-4 duration-500 font-semibold text-gray-700">
                                    <div class="flex items-center gap-2 mb-4 px-1">
                                        <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                                        <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Live
                                            Preview Result</span>
                                    </div>
                                    <div class="p-8 bg-gray-50/30 border border-gray-100 rounded-3xl">
                                        <div id="previewContent" class="prose prose-purple prose-lg max-w-none"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Media & Attachments -->
                        <div
                            class="bg-white/70 backdrop-blur-xl border border-white/20 rounded-2xl shadow-xl shadow-slate-200/50 overflow-hidden">
                            <div
                                class="px-6 py-4 flex items-center justify-between border-b border-gray-100 font-bold text-gray-900 tracking-tight">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-pink-50 flex items-center justify-center text-pink-600">
                                        <i class="fas fa-photo-video text-base"></i>
                                    </div>
                                    <h3 class="text-lg font-extrabold text-gray-900 tracking-tight">Media & Assets</h3>
                                </div>
                            </div>
                            <div class="p-6 space-y-8">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                                    <!-- Featured Image -->
                                    <div class="space-y-4">
                                        <label for="featured_image"
                                            class="block text-sm font-bold text-gray-500 uppercase tracking-widest px-1">
                                            Cover Image
                                        </label>

                                        <div class="relative group">
                                            <div id="featured_image_preview"
                                                class="mb-4 hidden animate-in zoom-in duration-300">
                                                <p
                                                    class="text-xs font-bold text-purple-600 uppercase tracking-widest mb-2 flex items-center gap-2 font-semibold text-gray-900">
                                                    <i class="fas fa-check-circle"></i> New Selection:
                                                </p>
                                                <div
                                                    class="relative h-48 bg-gray-50 rounded-2xl overflow-hidden border-2 border-dashed border-purple-200 group/item">
                                                    <img src="" alt="Preview"
                                                        class="w-full h-full object-cover">
                                                    <div id="featured_image_info"
                                                        class="absolute bottom-3 left-3 right-3 bg-white/90 backdrop-blur-md text-[10px] font-bold text-gray-600 px-3 py-2 rounded-xl border border-gray-100 shadow-sm uppercase tracking-tight hidden">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="relative">
                                                <input type="file" name="featured_image" id="featured_image"
                                                    accept="image/*"
                                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                                <div
                                                    class="flex items-center justify-center px-6 py-6 border-2 border-dashed border-gray-200 rounded-2xl group-hover:border-purple-300 transition-colors bg-gray-50/50 group-hover:bg-purple-50/30">
                                                    <div
                                                        class="text-center font-bold text-gray-500 group-hover:text-purple-600 uppercase tracking-widest transition-colors font-semibold">
                                                        <i
                                                            class="fas fa-cloud-upload-alt text-2xl text-gray-300 group-hover:text-purple-400 mb-2 transition-colors"></i>
                                                        <p
                                                            class="text-xs font-bold text-gray-500 group-hover:text-purple-600 uppercase tracking-widest transition-colors">
                                                            Select Cover Image</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest px-1">
                                            1200x630 Preferred</p>
                                        @error('featured_image')
                                            <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Gallery -->
                                    <div class="space-y-4">
                                        <label for="gallery_images"
                                            class="block text-sm font-bold text-gray-500 uppercase tracking-widest px-1 font-semibold text-gray-900 tracking-tight">
                                            Photo Gallery
                                        </label>

                                        <div id="gallery_preview_container"
                                            class="hidden mb-4 animate-in fade-in duration-300 px-1">
                                            <div class="flex flex-wrap gap-2" id="gallery_previews"></div>
                                        </div>

                                        <div class="relative group">
                                            <input type="file" name="gallery_images[]" id="gallery_images"
                                                multiple accept="image/*"
                                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10 font-bold text-gray-500 group-hover:text-pink-600 uppercase tracking-widest">
                                            <div
                                                class="flex items-center justify-center h-24 border-2 border-dashed border-gray-200 rounded-2xl group-hover:border-pink-300 transition-colors bg-gray-50/50 group-hover:bg-pink-50/30">
                                                <div class="text-center">
                                                    <i
                                                        class="fas fa-images text-2xl text-gray-300 group-hover:text-pink-400 mb-1 transition-colors"></i>
                                                    <p
                                                        class="text-xs font-bold text-gray-500 group-hover:text-pink-600 uppercase tracking-widest transition-colors">
                                                        Add Gallery Photos</p>
                                                </div>
                                            </div>
                                        </div>
                                        @error('gallery_images')
                                            <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- END LEFT COLUMN -->

                    <!-- RIGHT SIDEBAR: Settings -->
                    <div class="lg:col-span-4 space-y-8">

                        <!-- Publishing Panel -->
                        <div
                            class="bg-white/70 backdrop-blur-xl border border-white/20 rounded-2xl shadow-xl shadow-slate-200/50 overflow-hidden">
                            <div
                                class="p-5 border-b border-gray-100 flex items-center gap-3 font-extrabold text-gray-900 tracking-tight">
                                <div
                                    class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600">
                                    <i class="fas fa-paper-plane text-base"></i>
                                </div>
                                <h3 class="text-lg font-extrabold text-gray-900 tracking-tight">Publishing</h3>
                            </div>
                            <div class="p-5 space-y-5">
                                <div class="space-y-3">
                                    <label
                                        class="block text-xs font-bold text-gray-400 uppercase tracking-widest px-1 font-semibold text-gray-700">
                                        Post Status
                                    </label>
                                    <div
                                        class="flex items-center gap-3 px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl text-slate-600 font-bold text-sm">
                                        <div class="w-2 h-2 rounded-full bg-slate-400 animate-pulse"></div>
                                        <span>📝 Initial Draft Only</span>
                                        <input type="hidden" name="status" id="status" value="draft">
                                    </div>
                                    <p
                                        class="text-[10px] text-gray-400 font-bold uppercase tracking-widest px-1 mt-1 leading-relaxed">
                                        You can publish or schedule this post from the "Manage" page after saving.
                                    </p>
                                </div>



                                <button type="submit" form="blog-form"
                                    class="w-full py-3 bg-brand-gradient text-white rounded-xl font-bold shadow-lg shadow-purple-200 hover:shadow-purple-300 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-2 text-sm font-bold text-white tracking-widest">
                                    <i class="fas fa-rocket text-xs"></i>
                                    CREATE STORY
                                </button>
                            </div>
                        </div>

                        <!-- SEO & Metadata -->
                        <div
                            class="bg-white/70 backdrop-blur-xl border border-white/20 rounded-2xl shadow-xl shadow-slate-200/50 overflow-hidden font-extrabold text-gray-900 tracking-tight">
                            <div class="p-5 border-b border-gray-100 flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 font-extrabold text-gray-900 tracking-tight">
                                    <i class="fas fa-search text-base"></i>
                                </div>
                                <h3 class="text-lg font-extrabold text-gray-900 tracking-tight">SEO & Search</h3>
                            </div>
                            <div class="p-5 space-y-5">
                                <div class="space-y-4">
                                    <div>
                                        <label for="meta_title"
                                            class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 px-1 font-semibold text-gray-700">SEO
                                            Title</label>
                                        <input type="text" name="meta_title" id="meta_title"
                                            value="{{ old('meta_title') }}"
                                            class="w-full px-4 py-3 bg-gray-50/50 border border-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 text-sm transition-all font-semibold"
                                            placeholder="Leave empty for auto-title">
                                    </div>
                                    <div>
                                        <label for="meta_description"
                                            class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 px-1 font-semibold text-gray-700">Meta
                                            Description</label>
                                        <textarea name="meta_description" id="meta_description" rows="3"
                                            class="w-full px-4 py-3 bg-gray-50/50 border border-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 text-xs transition-all resize-none font-medium"
                                            placeholder="Write summary for search engines...">{{ old('meta_description') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </form>
        </div>
    </div>

    @include('blog.partials.ai-modal')

    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script>
        // Initialize functionality on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Load template from localStorage
            const templateData = localStorage.getItem('blogTemplate');
            if (templateData) {
                try {
                    const data = JSON.parse(templateData);
                    if (data.type) {
                        const typeSelect = document.getElementById('type');
                        if (typeSelect) typeSelect.value = data.type || 'article';
                    }
                    if (data.title) {
                        const titleMatch = data.content.match(/^# (.+)/);
                        if (titleMatch) document.getElementById('title').value = titleMatch[1].trim();
                    }
                    document.getElementById('content').value = data.content;
                    localStorage.removeItem('blogTemplate');
                    // Trigger preview IF content was loaded
                    if (data.content) document.getElementById('previewToggle').click();
                } catch (e) {
                    console.error('Template error:', e);
                }
            }



            // Image Upload Engine (Preview & Professional Compression)
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
                        processImage(file, (compressedBlob) => {
                            const newFile = new File([compressedBlob], file.name, {
                                type: 'image/webp',
                                lastModified: Date.now()
                            });
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(newFile);
                            featuredInput.files = dataTransfer.files;
                            showPreview(newFile, featuredPreview, featuredImg, featuredInfo, true);
                        });
                    } else {
                        showPreview(file, featuredPreview, featuredImg, featuredInfo, false);
                    }
                });
            }

            // Gallery Support
            const galleryInput = document.getElementById('gallery_images');
            const galleryContainer = document.getElementById('gallery_preview_container');
            const galleryPreviews = document.getElementById('gallery_previews');

            if (galleryInput && galleryContainer) {
                galleryInput.addEventListener('change', async function(e) {
                    const files = Array.from(e.target.files);
                    if (!files.length) return;

                    galleryPreviews.innerHTML = '';
                    galleryContainer.classList.remove('hidden');

                    const dataTransfer = new DataTransfer();
                    for (const file of files) {
                        if (file.size > MAX_SIZE_MB * 1024 * 1024) {
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
                    div.className =
                        'relative w-16 h-16 rounded-xl overflow-hidden border-2 border-white shadow-sm group';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover">
                        ${isOptimized ? '<div class="absolute bottom-0 inset-x-0 bg-green-500/80 backdrop-blur-sm text-white text-[8px] font-bold text-center py-0.5">WEBP</div>' : ''}
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

        // Preview Content
        document.getElementById('previewToggle').addEventListener('click', function() {
            const previewArea = document.getElementById('contentPreview');
            const previewContent = document.getElementById('previewContent');
            const content = document.getElementById('content').value;
            const contentType = document.getElementById('content_type').value;

            if (previewArea.classList.contains('hidden')) {
                let html = '';
                if (contentType === 'markdown') {
                    html = marked.parse(content);
                } else if (contentType === 'html') {
                    html = content;
                } else {
                    html = content.replace(/\n/g, '<br>');
                }
                previewContent.innerHTML = html;
                previewArea.classList.remove('hidden');
                this.innerHTML = '<i class="fas fa-eye-slash mr-2"></i>Hide Preview';
            } else {
                previewArea.classList.add('hidden');
                this.innerHTML = '<i class="fas fa-eye mr-2"></i>Preview';
            }
        });

        // Insert Markdown/HTML Utility
        function insertMarkdown(before, after, placeholder) {
            const t = document.getElementById('content');
            const s = t.selectionStart;
            const e = t.selectionEnd;
            const sel = t.value.substring(s, e);
            const r = before + (sel || placeholder) + after;
            t.value = t.value.substring(0, s) + r + t.value.substring(e);
            t.focus();
            t.setSelectionRange(s + before.length, s + before.length + (sel || placeholder).length);
        }

        function insertHTML(b, a, p) {
            insertMarkdown(b, a, p);
        }

        // AI Request Handling
        document.getElementById('generateAIContent').addEventListener('click', async function() {
            const btn = this;
            const ctx = document.getElementById('ai_context').value;
            const title = document.getElementById('title').value;
            const action = document.getElementById('ai_action').value;
            const aiModel = document.getElementById('ai_model').value;
            const aiLength = document.getElementById('ai_length') ? document.getElementById('ai_length').value :
                'medium';
            const aiTone = document.getElementById('ai_tone') ? document.getElementById('ai_tone').value :
                'professional';

            if (action === 'generate_blog' && !title) {
                alert('Title required for generations');
                return;
            }

            btn.disabled = true;
            document.getElementById('ai_generating').classList.remove('hidden');

            try {
                const response = await fetch('{{ route('ai.generate-blog') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        action: action,
                        context: ctx,
                        post_context: {
                            title,
                            type: document.getElementById('type').value
                        },
                        model: aiModel,
                        length: aiLength,
                        tone: aiTone
                    })
                });
                const data = await response.json();
                if (data.success) {
                    window.showAIContentModal(data.content, data.excerpt, action);
                } else {
                    alert(data.message);
                }
            } catch (e) {
                alert('AI Error');
            } finally {
                btn.disabled = false;
                document.getElementById('ai_generating').classList.add('hidden');
            }
        });
    </script>
</x-app-layout>
