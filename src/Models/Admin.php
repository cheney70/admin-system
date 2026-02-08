<?php

namespace Cheney\AdminSystem\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Database\Factories\AdminFactory;

class Admin extends Authenticatable implements JWTSubject
{
    use Notifiable, HasFactory;

    protected static function newFactory()
    {
        return AdminFactory::new();
    }

    protected $table = 'admins';

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
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'status' => 'integer',
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
        return $this->belongsToMany(Role::class, 'role_admin', 'admin_id', 'role_id');
    }

    public function operationLogs()
    {
        return $this->hasMany(OperationLog::class, 'admin_id');
    }

    public function hasPermission($permissionCode)
    {
        foreach ($this->roles as $role) {
            if ($role->hasPermission($permissionCode)) {
                return true;
            }
        }
        return false;
    }

    public function hasAnyPermission(array $permissionCodes)
    {
        foreach ($this->roles as $role) {
            if ($role->hasAnyPermission($permissionCodes)) {
                return true;
            }
        }
        return false;
    }

    public function hasAllPermissions(array $permissionCodes)
    {
        foreach ($this->roles as $role) {
            if ($role->hasAllPermissions($permissionCodes)) {
                return true;
            }
        }
        return false;
    }
}
