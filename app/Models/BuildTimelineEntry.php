<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BuildTimelineEntry extends Model
{
    protected $fillable = [
        'user_id',
        'car_profile_id',
        'title',
        'category',
        'description',
        'event_date',
        'mileage',
        'cost',
        'image_path',
    ];

    protected function casts(): array
    {
        return [
            'event_date' => 'date',
            'mileage' => 'integer',
            'cost' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function carProfile(): BelongsTo
    {
        return $this->belongsTo(CarProfile::class);
    }
}
