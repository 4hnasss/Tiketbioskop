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

public function buatPembayaran(Request $request)
{
    try {
        Log::info('=== Buat Pembayaran Request ===');
        Log::info('Request Data:', $request->all());

        $validated = $request->validate([
            'kursi' => 'required|array|min:1',
            'kursi.*' => 'required|string',
            'jadwal_id' => 'required|integer|exists:jadwals,id',
            'hargaPerKursi' => 'required|numeric|min:0',
        ]);

        if (!auth()->check()) {
            return response()->json([
                'success' => false, 
                'message' => 'Anda harus login terlebih dahulu.'
            ], 401);
        }

        $kursi = $validated['kursi'];
        $jadwalId = $validated['jadwal_id'];
        $hargaPerKursi = $validated['hargaPerKursi'];

        // ✅ CEK: Pastikan kursi masih tersedia
        foreach ($kursi as $nomorKursi) {
            $kursiCheck = Kursi::where('jadwal_id', $jadwalId)
                ->where('nomorkursi', $nomorKursi)
                ->first();
            
            if (!$kursiCheck || $kursiCheck->status !== 'tersedia') {
                return response()->json([
                    'success' => false,
                    'message' => "Kursi {$nomorKursi} sudah tidak tersedia. Silakan pilih kursi lain."
                ], 400);
            }
        }

        $jadwal = Jadwal::with(['film', 'studio'])->find($jadwalId);
        if (!$jadwal) {
            return response()->json([
                'success' => false, 
                'message' => 'Jadwal tidak ditemukan.'
            ], 404);
        }

        $totalHarga = count($kursi) * $hargaPerKursi;

        // ✅ Simpan transaksi
        $transaksi = Transaksi::create([
            'user_id' => auth()->id(),
            'jadwal_id' => $jadwalId,
            'kursi' => $kursi,
            'totalharga' => $totalHarga,
            'status' => 'pending',
            'tanggaltransaksi' => now(),
        ]);

        Log::info('Transaksi Created:', ['id' => $transaksi->id]);

        // ✅ UPDATE: Set status kursi jadi "dipesan" (reserved)
        foreach ($kursi as $nomorKursi) {
            Kursi::where('jadwal_id', $jadwalId)
                ->where('nomorkursi', $nomorKursi)
                ->update(['status' => 'dipesan']);
            
            Log::info('Kursi reserved:', [
                'nomor' => $nomorKursi,
                'status' => 'dipesan'
            ]);
        }

        // ✅ Konfigurasi Midtrans
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $orderId = 'FLX-' . $transaksi->id . '-' . time();
        
        Log::info('Generating Midtrans Token with Order ID:', ['order_id' => $orderId]);

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $totalHarga,
            ],
            'customer_details' => [
                'first_name' => auth()->user()->name ?? 'Guest',
                'email' => auth()->user()->email ?? 'guest@example.com',
            ],
            'item_details' => [
                [
                    'id' => 'TICKET-' . $jadwalId,
                    'price' => (int) $hargaPerKursi,
                    'quantity' => count($kursi),
                    'name' => $jadwal->film->judul ?? 'Movie Ticket',
                ]
            ],
        ];

        $snapToken = \Midtrans\Snap::getSnapToken($params);
        
        Log::info('Snap Token Generated:', ['token' => substr($snapToken, 0, 20) . '...']);

        $transaksi->snap_token = $snapToken;
        $transaksi->save();

        Log::info('=== Pembayaran Berhasil ===');

        return response()->json([
            'success' => true,
            'transaksiId' => $transaksi->id,
            'snapToken' => $snapToken,
            'message' => 'Transaksi berhasil dibuat'
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        Log::error('Validation Error:', $e->errors());
        return response()->json([
            'success' => false,
            'message' => 'Data tidak valid',
            'errors' => $e->errors()
        ], 422);

    } catch (\Exception $e) {
        Log::error('=== Error Buat Pembayaran ===');
        Log::error('Error Message: ' . $e->getMessage());
        Log::error('Error Trace: ' . $e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}

public function show($id)
{
    try {
        $transaksi = Transaksi::find($id);
        
        if (!$transaksi) {
            Log::error('Transaksi not found: ' . $id);
            return redirect()->route('transaksi.riwayat')
                ->with('error', 'Transaksi tidak ditemukan');
        }
        
        $transaksi->load(['jadwal.film', 'jadwal.studio', 'user']);
        
        Log::info('=== Transaksi Show ===', [
            'id' => $transaksi->id,
            'status' => $transaksi->status,
            'snap_token' => $transaksi->snap_token ? 'exists' : 'null'
        ]);
        
        // ✅ HANYA regenerate jika BENAR-BENAR tidak ada snap_token
        if (in_array($transaksi->status, ['pending', 'challenge']) && empty($transaksi->snap_token)) {
            try {
                \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
                \Midtrans\Config::$isProduction = false;
                \Midtrans\Config::$isSanitized = true;
                \Midtrans\Config::$is3ds = true;
                
                // ✅ PENTING: Order ID HARUS UNIK dengan timestamp baru
                $orderId = 'FLX-' . $transaksi->id . '-' . time();
                
                Log::info('Regenerating snap token with order_id: ' . $orderId);
                
                $params = [
                    'transaction_details' => [
                        'order_id' => $orderId, // ✅ Unique dengan timestamp
                        'gross_amount' => (int) $transaksi->totalharga,
                    ],
                    'customer_details' => [
                        'first_name' => $transaksi->user->name ?? 'Guest',
                        'email' => $transaksi->user->email ?? 'guest@example.com',
                    ],
                ];
                
                $snapToken = \Midtrans\Snap::getSnapToken($params);
                $transaksi->snap_token = $snapToken;
                $transaksi->save();
                
                Log::info('Snap token regenerated successfully');
            } catch (\Exception $e) {
                Log::error('Failed to regenerate snap token: ' . $e->getMessage());
                // Tampilkan error ke user
                return redirect()->route('transaksi.riwayat')
                    ->with('error', 'Token pembayaran tidak valid. Silakan buat transaksi baru.');
            }
        }
        
        return view('pages.transaksi', compact('transaksi'));
        
    } catch (\Exception $e) {
        Log::error('=== Error in show method ===');
        Log::error('Message: ' . $e->getMessage());
        
        return redirect()->route('transaksi.riwayat')
            ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

public function updateStatus(Request $request, $id)
{
    try {
        $transaksi = Transaksi::findOrFail($id);
        $oldStatus = $transaksi->status;
        $newStatus = $request->status;
        
        $transaksi->status = $newStatus;
        $transaksi->metode_pembayaran = $request->metode_pembayaran ?? null;
        $transaksi->save();

        Log::info('Status transaksi updated', [
            'transaksi_id' => $id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus
        ]);

        // ✅ PENTING: Update status kursi ketika pembayaran settlement
        if ($newStatus === 'settlement' && $oldStatus !== 'settlement') {
            $this->updateKursiStatus($transaksi, 'terjual');
            Log::info('Kursi status updated to terjual', [
                'transaksi_id' => $id,
                'kursi' => $transaksi->kursi
            ]);
        }
        
        // ✅ Jika transaksi dibatalkan, kembalikan kursi jadi tersedia
        if ($newStatus === 'batal' && in_array($oldStatus, ['pending', 'challenge'])) {
            $this->updateKursiStatus($transaksi, 'tersedia');
            Log::info('Kursi status updated to tersedia (cancelled)', [
                'transaksi_id' => $id,
                'kursi' => $transaksi->kursi
            ]);
        }

        return response()->json(['success' => true]);
        
    } catch (\Exception $e) {
        Log::error('Error updating status: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

/**
 * Helper function untuk update status kursi
 */
private function updateKursiStatus($transaksi, $status)
{
    try {
        $kursiList = is_array($transaksi->kursi) ? $transaksi->kursi : json_decode($transaksi->kursi, true);
        
        if (!is_array($kursiList)) {
            Log::error('Kursi list is not array', ['kursi' => $transaksi->kursi]);
            return;
        }

        foreach ($kursiList as $nomorKursi) {
            Kursi::where('jadwal_id', $transaksi->jadwal_id)
                ->where('nomorkursi', $nomorKursi)
                ->update(['status' => $status]);
            
            Log::info('Kursi updated', [
                'nomor' => $nomorKursi,
                'jadwal_id' => $transaksi->jadwal_id,
                'status' => $status
            ]);
        }
        
    } catch (\Exception $e) {
        Log::error('Error updating kursi status: ' . $e->getMessage());
    }
}

/**
     * Menampilkan daftar riwayat transaksi user.
     */
public function riwayat()
    {
        $userId = auth()->id();

        $transaksis = Transaksi::with(['jadwal.film', 'jadwal.studio'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
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
