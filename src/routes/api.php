<?php

use Illuminate\Support\Facades\Route;
use Cheney\AdminSystem\Controllers\AuthController;
use Cheney\AdminSystem\Controllers\UserController;
use Cheney\AdminSystem\Controllers\RoleController;
use Cheney\AdminSystem\Controllers\PermissionController;
use Cheney\AdminSystem\Controllers\MenuController;
use Cheney\AdminSystem\Controllers\OperationLogController;
Route::prefix('system')->group(function () {
    Route::middleware('cors')->group(function () {
        Route::prefix('auth')->group(function () {
            Route::post('login', [AuthController::class, 'login']);
            Route::post('logout', [AuthController::class, 'logout'])->middleware('jwt');
            Route::post('refresh', [AuthController::class, 'refresh'])->middleware('jwt');
            Route::get('me', [AuthController::class, 'me'])->middleware('jwt');
        });

        Route::middleware('jwt')->group(function () {
            Route::apiResource('users', UserController::class);
            Route::post('users/{id}/roles', [UserController::class, 'assignRoles']);
            Route::post('users/{id}/reset-password', [UserController::class, 'resetPassword']);

            Route::apiResource('roles', RoleController::class);
            Route::post('roles/{id}/permissions', [RoleController::class, 'assignPermissions']);

            Route::apiResource('permissions', PermissionController::class);

            Route::apiResource('menus', MenuController::class);
            Route::get('user-menus', [MenuController::class, 'userMenus']);

            Route::apiResource('operation-logs', OperationLogController::class)->only(['index', 'show', 'destroy']);
            Route::post('operation-logs/clear', [OperationLogController::class, 'clear']);
            Route::get('operation-logs/statistics', [OperationLogController::class, 'statistics']);
        });
    });
});