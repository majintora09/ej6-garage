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
        'category',
        'caption',
        'notes',
        'visibility',
        'image_position',
    ];

    public function carProfile(): BelongsTo
    {
        return $this->belongsTo(CarProfile::class);
    }
}
