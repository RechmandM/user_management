<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * Controller API untuk manajemen user.
 * Mendukung operasi CRUD user dengan response JSON dan validasi request.
 */
class UserController extends Controller
{
    /**
     * Ambil daftar semua user.
     * Mengembalikan data user tanpa password.
     */
    public function index(): JsonResponse
    {
        $users = User::orderByDesc('created_at')->get(['id', 'email', 'name', 'created_at', 'updated_at']);

        return response()->json([
            'success' => true,
            'message' => 'Users berhasil diambil',
            'data' => $users,
        ]);
    }

    /**
     * Buat user baru melalui API.
     * Password di-hash sebelum disimpan.
     */
    public function store(UserStoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil ditambahkan',
            'data' => [
                'email' => $user->email,
                'name' => $user->name,
            ],
        ], 201);
    }

    /**
     * Ambil detail user berdasarkan ID.
     * Jika tidak ditemukan, mengembalikan response 404.
     */
    public function show(int $id): JsonResponse
    {
        $user = User::find($id, ['email', 'name', 'created_at', 'updated_at']);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'User berhasil ditampilkan',
            'data' => $user,
        ]);
    }

    /**
     * Update user berdasarkan ID.
     * Password hanya di-hash jika disertakan dalam request.
     */
    public function update(UserUpdateRequest $request, int $id): JsonResponse
    {
        $user = User::find($id);

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan',
            ], 404);
        }

        $data = $request->validated();

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diperbarui',
            'data' => [
                'email' => $user->email,
                'name' => $user->name,
            ],
        ]);
    }

    /**
     * Hapus user berdasarkan ID.
     * Jika user tidak ditemukan, mengembalikan response 404.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {

        // validasi tidak bisa menghapus diri sendiri
        $currentUser = $request->user();
        if ($currentUser && $currentUser->id === $id) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak dapat menghapus akun Anda sendiri',
            ], 422); // HTTP 422 Unprocessable Content (atau 403 Forbidden)
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan',
            ], 404);
        }

        // hapus token user
        $user->tokens()->delete();

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil dihapus',
        ]);
    }
}
