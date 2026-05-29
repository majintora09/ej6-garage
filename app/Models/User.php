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
use Illuminate\Support\Str;

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
        'display_name',
        'profile_slug',
        'bio',
        'location',
        'avatar_path',
        'banner_path',
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

    public function communityPosts(): HasMany
    {
        return $this->hasMany(CommunityPost::class)->latest();
    }

    public function communityPostLikes(): HasMany
    {
        return $this->hasMany(CommunityPostLike::class);
    }

    public function communityPostComments(): HasMany
    {
        return $this->hasMany(CommunityPostComment::class);
    }

    public function displayHandle(): string
    {
        return $this->display_name ?: $this->name;
    }

    public function ensureProfileSlug(): string
    {
        if ($this->profile_slug) {
            return $this->profile_slug;
        }

        $base = Str::slug($this->displayHandle()) ?: 'driver-'.$this->id;
        $slug = $base;
        $suffix = 2;

        while (self::where('profile_slug', $slug)->where('id', '!=', $this->id)->exists()) {
            $slug = $base.'-'.$suffix;
            $suffix++;
        }

        $this->forceFill(['profile_slug' => $slug])->save();

        return $slug;
    }
}
