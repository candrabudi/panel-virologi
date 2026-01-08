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
        Schema::table('system_traffic_logs', function (Blueprint $table) {
            $table->longText('query_params')->nullable()->after('path');
            $table->longText('headers')->nullable()->after('payload');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('system_traffic_logs', function (Blueprint $table) {
            $table->dropColumn(['query_params', 'headers']);
        });
    }
};
