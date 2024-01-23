<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable, HasFactory, HasRoles;

    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    protected $hidden = [
        'id', 'password', 'updated_at', 'permissions'
    ];

    protected $casts = [
        'status' => 'boolean',
        'created_at' => 'datetime:Y-m-d H:i:s'
    ];

    public static function boot(): void
    {
        parent::boot();

        static::created(function (User $user) {
            $user->assignRole('Basic');
        });
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function isDisabled(): bool
    {
        return !$this->getAttribute('status');
    }
}
