<?php

namespace Database\Seeders;

use Database\Seeders\Prod\AIToolsContentCreationSeeder;
use Database\Seeders\Prod\AIToolsReplacingJobsSeeder;
use Database\Seeders\Prod\BestBlogTemplatesSeeder;
use Database\Seeders\Prod\BlogTemplatesGuideSeeder;
use Database\Seeders\Prod\CybersecurityTrendsSeeder;
use Database\Seeders\Prod\DockerGuideSeeder;
use Database\Seeders\Prod\FutureWebDevTrendsSeeder;
use Database\Seeders\Prod\KubernetesGuideSeeder;
use Database\Seeders\Prod\LaravelBeginnerGuideSeeder;
use Database\Seeders\Prod\LaravelHerdDBnginSetupSeeder;
use Database\Seeders\Prod\MachineLearningProductionSeeder;
use Database\Seeders\Prod\NginxInstallGuideSeeder;
use Database\Seeders\Prod\React18GuideSeeder;
use Database\Seeders\Prod\WhyUseBlogTemplatesSeeder;
use Illuminate\Database\Seeder;

class ProdBlogPostSeeder extends Seeder
{
    /**
     * Run production blog post seeders.
     */
    public function run(): void
    {
        $this->call([
            BlogTemplatesGuideSeeder::class,
            WhyUseBlogTemplatesSeeder::class,
            NginxInstallGuideSeeder::class,
            LaravelHerdDBnginSetupSeeder::class,
            AIToolsReplacingJobsSeeder::class,
            AIToolsContentCreationSeeder::class,
            CybersecurityTrendsSeeder::class,
            DockerGuideSeeder::class,
            FutureWebDevTrendsSeeder::class,
            KubernetesGuideSeeder::class,
            MachineLearningProductionSeeder::class,
            React18GuideSeeder::class,
            LaravelBeginnerGuideSeeder::class,
            BestBlogTemplatesSeeder::class,
        ]);

        $this->command->info('Production blog posts seeded successfully!');
    }
}
