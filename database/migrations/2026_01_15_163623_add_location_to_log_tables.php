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
        Schema::table('server_anomalies', function (Blueprint $table) {
            $table->string('country_code')->nullable()->after('ip_address');
            $table->string('country_name')->nullable()->after('country_code');
            $table->decimal('latitude', 10, 7)->nullable()->after('country_name');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
        });

        Schema::table('access_logs', function (Blueprint $table) {
            $table->string('country_code')->nullable()->after('ip_address');
            $table->string('country_name')->nullable()->after('country_code');
            $table->decimal('latitude', 10, 7)->nullable()->after('country_name');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('server_anomalies', function (Blueprint $table) {
            $table->dropColumn(['country_code', 'country_name', 'latitude', 'longitude']);
        });

        Schema::table('access_logs', function (Blueprint $table) {
            $table->dropColumn(['country_code', 'country_name', 'latitude', 'longitude']);
        });
    }
};
