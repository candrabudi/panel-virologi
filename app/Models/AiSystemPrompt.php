<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiSystemPrompt extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'version',
        'base_prompt',
        'personality_traits',
        'capabilities',
        'response_templates',
        'custom_rules',
        'is_active',
        'priority',
    ];
}
