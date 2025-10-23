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
        $film = Film::findOrFail($film_id);
        $jadwal = Jadwal::with(['film', 'harga', 'studio'])->findOrFail($jadwal_id);

        $kursi = Kursi::where('jadwal_id', $jadwal_id)
              ->where('studio_id', $jadwal->studio_id)
              ->get(); // pastikan ambil status terbaru

        $hargaPerKursi = $jadwal->harga->harga ?? 20000;

        return view('pages.kursi', compact('film', 'jadwal', 'kursi', 'hargaPerKursi'));
    }

public function buatPembayaran(Request $request)
{
    $request->validate([
        'kursi' => 'required|array',
        'hargaPerKursi' => 'required|integer',
        'jadwal_id' => 'required|integer'
    ]);

    $jumlahTiket = count($request->kursi);
    $hargaTotal = $jumlahTiket * $request->hargaPerKursi;

    $orderId = 'ORDER-' . uniqid();

    // Midtrans config
    MidtransConfig::$serverKey = config('midtrans.serverKey');
    MidtransConfig::$isProduction = false;
    MidtransConfig::$isSanitized = true;
    MidtransConfig::$is3ds = true;

    $params = [
        'transaction_details' => [
            'order_id' => $orderId,
            'gross_amount' => $hargaTotal,
        ],
        'customer_details' => [
            'first_name' => auth()->user()->name,
            'email' => auth()->user()->email,
        ]
    ];

    $snapToken = Snap::getSnapToken($params);

    // Simpan transaksi sementara
    $transaksi = Transaksi::create([
        'order_id' => $orderId,
        'user_id' => auth()->id(),
        'jadwal_id' => $request->jadwal_id,
        'kursi' => json_encode($request->kursi),
        'status' => 'panding', // set default sebagai pending
        'totalharga' => $hargaTotal,
        'snap_token' => $snapToken,
        'tanggaltransaksi' => Carbon::now()
    ]);

    // Tandai kursi sebagai 'dipesan' sementara
    Kursi::whereIn('nomorkursi', $request->kursi)
        ->where('jadwal_id', $request->jadwal_id)
        ->update(['status' => 'dipesan']);

    return response()->json([
        'snapToken' => $snapToken,
        'orderId' => $orderId
    ]);
}


public function midtransWebhook(Request $request)
{
    \Midtrans\Config::$serverKey = config('midtrans.serverKey');
    \Midtrans\Config::$isProduction = false;
    \Midtrans\Config::$isSanitized = true;
    \Midtrans\Config::$is3ds = true;

    $notif = new \Midtrans\Notification();

    $transaction = $notif->transaction_status; // capture, settlement, pending, deny, expire, cancel
    $order_id = $notif->order_id;
    $fraud = $notif->fraud_status ?? null;

    $transaksi = Transaksi::where('order_id', $order_id)->first();

    if (!$transaksi) {
        Log::warning("Transaksi tidak ditemukan: " . $order_id);
        return response()->json(['message' => 'Order not found'], 404);
    }

    $kursiList = json_decode($transaksi->kursi ?? '[]', true);

    switch ($transaction) {
        case 'capture':
            if ($fraud == 'challenge') {
                $transaksi->status = 'challenge';
            } else {
                $transaksi->status = 'selesai';
                Kursi::whereIn('nomorkursi', $kursiList)
                    ->where('jadwal_id', $transaksi->jadwal_id)
                    ->update(['status' => 'terjual']);
            }
            break;

        case 'settlement':
            $transaksi->status = 'selesai';
            Kursi::whereIn('nomorkursi', $kursiList)
                ->where('jadwal_id', $transaksi->jadwal_id)
                ->update(['status' => 'terjual']);
            break;

        case 'pending':
            $transaksi->status = 'panding';
            Kursi::whereIn('nomorkursi', $kursiList)
                ->where('jadwal_id', $transaksi->jadwal_id)
                ->update(['status' => 'dipesan']);
            break;

        case 'deny':
        case 'expire':
        case 'cancel':
            $transaksi->status = 'batal';
            Kursi::whereIn('nomorkursi', $kursiList)
                ->where('jadwal_id', $transaksi->jadwal_id)
                ->update(['status' => 'tersedia']);
            break;
    }

    $transaksi->save();

    return response()->json(['message' => 'OK']);
}


public function tiket($transaksiId)
{
    $tiket = Tiket::with(['jadwal.film', 'jadwal.studio', 'kursi', 'transaksi'])
        ->where('transaksi_id', $transaksiId)
        ->get();

    if ($tiket->isEmpty()) {
        return redirect()->back()->with('error', 'Tiket tidak ditemukan.');
    }

    return view('pages.tiket', compact('tiket'));
}


}
