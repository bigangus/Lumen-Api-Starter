<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Laravel\Lumen\Auth\Authorizable;
use Spatie\Permission\Traits\HasPermissions;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable, HasFactory, HasRoles;

    protected $guarded = [
        'id', 'created_at', 'updated_at'
    ];

    protected $hidden = [
        'password'
    ];

    public static function boot(): void
    {
        parent::boot();
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

    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }

    public function canEditThatUser($id): bool {
        $user = User::query()->find($id);
        $ids = Entity::query()->find(Auth::user()->getAttribute('entity_id'))->children()->pluck('id')->toArray();
        return in_array($user->getAttribute('entity_id'), $ids);
    }

    public function canEditThatEntity($id): bool {
        $entity = Entity::query()->find($id);
        $ids = Entity::query()->find(Auth::user()->getAttribute('entity_id'))->children()->pluck('id')->toArray();
        return in_array($entity->getAttribute('id'), $ids);
    }
}
