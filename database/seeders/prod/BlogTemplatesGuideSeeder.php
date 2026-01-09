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

        BlogPost::updateOrCreate(
            ['slug' => 'blog-post-templates-create-professional-content-faster'],
            [
                'user_id' => $user->id,
                'title' => 'The Ultimate Guide to Blog Post Templates',
                'excerpt' => 'Learn how to use blog post templates to streamline your content creation process and maintain consistency across your blog.',
                'content' => $content,
                'type' => 'tutorial',
                'category' => 'Content Strategy',
                'tags' => ['blogging', 'templates', 'content-creation', 'productivity'],
                'meta_title' => 'Blog Post Templates Guide: Streamline Your Writing',
                'meta_description' => 'A comprehensive guide on using blog post templates to write faster and more consistently.',
                'status' => 'published',
                'published_at' => now()->subDays(10),
                'is_monetized' => true,
                'views' => 1250,
                'unique_visitors' => 950,
            ]
        );
    }
}
