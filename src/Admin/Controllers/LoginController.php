<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2026/1/18
 * Time: 12:32
 */

namespace Cheney\Content\Admin\Controllers;

use Illuminate\Http\Request;
use Cheney\Content\Services\AdminService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Cheney\Content\Traits\ResponseTrait;

class LoginController extends Controller{
    use ResponseTrait;
    /**
     * @var AdminService
     */
    private $service;

    public function __construct(AdminService $adminService)
    {
        $this->service = $adminService;
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function cacheKey(){
        $data = Str::random(10);
        //缓存随机数
        Cache::put(config("content.admin_cache_key"),$data);
        $message = !$data ? "没有数据": "成功";
        $msgCode = !$data ? "20000": "100000";
        if ($data){
            return self::success($data);
        }else{
            return self::error(20000,'请求失败');
        }
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
        $inputs = $request->only('name','password');
        //验证输入框
        $validator = Validator::make($inputs, [
            'name'          => ['required'],
            'password'     => ['required']
        ]);
        if ($validator->fails()) {
            return self::parametersIllegal($validator->messages()->first());
        }

        $name     = $inputs['name'];
        $password = $inputs['password'];
        $result = $this->service -> login($name , $password);

        if ($result){
            return self::success($result);
        }else{
            return self::error(20000,'用户名密码错误');
        }
    }
}