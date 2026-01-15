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
        Schema::create('targeted_attacks', function (Blueprint $table) {
            $table->id();
            $table->string('target_url')->nullable();
            $table->string('attack_vector');
            $table->enum('severity', ['critical', 'high', 'medium', 'low'])->default('high');
            $table->string('affected_asset')->nullable();
            $table->enum('status', ['blocked', 'mitigated', 'active', 'investigating'])->default('blocked');
            $table->text('details')->nullable();
            $table->timestamp('incident_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('targeted_attacks');
    }
};
