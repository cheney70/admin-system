<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2026/1/1
 * Time: 13:59
 */

use Illuminate\Support\Facades\Route;
/**
 * import swagger
 */
if (env('APP_ENV') != 'production') {
    Route::get('/admin/swagger', function () {
        return response()->json(\OpenApi\scan(__DIR__.DIRECTORY_SEPARATOR."src". DIRECTORY_SEPARATOR ."Controllers"));
    });
}

Route::prefix('/file')->group(function () {
    Route::post('/upload', 'Cheney\Content\Controllers\FileController@uploadFile');
});

$middleware = explode(',' ,config('content.route.middleware'));
Route::group([
    'prefix'     => config('content.route.prefix'),
    'namespace'  => config('content.route.namespace'),
    'middleware' => $middleware,
], function ($router) {
    Route::prefix('/article')->group(function(){
        Route::get('/list',[Cheney\Content\Controllers\Http\ArticleController::class,'lists']);
        Route::get('/tops',[Cheney\Content\Controllers\Http\ArticleController::class,'tops']);
        Route::get('/detail/{id}',[Cheney\Content\Controllers\Http\ArticleController::class,'detail']);
    });

    Route::prefix('/category')->group(function(){
        Route::get('/list',[Cheney\Content\Controllers\Http\CategoryController::class,'lists']);
        Route::get('/detail/{id}',[Cheney\Content\Controllers\Http\CategoryController::class,'detail']);
    });
});

Route::group([
    'prefix'     => config('content.route.admin_prefix'),
    'namespace'  => config('content.route.admin_namespace') ,

], function ($router) {
    $router-> prefix('/login')->group(function(){
        Route::post('/',[Cheney\Content\Controllers\Admin\loginController::class,'login']);
        Route::get('/cachekey',[Cheney\Content\Controllers\Admin\loginController::class,'cacheKey']);
    });

    $adminMiddleware = explode(',' , config('content.route.admin_middleware'));
    Route::group([
        'middleware' => $adminMiddleware,
    ], function ($router) {
        Route::get('/logout',[Cheney\Content\Controllers\Admin\loginController::class,'logout']);
        $router->prefix('/')->group(function(){
             Route::get('/list',[Cheney\Content\Controllers\Admin\AdminController::class,'lists']);
             Route::get('/info/{id?}',[Cheney\Content\Controllers\Admin\AdminController::class,'info']);
         });
        Route::get('/user/nav',[Cheney\Content\Controllers\Admin\AdminController::class,'nav']);

        Route::prefix('/article')->group(function(){
            Route::post('/create',[Cheney\Content\Controllers\Admin\ArticleController::class,'create']);
            Route::delete('/delete/{id}',[Cheney\Content\Controllers\Admin\ArticleController::class,'delete']);
            Route::put('/update/{id}',[Cheney\Content\Controllers\Admin\ArticleController::class,'update']);
            Route::get('/list',[Cheney\Content\Controllers\Admin\ArticleController::class,'lists']);
            Route::get('/detail/{id}',[Cheney\Content\Controllers\Admin\ArticleController::class,'detail']);
        });

        Route::prefix('/category')->group(function(){
            Route::post('/create',[Cheney\Content\Controllers\Admin\CategoryController::class,'create']);
            Route::delete('/delete/{id}',[Cheney\Content\Controllers\Admin\CategoryController::class,'delete']);
            Route::put('/update/{id}',[Cheney\Content\Controllers\Admin\CategoryController::class,'update']);
            Route::get('/list',[Cheney\Content\Controllers\Admin\CategoryController::class,'lists']);
            Route::get('/detail/{id}',[Cheney\Content\Controllers\Admin\CategoryController::class,'detail']);
        });
    });
});