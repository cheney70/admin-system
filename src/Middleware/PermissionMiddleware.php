<?php

namespace Cheney\AdminSystem\Middleware;

use Closure;
use Illuminate\Http\Request;
use Cheney\AdminSystem\Traits\ApiResponseTrait;
use Tymon\JWTAuth\Facades\JWTAuth;

class PermissionMiddleware
{
    use ApiResponseTrait;

    public function handle(Request $request, Closure $next, $permission)
    {
        $admin = JWTAuth::parseToken()->authenticate();
        
        if (!$admin) {
            return $this->unauthorized();
        }
        
        if (!$admin->hasPermission($permission)) {
            return $this->forbidden('无权访问');
        }
        
        return $next($request);
    }
}
