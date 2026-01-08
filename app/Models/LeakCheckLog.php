<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeakCheckLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'query',
        'leak_count',
        'raw_response',
        'status',
        'error_message',
        'ip_address',
    ];

    protected $casts = [
        'raw_response' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
