<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mod extends Model
{
    protected $fillable = [
        'name',
        'category',
        'price',
        'priority',
        'status',
        'link',
        'notes',
    ];
}
