<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2026/1/1
 * Time: 13:59
 */

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix'     => config('route.route.prefix'),
    'namespace'  => config('route.route.namespace') ,
    'middleware' => config('route.route.middleware'),
], function ($router) {
    Route::prefix('/article')->group(function(){
        Route::get('/list',[Cheney\Content\Http\Controllers\ArticleController::class,'lists']);
        Route::get('/tops',[Cheney\Content\Http\Controllers\ArticleController::class,'tops']);
        Route::get('/detail/{id}',[Cheney\Content\Http\Controllers\ArticleController::class,'detail']);
    });

    Route::prefix('/article-type')->group(function(){
        Route::get('/list',[Cheney\Content\Http\Controllers\ArticleTypeController::class,'lists']);
        Route::get('/detail/{id}',[Cheney\Content\Http\Controllers\ArticleTypeController::class,'detail']);
    });
});

