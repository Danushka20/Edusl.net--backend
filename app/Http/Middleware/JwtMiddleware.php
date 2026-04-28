<?php

namespace App\Http\Middleware;

use App\Services\JwtService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class JwtMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $authorization = $request->header('Authorization', '');

        if (!preg_match('/Bearer\s+(.+)/', $authorization, $matches)) {
            return response()->json(['message' => 'Authorization Bearer token required.'], 401);
        }

        try {
            $payload = app(JwtService::class)->decodeToken($matches[1]);
        } catch (\Throwable $exception) {
            return response()->json(['message' => 'Invalid or expired token.'], 401);
        }

        $user = \App\Models\User::find($payload->sub ?? null);

        if (! $user) {
            return response()->json(['message' => 'Authenticated user not found.'], 401);
        }

        Auth::setUser($user);
        $request->setUserResolver(fn () => $user);

        return $next($request);
    }
}
