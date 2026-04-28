<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\JwtService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => User::ROLE_USER,
            'provider' => 'email',
        ]);

        return $this->tokenResponse($user);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid credentials.'], 401);
        }

        return $this->tokenResponse($user);
    }

    public function googleLogin(Request $request)
    {
        $data = $request->validate([
            'token' => ['required', 'string'],
        ]);

        try {
            $googleUser = Socialite::driver('google')->userFromToken($data['token']);
        } catch (\Throwable $exception) {
            return response()->json(['message' => 'Google token verification failed.'], 401);
        }

        if (! $googleUser->getEmail()) {
            return response()->json(['message' => 'Google account email is required.'], 400);
        }

        $user = User::where('provider', 'google')
            ->where('provider_id', $googleUser->getId())
            ->first();

        if (! $user) {
            $user = User::where('email', $googleUser->getEmail())->first();
        }

        if (! $user) {
            $user = User::create([
                'name' => $googleUser->getName() ?: $googleUser->getNickname() ?: 'Google User',
                'email' => $googleUser->getEmail(),
                'password' => Hash::make(Str::random(32)),
                'role' => User::ROLE_USER,
                'provider' => 'google',
                'provider_id' => $googleUser->getId(),
            ]);
        } else {
            $user->update([
                'provider' => 'google',
                'provider_id' => $googleUser->getId(),
                'name' => $googleUser->getName() ?: $user->name,
            ]);
        }

        return $this->tokenResponse($user);
    }

    public function me(Request $request)
    {
        return response()->json(['user' => $request->user()]);
    }

    protected function tokenResponse(User $user)
    {
        $token = app(JwtService::class)->generateToken($user);

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'provider' => $user->provider,
            ],
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => config('jwt.ttl') * 60,
        ]);
    }
}
