<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        $user = $request->user();

        if (! $user) {
            return response()->json(['message' => 'Authentication required.'], 401);
        }

        $allowed = explode('|', $roles);

        if (! in_array($user->role, $allowed, true)) {
            return response()->json(['message' => 'Unauthorized for this role.'], 403);
        }

        return $next($request);
    }
}
