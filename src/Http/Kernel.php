<?php

namespace Cheney\adminSystem\Cheney\AdminSystem\Admin\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        \Cheney\AdminSystem\Admin\Http\Middleware\HandleCors::class,
    ];

    protected $middlewareGroups = [
        'web' => [],
        'api' => [
            'cors',
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    protected $routeMiddleware = [
        'cors' => \Cheney\AdminSystem\Admin\Http\Middleware\HandleCors::class,
        'jwt' => \Cheney\AdminSystem\Admin\Http\Middleware\JwtMiddleware::class,
        'permission' => \Cheney\AdminSystem\Admin\Http\Middleware\PermissionMiddleware::class,
    ];
}