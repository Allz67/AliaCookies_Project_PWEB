<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CekAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login DAN memiliki role 'admin'
        if (Auth::check() && Auth::user()->role === 'admin') {
            return $next($request);
        }

        // PERBAIKAN: Jika customer nyasar, lempar ke beranda toko ('home'), JANGAN ke 'dashboard' lagi!
        return redirect()->route('home')->with('error', 'Maaf, Anda tidak memiliki akses ke halaman Admin.');
    }
}
