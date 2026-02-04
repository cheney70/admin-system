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
     * 登录
     */
    public function login($name,$password){
        //try{
            $model = AdminUsers::query();
            $model -> where('name',$name);
            if(! $model->exists()){
                return false;
            }
            // 查修数据
            $dbPassword = $model->value('password');
            // 获取真实密码
            $inputCache = substr($password, 0, 10);

            // 获取密钥
            $cacheKey    = Cache::pull(config("content.admin_cache_key"));
            // 上传密钥和缓存对比
            if ($cacheKey  !== $inputCache) {
                return false;
            }
            $inputPassword = substr($password, 10);
            // 验证密码
            if ($inputPassword  !== $dbPassword) {
                return false;
            }
            // 生成同ken
            $accessToken = encrypt(Str::random(15));
            // $accessToken
            $model ->update(['access_token' => $accessToken]);
            $adminData = $model->first();
        //}catch (\Exception $e){
            //echo $e ->getMessage();
           // return false;
        //}
        //��¼�ɹ�ɾ������
        //Cache::pull($cacheKey);
        return $adminData;
    }

    public function logout($id){
        $admin = AdminUsers::findOrFail($id);
        $admin->access_token = '';
        return $admin->save();
    }

    /**
     * @param $token
     * @return bool
     */
    public function getTokenByAdminUser($token){
        $model = AdminUsers::query()->with('roles')->with('permissions');
        $access_token = str_replace("Bearer ", "" ,$token);
        $model -> where('access_token',trim($access_token));
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
        $model = AdminUsers::query();
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