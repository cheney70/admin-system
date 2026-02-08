<?php

namespace Cheney\AdminSystem\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'menu_id',
        'type',
    ];

    protected $casts = [
        'type' => 'integer',
        'menu_id' => 'integer',
    ];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'permission_role');
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeByMenu($query, $menuId)
    {
        return $query->where('menu_id', $menuId);
    }
}