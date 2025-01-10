<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public static function ensureExists(string $name, ?string $description = null): self
    {
        return static::firstOrCreate(
            ['name' => $name],
            ['description' => $description]
        );
    }
}