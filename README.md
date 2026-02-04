## 安装
composer require cheney/content

## 发布资源
 php artisan vendor:publish --provider="Cheney\Content\ContentServiceProvider"

## 配置
拷贝项目配置文件到docs 目录

##  装载 Middleware
Kerenl.php 添加一行
protected $middleware = [
       .......
        \Cheney\Content\Middleware\AdminAuth::class,
 ];

 protected $routeMiddleware = [
         ......
         'admin.auth' => \Cheney\Content\Middleware\AdminAuth::class,
 ];

## app.congig 增加
Cheney\Content\ContentServiceProvider::class,

### 运行数据库迁移文件
自行解决