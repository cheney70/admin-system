<?php

return [
    'prefix' => 'api',
    'middleware' => ['api'],
    'jwt_secret' => env('JWT_SECRET'),
    'jwt_ttl' => env('JWT_TTL', 1440),
];