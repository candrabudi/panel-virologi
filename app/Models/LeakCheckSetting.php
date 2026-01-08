<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeakCheckSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_endpoint',
        'api_token',
        'default_limit',
        'lang',
        'bot_name',
        'is_enabled',
    ];
}
