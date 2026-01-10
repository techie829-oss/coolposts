<?php

namespace Database\Seeders\Prod\Published;

use Illuminate\Database\Seeder;
use App\Models\BlogPost;
use App\Models\User;

class CybersecurityTrendsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create();
        }

        $content = file_get_contents(database_path('seeders/md/published/cybersecurity-trends-2026.md'));

        BlogPost::updateOrCreate(
            ['slug' => 'cybersecurity-trends-2026-post-ai-era'],
            [
                'user_id' => $user->id,
                'title' => 'Cybersecurity Trends 2026: Protecting Digital Assets in a Post-AI Era',
                'excerpt' => 'Cybersecurity has entered a new phase in 2026. This article reflects post-2025 realities and trends, specifically focusing on what organizations are actually facing today including ransomware cartels and AI-driven attacks.',
                'content' => $content,
                'type' => 'article',
                'category' => 'Cybersecurity',
                'tags' => ['cybersecurity', '2026', 'ai-security', 'zero-trust', 'ransomware'],
                'meta_title' => 'Cybersecurity Trends 2026: Post-AI Era Defense',
                'meta_description' => 'Discover the top cybersecurity trends of 2026, from AI-driven attacks and ransomware syndicates to Zero Trust and XDR adoption.',
                'status' => 'published',
                'published_at' => now(),
                'is_monetized' => true,
                'views' => 88,
                'unique_visitors' => 65,
            ]
        );

        $this->command->info('Prod Seeder: "Cybersecurity Trends 2026" seeded successfully!');
    }
}
