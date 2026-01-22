<?php

namespace Cheney\Content\Middleware;

use Closure;
use Cheney\Content\Services\AdminService;

class AdminAuth
{
    protected $user;
    public function __construct(AdminService $userService)
    {
        $this->user = $userService;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
         if(!$request->header('token')){
            abort('401','ÇëµÇÂ¼£¡');
        }
        $token = $request->header('token');
        //Log::info("token=".json_encode($token)."-------");
        $user = $this->user->getTokenByAdminUser($token);
        if(! $user){
            abort('401','ÇëµÇÂ¼£¡');
        }
        Log::info("user=".json_encode($user)."-------");
        app()->singleton('admin', function() use ($user){
            return $user;
        });
        return $next($request);
    }
}
