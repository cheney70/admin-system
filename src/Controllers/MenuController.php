<?php

namespace Cheney\AdminSystem\Controllers;

use Illuminate\Http\Request;
use Admin\Services\MenuService;
use Admin\Traits\ApiResponseTrait;

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
            return $this->successWithData($menus);
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
                'parent_id' => 'nullable|integer',
                'path' => 'nullable|string|max:100',
                'component' => 'nullable|string|max:100',
                'icon' => 'nullable|string|max:50',
                'type' => 'required|integer|in:1,2,3',
                'sort' => 'nullable|integer',
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
            return $this->successWithData($menu);
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
                'parent_id' => 'nullable|integer',
                'path' => 'nullable|string|max:100',
                'component' => 'nullable|string|max:100',
                'icon' => 'nullable|string|max:50',
                'type' => 'required|integer|in:1,2,3',
                'sort' => 'nullable|integer',
                'status' => 'nullable|integer|in:0,1',
                'is_hidden' => 'nullable|boolean',
                'keep_alive' => 'nullable|boolean',
            ]);

            $menu = $this->menuService->update($id, $validated);
            return $this->updated($menu);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->menuService->destroy($id);
            return $this->deleted();
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function userMenus()
    {
        try {
            $menus = $this->menuService->getUserMenus();
            return $this->successWithData($menus);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}