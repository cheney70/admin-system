<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2026/1/21
 * Time: 15:11
 */

namespace Cheney\Content\Admin\Controllers;

use Illuminate\Http\Request;
use Cheney\Content\Services\AdminService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;
use Cheney\Content\Traits\ResponseTrait;

class AdminController extends Controller{
    use ResponseTrait;
    /**
     * @var AdminService
     */
    private $service;

    public function __construct(AdminService $adminService)
    {
        $this->service = $adminService;
    }

    /**
     * 获取内容列表
     *
     * * @OA\Get(
     *  tags={"内容"},
     *  path="/api/frontend/article/list",
     *  operationId="articleList",
     *  description="内容列表",
     *  @OA\Parameter(name="TypeId",in="query",description="分类id"),
     *  @OA\Parameter(name="page",in="query",description="当前页"),
     *  @OA\Parameter(name="page_num",in="query",description="每页条数",),
     *  @OA\Response(response="100000", description="success"),
     *  @OA\Response(response="200000", description="fail"),
     * )
     * @param Request $request
     * @return string
     */
    public function lists(Request $request)
    {
        $inputs = $request->only('TypeId','page','page_num');
        $validator = Validator::make($inputs, [
            'Option'     => ['required']
        ]);
        if ($validator->fails()) {
            return self::parametersIllegal($validator->messages()->first());
        }
        try{
            $inputs['page_limit'] = isset($inputs['page_num']) ? $inputs['page_num'] : 10;
            $result = $this->service ->getList($inputs);
            if(!$result){
                return self::parametersIllegal("没有数据");
            }
            return self::success($result);
        }catch (\Exception $e){
            return self::error($e->getCode(),$e->getMessage());
        }
    }

    /**
     * 获取文章详情
     *
     * @OA\Get(
     *   tags={"内容"},
     *   path="/api/frontend/article/detail/{id}",
     *   operationId="articleDetail",
     *   description="获取内容详情",
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
            dd($result->toArray());
            if(!$result){
                return self::parametersIllegal("没有数据");
            }
            return self::success($result);
        }catch (\Exception $e){
            return self::error($e->getCode(),$e->getMessage());
        }
    }

}