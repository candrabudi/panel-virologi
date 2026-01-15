<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecurityMetric extends Model
{
    use HasFactory;

    protected $fillable = [
        'active_botnets',
        'c2_nodes_blocked',
        'traffic_scrubbed',
        'threat_level',
        'metric_date'
    ];
}
