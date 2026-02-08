<?php

namespace Cheney\AdminSystem\Controllers;

use Illuminate\Http\Request;
use Cheney\AdminSystem\Services\UserService;
use Cheney\AdminSystem\Traits\ApiResponseTrait;

class UserController extends Controller
{
    use ApiResponseTrait;

    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index(Request $request)
    {
        try {
            $admins = $this->userService->index($request->all());
            return $this->successPaginated($admins);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'username' => 'required|string|max:50|unique:admins',
                'password' => 'required|string|min:6',
                'name' => 'required|string|max:50',
                'email' => 'nullable|email|max:100|unique:admins',
                'phone' => 'nullable|string|max:20',
                'avatar' => 'nullable|string',
                'status' => 'nullable|integer|in:0,1',
            ]);

            $admin = $this->userService->store($validated);
            return $this->created($admin);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $admin = $this->userService->show($id);
            return $this->success($admin);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'username' => 'required|string|max:50|unique:admins,username,' . $id,
                'name' => 'required|string|max:50',
                'email' => 'nullable|email|max:100|unique:admins,email,' . $id,
                'phone' => 'nullable|string|max:20',
                'avatar' => 'nullable|string',
                'status' => 'nullable|integer|in:0,1',
            ]);

            $admin = $this->userService->update($id, $validated);
            return $this->success($admin, '更新成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->userService->destroy($id);
            return $this->success(null, '删除成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function assignRoles(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'role_ids' => 'required|array',
                'role_ids.*' => 'exists:roles,id',
            ]);

            $this->userService->assignRoles($id, $validated['role_ids']);
            return $this->success(null, '分配角色成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function resetPassword(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'password' => 'required|string|min:6',
            ]);

            $this->userService->resetPassword($id, $validated['password']);
            return $this->success(null, '重置密码成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function changeStatus(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|integer|in:0,1',
            ]);

            $this->userService->changeStatus($id, $validated['status']);
            return $this->success(null, '状态修改成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
