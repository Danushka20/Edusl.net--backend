<?php

namespace App\Http\Controllers;

use App\Models\User;

class AdminController extends Controller
{
    public function dashboard(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'message' => 'Admin panel access granted.',
            'totals' => [
                'users' => User::count(),
            ],
        ]);
    }

    public function users(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'users' => User::select('id', 'name', 'email', 'role', 'provider', 'created_at')->orderBy('created_at', 'desc')->get(),
        ]);
    }
}
