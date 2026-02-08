# Facade和Helper使用示例

## Facade使用示例

### 1. 在控制器中使用Facade

```php
<?php

namespace App\Http\Controllers;

use Cheney\AdminSystem\Facades\AdminAuth;
use Cheney\AdminSystem\Facades\AdminUser;
use Cheney\AdminSystem\Facades\AdminRole;
use Cheney\AdminSystem\Facades\AdminPermission;
use Cheney\AdminSystem\Facades\AdminMenu;
use Cheney\AdminSystem\Facades\AdminOperationLog;
use Illuminate\Http\Request;

class ExampleController extends Controller
{
    public function login(Request $request)
    {
        $result = AdminAuth::login($request->username, $request->password);
        return response()->json($result);
    }

    public function getUsers()
    {
        $users = AdminUser::index(['status' => 1]);
        return response()->json($users);
    }

    public function createUser(Request $request)
    {
        $user = AdminUser::store([
            'username' => $request->username,
            'password' => $request->password,
            'name' => $request->name,
            'email' => $request->email,
        ]);
        return response()->json($user);
    }

    public function assignRole($userId, $roleId)
    {
        AdminUser::assignRoles($userId, [$roleId]);
        return response()->json(['message' => '角色分配成功']);
    }

    public function getRoles()
    {
        $roles = AdminRole->index(['status' => 1]);
        return response()->json($roles);
    }

    public function getPermissions()
    {
        $permissions = AdminPermission->index(['type' => 1]);
        return response()->json($permissions);
    }

    public function getMenus()
    {
        $menus = AdminMenu->index();
        return response()->json($menus);
    }

    public function getUserMenus()
    {
        $menus = AdminMenu->getUserMenus();
        return response()->json($menus);
    }

    public function getOperationLogs()
    {
        $logs = AdminOperationLog->index(['status' => 1]);
        return response()->json($logs);
    }

    public function clearOldLogs()
    {
        AdminOperationLog->clear(30);
        return response()->json(['message' => '清理30天前的日志成功']);
    }
}
```

### 2. 在服务类中使用Facade

```php
<?php

namespace App\Services;

use Cheney\AdminSystem\Facades\AdminUser;
use Cheney\AdminSystem\Facades\AdminRole;
use Cheney\AdminSystem\Facades\AdminPermission;

class CustomUserService
{
    public function createUserWithRoles($userData, $roleIds)
    {
        $user = AdminUser->store($userData);
        
        if (!empty($roleIds)) {
            AdminUser->assignRoles($user['data']['id'], $roleIds);
        }
        
        return $user;
    }

    public function getUserWithRoles($userId)
    {
        $user = AdminUser->show($userId);
        
        $roles = AdminRole->index(['user_id' => $userId]);
        
        return [
            'user' => $user,
            'roles' => $roles,
        ];
    }

    public function checkUserPermission($userId, $permissionCode)
    {
        $user = AdminUser->show($userId);
        
        $hasPermission = AdminPermission->checkUserPermission($userId, $permissionCode);
        
        return $hasPermission;
    }
}
```

### 3. 在中间件中使用Facade

```php
<?php

namespace App\Http\Middleware;

use Cheney\AdminSystem\Facades\AdminAuth;
use Cheney\AdminSystem\Facades\AdminPermission;
use Closure;

class CustomAuthMiddleware
{
    public function handle($request, Closure $next, $permission = null)
    {
        $user = AdminAuth->me();
        
        if (!$user) {
            return response()->json(['message' => '未登录'], 401);
        }
        
        if ($permission) {
            $hasPermission = AdminPermission->checkUserPermission(
                $user['data']['id'], 
                $permission
            );
            
            if (!$hasPermission) {
                return response()->json(['message' => '权限不足'], 403);
            }
        }
        
        return $next($request);
    }
}
```

## Helper函数使用示例

### 1. 在控制器中使用Helper函数

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController extends Controller
{
    public function login(Request $request)
    {
        $authService = admin_auth();
        $result = $authService->login($request->username, $request->password);
        return response()->json($result);
    }

    public function getUsers()
    {
        $userService = admin_user();
        $users = $userService->index(['status' => 1]);
        return response()->json($users);
    }

    public function createUser(Request $request)
    {
        $userService = admin_user();
        $user = $userService->store([
            'username' => $request->username,
            'password' => $request->password,
            'name' => $request->name,
        ]);
        return response()->json($user);
    }

    public function getRoles()
    {
        $roleService = admin_role();
        $roles = $roleService->index(['status' => 1]);
        return response()->json($roles);
    }

    public function getPermissions()
    {
        $permissionService = admin_permission();
        $permissions = $permissionService->index(['type' => 1]);
        return response()->json($permissions);
    }

    public function getMenus()
    {
        $menuService = admin_menu();
        $menus = $menuService->index();
        return response()->json($menus);
    }

    public function getOperationLogs()
    {
        $logService = admin_operation_log();
        $logs = $logService->index(['status' => 1]);
        return response()->json($logs);
    }
}
```

### 2. 在视图Blade模板中使用Helper函数

```blade
{{-- 在Blade模板中使用 --}}
<div class="user-info">
    @php
        $authService = admin_auth();
        $user = $authService->me();
    @endphp
    
    @if($user)
        <p>欢迎, {{ $user['data']['name'] }}</p>
    @endif
</div>

<div class="menu">
    @php
        $menuService = admin_menu();
        $menus = $menuService->getUserMenus();
    @endphp
    
    @foreach($menus['data'] as $menu)
        <a href="{{ $menu['path'] }}">{{ $menu['title'] }}</a>
    @endforeach
</div>
```

### 3. 在命令中使用Helper函数

```php
<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SyncUsersCommand extends Command
{
    protected $signature = 'users:sync';

    protected $description = '同步用户数据';

    public function handle()
    {
        $userService = admin_user();
        
        $this->info('开始同步用户数据...');
        
        $users = $userService->index(['status' => 1]);
        
        foreach ($users['data'] as $user) {
            $this->info("处理用户: {$user['name']}");
        }
        
        $this->info('同步完成!');
        
        return 0;
    }
}
```

### 4. 在Job中使用Helper函数

```php
<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessUserJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    public function handle()
    {
        $userService = admin_user();
        $logService = admin_operation_log();
        
        $user = $userService->show($this->userId);
        
        $logService->store([
            'user_id' => $this->userId,
            'action' => 'process_user',
            'description' => '处理用户数据',
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
```

### 5. 在Event Listener中使用Helper函数

```php
<?php

namespace App\Listeners;

use App\Events\UserCreated;
use Cheney\AdminSystem\Facades\AdminRole;

class AssignDefaultRoleListener
{
    public function handle(UserCreated $event)
    {
        $roleService = admin_role();
        
        $defaultRole = $roleService->index(['code' => 'user']);
        
        if (!empty($defaultRole['data'])) {
            $userService = admin_user();
            $userService->assignRoles(
                $event->userId, 
                [$defaultRole['data'][0]['id']]
            );
        }
    }
}
```

## 混合使用Facade和Helper函数

```php
<?php

namespace App\Services;

use Cheney\AdminSystem\Facades\AdminAuth;
use Illuminate\Support\Facades\Log;

class AdvancedUserService
{
    public function processUserLogin($username, $password)
    {
        try {
            $result = AdminAuth->login($username, $password);
            
            if ($result['code'] === 10000) {
                $logService = admin_operation_log();
                $logService->store([
                    'user_id' => $result['data']['user']['id'],
                    'action' => 'login',
                    'description' => '用户登录',
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
                
                return $result;
            }
            
            return $result;
        } catch (\Exception $e) {
            Log::error('用户登录失败', [
                'username' => $username,
                'error' => $e->getMessage(),
            ]);
            
            throw $e;
        }
    }

    public function createUserWithDefaultRoles($userData)
    {
        $userService = admin_user();
        $roleService = admin_role();
        
        $user = $userService->store($userData);
        
        $defaultRoles = $roleService->index(['is_default' => 1]);
        
        if (!empty($defaultRoles['data'])) {
            $roleIds = array_column($defaultRoles['data'], 'id');
            $userService->assignRoles($user['data']['id'], $roleIds);
        }
        
        return $user;
    }
}
```

## 最佳实践

1. **在控制器中使用Facade**：代码更简洁，易于阅读
2. **在服务类中使用Helper函数**：更灵活，可以传递服务实例
3. **在视图中使用Helper函数**：避免在视图中直接使用Facade
4. **在命令和Job中使用Helper函数**：避免依赖注入问题
5. **统一使用方式**：在项目中保持一致的使用方式

## 注意事项

1. Facade和Helper函数都返回服务实例，可以调用服务中的所有方法
2. 确保在使用前已经正确安装和配置了AdminSystem包
3. 在生产环境中使用时，建议添加适当的错误处理
4. Helper函数会在全局命名空间中定义，注意避免命名冲突