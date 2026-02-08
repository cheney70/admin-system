<?php

namespace Cheney\AdminSystem\Controllers;

use Illuminate\Http\Request;
use Admin\Services\OperationLogService;
use Admin\Traits\ApiResponseTrait;

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
            return $this->successWithData($log);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->operationLogService->destroy($id);
            return $this->deleted();
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function clear()
    {
        try {
            $days = request('days', 30);
            $this->operationLogService->clear($days);
            return $this->successWithMessage('清理成功');
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }

    public function statistics()
    {
        try {
            $stats = $this->operationLogService->statistics();
            return $this->success($stats);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }
    }
}