# API响应格式说明

## 统一响应格式

所有API接口都遵循统一的响应格式：

### 成功响应

```json
{
  "code": 10000,
  "message": "操作成功",
  "data": {
    // 实际数据
  }
}
```

### 失败响应

```json
{
  "code": 20000,
  "message": "操作失败的具体原因",
  "data": null
}
```

## 响应代码说明

| 代码 | 说明 |
|------|------|
| 10000 | 操作成功 |
| 20000 | 操作失败 |
| 401 | 未授权（Token无效或已过期） |
| 403 | 无权访问（权限不足） |
| 404 | 资源不存在 |
| 422 | 参数验证失败 |
| 500 | 服务器内部错误 |

## ApiResponseTrait 方法

### 基础方法

#### success($data, $message, $code)
返回成功响应

```php
return $this->success($data, '操作成功');
return $this->success($data, '登录成功');
return $this->success($data, '操作成功', 10000);
```

#### error($message, $code, $data)
返回失败响应

```php
return $this->error('操作失败');
return $this->error('用户名或密码错误');
return $this->error('参数验证失败', 422);
```

### 快捷方法

#### successWithData($data)
返回带数据的成功响应

```php
return $this->successWithData($user);
```

#### successWithMessage($message)
返回带消息的成功响应

```php
return $this->successWithMessage('创建成功');
return $this->successWithMessage('更新成功');
```

#### successPaginated($data, $message)
返回分页数据的成功响应

```php
return $this->successPaginated($users);
```

#### created($data, $message)
返回创建成功的响应

```php
return $this->created($user);
return $this->created($user, '创建成功');
```

#### updated($data, $message)
返回更新成功的响应

```php
return $this->updated($user);
return $this->updated($user, '更新成功');
```

#### deleted($message)
返回删除成功的响应

```php
return $this->deleted();
return $this->deleted('删除成功');
```

### 错误快捷方法

#### unauthorized($message)
返回未授权响应（code: 401）

```php
return $this->unauthorized();
return $this->unauthorized('Token无效或已过期');
```

#### forbidden($message)
返回无权访问响应（code: 403）

```php
return $this->forbidden();
return $this->forbidden('无权访问');
return $this->forbidden('账号已被禁用');
```

#### notFound($message)
返回资源不存在响应（code: 404）

```php
return $this->notFound();
return $this->notFound('用户不存在');
```

#### validation($errors, $message)
返回参数验证失败响应（code: 422）

```php
return $this->validation($errors);
return $this->validation($errors, '参数验证失败');
```

#### serverError($message)
返回服务器错误响应（code: 500）

```php
return $this->serverError();
return $this->serverError('服务器内部错误');
```

## 使用示例

### 在控制器中使用

```php
<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponseTrait;

class UserController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $users = User::all();
        
        return $this->successWithData($users);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([...]);
        
        $user = User::create($validated);
        
        return $this->created($user);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $user->update($request->all());
        
        return $this->updated($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        $user->delete();
        
        return $this->deleted();
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        
        return $this->successWithData($user);
    }
}
```

### 在中间件中使用

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;

class JwtMiddleware
{
    use ApiResponseTrait;

    public function handle(Request $request, Closure $next)
    {
        try {
            $user = auth('api')->user();
            
            if (!$user) {
                return $this->unauthorized();
            }
            
            if ($user->status != 1) {
                return $this->forbidden('账号已被禁用');
            }
            
            return $next($request);
        } catch (\Exception $e) {
            return $this->unauthorized('Token无效或已过期');
        }
    }
}
```

## 前端处理

### 响应拦截器

前端axios拦截器会自动处理统一响应格式：

```javascript
service.interceptors.response.use(
  response => {
    const res = response.data
    
    if (res.code === 10000) {
      return res.data
    } else {
      message.error(res.message || 'Error')
      return Promise.reject(new Error(res.message || 'Error'))
    }
  },
  error => {
    if (error.response) {
      if (error.response.status === 401) {
        message.error('登录已过期，请重新登录')
      } else if (error.response.status === 403) {
        message.error('没有权限访问')
      } else if (error.response.status === 422) {
        message.error('参数验证失败')
      }
    }
    
    return Promise.reject(error)
  }
)
```

### API调用示例

```javascript
import request from '@/utils/request'

export function getUserList(params) {
  return request({
    url: '/users',
    method: 'get',
    params
  })
}

export function createUser(data) {
  return request({
    url: '/users',
    method: 'post',
    data
  })
}

export function updateUser(id, data) {
  return request({
    url: `/users/${id}`,
    method: 'put',
    data
  })
}

export function deleteUser(id) {
  return request({
    url: `/users/${id}`,
    method: 'delete'
  })
}
```

## 响应示例

### 登录成功

```json
{
  "code": 10000,
  "message": "登录成功",
  "data": {
    "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "token_type": "bearer",
    "expires_in": 86400,
    "user": {
      "id": 1,
      "username": "admin",
      "name": "超级管理员",
      "email": "admin@example.com"
    }
  }
}
```

### 登录失败

```json
{
  "code": 20000,
  "message": "用户名或密码错误",
  "data": null
}
```

### 获取用户列表

```json
{
  "code": 10000,
  "message": "操作成功",
  "data": {
    "current_page": 1,
    "data": [
      {
        "id": 1,
        "username": "admin",
        "name": "超级管理员"
      }
    ],
    "per_page": 15,
    "total": 1
  }
}
```

### 参数验证失败

```json
{
  "code": 422,
  "message": "参数验证失败",
  "data": {
    "username": ["用户名不能为空"],
    "password": ["密码不能少于6位"]
  }
}
```

### 未授权

```json
{
  "code": 401,
  "message": "未授权",
  "data": null
}
```

### 无权访问

```json
{
  "code": 403,
  "message": "无权访问",
  "data": null
}
```

## 自定义响应代码

如果需要自定义响应代码，可以在控制器中覆盖：

```php
class YourController extends Controller
{
    use ApiResponseTrait;
    
    protected $successCode = 10001;
    protected $errorCode = 20001;
    
    public function someMethod()
    {
        return $this->success($data, '操作成功');
    }
}
```

## 注意事项

1. **所有控制器都应使用 ApiResponseTrait**
2. **不要直接使用 response()->json()**，应该使用Trait提供的方法
3. **错误消息要清晰明确**，帮助前端用户理解问题
4. **成功响应尽量返回数据**，方便前端使用
5. **分页数据使用 successPaginated 方法**
6. **中间件中的错误也要使用统一格式**
