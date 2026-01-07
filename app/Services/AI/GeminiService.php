<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService implements AiServiceInterface
{
    private string $apiKey;
    private string $model;
    private int $maxTokens;
    private float $temperature;

    public function __construct(string $apiKey, string $model, int $maxTokens = 1000, float $temperature = 0.7)
    {
        $this->apiKey = $apiKey;
        $this->model = $model;
        $this->maxTokens = $maxTokens;
        $this->temperature = $temperature;
    }

    /**
     * Generate content based on prompt
     */
    public function generateContent(string $prompt, array $options = []): string
    {
        try {
            $response = Http::timeout(30)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                ])
                ->withHeader('X-goog-api-key', $this->apiKey)
                ->post(
                    "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent",
                    [
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => $prompt]
                                ]
                            ]
                        ],
                        'generationConfig' => [
                            'temperature' => $options['temperature'] ?? $this->temperature,
                            'maxOutputTokens' => $options['max_tokens'] ?? $this->maxTokens,
                        ]
                    ]
                );

            if ($response->successful()) {
                return $response->json('candidates.0.content.parts.0.text', '');
            }

            Log::error('Gemini API Error', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return 'Error generating content. Please check API configuration.';
        } catch (\Exception $e) {
            Log::error('Gemini Service Exception: ' . $e->getMessage());
            return 'Error connecting to AI service.';
        }
    }

    /**
     * Generate blog content
     */
    public function generateBlogContent(string $topic, array $options = []): string
    {
        $prompt = "Write a comprehensive blog post about: {$topic}\n\n";
        $prompt .= "Include:\n";
        $prompt .= "- A compelling introduction\n";
        $prompt .= "- Well-structured sections with headings\n";
        $prompt .= "- Practical insights and examples\n";
        $prompt .= "- A clear conclusion\n";
        $prompt .= "Format the response in Markdown.";

        return $this->generateContent($prompt, $options);
    }

    /**
     * Optimize content for SEO
     */
    public function optimizeForSEO(string $content, array $keywords = []): string
    {
        $keywordsStr = implode(', ', $keywords);
        $prompt = "Optimize the following content for SEO. Focus on these keywords: {$keywordsStr}\n\n";
        $prompt .= "Original content:\n{$content}\n\n";
        $prompt .= "Provide improved SEO-optimized version while maintaining readability and natural flow.";

        return $this->generateContent($prompt);
    }

    /**
     * Rewrite content
     */
    public function rewriteContent(string $content, array $options = []): string
    {
        $tone = $options['tone'] ?? 'professional';
        $prompt = "Rewrite the following content in a {$tone} tone:\n\n{$content}";

        return $this->generateContent($prompt, $options);
    }

    /**
     * Check if service is available
     */
    public function isAvailable(): bool
    {
        return !empty($this->apiKey);
    }
}

