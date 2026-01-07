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
        Schema::create('blog_visitors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_post_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');

            // Visitor identification
            $table->string('ip_address');
            $table->string('user_agent')->nullable();
            $table->string('session_id')->nullable();
            $table->string('referrer')->nullable();

            // Visit tracking
            $table->timestamp('visited_at');
            $table->timestamp('left_at')->nullable();
            $table->integer('time_spent_seconds')->default(0);
            $table->integer('scroll_depth_percentage')->default(0);

            // Engagement metrics
            $table->boolean('is_unique_visit')->default(true);
            $table->boolean('is_bounce')->default(false);
            $table->integer('page_views')->default(1);
            $table->json('interactions')->nullable()->comment('Clicks, scrolls, etc.');

            // Earnings calculation
            $table->enum('time_category', ['less_2min', '2_5min', 'more_5min'])->nullable();
            $table->decimal('earnings_inr', 8, 4)->default(0);
            $table->decimal('earnings_usd', 8, 4)->default(0);
            $table->boolean('earnings_credited')->default(false);
            $table->timestamp('earnings_credited_at')->nullable();

            // Device and location
            $table->string('device_type')->nullable(); // desktop, mobile, tablet
            $table->string('browser')->nullable();
            $table->string('os')->nullable();
            $table->string('country')->nullable();
            $table->string('country_code', 2)->nullable();
            $table->string('country_name')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('continent_code', 2)->nullable();
            $table->string('continent_name')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('timezone')->nullable();
            $table->string('isp')->nullable();
            $table->string('organization')->nullable();

            // Fraud detection
            $table->boolean('is_suspicious')->default(false);
            $table->json('fraud_flags')->nullable();
            $table->decimal('risk_score', 3, 2)->default(0);
            $table->json('tracking_metadata')->nullable();

            $table->timestamps();

            // Indexes
            $table->index(['blog_post_id', 'visited_at']);
            $table->index(['ip_address', 'blog_post_id']);
            $table->index(['user_id', 'visited_at']);
            $table->index(['time_category', 'earnings_credited']);
            $table->index(['country_code']);
            $table->index(['continent_code']);
            $table->index(['city']);
            $table->index(['visited_at', 'country_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_visitors');
    }
};
