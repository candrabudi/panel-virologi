<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'hero_badge',
        'hero_title',
        'hero_description',
        'channels',
        'social_title',
        'social_description',
        'seo_title',
        'seo_description',
        'seo_keywords',
    ];

    protected $casts = [
        'channels' => 'array',
        'seo_keywords' => 'array',
    ];
}
