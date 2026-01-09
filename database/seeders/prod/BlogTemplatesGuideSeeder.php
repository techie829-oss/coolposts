<?php

namespace Database\Seeders\Prod;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;

class BlogTemplatesGuideSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        $content = file_get_contents(database_path('seeders/md/approved/blog-post-templates-guide.md'));

        BlogPost::create([
            'user_id' => $user->id,
            'title' => 'Blog Post Templates: Create Professional Content Faster Without Writing From Scratch',
            'slug' => 'blog-post-templates-create-professional-content-faster',
            'excerpt' => 'Discover ready-to-use blog post templates for tutorials, how-to guides, reviews, news articles, portfolios, and business pages. Save time and publish better content.',
            'content' => $content,
            'type' => 'guide',
            'category' => 'Content Writing',
            'tags' => ['blog templates', 'content templates', 'writing faster', 'seo content'],
            'meta_title' => 'Blog Post Templates: Create Professional Content Faster',
            'meta_description' => 'Discover ready-to-use blog post templates for tutorials, how-to guides, reviews, news articles, portfolios, and business pages.',
            'status' => 'published',
            'published_at' => now()->subDays(2),
            'is_monetized' => false,
            'views' => 210,
            'unique_visitors' => 165,
        ]);
    }
}
