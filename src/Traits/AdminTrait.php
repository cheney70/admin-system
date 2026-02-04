<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2026/1/24
 * Time: 13:39
 */

namespace Cheney\Content\Traits;

trait AdminTrait
{
    /**
     * get auth user
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public static function authUser()
    {
        return app()->make('admin');
    }

    /**
     * get auth user id
     *
     * @return int|null
     */
    public static function authAdminId()
    {
        $user = self::authUser();
        return $user->id;
    }
}