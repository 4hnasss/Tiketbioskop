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
use Midtrans\Config as MidtransConfig;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Midtrans\Notification;

class UserController extends Controller
{
    public function home()
    {
        $today = Carbon::today();

        // Film yang sedang tayang
        $filmPlayNow = Film::whereDate('tanggalmulai', '<=', $today)
                            ->orderBy('tanggalmulai', 'desc')
                            ->take(4)
                            ->get();

        // Film yang akan tayang
        $filmUpcoming = Film::whereDate('tanggalmulai', '>', $today)
                            ->orderBy('tanggalmulai', 'asc')
                            ->take(4)
                            ->get();


        return view('pages.home', compact('filmPlayNow', 'filmUpcoming'));
    }

public function film()
{
    // Film yang sedang tayang
    $filmPlayNow = Film::with('jadwals')
        ->where('tanggalmulai', '<=', now())
        ->where('tanggalselesai', '>=', now())
        ->get();

    // Film yang akan tayang
    $filmUpcoming = Film::with('jadwals')
        ->where('tanggalmulai', '>', now())
        ->get();

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

    public function showRegister()
    {
        return view('auth.registrasi'); // arahkan ke file blade kamu
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

    public function transaksi()
{
    // Ambil semua transaksi user yang login beserta tiket dan relasi jadwal & film
    $transaksis = Transaksi::with(['tiket.jadwal.film', 'jadwal.studio'])
                    ->where('user_id', Auth::id())
                    ->orderBy('tanggaltransaksi', 'desc')
                    ->get();

    return view('pages.transaksi', compact('transaksis'));
}


    public function kursi($film_id, $jadwal_id)
{

    // ==== Ambil data film & jadwal ====
    $film = Film::findOrFail($film_id);
    $jadwal = Jadwal::with(['film', 'harga', 'studio'])->findOrFail($jadwal_id);

    // ==== Ambil data kursi ====
    $kursi = Kursi::where('jadwal_id', $jadwal_id)
        ->where('studio_id', $jadwal->studio_id)
        ->get();

    // ==== Ambil harga per kursi ====
    $hargaPerKursi = $jadwal->harga->harga ?? 20000;

    // ==== Konfigurasi Midtrans ====
    MidtransConfig::$serverKey = config('midtrans.serverKey');
    MidtransConfig::$isProduction = false;  // Sandbox mode
    MidtransConfig::$isSanitized = true;
    MidtransConfig::$is3ds = true;

    // ==== Buat transaksi di database ====
    $orderId = 'TCKT-' . uniqid();

    $transaksi = Transaksi::create([
        'user_id' => Auth::id(),
        'jadwal_id' => $jadwal_id,
        'order_id' => $orderId,
        'tanggaltransaksi' => Carbon::now(),
        'totalharga' => $hargaPerKursi,
        'status' => 'panding', // sesuai permintaan
    ]);

    // ==== Parameter untuk Midtrans ====
    $params = [
        'transaction_details' => [
            'order_id' => $orderId,
            'gross_amount' => (int) $transaksi->totalharga,
        ],
        'customer_details' => [
            'name' => Auth::user()->name ?? 'Guest User',
            'email' => Auth::user()->email ?? 'guest@example.com',
        ],
    ];

    // ==== Dapatkan Snap Token dari Midtrans ====
    $snapToken = Snap::getSnapToken($params);

    // ==== Simpan snap token ke database ====
    $transaksi->update(['snap_token' => $snapToken]);

    // ==== Kirim data ke view ====
    return view('pages.kursi', compact('film', 'jadwal', 'kursi', 'hargaPerKursi', 'snapToken'));
}

public function tiket($id)
{
    $tiket = Tiket::with(['jadwal.film', 'jadwal.studio', 'kursi', 'transaksi'])->find($id);

    if (!$tiket) {
        return redirect()->back()->with('error', 'Tiket tidak ditemukan.');
    }

    return view('pages.tiket', compact('tiket'));
}




public function midtransWebhook(Request $request)
{
    Log::info('Webhook Midtrans diterima:', $request->all());

    $serverKey = config('midtrans.serverKey');
    $json = $request->getContent();
    $signatureKey = hash(
        'sha512',
        $request->order_id . $request->status_code . $request->gross_amount . $serverKey
    );

    // Validasi keamanan dari Midtrans
    if ($signatureKey !== $request->signature_key) {
        return response()->json(['message' => 'Invalid signature'], 403);
    }

    // Ambil transaksi berdasarkan order_id
    $transaksi = Transaksi::where('order_id', $request->order_id)->first();

    if (!$transaksi) {
        return response()->json(['message' => 'Transaction not found'], 404);
    }

    // Update status berdasarkan Midtrans
    if ($request->transaction_status === 'capture' || $request->transaction_status === 'settlement') {
        $transaksi->update(['status' => 'success']);
    } elseif ($request->transaction_status === 'pending') {
        $transaksi->update(['status' => 'panding']); // sesuai permintaan
    } elseif ($request->transaction_status === 'deny' || $request->transaction_status === 'cancel') {
        $transaksi->update(['status' => 'failed']);
    }

    return response()->json(['message' => 'Webhook processed successfully']);
}



}
