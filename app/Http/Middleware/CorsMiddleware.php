<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CorsMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $config = config('cors', []);
        $origin = $request->headers->get('origin');
        $allowedOrigins = $config['allowed_origins'] ?? [];
        $allowedMethods = implode(', ', $config['allowed_methods'] ?? ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS']);
        $allowedHeaders = implode(', ', $config['allowed_headers'] ?? ['*']);
        $exposedHeaders = implode(', ', $config['exposed_headers'] ?? []);
        $supportsCredentials = $config['supports_credentials'] ? 'true' : 'false';

        if ($request->isMethod('OPTIONS')) {
            $response = response()->json([], 200);
        } else {
            $response = $next($request);
        }

        if ($origin && in_array($origin, $allowedOrigins, true)) {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
        }

        $response->headers->set('Access-Control-Allow-Methods', $allowedMethods);
        $response->headers->set('Access-Control-Allow-Headers', $allowedHeaders);
        $response->headers->set('Access-Control-Allow-Credentials', $supportsCredentials);
        $response->headers->set('Access-Control-Max-Age', (string) ($config['max_age'] ?? 0));

        if ($exposedHeaders !== '') {
            $response->headers->set('Access-Control-Expose-Headers', $exposedHeaders);
        }

        return $response;
    }
}
