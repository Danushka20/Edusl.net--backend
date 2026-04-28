<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HealthController extends Controller
{
    public function index(): \Illuminate\Http\JsonResponse
    {
        return response()->json([
            'status' => 'ok',
            'environment' => app()->environment(),
        ]);
    }

    public function db(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $connection = DB::connection();
            $connection->getPdo();

            return response()->json([
                'status' => 'ok',
                'connection' => $connection->getName(),
                'database' => $connection->getDatabaseName(),
            ]);
        } catch (\Throwable $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage(),
            ], 500);
        }
    }
}
