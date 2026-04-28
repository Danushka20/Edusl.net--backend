<?php

namespace App\Services;

use App\Models\User;
use DateTimeImmutable;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JwtService
{
    public function generateToken(User $user): string
    {
        $secret = config('jwt.secret');
        $ttl = (int) config('jwt.ttl', 1440);

        $payload = [
            'iss' => config('app.url'),
            'sub' => $user->id,
            'email' => $user->email,
            'role' => $user->role,
            'iat' => time(),
            'exp' => time() + ($ttl * 60),
        ];

        return JWT::encode($payload, $secret, 'HS256');
    }

    public function decodeToken(string $token): object
    {
        $secret = config('jwt.secret');

        return JWT::decode($token, new Key($secret, 'HS256'));
    }
}
