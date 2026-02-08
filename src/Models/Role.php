<?php

namespace Cheney\AdminSystem\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'sort',
        'status',
    ];

    protected $casts = [
        'status' => 'integer',
        'sort' => 'integer',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'role_user');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }

    public function syncPermissions(array $permissionIds)
    {
        return $this->permissions()->sync($permissionIds);
    }

    public function hasPermission($permissionCode)
    {
        return $this->permissions()->where('code', $permissionCode)->exists();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }
}