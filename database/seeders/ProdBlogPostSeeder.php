<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class ProdBlogPostSeeder extends Seeder
{
    /**
     * Run production blog post seeders.
     */
    public function run(): void
    {
        $this->call([
            // Moved to Prod/Published folder - already seeded
            \Database\Seeders\Prod\Published\BlogTemplatesGuideSeeder::class,
            \Database\Seeders\Prod\Published\WhyUseBlogTemplatesSeeder::class,
            \Database\Seeders\Prod\Published\NginxInstallGuideSeeder::class,
            \Database\Seeders\Prod\Published\LaravelHerdDBnginSetupSeeder::class,
            \Database\Seeders\Prod\Published\AIToolsReplacingJobsSeeder::class,
            \Database\Seeders\Prod\Published\AIToolsContentCreationSeeder::class,
            \Database\Seeders\Prod\Published\CybersecurityTrendsSeeder::class,
            \Database\Seeders\Prod\Published\DockerGuideSeeder::class,
            \Database\Seeders\Prod\Published\FutureWebDevTrendsSeeder::class,
            \Database\Seeders\Prod\Published\KubernetesGuideSeeder::class,
            \Database\Seeders\Prod\Published\MachineLearningProductionSeeder::class,
            \Database\Seeders\Prod\Published\React18GuideSeeder::class,
            \Database\Seeders\Prod\Published\LaravelBeginnerGuideSeeder::class,
            \Database\Seeders\Prod\Published\BestBlogTemplatesSeeder::class,
        ]);

        $this->command->info('Production blog posts seeded successfully!');
    }
}
