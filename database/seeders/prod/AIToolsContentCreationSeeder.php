<?php

namespace Database\Seeders\Prod;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;

class AIToolsContentCreationSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        $content = file_get_contents(database_path('seeders/md/approved/ai-tools-and-content-creation.md'));

        BlogPost::create([
            'user_id' => $user->id,
            'title' => 'How AI Tools Are Changing Content Creation Without Replacing Writers',
            'slug' => 'how-ai-tools-changing-content-creation-without-replacing-writers',
            'excerpt' => 'AI tools are transforming content workflows, but not eliminating writers. Discover how AI assists with drafting and editing while human creativity remains essential.',
            'content' => $content,
            'type' => 'guide',
            'category' => 'Technology & AI',
            'tags' => ['ai', 'content creation', 'writing', 'ai tools'],
            'meta_title' => 'How AI Tools Are Changing Content Creation Without Replacing Writers',
            'meta_description' => 'Learn how AI tools enhance content creation workflows without replacing human writers. Understand the collaboration between AI and creators.',
            'status' => 'published',
            'published_at' => now()->subDays(4),
            'is_monetized' => false,
            'views' => 420,
            'unique_visitors' => 310,
        ]);
    }
}
