<?php

namespace Cheney\AdminSystem\Services;

use Cheney\AdminSystem\Models\Permission;
use Illuminate\Pagination\LengthAwarePaginator;

class PermissionService
{
    protected $permissionModel;

    public function __construct(Permission $permissionModel)
    {
        $this->permissionModel = $permissionModel;
    }

    public function index(array $params = []): LengthAwarePaginator
    {
        $query = $this->permissionModel->query();

        if (isset($params['name'])) {
            $query->where('name', 'like', '%' . $params['name'] . '%');
        }

        if (isset($params['code'])) {
            $query->where('code', 'like', '%' . $params['code'] . '%');
        }

        if (isset($params['type'])) {
            $query->where('type', $params['type']);
        }

        if (isset($params['menu_id'])) {
            $query->where('menu_id', $params['menu_id']);
        }

        $perPage = $params['per_page'] ?? 15;
        return $query->with('menu')->orderBy('id')->paginate($perPage);
    }

    public function show(int $id)
    {
        return $this->permissionModel->with('menu', 'roles')->findOrFail($id);
    }

    public function store(array $data): Permission
    {
        return $this->permissionModel->create($data);
    }

    public function update(int $id, array $data): Permission
    {
        $permission = $this->permissionModel->findOrFail($id);
        $permission->update($data);
        return $permission->fresh();
    }

    public function destroy(int $id): bool
    {
        $permission = $this->permissionModel->findOrFail($id);

        if ($permission->roles()->exists()) {
            throw new \Exception('该权限已被角色使用，无法删除');
        }

        return $permission->delete();
    }
}
