<?php

namespace Database\Seeders\Prod\Published;

use Illuminate\Database\Seeder;
use App\Models\BlogPost;
use App\Models\User;
use Illuminate\Support\Str;

class BestBlogTemplatesSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create();
        }

        $content = "Every type of blog post serves a different purpose. Tutorials educate, reviews persuade, business pages build trust, and portfolios showcase expertise. The problem is that most writers use the same structure for everything, which leads to poor engagement.\n\nUsing the right template for the right content type makes a huge difference. A tutorial needs step-by-step clarity, while a review needs pros, cons, and a final verdict.\n\n## Tutorial and How-To Templates\n\nTutorial templates are designed for clarity and learning. They include prerequisites, numbered steps, code blocks, troubleshooting sections, and conclusions. These are perfect for developers, educators, and technical bloggers.\n\n## Review Templates\n\nReview templates help readers make decisions. They include quick summaries, feature breakdowns, pros and cons, pricing analysis, alternatives, and final ratings.\n\n## News and List Templates\n\nNews templates follow journalistic structure with lead paragraphs, background context, and future implications. List templates work best for rankings, top tools, and curated resources.\n\n## Business and Portfolio Templates\n\nBusiness page templates help companies present services, values, and contact details professionally. Portfolio templates are ideal for freelancers and agencies to showcase projects, skills, and testimonials.\n\n## Why Templates Improve SEO\n\nSearch engines favor structured content. Templates naturally improve heading hierarchy, keyword placement, readability, and internal linking opportunities.\n\n## Start Writing With Confidence\n\nInstead of reinventing the wheel every time, choose a template that fits your goal. You will write faster, publish better content, and keep your site consistent.\n\nExplore our blog post templates and start creating professional content without the stress of starting from scratch.";

        BlogPost::updateOrCreate(
            ['slug' => 'best-blog-post-templates-tutorials-reviews-business'],
            [
                'user_id' => $user->id,
                'title' => 'Best Blog Post Templates for Tutorials, Reviews, Business Pages, and Portfolios',
                'excerpt' => 'Explore the best blog post templates for tutorials, how-to articles, reviews, business pages, and portfolios. Write better content with proven formats.',
                'content' => $content,
                'type' => 'tutorial',
                'category' => 'Content Strategy',
                'tags' => ['blog writing', 'content strategy', 'templates', 'seo'],
                'meta_title' => 'Best Blog Post Templates for Tutorials, Reviews, Business Pages, and Portfolios',
                'meta_description' => 'Explore the best blog post templates for tutorials, how-to articles, reviews, business pages, and portfolios.',
                'status' => 'published',
                'published_at' => now()->subDays(4),
                'is_monetized' => true,
                'views' => 180,
                'unique_visitors' => 140,
            ]
        );
    }
}
