<?php

namespace Cheney\AdminSystem\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $fillable = [
        'username',
        'password',
        'name',
        'email',
        'phone',
        'avatar',
        'status',
        'last_login_at',
        'last_login_ip',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'status' => 'integer',
        'last_login_at' => 'datetime',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_user');
    }

    public function permissions()
    {
        return $this->roles->pluck('permissions')->flatten()->unique('id');
    }

    public function hasPermission($permissionCode)
    {
        return $this->permissions()->contains('code', $permissionCode);
    }

    public function hasRole($roleCode)
    {
        return $this->roles()->where('code', $roleCode)->exists();
    }

    public function operationLogs()
    {
        return $this->hasMany(OperationLog::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}