<?php

if (!function_exists('admin_auth')) {
    function admin_auth()
    {
        return app('admin.auth');
    }
}

if (!function_exists('admin_user')) {
    function admin_user()
    {
        return app('admin.user');
    }
}

if (!function_exists('admin_role')) {
    function admin_role()
    {
        return app('admin.role');
    }
}

if (!function_exists('admin_permission')) {
    function admin_permission()
    {
        return app('admin.permission');
    }
}

if (!function_exists('admin_menu')) {
    function admin_menu()
    {
        return app('admin.menu');
    }
}

if (!function_exists('admin_operation_log')) {
    function admin_operation_log()
    {
        return app('admin.operation-log');
    }
}