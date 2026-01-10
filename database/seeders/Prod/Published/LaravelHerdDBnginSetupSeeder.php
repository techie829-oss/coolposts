<?php

namespace Database\Seeders\Prod\Published;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;

class LaravelHerdDBnginSetupSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        // macOS Guide
        $macContent = file_get_contents(database_path('seeders/md/published/how-to-laravel-herd-dbngin-macos.md'));

        BlogPost::updateOrCreate(
            ['slug' => 'how-to-setup-laravel-12-macos-using-herd-dbngin'],
            [
                'user_id' => $user->id,
                'title' => 'How to Set Up Laravel 12 Development Environment on macOS Using Herd and DBngin',
                'excerpt' => 'The complete guide to setting up Laravel 12 on macOS. Learn how to use Herd for PHP, DBngin for MySQL, and configure the ideal local dev stack.',
                'content' => $macContent,
                'type' => 'tutorial',
                'category' => 'Web Development',
                'tags' => ['laravel-12', 'herd', 'macos', 'dbngin', 'mysql', 'sqlite'],
                'meta_title' => 'Setup Laravel 12 on macOS with Herd & DBngin',
                'meta_description' => 'The ultimate macOS dev stack. Learn to set up Laravel 12 using improved Herd for PHP and DBngin for MySQL management.',
                'meta_keywords' => ['laravel herd setup', 'dbngin configuration', 'macos php development', 'local laravel environment', 'mysql macos'],
                'canonical_url' => 'https://www.coolposts.site/blog-posts/how-to-setup-laravel-12-macos-using-herd-dbngin',
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'is_monetized' => false,
                'views' => 450,
                'unique_visitors' => 310,
            ]
        );

        // Windows Guide
        $windowsContent = file_get_contents(database_path('seeders/md/published/how-to-laravel-herd-windows.md'));

        BlogPost::updateOrCreate(
            ['slug' => 'how-to-setup-laravel-12-windows-using-herd-mysql'],
            [
                'user_id' => $user->id,
                'title' => 'How to Set Up Laravel 12 Development Environment on Windows Using Herd (with MySQL via DBngin)',
                'excerpt' => 'A complete guide to setting up Laravel 12 on Windows using Herd and DBngin. Learn how to configure MySQL, manage PHP versions, and avoid common config pitfalls.',
                'content' => $windowsContent,
                'type' => 'tutorial',
                'category' => 'Web Development',
                'tags' => ['laravel-12', 'herd', 'windows', 'dbngin', 'mysql', 'local-development'],
                'meta_title' => 'Setup Laravel 12 on Windows with Herd & DBngin',
                'meta_description' => 'Develop Laravel on Windows like a pro. Complete guide to configuring Laravel 12, Herd, and MySQL via DBngin for a smooth workflow.',
                'meta_keywords' => ['laravel herd windows', 'dbngin windows', 'local php dev windows', 'laravel setup guide', 'windows mysql'],
                'canonical_url' => 'https://www.coolposts.site/blog-posts/how-to-setup-laravel-12-windows-using-herd-mysql',
                'status' => 'published',
                'published_at' => now()->subDays(7),
                'is_monetized' => false,
                'views' => 340,
                'unique_visitors' => 255,
            ]
        );

        // Linux Guide (Valet)
        $linuxContent = file_get_contents(database_path('seeders/md/published/how-to-laravel-linux-valet.md'));

        BlogPost::updateOrCreate(
            ['slug' => 'how-to-setup-laravel-12-linux-using-valet'],
            [
                'user_id' => $user->id,
                'title' => 'How to Set Up Laravel 12 Development Environment on Linux Using Valet Linux',
                'excerpt' => 'The ultimate guide to native Laravel 12 development on Linux using Valet. Achieve Herd-like speed on Ubuntu/Debian without Docker complexity.',
                'content' => $linuxContent,
                'type' => 'tutorial',
                'category' => 'Web Development',
                'tags' => ['laravel-12', 'linux', 'valet-linux', 'ubuntu', 'mysql', 'sqlite'],
                'meta_title' => 'Setup Laravel 12 on Linux with Valet',
                'meta_description' => 'Achieve native speed on Linux. Learn how to set up Laravel 12 using Valet Linux for a robust, Docker-free development environment.',
                'meta_keywords' => ['laravel valet linux', 'linux php dev', 'ubuntu laravel setup', 'valet linux guide', 'native linux php'],
                'canonical_url' => 'https://www.coolposts.site/blog-posts/how-to-setup-laravel-12-linux-using-valet',
                'status' => 'published',
                'published_at' => now()->subDays(4),
                'is_monetized' => false,
                'views' => 120,
                'unique_visitors' => 95,
            ]
        );

        // Laragon Windows Guide (New)
        $laragonContent = file_get_contents(database_path('seeders/md/published/how-to-laravel-laragon-windows.md'));

        BlogPost::updateOrCreate(
            ['slug' => 'how-to-setup-laravel-windows-using-laragon'],
            [
                'user_id' => $user->id,
                'title' => 'How to Set Up Laravel Development Environment on Windows Using Laragon',
                'excerpt' => 'A complete guide to setting up Laravel 12 on Windows using Laragon. The beginner-friendly alternative to Herd for easy PHP and MySQL management.',
                'content' => $laragonContent,
                'type' => 'tutorial',
                'category' => 'Web Development',
                'tags' => ['laravel-12', 'laragon', 'windows', 'mysql', 'sqlite', 'local-development'],
                'meta_title' => 'Setup Laravel 12 on Windows with Laragon',
                'meta_description' => 'Prefer Laragon? Here is the complete guide to setting up a modern Laravel 12 environment on Windows using the reliable Laragon stack.',
                'meta_keywords' => ['laragon setup', 'laravel windows laragon', 'easy php windows', 'laragon guide', 'local dev server'],
                'canonical_url' => 'https://www.coolposts.site/blog-posts/how-to-setup-laravel-windows-using-laragon',
                'status' => 'published',
                'published_at' => now()->subDays(3),
                'is_monetized' => false,
                'views' => 150,
                'unique_visitors' => 110,
            ]
        );
    }
}
