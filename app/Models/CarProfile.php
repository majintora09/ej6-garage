<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarProfile extends Model
{
    protected $fillable = [
        'user_id',
        'make',
        'model',
        'chassis',
        'year',
        'engine',
        'color_name',
        'color_code',
        'theme_color',
        'interior',
        'body_type',
        'model_path',
        'build_vibe',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(CarPhoto::class)->latest();
    }
}
