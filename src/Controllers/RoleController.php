<?php

namespace Cheney\AdminSystem\Controllers;

use Illuminate\Http\Request;
use Cheney\AdminSystem\Services\RoleService;
use Cheney\AdminSystem\Traits\ApiResponseTrait;

class RoleController extends Controller
{
    use ApiResponseTrait;

    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index(Request $request)
    {
        try {
            $roles = $this->roleService->index($request->all());
            return $this->successPaginated($roles);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:50',
                'code' => 'required|string|max:50|unique:roles',
                'description' => 'nullable|string|max:255',
                'sort' => 'nullable|integer|min:0',
                'status' => 'nullable|integer|in:0,1',
            ]);

            $role = $this->roleService->store($validated);
            return $this->created($role);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $role = $this->roleService->show($id);
            return $this->success($role);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:50',
                'code' => 'required|string|max:50|unique:roles,code,' . $id,
                'description' => 'nullable|string|max:255',
                'sort' => 'nullable|integer|min:0',
                'status' => 'nullable|integer|in:0,1',
            ]);

            $role = $this->roleService->update($id, $validated);
            return $this->success($role, '更新成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->roleService->destroy($id);
            return $this->success(null, '删除成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function assignPermissions(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'permission_ids' => 'required|array',
                'permission_ids.*' => 'exists:permissions,id',
            ]);

            $this->roleService->assignPermissions($id, $validated['permission_ids']);
            return $this->success(null, '分配权限成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function assignAdmins(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'admin_ids' => 'required|array',
                'admin_ids.*' => 'exists:admins,id',
            ]);

            $this->roleService->assignAdmins($id, $validated['admin_ids']);
            return $this->success(null, '分配管理员成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
