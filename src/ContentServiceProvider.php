<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2025/12/31
 * Time: 21:04
 */

namespace Cheney\Content;

use Illuminate\Support\ServiceProvider;


class ContentServiceProvider extends ServiceProvider{
    /**
     * 在容器中注册绑定。
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton("Content", function ($app) {
            $this->mergeConfigFrom(
                __DIR__.'/../config/content.php', 'content'
            );
            return new Connection($app["config"]->get("content"));
        });

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations'); // 发现迁移
        // 如果需要发布迁移文件到主应用，则需要定义发布组
        $this->publishes([
            __DIR__.'/../database/migrations' => database_path('migrations'),
        ], 'cheney-package-migrations');
    }

    /**
     * 引导任何应用程序服务。
     *
     * @return void
     */
    public function boot()
    {
        //加载视图
        view()->composer('view', function () {
            //
        });
        //加载路由文件
        $this->loadRoutesFrom(__DIR__.'/../routes.php');
        /*
        $this->publishes([
            __DIR__.'/../database/migrations/' => database_path('migrations')
        ], 'migrations');
        */

    }

}