<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiBehaviorRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'rule_name',
        'trigger_condition',
        'rule_description',
        'action',
        'examples',
        'priority',
        'is_active',
        'scope',
    ];
}
