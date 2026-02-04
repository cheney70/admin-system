<?php

namespace Cheney\Content\Controllers;

use Illuminate\Routing\Controller as BaseController;

/**
 * @OA\Info(title="接口文档及调试", version="1.0")
 * @OA\SecurityScheme(
 *   type="http",
 *   in="header",
 *   name="Authorization",
 *   scheme="bearer",
 *   securityScheme="Authorization",
 *   bearerFormat="JWT"
 * )
 */
class Controller extends BaseController
{

}
