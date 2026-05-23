<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Mod extends Model
{
    protected $fillable = [
        'user_id',
        'car_profile_id',
        'name',
        'category',
        'price',
        'priority',
        'status',
        'link',
        'notes',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function carProfile(): BelongsTo
    {
        return $this->belongsTo(CarProfile::class);
    }
}
