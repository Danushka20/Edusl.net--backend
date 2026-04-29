<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

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
            'users' => User::select('id', 'name', 'email', 'role', 'provider', 'created_at')
                ->orderBy('created_at', 'desc')
                ->get(),
        ]);
    }

    public function updateRole(Request $request, User $user): \Illuminate\Http\JsonResponse
    {
        $data = $request->validate([
            'role' => ['required', 'string', 'in:' . User::ROLE_ADMIN . ',' . User::ROLE_USER],
        ]);

        $user->role = $data['role'];
        $user->save();

        return response()->json(['user' => $user]);
    }
}
