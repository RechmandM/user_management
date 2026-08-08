<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

/**
 * Controller web untuk manajemen user.
 * Menangani tampilan dan proses CRUD user melalui antarmuka web.
 */

class UserController extends Controller
{
    /**
     * Tampilkan daftar user.
     */
    public function index(Request $request): View
    {
        $users = User::orderByDesc('created_at')->get();
        $user_email = $request->session()->get('user_email');

        return view('users.index', compact('users', 'user_email'));
    }

    /**
     * Tampilkan form pembuatan user baru.
     */
    public function create(): View
    {
        return view('users.create');
    }

    /**
     * Simpan user baru.
     * Validasi dilakukan oleh UserStoreRequest dan password di-hash sebelum disimpan.
     */
    public function store(UserStoreRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');
    }

    /**
     * Tampilkan form edit user berdasarkan ID.
     */
    public function edit(int $id): View
    {
        $user = User::findOrFail($id);

        return view('users.edit', compact('user'));
    }

    /**
     * Perbarui data user berdasarkan ID.
     * Password hanya di-hash jika input password disertakan.
     */
    public function update(UserUpdateRequest $request, int $id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $data = $request->validated();

        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui');
    }

    /**
     * Hapus user berdasarkan ID.
     */
    public function destroy(int $id, Request $request): RedirectResponse
    {
        $user = User::findOrFail($id);

        // validasi tidak bisa menghapus diri sendiri
        $loggedInEmail = $request->session()->get('user_email');
        if ($user->email === $loggedInEmail) {
            return redirect()->route('users.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri');
        }

        // hapus token user bila ada
        $user->tokens()->delete();

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus');
    }
}
