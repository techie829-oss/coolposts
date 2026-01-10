<?php

namespace Database\Seeders\Prod;

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

        $content = file_get_contents(database_path('seeders/md/approved/future-web-development-trends-2025-2026.md'));

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
                'meta_description' => 'Explore the top web development trends for 2025 and 2026, including AI-native workflows, edge architecture, and framework consolidation.',
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
