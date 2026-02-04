<?php

namespace Cheney\Content\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    protected $table= 'admin_roles';
    use SoftDeletes;

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];
    /**
     * A role belongs to many users.
     *
     * @return
     */
    public function administrators()
    {
        return $this->belongsToMany(AdminUsers::class, RoueUsers::class, 'role_id', 'user_id');
    }

    /**
     * A role belongs to many permissions.
     *
     * @return
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, RolePermission::class, 'role_id', 'permission_id');
    }

    /**
     * A role belongs to many menus.
     *
     * @return
     */
    public function menus()
    {
        return $this->belongsToMany(Menu::class, RoleMenu::class, 'role_id', 'menu_id');
    }
}
