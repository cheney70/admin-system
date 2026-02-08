<?php

namespace Cheney\AdminSystem\Services;

use Cheney\AdminSystem\Models\Menu;
use Cheney\AdminSystem\Models\Permission;

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

    public function getUserMenus()
    {
        $user = auth('api')->user();
        $permissions = $user->permissions();
        
        $menuIds = $permissions->pluck('menu_id')->unique()->filter();
        
        $menus = $this->menuModel->whereIn('id', $menuIds)
            ->active()
            ->notHidden()
            ->orderBy('sort')
            ->get();
            
        return $this->buildTree($menus->toArray());
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