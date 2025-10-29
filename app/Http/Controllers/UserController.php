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
public function kursi($film_id, $jadwal_id) 
{ 
    $film = Film::findOrFail($film_id); 
    $jadwal = Jadwal::with(['film', 'harga', 'studio'])->findOrFail($jadwal_id);
    $kursi = Kursi::where('jadwal_id', $jadwal_id)
        ->where('studio_id', $jadwal->studio_id)
        ->get();

    $hargaPerKursi = $jadwal->harga->harga ?? 20000;

    return view('pages.kursi', compact('film', 'jadwal', 'kursi', 'hargaPerKursi'));
}

// ==========================
// BUAT PEMBAYARAN (DARI HALAMAN KURSI)
// ==========================
public function buatPembayaran(Request $request)
{ 
    $request->validate([ 
        'kursi' => 'required|array', 
        'hargaPerKursi' => 'required|integer', 
        'jadwal_id' => 'required|integer' 
    ]);
    
    $user = Auth::user();
    $jumlahTiket = count($request->kursi);
    $hargaTotal = $jumlahTiket * $request->hargaPerKursi;

    // Cek apakah kursi sudah dipesan sebelumnya (hindari double booking)
    $kursiTerpakai = Kursi::where('jadwal_id', $request->jadwal_id)
        ->whereIn('nomorkursi', $request->kursi)
        ->whereIn('status', ['dipesan', 'terjual'])
        ->exists();

    if ($kursiTerpakai) {
        return response()->json([
            'error' => true,
            'message' => 'Beberapa kursi sudah dipesan atau terjual. Silakan pilih kursi lain.'
        ], 422);
    }

    // 🔹 Pastikan order_id 100% unik (timestamp + random string)
    $orderId = 'ORDER-' . now()->format('YmdHis') . '-' . strtoupper(Str::random(6));

    // 🔹 Setup Midtrans
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
            'first_name' => $user->name,
            'email' => $user->email,
        ],
    ];

    try {
        $snapToken = Snap::getSnapToken($params);

        // 🔹 Simpan transaksi dengan status 'pending' (BUKAN 'success')
        // Status akan otomatis update dari Midtrans callback
        $transaksi = Transaksi::create([
            'order_id' => $orderId,
            'user_id' => $user->id,
            'jadwal_id' => $request->jadwal_id,
            'kursi' => json_encode($request->kursi),
            'status' => 'pending', // 🔹 Default pending, tunggu callback Midtrans
            'totalharga' => $hargaTotal,
            'snap_token' => $snapToken,
            'tanggaltransaksi' => now(),
        ]);

        // 🔹 Ubah status kursi menjadi 'dipesan' (temporary hold)
        Kursi::whereIn('nomorkursi', $request->kursi)
            ->where('jadwal_id', $request->jadwal_id)
            ->update(['status' => 'dipesan']);

        Log::info('Transaksi Created:', [
            'order_id' => $orderId,
            'transaksi_id' => $transaksi->id,
            'status' => 'pending'
        ]);

        return response()->json([
            'success' => true,
            'snapToken' => $snapToken,
            'orderId' => $orderId,
            'transaksiId' => $transaksi->id,
        ]);
    } catch (\Exception $e) {
        Log::error('Midtrans Error: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'Gagal membuat pembayaran: ' . $e->getMessage(),
        ], 500);
    }
}

// ==========================
// UPDATE STATUS TRANSAKSI
// ==========================
public function updateStatus(Request $request, $id)
{
    $transaksi = Transaksi::findOrFail($id);
    // 🔐 Validasi kepemilikan transaksi
    // if ($transaksi->user_id !== Auth::id()) {
    //     return response()->json([
    //         'success' => false,
    //         'message' => 'Akses ditolak'
    //     ], 403);
    // }

    // 🔎 Validasi input
    $request->validate([
        'status' => 'required|string',
        'metode_pembayaran' => 'nullable|string'
    ]);

    // 🔄 Mapping status eksternal ke internal
    $statusInput = strtolower(trim($request->input('status')));
    $mapStatus = [
        'settlement' => 'selesai',
        'capture' => 'selesai',
        'success' => 'selesai',
        'selesai' => 'selesai',
        'pending' => 'pending',
        'batal' => 'batal',
        'cancel' => 'batal',
        'expire' => 'batal',
        'deny' => 'batal',
        'failure' => 'batal'
    ];
    $newStatus = $mapStatus[$statusInput] ?? $statusInput;

    // 🔒 Cegah perubahan status jika sudah selesai
    if ($transaksi->status === 'selesai' && $newStatus !== 'selesai') {
        return response()->json([
            'success' => false,
            'message' => 'Transaksi sudah selesai, tidak bisa diubah',
            'data' => ['status' => $transaksi->status]
        ], 400);
    }

    DB::beginTransaction();
    try {
        $oldStatus = $transaksi->status;

        // 🔧 Update transaksi
        $transaksi->update([
            'status' => $newStatus,
            'metode_pembayaran' => $request->metode_pembayaran ?? $transaksi->metode_pembayaran,
            'updated_at' => now()
        ]);

        // 🎫 Update status kursi
       $kursiList = json_decode($transaksi->kursi, true);

// Pastikan kursiList adalah array
if (!is_array($kursiList) || count($kursiList) === 0) {
    return response()->json([
        'success' => false,
        'message' => 'Data kursi tidak valid atau kosong'
    ], 400);
}

        $kursiStatus = match ($newStatus) {
            'selesai' => 'terjual',
            'pending' => 'dipesan',
            default => 'tersedia'
        };

        $updated = Kursi::whereIn('nomorkursi', $kursiList)
            ->where('jadwal_id', $transaksi->jadwal_id)
            ->update(['status' => $kursiStatus]);

        DB::commit();

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diperbarui',
            'data' => [
                'transaksi_id' => $transaksi->id,
                'order_id' => $transaksi->order_id,
                'old_status' => $oldStatus,
                'new_status' => $transaksi->status,
                'status_label' => [
                    'selesai' => 'Pembayaran Selesai',
                    'pending' => 'Menunggu Pembayaran',
                    'batal' => 'Dibatalkan'
                ][$newStatus] ?? ucfirst($newStatus),
                'metode_pembayaran' => $transaksi->metode_pembayaran,
                'updated_at' => $transaksi->updated_at->format('Y-m-d H:i:s')
            ]
        ]);
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('❌ Error updateStatus:', ['message' => $e->getMessage()]);
        return response()->json([
            'success' => false,
            'message' => 'Gagal memperbarui status: ' . $e->getMessage()
        ], 500);
    }
    Log::info('🔍 Debug UpdateStatus', [
    'transaksi_id' => $transaksi->id,
    'transaksi_user_id' => $transaksi->user_id,
    'auth_id' => Auth::id(),
    'input_status' => $request->status
]);

}

// ==========================
// WEBHOOK MIDTRANS (Callback Notification)
// ==========================


// ==========================
// CEK STATUS TRANSAKSI (untuk popup close)
// ==========================
public function cekStatus(Transaksi $transaksi)
{
    // Validasi ownership
    if ($transaksi->user_id !== Auth::id()) {
        return response()->json([
            'success' => false,
            'message' => 'Akses ditolak'
        ], 403);
    }


    // 🔹 Cek status terbaru dari database (sudah diupdate via webhook)
    // Tidak perlu query ke Midtrans lagi karena webhook sudah handle
    
    Log::info('Checking transaction status:', [
        'transaksi_id' => $transaksi->id,
        'order_id' => $transaksi->order_id,
        'current_status' => $transaksi->status
    ]);

    return response()->json([
        'success' => true,
        'status' => $transaksi->status,
        'metode_pembayaran' => $transaksi->metode_pembayaran,
        'order_id' => $transaksi->order_id,
        'updated_at' => $transaksi->updated_at
    ]);
}

// ==========================
// RIWAYAT TRANSAKSI
// ==========================
public function transaksi()
{
    $transaksis = Transaksi::with(['jadwal.film'])
        ->where('user_id', Auth::id())
        ->orderBy('tanggaltransaksi', 'desc')
        ->get();

    return view('pages.transaksi', compact('transaksis'));
}
    // ==========================
    // HALAMAN TIKET (SESUDAH BAYAR)
    // ==========================
    public function tiket()
    {
        return view('pages.tiket');
    }

}
