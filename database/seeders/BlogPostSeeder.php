<?php

namespace Database\Seeders;

use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogPostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            $user = User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'password' => bcrypt('password'),
            ]);
        }

        $blogPosts = [
            [
                'title' => 'Blog Post Templates: Create Professional Content Faster Without Writing From Scratch',
                'excerpt' => 'Discover ready-to-use blog post templates for tutorials, how-to guides, reviews, news articles, portfolios, and business pages. Save time and publish better content.',
                'content' => "Writing high-quality blog posts consistently is one of the biggest challenges for creators, developers, and businesses. Whether you are running a tech blog, company website, or personal portfolio, starting from a blank page often leads to wasted time and low productivity.\n\nBlog post templates solve this problem by giving you a proven structure that works. Instead of guessing what to write, you follow a ready-made framework designed for clarity, SEO, and reader engagement.\n\n## What Are Blog Post Templates?\n\nBlog post templates are pre-structured content layouts that include headings, sections, and prompts. They guide you on what to write, where to write it, and how to organize information effectively.\n\nOur platform offers professionally designed templates for tutorials, how-to guides, product reviews, news articles, list posts, case studies, business pages, and portfolios.\n\n## Why Use Blog Templates?\n\nUsing templates helps you write faster, maintain consistency across your site, improve SEO structure, and reduce writer’s block. Templates ensure that important sections like introductions, conclusions, FAQs, and calls-to-action are never missed.\n\n## Who Should Use These Templates?\n\nThese templates are perfect for bloggers, developers, startup founders, marketers, freelancers, agencies, and students. Whether you are writing technical documentation or business content, templates make the process smooth and professional.\n\n## Create Content in Minutes\n\nInstead of spending hours planning content structure, you can simply choose a template, customize the text, and publish. This allows you to focus more on value and less on formatting.\n\n## Get Started With Blog Post Templates\n\nIf you want to create better content faster, blog post templates are the smartest solution. Choose a template, customize it for your topic, and start publishing professional content today.",
                'type' => 'guide',
                'category' => 'Content Writing',
                'tags' => ['blog templates', 'content templates', 'writing faster', 'seo content'],
                'status' => 'published',
                'published_at' => now()->subDays(2),
                'is_monetized' => false,
                'earning_rate_less_2min' => 0,
                'earning_rate_2_5min' => 0,
                'earning_rate_more_5min' => 0,
                'views' => 210,
                'unique_visitors' => 165,
            ],
            [
                'title' => 'How to Install and Configure Nginx on Ubuntu (Beginner-Friendly Guide)',
                'excerpt' => 'Learn how to install and configure Nginx on Ubuntu with this beginner-friendly, step-by-step guide. Perfect for developers and bloggers.',
                'content' => "Nginx is a high-performance web server widely used for hosting blogs, portfolios, APIs, and large-scale applications. It is known for its speed, low memory usage, and ability to handle high traffic efficiently.\n\nThis guide explains how to install and configure Nginx on Ubuntu step by step, making it easy for beginners while following production-ready practices.\n\n## Prerequisites\n\nBefore starting, ensure you are using Ubuntu 20.04 or later, have sudo access on the server or VPS, and basic familiarity with Linux commands.\n\n## Step 1: Update Your System\n\nAlways update package lists before installing new software to avoid dependency issues.\n\n```bash\nsudo apt update && sudo apt upgrade -y\n```\n\n## Step 2: Install Nginx\n\nInstall Nginx from Ubuntu’s official repositories.\n\n```bash\nsudo apt install nginx -y\n```\n\nConfirm that the service is running.\n\n```bash\nsystemctl status nginx\n```\n\n## Step 3: Allow Nginx Through Firewall\n\nIf UFW is enabled, allow HTTP and HTTPS traffic.\n\n```bash\nsudo ufw allow 'Nginx Full'\nsudo ufw reload\n```\n\n## Step 4: Test Nginx\n\nOpen your browser and visit your server’s IP address. You should see the default Nginx welcome page, confirming the installation.\n\n## Step 5: Create a Server Block\n\nCreate a new configuration file for your domain.\n\n```bash\nsudo nano /etc/nginx/sites-available/yourdomain\n```\n\nDefine the server name, document root, and index file. Enable the configuration by linking it to the sites-enabled directory.\n\n```bash\nsudo ln -s /etc/nginx/sites-available/yourdomain /etc/nginx/sites-enabled/\n```\n\nTest the configuration.\n\n```bash\nsudo nginx -t\n```\n\nReload Nginx to apply changes.\n\n```bash\nsudo systemctl reload nginx\n```\n\n## Step 6: Enable HTTPS\n\nSecure your site with a free SSL certificate using Let’s Encrypt.\n\n```bash\nsudo apt install certbot python3-certbot-nginx -y\nsudo certbot --nginx\n```\n\n## Common Issues\n\n403 errors usually indicate permission problems, while 502 errors mean the backend service is not responding. Always check logs using `/var/log/nginx/error.log`.\n\n## Conclusion\n\nNginx is a fast and reliable web server suitable for beginners and production environments. Once configured correctly, it requires minimal maintenance and delivers excellent performance.",
                'type' => 'guide',
                'category' => 'Web Development',
                'tags' => ['nginx', 'ubuntu', 'web-server', 'hosting'],
                'status' => 'published',
                'published_at' => now()->subDay(),
                'is_monetized' => false,
                'earning_rate_less_2min' => 0,
                'earning_rate_2_5min' => 0,
                'earning_rate_more_5min' => 0,
                'views' => 120,
                'unique_visitors' => 95,
            ],
            [
                'title' => 'Getting Started With Laravel: A Beginner\'s Guide',
                'excerpt' => 'Learn Laravel from scratch with this beginner-friendly guide. Understand MVC, routing, Blade, and start building web apps confidently.',
                'content' => "Laravel is one of the most popular PHP frameworks available today. It allows developers to build modern web applications quickly and cleanly.\n\nIf you are new to Laravel, this guide will help you understand the fundamentals without unnecessary complexity.\n\n## What Is Laravel?\n\nLaravel is an open-source PHP framework that follows the Model-View-Controller architecture and simplifies routing, authentication, database operations, and security.\n\n## Why Choose Laravel?\n\nLaravel offers clean syntax, powerful Eloquent ORM, Blade templating engine, built-in security features, and strong community support.\n\n## Requirements\n\nYou need PHP 8.1 or higher, Composer, and a local server or VPS to get started.\n\n## Installing Laravel\n\nUse Composer to create a new Laravel project and start the development server using php artisan serve.\n\n## Core Concepts\n\nRouting defines how URLs behave, Blade templates allow clean UI rendering, and Eloquent ORM lets you interact with databases without writing raw SQL.\n\n## What to Learn Next\n\nFocus on authentication, middleware, controllers, migrations, and deployment.\n\n## Final Thoughts\n\nLaravel is beginner-friendly yet powerful enough for large-scale applications such as blogs, SaaS platforms, and APIs.",
                'type' => 'tutorial',
                'category' => 'Web Development',
                'tags' => ['laravel', 'php', 'framework', 'backend'],
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'is_monetized' => false,
                'earning_rate_less_2min' => 0,
                'earning_rate_2_5min' => 0,
                'earning_rate_more_5min' => 0,
                'views' => 340,
                'unique_visitors' => 270,
            ],
            [
                'title' => 'Best Blog Post Templates for Tutorials, Reviews, Business Pages, and Portfolios',
                'excerpt' => 'Explore the best blog post templates for tutorials, how-to articles, reviews, business pages, and portfolios. Write better content with proven formats.',
                'content' => "Every type of blog post serves a different purpose. Tutorials educate, reviews persuade, business pages build trust, and portfolios showcase expertise. The problem is that most writers use the same structure for everything, which leads to poor engagement.\n\nUsing the right template for the right content type makes a huge difference. A tutorial needs step-by-step clarity, while a review needs pros, cons, and a final verdict.\n\n## Tutorial and How-To Templates\n\nTutorial templates are designed for clarity and learning. They include prerequisites, numbered steps, code blocks, troubleshooting sections, and conclusions. These are perfect for developers, educators, and technical bloggers.\n\n## Review Templates\n\nReview templates help readers make decisions. They include quick summaries, feature breakdowns, pros and cons, pricing analysis, alternatives, and final ratings.\n\n## News and List Templates\n\nNews templates follow journalistic structure with lead paragraphs, background context, and future implications. List templates work best for rankings, top tools, and curated resources.\n\n## Business and Portfolio Templates\n\nBusiness page templates help companies present services, values, and contact details professionally. Portfolio templates are ideal for freelancers and agencies to showcase projects, skills, and testimonials.\n\n## Why Templates Improve SEO\n\nSearch engines favor structured content. Templates naturally improve heading hierarchy, keyword placement, readability, and internal linking opportunities.\n\n## Start Writing With Confidence\n\nInstead of reinventing the wheel every time, choose a template that fits your goal. You will write faster, publish better content, and keep your site consistent.\n\nExplore our blog post templates and start creating professional content without the stress of starting from scratch.",
                'type' => 'tutorial',
                'category' => 'Content Strategy',
                'tags' => ['blog writing', 'content strategy', 'templates', 'seo'],
                'status' => 'published',
                'published_at' => now()->subDays(4),
                'is_monetized' => false,
                'earning_rate_less_2min' => 0,
                'earning_rate_2_5min' => 0,
                'earning_rate_more_5min' => 0,
                'views' => 180,
                'unique_visitors' => 140,
            ],
        ];

        foreach ($blogPosts as $postData) {
            BlogPost::create(array_merge($postData, [
                'user_id' => $user->id,
                'slug' => Str::slug($postData['title']),
                'meta_title' => $postData['title'],
                'meta_description' => $postData['excerpt'],
            ]));
        }

        $this->command->info('Sample blog posts created successfully!');
    }
}
