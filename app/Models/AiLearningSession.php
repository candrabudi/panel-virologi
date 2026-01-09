<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiLearningSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'chat_session_id',
        'user_id',
        'user_query',
        'ai_response',
        'extracted_insights',
        'was_helpful',
        'feedback_score',
        'correction',
        'added_to_knowledge_base',
        'knowledge_base_id',
    ];
}
