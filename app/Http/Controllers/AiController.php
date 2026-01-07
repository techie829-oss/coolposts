<?php

namespace App\Http\Controllers;

use App\Models\AiSetting;
use App\Services\AI\AiServiceFactory;
use Illuminate\Http\Request;

class AiController extends Controller
{
    /**
     * Generate blog content using AI
     */
    public function generateBlog(Request $request)
    {
        // No strict validation - allow flexible parameters based on action
        $request->validate([
            'action' => 'nullable|string',
            'model' => 'nullable|string',
        ]);

        $aiSettings = AiSetting::getSettings();

        if (!$aiSettings->blog_generation_enabled) {
            return response()->json([
                'success' => false,
                'message' => 'AI blog generation is disabled in settings.'
            ], 403);
        }

        // Get the AI service - extract provider from model
        $model = $request->model ?? 'gemini-2.5-flash'; // e.g., "gemini-2.5-flash"
        $provider = 'gemini'; // We only support Gemini for now

        \Log::info('AI Request', [
            'action' => $request->action,
            'model' => $model,
            'provider' => $provider
        ]);

        // Get the AI service with custom model
        $aiService = AiServiceFactory::create($provider, $model);

        \Log::info('AI Service Creation', [
            'service_exists' => $aiService !== null,
            'service_class' => $aiService ? get_class($aiService) : 'null'
        ]);

        if (!$aiService) {
            \Log::error('AI Service creation failed', [
                'provider' => $provider,
                'model' => $model,
                'ai_settings' => [
                    'gemini_enabled' => $aiSettings->gemini_enabled,
                    'has_api_key' => !empty($aiSettings->gemini_api_key)
                ]
            ]);

            return response()->json([
                'success' => false,
                'message' => 'AI service not available. Please check your API configuration.'
            ], 503);
        }

        try {
            $action = $request->action ?? 'improve';
            $content = '';

            switch ($action) {
                case 'generate_blog':
                    return $this->generateFullBlog($request, $aiService);
                case 'improve':
                    $content = $this->improveText($request, $aiService);
                    break;
                case 'optimize':
                    $content = $this->optimizeText($request, $aiService);
                    break;
                case 'expand':
                    $content = $this->expandText($request, $aiService);
                    break;
                case 'simplify':
                    $content = $this->simplifyText($request, $aiService);
                    break;
                case 'generate':
                    $content = $this->generateSection($request, $aiService);
                    break;
                default:
                    throw new \Exception('Invalid action specified');
            }

            return response()->json([
                'success' => true,
                'content' => $content
            ]);

        } catch (\Exception $e) {
            \Log::error('AI Generation Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to process content: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Build prompt based on post type and settings
     */
    private function buildPrompt(Request $request): string
    {
        $type = $request->type ?? 'article';
        $title = $request->title;
        $category = $request->category;
        $tags = $request->tags;
        $length = $request->length;
        $tone = $request->tone;
        $contentType = $request->content_type ?? 'markdown';

        // Base prompt
        $prompt = "Write a comprehensive blog post about: {$title}\n\n";

        // Add context
        if ($category) {
            $prompt .= "Category: {$category}\n";
        }
        if ($tags) {
            $prompt .= "Tags: {$tags}\n";
        }

        // Add type-specific instructions
        $prompt .= $this->getTypeSpecificInstructions($type);

        // Add length instruction
        $prompt .= $this->getLengthInstructions($length);

        // Add tone instruction
        $prompt .= "Tone: {$tone}\n\n";

        // Add content format instructions
        $prompt .= $this->getContentFormatInstructions($contentType);

        // Common requirements
        $prompt .= "Requirements:\n";
        $prompt .= "- Include clear headings (## for main sections, ### for subsections)\n";
        $prompt .= "- Use proper paragraph structure\n";
        $prompt .= "- Include relevant examples where applicable\n";
        $prompt .= "- End with a strong conclusion\n";

        // Add excerpt if provided
        if ($request->excerpt) {
            $prompt .= "- Opening should align with this summary: {$request->excerpt}\n";
        }

        $prompt .= "\nStart writing the blog post now:";

        return $prompt;
    }

    /**
     * Get type-specific instructions
     */
    private function getTypeSpecificInstructions(string $type): string
    {
        return match($type) {
            'tutorial' => "Format: Step-by-step tutorial with numbered sections, code examples, and clear instructions.\n",
            'review' => "Format: Product/service review with ratings, pros/cons, features analysis, and final verdict.\n",
            'news' => "Format: News article with lead paragraph, background context, current events, and future implications.\n",
            'guide' => "Format: How-to guide with prerequisites, step-by-step instructions, and troubleshooting tips.\n",
            'article' => "Format: Comprehensive article with detailed descriptions and in-depth analysis.\n",
            'case_study' => "Format: Case study with problem statement, solution approach, results, and lessons learned.\n",
            'list' => "Format: Numbered list format (top 10/7/5) with detailed descriptions for each item.\n",
            'business_page' => "Format: Business page with company overview, services, benefits, and contact information.\n",
            'company_portfolio' => "Format: Company portfolio with projects, team, expertise, and client testimonials.\n",
            'personal_portfolio' => "Format: Personal portfolio with skills, work experience, projects, and achievements.\n",
            default => "Format: Comprehensive article with introduction, main sections, and conclusion.\n"
        };
    }

    /**
     * Get length instructions
     */
    private function getLengthInstructions(string $length): string
    {
        return match($length) {
            'short' => "Length: 300-500 words. Keep it concise and to the point.\n",
            'medium' => "Length: 500-1000 words. Provide thorough coverage with examples.\n",
            'long' => "Length: 1000-2000 words. Comprehensive deep-dive with extensive details.\n",
            default => "Length: 500-1000 words. Provide thorough coverage.\n"
        };
    }

    /**
     * Get max tokens based on length
     */
    private function getMaxTokens(string $length): int
    {
        return match($length) {
            'short' => 500,
            'medium' => 1000,
            'long' => 2000,
            default => 1000
        };
    }

    /**
     * Extract excerpt from content
     */
    private function extractExcerpt(string $content): string
    {
        // Get first paragraph or first 150 characters
        $paragraphs = explode("\n\n", $content);
        $firstParagraph = $paragraphs[0] ?? '';

        // Remove markdown formatting
        $excerpt = strip_tags(preg_replace('/#+\s+/', '', $firstParagraph));

        // Limit to 150 characters
        if (strlen($excerpt) > 150) {
            $excerpt = substr($excerpt, 0, 150) . '...';
        }

        return $excerpt;
    }

    /**
     * Get content format instructions based on content type
     */
    private function getContentFormatInstructions(string $contentType): string
    {
        switch ($contentType) {
            case 'html':
                return "IMPORTANT: Format the response as RAW HTML code only. Use HTML tags like <h1>, <h2>, <p>, <strong>, <em>, <ul>, <ol>, <li>, <a>, <img>, etc. Do NOT use Markdown syntax. Return pure HTML.\n\n";

            case 'text':
            case 'plaintext':
                return "IMPORTANT: Write the content as plain text only. No formatting, no HTML, no Markdown. Just clean text with line breaks between paragraphs. Use double line breaks to separate paragraphs.\n\n";

            case 'markdown':
            default:
                return "IMPORTANT: Format the response as Markdown. Use # for h1, ## for h2, ### for h3, ** for bold, * for italic, - for lists, [text](url) for links, ![alt](url) for images, ``` for code blocks.\n\n";
        }
    }

    /**
     * Get image category from prompt for PlaceIMG
     */
    private function getImageCategoryFromPrompt(string $prompt): string
    {
        $promptLower = strtolower($prompt);

        if (preg_match('/person|people|human|portrait|face|man|woman/', $promptLower)) {
            return 'people';
        } elseif (preg_match('/animal|cat|dog|bird|wildlife/', $promptLower)) {
            return 'animals';
        } elseif (preg_match('/tech|computer|phone|device|gadget/', $promptLower)) {
            return 'tech';
        } elseif (preg_match('/nature|landscape|tree|forest|mountain/', $promptLower)) {
            return 'nature';
        } elseif (preg_match('/architecture|building|house|city/', $promptLower)) {
            return 'arch';
        } else {
            return 'any';
        }
    }

    /**
     * Analyze uploaded image
     */
    public function analyzeImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:10240', // 10MB max
            'context' => 'nullable|string',
        ]);

        try {
            // Store the image
            $imagePath = $request->file('image')->store('blog-images', 'public');
            $imageUrl = asset('storage/' . $imagePath);

            // Use Gemini Vision to analyze the image
            $aiService = AiServiceFactory::create('gemini', 'gemini-pro-vision');

            if ($aiService) {
                // Build analysis prompt
                $prompt = "Analyze this image and provide:\n";
                $prompt .= "1. A detailed description\n";
                $prompt .= "2. A caption suitable for a blog post\n";
                $prompt .= "3. Alt text for accessibility\n";

                if ($request->context) {
                    $prompt .= "\nContext: {$request->context}";
                }

                $analysis = $aiService->generateContent($prompt);

                // Parse the analysis
                $lines = explode("\n", $analysis);
                $description = $lines[1] ?? '';
                $caption = $lines[3] ?? '';
                $altText = $lines[5] ?? 'Image description';
            } else {
                // Fallback if AI is not available
                $description = 'Uploaded image';
                $caption = 'Uploaded image';
                $altText = $request->context ?: 'Uploaded image';
            }

            return response()->json([
                'success' => true,
                'imagePath' => $imageUrl,
                'description' => $description,
                'caption' => $caption,
                'altText' => $altText
            ]);

        } catch (\Exception $e) {
            \Log::error('Image Analysis Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to analyze image: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate image from content
     */
    public function generateImage(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'aspect_ratio' => 'required|string',
            'style' => 'required|string',
            'type' => 'required|string',
        ]);

        try {
            // Use Gemini to create a prompt for image generation
            $aiService = AiServiceFactory::create('gemini');

            if (!$aiService) {
                return response()->json([
                    'success' => false,
                    'message' => 'AI service not available'
                ], 503);
            }

            // Analyze content and create image prompt
            $prompt = "Based on this blog post content, create a detailed image description for generation:\n\n";
            $prompt .= "Title: {$request->title}\n";
            $prompt .= "Content: " . substr($request->input('content', ''), 0, 1000) . "\n\n";
            $prompt .= "Create a visual description that represents the main theme. ";
            $prompt .= "Style: {$request->style}. ";
            $prompt .= "Aspect ratio: {$request->aspect_ratio}.\n";
            $prompt .= "Return only the image description.";

            $imagePrompt = $aiService->generateContent($prompt);

            // Calculate dimensions based on aspect ratio
            $width = $request->aspect_ratio === '1:1' ? 800 : ($request->aspect_ratio === '9:16' ? 600 : 1200);
            $height = $request->aspect_ratio === '1:1' ? 800 : ($request->aspect_ratio === '9:16' ? 1066 : 630);

            // Use Picsum Photos for reliable random photos (free, reliable)
            $category = $this->getImageCategoryFromPrompt($imagePrompt);

            // Picsum provides high-quality random photos from Unsplash
            $picsumUrl = "https://picsum.photos/{$width}/{$height}?random=" . time();

            // Try to download and store locally
            try {
                $ch = curl_init($picsumUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
                curl_setopt($ch, CURLOPT_TIMEOUT, 10);
                curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');

                $imageContent = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpCode === 200 && $imageContent && strlen($imageContent) > 1000) {
                    $imageName = 'ai-generated-' . time() . '-' . rand(1000, 9999) . '.jpg';
                    $imagePath = 'blog-images/' . $imageName;

                    \Storage::disk('public')->put($imagePath, $imageContent);
                    $imageUrl = asset('storage/' . $imagePath);

                    return response()->json([
                        'success' => true,
                        'imagePath' => $imageUrl,
                        'prompt' => $imagePrompt,
                        'caption' => 'AI Generated image for: ' . $request->title,
                        'altText' => $request->title,
                        'width' => $width,
                        'height' => $height,
                        'note' => 'Realistic photo generated from Picsum'
                    ]);
                }
            } catch (\Exception $downloadError) {
                \Log::warning('Failed to download Picsum image: ' . $downloadError->getMessage());
            }

            // If Unsplash fails, create a better looking gradient placeholder
            $titleText = htmlspecialchars(substr($request->title, 0, 40));
            $svg = '<?xml version="1.0" encoding="UTF-8"?>
<svg width="' . $width . '" height="' . $height . '" xmlns="http://www.w3.org/2000/svg">
    <defs>
        <linearGradient id="grad1" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:#1e293b;stop-opacity:1" />
            <stop offset="50%" style="stop-color:#475569;stop-opacity:1" />
            <stop offset="100%" style="stop-color:#64748b;stop-opacity:1" />
        </linearGradient>
        <radialGradient id="glow" cx="50%" cy="50%">
            <stop offset="0%" style="stop-color:#ffffff;stop-opacity:0.3" />
            <stop offset="100%" style="stop-color:#ffffff;stop-opacity:0" />
        </radialGradient>
    </defs>
    <rect width="' . $width . '" height="' . $height . '" fill="url(#grad1)"/>
    <rect width="' . $width . '" height="' . $height . '" fill="url(#glow)"/>
    <text x="50%" y="50%" font-family="Arial, sans-serif" font-size="28" font-weight="bold"
        fill="white" text-anchor="middle" dominant-baseline="middle" opacity="0.9">' . $titleText . '</text>
    <text x="50%" y="60%" font-family="Arial, sans-serif" font-size="14"
        fill="white" text-anchor="middle" dominant-baseline="middle" opacity="0.6">Professional Blog Image</text>
</svg>';

            $base64Svg = 'data:image/svg+xml;base64,' . base64_encode($svg);

            return response()->json([
                'success' => true,
                'imagePath' => $base64Svg,
                'prompt' => $imagePrompt,
                'caption' => 'AI Generated image for: ' . $request->title,
                'altText' => $request->title,
                'width' => $width,
                'height' => $height,
                'note' => 'Placeholder - Real AI image generation requires Imagen API setup'
            ]);

        } catch (\Exception $e) {
            \Log::error('Image Generation Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate image: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Improve selected text
     */
    private function improveText(Request $request, $aiService): string
    {
        $text = $request->selected_text;
        if (!$text) {
            throw new \Exception('No text selected to improve');
        }

        $prompt = "Improve the following Markdown text. Make it more engaging, clear, and professional while maintaining the original meaning and structure.\n\n";
        $prompt .= "Original text:\n{$text}\n\n";
        $prompt .= "Return only the improved Markdown text.";

        return $aiService->generateContent($prompt);
    }

    /**
     * Optimize and polish text
     */
    private function optimizeText(Request $request, $aiService): string
    {
        $text = $request->selected_text;
        if (!$text) {
            throw new \Exception('No text selected to optimize');
        }

        $prompt = "Optimize the following Markdown text for better readability, flow, and engagement. Improve word choice, fix any awkward phrasing, and enhance clarity.\n\n";
        $prompt .= "Original text:\n{$text}\n\n";
        $prompt .= "Return only the optimized Markdown text.";

        return $aiService->generateContent($prompt);
    }

    /**
     * Expand text with more detail
     */
    private function expandText(Request $request, $aiService): string
    {
        $text = $request->selected_text;
        if (!$text) {
            throw new \Exception('No text selected to expand');
        }

        $context = $request->context ?: 'Add more detail and explanation';

        $prompt = "Expand the following Markdown text with more depth, examples, and explanation. {$context}\n\n";
        $prompt .= "Original text:\n{$text}\n\n";
        $prompt .= "Return only the expanded Markdown text.";

        return $aiService->generateContent($prompt);
    }

    /**
     * Simplify text language
     */
    private function simplifyText(Request $request, $aiService): string
    {
        $text = $request->selected_text;
        if (!$text) {
            throw new \Exception('No text selected to simplify');
        }

        $prompt = "Simplify the following Markdown text. Use simpler words, shorter sentences, and clearer explanations while keeping the main points.\n\n";
        $prompt .= "Original text:\n{$text}\n\n";
        $prompt .= "Return only the simplified Markdown text.";

        return $aiService->generateContent($prompt);
    }

    /**
     * Generate a new section
     */
    private function generateSection(Request $request, $aiService): string
    {
        $context = $request->context;
        if (!$context) {
            throw new \Exception('Please provide context for what to generate');
        }

        $postContext = $request->post_context ?? [];

        $prompt = "Generate a concise Markdown section for a blog post.\n\n";

        if (!empty($postContext)) {
            $prompt .= "Post Context:\n";
            if (isset($postContext['title'])) $prompt .= "Title: {$postContext['title']}\n";
            if (isset($postContext['type'])) $prompt .= "Type: {$postContext['type']}\n";
            if (isset($postContext['category'])) $prompt .= "Category: {$postContext['category']}\n";
        }

        $prompt .= "\nRequest: {$context}\n";
        $prompt .= "\nReturn a well-formatted Markdown section (100-300 words).";

        return $aiService->generateContent($prompt);
    }

    /**
     * Generate full blog in markdown format
     */
    private function generateFullBlog(Request $request, $aiService)
    {
        $postContext = $request->post_context ?? [];

        if (empty($postContext) || !isset($postContext['title'])) {
            throw new \Exception('Please provide title for blog generation');
        }

        $title = $postContext['title'];
        $type = $postContext['type'] ?? 'article';
        $category = $postContext['category'] ?? '';

        // Build comprehensive prompt for full blog
        $prompt = "Write a comprehensive blog post in MARKDOWN format.\n\n";
        $prompt .= "Title: {$title}\n";

        if ($category) {
            $prompt .= "Category: {$category}\n";
        }

        $prompt .= "Type: {$type}\n\n";

        // Add type-specific structure
        $prompt .= $this->getTypeSpecificInstructions($type);

        // Content structure
        $prompt .= "Structure:\n";
        $prompt .= "- Start with a compelling introduction\n";
        $prompt .= "- Include 3-4 main sections with clear headings (## for main, ### for sub)\n";
        $prompt .= "- Use bullet points and lists where appropriate\n";
        $prompt .= "- Include practical examples or insights\n";
        $prompt .= "- End with a strong conclusion\n\n";

        $prompt .= "IMPORTANT: Return ONLY valid Markdown. Use proper formatting (#, ##, ###, **, *, -, etc.)\n";

        // Get optional parameters
        $length = $request->length ?? 'medium';
        $tone = $request->tone ?? 'professional';

        // Set word count based on length
        $wordCount = [
            'short' => '300-500',
            'medium' => '500-1000',
            'long' => '1000-2000'
        ][$length] ?? '500-1000';

        $prompt .= "Length: {$wordCount} words\n";
        $prompt .= "Tone: {$tone}\n\n";

        $prompt .= "Start writing the blog post now:\n\n";

        // Generate content with higher token limit for full blogs
        $content = $aiService->generateContent($prompt, [
            'max_tokens' => 4000, // Increased from 2000 to support longer blogs
            'temperature' => 0.7
        ]);

        // Extract excerpt
        $excerpt = $this->extractExcerpt($content);

        return response()->json([
            'success' => true,
            'content' => $content,
            'excerpt' => $excerpt
        ]);
    }
}
