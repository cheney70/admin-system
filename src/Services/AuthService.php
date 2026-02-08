<?php

namespace Cheney\AdminSystem\Services;

use Cheney\AdminSystem\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use Cheney\AdminSystem\Exceptions\AuthException;

class AuthService
{
    protected $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    public function login(string $username, string $password)
    {
        $user = $this->userModel->where('username', $username)->first();

        if (!$user) {
            throw new AuthException('用户不存在');
        }

        if ($user->status != 1) {
            throw new AuthException('账号已被禁用');
        }

        if (!Hash::check($password, $user->password)) {
            throw new AuthException('密码错误');
        }

        $token = JWTAuth::fromUser($user);

        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => request()->ip(),
        ]);

        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
            'user' => $this->getUserInfo($user),
        ];
    }

    public function logout()
    {
        auth('api')->logout();
    }

    public function refresh()
    {
        $token = auth('api')->refresh();
        $user = auth('api')->user();

        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60,
        ];
    }

    public function me()
    {
        $user = auth('api')->user();
        return $this->getUserInfo($user);
    }

    protected function getUserInfo($user)
    {
        return [
            'id' => $user->id,
            'username' => $user->username,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'avatar' => $user->avatar,
            'roles' => $user->roles,
            'permissions' => $user->permissions()->values(),
            'created_at' => $user->created_at,
        ];
    }
}