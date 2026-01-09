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

        $content = file_get_contents(database_path('seeders/md/ai-tools-replacing-jobs-truth-vs-hype.md'));

        BlogPost::create([
            'user_id' => $user->id,
            'title' => 'AI Tools Replacing Jobs: Truth vs Hype — What Really Changes in the Workforce',
            'slug' => 'ai-tools-replacing-jobs-truth-vs-hype-what-really-changes-in-workforce',
            'excerpt' => 'AI automation is reshaping the workforce, but not in the way headlines suggest. Learn what\'s actually changing, which jobs are affected, and how professionals should prepare.',
            'content' => $content,
            'type' => 'guide',
            'category' => 'Technology & AI',
            'tags' => ['ai', 'automation', 'jobs', 'workforce', 'future-of-work'],
            'meta_title' => 'AI Tools Replacing Jobs: Truth vs Hype — What Really Changes',
            'meta_description' => 'Separating AI job automation hype from reality. Understand which jobs are affected, what skills matter, and how to prepare for an AI-enabled workplace.',
            'status' => 'published',
            'published_at' => now()->subDays(1),
            'is_monetized' => false,
            'views' => 580,
            'unique_visitors' => 420,
        ]);
    }
}
