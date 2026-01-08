<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AboutSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'hero_badge',
        'hero_title',
        'hero_description',
        'hero_image',
        'story_title',
        'story_content',
        'story_image',
        'vision_title',
        'vision_content',
        'mission_title',
        'mission_items',
        'stats',
        'core_values',
        'team_members',
        'seo_title',
        'seo_description',
        'seo_keywords',
    ];

    protected $casts = [
        'mission_items' => 'array',
        'stats' => 'array',
        'core_values' => 'array',
        'team_members' => 'array',
        'seo_keywords' => 'array',
    ];
}
