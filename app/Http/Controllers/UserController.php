<?php

namespace App\Http\Controllers;

use App\Models\film;
use App\Models\harga;
use App\Models\jadwal;
use App\Models\kursi;
use App\Models\tiket;
use App\Models\transaksi;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Str;
use Midtrans\Config as MidtransConfig;
use Carbon\Carbon;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Midtrans\Notification;
use Stringable;

class UserController extends Controller
{
public function home()
{
    $today = Carbon::today();

    // Film yang sedang tayang
    $filmPlayNow = Film::whereDate('tanggalmulai', '<=', $today)
                        ->orderBy('tanggalmulai', 'desc')
                        ->take(8)
                        ->get();

    // Film yang akan tayang
    $filmUpcoming = Film::whereDate('tanggalmulai', '>', $today)
                        ->orderBy('tanggalmulai', 'asc')
                        ->take(8)
                        ->get();

    // Film acak untuk scroll banner
    $filmRandom = Film::inRandomOrder()->take(10)->get();

    // Kirim semua ke view utama
    return view('pages.home', compact('filmPlayNow', 'filmUpcoming', 'filmRandom'));
    }

        public function film()
        {
 $today = now()->toDateString();

    $filmPlayNow = Film::where('tanggalmulai', '<=', $today)
                        ->where('tanggalselesai', '>=', $today)
                        ->with('jadwals')
                        ->get();

    $filmUpcoming = Film::where('tanggalmulai', '>', $today)
                         ->with('jadwals')
                         ->get();

            return view('pages.film', compact('filmPlayNow', 'filmUpcoming'));
        }


public function detailfilm(Film $film, Request $request)
{
    // Ambil tanggal dari query, atau default hari ini
    $tanggal = $request->query('tanggal', Carbon::today()->toDateString());

    // Ambil semua jadwal film ini dan kelompokkan berdasarkan tanggal
    $jadwals = Jadwal::where('film_id', $film->id)
        ->orderBy('tanggal')
        ->orderBy('jamtayang')
        ->get()
        ->groupBy('tanggal');

    // Kirim data ke view
    return view('pages.detailfilm', [
        'film' => $film,
        'jadwals' => $jadwals,
        'tanggal' => $tanggal, // penting: dikirim ke Blade
    ]);
}

    public function showRegister()
    {
        return view('auth.registrasi');
    }

    public function register(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'nohp' => 'nullable|string|max:20',
        ]);

        // Simpan ke database
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nohp' => $request->nohp,
        ]);

        // Redirect setelah register
        return redirect('/login')->with('success', 'Akun berhasil dibuat! Silakan login.');
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

    public function profile()
    {
        // Ambil data user yang sedang login
        $user = Auth::user();

        // Kirim data user ke view
        return view('pages.profile', compact('user'));
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
// ========================== 
// PILIH KURSI 
// ========================== 
public function Kursi($jadwalId)
{
    $jadwal = Jadwal::with(['film', 'studio'])->findOrFail($jadwalId);
    $kursi = Kursi::where('jadwal_id', $jadwalId)->orderBy('nomorkursi')->get();
    $hargaPerKursi = 45000;

    return view('pages.kursi', compact('jadwal', 'kursi', 'hargaPerKursi'));
}

public function buatPembayaran(Request $request)
    {
        $kursi = $request->kursi;
        $hargaPerKursi = $request->hargaPerKursi;
        $jadwalId = $request->jadwal_id;

        $totalHarga = count($kursi) * $hargaPerKursi;

        // Simpan transaksi ke database
        $transaksi = Transaksi::create([
            'user_id' => auth()->id(),
            'jadwal_id' => $jadwalId,
            'kursi' => json_encode($kursi),
            'total' => $totalHarga,
            'status' => 'pending',
            'tanggaltransaksi' => now(),
        ]);

        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.serverKey');
        Config::$isProduction = false;
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => 'TICKETLY-' . $transaksi->id,
                'gross_amount' => $totalHarga,
            ],
            'customer_details' => [
                'email' => auth()->user()->email ?? 'user@example.com',
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        // Simpan Snap Token
        $transaksi->update([
            'snap_token' => $snapToken,
            'order_id' => $params['transaction_details']['order_id'],
        ]);

        return response()->json([
            'transaksiId' => $transaksi->id,
            'snapToken' => $snapToken
        ]);
    }

public function show($id)
{
    $transaksi = Transaksi::with(['jadwal.film', 'jadwal.studio'])->findOrFail($id);

    // Ambil kursi mentah
    $kursiRaw = $transaksi->kursi;

    // Deteksi format kursi (bisa array, json, atau string)
    if (empty($kursiRaw)) {
        $nomorkursi = [];
    } elseif (is_array($kursiRaw)) {
        $nomorkursi = $kursiRaw;
    } elseif (is_string($kursiRaw)) {
        $decoded = json_decode($kursiRaw, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $nomorkursi = $decoded;
        } else {
            $nomorkursi = array_map('trim', explode(',', $kursiRaw));
        }
    } else {
        $nomorkursi = [];
    }

    // Pastikan total harga terbaca dari field yang benar
    $totalHarga = $transaksi->total 
        ?? $transaksi->jumlah_bayar 
        ?? $transaksi->harga_total 
        ?? 0;

    return view('pages.transaksi', compact('transaksi', 'nomorkursi', 'totalHarga'));
}






    public function updateStatus(Request $request, $id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $transaksi->update([
            'status' => $request->status,
            'metode_pembayaran' => $request->metode_pembayaran ?? 'unknown',
        ]);

        // Jika sudah settle, ubah status kursi
        if ($request->status === 'settlement') {
            $kursiList = json_decode($transaksi->kursi, true) ?? [];

            Kursi::where('jadwal_id', $transaksi->jadwal_id)
                ->whereIn('nomorkursi', $kursiList)
                ->update(['status' => 'terisi']);
        }

        return response()->json(['success' => true]);
    }





/**
     * Menampilkan daftar riwayat transaksi user.
     */
public function riwayat()
    {
        $userId = auth()->id();

        $transaksis = Transaksi::with(['jadwal.film', 'jadwal.studio'])
            ->where('user_id', $userId)
            ->orderByDesc('created_at')
            ->get();

        return view('pages.riwayat-transaksi', compact('transaksis'));
    }
    // ==========================
    // HALAMAN TIKET (SESUDAH BAYAR)
    // ==========================
    public function tiket()
    {
        return view('pages.tiket');
    }

}
