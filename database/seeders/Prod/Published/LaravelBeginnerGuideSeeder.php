<?php

namespace Database\Seeders\Prod\Published;

use Illuminate\Database\Seeder;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Support\Str;

class LaravelBeginnerGuideSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create();
        }

        $content = "Laravel is one of the most popular PHP frameworks available today. It allows developers to build modern web applications quickly and cleanly.\n\nIf you are new to Laravel, this guide will help you understand the fundamentals without unnecessary complexity.\n\n## What Is Laravel?\n\nLaravel is an open-source PHP framework that follows the Model-View-Controller architecture and simplifies routing, authentication, database operations, and security.\n\n## Why Choose Laravel?\n\nLaravel offers clean syntax, powerful Eloquent ORM, Blade templating engine, built-in security features, and strong community support.\n\n## Requirements\n\nYou need PHP 8.1 or higher, Composer, and a local server or VPS to get started.\n\n## Installing Laravel\n\nUse Composer to create a new Laravel project and start the development server using php artisan serve.\n\n## Core Concepts\n\nRouting defines how URLs behave, Blade templates allow clean UI rendering, and Eloquent ORM lets you interact with databases without writing raw SQL.\n\n## What to Learn Next\n\nFocus on authentication, middleware, controllers, migrations, and deployment.\n\n## Final Thoughts\n\nLaravel is beginner-friendly yet powerful enough for large-scale applications such as blogs, SaaS platforms, and APIs.";

        BlogPost::updateOrCreate(
            ['slug' => 'getting-started-with-laravel-beginner-guide'],
            [
                'user_id' => $user->id,
                'title' => 'Getting Started With Laravel: A Beginner\'s Guide',
                'excerpt' => 'Learn Laravel from scratch with this beginner-friendly guide. Understand MVC, routing, Blade, and start building web apps confidently.',
                'content' => $content,
                'type' => 'tutorial',
                'category' => 'Web Development',
                'tags' => ['laravel', 'php', 'framework', 'backend'],
                'meta_title' => 'Laravel Beginner\'s Guide: Start Building Web Apps',
                'meta_description' => 'The definitive beginner\'s guide to Laravel 12. Master MVC, Routing, and Blade templates to build modern web applications from scratch.',
                'meta_keywords' => ['laravel beginner guide', 'learn laravel 2026', 'php framework tutorial', 'laravel mvc', 'backend development'],
                'canonical_url' => 'https://www.coolposts.site/blog-posts/getting-started-with-laravel-beginner-guide',
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'is_monetized' => true,
                'views' => 340,
                'unique_visitors' => 270,
            ]
        );
    }
}
