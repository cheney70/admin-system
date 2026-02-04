<?php

namespace Cheney\Content\Controllers\Http;

use Illuminate\Http\Request;
use Cheney\Content\Services\ArticleService;
use Illuminate\Support\Facades\Validator;
use Cheney\Content\Controllers\Controller;
use Cheney\Content\Traits\ResponseTrait;

class ArticleController extends Controller
{
    use ResponseTrait;
    /**
     * @var ArticleService
     */
    private $service;

    public function __construct(ArticleService $articleService)
    {
        $this->service = $articleService;
    }

    /**
     * 获取文章列表
     *
     * @OA\Get(path="/content/article/list",
     *   tags={"前端接口"},
     *   operationId="getHttpArticleList",
     *   description="获取文章列表",
     *   @OA\Parameter(name="type_id",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="integer")
     *   ),
     *  @OA\Parameter(name="page",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *  @OA\Parameter(name="page_num",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Response(response="100000", description="success"),
     *   @OA\Response(response="200000", description="fail"),
     * )
     */
    public function lists(Request $request)
    {
        $inputs = $request->only('type_id','page','page_num');
        $validator = Validator::make($inputs, [
            'page'     => ['required']
        ]);
        if ($validator->fails()) {
            return self::parametersIllegal($validator->messages()->first());
        }
        try{
            $inputs['page_limit'] = isset($inputs['page_num']) ? $inputs['page_num'] : 10;
            $result = $this->service ->getArticleList($inputs);
            if(!$result){
                return self::parametersIllegal("没有数据");
            }
            return self::success($result);
        }catch (\Exception $e){
            return self::error($e->getCode(),$e->getMessage());
        }
    }

    /**
     * 文章推荐列表
     *
     * @OA\Get(
     *   tags={"前端接口"},
     *   path="/content/article/tops",
     *   operationId="articleTops",
     *   description="文章推荐列表",
     *   @OA\Parameter(name="type",in="query",description="内容选项1：话题，2：文章】",@OA\Schema(type="integer")),
     *   @OA\Response(response="100000", description="success"),
     *   @OA\Response(response="200000", description="fail"),
     * )
     * @param Request $request
     * @return string
     */
    public function tops(Request $request)
    {
        $inputs = $request->only('type');
        try{
            $inputs['is_top'] = 1;
            $result = $this->service ->getArticleList($inputs);
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
     * @OA\Get(path="/content/article/detail/{id}",
     *   tags={"前端接口"},
     *   operationId="getArticleById",
     *   description="获取文章详情",
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
