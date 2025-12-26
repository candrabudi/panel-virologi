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
        Schema::create('ai_decision_logs', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->index();
            $table->unsignedBigInteger('session_id')->nullable()->index();

            $table->text('raw_prompt');
            $table->text('normalized_prompt');

            $table->string('intent_code')->nullable()->index();
            $table->enum('decision', ['direct', 'clarify', 'guided'])->index();
            $table->enum('feedback', ['success', 'fail', 'unknown'])->default('unknown')->index();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_decision_logs');
    }
};
