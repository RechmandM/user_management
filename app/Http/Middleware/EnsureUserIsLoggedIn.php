<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsLoggedIn
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // Pastikan user sudah login sebelum mengakses route user.
        if (! $request->session()->has('user_email')) {
            return redirect()->route('login.form')->with('error', 'Silakan login terlebih dahulu.');
        }
        return $next($request);
    }
}
