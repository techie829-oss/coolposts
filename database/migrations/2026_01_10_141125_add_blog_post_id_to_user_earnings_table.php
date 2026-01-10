<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_earnings', function (Blueprint $table) {
            $table->foreignId('blog_post_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('link_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_earnings', function (Blueprint $table) {
            $table->dropForeign(['blog_post_id']);
            $table->dropColumn('blog_post_id');
            $table->foreignId('link_id')->nullable(false)->change();
        });
    }
};
