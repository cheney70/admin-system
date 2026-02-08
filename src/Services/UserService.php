<?php

namespace Cheney\AdminSystem\Services;

use Cheney\AdminSystem\Models\User;
use Cheney\AdminSystem\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{
    protected $userModel;
    protected $roleModel;

    public function __construct(User $userModel, Role $roleModel)
    {
        $this->userModel = $userModel;
        $this->roleModel = $roleModel;
    }

    public function index(array $params = []): LengthAwarePaginator
    {
        $query = $this->userModel->query();

        if (isset($params['username'])) {
            $query->where('username', 'like', '%' . $params['username'] . '%');
        }

        if (isset($params['name'])) {
            $query->where('name', 'like', '%' . $params['name'] . '%');
        }

        if (isset($params['email'])) {
            $query->where('email', 'like', '%' . $params['email'] . '%');
        }

        if (isset($params['status'])) {
            $query->where('status', $params['status']);
        }

        $perPage = $params['per_page'] ?? 15;
        return $query->with('roles')->orderBy('id', 'desc')->paginate($perPage);
    }

    public function show(int $id)
    {
        return $this->userModel->with('roles')->findOrFail($id);
    }

    public function store(array $data): User
    {
        $data['password'] = bcrypt($data['password']);
        return $this->userModel->create($data);
    }

    public function update(int $id, array $data): User
    {
        $user = $this->userModel->findOrFail($id);

        if (isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        $user->update($data);
        return $user->fresh();
    }

    public function destroy(int $id): bool
    {
        $user = $this->userModel->findOrFail($id);

        $currentUser = auth('api')->user();
        if ($user->id === $currentUser->id) {
            throw new \Exception('不能删除当前登录用户');
        }

        return $user->delete();
    }

    public function assignRoles(int $userId, array $roleIds): void
    {
        $user = $this->userModel->findOrFail($userId);
        $user->roles()->sync($roleIds);
    }

    public function resetPassword(int $userId, string $password): void
    {
        $user = $this->userModel->findOrFail($userId);
        $user->update([
            'password' => bcrypt($password),
        ]);
    }
}