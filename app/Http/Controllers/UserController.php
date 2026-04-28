<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function profile(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json(['user' => $request->user()]);
    }

    public function dashboard(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'message' => 'User dashboard access granted.',
            'user' => $request->user()?->only(['id', 'name', 'email', 'role']),
        ]);
    }
}
