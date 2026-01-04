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

    'route' => [
        'prefix'      => env('CONTENT_ROUTE_PREFIX', 'content'),
        'namespace'  => 'Cheney\\Content\\Controllers',
        'middleware' => 'api',
    ],
];