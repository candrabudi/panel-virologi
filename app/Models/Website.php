<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    protected $fillable = [
        'name',
        'tagline',
        'description',
        'logo_rectangle',
        'logo_square',
        'favicon',
        'email',
        'phone',
        'copyright_text',
    ];

    protected $casts = [
        'extra_meta' => 'array',
    ];
}
