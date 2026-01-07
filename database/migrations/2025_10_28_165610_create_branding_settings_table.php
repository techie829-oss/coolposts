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
        Schema::create('branding_settings', function (Blueprint $table) {
            $table->id();
            $table->string('brand_name')->default('CoolHax Posts');
            $table->string('brand_logo')->nullable();
            $table->string('primary_color')->default('#8b5cf6'); // Purple
            $table->string('secondary_color')->default('#ec4899'); // Pink
            $table->string('accent_color')->default('#ef4444'); // Red
            $table->string('gradient_start')->default('#8b5cf6'); // Purple
            $table->string('gradient_end')->default('#ec4899'); // Pink
            $table->string('gradient_third')->default('#ef4444'); // Red
            $table->string('brand_tagline')->nullable();
            $table->text('brand_description')->nullable();
            $table->string('favicon')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branding_settings');
    }
};
