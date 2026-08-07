<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

/**
 * Controller autentikasi API.
 * Menangani pendaftaran, login, dan logout untuk endpoint JSON.
 */
class AuthController extends Controller
{
    /**
     * Proses registrasi API.
     * Menggunakan RegisterRequest untuk validasi dan menyimpan password dalam bentuk hash.
     */
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'data' => [
                'email' => $user->email,
                'name' => $user->name,
            ],
        ], 201);
    }

    /**
     * Autentikasi API user.
     * Jika valid, kembalikan token Bearer yang bisa digunakan untuk endpoint terproteksi.
     */
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();
        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => ['token' => $token],
        ]);
    }

    /**
     * Logout API dan hapus token saat ini.
     */
    public function logout(): JsonResponse
    {
        $user = auth()->user();

        if ($user) {
            $user->currentAccessToken()?->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logout successful',
        ]);
    }
}
