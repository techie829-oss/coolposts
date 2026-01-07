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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('subscription_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('payment_gateway_id')->constrained()->onDelete('cascade');
            $table->string('transaction_id')->unique(); // Unique transaction ID
            $table->string('gateway_transaction_id')->nullable(); // External gateway transaction ID
            $table->string('status'); // pending, completed, failed, cancelled, refunded
            $table->string('type'); // subscription, one_time, refund
            $table->decimal('amount', 10, 2);
            $table->string('currency');
            $table->decimal('gateway_fee', 10, 2)->default(0.00);
            $table->decimal('net_amount', 10, 2); // Amount after fees
            $table->string('payment_method')->nullable(); // card, wallet, upi, etc.
            $table->string('payment_method_details')->nullable(); // Last 4 digits, wallet name, etc.
            $table->json('gateway_response')->nullable(); // Raw response from payment gateway
            $table->text('description')->nullable();
            $table->string('failure_reason')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('failed_at')->nullable();
            $table->json('metadata')->nullable(); // Additional transaction data
            $table->timestamps();

            // Indexes for performance
            $table->index(['user_id', 'status']);
            $table->index(['status', 'created_at']);
            $table->index(['gateway_transaction_id']);
            $table->index(['type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
