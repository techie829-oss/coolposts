<?php

namespace Database\Seeders\Prod;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;

class LaravelHerdWindowsSetupSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        $content = file_get_contents(database_path('seeders/md/approved/how-to-laravel-herd-windows.md'));

        BlogPost::create([
            'user_id' => $user->id,
            'title' => 'How to Set Up Laravel Development Environment with Herd on Windows',
            'slug' => 'how-to-setup-laravel-herd-windows',
            'excerpt' => 'Complete guide to setting up Laravel development on Windows using Herd. Learn how to manage PHP versions, databases, and local sites effortlessly on Windows 10 and 11.',
            'content' => $content,
            'type' => 'tutorial',
            'category' => 'Web Development',
            'tags' => ['laravel', 'herd', 'windows', 'php', 'local-development'],
            'meta_title' => 'How to Set Up Laravel with Herd on Windows',
            'meta_description' => 'Step-by-step guide for Laravel development on Windows using Herd. Manage PHP versions and databases without complex configuration.',
            'status' => 'published',
            'published_at' => now()->subDays(7),
            'is_monetized' => false,
            'views' => 340,
            'unique_visitors' => 255,
        ]);
    }
}
