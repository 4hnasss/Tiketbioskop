<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class KasirMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login
        if (!Auth::check()) {
            return redirect()->route('login.form')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Cek apakah user adalah kasir
        if (Auth::user()->role === 'kasir') {
            return $next($request);
        }

        // Kalau bukan kasir, redirect ke home
        return redirect()->route('home')
            ->with('error', 'Akses ditolak: Anda tidak memiliki izin untuk mengakses halaman kasir.');
    }
}