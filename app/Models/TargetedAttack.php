<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetedAttack extends Model
{
    use HasFactory;

    protected $fillable = [
        'target_url',
        'attack_vector',
        'severity',
        'affected_asset',
        'status',
        'details',
        'incident_at',
    ];
}
