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
        if (!Schema::hasTable('access_logs')) {
            Schema::create('access_logs', function (Blueprint $table) {
                $table->id();
                $table->string('ip_address')->nullable();
                $table->string('user_agent')->nullable();
                $table->string('email')->nullable();
                $table->string('status'); // 'success', 'failed'
                $table->text('details')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('server_anomalies')) {
            Schema::create('server_anomalies', function (Blueprint $table) {
                $table->id();
                $table->string('event_type'); // 'xss_attempt', 'sqli_attempt', 'brute_force', etc.
                $table->string('ip_address')->nullable();
                $table->text('details')->nullable();
                $table->enum('severity', ['low', 'medium', 'high', 'critical'])->default('medium');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('server_anomalies');
        Schema::dropIfExists('access_logs');
    }
};
