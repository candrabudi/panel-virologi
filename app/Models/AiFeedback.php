<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiFeedback extends Model
{
    use HasFactory;

    protected $table = 'ai_feedback';

    protected $fillable = [
        'chat_message_id',
        'user_id',
        'feedback_type',
        'rating',
        'comment',
        'suggested_improvement',
        'reviewed_by_admin',
        'applied_to_training',
    ];
}
