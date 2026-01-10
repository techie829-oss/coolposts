<?php

namespace Database\Seeders\Prod\Published;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;

class NginxInstallGuideSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        $content = file_get_contents(database_path('seeders/md/published/nginx-install-ubuntu-guide.md'));

        BlogPost::updateOrCreate(
            ['slug' => 'how-to-install-configure-nginx-ubuntu-beginner-guide'],
            [
                'user_id' => $user->id,
                'title' => 'How to Install and Configure Nginx on Ubuntu (Beginner-Friendly Guide)',
                'excerpt' => 'Learn how to install and configure Nginx on Ubuntu with this beginner-friendly, step-by-step guide. Perfect for developers and bloggers.',
                'content' => $content,
                'type' => 'tutorial',
                'category' => 'Web Development',
                'tags' => ['nginx', 'ubuntu', 'web-server', 'hosting', 'linux'],
                'meta_title' => 'Install Nginx on Ubuntu: Beginner Friendly Guide',
                'meta_description' => 'Host your site in minutes. A step-by-step, beginner-friendly guide to installing and configuring Nginx on Ubuntu servers.',
                'meta_keywords' => ['install nginx ubuntu', 'nginx guide', 'ubuntu web server', 'nginx configuration', 'linux hosting'],
                'canonical_url' => 'https://www.coolposts.site/blog-posts/how-to-install-configure-nginx-ubuntu-beginner-guide',
                'status' => 'published',
                'published_at' => now()->subDay(),
                'is_monetized' => false,
                'views' => 120,
                'unique_visitors' => 95,
            ]
        );
    }
}
