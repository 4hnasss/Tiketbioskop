<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\KasirController;
use Illuminate\Support\Facades\Route;

// ------------------------
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
// ROUTE HANYA UNTUK USER LOGIN
// ------------------------
Route::middleware('auth')->group(function () {
    Route::get('/profile', [UserController::class, 'profile'])->name('profile');
    Route::get('/kursi/{film}/{jadwal}', [UserController::class, 'kursi'])->name('kursi');
    Route::post('/buat-pembayaran', [UserController::class, 'buatPembayaran'])->name('buat.pembayaran');
    Route::get('/transaksi/{id}', [UserController::class, 'show'])->name('transaksi.show');
    Route::post('/transaksi/{id}/update-status', [UserController::class, 'updateStatus'])->name('transaksi.update');
    Route::get('/riwayat-transaksi', [UserController::class, 'riwayat'])->name('transaksi.riwayat');
    Route::get('/tiket/{transaksiId}', [UserController::class, 'tiket'])->name('tiket');
});

// ------------------------
// ROUTE KHUSUS ADMIN
// ------------------------

// Redirect /admin ke Filament dashboard dengan middleware
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::redirect('/admin', config('filament.path'))->name('admin.dashboard');
});



// ------------------------
// ROUTE KHUSUS KASIR
// ------------------------
Route::middleware(['auth', 'kasir'])->prefix('kasir')->group(function () {
    Route::get('/welcome', [KasirController::class, 'welcome'])->name('kasir.welcome');
    // Pesan Tiket
    Route::get('/pesan-tiket', [KasirController::class, 'pesanTiket'])->name('kasir.pesan-tiket');
    Route::get('/pesan-tiket/jadwal/{film}', [KasirController::class, 'pilihJadwal'])->name('kasir.pilih-jadwal');
    Route::get('/pesan-tiket/kursi/{film}/{jadwal}', [KasirController::class, 'pilihKursi'])->name('pilih-kursi');
    Route::post('/pesan-tiket/proses', [KasirController::class, 'prosesBooking'])->name('proses-booking');
    
    // Transaksi
    Route::get('/transaksi-kasir/{id}', [KasirController::class, 'detailTransaksi'])->name('transaksi-kasir');
    Route::post('/transaksi-kasir/{id}/pembayaran-cash', [KasirController::class, 'prosesPembayaranCash'])->name('pembayaran-cash');
    Route::post('/transaksi-kasir/{id}/update-status', [KasirController::class, 'updateStatusPembayaran'])->name('update-status-pembayaran');
    
    Route::get('/kasir/riwayat-transaksi', [KasirController::class, 'riwayatTransaksi'])
        ->name('riwayat-transaksi');

    // Detail Transaksi (READ ONLY)
    Route::get('/detail-transaksi/{id}', [KasirController::class, 'showDetailTransaksi'])->name('detail-transaksi');


    // Laporan Keuangan
    Route::get('/laporan-keuangan', [KasirController::class, 'laporanKeuangan'])->name('kasir.laporan-keuangan');
});