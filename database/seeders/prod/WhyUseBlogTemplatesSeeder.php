<?php

namespace Database\Seeders\Prod;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;

class WhyUseBlogTemplatesSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        $content = file_get_contents(database_path('seeders/md/approved/why-use-blog-post-templates.md'));

        BlogPost::create([
            'user_id' => $user->id,
            'title' => 'Why Blog Post Templates Matter for Consistent, High-Quality Content',
            'slug' => 'why-blog-post-templates-matter-consistent-high-quality-content',
            'excerpt' => 'Creating consistent, high-quality blog content is challenging. Learn how blog post templates solve this problem by providing repeatable frameworks for better structure, SEO, and efficiency.',
            'content' => $content,
            'type' => 'guide',
            'category' => 'Content Writing',
            'tags' => ['blog templates', 'content writing', 'blogging', 'content strategy'],
            'meta_title' => 'Why Blog Post Templates Matter for Consistent, High-Quality Content',
            'meta_description' => 'Blog post templates provide repeatable frameworks that ensure clarity, consistency, and professionalism. Learn why they matter for content creators.',
            'status' => 'published',
            'published_at' => now()->subDays(3),
            'is_monetized' => false,
            'views' => 350,
            'unique_visitors' => 280,
        ]);
    }
}
