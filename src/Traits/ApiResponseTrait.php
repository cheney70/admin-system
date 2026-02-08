<?php

namespace Cheney\AdminSystem\Traits;

use Illuminate\Http\JsonResponse;

trait ApiResponseTrait
{
    protected $successCode = 10000;
    protected $errorCode = 20000;

    protected function success($data = null, $message = '操作成功', $code = null)
    {
        return response()->json([
            'code' => $code ?? $this->successCode,
            'message' => $message,
            'data' => $data,
        ]);
    }

    protected function error($message = '操作失败', $code = null, $data = null)
    {
        return response()->json([
            'code' => $code ?? $this->errorCode,
            'message' => $message,
            'data' => $data,
        ]);
    }

    protected function successWithData($data, $message = '操作成功')
    {
        return $this->success($data, $message);
    }

    protected function successWithMessage($message)
    {
        return $this->success(null, $message);
    }

    protected function successPaginated($data, $message = '操作成功')
    {
        return $this->success($data, $message);
    }

    protected function unauthorized($message = '未授权')
    {
        return $this->error($message, 401);
    }

    protected function forbidden($message = '无权访问')
    {
        return $this->error($message, 403);
    }

    protected function notFound($message = '资源不存在')
    {
        return $this->error($message, 404);
    }

    protected function validation($errors, $message = '参数验证失败')
    {
        return $this->error($message, 422, $errors);
    }

    protected function serverError($message = '服务器错误')
    {
        return $this->error($message, 500);
    }

    protected function created($data = null, $message = '创建成功')
    {
        return $this->success($data, $message);
    }

    protected function updated($data = null, $message = '更新成功')
    {
        return $this->success($data, $message);
    }

    protected function deleted($message = '删除成功')
    {
        return $this->success(null, $message);
    }
}