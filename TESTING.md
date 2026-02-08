# 后端单元测试说明

## 测试概述

本项目包含完整的单元测试和功能测试，覆盖了所有核心功能模块。

## 测试结构

```
tests/
├── Feature/              # 功能测试
│   ├── AuthTest.php      # 认证测试
│   ├── UserTest.php      # 用户管理测试
│   ├── RoleTest.php      # 角色管理测试
│   ├── PermissionTest.php # 权限管理测试
│   ├── MenuTest.php      # 菜单管理测试
│   └── OperationLogTest.php # 操作日志测试
└── Unit/                # 单元测试（待添加）
```

## 测试覆盖

### 1. AuthTest - 认证测试
- ✅ 用户登录（有效凭证）
- ✅ 用户登录（无效凭证）
- ✅ 用户登录（禁用账号）
- ✅ 用户退出
- ✅ 获取用户信息
- ✅ 未授权访问保护路由
- ✅ 刷新Token

### 2. UserTest - 用户管理测试
- ✅ 获取用户列表
- ✅ 创建用户
- ✅ 更新用户
- ✅ 删除用户
- ✅ 不能删除自己
- ✅ 分配角色给用户
- ✅ 重置用户密码
- ✅ 未授权访问
- ✅ 按用户名过滤
- ✅ 按状态过滤

### 3. RoleTest - 角色管理测试
- ✅ 获取角色列表
- ✅ 创建角色
- ✅ 更新角色
- ✅ 删除角色
- ✅ 不能删除有用户的角色
- ✅ 分配权限给角色
- ✅ 按名称过滤
- ✅ 按状态过滤
- ✅ 角色编码唯一性验证

### 4. PermissionTest - 权限管理测试
- ✅ 获取权限列表
- ✅ 创建权限
- ✅ 更新权限
- ✅ 删除权限
- ✅ 不能删除已分配的权限
- ✅ 按名称过滤
- ✅ 按类型过滤
- ✅ 权限编码唯一性验证
- ✅ 权限类型有效性验证

### 5. MenuTest - 菜单管理测试
- ✅ 获取菜单列表
- ✅ 创建菜单
- ✅ 创建子菜单
- ✅ 更新菜单
- ✅ 删除菜单
- ✅ 不能删除有子菜单的菜单
- ✅ 按标题过滤
- ✅ 按状态过滤
- ✅ 菜单名称唯一性验证
- ✅ 菜单类型有效性验证
- ✅ 获取用户菜单

### 6. OperationLogTest - 操作日志测试
- ✅ 获取日志列表
- ✅ 获取日志统计
- ✅ 删除日志
- ✅ 清理旧日志
- ✅ 按用户名过滤
- ✅ 按模块过滤
- ✅ 按操作类型过滤
- ✅ 按状态过滤
- ✅ 按日期范围过滤
- ✅ 未授权访问

## 运行测试

### 运行所有测试
```bash
cd backend
php artisan test
```

### 运行特定测试文件
```bash
# 只运行认证测试
php artisan test --testsuite=Feature --filter=AuthTest

# 只运行用户测试
php artisan test --testsuite=Feature --filter=UserTest

# 只运行角色测试
php artisan test --testsuite=Feature --filter=RoleTest

# 只运行权限测试
php artisan test --testsuite=Feature --filter=PermissionTest

# 只运行菜单测试
php artisan test --testsuite=Feature --filter=MenuTest

# 只运行日志测试
php artisan test --testsuite=Feature --filter=OperationLogTest
```

### 运行特定测试方法
```bash
# 只测试登录功能
php artisan test --filter=test_user_can_login_with_valid_credentials

# 只测试创建用户功能
php artisan test --filter=test_authenticated_user_can_create_user
```

### 生成测试覆盖率报告
```bash
php artisan test --coverage
```

### 详细输出模式
```bash
php artisan test --verbose
```

### 停止在第一个失败
```bash
php artisan test --stop-on-failure
```

## 测试数据工厂

项目包含以下数据工厂，用于生成测试数据：

- **UserFactory** - 生成测试用户
- **RoleFactory** - 生成测试角色
- **PermissionFactory** - 生成测试权限
- **MenuFactory** - 生成测试菜单
- **OperationLogFactory** - 生成测试操作日志

### 使用数据工厂

```php
// 创建单个用户
$user = User::factory()->create();

// 创建多个用户
$users = User::factory()->count(10)->create();

// 创建带特定属性的用户
$user = User::factory()->create([
    'username' => 'testuser',
    'status' => 1,
]);

// 创建关联数据
$user = User::factory()
    ->has(Role::factory()->count(2))
    ->create();
```

## 测试配置

测试使用内存数据库（SQLite），确保测试之间相互独立。

配置文件：`phpunit.xml`

```xml
<php>
    <env name="APP_ENV" value="testing"/>
    <env name="DB_CONNECTION" value="sqlite"/>
    <env name="DB_DATABASE" value=":memory:"/>
</php>
```

## 编写新测试

### 1. 创建测试文件

在 `tests/Feature/` 目录下创建新的测试文件：

```php
<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class YourFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_something_works()
    {
        $response = $this->get('/api/your-endpoint');

        $response->assertStatus(200);
    }
}
```

### 2. 测试最佳实践

- **使用 RefreshDatabase trait**：确保每个测试都有干净的数据库
- **测试命名清晰**：使用 `test_` 前缀，描述测试行为
- **一个测试一个断言**：保持测试简单和专注
- **使用数据工厂**：避免重复的测试数据创建代码
- **测试边界情况**：包括成功和失败的场景
- **使用断言方法**：利用Laravel提供的丰富断言方法

### 3. 常用断言

```php
// 断言状态码
$response->assertStatus(200);
$response->assertStatus(401);
$response->assertStatus(422);

// 断言JSON结构
$response->assertJsonStructure([
    'data',
    'total',
]);

// 断言JSON值
$response->assertJson([
    'message' => '创建成功',
]);

// 断言数据库
$this->assertDatabaseHas('users', [
    'username' => 'testuser',
]);

$this->assertDatabaseMissing('users', [
    'username' => 'testuser',
]);

// 断言认证
$this->assertAuthenticated();
$this->assertGuest();
```

## 持续集成

### GitHub Actions 示例

```yaml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    
    steps:
    - uses: actions/checkout@v2
    
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '7.4'
    
    - name: Install Dependencies
      run: composer install --prefer-dist --no-progress --no-suggest
    
    - name: Run Tests
      run: php artisan test
```

## 故障排除

### 测试失败常见原因

1. **数据库迁移问题**
   ```bash
   php artisan migrate:fresh
   ```

2. **环境变量问题**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

3. **依赖问题**
   ```bash
   composer install
   ```

4. **缓存问题**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

## 性能优化

- 使用 `RefreshDatabase` trait 而不是 `DatabaseMigrations`，前者更快
- 在测试中使用内存数据库
- 避免在测试中发送真实的邮件或HTTP请求
- 使用 Mock 来模拟外部服务

## 参考资源

- [Laravel 测试文档](https://laravel.com/docs/testing)
- [PHPUnit 文档](https://phpunit.de/documentation.html)
- [Laravel 数据工厂](https://laravel.com/docs/eloquent-factories)
