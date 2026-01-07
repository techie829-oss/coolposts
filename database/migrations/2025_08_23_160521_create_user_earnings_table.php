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
        Schema::create('user_earnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('link_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 10, 4);
            $table->string('currency', 3)->default('USD');
            $table->decimal('amount_inr', 10, 4)->default(0);
            $table->decimal('amount_usd', 10, 4)->default(0);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->text('notes')->nullable();

            // Geographic tracking
            $table->string('country_code', 2)->nullable();
            $table->string('country_name')->nullable();
            $table->string('continent_code', 2)->nullable();
            $table->string('continent_name')->nullable();

            $table->timestamps();

            // Geographic indexes
            $table->index(['country_code']);
            $table->index(['continent_code']);
            $table->index(['created_at', 'country_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_earnings');
    }
};
