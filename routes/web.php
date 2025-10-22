<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Halaman login & registrasi
Route::get('/login', [UserController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [UserController::class, 'login'])->name('login');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');
Route::get('/register', [UserController::class, 'showRegister'])->name('register');
Route::post('/register', [UserController::class, 'register'])->name('register.store');

// Halaman umum
Route::get('/', [UserController::class, 'home'])->name('home');
Route::get('/film', [UserController::class, 'film'])->name('film');
Route::get('/detailfilm/{film}', [UserController::class, 'detailfilm'])->name('film.detailfilm');

// Halaman yang hanya bisa diakses ketika login
Route::middleware('auth')->group(function () {
    Route::get('/kursi/{film}/{jadwal}', [UserController::class, 'kursi'])->name('kursi');
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::get('/transaksi', [UserController::class, 'transaksi'])->name('transaksi');
    Route::post('/midtrans/webhook', [UserController::class, 'midtransWebhook']);
    Route::get('/tiket/{id}', [UserController::class, 'tiket'])->name('tiket.show');
});
