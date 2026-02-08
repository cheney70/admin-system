<?php

namespace Cheney\AdminSystem\Controllers;

use Illuminate\Http\Request;
use Admin\Services\PermissionService;
use Admin\Traits\ApiResponseTrait;

class PermissionController extends Controller
{
    use ApiResponseTrait;

    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public function index(Request $request)
    {
        try {
            $permissions = $this->permissionService->index($request->all());
            return $this->successPaginated($permissions);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'code' => 'required|string|max:100|unique:permissions',
                'description' => 'nullable|string',
                'menu_id' => 'nullable|integer|exists:menus,id',
                'type' => 'required|integer|in:1,2',
            ]);

            $permission = $this->permissionService->store($validated);
            return $this->created($permission);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $permission = $this->permissionService->show($id);
            return $this->successWithData($permission);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:100',
                'code' => 'required|string|max:100|unique:permissions,code,' . $id,
                'description' => 'nullable|string',
                'menu_id' => 'nullable|integer|exists:menus,id',
                'type' => 'required|integer|in:1,2',
            ]);

            $permission = $this->permissionService->update($id, $validated);
            return $this->updated($permission);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->permissionService->destroy($id);
            return $this->deleted();
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}