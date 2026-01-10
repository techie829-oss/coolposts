<?php

namespace Database\Seeders\Prod\Published;

use Illuminate\Database\Seeder;
use App\Models\BlogPost;
use App\Models\User;

class MachineLearningProductionSeeder extends Seeder
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

        $content = file_get_contents(database_path('seeders/md/published/machine-learning-in-production.md'));

        BlogPost::updateOrCreate(
            ['slug' => 'machine-learning-in-production-best-practices-challenges'],
            [
                'user_id' => $user->id,
                'title' => 'Machine Learning in Production: Best Practices and Challenges',
                'excerpt' => 'Deploying machine learning models into production is often more complex than building the models themselves. This guide explains how machine learning works in production, the common challenges teams face, and the best practices used in real-world systems.',
                'content' => $content,
                'type' => 'guide',
                'category' => 'Machine Learning',
                'tags' => ['machine-learning', 'mlops', 'production', 'AI'],
                'meta_title' => 'Machine Learning in Production: A Complete Guide',
                'meta_description' => 'Learn best practices for deploying machine learning models in production, covering MLOps, monitoring, drift detection, and scaling strategies.',
                'status' => 'published',
                'published_at' => now(),
                'is_monetized' => true,
                'views' => 45,
                'unique_visitors' => 30,
            ]
        );

        $this->command->info('Prod Seeder: "Machine Learning in Production" seeded successfully!');
    }
}
