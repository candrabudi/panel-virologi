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
        if (!Schema::hasTable('security_metrics')) {
            Schema::create('security_metrics', function (Blueprint $table) {
                $table->id();
                $table->integer('active_botnets')->default(0);
                $table->integer('c2_nodes_blocked')->default(0);
                $table->string('traffic_scrubbed')->default('0 GB'); // e.g., "564 GB"
                $table->enum('threat_level', ['LOW', 'MEDIUM', 'HIGH', 'CRITICAL'])->default('LOW');
                $table->date('metric_date')->unique(); // Daily stats
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('security_metrics');
    }
};
