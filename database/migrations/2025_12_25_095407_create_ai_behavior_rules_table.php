<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('ai_behavior_rules')) {
            Schema::create('ai_behavior_rules', function (Blueprint $table) {
                $table->id();
                $table->string('rule_name', 100);
                $table->string('trigger_condition', 200);
                $table->text('rule_description');
                $table->text('action');
                $table->text('examples')->nullable();
                $table->integer('priority')->default(0);
                $table->boolean('is_active')->default(true);
                $table->string('scope', 50)->default('global');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_behavior_rules');
    }
};
