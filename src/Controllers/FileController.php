<?php

namespace Cheney\Content\Controllers;

use Illuminate\Http\Request;
use Cheney\Content\Controllers;
use Cheney\Content\Traits\FileTrait;
use Cheney\Content\Traits\ResponseTrait;

class FileController extends Controller
{
    use FileTrait , ResponseTrait;
    /**
     * upload file
     *
     * @OA\Post(
     *   path="/file/upload",
     *   tags={"通用接口"},
     *   operationId="fileUpload",
     *   description="文件上传",
     *   @OA\RequestBody(
     *       required=true,
     *       description="upload a file",
     *       @OA\MediaType(
     *         mediaType="multipart/form-data",
     *         @OA\Schema(
     *              @OA\Property(property="file",type="file",description="file resource",)
     *          ),
     *       ),
     *   ),
     *   @OA\Response(
     *     response="100000",
     *     description="success",
     *     @OA\JsonContent(
     *          @OA\Property(property="origin_file",type="string",description="origin file name",
     *         ),
     *         @OA\Property(property="file_path",type="string",description="file path",),
     *         @OA\Property(property="file_url",type="string",description="file url",),
     *     ),
     *   ),
     *   @OA\Response(response="200000", description="fail"),
     * )
     * @param Request $request
     * @return string
     */
    public function uploadFile(Request $request){
        if (!$request->hasFile('file')) {
            return self::parametersIllegal('file not input.');
        }
        try {
            $storage = self::fileUpload($request->file('file'));
            return self::success($storage);
        } catch (Exception $e) {
            return self::fail($e->getMessage());
        }
    }
}
