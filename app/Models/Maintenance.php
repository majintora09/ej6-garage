<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $fillable = [
        'title',
        'category',
        'mileage',
        'cost',
        'notes',
        'service_date',
        'next_due_date',
        'next_due_mileage',
    ];
}
