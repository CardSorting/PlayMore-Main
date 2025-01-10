<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'bio',
        'country',
        'shipping_countries',
        'website',
        'social_instagram',
        'social_twitter',
        'response_time',
        'last_active_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_active_at' => 'datetime',
        'shipping_countries' => 'array'
    ];

    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class);
    }

    public function printOrders(): HasMany
    {
        return $this->hasMany(PrintOrder::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class, 'user_id');
    }

    public function isOnline(): bool
    {
        if (!$this->last_active_at) {
            return false;
        }

        return $this->last_active_at->diffInMinutes(now()) <= 5;
    }

    public function updateLastActive(): void
    {
        $this->update(['last_active_at' => now()]);
    }

    public function getAverageRatingAttribute(): float
    {
        return $this->ratings()->avg('rating') ?? 0.0;
    }

    public function getTotalRatingsAttribute(): int
    {
        return $this->ratings()->count();
    }
}
