<?php

namespace Database\Seeders\Prod;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AIToolsReplacingJobsSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        $content = file_get_contents(database_path('seeders/md/approved/ai-tools-replacing-jobs-truth-vs-hype.md'));

        try {
            BlogPost::updateOrCreate(
                ['slug' => 'ai-tools-replacing-jobs-truth-vs-hype-what-really-changes-in-workforce'],
                [
                    'user_id' => $user->id,
                    'title' => 'AI Tools Replacing Jobs: Truth vs Hype',
                    'excerpt' => 'An in-depth analysis of how AI tools are impacting the job market and which roles are most at risk.',
                    'content' => $content,
                    'type' => 'opinion',
                    'category' => 'Technology',
                    'tags' => ['ai', 'jobs', 'future-of-work', 'technology'],
                    'meta_title' => 'Will AI Replace Your Job? The Truth vs Hype',
                    'meta_description' => 'Explore the reality of AI replacing jobs versus augmenting them. Understand the future of work in the age of AI.',
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
