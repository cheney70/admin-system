<?php

namespace Cheney\AdminSystem\Controllers;

use Illuminate\Http\Request;
use Admin\Services\RoleService;
use Admin\Traits\ApiResponseTrait;

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
                'description' => 'nullable|string',
                'sort' => 'nullable|integer',
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
            return $this->successWithData($role);
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
                'description' => 'nullable|string',
                'sort' => 'nullable|integer',
                'status' => 'nullable|integer|in:0,1',
            ]);

            $role = $this->roleService->update($id, $validated);
            return $this->updated($role);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->roleService->destroy($id);
            return $this->deleted();
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function assignPermissions(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'permission_ids' => 'required|array',
                'permission_ids.*' => 'integer|exists:permissions,id',
            ]);

            $this->roleService->assignPermissions($id, $validated['permission_ids']);
            return $this->successWithMessage('权限分配成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}