<?php

namespace Database\Seeders\Prod;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;

class LaravelHerdDBnginSetupSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        $content = file_get_contents(database_path('seeders/md/approved/how-to-laravel-herd-dbngin-macos.md'));

        BlogPost::create([
            'user_id' => $user->id,
            'title' => 'How to Set Up Laravel Development Environment with Herd and DBngin on macOS',
            'slug' => 'how-to-setup-laravel-herd-dbngin-macos',
            'excerpt' => 'Learn how to set up a modern Laravel development environment on macOS using Herd and DBngin. Streamline PHP versions, web servers, and database management for faster local development.',
            'content' => $content,
            'type' => 'tutorial',
            'category' => 'Web Development',
            'tags' => ['laravel', 'herd', 'dbngin', 'macos', 'php', 'local-development'],
            'meta_title' => 'How to Set Up Laravel with Herd and DBngin on macOS',
            'meta_description' => 'Complete guide to setting up Laravel development environment on macOS with Herd and DBngin. Manage PHP versions and databases effortlessly.',
            'status' => 'published',
            'published_at' => now()->subDays(6),
            'is_monetized' => false,
            'views' => 280,
            'unique_visitors' => 215,
        ]);
    }
}
