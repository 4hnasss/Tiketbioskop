<?php

namespace App\Http\Controllers;

use App\Models\film;
use App\Models\jadwal;
use App\Models\kursi;
use App\Models\Tiket;
use App\Models\transaksi;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    // ✅ WAKTU TIMEOUT PEMBAYARAN (dalam menit)
    const PAYMENT_TIMEOUT_MINUTES = 1;

    public function home()
    {
        $today = Carbon::today();

        $filmPlayNow = Film::whereHas('jadwal', function($query) use ($today) {
            $query->whereDate('tanggal', $today);
        })
        ->with(['jadwal' => function($query) use ($today) {
            $query->whereDate('tanggal', $today)
                  ->orderBy('jamtayang', 'asc');
        }])
        ->orderBy('created_at', 'desc')
        ->take(8)
        ->get();

        $filmUpcoming = Film::whereHas('jadwal', function($query) use ($today) {
            $query->whereDate('tanggal', '>', $today);
        })
        ->whereDoesntHave('jadwal', function($query) use ($today) {
            $query->whereDate('tanggal', $today);
        })
        ->with(['jadwal' => function($query) use ($today) {
            $query->whereDate('tanggal', '>', $today)
                  ->orderBy('tanggal', 'asc')
                  ->orderBy('jamtayang', 'asc')
                  ->limit(3);
        }])
        ->orderBy('created_at', 'desc')
        ->take(8)
        ->get();

        $filmRandom = Film::inRandomOrder()->take(10)->get();

        return view('pages.home', compact('filmPlayNow', 'filmUpcoming', 'filmRandom'));
    }

    public function film()
    {
        $today = Carbon::today();
        
        $filmPlayNow = Film::whereHas('jadwal', function($query) use ($today) {
            $query->whereDate('tanggal', $today);
        })
        ->with(['jadwal' => function($query) use ($today) {
            $query->whereDate('tanggal', $today)
                  ->orderBy('jamtayang', 'asc');
        }])
        ->get();
        
        $filmUpcoming = Film::whereHas('jadwal', function($query) use ($today) {
            $query->whereDate('tanggal', '>', $today);
        })
        ->whereDoesntHave('jadwal', function($query) use ($today) {
            $query->whereDate('tanggal', $today);
        })
        ->with(['jadwal' => function($query) use ($today) {
            $query->whereDate('tanggal', '>', $today)
                  ->orderBy('tanggal', 'asc')
                  ->orderBy('jamtayang', 'asc')
                  ->limit(3);
        }])
        ->get();

        return view('pages.film', compact('filmPlayNow', 'filmUpcoming'));
    }

    public function detailfilm($id, Request $request)
    {
        $film = Film::findOrFail($id);
        $today = Carbon::today()->toDateString();
        
        $tanggal = $request->input('tanggal', $today);
        
        if ($tanggal < $today) {
            $tanggal = $today;
        }
        
        $jadwals = $film->jadwal()
            ->whereDate('tanggal', $tanggal)
            ->with(['studio', 'harga'])
            ->orderBy('jamtayang', 'asc')
            ->get()
            ->groupBy(function($item) {
                return Carbon::parse($item->tanggal)->format('Y-m-d');
            });

        return view('pages.detailfilm', compact('film', 'jadwals', 'tanggal'));
    }

    public function showRegister()
    {
        return view('auth.registrasi');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'nohp' => 'nullable|string|max:20',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'nohp' => $request->nohp,
        ]);

        return redirect('/login')->with('success', 'Akun berhasil dibuat! Silakan login.');
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            switch ($user->role) {
                case 'admin':
                    return redirect()->intended('/admin');
                
                case 'kasir':
                    return redirect()->intended('/kasir/welcome');
                
                case 'owner':
                    return redirect()->intended('/owner/dashboard');
                default:
                    return redirect()->intended('/');
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput();
    }

    public function profile()
    {
        $user = Auth::user();
        return view('pages.profile', compact('user'));
    }

    public function logout(Request $request)
    {
        // ✅ PENTING: Cancel semua transaksi pending yang expired milik user sebelum logout
        try {
            $userId = auth()->id();
            
            if ($userId) {
                Log::info('Checking expired transactions before logout', ['user_id' => $userId]);
                
                // Cari transaksi pending yang expired
                $expiredTransactions = Transaksi::where('user_id', $userId)
                    ->where('status', 'pending')
                    ->whereNotNull('payment_expired_at')
                    ->where('payment_expired_at', '<', now())
                    ->get();
                
                foreach ($expiredTransactions as $transaksi) {
                    Log::info('Cancelling expired transaction on logout', [
                        'user_id' => $userId,
                        'transaksi_id' => $transaksi->id
                    ]);
                    
                    $this->cancelExpiredTransaction($transaksi);
                }
                
                if ($expiredTransactions->count() > 0) {
                    Log::info('Cancelled expired transactions on logout', [
                        'user_id' => $userId,
                        'count' => $expiredTransactions->count()
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error cancelling expired transactions on logout: ' . $e->getMessage());
            // Tetap lanjutkan logout meskipun ada error
        }
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }

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

            // ✅ Simpan transaksi dengan waktu expired
            $transaksi = Transaksi::create([
                'user_id' => auth()->id(),
                'jadwal_id' => $jadwalId,
                'kursi' => $kursi,
                'totalharga' => $totalHarga,
                'status' => 'pending',
                'tanggaltransaksi' => now(),
                'payment_expired_at' => now()->addMinutes(self::PAYMENT_TIMEOUT_MINUTES),
            ]);

            Log::info('Transaksi Created:', [
                'id' => $transaksi->id,
                'expired_at' => $transaksi->payment_expired_at
            ]);

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
                'expiredAt' => $transaksi->payment_expired_at->toIso8601String(),
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
            $transaksi = Transaksi::with(['jadwal.film', 'jadwal.studio', 'user'])->find($id);
            
            if (!$transaksi) {
                Log::error('Transaksi not found: ' . $id);
                return redirect()->route('transaksi.riwayat')
                    ->with('error', 'Transaksi tidak ditemukan');
            }
            
            // ✅ CRITICAL: CEK TIMEOUT PEMBAYARAN SEBELUM APAPUN
            if ($transaksi->status === 'pending' && $transaksi->payment_expired_at) {
                if (now()->greaterThanOrEqualTo($transaksi->payment_expired_at)) {
                    Log::info('Payment timeout detected - Auto cancelling', [
                        'transaksi_id' => $transaksi->id,
                        'expired_at' => $transaksi->payment_expired_at,
                        'current_time' => now()
                    ]);
                    
                    // Auto-cancel transaksi
                    $this->cancelExpiredTransaction($transaksi);
                    
                    // IMPORTANT: Redirect ke riwayat dengan pesan error
                    return redirect()->route('transaksi.riwayat')
                        ->with('error', 'Waktu pembayaran telah habis. Silakan buat transaksi baru.');
                }
            }
            
            Log::info('=== Transaksi Show ===', [
                'id' => $transaksi->id,
                'status' => $transaksi->status,
                'snap_token' => $transaksi->snap_token ? 'exists' : 'null',
                'expired_at' => $transaksi->payment_expired_at,
                'is_expired' => $transaksi->payment_expired_at ? now()->greaterThanOrEqualTo($transaksi->payment_expired_at) : false
            ]);
            
            // ✅ Jika sudah expired, redirect
            if ($transaksi->status === 'expired') {
                return redirect()->route('transaksi.riwayat')
                    ->with('error', 'Transaksi ini telah kadaluarsa. Silakan buat transaksi baru.');
            }
            
            // ✅ HANYA regenerate jika status pending/challenge dan tidak ada snap_token
            if (in_array($transaksi->status, ['pending', 'challenge']) && empty($transaksi->snap_token)) {
                try {
                    \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
                    \Midtrans\Config::$isProduction = false;
                    \Midtrans\Config::$isSanitized = true;
                    \Midtrans\Config::$is3ds = true;
                    
                    $orderId = 'FLX-' . $transaksi->id . '-' . time();
                    
                    Log::info('Regenerating snap token with order_id: ' . $orderId);
                    
                    $params = [
                        'transaction_details' => [
                            'order_id' => $orderId,
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
            Log::info('=== Update Status Request ===', [
                'transaksi_id' => $id,
                'request_data' => $request->all()
            ]);

            $transaksi = Transaksi::findOrFail($id);
            $oldStatus = $transaksi->status;
            $newStatus = $request->status;
            
            if (!$newStatus) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status tidak boleh kosong'
                ], 400);
            }

            DB::beginTransaction();
            
            $transaksi->status = $newStatus;
            $transaksi->metode_pembayaran = $request->metode_pembayaran ?? $transaksi->metode_pembayaran;
            $transaksi->save();

            Log::info('Status transaksi updated', [
                'transaksi_id' => $id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus
            ]);

            // ✅ PENTING: Update status kursi ketika pembayaran settlement
            if ($newStatus === 'settlement' && $oldStatus !== 'settlement') {
                $this->updateKursiStatus($transaksi, 'terjual');
                
                // ✅ AUTO GENERATE TIKET PER KURSI
                $this->generateTiket($transaksi);
                
                Log::info('Kursi status updated to terjual & tiket generated', [
                    'transaksi_id' => $id,
                    'kursi' => $transaksi->kursi
                ]);
            }
            
            // ✅ Jika transaksi dibatalkan, kembalikan kursi jadi tersedia
            if (in_array($newStatus, ['batal', 'expired']) && in_array($oldStatus, ['pending', 'challenge', 'dipesan'])) {
                $this->updateKursiStatus($transaksi, 'tersedia');
                Log::info('Kursi status updated to tersedia (cancelled/expired)', [
                    'transaksi_id' => $id,
                    'kursi' => $transaksi->kursi
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'status' => $newStatus,
                'message' => 'Status berhasil diupdate'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating status: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ✅ CEK DAN BATALKAN TRANSAKSI YANG EXPIRED
     */
    public function checkPaymentStatus(Request $request, $id)
    {
        try {
            $transaksi = Transaksi::findOrFail($id);
            
            Log::info('Checking payment status', [
                'transaksi_id' => $id,
                'status' => $transaksi->status,
                'expired_at' => $transaksi->payment_expired_at,
                'now' => now()
            ]);
            
            // Cek apakah pembayaran sudah expired
            if ($transaksi->status === 'pending' && $transaksi->payment_expired_at) {
                if (now()->greaterThanOrEqualTo($transaksi->payment_expired_at)) {
                    
                    Log::info('Payment expired - cancelling now', [
                        'transaksi_id' => $id
                    ]);
                    
                    // Auto-cancel
                    $this->cancelExpiredTransaction($transaksi);
                    
                    return response()->json([
                        'success' => true,
                        'expired' => true,
                        'status' => 'expired',
                        'redirect' => true,
                        'message' => 'Waktu pembayaran telah habis'
                    ]);
                }
                
                // Hitung sisa waktu
                $remainingSeconds = now()->diffInSeconds($transaksi->payment_expired_at, false);
                
                return response()->json([
                    'success' => true,
                    'expired' => false,
                    'remaining_seconds' => max(0, $remainingSeconds),
                    'status' => $transaksi->status
                ]);
            }
            
            return response()->json([
                'success' => true,
                'expired' => false,
                'status' => $transaksi->status
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error checking payment status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * ✅ CANCEL TRANSAKSI YANG EXPIRED
     */
    private function cancelExpiredTransaction($transaksi)
    {
        try {
            DB::beginTransaction();
            
            Log::info('Cancelling expired transaction', [
                'transaksi_id' => $transaksi->id,
                'old_status' => $transaksi->status,
                'expired_at' => $transaksi->payment_expired_at
            ]);
            
            // Update status transaksi
            $transaksi->status = 'expired';
            $transaksi->save();
            
            Log::info('Transaction status updated to expired', [
                'transaksi_id' => $transaksi->id
            ]);
            
            // Kembalikan kursi jadi tersedia
            $this->updateKursiStatus($transaksi, 'tersedia');
            
            Log::info('Seats released to tersedia', [
                'transaksi_id' => $transaksi->id
            ]);
            
            DB::commit();
            
            Log::info('✅ Expired transaction cancelled successfully', [
                'transaksi_id' => $transaksi->id,
                'expired_at' => $transaksi->payment_expired_at
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('❌ Error cancelling expired transaction', [
                'transaksi_id' => $transaksi->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
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

            Log::info('Updating kursi status', [
                'transaksi_id' => $transaksi->id,
                'jadwal_id' => $transaksi->jadwal_id,
                'kursi_list' => $kursiList,
                'new_status' => $status
            ]);

            foreach ($kursiList as $nomorKursi) {
                $kursi = Kursi::where('jadwal_id', $transaksi->jadwal_id)
                    ->where('nomorkursi', $nomorKursi)
                    ->first();
                
                if ($kursi) {
                    $oldStatus = $kursi->status;
                    $kursi->status = $status;
                    $kursi->save();
                    
                    Log::info('✅ Kursi updated', [
                        'nomor' => $nomorKursi,
                        'jadwal_id' => $transaksi->jadwal_id,
                        'old_status' => $oldStatus,
                        'new_status' => $status
                    ]);
                } else {
                    Log::warning('⚠️ Kursi not found', [
                        'nomor' => $nomorKursi,
                        'jadwal_id' => $transaksi->jadwal_id
                    ]);
                }
            }
            
        } catch (\Exception $e) {
            Log::error('Error updating kursi status: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * ✅ AUTO GENERATE TIKET PER KURSI
     */
    private function generateTiket($transaksi)
    {
        try {
            $kursiList = is_array($transaksi->kursi) ? $transaksi->kursi : json_decode($transaksi->kursi, true);
            
            if (!is_array($kursiList)) {
                Log::error('Kursi list is not array', ['kursi' => $transaksi->kursi]);
                return;
            }
            
            foreach ($kursiList as $nomorKursi) {
                $kursi = Kursi::where('jadwal_id', $transaksi->jadwal_id)
                    ->where('nomorkursi', $nomorKursi)
                    ->first();
                
                if ($kursi) {
                    $kodetiket = $this->generateKodeTiket($transaksi->id, $nomorKursi);
                    
                    $existingTiket = Tiket::where('transaksi_id', $transaksi->id)
                        ->where('kursi_id', $kursi->id)
                        ->first();
                    
                    if (!$existingTiket) {
                        Tiket::create([
                            'transaksi_id' => $transaksi->id,
                            'kursi_id' => $kursi->id,
                            'jadwal_id' => $transaksi->jadwal_id,
                            'kodetiket' => $kodetiket
                        ]);
                        
                        Log::info('✅ Tiket berhasil dibuat', [
                            'kodetiket' => $kodetiket,
                            'kursi' => $nomorKursi,
                            'transaksi_id' => $transaksi->id
                        ]);
                    }
                }
            }
            
        } catch (\Exception $e) {
            Log::error('❌ Error generating tiket: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * ✅ Generate Kode Tiket Unik
     */
    private function generateKodeTiket($transaksiId, $nomorKursi)
    {
        $month = date('m');
        $day = date('d');
        
        return "FLX{$month}{$day}-{$transaksiId}-{$nomorKursi}";
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

    public function detailTransaksi($id)
    {
        $userId = auth()->id();

        $transaksi = Transaksi::with(['jadwal.film', 'jadwal.studio', 'tikets.kursi'])
            ->where('user_id', $userId)
            ->where('id', $id)
            ->firstOrFail();

        return view('pages.detail-transaksi', compact('transaksi'));
    }
}