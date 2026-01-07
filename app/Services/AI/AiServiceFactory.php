<?php

namespace App\Services\AI;

use App\Models\AiSetting;

class AiServiceFactory
{
    /**
     * Create AI service instance based on provider
     */
    public static function create(?string $provider = null, ?string $customModel = null): ?AiServiceInterface
    {
        $settings = AiSetting::getSettings();
        $provider = $provider ?? $settings->default_provider;

        if (!$settings->isProviderEnabled($provider)) {
            return null;
        }

        $apiKey = $settings->getApiKey($provider);
        if (!$apiKey) {
            return null;
        }

        $model = $customModel ?? $settings->getModel($provider);

        return match($provider) {
            'gemini' => new GeminiService($apiKey, $model, $settings->max_tokens, $settings->temperature),
            // Add other providers here as needed
            default => null,
        };
    }

    /**
     * Get available providers
     */
    public static function getAvailableProviders(): array
    {
        $settings = AiSetting::getSettings();
        $providers = [];

        if ($settings->gemini_enabled && $settings->gemini_api_key) {
            $providers[] = 'gemini';
        }
        if ($settings->openai_enabled && $settings->openai_api_key) {
            $providers[] = 'openai';
        }
        if ($settings->huggingface_enabled && $settings->huggingface_api_key) {
            $providers[] = 'huggingface';
        }
        if ($settings->cohere_enabled && $settings->cohere_api_key) {
            $providers[] = 'cohere';
        }

        return $providers;
    }
}

