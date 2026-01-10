<?php

namespace Database\Seeders\Prod\Published;

use Illuminate\Database\Seeder;
use App\Models\BlogPost;
use App\Models\User;

class FutureWebDevTrendsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        // Fallback if no user exists, though unlikely in prod seed env
        if (!$user) {
            $user = User::factory()->create();
        }

        $content = file_get_contents(database_path('seeders/md/published/future-web-development-trends-2025-2026.md'));

        BlogPost::updateOrCreate(
            ['slug' => 'future-web-development-trends-2025-2026-outlook'],
            [
                'user_id' => $user->id,
                'title' => 'The Future of Web Development: Trends Shaping 2025 and Beyond (2026 Outlook)',
                'excerpt' => 'An in-depth look at AI-native workflows, edge computing, framework consolidation, and the technologies developers must master for 2026.',
                'content' => $content,
                'type' => 'article',
                'category' => 'Technology',
                'tags' => ['web-development', 'trends', 'ai', 'edge-computing', '2026'],
                'meta_title' => 'Web Development Trends 2025-2026: The Future of Code',
                'meta_description' => 'What does 2026 hold for web dev? From AI-native workflows to edge architecture, discover the technologies defining the future of code.',
                'meta_keywords' => ['web development trends 2026', 'future of coding', 'AI in web dev', 'edge computing', 'frontend trends'],
                'canonical_url' => 'https://www.coolposts.site/blog-posts/future-web-development-trends-2025-2026-outlook',
                'status' => 'published',
                'published_at' => now(), // Keeps current time on run, or could be set to a fixed recent date
                'is_monetized' => true,
                'views' => 150,
                'unique_visitors' => 120,
            ]
        );

        $this->command->info('Prod Seeder: "Future of Web Development" seeded successfully!');
    }
}
