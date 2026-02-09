<?php

namespace Cheney\AdminSystem\Services;

use Cheney\AdminSystem\Models\Menu;
use Cheney\AdminSystem\Models\Permission;
use Tymon\JWTAuth\Facades\JWTAuth;

class MenuService
{
    protected $menuModel;
    protected $permissionModel;

    public function __construct(Menu $menuModel, Permission $permissionModel)
    {
        $this->menuModel = $menuModel;
        $this->permissionModel = $permissionModel;
    }

    public function index(array $params = [])
    {
        $query = $this->menuModel->query();

        if (isset($params['title'])) {
            $query->where('title', 'like', '%' . $params['title'] . '%');
        }

        if (isset($params['status'])) {
            $query->where('status', $params['status']);
        }

        $menus = $query->orderBy('sort')->get();
        
        return $this->buildTree($menus->toArray());
    }

    public function show(int $id)
    {
        return $this->menuModel->findOrFail($id);
    }

    public function store(array $data): Menu
    {
        return $this->menuModel->create($data);
    }

    public function update(int $id, array $data): Menu
    {
        $menu = $this->menuModel->findOrFail($id);
        $menu->update($data);
        return $menu->fresh();
    }

    public function destroy(int $id): bool
    {
        $menu = $this->menuModel->findOrFail($id);

        if ($menu->children()->exists()) {
            throw new \Exception('该菜单下有子菜单，无法删除');
        }

        return $menu->delete();
    }

    public function tree()
    {
        $menus = $this->menuModel->orderBy('sort')->get();
        return $this->buildTree($menus->toArray());
    }

    public function userMenus($admin = null)
    {
        if ($admin === null) {
            $admin = auth('admin')->user();
        }
        
        if (!$admin) {
            throw new \Exception('用户未登录或token已过期');
        }
        
        // 预加载角色和权限关联
        $admin = $admin->load(['roles', 'roles.permissions']);
        
        $permissionCodes = [];
        foreach ($admin->roles as $role) {
            foreach ($role->permissions as $permission) {
                $permissionCodes[] = $permission->code;
            }
        }
        
        $permissionCodes = array_unique($permissionCodes);
        
        $menuIds = $this->permissionModel->whereIn('code', $permissionCodes)
            ->whereNotNull('menu_id')
            ->pluck('menu_id')
            ->unique()
            ->filter();
        
        $menus = $this->menuModel->whereIn('id', $menuIds)
            ->active()
            ->notHidden()
            ->menuType()
            ->orderBy('sort')
            ->get();
        
        $menuIds = $menus->pluck('id')->toArray();
        
        $allMenuIds = $menuIds;
        
        foreach ($menuIds as $menuId) {
            $parentIds = $this->getAllParentMenuIds($menuId);
            $allMenuIds = array_merge($allMenuIds, $parentIds);
        }
        
        $allMenuIds = array_unique($allMenuIds);
        
        $allMenus = $this->menuModel->whereIn('id', $allMenuIds)
            ->active()
            ->notHidden()
            ->orderBy('sort')
            ->get();
            
        return $this->buildTree($allMenus->toArray());
    }
    
    protected function getAllParentMenuIds($menuId, &$parentIds = [])
    {
        $menu = $this->menuModel->find($menuId);
        
        if ($menu && $menu->parent_id > 0) {
            $parentIds[] = $menu->parent_id;
            $this->getAllParentMenuIds($menu->parent_id, $parentIds);
        }
        
        return $parentIds;
    }

    protected function buildTree(array $elements, $parentId = 0)
    {
        $branch = [];

        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }

        return $branch;
    }
}
