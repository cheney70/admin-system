<?php

namespace Cheney\Content\Http\Controllers;

use Illuminate\Http\Request;
use Cheney\Content\Http\Services\CategoryService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller;

class CategoryController extends Controller
{
    /**
     * @var ArticleTypeService
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
     *   tags={"内容分类"},
     *   path="/api/frontend/article-type/list",
     *   operationId="articleTypeList",
     *   description="获取内容分类列表",
     *   @OA\Parameter(name="Option",in="query",description="内容选项【TOPIC：话题，ARTICLE：文章；非必填】"),
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
        $inputs = $request->only('Option','page','page_num');
        try{
            $inputs['page_limit'] = isset($inputs['page_num']) ? $inputs['page_num'] : 10;
            $result = $this->service ->getCategorys($inputs);

            $message = !$result ? "没有数据": "成功";
            $msgCode = !$result ? "20000": "100000";
            $result = [
                'msg_code'    => $msgCode,
                'message'     => $message,
                'response'    => $result,
                'server_time' => time()
            ];
            return response()->json($result);

        }catch (\Exception $e){
            return self::error($e->getCode(),$e->getMessage());
        }
    }

    /**
     * 获取内容分类详情
     *
     * @OA\Get(
     *   tags={"内容分类"},
     *   path="/api/frontend/article-type/detail/{id}",
     *   operationId="articleTypeDetail",
     *   description="获取内容分类详情",
     *   @OA\Parameter(name="id",in="path",description="内容id",@OA\Schema(type="int")),
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

            $message = !$result ? "没有数据": "成功";
            $msgCode = !$result ? "20000": "100000";
            $result = [
                'msg_code'    => $msgCode,
                'message'     => $message,
                'response'    => $result,
                'server_time' => time()
            ];
            return response()->json($result);
        }catch (\Exception $e){
            return self::error($e->getCode(),$e->getMessage());
        }
    }
}
