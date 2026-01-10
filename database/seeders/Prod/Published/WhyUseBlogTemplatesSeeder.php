<?php

namespace Database\Seeders\Prod\Published;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;

class WhyUseBlogTemplatesSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        $content = file_get_contents(database_path('seeders/md/published/why-use-blog-post-templates.md'));

        BlogPost::updateOrCreate(
            ['slug' => 'why-blog-post-templates-matter-consistent-high-quality-content'],
            [
                'user_id' => $user->id,
                'title' => 'Why Blog Post Templates Matter',
                'excerpt' => 'Discover the key benefits of using templates for your blog posts, from saving time to improving SEO.',
                'content' => $content,
                'type' => 'article',
                'category' => 'Blogging Tips',
                'tags' => ['blogging', 'templates', 'efficiency'],
                'meta_title' => 'Why You Should Use Blog Post Templates',
                'meta_description' => 'Learn why blog post templates are essential for efficient and consistent content creation.',
                'status' => 'published',
                'published_at' => now()->subDays(15),
                'is_monetized' => false,
                'views' => 890,
                'unique_visitors' => 600,
            ]
        );
    }
}
