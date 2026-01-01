<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'phone_number',
        'avatar',
        'company',
        'job_title',
        'country',
        'city',
        'bio',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
