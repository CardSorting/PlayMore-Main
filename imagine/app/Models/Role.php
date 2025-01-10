<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function hasPermission(string $permission): bool
    {
        return $this->permissions()->where('name', $permission)->exists();
    }

    public function givePermissionTo(Permission|string $permission): void
    {
        if (is_string($permission)) {
            $permission = Permission::whereName($permission)->firstOrFail();
        }
        
        if (!$this->hasPermission($permission->name)) {
            $this->permissions()->attach($permission);
        }
    }

    public function revokePermissionTo(Permission|string $permission): void
    {
        if (is_string($permission)) {
            $permission = Permission::whereName($permission)->firstOrFail();
        }

        $this->permissions()->detach($permission);
    }

    public function syncPermissions(array $permissions): void
    {
        $permissionIds = collect($permissions)->map(function ($permission) {
            return is_string($permission) 
                ? Permission::whereName($permission)->firstOrFail()->id 
                : $permission->id;
        });

        $this->permissions()->sync($permissionIds);
    }
}
