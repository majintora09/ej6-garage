<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InspectionPoint extends Model
{
    protected $fillable = [
        'name',
        'category',
        'status',
        'priority',
        'description',
        'x',
        'y',
        'z',
    ];
}
