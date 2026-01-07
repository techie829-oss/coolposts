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
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('shadow_balance_inr', 10, 4)->default(0)->after('balance_usd');
            $table->decimal('shadow_balance_usd', 10, 4)->default(0)->after('shadow_balance_inr');
        });

        Schema::table('user_earnings', function (Blueprint $table) {
            $table->boolean('is_shadow')->default(false)->after('status');
        });

        Schema::table('global_settings', function (Blueprint $table) {
            $table->integer('fraud_rapid_click_threshold')->default(10)->after('enable_device_tracking');
            $table->integer('fraud_click_time_window')->default(300)->after('fraud_rapid_click_threshold');
            $table->decimal('fraud_vpn_penalty_score', 3, 2)->default(0.30)->after('fraud_click_time_window');
            $table->decimal('fraud_bot_penalty_score', 3, 2)->default(0.40)->after('fraud_vpn_penalty_score');
            $table->boolean('payouts_frozen')->default(true)->after('fraud_bot_penalty_score');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['shadow_balance_inr', 'shadow_balance_usd']);
        });

        Schema::table('user_earnings', function (Blueprint $table) {
            $table->dropColumn('is_shadow');
        });

        Schema::table('global_settings', function (Blueprint $table) {
            $table->dropColumn([
                'fraud_rapid_click_threshold',
                'fraud_click_time_window',
                'fraud_vpn_penalty_score',
                'fraud_bot_penalty_score',
                'payouts_frozen'
            ]);
        });
    }
};
