<?php

namespace Cheney\AdminSystem\Services;

use Cheney\AdminSystem\Models\OperationLog;
use Illuminate\Pagination\LengthAwarePaginator;

class OperationLogService
{
    protected $operationLogModel;

    public function __construct(OperationLog $operationLogModel)
    {
        $this->operationLogModel = $operationLogModel;
    }

    public function index(array $params = []): LengthAwarePaginator
    {
        $query = $this->operationLogModel->query();

        if (isset($params['username'])) {
            $query->where('username', 'like', '%' . $params['username'] . '%');
        }

        if (isset($params['module'])) {
            $query->where('module', 'like', '%' . $params['module'] . '%');
        }

        if (isset($params['action'])) {
            $query->where('action', 'like', '%' . $params['action'] . '%');
        }

        if (isset($params['status'])) {
            $query->where('status', $params['status']);
        }

        if (isset($params['start_date']) && isset($params['end_date'])) {
            $query->whereBetween('created_at', [$params['start_date'], $params['end_date']]);
        }

        $perPage = $params['per_page'] ?? 15;
        return $query->with('user')->orderBy('created_at', 'desc')->paginate($perPage);
    }

    public function show(int $id)
    {
        return $this->operationLogModel->with('user')->findOrFail($id);
    }

    public function destroy(int $id): bool
    {
        $log = $this->operationLogModel->findOrFail($id);
        return $log->delete();
    }

    public function clear(int $days = 30): int
    {
        return $this->operationLogModel->where('created_at', '<', now()->subDays($days))->delete();
    }

    public function statistics(): array
    {
        $total = $this->operationLogModel->count();
        $success = $this->operationLogModel->success()->count();
        $failed = $this->operationLogModel->failed()->count();
        
        $moduleStats = $this->operationLogModel->selectRaw('module, count(*) as count')
            ->groupBy('module')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
            
        $actionStats = $this->operationLogModel->selectRaw('action, count(*) as count')
            ->groupBy('action')
            ->orderBy('count', 'desc')
            ->limit(10)
            ->get();
        
        return [
            'total' => $total,
            'success' => $success,
            'failed' => $failed,
            'module_stats' => $moduleStats,
            'action_stats' => $actionStats,
        ];
    }
}