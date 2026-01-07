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
        Schema::create('ai_settings', function (Blueprint $table) {
            $table->id();

            // Gemini AI Settings
            $table->boolean('gemini_enabled')->default(false);
            $table->text('gemini_api_key')->nullable();
            $table->string('gemini_model')->default('gemini-1.5-flash');

            // OpenAI Settings
            $table->boolean('openai_enabled')->default(false);
            $table->text('openai_api_key')->nullable();
            $table->string('openai_model')->default('gpt-3.5-turbo');

            // HuggingFace Settings
            $table->boolean('huggingface_enabled')->default(false);
            $table->text('huggingface_api_key')->nullable();
            $table->string('huggingface_model')->default('meta-llama/Llama-2-7b-chat-hf');

            // Cohere Settings
            $table->boolean('cohere_enabled')->default(false);
            $table->text('cohere_api_key')->nullable();
            $table->string('cohere_model')->default('command');

            // General Settings
            $table->string('default_provider')->default('gemini');
            $table->integer('max_tokens')->default(1000);
            $table->decimal('temperature', 2, 1)->default(0.7);
            $table->text('system_prompt')->nullable();

            // AI Features Toggles
            $table->boolean('blog_generation_enabled')->default(true);
            $table->boolean('seo_optimization_enabled')->default(true);
            $table->boolean('content_rewrite_enabled')->default(true);
            $table->boolean('image_generation_enabled')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_settings');
    }
};
