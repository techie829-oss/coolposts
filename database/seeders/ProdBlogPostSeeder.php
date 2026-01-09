<?php

namespace Database\Seeders;

use Database\Seeders\Prod\AIToolsContentCreationSeeder;
use Database\Seeders\Prod\AIToolsReplacingJobsSeeder;
use Database\Seeders\Prod\BlogTemplatesGuideSeeder;
use Database\Seeders\Prod\LaravelHerdDBnginSetupSeeder;
use Database\Seeders\Prod\LaravelHerdWindowsSetupSeeder;
use Database\Seeders\Prod\NginxInstallGuideSeeder;
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
            LaravelHerdWindowsSetupSeeder::class,
            AIToolsReplacingJobsSeeder::class,
            AIToolsContentCreationSeeder::class,
        ]);

        $this->command->info('Production blog posts seeded successfully!');
    }
}
