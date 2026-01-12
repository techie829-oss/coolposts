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
                                <span class="text-gray-900 truncate max-w-[200px]">Edit: {{ $post->title }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
                <h2 class="font-extrabold text-3xl text-gray-900 leading-tight tracking-tight">
                    {{ __('Edit Blog Post') }}
                </h2>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('blog.manage') }}"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">
                    <i class="fas fa-tasks mr-2 text-gray-400 text-xs"></i>
                    Manage Posts
                </a>
                <a href="{{ route('blog.show', $post->slug) }}" target="_blank"
                    class="inline-flex items-center px-4 py-2 bg-white border border-gray-200 rounded-xl text-sm font-semibold text-gray-700 hover:bg-gray-50 hover:border-gray-300 transition-all shadow-sm">
                    <i class="fas fa-external-link-alt mr-2 text-gray-400 text-xs"></i>
                    View Live
                </a>
                <button type="submit" form="blog-form"
                    class="inline-flex items-center px-6 py-2.5 bg-brand-gradient text-white rounded-xl text-sm font-bold shadow-lg shadow-purple-200 hover:shadow-purple-300 hover:scale-[1.02] active:scale-[0.98] transition-all">
                    <i class="fas fa-save mr-2"></i>
                    Update Post
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('blog.update', $post) }}" enctype="multipart/form-data"
                id="blog-form">
                @csrf
                @method('PUT')

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
                                    <input type="text" name="title" id="title"
                                        value="{{ old('title', $post->title) }}" required
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
                                                class="w-full pl-10 pr-4 py-2 bg-gray-50/50 border border-gray-100 rounded-lg focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 focus:bg-white transition-all appearance-none cursor-pointer text-sm">
                                                <option value="">Select Type</option>
                                                @foreach ($types as $key => $type)
                                                    <option value="{{ $key }}"
                                                        {{ old('type', $post->type) == $key ? 'selected' : '' }}>
                                                        {{ $type }}
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
                                                class="w-full pl-10 pr-4 py-2 bg-gray-50/50 border border-gray-100 rounded-lg focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 focus:bg-white transition-all appearance-none cursor-pointer text-sm">
                                                @foreach (\App\Models\BlogPost::CONTENT_TYPES as $type => $label)
                                                    <option value="{{ $type }}"
                                                        {{ old('content_type', $post->content_type ?? 'markdown') == $type ? 'selected' : '' }}>
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
                                                value="{{ old('category', $post->category) }}"
                                                class="w-full pl-10 pr-4 py-2 bg-gray-50/50 border border-gray-100 rounded-lg focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 focus:bg-white transition-all text-sm"
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
                                                value="{{ is_array($tags = old('tags', $post->tags)) ? implode(', ', $tags) : $tags }}"
                                                class="w-full pl-10 pr-4 py-2 bg-gray-50/50 border border-gray-100 rounded-lg focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 focus:bg-white transition-all text-sm"
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
                                        class="w-full px-4 py-3 bg-gray-50/50 border border-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 focus:bg-white transition-all resize-none placeholder:text-gray-400 text-sm"
                                        placeholder="A brief summary of your post for link listings...">{{ old('excerpt', $post->excerpt) }}</textarea>
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
                                <!-- Animated Background -->
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
                        @elseif ($aiSettings->blog_generation_enabled && !$userCanUseAI)
                            <!-- AI Restricted Message -->
                            <div
                                class="mb-8 bg-gradient-to-r from-orange-50 to-yellow-50 rounded-xl p-6 border border-orange-200">
                                <div class="flex items-center mb-4">
                                    <i class="fas fa-user-shield text-orange-600 text-2xl mr-3"></i>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900">AI Features Restricted
                                        </h3>
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
                                        class="inline-flex items-center px-4 py-2 bg-purple-50 text-purple-700 rounded-xl text-sm font-bold hover:bg-purple-100 transition-all">
                                        <i class="fas fa-eye mr-2"></i>Preview
                                    </button>
                                </div>
                            </div>

                            <div class="p-6">
                                <label for="content"
                                    class="block text-sm font-bold text-gray-400 uppercase tracking-widest mb-4 px-1">
                                    Storytelling Area <span class="text-red-500">*</span>
                                </label>

                                <!-- Toolbars -->
                                <div class="relative mb-0 z-10">
                                    <!-- Markdown Toolbar -->
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

                                    <!-- HTML Toolbar -->
                                    <div id="htmlToolbar"
                                        class="flex flex-wrap gap-1.5 p-2 bg-gray-50/80 backdrop-blur-sm border border-gray-100 rounded-t-2xl hidden">
                                        <button type="button" onclick="insertHTML('<strong>', '</strong>', 'Bold')"
                                            class="w-9 h-9 flex items-center justify-center bg-white border border-gray-100 rounded-lg text-gray-600 hover:text-purple-600 hover:border-purple-200 transition-all shadow-sm">
                                            <i class="fas fa-bold"></i>
                                        </button>
                                        <button type="button" onclick="insertHTML('<em>', '</em>', 'Italic')"
                                            class="w-9 h-9 flex items-center justify-center bg-white border border-gray-100 rounded-lg text-gray-600 hover:text-purple-600 hover:border-purple-200 transition-all shadow-sm">
                                            <i class="fas fa-italic"></i>
                                        </button>
                                        <button type="button" onclick="insertHTML('<h2>', '</h2>', 'Heading 2')"
                                            class="w-9 h-9 flex items-center justify-center bg-white border border-gray-100 rounded-lg text-gray-600 hover:text-purple-600 hover:border-purple-200 transition-all shadow-sm">
                                            <i class="fas fa-heading"></i>
                                        </button>
                                        <button type="button" onclick="insertHTML('<a href=\"\">', '</a>', 'Link')"
                                            class="w-9 h-9 flex items-center justify-center bg-white border border-gray-100 rounded-lg text-gray-600 hover:text-purple-600 hover:border-purple-200 transition-all shadow-sm">
                                            <i class="fas fa-link text-xs"></i>
                                        </button>
                                    </div>

                                    <!-- Text Toolbar -->
                                    <div id="textToolbar"
                                        class="p-3 bg-gray-50/80 backdrop-blur-sm border border-gray-100 rounded-t-2xl hidden">
                                        <div
                                            class="text-xs font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                                            <i class="fas fa-info-circle text-purple-500"></i>
                                            Plain Text Format Enabled
                                        </div>
                                    </div>

                                    <textarea name="content" id="content" rows="18" required
                                        class="w-full px-6 py-6 border-x border-b border-gray-100 rounded-b-xl focus:outline-none focus:ring-8 focus:ring-purple-500/5 focus:border-purple-200 focus:bg-white transition-all font-mono text-xs leading-relaxed text-gray-800 placeholder:text-gray-300 shadow-inner"
                                        placeholder="Start writing your masterpiece here...">{{ old('content', $post->content) }}</textarea>
                                </div>
                                @error('content')
                                    <p class="mt-2 text-sm text-red-600 font-medium px-1">{{ $message }}</p>
                                @enderror

                                <!-- Content Preview Area -->
                                <div id="contentPreview"
                                    class="hidden mt-8 transition-all animate-in fade-in slide-in-from-top-4 duration-500">
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
                            <div class="px-6 py-4 flex items-center justify-between border-b border-gray-100">
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
                                            @if ($post->featured_image)
                                                <div
                                                    class="mb-4 relative rounded-2xl overflow-hidden shadow-lg border-4 border-white">
                                                    <img src="{{ $post->featured_image }}" alt="Current"
                                                        class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-700">
                                                    <div
                                                        class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                        <span
                                                            class="text-white text-xs font-bold uppercase tracking-widest bg-black/40 backdrop-blur-md px-3 py-1.5 rounded-full">Current
                                                            Image</span>
                                                    </div>
                                                </div>
                                            @endif

                                            <div id="featured_image_preview"
                                                class="mb-4 hidden animate-in zoom-in duration-300">
                                                <p
                                                    class="text-xs font-bold text-purple-600 uppercase tracking-widest mb-2 flex items-center gap-2">
                                                    <i class="fas fa-check-circle"></i> New Selection:
                                                </p>
                                                <div
                                                    class="relative h-48 bg-gray-50 rounded-2xl overflow-hidden border-2 border-dashed border-purple-200 group/item">
                                                    <img src="" alt="Preview"
                                                        class="w-full h-full object-cover">
                                                    <div id="featured_image_info"
                                                        class="absolute bottom-3 left-3 right-3 bg-white/90 backdrop-blur-md text-[10px] font-bold text-gray-600 px-3 py-2 rounded-xl border border-gray-100 shadow-sm uppercase tracking-tight">
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="relative">
                                                <input type="file" name="featured_image" id="featured_image"
                                                    accept="image/*"
                                                    class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                                <div
                                                    class="flex items-center justify-center px-6 py-6 border-2 border-dashed border-gray-200 rounded-2xl group-hover:border-purple-300 transition-colors bg-gray-50/50 group-hover:bg-purple-50/30">
                                                    <div class="text-center">
                                                        <i
                                                            class="fas fa-cloud-upload-alt text-2xl text-gray-300 group-hover:text-purple-400 mb-2 transition-colors"></i>
                                                        <p
                                                            class="text-xs font-bold text-gray-500 group-hover:text-purple-600 uppercase tracking-widest transition-colors">
                                                            Replace Cover</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest px-1">
                                            1200x630 (WebP Auto-optimized)</p>
                                        @error('featured_image')
                                            <p class="mt-1 text-sm text-red-600 font-medium font-medium">
                                                {{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Gallery -->
                                    <div class="space-y-4">
                                        <label for="gallery_images"
                                            class="block text-sm font-bold text-gray-500 uppercase tracking-widest px-1">
                                            Photo Gallery
                                        </label>

                                        @if ($post->gallery_images && count($post->gallery_images) > 0)
                                            <div class="flex flex-wrap gap-2 mb-4">
                                                @foreach ($post->gallery_images as $image)
                                                    <div
                                                        class="relative w-14 h-14 rounded-xl overflow-hidden border-2 border-white shadow-sm transition-transform hover:scale-110 hover:z-10 group">
                                                        <img src="{{ $image }}"
                                                            class="w-full h-full object-cover">
                                                    </div>
                                                @endforeach
                                                <div
                                                    class="w-14 h-14 rounded-xl border-2 border-dashed border-gray-200 flex items-center justify-center bg-gray-50">
                                                    <span
                                                        class="text-[10px] font-bold text-gray-400">{{ count($post->gallery_images) }}+</span>
                                                </div>
                                            </div>
                                        @endif

                                        <div id="gallery_preview_container"
                                            class="hidden mb-4 animate-in fade-in duration-300 px-1">
                                            <div class="flex flex-wrap gap-2" id="gallery_previews"></div>
                                        </div>

                                        <div class="relative group">
                                            <input type="file" name="gallery_images[]" id="gallery_images"
                                                multiple accept="image/*"
                                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                            <div
                                                class="flex items-center justify-center h-24 border-2 border-dashed border-gray-200 rounded-2xl group-hover:border-pink-300 transition-colors bg-gray-50/50 group-hover:bg-pink-50/30">
                                                <div class="text-center">
                                                    <i
                                                        class="fas fa-images text-2xl text-gray-300 group-hover:text-pink-400 mb-1 transition-colors"></i>
                                                    <p
                                                        class="text-xs font-bold text-gray-500 group-hover:text-pink-600 uppercase tracking-widest">
                                                        Add Gallery Photos</p>
                                                </div>
                                            </div>
                                        </div>
                                        @error('gallery_images')
                                            <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Attachments -->
                                <div class="pt-4 px-1">
                                    <label for="attachments"
                                        class="block text-sm font-bold text-gray-500 uppercase tracking-widest mb-4">
                                        Resource Attachments
                                    </label>

                                    @if ($post->attachments && count($post->attachments) > 0)
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-6">
                                            @foreach ($post->attachments as $attachment)
                                                <div
                                                    class="flex items-center justify-between p-3 bg-gray-50 rounded-2xl border border-gray-100 hover:border-purple-100 transition-all group">
                                                    <div class="flex items-center gap-3">
                                                        <div
                                                            class="w-9 h-9 rounded-xl bg-white border border-gray-100 flex items-center justify-center text-gray-400 group-hover:text-purple-500 transition-colors">
                                                            <i class="fas fa-file-alt"></i>
                                                        </div>
                                                        <div class="truncate">
                                                            <p
                                                                class="text-xs font-bold text-gray-700 truncate max-w-[150px]">
                                                                {{ $attachment['name'] }}</p>
                                                            <p class="text-[10px] text-gray-400 font-bold uppercase">
                                                                {{ number_format($attachment['size'] / 1024, 1) }} KB
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="relative group">
                                        <input type="file" name="attachments[]" id="attachments" multiple
                                            class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                        <div
                                            class="flex items-center justify-between px-6 py-4 border-2 border-dashed border-gray-200 rounded-2xl group-hover:border-purple-300 transition-all bg-gray-50/30 group-hover:bg-white shadow-sm">
                                            <div class="flex items-center gap-3">
                                                <i
                                                    class="fas fa-paperclip text-gray-300 group-hover:text-purple-400 text-lg transition-colors"></i>
                                                <span
                                                    class="text-sm font-bold text-gray-500 group-hover:text-purple-600 uppercase tracking-widest transition-colors">Choose
                                                    files to attach...</span>
                                            </div>
                                            <span
                                                class="text-[10px] font-bold text-gray-300 uppercase tracking-widest group-hover:text-purple-300 transition-colors">Max
                                                10MB each</span>
                                        </div>
                                    </div>
                                    @error('attachments')
                                        <p class="mt-2 text-sm text-red-600 font-medium">{{ $message }}</p>
                                    @enderror
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
                            <div class="p-5 border-b border-gray-100 flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600">
                                    <i class="fas fa-paper-plane text-base"></i>
                                </div>
                                <h3 class="text-lg font-extrabold text-gray-900 tracking-tight">Publishing</h3>
                            </div>
                            <div class="p-5 space-y-5">
                                <!-- Status Selection -->
                                <div class="space-y-3">
                                    <label for="status"
                                        class="block text-xs font-bold text-gray-400 uppercase tracking-widest px-1">
                                        Status <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative group">
                                        <select name="status" id="status" required
                                            class="w-full pl-4 pr-10 py-3 bg-gray-50/50 border border-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 transition-all appearance-none cursor-pointer font-semibold text-gray-700">
                                            <option value="draft"
                                                {{ old('status', $post->status) == 'draft' ? 'selected' : '' }}>📝
                                                Draft</option>
                                            <option value="published"
                                                {{ old('status', $post->status) == 'published' ? 'selected' : '' }}>✅
                                                Published</option>
                                            <option value="archived"
                                                {{ old('status', $post->status) == 'archived' ? 'selected' : '' }}>📁
                                                Archived</option>
                                            <option value="scheduled"
                                                {{ old('status', $post->status) == 'scheduled' ? 'selected' : '' }}>⏰
                                                Scheduled</option>
                                        </select>
                                        <div
                                            class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400">
                                            <i class="fas fa-chevron-down text-xs"></i>
                                        </div>
                                    </div>
                                    @error('status')
                                        <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Schedule Date -->
                                <div id="scheduled_date_group"
                                    class="space-y-3 transition-all duration-300 {{ old('status', $post->status) == 'scheduled' ? '' : 'hidden' }}">
                                    <label for="scheduled_at"
                                        class="block text-xs font-bold text-gray-400 uppercase tracking-widest px-1">
                                        Release Date
                                    </label>
                                    <input type="datetime-local" name="scheduled_at" id="scheduled_at"
                                        value="{{ old('scheduled_at', $post->scheduled_at ? $post->scheduled_at->format('Y-m-d\TH:i') : '') }}"
                                        class="w-full px-4 py-3 bg-gray-50/50 border border-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 transition-all font-medium text-gray-700">
                                    @error('scheduled_at')
                                        <p class="mt-1 text-sm text-red-600 font-medium">{{ $message }}</p>
                                    @enderror
                                </div>

                                <button type="submit" form="blog-form"
                                    class="w-full py-3 bg-brand-gradient text-white rounded-xl font-bold shadow-lg shadow-purple-200 hover:shadow-purple-300 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-2 text-sm">
                                    <i class="fas fa-save text-xs"></i>
                                    Update Changes
                                </button>
                            </div>
                        </div>

                        <!-- SEO & Metadata -->
                        <div
                            class="bg-white/70 backdrop-blur-xl border border-white/20 rounded-2xl shadow-xl shadow-slate-200/50 overflow-hidden">
                            <div class="p-5 border-b border-gray-100 flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                                    <i class="fas fa-search text-base"></i>
                                </div>
                                <h3 class="text-lg font-extrabold text-gray-900 tracking-tight">SEO & Search</h3>
                            </div>
                            <div class="p-5 space-y-5">
                                <div class="space-y-4">
                                    <div>
                                        <label for="meta_title"
                                            class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">SEO
                                            Title</label>
                                        <input type="text" name="meta_title" id="meta_title"
                                            value="{{ old('meta_title', $post->meta_title) }}"
                                            class="w-full px-4 py-3 bg-gray-50/50 border border-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 text-sm transition-all"
                                            placeholder="Leave empty for auto-title">
                                    </div>

                                    <div>
                                        <label for="meta_description"
                                            class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Meta
                                            Description</label>
                                        <textarea name="meta_description" id="meta_description" rows="3"
                                            class="w-full px-4 py-3 bg-gray-50/50 border border-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 text-xs transition-all resize-none"
                                            placeholder="Write summary for search engines...">{{ old('meta_description', $post->meta_description) }}</textarea>
                                    </div>

                                    <div>
                                        <label for="meta_keywords"
                                            class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Keywords</label>
                                        <input type="text" name="meta_keywords" id="meta_keywords"
                                            value="{{ is_array($k = old('meta_keywords', $post->meta_keywords)) ? implode(', ', $k) : $k }}"
                                            class="w-full px-4 py-3 bg-gray-50/50 border border-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 text-sm transition-all"
                                            placeholder="marketing, seo, blog">
                                    </div>

                                    <div>
                                        <label for="canonical_url"
                                            class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Canonical
                                            Link</label>
                                        <input type="url" name="canonical_url" id="canonical_url"
                                            value="{{ old('canonical_url', $post->canonical_url) }}"
                                            class="w-full px-4 py-3 bg-gray-50/50 border border-gray-100 rounded-xl focus:outline-none focus:ring-4 focus:ring-purple-500/10 focus:border-purple-500 text-sm transition-all"
                                            placeholder="https://example.com/source">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Revenue & Monetization -->
                        <div
                            class="bg-white/70 backdrop-blur-xl border border-white/20 rounded-2xl shadow-xl shadow-slate-200/50 overflow-hidden">
                            <div class="p-5 border-b border-gray-100 flex items-center gap-3">
                                <div
                                    class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center text-green-600">
                                    <i class="fas fa-coins text-base"></i>
                                </div>
                                <h3 class="text-lg font-extrabold text-gray-900 tracking-tight">Earning Path</h3>
                            </div>
                            <div class="p-5 space-y-5">
                                @if (Auth::user()->isAdmin())
                                    <div class="p-4 bg-yellow-50 rounded-2xl border border-yellow-100 space-y-4">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-shield-alt text-yellow-600"></i>
                                            <span class="text-xs font-bold text-yellow-800 uppercase">Administrator
                                                Controls</span>
                                        </div>

                                        <label class="flex items-center group cursor-pointer">
                                            <div class="relative">
                                                <input type="checkbox" name="is_monetized" value="1"
                                                    {{ old('is_monetized', $post->is_monetized) ? 'checked' : '' }}
                                                    class="sr-only peer">
                                                <div
                                                    class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500 transition-all">
                                                </div>
                                            </div>
                                            <span
                                                class="ml-3 text-sm font-bold text-gray-600 group-hover:text-green-600 transition-colors">Enable
                                                Monetization</span>
                                        </label>

                                        <div class="space-y-4 pt-2">
                                            <div class="grid grid-cols-2 gap-3">
                                                <div class="space-y-2">
                                                    <label
                                                        class="block text-[10px] font-bold text-gray-400 uppercase">Revenue
                                                        Model</label>
                                                    <select name="monetization_type"
                                                        class="w-full px-3 py-2 bg-white border border-yellow-200 rounded-lg text-sm font-semibold focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none transition-all">
                                                        @foreach ($monetizationTypes as $key => $type)
                                                            <option value="{{ $key }}"
                                                                {{ old('monetization_type', $post->monetization_type) == $key ? 'selected' : '' }}>
                                                                {{ $type }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div class="space-y-2">
                                                    <label
                                                        class="block text-[10px] font-bold text-gray-400 uppercase">Ad
                                                        Strategy</label>
                                                    <select name="ad_type"
                                                        class="w-full px-3 py-2 bg-white border border-yellow-200 rounded-lg text-sm font-semibold focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none transition-all">
                                                        @foreach ($adTypes as $key => $type)
                                                            <option value="{{ $key }}"
                                                                {{ old('ad_type', $post->ad_type) == $key ? 'selected' : '' }}>
                                                                {{ $type }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="space-y-2">
                                                <label class="block text-[10px] font-bold text-gray-400 uppercase">Ad
                                                    Gap (Paragraphs)</label>
                                                <input type="number" name="ad_frequency"
                                                    value="{{ old('ad_frequency', $post->ad_frequency) }}"
                                                    min="1" max="10"
                                                    class="w-full px-3 py-2 bg-white border border-yellow-200 rounded-lg text-sm font-semibold focus:ring-2 focus:ring-yellow-400 focus:border-transparent outline-none transition-all">
                                            </div>

                                            <div class="pt-2 border-t border-yellow-100 space-y-3">
                                                <p class="text-[10px] font-bold text-yellow-700 uppercase">Earning
                                                    Rates (₹)</p>
                                                <div class="grid grid-cols-3 gap-2">
                                                    <div class="space-y-1">
                                                        <label
                                                            class="text-[9px] text-gray-500 font-bold uppercase">&lt;
                                                            2m</label>
                                                        <input type="number" name="earning_rate_less_2min"
                                                            value="{{ old('earning_rate_less_2min', $post->earning_rate_less_2min) }}"
                                                            step="0.0001"
                                                            class="w-full px-2 py-1.5 bg-white border border-yellow-200 rounded text-xs font-bold">
                                                    </div>
                                                    <div class="space-y-1">
                                                        <label
                                                            class="text-[9px] text-gray-500 font-bold uppercase">2-5m</label>
                                                        <input type="number" name="earning_rate_2_5min"
                                                            value="{{ old('earning_rate_2_5min', $post->earning_rate_2_5min) }}"
                                                            step="0.0001"
                                                            class="w-full px-2 py-1.5 bg-white border border-yellow-200 rounded text-xs font-bold">
                                                    </div>
                                                    <div class="space-y-1">
                                                        <label
                                                            class="text-[9px] text-gray-500 font-bold uppercase">&gt;
                                                            5m</label>
                                                        <input type="number" name="earning_rate_more_5min"
                                                            value="{{ old('earning_rate_more_5min', $post->earning_rate_more_5min) }}"
                                                            step="0.0001"
                                                            class="w-full px-2 py-1.5 bg-white border border-yellow-200 rounded text-xs font-bold">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="p-4 bg-blue-50/50 rounded-2xl border border-blue-100 border-dashed">
                                        <div class="flex items-center gap-2 mb-3">
                                            <i class="fas fa-info-circle text-blue-500 text-sm"></i>
                                            <span
                                                class="text-xs font-bold text-blue-800 uppercase tracking-tight">Active
                                                Strategy</span>
                                        </div>
                                        <div class="space-y-2">
                                            <div class="flex justify-between items-center text-xs">
                                                <span class="text-gray-500 font-medium text-[10px] uppercase">Revenue
                                                    Hub:</span>
                                                <span
                                                    class="font-bold text-gray-700 capitalize">{{ str_replace('_', ' ', $post->monetization_type) }}</span>
                                            </div>
                                            <div class="flex justify-between items-center text-xs">
                                                <span class="text-gray-500 font-medium text-[10px] uppercase">Ad
                                                    Placement:</span>
                                                <span
                                                    class="font-bold text-gray-700 capitalize">{{ str_replace('_', ' ', $post->ad_type) }}</span>
                                            </div>
                                            <div
                                                class="pt-3 border-t border-blue-100 flex items-center justify-between">
                                                <span class="text-[10px] font-bold text-blue-600 uppercase">Earning
                                                    Potential:</span>
                                                <span
                                                    class="text-xs font-extrabold text-blue-700">₹{{ $post->earning_rate_more_5min }}
                                                    / visit</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Hidden states for non-admins -->
                                    <input type="hidden" name="is_monetized"
                                        value="{{ $post->is_monetized ? 1 : 0 }}">
                                    <input type="hidden" name="monetization_type"
                                        value="{{ $post->monetization_type }}">
                                    <input type="hidden" name="ad_type" value="{{ $post->ad_type }}">
                                    <input type="hidden" name="earning_rate_less_2min"
                                        value="{{ $post->earning_rate_less_2min }}">
                                    <input type="hidden" name="earning_rate_2_5min"
                                        value="{{ $post->earning_rate_2_5min }}">
                                    <input type="hidden" name="earning_rate_more_5min"
                                        value="{{ $post->earning_rate_more_5min }}">
                                @endif
                            </div>
                        </div>

                    </div>
                    <!-- END RIGHT SIDEBAR -->

                </div>
                <!-- END GRID -->

            </form>
        </div>
    </div>


    @include('blog.partials.ai-modal')

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
        <script>
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
