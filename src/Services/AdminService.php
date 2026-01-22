<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2026/1/19
 * Time: 10:40
 */

namespace Cheney\Content\Services;

use Cheney\Content\Services\BaseService;
use Illuminate\Support\Str;
use Cheney\Content\Models\AdminUsers;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class AdminService extends BaseService{

    public function __construct()
    {
        parent::__construct(AdminUsers::class);
    }

    /**
     * ÓÃ»§µÇÂ¼
     */
    public function login($name,$password){
        //try{
            $model = AdminUsers::query();
            $model -> where('name',$name);
            if(! $model->exists()){
                return false;
            }
            //»ñÈ¡Êı¾İ¿âÃÜÂë
            $dbPassword = $model->value('password');
            //½ØÈ¡MD5 ×Ö·û´®
            $inputCache = substr($password, 0, 10);

            //Í¨¹ıÆ´½Ó»ñÈ¡ÕæÊµÃÜÂë
            $cacheKey    = Cache::get(config("content.admin_cache_key"));
            //ÅĞ¶Ï»º´æ
            if ($cacheKey  !== $inputCache) {
                return false;
            }
            $inputPassword = substr($password, 10);
            //ÅĞ¶Ïpasswrod
            if ($inputPassword  !== $dbPassword) {
                return false;
            }
            //Éú³ÉµÇÂ¼token
            $accessToken = encrypt(Str::random(15));
            //±£´æ$accessToken
            $model ->update(['access_token' => $accessToken]);
            $adminData = $model->first();
        //}catch (\Exception $e){
            //echo $e ->getMessage();
           // return false;
        //}
        //µÇÂ¼³É¹¦É¾³ı»º´æ
        //Cache::pull($cacheKey);
        return $adminData;
    }

    /**
     * @param $token
     * @return bool
     */
    public function getTokenByAdminUser($token){
        $model = AdminUsers::query();
        $access_token = str_replace($token , "Bearer ", "" );
        $model -> where('access_toke',$access_token);
        if( !$model->exists()){
            return null;
        } else {
            return $model->first();
        }
    }

    /**
     * @return void
     */
    public function getList($params){
        $model = Articles::query();
        if (isset($params['name']) && !empty($params['name'])){
            $model ->where('name',$params['name']);
        }
        if (isset($params['status']) && !empty($params['status'])){
            $model ->where('status',$params['status']);
        }
        $orderBy   = isset($params['orderBy']) ? $params['orderBy'] : 'id';
        $orderSort = isset($params['byAsc']) ? 'ASC' : 'DESC';
        $model->orderBy($orderBy,$orderSort);
        if(isset($params['groupBy']) && $params['groupBy']){
            $model->groupBy($params['groupBy']);
        }
        if(! $model->exists()){
            return false;
        }
        if(isset($params['page_num']) && $params['page_num']){
            $page    = isset($params['page']) ? $params['page'] : 1;
            $result = $model->paginate($params['page_num'],['*'],'page',$page);
        }else{
            $result = $model->get();
        }
        return $result;
    }

}