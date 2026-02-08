# Ant Admin System Composer Package

Laravel后台管理系统Composer包，提供完整的RBAC权限管理功能。

## 安装

```bash
composer require antadmin/admin-system
```

## 配置

### 1. 发布配置文件

```bash
php artisan vendor:publish --provider="Cheney\AdminSystem\AdminSystemServiceProvider" --tag="admin-config"
```

### 2. 发布并运行数据库迁移

```bash
php artisan vendor:publish --provider="Cheney\AdminSystem\AdminSystemServiceProvider" --tag="admin-migrations"
php artisan migrate
```

### 3. 生成JWT密钥

```bash
php artisan jwt:secret
```

## 使用

### Facade

包提供了以下Facade，可以直接在代码中使用：

#### AdminAuth - 认证服务

```php
use Cheney\AdminSystem\Facades\AdminAuth;

// 用户登录
$result = AdminAuth::login('username', 'password');

// 获取当前用户
$user = AdminAuth::me();

// 用户退出
AdminAuth::logout();

// 刷新Token
$token = AdminAuth::refresh();
```

#### AdminUser - 用户服务

```php
use Cheney\AdminSystem\Facades\AdminUser;

// 获取用户列表
$users = AdminUser::index(['status' => 1]);

// 创建用户
$user = AdminUser::store([
    'username' => 'test',
    'password' => 'password',
    'name' => '测试用户',
]);

// 更新用户
$user = AdminUser::update($userId, [
    'name' => '更新后的名称',
]);

// 删除用户
AdminUser::destroy($userId);

// 分配角色
AdminUser::assignRoles($userId, [1, 2, 3]);

// 重置密码
AdminUser::resetPassword($userId, 'newpassword');
```

#### AdminRole - 角色服务

```php
use Cheney\AdminSystem\Facades\AdminRole;

// 获取角色列表
$roles = AdminRole::index(['status' => 1]);

// 创建角色
$role = AdminRole::store([
    'name' => '管理员',
    'code' => 'admin',
]);

// 更新角色
$role = AdminRole::update($roleId, [
    'description' => '更新描述',
]);

// 删除角色
AdminRole::destroy($roleId);

// 分配权限
AdminRole::assignPermissions($roleId, [1, 2, 3]);
```

#### AdminPermission - 权限服务

```php
use Cheney\AdminSystem\Facades\AdminPermission;

// 获取权限列表
$permissions = AdminPermission::index(['type' => 1]);

// 创建权限
$permission = AdminPermission::store([
    'name' => '查看用户',
    'code' => 'user:list',
    'type' => 1,
]);

// 更新权限
$permission = AdminPermission::update($permissionId, [
    'description' => '更新描述',
]);

// 删除权限
AdminPermission::destroy($permissionId);
```

#### AdminMenu - 菜单服务

```php
use Cheney\AdminSystem\Facades\AdminMenu;

// 获取菜单列表（树形结构）
$menus = AdminMenu::index();

// 创建菜单
$menu = AdminMenu::store([
    'title' => '系统管理',
    'name' => 'System',
    'parent_id' => 0,
]);

// 更新菜单
$menu = AdminMenu::update($menuId, [
    'title' => '更新标题',
]);

// 删除菜单
AdminMenu::destroy($menuId);

// 获取当前用户菜单
$userMenus = AdminMenu::getUserMenus();
```

#### AdminOperationLog - 操作日志服务

```php
use Cheney\AdminSystem\Facades\AdminOperationLog;

// 获取操作日志列表
$logs = AdminOperationLog::index(['status' => 1]);

// 获取日志详情
$log = AdminOperationLog::show($logId);

// 删除日志
AdminOperationLog::destroy($logId);

// 清理旧日志
AdminOperationLog::clear(30); // 清理30天前的日志

// 获取统计数据
$stats = AdminOperationLog::statistics();
```

### Helper函数

包提供了全局辅助函数，可以在任何地方使用：

```php
// 获取认证服务实例
$authService = admin_auth();

// 获取用户服务实例
$userService = admin_user();

// 获取角色服务实例
$roleService = admin_role();

// 获取权限服务实例
$permissionService = admin_permission();

// 获取菜单服务实例
$menuService = admin_menu();

// 获取操作日志服务实例
$operationLogService = admin_operation_log();
```

### 模型

包中包含以下模型：

- `Cheney\AdminSystem\Models\User` - 用户模型
- `Cheney\AdminSystem\Models\Role` - 角色模型
- `Cheney\AdminSystem\Models\Permission` - 权限模型
- `Cheney\AdminSystem\Models\Menu` - 菜单模型
- `Cheney\AdminSystem\Models\OperationLog` - 操作日志模型

### 控制器

包中包含以下控制器：

- `Cheney\AdminSystem\Http\Controllers\AuthController` - 认证控制器
- `Cheney\AdminSystem\Http\Controllers\UserController` - 用户管理控制器
- `Cheney\AdminSystem\Http\Controllers\RoleController` - 角色管理控制器
- `Cheney\AdminSystem\Http\Controllers\PermissionController` - 权限管理控制器
- `Cheney\AdminSystem\Http\Controllers\MenuController` - 菜单管理控制器
- `Cheney\AdminSystem\Http\Controllers\OperationLogController` - 操作日志控制器

### 中间件

包中包含以下中间件：

- `Cheney\AdminSystem\Http\Middleware\JwtMiddleware` - JWT认证中间件
- `Cheney\AdminSystem\Http\Middleware\PermissionMiddleware` - 权限验证中间件
- `Cheney\AdminSystem\Http\Middleware\OperationLogMiddleware` - 操作日志中间件

### 路由

包会自动注册API路由，默认前缀为 `/api`。

主要路由：

- `POST /api/login` - 用户登录
- `POST /api/logout` - 用户退出
- `GET /api/me` - 获取当前用户信息
- `POST /api/refresh` - 刷新Token
- `GET /api/users` - 用户列表
- `POST /api/users` - 创建用户
- `PUT /api/users/{id}` - 更新用户
- `DELETE /api/users/{id}` - 删除用户
- `GET /api/roles` - 角色列表
- `POST /api/roles` - 创建角色
- `PUT /api/roles/{id}` - 更新角色
- `DELETE /api/roles/{id}` - 删除角色
- `GET /api/permissions` - 权限列表
- `POST /api/permissions` - 创建权限
- `PUT /api/permissions/{id}` - 更新权限
- `DELETE /api/permissions/{id}` - 删除权限
- `GET /api/menus` - 菜单列表
- `POST /api/menus` - 创建菜单
- `PUT /api/menus/{id}` - 更新菜单
- `DELETE /api/menus/{id}` - 删除菜单
- `GET /api/operation-logs` - 操作日志列表
- `GET /api/operation-logs/statistics` - 操作日志统计

## API文档

安装后访问 `/api/documentation` 查看Swagger API文档。

## 配置选项

在 `config/admin.php` 中可以配置：

- `prefix` - API路由前缀（默认：api）
- `middleware` - API中间件（默认：['api']）
- `jwt_secret` - JWT密钥
- `jwt_ttl` - JWT过期时间（分钟）

## 数据库表

包包含以下数据库表：

- `users` - 用户表
- `roles` - 角色表
- `permissions` - 权限表
- `menus` - 菜单表
- `role_user` - 用户角色关联表
- `permission_role` - 角色权限关联表
- `operation_logs` - 操作日志表

## 许可证

MIT License