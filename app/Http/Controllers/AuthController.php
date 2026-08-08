<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

/**
 * Controller autentikasi web.
 * Menangani tampilan dan proses register, login, dan logout untuk antarmuka web.
 */
class AuthController extends Controller
{
    // Tampilkan form registrasi.
    public function showRegister(): RedirectResponse|View
    {
        if (session()->has('user_email')) {
            return redirect()->route('users.index');
        }

        return view('auth.register');
    }

    /**
     * Proses pendaftaran user baru.
     * Memvalidasi input melalui RegisterRequest dan menyimpan password yang telah di-hash.
     */
    public function register(RegisterRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('login.form')->with('success', 'Registrasi berhasil. Silakan login.');
    }

    /**
     * Tampilkan form login.
     */
    public function showLogin(): RedirectResponse|View
    {
        if (session()->has('user_email')) {
            return redirect()->route('users.index');
        }

        return view('auth.login');
    }

    /**
     * Autentikasi user web.
     * Memeriksa email dan password, lalu menyimpan email user di session saat berhasil.
     */
    public function login(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();
        $user = User::where('email', $credentials['email'])->first();

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors(['login' => 'Email atau password salah.'])->withInput();
        }

        session()->put('user_email', $user->email);

        return redirect()->route('users.index')->with('success', 'Login berhasil.');
    }

    /**
     * Logout user web dan invalidasi session.
     */
    public function logout(Request $request): RedirectResponse
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.form')->with('success', 'Logout berhasil.');
    }
}
