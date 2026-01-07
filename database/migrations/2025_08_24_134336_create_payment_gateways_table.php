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
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Stripe, PayPal, Paytm
            $table->string('slug')->unique(); // stripe, paypal, paytm
            $table->text('description');
            $table->boolean('is_active')->default(false);
            $table->boolean('is_test_mode')->default(true);
            $table->string('environment'); // test, live
            $table->json('config'); // Gateway-specific configuration
            $table->json('supported_currencies'); // Array of supported currencies
            $table->json('supported_countries')->nullable(); // Array of supported countries
            $table->decimal('transaction_fee_percentage', 5, 2)->default(0.00);
            $table->decimal('transaction_fee_fixed', 10, 2)->default(0.00);
            $table->text('webhook_url')->nullable();
            $table->string('webhook_secret')->nullable();
            $table->timestamp('last_webhook_received_at')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_gateways');
    }
};
