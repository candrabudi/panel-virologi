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
        if (!Schema::hasTable('server_health_metrics')) {
            Schema::create('server_health_metrics', function (Blueprint $table) {
                $table->id();
                $table->float('cpu_usage')->default(0);
                $table->float('memory_usage')->default(0);
                $table->float('disk_usage')->default(0);
                $table->float('traffic_in')->default(0); // Mbps
                $table->float('traffic_out')->default(0); // Mbps
                $table->enum('status', ['operational', 'degraded', 'maintenance'])->default('operational');
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('server_health_metrics');
    }
};
