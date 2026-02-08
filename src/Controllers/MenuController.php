<?php

namespace Cheney\AdminSystem\Controllers;

use Illuminate\Http\Request;
use Cheney\AdminSystem\Services\MenuService;
use Cheney\AdminSystem\Traits\ApiResponseTrait;

class MenuController extends Controller
{
    use ApiResponseTrait;

    protected $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    public function index(Request $request)
    {
        try {
            $menus = $this->menuService->index($request->all());
            return $this->success($menus);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:50',
                'name' => 'required|string|max:50|unique:menus',
                'parent_id' => 'nullable|integer|exists:menus,id',
                'path' => 'nullable|string|max:255',
                'component' => 'nullable|string|max:255',
                'icon' => 'nullable|string|max:50',
                'type' => 'required|integer|in:1,2,3',
                'sort' => 'nullable|integer|min:0',
                'status' => 'nullable|integer|in:0,1',
                'is_hidden' => 'nullable|boolean',
                'keep_alive' => 'nullable|boolean',
            ]);

            $menu = $this->menuService->store($validated);
            return $this->created($menu);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $menu = $this->menuService->show($id);
            return $this->success($menu);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:50',
                'name' => 'required|string|max:50|unique:menus,name,' . $id,
                'parent_id' => 'nullable|integer|exists:menus,id',
                'path' => 'nullable|string|max:255',
                'component' => 'nullable|string|max:255',
                'icon' => 'nullable|string|max:50',
                'type' => 'required|integer|in:1,2,3',
                'sort' => 'nullable|integer|min:0',
                'status' => 'nullable|integer|in:0,1',
                'is_hidden' => 'nullable|boolean',
                'keep_alive' => 'nullable|boolean',
            ]);

            $menu = $this->menuService->update($id, $validated);
            return $this->success($menu, '更新成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->menuService->destroy($id);
            return $this->success(null, '删除成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function tree()
    {
        try {
            $tree = $this->menuService->tree();
            return $this->success($tree);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function userMenus()
    {
        try {
            $menus = $this->menuService->userMenus();
            return $this->success($menus);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
