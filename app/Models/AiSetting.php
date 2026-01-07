<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AiSetting extends Model
{
    protected $fillable = [
        'gemini_enabled', 'gemini_api_key', 'gemini_model',
        'openai_enabled', 'openai_api_key', 'openai_model',
        'huggingface_enabled', 'huggingface_api_key', 'huggingface_model',
        'cohere_enabled', 'cohere_api_key', 'cohere_model',
        'default_provider', 'max_tokens', 'temperature', 'system_prompt',
        'blog_generation_enabled', 'seo_optimization_enabled',
        'content_rewrite_enabled', 'image_generation_enabled',
        'users_can_use_ai',
    ];

    protected $casts = [
        'gemini_enabled' => 'boolean',
        'openai_enabled' => 'boolean',
        'huggingface_enabled' => 'boolean',
        'cohere_enabled' => 'boolean',
        'blog_generation_enabled' => 'boolean',
        'seo_optimization_enabled' => 'boolean',
        'content_rewrite_enabled' => 'boolean',
        'image_generation_enabled' => 'boolean',
        'users_can_use_ai' => 'boolean',
        'temperature' => 'float',
    ];

    /**
     * Get AI settings (cached)
     */
    public static function getSettings(): self
    {
        return Cache::remember('ai_settings', 3600, function () {
            return self::first() ?? self::createDefaultSettings();
        });
    }

    /**
     * Create default AI settings
     */
    public static function createDefaultSettings(): self
    {
        return self::create([
            'gemini_enabled' => true,
            'gemini_api_key' => env('GEMINI_API_KEY', 'ENTER_YOUR_GEMINI_API_KEY_HERE'),
            'gemini_model' => 'gemini-2.5-flash',
            'openai_enabled' => false,
            'openai_api_key' => null,
            'openai_model' => 'gpt-3.5-turbo',
            'huggingface_enabled' => false,
            'huggingface_api_key' => null,
            'huggingface_model' => 'meta-llama/Llama-2-7b-chat-hf',
            'cohere_enabled' => false,
            'cohere_api_key' => null,
            'cohere_model' => 'command',
            'default_provider' => 'gemini',
            'max_tokens' => 1000,
            'temperature' => 0.7,
            'system_prompt' => null,
            'blog_generation_enabled' => true,
            'seo_optimization_enabled' => true,
            'content_rewrite_enabled' => true,
            'image_generation_enabled' => false,
        ]);
    }

    /**
     * Check if a provider is enabled
     */
    public function isProviderEnabled(string $provider): bool
    {
        return match($provider) {
            'gemini' => $this->gemini_enabled,
            'openai' => $this->openai_enabled,
            'huggingface' => $this->huggingface_enabled,
            'cohere' => $this->cohere_enabled,
            default => false,
        };
    }

    /**
     * Get API key for a provider
     */
    public function getApiKey(string $provider): ?string
    {
        return match($provider) {
            'gemini' => $this->gemini_api_key,
            'openai' => $this->openai_api_key,
            'huggingface' => $this->huggingface_api_key,
            'cohere' => $this->cohere_api_key,
            default => null,
        };
    }

    /**
     * Get model for a provider
     */
    public function getModel(string $provider): string
    {
        return match($provider) {
            'gemini' => $this->gemini_model,
            'openai' => $this->openai_model,
            'huggingface' => $this->huggingface_model,
            'cohere' => $this->cohere_model,
            default => 'gemini-1.5-flash',
        };
    }

    /**
     * Mask API key for display
     */
    public function getMaskedApiKey(string $provider): string
    {
        $key = $this->getApiKey($provider);
        if (!$key) {
            return 'Not set';
        }
        return substr($key, 0, 8) . '...' . substr($key, -4);
    }
}
