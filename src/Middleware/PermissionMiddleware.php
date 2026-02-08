<?php

namespace Cheney\AdminSystem\Middleware;

use Closure;
use Illuminate\Http\Request;
use Cheney\AdminSystem\Traits\ApiResponseTrait;

class PermissionMiddleware
{
    use ApiResponseTrait;

    public function handle(Request $request, Closure $next, $permission)
    {
        $user = auth('api')->user();
        
        if (!$user) {
            return $this->unauthorized();
        }
        
        if (!$user->hasPermission($permission)) {
            return $this->forbidden('无权访问');
        }
        
        return $next($request);
    }
}