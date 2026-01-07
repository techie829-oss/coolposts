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
        // Update global settings to disable earnings features
        $settings = \App\Models\GlobalSetting::first();

        if ($settings) {
            $settings->earnings_enabled = false;
            $settings->monetization_enabled = false;
            $settings->save();
        } else {
            // Fallback if model logic isn't available during migration for some reason, 
            // though direct DB access is usually preferred for robustness.
            DB::table('global_settings')->update([
                'earnings_enabled' => false,
                'monetization_enabled' => false
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Re-enable if rolling back
        DB::table('global_settings')->update([
            'earnings_enabled' => true,
            'monetization_enabled' => true
        ]);
    }
};
