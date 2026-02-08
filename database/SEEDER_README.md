# 数据库填充说明

## 填充器列表

系统包含以下数据库填充器：

### 1. UserSeeder
创建测试用户账号：
- **admin** / 123456 - 超级管理员
- **editor** / 123456 - 编辑员
- **user** / 123456 - 普通用户

### 2. RoleSeeder
创建角色：
- 超级管理员 (super_admin)
- 管理员 (admin)
- 编辑员 (editor)
- 普通用户 (user)

### 3. MenuSeeder
创建菜单：
- 系统管理
- 用户管理
- 角色管理
- 菜单管理
- 权限管理
- 操作日志

### 4. PermissionSeeder
创建权限：
- 用户管理权限（查看、创建、编辑、删除、分配角色、重置密码）
- 角色管理权限（查看、创建、编辑、删除、分配权限）
- 菜单管理权限（查看、创建、编辑、删除）
- 权限管理权限（查看、创建、编辑、删除）
- 日志管理权限（查看、删除、清理）

### 5. RoleUserSeeder
关联用户和角色：
- admin -> 超级管理员
- editor -> 编辑员
- user -> 普通用户

### 6. PermissionRoleSeeder
关联角色和权限：
- 超级管理员 -> 所有权限
- 管理员 -> 大部分管理权限（不含删除角色、删除菜单等）
- 编辑员 -> 只读权限
- 普通用户 -> 仅日志查看权限

## 使用方法

### 运行所有填充器

```bash
php artisan db:seed
```

### 运行指定填充器

```bash
# 只填充用户数据
php artisan db:seed --class=UserSeeder

# 只填充角色数据
php artisan db:seed --class=RoleSeeder

# 只填充菜单数据
php artisan db:seed --class=MenuSeeder

# 只填充权限数据
php artisan db:seed --class=PermissionSeeder
```

### 重置并重新填充

```bash
# 回滚所有迁移并重新运行
php artisan migrate:fresh --seed

# 或者分步执行
php artisan migrate:fresh
php artisan db:seed
```

## 注意事项

1. **首次使用**：建议在运行迁移后立即运行填充器
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

2. **生产环境**：在生产环境使用填充器前，请先检查填充器中的测试数据是否适合生产环境

3. **密码安全**：填充器中的默认密码为 `123456`，生产环境请务必修改

4. **数据覆盖**：运行填充器会覆盖已存在的数据，请谨慎操作

## 测试账号

填充完成后，可以使用以下账号登录：

| 用户名 | 密码 | 角色 | 权限 |
|--------|------|------|------|
| admin | 123456 | 超级管理员 | 所有权限 |
| editor | 123456 | 编辑员 | 只读权限 |
| user | 123456 | 普通用户 | 仅日志查看 |

## 自定义数据

如需修改填充数据，请编辑对应的Seeder文件：
- `database/seeders/UserSeeder.php` - 修改用户数据
- `database/seeders/RoleSeeder.php` - 修改角色数据
- `database/seeders/MenuSeeder.php` - 修改菜单数据
- `database/seeders/PermissionSeeder.php` - 修改权限数据
- `database/seeders/RoleUserSeeder.php` - 修改用户角色关联
- `database/seeders/PermissionRoleSeeder.php` - 修改角色权限关联