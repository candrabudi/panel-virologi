<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiPerformanceMetric extends Model
{
    use HasFactory;

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
}
