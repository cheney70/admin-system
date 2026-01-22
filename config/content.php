<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2025/12/25
 * Time: 11:58
 */

return [

    'title'=>"网站名称",
    'uploadDir'  => "图片存储路径",
    'admin_cache_key' => 'Content_Admin_Key_id',
    'route' => [
        'prefix'      => env('CONTENT_ROUTE_PREFIX', 'content'),
        'namespace'  => 'Cheney\\Content\\Controllers',
        'middleware' => 'api',

        'admin_prefix'      => env('CONTENT_ADMIN_ROUTE_PREFIX', 'admin/api'),
        'admin_namespace'  => 'Cheney\\Content\\Admin\\Controllers',
        'admin_middleware' => 'admin_api',
    ],
];