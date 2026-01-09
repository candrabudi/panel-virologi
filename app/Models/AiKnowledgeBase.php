<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiKnowledgeBase extends Model
{
    use HasFactory;

    protected $table = 'ai_knowledge_base';

    protected $fillable = [
        'category',
        'topic',
        'content',
        'context',
        'examples',
        'references',
        'tags',
        'embedding',
        'usage_count',
        'relevance_score',
        'source',
        'created_by',
        'last_used_at',
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
    ];
}
