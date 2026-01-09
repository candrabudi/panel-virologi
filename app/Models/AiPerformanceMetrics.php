<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiPerformanceMetrics extends Model
{
    protected $table = 'ai_performance_metrics';

    protected $fillable = [
        'metric_date',
        'total_queries',
        'successful_responses',
        'failed_responses',
        'average_response_time',
        'user_satisfaction_score',
        'knowledge_base_hits',
        'new_learnings',
        'top_topics',
        'improvement_areas',
    ];

    protected $casts = [
        'metric_date' => 'date',
        'total_queries' => 'integer',
        'successful_responses' => 'integer',
        'failed_responses' => 'integer',
        'average_response_time' => 'decimal:2',
        'user_satisfaction_score' => 'decimal:2',
        'knowledge_base_hits' => 'integer',
        'new_learnings' => 'integer',
        'top_topics' => 'array',
        'improvement_areas' => 'array',
    ];
}
