<?php

namespace Cheney\AdminSystem\Models;

use Illuminate\Database\Eloquent\Model;

class OperationLog extends Model
{
    protected $fillable = [
        'user_id',
        'username',
        'module',
        'action',
        'method',
        'url',
        'ip',
        'user_agent',
        'params',
        'status',
        'error_message',
    ];

    protected $casts = [
        'status' => 'integer',
        'params' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeSuccess($query)
    {
        return $query->where('status', 1);
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 0);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByModule($query, $module)
    {
        return $query->where('module', $module);
    }

    public function scopeByAction($query, $action)
    {
        return $query->where('action', $action);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }
}