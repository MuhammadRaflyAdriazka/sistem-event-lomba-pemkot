<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Menangani permintaan yang masuk.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles Daftar peran yang diizinkan.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Cek dulu, apakah pengguna sudah login?
        if (!Auth::check()) {
            // Jika belum, lempar ke halaman login.
            return redirect('login');
        }

        // Ambil peran dari pengguna yang sedang login.
        $userRole = Auth::user()->peran;

        // Cek apakah peran pengguna ada di dalam daftar peran yang diizinkan ($roles).
        if (in_array($userRole, $roles)) {
            // Jika cocok, izinkan pengguna melanjutkan ke halaman yang dituju.
            return $next($request);
        }

        // Jika tidak cocok, tolak akses dan tampilkan halaman error 403.
        abort(403, 'AKSES DITOLAK: ANDA TIDAK MEMILIKI WEWENANG.');
    }
}