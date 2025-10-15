<?php

namespace RbacAuth\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'role_user',
            'user_id',
            'role_id'
        );
    }

    public function permissions(): mixed
    {
        return $this->roles->flatMap->permissions->pluck('name')->unique();
    }

    public function hasPermission(string $permission): bool
    {
        return $this->permissions()->contains($permission);
    }

    public function hasPermissionTo(string $permission): bool
    {
        return $this->hasPermission($permission);
    }

    public function hasAnyPermission(array $permissions): bool
    {
        return $this->permissions()->intersect($permissions)->isNotEmpty();
    }

    public function hasRole($role): mixed
    {
        return $this->roles->contains('name', $role);
    }

    public function hasAnyRole(array $roles): mixed
    {
        return $this->roles->whereIn('name', $roles)->isNotEmpty();
    }
}
