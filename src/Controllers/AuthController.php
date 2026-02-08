<?php

namespace Cheney\AdminSystem\Controllers;

use Illuminate\Http\Request;
use Admin\Services\AuthService;
use Admin\Traits\ApiResponseTrait;

/**
 * @OA\Info(
 *     title="Ant Admin System API",
 *     version="1.0.0",
 *     description="后台管理系统API文档"
 * )
 * 
 * @OA\Server(
 *     url="/api",
 *     description="API Server"
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class AuthController extends Controller
{
    use ApiResponseTrait;

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * 用户登录
     * 
     * @OA\Post(
     *     path="/login",
     *     summary="用户登录",
     *     tags={"认证"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"username","password"},
     *             @OA\Property(property="username", type="string", example="admin"),
     *             @OA\Property(property="password", type="string", example="123456")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="登录成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="message", type="string", example="登录成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="认证失败"
     *     )
     * )
     */
    public function login(Request $request)
    {
        try {
            $data = $this->authService->login(
                $request->username,
                $request->password
            );
            return $this->success($data, '登录成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * 获取当前用户信息
     * 
     * @OA\Get(
     *     path="/me",
     *     summary="获取当前用户信息",
     *     tags={"认证"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="message", type="string", example="操作成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function me()
    {
        try {
            $user = $this->authService->me();
            return $this->successWithData($user);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * 用户退出
     * 
     * @OA\Post(
     *     path="/logout",
     *     summary="用户退出",
     *     tags={"认证"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="退出成功"
     *     )
     * )
     */
    public function logout()
    {
        try {
            $this->authService->logout();
            return $this->successWithMessage('退出成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    /**
     * 刷新Token
     * 
     * @OA\Post(
     *     path="/refresh",
     *     summary="刷新Token",
     *     tags={"认证"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="刷新成功",
     *         @OA\JsonContent(
     *             @OA\Property(property="code", type="integer", example=10000),
     *             @OA\Property(property="message", type="string", example="刷新成功"),
     *             @OA\Property(property="data", type="object")
     *         )
     *     )
     * )
     */
    public function refresh()
    {
        try {
            $data = $this->authService->refresh();
            return $this->success($data, '刷新成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}