<?php

namespace Cheney\AdminSystem\Controllers;

use Illuminate\Http\Request;
use Admin\Services\UserService;
use Admin\Traits\ApiResponseTrait;

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
            $users = $this->userService->index($request->all());
            return $this->successPaginated($users);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'username' => 'required|string|max:50|unique:users',
                'password' => 'required|string|min:6',
                'name' => 'required|string|max:50',
                'email' => 'nullable|email|max:100|unique:users',
                'phone' => 'nullable|string|max:20',
                'avatar' => 'nullable|string',
                'status' => 'nullable|integer|in:0,1',
            ]);

            $user = $this->userService->store($validated);
            return $this->created($user);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $user = $this->userService->show($id);
            return $this->successWithData($user);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'username' => 'required|string|max:50|unique:users,username,' . $id,
                'password' => 'nullable|string|min:6',
                'name' => 'required|string|max:50',
                'email' => 'nullable|email|max:100|unique:users,email,' . $id,
                'phone' => 'nullable|string|max:20',
                'avatar' => 'nullable|string',
                'status' => 'nullable|integer|in:0,1',
            ]);

            $user = $this->userService->update($id, $validated);
            return $this->updated($user);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->userService->destroy($id);
            return $this->deleted();
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function assignRoles(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'role_ids' => 'required|array',
                'role_ids.*' => 'integer|exists:roles,id',
            ]);

            $this->userService->assignRoles($id, $validated['role_ids']);
            return $this->successWithMessage('角色分配成功');
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
            return $this->successWithMessage('密码重置成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}