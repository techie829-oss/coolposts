<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Basic post info
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->longText('content');
            $table->enum('content_type', ['markdown', 'text', 'html'])->default('markdown');

            // Blog type and category
            $table->enum('type', ['tutorial', 'news', 'guide', 'review', 'article', 'case_study'])->default('article');
            $table->string('category')->nullable();
            $table->json('tags')->nullable();

            // Featured image and media
            $table->string('featured_image')->nullable();
            $table->json('gallery_images')->nullable();
            $table->json('attachments')->nullable();

            // SEO and meta
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->json('meta_keywords')->nullable();
            $table->string('canonical_url')->nullable();

            // Monetization settings
            $table->boolean('is_monetized')->default(true);
            $table->enum('monetization_type', ['time_based', 'ad_based', 'both'])->default('time_based');

            // Time-based earning rates (per minute of engagement)
            $table->decimal('earning_rate_less_2min', 8, 4)->default(0.0010)->comment('Earning per visitor spending less than 2 minutes');
            $table->decimal('earning_rate_2_5min', 8, 4)->default(0.0020)->comment('Earning per visitor spending 2-5 minutes');
            $table->decimal('earning_rate_more_5min', 8, 4)->default(0.0050)->comment('Earning per visitor spending more than 5 minutes');

            // Ad-based settings
            $table->enum('ad_type', ['no_ads', 'banner_ads', 'interstitial_ads', 'both'])->default('banner_ads');
            $table->integer('ad_frequency')->default(1)->comment('Show ad every N minutes');

            // Content structure
            $table->json('sections')->nullable()->comment('Multiple sections for complex blog posts');
            $table->json('code_blocks')->nullable()->comment('Code blocks for tutorials');
            $table->json('tables')->nullable()->comment('Data tables');
            $table->json('quotes')->nullable()->comment('Quote blocks');

            // Status and publishing
            $table->enum('status', ['draft', 'published', 'archived', 'scheduled'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamp('scheduled_at')->nullable();

            // Statistics
            $table->integer('views')->default(0);
            $table->integer('unique_visitors')->default(0);
            $table->integer('avg_time_spent')->default(0)->comment('Average time spent in seconds');
            $table->decimal('total_earnings_inr', 10, 4)->default(0);
            $table->decimal('total_earnings_usd', 10, 4)->default(0);

            // Engagement tracking
            $table->timestamp('last_viewed_at')->nullable();
            $table->integer('bounce_rate')->default(0)->comment('Percentage of single-page visits');
            $table->integer('scroll_depth_avg')->default(0)->comment('Average scroll depth percentage');

            $table->timestamps();

            // Indexes
            $table->index(['status', 'published_at']);
            $table->index(['user_id', 'status']);
            $table->index(['type', 'category']);
            $table->index(['slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
