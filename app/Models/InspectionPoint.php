<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InspectionPoint extends Model
{
    protected $fillable = [
        'car_profile_id',
        'name',
        'category',
        'status',
        'priority',
        'description',
        'x',
        'y',
        'z',
        'normalized_x',
        'normalized_y',
        'normalized_z',
    ];

    protected function casts(): array
    {
        return [
            'x' => 'float',
            'y' => 'float',
            'z' => 'float',
            'normalized_x' => 'float',
            'normalized_y' => 'float',
            'normalized_z' => 'float',
        ];
    }

    public function carProfile(): BelongsTo
    {
        return $this->belongsTo(CarProfile::class);
    }
}
