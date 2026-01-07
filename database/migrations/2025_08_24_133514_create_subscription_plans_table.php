<?php

namespace Database\Migrations;

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
        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Basic, Pro, Enterprise
            $table->string('slug'); // basic, pro, enterprise
            $table->text('description');
            $table->decimal('price_inr', 10, 2);
            $table->decimal('price_usd', 10, 2);
            $table->string('billing_cycle'); // monthly, yearly
            $table->integer('duration_days'); // 30 for monthly, 365 for yearly
            $table->json('features'); // Array of features
            $table->boolean('is_active')->default(true);
            $table->boolean('is_popular')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            // Make slug unique only within billing cycle
            $table->unique(['slug', 'billing_cycle']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_plans');
    }
};
