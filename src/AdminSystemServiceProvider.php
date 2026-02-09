<?php

namespace Cheney\AdminSystem;

use Illuminate\Support\ServiceProvider;    
use Cheney\AdminSystem\Services\AuthService;
use Cheney\AdminSystem\Services\UserService;
use Cheney\AdminSystem\Services\RoleService;
use Cheney\AdminSystem\Services\PermissionService;
use Cheney\AdminSystem\Services\MenuService;
use Cheney\AdminSystem\Services\OperationLogService;

class AdminSystemServiceProvider extends ServiceProvider
{
    protected $namespace = 'Cheney\\AdminSystem\\Controllers';

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/admin.php' => config_path('admin.php'),
        ], 'admin-config');

        $this->publishes([
            __DIR__ . '/../config/auth.php' => config_path('auth.php'),
        ], 'admin-auth');

        $this->publishes([
            __DIR__ . '/../config/jwt.php' => config_path('jwt.php'),
        ], 'admin-jwt');

        $this->publishes([
            __DIR__ . '/../config/cors.php' => config_path('cors.php'),
        ], 'admin-cors');

        $this->publishes([
            __DIR__ . '/../config/l5-swagger.php' => config_path('l5-swagger.php'),
        ], 'admin-swagger');

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'admin-migrations');

        $this->publishes([
            __DIR__ . '/../database/factories' => database_path('factories'),
        ], 'admin-factories');

        $this->publishes([
            __DIR__ . '/../database/seeders' => database_path('seeders'),
        ], 'admin-seeders');

        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'admin');

        $this->registerMiddleware();
    }

    public function register()
    {
        $this->registerServiceProviders();

        $this->mergeConfigFrom(
            __DIR__ . '/../config/admin.php',
            'admin'
        );

        $this->mergeConfigFrom(
            __DIR__ . '/../config/auth.php',
            'auth'
        );

        $this->mergeConfigFrom(
            __DIR__ . '/../config/jwt.php',
            'jwt'
        );

        $this->mergeConfigFrom(
            __DIR__ . '/../config/cors.php',
            'cors'
        );

        $this->mergeConfigFrom(
            __DIR__ . '/../config/l5-swagger.php',
            'l5-swagger'
        );

        $this->registerServices();
    }

    protected function registerServiceProviders()
    {
        $this->app->register(\Tymon\JWTAuth\Providers\LaravelServiceProvider::class);
        $this->app->register(\L5Swagger\L5SwaggerServiceProvider::class);
    }

    protected function registerServices()
    {
        $this->app->singleton('admin.auth', function ($app) {
            return new AuthService(
                $app->make(Cheney\AdminSystem\Models\Admin::class)
            );
        });

        $this->app->singleton('admin.user', function ($app) {
            return new UserService(
                $app->make(Cheney\AdminSystem\Models\Admin::class),
                $app->make(Cheney\AdminSystem\Models\Role::class)
            );
        });

        $this->app->singleton('admin.role', function ($app) {
            return new RoleService(
                $app->make(Cheney\AdminSystem\Models\Role::class),
                $app->make(Cheney\AdminSystem\Models\Permission::class)
            );
        });

        $this->app->singleton('admin.permission', function ($app) {
            return new PermissionService(
                $app->make(Cheney\AdminSystem\Models\Permission::class) 
            );
        });

        $this->app->singleton('admin.menu', function ($app) {
            return new MenuService(
                $app->make(Cheney\AdminSystem\Models\Menu::class),
                $app->make(Cheney\AdminSystem\Models\Permission::class)
            );
        });

        $this->app->singleton('admin.operation-log', function ($app) {
            return new OperationLogService(
                $app->make(Cheney\AdminSystem\Models\OperationLog::class)
            );
        });
    }

    protected function registerMiddleware()
    {
        $router = $this->app['router'];

        $router->aliasMiddleware('jwt', \Cheney\AdminSystem\Middleware\JwtMiddleware::class);
        $router->aliasMiddleware('permission', \Cheney\AdminSystem\Middleware\PermissionMiddleware::class);
        $router->aliasMiddleware('operation.log', \Cheney\AdminSystem\Middleware\OperationLogMiddleware::class);
        $router->aliasMiddleware('cors', \Cheney\AdminSystem\Middleware\HandleCors::class);
    }
}
