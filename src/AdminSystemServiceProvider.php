<?php

namespace Cheney\AdminSystem;

use Illuminate\Support\ServiceProvider;

class AdminSystemServiceProvider extends ServiceProvider
{
    protected $namespace = 'Cheney\\AdminSystem\\Controllers';

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/admin.php' => config_path('admin.php'),
        ], 'admin-config');
        
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'admin-migrations');

        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/admin.php',
            'admin'
        );

        $this->app->singleton('admin.auth', function ($app) {
            return new Services\AuthService(
                $app->make(Models\Admin::class)
            );
        });

        $this->app->singleton('admin.user', function ($app) {
            return new Services\UserService(
                $app->make(Models\Admin::class),
                $app->make(Models\Role::class)
            );
        });

        $this->app->singleton('admin.role', function ($app) {
            return new Services\RoleService(
                $app->make(Models\Role::class),
                $app->make(Models\Permission::class)
            );
        });

        $this->app->singleton('admin.permission', function ($app) {
            return new Services\PermissionService(
                $app->make(Models\Permission::class)
            );
        });

        $this->app->singleton('admin.menu', function ($app) {
            return new Services\MenuService(
                $app->make(Models\Menu::class),
                $app->make(Models\Permission::class)
            );
        });

        $this->app->singleton('admin.operation-log', function ($app) {
            return new Services\OperationLogService(
                $app->make(Models\OperationLog::class)
            );
        });
    }
}
