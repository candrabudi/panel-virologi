<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemTrafficLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'method',
        'path',
        'query_params',
        'payload',
        'headers',
        'response_status',
        'latency_ms',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'query_params' => 'array',
        'payload' => 'array',
        'headers' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
