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
        Schema::create('links', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('original_url');
            $table->string('short_code')->unique();
            $table->boolean('is_protected')->default(false);
            $table->string('password')->nullable();
            $table->decimal('earnings_per_click', 8, 4)->default(0.0010);
            $table->string('currency', 3)->default('INR');
            $table->enum('ad_type', ['no_ads', 'short_ads', 'long_ads'])->default('short_ads');
            $table->integer('ad_duration')->nullable();
            $table->decimal('earnings_per_click_inr', 8, 4)->default(0.5);
            $table->decimal('earnings_per_click_usd', 8, 4)->default(0.006);
            $table->boolean('is_monetized')->default(true);
            $table->boolean('is_active')->default(true);
            $table->integer('daily_click_limit')->nullable();
            $table->timestamp('last_click_at')->nullable();
            $table->string('category')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('links');
    }
};
