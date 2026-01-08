<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeakDataRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'leak_check_log_id',
        'query',
        'full_name',
        'email',
        'phone_number',
        'reason',
        'department',
        'requester_status',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leakCheckLog()
    {
        return $this->belongsTo(LeakCheckLog::class);
    }
}
