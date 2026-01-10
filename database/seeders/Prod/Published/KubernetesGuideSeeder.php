<?php

namespace Database\Seeders\Prod\Published;

use Illuminate\Database\Seeder;
use App\Models\BlogPost;
use App\Models\User;

class KubernetesGuideSeeder extends Seeder
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

        $content = file_get_contents(database_path('seeders/md/published/kubernetes-beginners-guide-2026.md'));

        BlogPost::updateOrCreate(
            ['slug' => 'kubernetes-beginners-guide-2026'],
            [
                'user_id' => $user->id,
                'title' => 'Kubernetes for Beginners: Container Orchestration Made Simple (2026 Edition)',
                'excerpt' => 'Kubernetes has become the industry standard for running containerized applications at scale. This beginner-friendly guide explains Kubernetes from the ground up, covering core concepts, architecture, and practical usage.',
                'content' => $content,
                'type' => 'guide',
                'category' => 'DevOps',
                'tags' => ['kubernetes', 'k8s', 'container-orchestration', 'devops', '2026'],
                'meta_title' => 'Kubernetes for Beginners (2026): The Ultimate Guide',
                'meta_description' => 'Master Kubernetes in 2026. A clear, beginner-friendly guide to Pods, Services, and Deployments. Learn why K8s is the operating system of the cloud.',
                'meta_keywords' => ['kubernetes tutorial', 'k8s guide', 'container orchestration', 'devops', 'docker vs kubernetes'],
                'canonical_url' => 'https://www.coolposts.site/blog-posts/kubernetes-beginners-guide-2026',
                'status' => 'published',
                'published_at' => now(),
                'is_monetized' => true,
                'views' => 320,
                'unique_visitors' => 280,
            ]
        );

        $this->command->info('Prod Seeder: "Kubernetes Guide 2026" seeded successfully!');
    }
}
