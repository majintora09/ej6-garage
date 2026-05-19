<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarPhoto extends Model
{
    protected $fillable = [
        'car_profile_id',
        'path',
        'original_name',
        'caption',
    ];

    public function carProfile(): BelongsTo
    {
        return $this->belongsTo(CarProfile::class);
    }
}
