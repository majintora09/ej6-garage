<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'active_car_profile_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function carProfile(): HasOne
    {
        return $this->hasOne(CarProfile::class);
    }

    public function carProfiles(): HasMany
    {
        return $this->hasMany(CarProfile::class)->latest();
    }

    public function activeCarProfile(): BelongsTo
    {
        return $this->belongsTo(CarProfile::class, 'active_car_profile_id');
    }

    public function activeCar(): ?CarProfile
    {
        $activeCar = $this->activeCarProfile;

        if ($activeCar && $activeCar->user_id === $this->id) {
            return $activeCar;
        }

        return $this->carProfile;
    }

    public function buildTimelineEntries(): HasMany
    {
        return $this->hasMany(BuildTimelineEntry::class);
    }
}
