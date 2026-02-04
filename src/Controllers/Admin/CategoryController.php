<?php

namespace Cheney\Content\Controllers\Admin;

use Illuminate\Http\Request;
use Cheney\Content\Services\CategoryService;
use Illuminate\Support\Facades\Validator;
use Cheney\Content\Controllers\Controller;
use Cheney\Content\Traits\ResponseTrait;
use Cheney\Content\Traits\FileTrait;

class CategoryController extends Controller
{
    use ResponseTrait, FileTrait;
    /**
     * @var CategoryService
     */
    private $service;

    public function __construct(CategoryService $categoryService)
    {
        $this->service = $categoryService;
    }

    /**
     * 创建分类
     *
     * @OA\Post(path="/admin/category/create",     *
     *   tags={"管理后台接口"},
     *   operationId="adminCategoryCreate",
     *   description="创建分类",
     *   security={{"Authorization":{}}},
     *   @OA\Parameter(name="name",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(name="type",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     *   ),
     *  @OA\Parameter(name="level",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Parameter(name="parent_id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Parameter(name="sign",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     *   ),
     *  @OA\Parameter(name="icon",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(name="remarks",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(name="status",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Response(response="100000", description="success"),
     *   @OA\Response(response="200000", description="fail"),
     * )
     */
    public function create(Request $request){
        $inputs = $request->only('name','type','level','parent_id','sign','icon','remarks','status');
        $validator = Validator::make($inputs, [
            'name'      => ['required'],
            'level'     => ['required'],
            'parent_id' => ['required'],
            'sign'      => ['required']
        ]);
        if ($validator->fails()) {
            return self::parametersIllegal($validator->messages()->first());
        }
        try{
            $result = $this->service ->create($inputs);
            if(!$result){
                return self::parametersIllegal("没有数据");
            }
            return self::success($result);
        }catch (\Exception $e){
            return self::error($e->getCode(),$e->getMessage());
        }
    }

    /**
     * 删除分类
     *
     * @OA\Delete(
     *   tags={"管理后台接口"},
     *   path="/admin/category/delete/{id}",
     *   operationId="DeleteCategory",
     *   description="删除分类",
     *   security={{"Authorization":{}}},
     *   @OA\Parameter(name="id",in="path",description="内容id",@OA\Schema(type="integer")),
     *   @OA\Response(response="100000", description="success"),
     *   @OA\Response(response="200000", description="fail"),
     * )
     * @param Request $request
     * @return string
     */
    public function delete(int $id){
        try{
            $result = $this->service ->delete(['id' =>$id]);
            if(!$result){
                return self::parametersIllegal("没有数据");
            }
            return self::success($result);
        }catch (\Exception $e){
            return self::error($e->getCode(),$e->getMessage());
        }
    }

    /**
     * 修改分类
     *
     * @OA\Put(path="/admin/category/update/{id}",     *
     *   tags={"管理后台接口"},
     *   operationId="adminCategoryUpdate",
     *   description="修改分类",
     *   security={{"Authorization":{}}},
     *   @OA\Parameter(name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Parameter(name="name",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(name="type",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="string")
     *   ),
     *  @OA\Parameter(name="level",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Parameter(name="parent_id",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Parameter(name="sign",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="string")
     *   ),
     *  @OA\Parameter(name="icon",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(name="remarks",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(name="status",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Response(response="100000", description="success"),
     *   @OA\Response(response="200000", description="fail"),
     * )
     */
    public function update($id , Request $request){
        $inputs = $request->only('name','type','level','parent_id','sign','icon','remarks','status');
        try{
            $result = $this->service ->update($id , $inputs);
            if(!$result){
                return self::parametersIllegal("没有数据");
            }
            return self::success($result);
        }catch (\Exception $e){
            return self::error($e->getCode(),$e->getMessage());
        }
    }

    /**
     * 获取内容分类列表
     *
     * @OA\Get(
     *   tags={"管理后台接口"},
     *   path="/admin/category/list",
     *   operationId="adminCategoryList",
     *   description="获取内容分类列表",
     *   security={{"Authorization":{}}},
     *   @OA\Parameter(name="type",in="query",description="类型，1产品分类，2文章分类"),
     *   @OA\Parameter(name="page",in="query",description="当前页"),
     *   @OA\Parameter(name="page_num",in="query",description="每页条数"),
     *   @OA\Response(response="100000", description="success"),
     *   @OA\Response(response="200000", description="fail"),
     * )
     * @param Request $request
     * @return string
     */
    public function lists(Request $request)
    {
        $inputs = $request->only('type','page','page_num');
        try{
            $inputs['page_limit'] = isset($inputs['page_num']) ? $inputs['page_num'] : 10;
            $result = $this->service ->getCategorys($inputs);
            if(!$result){
                return self::parametersIllegal("没有数据");
            }
            return self::success($result);
        }catch (\Exception $e){
            return self::error($e->getCode(),$e->getMessage());
        }
    }

    /**
     * 获取分类详情
     *
     * @OA\Get(
     *   tags={"管理后台接口"},
     *   path="/admin/category/detail/{id}",
     *   operationId="adminCategoryDetail",
     *   description="获取分类详情",
     *   security={{"Authorization":{}}},
     *   @OA\Parameter(name="id",in="path",description="内容id",@OA\Schema(type="integer")),
     *   @OA\Response(response="100000", description="success"),
     *   @OA\Response(response="200000", description="fail"),
     * )
     * @param Request $request
     * @return string
     */
    public function detail($id)
    {
        try{
            $result = $this->service ->getById($id);
            if(!$result){
                return self::parametersIllegal("没有数据");
            }
            return self::success($result);
        }catch (\Exception $e){
            return self::error($e->getCode(),$e->getMessage());
        }
    }
}
