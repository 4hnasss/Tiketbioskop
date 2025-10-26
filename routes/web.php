<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/// ------------------------
// HALAMAN UMUM
// ------------------------
Route::get('/', [UserController::class, 'home'])->name('home');
Route::get('/film', [UserController::class, 'film'])->name('film');
Route::get('/detailfilm/{film}', [UserController::class, 'detailfilm'])->name('film.detailfilm');

// ------------------------
// LOGIN & REGISTER
// ------------------------
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [UserController::class, 'login'])->name('login');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::get('/register', [UserController::class, 'showRegister'])->name('register');
Route::post('/register', [UserController::class, 'register'])->name('register.store');

// ------------------------
// MIDTRANS WEBHOOK (PUBLIC)
// ------------------------
Route::post('/midtrans/webhook', [UserController::class, 'midtransWebhook']);

// ------------------------
// ROUTE HANYA UNTUK USER LOGIN
// ------------------------
Route::middleware('auth')->group(function () {

    // Profil
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');

    // Riwayat transaksi
    Route::get('/transaksi', [UserController::class, 'transaksi'])->name('transaksi');

    // Pilih kursi & studio
    Route::get('/kursi/{film}/{jadwal}', [UserController::class, 'kursi'])->name('kursi');

    // Buat pembayaran (Snap token)
    Route::post('/buat-pembayaran', [UserController::class, 'buatPembayaran']);

    //tiket
    Route::get('/tiket', [UserController::class, 'tiket'])->name('tiket');


});

