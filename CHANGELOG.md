# 更新日志

本文档记录了 cheney/admin-system 包的所有重要更改。

## [1.0.2] - 2026-02-09

### 修复
- 修复所有数据表缺少软删除字段的问题，为 roles、permissions、menus、operation_logs 表添加 `deleted_at` 字段
- 创建数据库迁移文件 `AddSoftDeletesToTables` 来为现有数据库表添加软删除支持
- 修复登录接口在查询关联角色时因缺少 `deleted_at` 字段而导致的 SQL 错误

### 改进
- 为所有数据表添加软删除支持，包括 admins、roles、permissions、menus、operation_logs
- 为所有 Model 添加 SoftDeletes trait
- 完善软删除功能的数据库迁移，确保现有数据库可以平滑升级
- 测试所有 API 接口，确保软删除功能正常工作

### 测试
- 测试用户登录接口，成功获取 JWT Token
- 测试菜单管理接口，成功删除菜单并验证软删除效果
- 测试角色管理接口，成功删除角色并验证软删除效果
- 测试权限管理接口，成功删除权限并验证软删除效果
- 测试用户管理接口，成功获取用户列表
- 测试操作日志接口，成功获取操作日志列表

## [1.0.1] - 2026-02-09

### 修复
- 修复 AuthService 中使用 `auth('admin')` 导致的认证问题，统一使用 `JWTAuth::parseToken()->authenticate()`
- 修复 PermissionMiddleware 中使用 `auth('admin')` 导致的权限检查失败问题
- 修复 OperationLogMiddleware 中使用 `auth('admin')` 导致的操作日志记录失败问题
- 修复 MenuService 中使用 `auth('admin')` 导致的用户菜单获取失败问题
- 修复 MenuController 中缺少 `title` 字段验证导致的数据插入错误
- 修复 MenuController 中使用 `visible` 字段而不是数据库中的 `is_hidden` 字段的问题
- 修复 Swagger 配置文件中文档保存路径配置错误，从 `storage_path('api-docs')` 更新为 `public_path('api-docs')`
- 在 README.md 中添加详细的注意事项章节，包含 15 条重要提示

### 改进
- 优化 JWT 认证机制，确保所有 Service 层和 Middleware 层统一使用 JWTAuth
- 增强操作日志中间件的异常处理，避免 Token 无效时记录日志失败
- 完善文档说明，明确指出不应使用 `auth('admin')` 方式
- 统一菜单字段命名，使用 `is_hidden` 替代 `visible`
- 更新 Swagger 文档生成路径配置，确保文档可以通过 Web 访问
- 优化 AdminSystemServiceProvider，直接注册 JWT 和 L5Swagger 服务提供者，移除不必要的 app.php 配置文件

## [1.0.0] - 2024-01-01

### 新增
- 初始版本发布
- 用户管理功能（增删改查、角色分配、密码重置、状态管理）
- 角色管理功能（增删改查、权限分配、用户分配）
- 权限管理功能（增删改查、权限分组）
- 菜单管理功能（增删改查、树形结构展示、权限关联）
- 操作日志功能（记录用户操作、日志查询、统计、导出）
- JWT 认证机制
- RBAC 权限控制系统
- Swagger API 文档自动生成
- 完整的单元测试覆盖
- Service 层架构设计
- CORS 跨域支持
- Facade 门面类支持
- 数据库迁移和填充
- 统一 API 响应格式

### 技术栈
- Laravel 8+
- PHP 7.4+
- MySQL 5.7+
- Redis
- JWT Auth
- Swagger

### 数据库表
- admins - 管理员表
- roles - 角色表
- permissions - 权限表
- menus - 菜单表
- role_admin - 角色管理员关联表
- permission_role - 权限角色关联表
- operation_logs - 操作日志表

### API 端点
- `/api/system/auth/*` - 认证相关接口
- `/api/system/admins` - 用户管理接口
- `/api/system/roles` - 角色管理接口
- `/api/system/permissions` - 权限管理接口
- `/api/system/menus` - 菜单管理接口
- `/api/system/operation-logs` - 操作日志接口

### 配置
- JWT 配置
- 路由前缀配置
- 中间件配置
- 分页配置
- 操作日志配置
- 上传配置
- 默认管理员配置

### 中间件
- JWT 认证中间件
- 权限检查中间件
- 操作日志中间件
- CORS 中间件

### Facade
- AdminAuth - 认证服务门面
- AdminUser - 用户管理门面
- AdminRole - 角色管理门面
- AdminPermission - 权限管理门面
- AdminMenu - 菜单管理门面
- AdminOperationLog - 操作日志门面

### 单元测试
- AuthTest - 认证功能测试
- AdminTest - 用户管理测试
- RoleTest - 角色管理测试
- PermissionTest - 权限管理测试
- MenuTest - 菜单管理测试
- OperationLogTest - 操作日志测试

### 默认数据
- 默认管理员账号：admin / admin123
- 默认角色：超级管理员、管理员、编辑
- 默认权限：用户管理、角色管理、权限管理、菜单管理
- 默认菜单：系统管理、用户管理、角色管理、权限管理、菜单管理、操作日志

---

## 版本说明

版本号遵循 [语义化版本](https://semver.org/lang/zh-CN/) 规范。

- **主版本号**：不兼容的 API 修改
- **次版本号**：向下兼容的功能性新增
- **修订号**：向下兼容的问题修正
