<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
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

        // Cek apakah user adalah admin
        if (Auth::user()->role === 'admin') {
            return $next($request);
        }

        // Kalau bukan admin, redirect ke home dengan pesan
        return redirect()->route('home')
            ->with('error', 'Akses ditolak: Anda tidak memiliki izin untuk mengakses halaman admin.');
    }
}