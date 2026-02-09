<?php

return [

    'default' => 'default',

    'documentations' => [
        'default' => [
            'api' => [
                'title' => 'Ant Admin System API',
                'description' => '后台管理系统API文档',
                'version' => '1.0.0',
                'contact' => [
                    'name' => 'API Support',
                    'email' => 'support@example.com',
                ],
            ],
            'security' => [
                'securitySchemes' => [
                    'bearerAuth' => [
                        'type' => 'http',
                        'scheme' => 'bearer',
                        'bearerFormat' => 'JWT',
                    ],
                ],
                'security' => [
                    ['bearerAuth' => []],
                ],
            ],
            'servers' => [
                [
                    'url' => env('APP_URL', 'http://localhost:8000') . '/api',
                    'description' => 'API Server',
                ],
            ],
            'paths' => [],
            'consumes' => ['application/json'],
            'produces' => ['application/json'],
        ],
    ],

    'defaults' => [
        'routes' => [
            'api' => 'api/documentation',
        ],
        'paths' => [
            'annotations' => [
                base_path('vendor/cheney/admin-system/src/Controllers'),
                base_path('app/Http/Controllers'),
            ],
            'docs' => storage_path('api-docs'),
            'interfaces' => base_path('app/Interfaces'),
            'traits' => base_path('app/Traits'),
            'helpers' => base_path('app/Helpers'),
        ],
        'scan' => [
            'exclude' => [],
        ],
        'constants' => [
            'L5_SWAGGER_CONST_HOST' => env('L5_SWAGGER_CONST_HOST', 'http://localhost:8000'),
            'L5_SWAGGER_CONST_SCHEMES' => env('L5_SWAGGER_CONST_SCHEMES', 'http'),
        ],
        'generate_always' => env('L5_SWAGGER_GENERATE_ALWAYS', false),
        'generate_yaml_copy' => env('L5_SWAGGER_GENERATE_YAML_COPY', false),
        'proxy' => false,
        'additional_config_url' => null,
        'operations_sort' => env('L5_SWAGGER_OPERATIONS_SORT', null),
        'validator_url' => null,
        'ui' => [
            'display' => [
                'default_models_expand_depth' => 1,
                'default_model_expand_depth' => 1,
                'default_modelRendering' => 'example',
                'displayRequestDuration' => false,
                'docExpansion' => 'none',
                'filter' => true,
                'maxDisplayedTags' => -1,
                'showExtensions' => false,
                'showCommonExtensions' => false,
                'tryItOutEnabled' => true,
            ],
        ],
        'headers' => [
            'Accept' => 'application/json',
        ],
        'middleware' => [
            'api' => ['web'],
        ],
        'views' => [
            'swagger' => 'swagger::swagger',
            'l5-swagger' => 'swagger::index',
        ],
        'files' => [
            'generator' => storage_path('api-docs/swagger.json'),
        ],
        'algorithm' => null,
    ],
];
