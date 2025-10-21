<?php

namespace App\Http\Controllers;

use App\Models\film;
use App\Models\jadwal;
use App\Models\kursi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function home()
    {
        // Ambil data dari database berdasarkan status
        $filmPlayNow = film::where('status', 'playnow')->get();
        $filmUpcoming = film::where('status', 'upcomming')->get();

        // Kirim ke view
        return view('pages.home', compact('filmPlayNow', 'filmUpcoming'));
    }

    public function film()
    {
        // Ambil data dari database berdasarkan status
        $filmPlayNow = film::where('status', 'playnow')->get();
        $filmUpcoming = film::where('status', 'upcomming')->get();

        // Kirim ke view
        return view('pages.film', compact('filmPlayNow', 'filmUpcoming'));
    }

   public function detailfilm(film $film)
    {
        $jadwals = Jadwal::where('film_id', $film->id)
        ->orderBy('tanggal')
        ->orderBy('jamtayang')
        ->get()
        ->groupBy('tanggal');
        return view('pages.detailfilm', compact('film', 'jadwals'));
    }

    // Menampilkan halaman login
    public function showLoginForm()
    {
        return view('auth.login'); // menyesuaikan folder auth
    }

    // Memproses login
    public function login(Request $request)
    {
        // Validasi input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // supaya aman
            return redirect()->intended('/'); // redirect setelah login sukses
        }

        // Jika gagal login
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput();
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    public function profile(){
        return view('pages.profile');
    }

    public function transaksi(){
        return view('pages.transaksi');
    }

    public function tiket(){
        return view('pages.tiket');
    }

    public function kursi($film_id, $jadwal_id)
    {
        // Ambil data film
        $film = Film::findOrFail($film_id);

        // Ambil data jadwal + relasinya (film, harga, studio)
        $jadwal = Jadwal::with(['film', 'harga', 'studio'])->findOrFail($jadwal_id);

        $kursi = Kursi::where('jadwal_id', $jadwal_id)
              ->where('studio_id', $jadwal->studio_id)
              ->get();


        // Ambil harga dari relasi jadwal->harga
        $hargaPerKursi = $jadwal->harga->harga ?? 20000;

        // Biaya admin bisa tetap (sementara default)
        $biayaAdmin = 5000;

        // Kirim ke view
        return view('pages.kursi', compact('film', 'jadwal', 'kursi', 'hargaPerKursi', 'biayaAdmin'));
    }



}
