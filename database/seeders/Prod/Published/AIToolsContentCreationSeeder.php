<?php

namespace Database\Seeders\Prod\Published;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;

class AIToolsContentCreationSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        $content = file_get_contents(database_path('seeders/md/published/ai-tools-and-content-creation.md'));

        BlogPost::updateOrCreate(
            ['slug' => 'how-ai-tools-changing-content-creation-without-replacing-writers'],
            [
                'user_id' => $user->id,
                'title' => 'How AI Tools Are Changing Content Creation',
                'excerpt' => 'Explore the revolutionary impact of AI on content creation workflows, from writing to video production.',
                'content' => $content,
                'type' => 'article',
                'category' => 'Artificial Intelligence',
                'tags' => ['ai', 'content-creation', 'chatgpt', 'midjourney'],
                'meta_title' => 'AI in Content Creation: A Revolution',
                'meta_description' => 'Will AI replace writers? See how tools like ChatGPT and Midjourney are transforming creation workflows, boosting creativity, not killing it.',
                'meta_keywords' => ['ai content creation', 'chatgpt writing', 'midjourney art', 'ai writing tools', 'content revolution'],
                'canonical_url' => 'https://www.coolposts.site/blog-posts/how-ai-tools-changing-content-creation-without-replacing-writers',
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'is_monetized' => true,
                'views' => 3200,
                'unique_visitors' => 2800,
            ]
        );
    }
}
