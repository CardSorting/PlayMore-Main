<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Services\PulseService;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function galleries(): HasMany
    {
        return $this->hasMany(Gallery::class);
    }

    public function packs(): HasMany
    {
        return $this->hasMany(Pack::class);
    }

    public function creditTransactions(): HasMany
    {
        return $this->hasMany(CreditTransaction::class);
    }

    public function printOrders(): HasMany
    {
        return $this->hasMany(PrintOrder::class);
    }

    public function getCreditBalance(): int
    {
        return app(PulseService::class)->getCreditBalance($this);
    }

    public function addCredits(int $amount, ?string $description = null, ?string $reference = null): void
    {
        app(PulseService::class)->addCredits($this, $amount, $description, $reference);
    }

    public function deductCredits(int $amount, ?string $description = null, ?string $reference = null): bool
    {
        return app(PulseService::class)->deductCredits($this, $amount, $description, $reference);
    }

    public function getCreditHistory(int $limit = 10)
    {
        return app(PulseService::class)->getTransactionHistory($this, $limit);
    }
}
