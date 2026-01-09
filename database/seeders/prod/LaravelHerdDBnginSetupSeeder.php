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
            'title' => 'How to Set Up Laravel 12 Development Environment on macOS Using Herd and DBngin',
            'slug' => 'how-to-setup-laravel-12-macos-using-herd-dbngin',
            'excerpt' => 'The complete guide to setting up Laravel 12 on macOS. Learn how to use Herd for PHP, DBngin for MySQL, and configure the ideal local dev stack.',
            'content' => $macContent,
            'type' => 'tutorial',
            'category' => 'Web Development',
            'tags' => ['laravel-12', 'herd', 'macos', 'dbngin', 'mysql', 'sqlite'],
            'meta_title' => 'How to Set Up Laravel 12 on macOS Using Herd and DBngin',
            'meta_description' => 'Expert guide for setting up Laravel 12 on macOS. Covers Herd, DBngin, SQLite vs MySQL, and avoiding common setup pitfalls.',
            'status' => 'published',
            'published_at' => now()->subDays(5),
            'is_monetized' => false,
            'views' => 450,
            'unique_visitors' => 310,
        ]);

        // Windows Guide
        $windowsContent = file_get_contents(database_path('seeders/md/approved/how-to-laravel-herd-windows.md'));

        BlogPost::create([
            'user_id' => $user->id,
            'title' => 'How to Set Up Laravel 12 Development Environment on Windows Using Herd (with MySQL via DBngin)',
            'slug' => 'how-to-setup-laravel-12-windows-using-herd-mysql',
            'excerpt' => 'A complete guide to setting up Laravel 12 on Windows using Herd and DBngin. Learn how to configure MySQL, manage PHP versions, and avoid common config pitfalls.',
            'content' => $windowsContent,
            'type' => 'tutorial',
            'category' => 'Web Development',
            'tags' => ['laravel-12', 'herd', 'windows', 'dbngin', 'mysql', 'local-development'],
            'meta_title' => 'How to Set Up Laravel 12 on Windows Using Herd and MySQL',
            'meta_description' => 'Correct way to set up Laravel 12 on Windows with Herd and DBngin. Covers MySQL configuration, PHP management, and troubleshooting.',
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
            'title' => 'How to Set Up Laravel Development Environment on Linux Using Valet',
            'slug' => 'how-to-setup-laravel-linux-using-valet',
            'excerpt' => 'Laravel Herd isn\'t available for Linux yet. Learn how to set up Valet Linux to get the same lightning-fast, native development experience on Ubuntu and Debian.',
            'content' => $linuxContent,
            'type' => 'tutorial',
            'category' => 'Web Development',
            'tags' => ['laravel', 'linux', 'ubuntu', 'valet', 'herd-alternative', 'local-development'],
            'meta_title' => 'How to Set Up Laravel on Linux Using Valet',
            'meta_description' => 'Since Laravel Herd is not on Linux, learn how to set up Valet Linux for the same fast, configuration-free development environment on Ubuntu.',
            'status' => 'published',
            'published_at' => now()->subDays(5),
            'is_monetized' => false,
            'views' => 190,
            'unique_visitors' => 145,
        ]);
    }
}
