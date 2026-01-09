<?php

namespace Database\Seeders\Prod;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;

class NginxInstallGuideSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        $content = file_get_contents(database_path('seeders/md/approved/nginx-install-ubuntu-guide.md'));

        BlogPost::create([
            'user_id' => $user->id,
            'title' => 'How to Install and Configure Nginx on Ubuntu (Beginner-Friendly Guide)',
            'slug' => 'how-to-install-configure-nginx-ubuntu-beginner-guide',
            'excerpt' => 'Learn how to install and configure Nginx on Ubuntu with this beginner-friendly, step-by-step guide. Perfect for developers and bloggers.',
            'content' => $content,
            'type' => 'tutorial',
            'category' => 'Web Development',
            'tags' => ['nginx', 'ubuntu', 'web-server', 'hosting', 'linux'],
            'meta_title' => 'How to Install and Configure Nginx on Ubuntu',
            'meta_description' => 'Step-by-step guide to install and configure Nginx on Ubuntu. Beginner-friendly with production-ready practices.',
            'status' => 'published',
            'published_at' => now()->subDay(),
            'is_monetized' => false,
            'views' => 120,
            'unique_visitors' => 95,
        ]);
    }
}
