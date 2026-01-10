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
        $driver = DB::connection()->getDriverName();

        if ($driver === 'pgsql') {
            // PostgreSQL: Drop constraint and re-add with new allowed values
            DB::statement("ALTER TABLE blog_posts DROP CONSTRAINT IF EXISTS blog_posts_type_check");
            DB::statement("ALTER TABLE blog_posts ADD CONSTRAINT blog_posts_type_check CHECK (type::text = ANY (ARRAY['tutorial'::text, 'news'::text, 'guide'::text, 'review'::text, 'article'::text, 'case_study'::text, 'list'::text, 'business_page'::text, 'company_portfolio'::text, 'personal_portfolio'::text]))");
        } else {
            // MySQL: Modify column
            DB::statement("ALTER TABLE blog_posts MODIFY COLUMN type ENUM('tutorial', 'news', 'guide', 'review', 'article', 'case_study', 'list', 'business_page', 'company_portfolio', 'personal_portfolio') NOT NULL DEFAULT 'article'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'pgsql') {
            DB::statement("ALTER TABLE blog_posts DROP CONSTRAINT IF EXISTS blog_posts_type_check");
            DB::statement("ALTER TABLE blog_posts ADD CONSTRAINT blog_posts_type_check CHECK (type::text = ANY (ARRAY['tutorial'::text, 'news'::text, 'guide'::text, 'review'::text, 'article'::text, 'case_study'::text]))");
        } else {
            DB::statement("ALTER TABLE blog_posts MODIFY COLUMN type ENUM('tutorial', 'news', 'guide', 'review', 'article', 'case_study') NOT NULL DEFAULT 'article'");
        }
    }
};
