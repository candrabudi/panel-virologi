<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiTrainingData extends Model
{
    use HasFactory;

    protected $table = 'ai_training_data';

    protected $fillable = [
        'category',
        'question',
        'ideal_answer',
        'context',
        'metadata',
        'is_approved',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
    ];
}
