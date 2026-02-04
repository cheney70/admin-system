<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2025/12/25
 * Time: 11:58
 */

return [
    'title'=>"网站名称",
    'admin_cache_key' => 'Content_Admin_Key_id',
    'route' => [
        'prefix'      => env('CONTENT_ROUTE_PREFIX', 'content'),
        'namespace'  => 'Cheney\\Content\\Controllers\\Http',
        'middleware' => 'api',

        'admin_prefix'      => env('CONTENT_ADMIN_ROUTE_PREFIX', 'admin'),
        'admin_namespace'  => 'Cheney\\Content\\Controllers\\Admin',
        'admin_middleware' => 'admin.auth', // 中间件别名
    ],
];