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
        Schema::table('leak_check_logs', function (Blueprint $table) {
            $table->longText('raw_response')->nullable()->after('leak_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leak_check_logs', function (Blueprint $table) {
            $table->dropColumn('raw_response');
        });
    }
};
