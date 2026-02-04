<?php

namespace Cheney\Content\Controllers\Http;

use Illuminate\Http\Request;
use Cheney\Content\Services\CategoryService;
use Illuminate\Support\Facades\Validator;
use Cheney\Content\Controllers\Controller;
use Cheney\Content\Traits\ResponseTrait;

class CategoryController extends Controller
{
    use ResponseTrait;
    /**
     * @var CategoryService
     */
    private $service;

    public function __construct(CategoryService $categoryService)
    {
        $this->service = $categoryService;
    }

    /**
     * 获取内容分类列表
     *
     * @OA\Get(
     *   tags={"前端接口"},
     *   path="/content/category/list",
     *   operationId="getHttpCategoryList",
     *   description="获取分类列表",
     *   @OA\Parameter(name="type",in="query",description="内容选项"),
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
        $validator = Validator::make($inputs, [
            'type'      => ['required'],
            'page'     => ['required'],
            'page_num' => ['required']
        ]);
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
     * @OA\Get(path="/content/category/detail/{id}",
     *   tags={"前端接口"},
     *   operationId="getHttpCategoryById",
     *   description="获取分类详情",
     *   @OA\Parameter(name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Response(response="100000", description="success"),
     *   @OA\Response(response="200000", description="fail"),
     * )
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
