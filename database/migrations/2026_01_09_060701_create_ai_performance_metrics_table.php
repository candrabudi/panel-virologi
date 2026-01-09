<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('ai_performance_metrics')) {
            Schema::create('ai_performance_metrics', function (Blueprint $table) {
                $table->id();
                $table->date('metric_date');
                $table->integer('total_queries')->default(0);
                $table->integer('successful_responses')->default(0);
                $table->integer('failed_responses')->default(0);
                $table->decimal('average_response_time', 8, 2)->default(0);
                $table->decimal('user_satisfaction_score', 3, 2)->default(0);
                $table->integer('knowledge_base_hits')->default(0);
                $table->integer('new_learnings')->default(0);
                $table->text('top_topics')->nullable();
                $table->text('improvement_areas')->nullable();
                $table->timestamps();
                
                $table->unique('metric_date');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_performance_metrics');
    }
};
