<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2026/1/18
 * Time: 12:32
 */

namespace Cheney\Content\Controllers\Admin;

use Illuminate\Http\Request;
use Cheney\Content\Services\AdminService;
use Illuminate\Support\Facades\Validator;
use Cheney\Content\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Cheney\Content\Traits\ResponseTrait;
use Illuminate\Support\Str;
use Cheney\Content\Traits\AdminTrait;

class LoginController extends Controller{
    use ResponseTrait , AdminTrait;
    /**
     * @var AdminService
     */
    private $service;

    public function __construct(AdminService $adminService)
    {
        $this->service = $adminService;
    }

    /**
     * 获取登录key
     *
     * @OA\Get(path="/admin/login/cachekey",
     *   tags={"管理后台接口"},
     *   operationId="getLoginKey",
     *   description="获取登录key",
     *   @OA\Response(response="100000", description="success"),
     *   @OA\Response(response="200000", description="fail"),
     * )
     */
    public function cacheKey(){
        $data = Str::random(10);
        //缓存随机数
        Cache::put(config("content.admin_cache_key"),$data);
        if ($data){
            return self::success($data);
        }else{
            return self::error(20000,'请求失败');
        }
    }

    /**
     * 后台登录
     *
     * @OA\Post(path="/admin/login",     *
     *   tags={"管理后台接口"},
     *   operationId="adminLogin",
     *   description="后台登录",
     *   @OA\Parameter(name="username",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(name="password",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Response(response="100000", description="success"),
     *   @OA\Response(response="200000", description="fail"),
     * )
     */
    public function login(Request $request){
        $inputs = $request->only('username','password');
        //验证输入框
        $validator = Validator::make($inputs, [
            'username'     => ['required'],
            'password'     => ['required']
        ]);
        if ($validator->fails()) {
            return self::parametersIllegal($validator->messages()->first());
        }

        $name     = $inputs['username'];
        $password = $inputs['password'];
        $result = $this->service -> login($name , $password);

        if ($result){
            return self::success($result);
        }else{
            return self::error(20000,'用户名密码错误');
        }
    }

    /**
     * 退出登录
     *
     * @OA\Get(path="/admin/logout",
     *   tags={"管理后台接口"},
     *   operationId="adminLogout",
     *   description="退出登录",
     *   security={{"Authorization":{}}},
     *   @OA\Response(response="100000", description="success"),
     *   @OA\Response(response="200000", description="fail"),
     * )
     */
    public function logout(){
        $id = self::authAdminId();
        $result = $this->service->logout($id);
        if ($result){
            return self::success(true);
        }else{
            return self::error(20000,'退出失败请重试');
        }
    }
}