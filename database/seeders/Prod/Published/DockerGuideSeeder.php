<?php

namespace Database\Seeders\Prod\Published;

use Illuminate\Database\Seeder;
use App\Models\BlogPost;
use App\Models\User;

class DockerGuideSeeder extends Seeder
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

        $content = file_get_contents(database_path('seeders/md/published/docker-containerization-guide-2026.md'));

        BlogPost::updateOrCreate(
            ['slug' => 'docker-containerization-guide-2026'],
            [
                'user_id' => $user->id,
                'title' => 'The Complete Guide to Docker and Containerization (2026 Edition)',
                'excerpt' => 'Docker has fundamentally changed how modern applications are built. This guide provides a practical, end-to-end understanding of Docker, covering fundamentals, production deployment, and security.',
                'content' => $content,
                'type' => 'guide',
                'category' => 'DevOps',
                'tags' => ['docker', 'containerization', 'devops', 'kubernetes', '2026'],
                'meta_title' => 'Docker Guide 2026: The Complete Container Handbook',
                'meta_description' => 'The definitive 2026 guide to Docker. Understand containers vs VMs, master Docker Compose, and learn production-ready orchestration tips.',
                'meta_keywords' => ['docker guide', 'containerization', 'devops tools', 'docker vs vm', 'docker tutorial 2026'],
                'canonical_url' => 'https://www.coolposts.site/blog-posts/docker-containerization-guide-2026',
                'status' => 'published',
                'published_at' => now(),
                'is_monetized' => true,
                'views' => 210,
                'unique_visitors' => 180,
            ]
        );

        $this->command->info('Prod Seeder: "Docker Guide 2026" seeded successfully!');
    }
}
