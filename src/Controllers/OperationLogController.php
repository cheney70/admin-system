<?php

namespace Cheney\AdminSystem\Controllers;

use Illuminate\Http\Request;
use Cheney\AdminSystem\Services\OperationLogService;
use Cheney\AdminSystem\Traits\ApiResponseTrait;

class OperationLogController extends Controller
{
    use ApiResponseTrait;

    protected $operationLogService;

    public function __construct(OperationLogService $operationLogService)
    {
        $this->operationLogService = $operationLogService;
    }

    public function index(Request $request)
    {
        try {
            $logs = $this->operationLogService->index($request->all());
            return $this->successPaginated($logs);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $log = $this->operationLogService->show($id);
            return $this->success($log);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->operationLogService->destroy($id);
            return $this->success(null, '删除成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function statistics()
    {
        try {
            $statistics = $this->operationLogService->statistics();
            return $this->success($statistics);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function export(Request $request)
    {
        try {
            $file = $this->operationLogService->export($request->all());
            return response()->download($file);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}
