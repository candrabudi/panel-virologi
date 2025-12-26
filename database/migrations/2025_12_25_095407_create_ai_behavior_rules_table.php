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
        Schema::create('ai_behavior_rules', function (Blueprint $table) {
            $table->id();

            $table->string('intent_code')->nullable()->index();
            $table->string('pattern')->nullable()->index();
            $table->string('scope_code')->nullable();
            $table->string('pattern_type')->nullable();
            $table->string('pattern_value')->nullable();
            $table->string('guided_kind')->nullable();

            $table->enum('decision', ['direct', 'clarify', 'guided'])->index();
            $table->unsignedInteger('priority')->default(50);

            $table->unsignedInteger('success_count')->default(0);
            $table->unsignedInteger('fail_count')->default(0);

            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_behavior_rules');
    }
};
