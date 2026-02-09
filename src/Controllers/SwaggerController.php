<?php

namespace Cheney\AdminSystem\Controllers;

use OpenApi\Annotations as OA;

/**
 * @OA\Info(
 *     title="Ant Admin System API",
 *     version="1.0.0",
 *     description="后台管理系统API文档",
 *     @OA\Contact(
 *         name="API Support",
 *         email="support@example.com"
 *     )
 * )
 * @OA\Server(
 *     url=L5_SWAGGER_CONST_HOST,
 *     description="API Server"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 * @OA\Tag(
 *     name="认证",
 *     description="用户认证相关接口"
 * )
 * @OA\Tag(
 *     name="用户管理",
 *     description="管理员用户管理接口"
 * )
 * @OA\Tag(
 *     name="角色管理",
 *     description="角色管理接口"
 * )
 * @OA\Tag(
 *     name="权限管理",
 *     description="权限管理接口"
 * )
 * @OA\Tag(
 *     name="菜单管理",
 *     description="菜单管理接口"
 * )
 * @OA\Tag(
 *     name="操作日志",
 *     description="操作日志管理接口"
 * )
 */
class SwaggerController
{
}
