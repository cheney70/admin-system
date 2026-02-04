<?php

namespace Cheney\Content\Controllers\Admin;

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
     * 创建文章
     *
     * @OA\Post(path="/admin/article/create",
     *   tags={"管理后台接口"},
     *   operationId="adminArticleCreate",
     *   description="创建文章",
     *   security={{"Authorization":{}}},
     *   @OA\Parameter(name="type_id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Parameter(name="title",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     *   ),
     *  @OA\Parameter(name="source",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(name="author",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(name="is_top",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="integer")
     *   ),
     *  @OA\Parameter(name="content",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(name="status",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Parameter(name="photo",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(name="keyowrds",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Response(response="100000", description="success"),
     *   @OA\Response(response="200000", description="fail"),
     * )
     */
    public function create(Request $request){
        $inputs = $request->only('type_id','title','source','author','is_top','content','status','photo','keyowrds');
        $validator = Validator::make($inputs, [
            'type_id'     => ['required'],
            'title'       => ['required'],
            'content'     => ['required'],
        ]);
        if ($validator->fails()) {
            return self::parametersIllegal($validator->messages()->first());
        }
        // 图片上传 photo

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
     * 删除文章
     *
     * @OA\Delete(
     *   tags={"管理后台接口"},
     *   path="/admin/article/delete/{id}",
     *   operationId="DeleteArticle",
     *   description="删除文章",
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
            $result = $this->service ->delete(['id'=>$id]);
            if(!$result){
                return self::parametersIllegal("没有数据");
            }
            return self::success($result);
        }catch (\Exception $e){
            return self::error($e->getCode(),$e->getMessage());
        }
    }

    /**
     * 修改文章
     *
     * @OA\Put(path="/admin/article/update/{id}",     *
     *   tags={"管理后台接口"},
     *   operationId="adminArticleUpdate",
     *   description="修改文章",
     *   security={{"Authorization":{}}},
     *  @OA\Parameter(name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Parameter(name="type_id",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Parameter(name="title",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="string")
     *   ),
     *  @OA\Parameter(name="source",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(name="author",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(name="is_top",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="integer")
     *   ),
     *  @OA\Parameter(name="content",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(name="status",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Parameter(name="photo",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Parameter(name="keyowrds",
     *     in="query",
     *     required=false,
     *     @OA\Schema(type="string")
     *   ),
     *   @OA\Response(response="100000", description="success"),
     *   @OA\Response(response="200000", description="fail"),
     * )
     */
    public function update($id , Request $request){
        $data = $request->only('type_id','title','source','author','is_top','content','status','photo','keyowrds');
        try{
            $result = $this->service ->update($id,$data);
            if(!$result){
                return self::parametersIllegal("没有数据");
            }
            return self::success($result);
        }catch (\Exception $e){
            return self::error($e->getCode(),$e->getMessage());
        }
    }

    /**
     * @OA\Get(path="/admin/article/list",
     *   tags={"管理后台接口"},
     *   operationId="getArticleList",
     *   description="获取文章列表",
     *   security={{"Authorization":{}}},
     *   @OA\Parameter(name="type_id",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *  @OA\Parameter(name="page",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *  @OA\Parameter(name="page_num",
     *     in="query",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Response(response="100000", description="success"),
     *   @OA\Response(response="200000", description="fail"),
     * )
     */
    public function lists(Request $request)
    {
        $inputs = $request->only('type_id','page','page_num');
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
     * 获取文章详情
     *
     * @OA\Get(path="/admin/article/detail/{id}",
     *   tags={"管理后台接口"},
     *   operationId="getArticleById",
     *   description="获取文章详情",
     *   security={{"Authorization":{}}},
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
