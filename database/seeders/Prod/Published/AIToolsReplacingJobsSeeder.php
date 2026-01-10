<?php

namespace Database\Seeders\Prod\Published;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AIToolsReplacingJobsSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        $content = file_get_contents(database_path('seeders/md/published/ai-tools-replacing-jobs-truth-vs-hype.md'));

        try {
            BlogPost::updateOrCreate(
                ['slug' => 'ai-tools-replacing-jobs-truth-vs-hype-what-really-changes-in-workforce'],
                [
                    'user_id' => $user->id,
                    'title' => 'AI Tools Replacing Jobs: Truth vs Hype',
                    'excerpt' => 'An in-depth analysis of how AI tools are impacting the job market and which roles are most at risk.',
                    'content' => $content,
                    'type' => 'article',
                    'category' => 'Technology',
                    'tags' => ['ai', 'jobs', 'future-of-work', 'technology'],
                    'meta_title' => 'Will AI Replace Your Job? The Truth vs Hype (2026)',
                    'meta_description' => 'Is AI coming for your job? We analyze the real impact of AI on the workforce, which roles are at risk, and how to future-proof your career.',
                    'meta_keywords' => ['ai jobs', 'future of work', 'artificial intelligence', 'automation', 'career trends'],
                    'canonical_url' => 'https://www.coolposts.site/blog-posts/ai-tools-replacing-jobs-truth-vs-hype-what-really-changes-in-workforce',
                    'status' => 'published',
                    'published_at' => now()->subDays(2),
                    'is_monetized' => true,
                    'views' => 5400,
                    'unique_visitors' => 4200,
                ]
            );
        } catch (\Exception $e) {
            $this->command->error("Failed to seed AITools: " . $e->getMessage());
        }
    }
}
