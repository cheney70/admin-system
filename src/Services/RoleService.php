<?php

namespace Cheney\AdminSystem\Services;

use Cheney\AdminSystem\Models\Role;
use Cheney\AdminSystem\Models\Permission;
use Illuminate\Pagination\LengthAwarePaginator;

class RoleService
{
    protected $roleModel;
    protected $permissionModel;

    public function __construct(Role $roleModel, Permission $permissionModel)
    {
        $this->roleModel = $roleModel;
        $this->permissionModel = $permissionModel;
    }

    public function index(array $params = []): LengthAwarePaginator
    {
        $query = $this->roleModel->query();

        if (isset($params['name'])) {
            $query->where('name', 'like', '%' . $params['name'] . '%');
        }

        if (isset($params['code'])) {
            $query->where('code', 'like', '%' . $params['code'] . '%');
        }

        if (isset($params['status'])) {
            $query->where('status', $params['status']);
        }

        $perPage = $params['per_page'] ?? 15;
        return $query->with('permissions')->orderBy('sort')->paginate($perPage);
    }

    public function show(int $id)
    {
        return $this->roleModel->with('permissions')->findOrFail($id);
    }

    public function store(array $data): Role
    {
        return $this->roleModel->create($data);
    }

    public function update(int $id, array $data): Role
    {
        $role = $this->roleModel->findOrFail($id);
        $role->update($data);
        return $role->fresh();
    }

    public function destroy(int $id): bool
    {
        $role = $this->roleModel->findOrFail($id);

        if ($role->users()->exists()) {
            throw new \Exception('该角色下有用户，无法删除');
        }

        return $role->delete();
    }

    public function assignPermissions(int $roleId, array $permissionIds): void
    {
        $role = $this->roleModel->findOrFail($roleId);
        $role->syncPermissions($permissionIds);
    }
}