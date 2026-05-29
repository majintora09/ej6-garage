<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CommunityPost extends Model
{
    protected $fillable = [
        'user_id',
        'car_profile_id',
        'title',
        'body',
        'category',
        'image_path',
        'image_position',
        'visibility',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function carProfile(): BelongsTo
    {
        return $this->belongsTo(CarProfile::class);
    }

    public function likes(): HasMany
    {
        return $this->hasMany(CommunityPostLike::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(CommunityPostComment::class);
    }

    public function isVisibleTo(?User $user): bool
    {
        if ($this->visibility !== 'private') {
            return true;
        }

        return $user && $user->id === $this->user_id;
    }
}
