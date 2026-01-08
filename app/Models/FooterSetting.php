<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FooterSetting extends Model
{
    protected $fillable = [
        'description',
        'address',
        'email',
        'phone',
        'copyright_text',
        'social_links',
        'column_1_title',
        'column_1_links',
        'column_2_title',
        'column_2_links',
        'column_3_title',
        'column_3_links',
    ];

    protected $casts = [
        'social_links' => 'array',
        'column_1_links' => 'array',
        'column_2_links' => 'array',
        'column_3_links' => 'array',
    ];
}
