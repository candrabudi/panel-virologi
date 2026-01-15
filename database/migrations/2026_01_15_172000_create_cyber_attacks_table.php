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
        if (!Schema::hasTable('cyber_attacks')) {
            Schema::create('cyber_attacks', function (Blueprint $table) {
                $table->id();
                $table->string('attack_id')->nullable();
                $table->string('source_ip')->nullable();
                $table->string('destination_ip')->nullable();
                $table->string('source_country')->nullable();
                $table->string('destination_country')->nullable();
                $table->string('protocol')->nullable();
                $table->integer('source_port')->nullable();
                $table->integer('destination_port')->nullable();
                $table->string('attack_type')->nullable();
                $table->bigInteger('payload_size_bytes')->nullable();
                $table->string('detection_label')->nullable();
                $table->float('confidence_score')->nullable();
                $table->string('ml_model')->nullable();
                $table->string('affected_system')->nullable();
                $table->string('port_type')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cyber_attacks');
    }
};
