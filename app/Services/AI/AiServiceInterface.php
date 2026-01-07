<?php

namespace App\Services\AI;

interface AiServiceInterface
{
    /**
     * Generate content based on prompt
     */
    public function generateContent(string $prompt, array $options = []): string;

    /**
     * Generate blog content
     */
    public function generateBlogContent(string $topic, array $options = []): string;

    /**
     * Optimize content for SEO
     */
    public function optimizeForSEO(string $content, array $keywords = []): string;

    /**
     * Rewrite content
     */
    public function rewriteContent(string $content, array $options = []): string;

    /**
     * Check if service is available
     */
    public function isAvailable(): bool;
}

