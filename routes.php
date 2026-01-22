<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2026/1/1
 * Time: 13:59
 */

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'     => config('content.route.prefix'),
    'namespace'  => config('content.route.namespace') ,
    'middleware' => config('content.route.middleware'),
], function ($router) {
    Route::prefix('/article')->group(function(){
        Route::get('/list',[Cheney\Content\Http\Controllers\ArticleController::class,'lists']);
        Route::get('/tops',[Cheney\Content\Http\Controllers\ArticleController::class,'tops']);
        Route::get('/detail/{id}',[Cheney\Content\Http\Controllers\ArticleController::class,'detail']);
    });

    Route::prefix('/category')->group(function(){
        Route::get('/list',[Cheney\Content\Http\Controllers\CategoryController::class,'lists']);
        Route::get('/detail/{id}',[Cheney\Content\Http\Controllers\CategoryController::class,'detail']);
    });
});

Route::group([
    'prefix'     => config('content.route.admin_prefix'),
    'namespace'  => config('content.route.admin_namespace') ,
], function ($router) {
    $router-> prefix('/login')->group(function(){
        Route::post('/',[Cheney\Content\Admin\Controllers\loginController::class,'login']);
        Route::get('/cachekey',[Cheney\Content\Admin\Controllers\loginController::class,'cacheKey']);

    });

    $router->middleware( config('content.route.admin_middleware'))
        -> prefix('/')->group(function(){
            Route::get('/list',[Cheney\Content\Admin\Controllers\AdminController::class,'lists']);
            Route::get('/detail/{id}',[Cheney\Content\Admin\Controllers\AdminController::class,'detail']);
        });

    Route::prefix('/article')->group(function(){
        Route::post('/create',[Cheney\Content\Admin\Controllers\ArticleController::class,'create']);
        Route::delete('/delete',[Cheney\Content\Admin\Controllers\ArticleController::class,'delete']);
        Route::post('/update',[Cheney\Content\Admin\Controllers\ArticleController::class,'update']);
        Route::get('/list',[Cheney\Content\Admin\Controllers\ArticleController::class,'lists']);
        Route::get('/detail/{id}',[Cheney\Content\Admin\Controllers\ArticleController::class,'detail']);
    });

    Route::prefix('/category')->group(function(){
        Route::post('/create',[Cheney\Content\Admin\Controllers\CategoryController::class,'create']);
        Route::delete('/delete',[Cheney\Content\Admin\Controllers\CategoryController::class,'delete']);
        Route::post('/update',[Cheney\Content\Admin\Controllers\CategoryController::class,'update']);
        Route::get('/list',[Cheney\Content\Admin\Controllers\CategoryController::class,'lists']);
        Route::get('/detail/{id}',[Cheney\Content\Admin\Controllers\CategoryController::class,'detail']);
    });
});