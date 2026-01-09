<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the type column to include new types
        DB::statement("ALTER TABLE blog_posts MODIFY COLUMN type ENUM('tutorial', 'news', 'guide', 'review', 'article', 'case_study', 'list', 'business_page', 'company_portfolio', 'personal_portfolio') NOT NULL DEFAULT 'article'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original types
        DB::statement("ALTER TABLE blog_posts MODIFY COLUMN type ENUM('tutorial', 'news', 'guide', 'review', 'article', 'case_study') NOT NULL DEFAULT 'article'");
    }
};
