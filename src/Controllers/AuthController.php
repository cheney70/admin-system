<?php

namespace Cheney\AdminSystem\Controllers;

use Illuminate\Http\Request;
use Cheney\AdminSystem\Services\AuthService;
use Cheney\AdminSystem\Traits\ApiResponseTrait;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    use ApiResponseTrait;

    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request)
    {
        try {
            $validated = $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ]);

            $result = $this->authService->login($validated);

            return $this->success($result, '登录成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function logout()
    {
        try {
            $this->authService->logout();
            return $this->success(null, '退出成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function refresh()
    {
        try {
            $token = $this->authService->refresh();
            return $this->success($token, '刷新成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function me()
    {
        try {
            $admin = $this->authService->me();
            return $this->success($admin);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function updateProfile(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:50',
                'email' => 'nullable|email|max:100',
                'phone' => 'nullable|string|max:20',
                'avatar' => 'nullable|string',
            ]);

            $admin = $this->authService->updateProfile($validated);
            return $this->success($admin, '更新成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $validated = $request->validate([
                'old_password' => 'required|string',
                'new_password' => 'required|string|min:6|confirmed',
            ]);

            $this->authService->changePassword($validated);
            return $this->success(null, '密码修改成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
