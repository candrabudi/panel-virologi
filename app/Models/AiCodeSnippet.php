<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiCodeSnippet extends Model
{
    use HasFactory;

    protected $fillable = [
        'language',
        'category',
        'title',
        'description',
        'secure_code',
        'insecure_code',
        'explanation',
        'security_benefits',
        'test_cases',
        'dependencies',
        'usage_count',
        'rating',
        'is_verified',
    ];
}
