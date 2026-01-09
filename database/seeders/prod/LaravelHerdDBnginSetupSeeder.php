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

        // macOS Guide
        $macContent = file_get_contents(database_path('seeders/md/approved/how-to-laravel-herd-dbngin-macos.md'));

        BlogPost::create([
            'user_id' => $user->id,
            'title' => 'How to Set Up Laravel Development Environment with Herd and DBngin on macOS',
            'slug' => 'how-to-setup-laravel-herd-dbngin-macos',
            'excerpt' => 'Learn how to set up a modern Laravel development environment on macOS using Herd and DBngin. Streamline PHP versions, web servers, and database management for faster local development.',
            'content' => $macContent,
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

        // Windows Guide
        $windowsContent = file_get_contents(database_path('seeders/md/approved/how-to-laravel-herd-windows.md'));

        BlogPost::create([
            'user_id' => $user->id,
            'title' => 'How to Set Up Laravel Development Environment with Herd on Windows',
            'slug' => 'how-to-setup-laravel-herd-windows',
            'excerpt' => 'Complete guide to setting up Laravel development on Windows using Herd. Learn how to manage PHP versions, databases, and local sites effortlessly on Windows 10 and 11.',
            'content' => $windowsContent,
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

        // Linux Guide (Valet)
        $linuxContent = file_get_contents(database_path('seeders/md/approved/how-to-laravel-linux-valet.md'));

        BlogPost::create([
            'user_id' => $user->id,
            'title' => 'Laravel Herd for Linux? The Best Native Alternative (Valet Linux Setup Guide)',
            'slug' => 'laravel-herd-linux-alternative-valet-setup-guide',
            'excerpt' => 'Laravel Herd isn\'t available for Linux yet. Learn how to set up Valet Linux to get the same lightning-fast, native development experience on Ubuntu and Debian.',
            'content' => $linuxContent,
            'type' => 'tutorial',
            'category' => 'Web Development',
            'tags' => ['laravel', 'linux', 'ubuntu', 'valet', 'herd-alternative', 'local-development'],
            'meta_title' => 'Laravel Herd for Linux? The Best Native Alternative Setup Guide',
            'meta_description' => 'Since Laravel Herd is not on Linux, learn how to set up Valet Linux for the same fast, configuration-free development environment on Ubuntu.',
            'status' => 'published',
            'published_at' => now()->subDays(5),
            'is_monetized' => false,
            'views' => 190,
            'unique_visitors' => 145,
        ]);
    }
}
