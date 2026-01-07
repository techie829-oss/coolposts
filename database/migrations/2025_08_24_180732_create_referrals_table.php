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
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('referrer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('referred_id')->constrained('users')->onDelete('cascade');
            $table->string('referral_code');
            $table->string('status'); // pending, completed, expired
            $table->decimal('amount_inr', 10, 2)->default(0.00);
            $table->decimal('amount_usd', 10, 2)->default(0.00);
            $table->string('currency')->default('INR');
            $table->decimal('commission_rate', 5, 2)->default(0.00); // Percentage
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->json('metadata')->nullable(); // Additional referral data
            $table->timestamps();

            $table->unique('referred_id'); // One user can only be referred once
            $table->index(['referrer_id', 'status']);
            $table->index(['referral_code', 'status']);
            $table->index(['status', 'completed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('referrals');
    }
};
