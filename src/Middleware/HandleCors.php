<?php

namespace Cheney\AdminSystem\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class HandleCors
{
    public function handle(Request $request, Closure $next)
    {
        $allowedOrigins = Config::get('cors.allowed_origins', ['*']);
        $allowedMethods = Config::get('cors.allowed_methods', ['*']);
        $allowedHeaders = Config::get('cors.allowed_headers', ['*']);
        $exposedHeaders = Config::get('cors.exposed_headers', []);
        $maxAge = Config::get('cors.max_age', 0);
        $supportsCredentials = Config::get('cors.supports_credentials', false);

        $origin = $request->headers->get('Origin');

        if ($this->isOriginAllowed($origin, $allowedOrigins)) {
            header('Access-Control-Allow-Origin: ' . $origin);
        }

        if ($supportsCredentials) {
            header('Access-Control-Allow-Credentials: true');
        }

        if (!empty($exposedHeaders)) {
            header('Access-Control-Expose-Headers: ' . implode(', ', $exposedHeaders));
        }

        header('Access-Control-Allow-Methods: ' . implode(', ', $allowedMethods));
        header('Access-Control-Allow-Headers: ' . implode(', ', $allowedHeaders));

        if ($maxAge > 0) {
            header('Access-Control-Max-Age: ' . $maxAge);
        }

        if ($request->isMethod('OPTIONS')) {
            return response('', 200);
        }

        return $next($request);
    }

    protected function isOriginAllowed($origin, $allowedOrigins)
    {
        if (in_array('*', $allowedOrigins)) {
            return true;
        }

        if (empty($origin)) {
            return true;
        }

        return in_array($origin, $allowedOrigins);
    }
}