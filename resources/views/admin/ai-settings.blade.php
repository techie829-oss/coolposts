<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('AI Settings') }}
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
            @if(session('success'))
                <div class="mb-6 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('ai-settings.update') }}" class="space-y-8">
                @csrf

                <!-- AI Features Toggles -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">AI Features</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <label class="text-sm font-medium text-gray-700">Blog Generation</label>
                                <p class="text-xs text-gray-500">Enable AI-powered blog content generation</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="blog_generation_enabled" value="1" {{ $aiSettings->blog_generation_enabled ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <label class="text-sm font-medium text-gray-700">SEO Optimization</label>
                                <p class="text-xs text-gray-500">AI-powered SEO content optimization</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="seo_optimization_enabled" value="1" {{ $aiSettings->seo_optimization_enabled ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <label class="text-sm font-medium text-gray-700">Content Rewrite</label>
                                <p class="text-xs text-gray-500">Rewrite existing content with AI</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="content_rewrite_enabled" value="1" {{ $aiSettings->content_rewrite_enabled ? 'checked' : '' }} class="sr-only peer">
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <label class="text-sm font-medium text-gray-700">Image Generation</label>
                                <p class="text-xs text-gray-500">AI-powered image generation (Coming Soon)</p>
                            </div>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" name="image_generation_enabled" value="1" {{ $aiSettings->image_generation_enabled ? 'checked' : '' }} class="sr-only peer" disabled>
                                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600 opacity-50"></div>
                            </label>
                        </div>

                        <div class="flex items-center justify-between border-t pt-4 mt-4 md:col-span-2">
                            <div>
                                <label class="text-sm font-medium text-gray-700">Allow Users to Use AI</label>
                                <p class="text-xs text-gray-500">Enable regular users to access AI features. If disabled, only admins can use AI.</p>
                            </div>
                            <div>
                                <input type="hidden" name="users_can_use_ai" value="0">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="users_can_use_ai" value="1" {{ ($aiSettings->users_can_use_ai ?? true) ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Gemini AI Settings -->
                <div class="bg-white rounded-lg shadow p-6">
                    <!-- Free Tier Info -->
                    <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                        <div class="flex items-start">
                            <i class="fas fa-info-circle text-blue-500 mt-1 mr-2"></i>
                            <div class="text-xs text-blue-800">
                                <strong>Free Tier Available:</strong> Gemini models are free to use without billing. Data is used to improve Google's services. Enable billing in Google Cloud for data privacy (pay-as-you-go only).
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i class="fas fa-robot text-purple-600 mr-2"></i>
                            Google Gemini
                        </h3>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="gemini_enabled" value="1" {{ $aiSettings->gemini_enabled ? 'checked' : '' }} class="sr-only peer" id="gemini_toggle">
                            <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                        </label>
                    </div>

                    <div class="space-y-4" id="gemini_settings">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">API Key</label>
                            <input type="text" name="gemini_api_key" value="{{ $aiSettings->gemini_api_key ?? '' }}" placeholder="Enter your Gemini API key" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <p class="text-xs text-gray-500 mt-1">
                                <i class="fas fa-info-circle text-blue-500"></i> Get your API key from <a href="https://makersuite.google.com/app/apikey" target="_blank" class="text-purple-600 hover:underline">Google AI Studio</a> (Free tier available)
                            </p>
                            @if($aiSettings->gemini_api_key && str_contains($aiSettings->gemini_api_key, 'DummyKey'))
                                <div class="mt-2 p-2 bg-yellow-50 border border-yellow-200 rounded text-xs text-yellow-800">
                                    <i class="fas fa-exclamation-triangle"></i> Using placeholder API key. Please replace with your real Gemini API key for production use.
                                </div>
                            @endif
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Model <span class="text-xs text-gray-500">(Free Tier)</span></label>
                            <select name="gemini_model" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                <option value="gemini-2.5-flash" {{ $aiSettings->gemini_model == 'gemini-2.5-flash' ? 'selected' : '' }}>Gemini 2.5 Flash ⭐ (Latest & Recommended)</option>
                                <option value="gemini-2.0-flash-exp" {{ $aiSettings->gemini_model == 'gemini-2.0-flash-exp' ? 'selected' : '' }}>Gemini 2.0 Flash Exp</option>
                                <option value="gemini-1.5-flash" {{ $aiSettings->gemini_model == 'gemini-1.5-flash' ? 'selected' : '' }}>Gemini 1.5 Flash</option>
                                <option value="gemini-1.5-pro" {{ $aiSettings->gemini_model == 'gemini-1.5-pro' ? 'selected' : '' }}>Gemini 1.5 Pro</option>
                                <option value="gemini-pro" {{ $aiSettings->gemini_model == 'gemini-pro' ? 'selected' : '' }}>Gemini Pro</option>
                                <option value="gemini-pro-vision" {{ $aiSettings->gemini_model == 'gemini-pro-vision' ? 'selected' : '' }}>Gemini Pro Vision (Multimodal)</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-2">
                                <i class="fas fa-info-circle"></i> All models support text, image, video, and audio I/O with free tier limits
                            </p>
                        </div>
                    </div>
                </div>

                <!-- General Settings -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">General Settings</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Default Provider</label>
                            <select name="default_provider" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                <option value="gemini" {{ $aiSettings->default_provider == 'gemini' ? 'selected' : '' }}>Gemini</option>
                                <option value="openai" {{ $aiSettings->default_provider == 'openai' ? 'selected' : '' }}>OpenAI (Coming Soon)</option>
                                <option value="huggingface" {{ $aiSettings->default_provider == 'huggingface' ? 'selected' : '' }}>HuggingFace (Coming Soon)</option>
                                <option value="cohere" {{ $aiSettings->default_provider == 'cohere' ? 'selected' : '' }}>Cohere (Coming Soon)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Max Tokens</label>
                            <input type="number" name="max_tokens" value="{{ $aiSettings->max_tokens }}" min="1" max="10000" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Temperature</label>
                            <input type="number" name="temperature" value="{{ $aiSettings->temperature }}" min="0" max="2" step="0.1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <p class="text-xs text-gray-500 mt-1">Lower = more focused, Higher = more creative</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">System Prompt</label>
                        <textarea name="system_prompt" rows="3" placeholder="Custom system prompt for AI behavior..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">{{ $aiSettings->system_prompt }}</textarea>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                        <i class="fas fa-save mr-2"></i>Save AI Settings
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Toggle settings visibility based on enabled state
        document.getElementById('gemini_toggle').addEventListener('change', function() {
            document.getElementById('gemini_settings').style.opacity = this.checked ? '1' : '0.5';
            document.getElementById('gemini_settings').style.pointerEvents = this.checked ? 'auto' : 'none';
        });

        // Set initial state
        document.addEventListener('DOMContentLoaded', function() {
            const toggle = document.getElementById('gemini_toggle');
            const settings = document.getElementById('gemini_settings');
            settings.style.opacity = toggle.checked ? '1' : '0.5';
            settings.style.pointerEvents = toggle.checked ? 'auto' : 'none';
        });
    </script>
</x-app-layout>

