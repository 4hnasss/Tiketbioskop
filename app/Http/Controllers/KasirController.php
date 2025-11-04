<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Jadwal;
use App\Models\Kursi;
use App\Models\Transaksi;
use App\Models\Keuangan;
use App\Models\Tiket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class KasirController extends Controller
{
    /**
     * Tampilkan halaman welcome untuk kasir
     */
public function welcome()
{
    $user = Auth::user();
    
    // Statistik hari ini dari tabel transaksi
    $today = Carbon::today();
    
    // Total transaksi hari ini
    $transaksiHariIni = Transaksi::whereDate('tanggaltransaksi', $today)->count();
    
    // Total pendapatan hari ini
    $pendapatanHariIni = Transaksi::whereDate('tanggaltransaksi', $today)
        ->sum('totalharga');
    
    // Tiket terjual hari ini (hitung dari kursi dalam transaksi)
    $tiketTerjualHariIni = Transaksi::whereDate('tanggaltransaksi', $today)
        ->get()
        ->sum(function($transaksi) {
            $kursi = is_array($transaksi->kursi) 
                ? $transaksi->kursi 
                : json_decode($transaksi->kursi, true);
            return is_array($kursi) ? count($kursi) : 0;
        });
    
    return view('welcome', compact(
        'user',
        'transaksiHariIni',
        'pendapatanHariIni',
        'tiketTerjualHariIni'
    ));
}

public function pesanTiket()
    {
        $today = Carbon::today();
        
        // Hanya tampilkan film yang memiliki jadwal hari ini
        $films = Film::whereHas('jadwal', function($query) use ($today) {
            $query->whereDate('tanggal', $today);
        })
        ->with(['jadwal' => function($query) use ($today) {
            $query->whereDate('tanggal', $today)
                  ->with('harga')
                  ->orderBy('jamtayang', 'asc');
        }])
        ->get();

        return view('pesan-tiket', compact('films'));
    }

    public function pilihJadwal($filmId)
    {
        $film = Film::findOrFail($filmId);
        $today = Carbon::today();
        
        // Hanya ambil jadwal hari ini
        $jadwals = $film->jadwal()
            ->whereDate('tanggal', $today)
            ->with(['studio', 'harga'])
            ->orderBy('jamtayang', 'asc')
            ->get();

        // Hitung harga min-max
        $hargaMin = $jadwals->min('harga.harga');
        $hargaMax = $jadwals->max('harga.harga');

        return view('pilih-jadwal', compact('film', 'jadwals', 'hargaMin', 'hargaMax'));
    }

public function pilihKursi(Film $film, Jadwal $jadwal)
{
    $kursi = Kursi::where('jadwal_id', $jadwal->id)
        ->orderBy('nomorkursi')
        ->get();

    // ❌ SALAH - Mengambil dari studio
    // $hargaPerKursi = $jadwal->studio->harga ?? 50000;

    // ✅ BENAR - Mengambil dari relasi harga di jadwal
    $hargaPerKursi = $jadwal->harga->harga ?? 50000;

    return view('pilih-kursi', compact('film', 'jadwal', 'kursi', 'hargaPerKursi'));
}
    public function prosesBooking(Request $request)
    {
        try {
            Log::info('=== Kasir Proses Booking ===');
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

            // Cek ketersediaan kursi
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

            // Simpan transaksi dengan status pending
            $transaksi = Transaksi::create([
                'user_id' => auth()->id(),
                'jadwal_id' => $jadwalId,
                'kursi' => $kursi,
                'totalharga' => $totalHarga,
                'status' => 'pending',
                'tanggaltransaksi' => now(),
            ]);

            Log::info('Transaksi Created:', ['id' => $transaksi->id]);

            // Set status kursi jadi "dipesan" (reserved)
            foreach ($kursi as $nomorKursi) {
                Kursi::where('jadwal_id', $jadwalId)
                    ->where('nomorkursi', $nomorKursi)
                    ->update(['status' => 'dipesan']);
                
                Log::info('Kursi reserved:', [
                    'nomor' => $nomorKursi,
                    'status' => 'dipesan'
                ]);
            }

            Log::info('=== Booking Berhasil ===');

            return response()->json([
                'success' => true,
                'transaksiId' => $transaksi->id,
                'message' => 'Transaksi berhasil dibuat'
            ]);

        } catch (\Exception $e) {
            Log::error('=== Error Proses Booking ===');
            Log::error('Error Message: ' . $e->getMessage());
            Log::error('Error Trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function detailTransaksi($id)
    {
        try {
            $transaksi = Transaksi::with(['jadwal.film', 'jadwal.studio', 'user'])
                ->findOrFail($id);
            
            Log::info('=== Kasir Detail Transaksi ===', [
                'id' => $transaksi->id,
                'status' => $transaksi->status
            ]);

            // Generate snap token jika belum ada dan status masih pending
            if (in_array($transaksi->status, ['pending', 'challenge']) && empty($transaksi->snap_token)) {
                try {
                    \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
                    \Midtrans\Config::$isProduction = false;
                    \Midtrans\Config::$isSanitized = true;
                    \Midtrans\Config::$is3ds = true;
                    
                    $orderId = 'FLX-KASIR-' . $transaksi->id . '-' . time();
                    
                    Log::info('Generating snap token with order_id: ' . $orderId);
                    
                    $params = [
                        'transaction_details' => [
                            'order_id' => $orderId,
                            'gross_amount' => (int) $transaksi->totalharga,
                        ],
                        'customer_details' => [
                            'first_name' => $transaksi->user->name ?? 'Kasir',
                            'email' => $transaksi->user->email ?? 'kasir@flixora.com',
                        ],
                        'item_details' => [
                            [
                                'id' => 'TICKET-' . $transaksi->jadwal_id,
                                'price' => (int) ($transaksi->totalharga / count($transaksi->kursi)),
                                'quantity' => count($transaksi->kursi),
                                'name' => $transaksi->jadwal->film->judul ?? 'Movie Ticket',
                            ]
                        ],
                    ];
                    
                    $snapToken = \Midtrans\Snap::getSnapToken($params);
                    $transaksi->snap_token = $snapToken;
                    $transaksi->save();
                    
                    Log::info('Snap token generated successfully');
                } catch (\Exception $e) {
                    Log::error('Failed to generate snap token: ' . $e->getMessage());
                }
            }
            
            return view('transaksi-kasir', compact('transaksi'));
            
        } catch (\Exception $e) {
            Log::error('=== Error in detailTransaksi ===');
            Log::error('Message: ' . $e->getMessage());
            
            return redirect()->route('pesan-tiket')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

// Update method prosesPembayaranCash
// Update method prosesPembayaranCash
public function prosesPembayaranCash(Request $request, $id)
{
    try {
        $transaksi = Transaksi::findOrFail($id);
        
        if ($transaksi->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi sudah diproses sebelumnya'
            ], 400);
        }

        DB::beginTransaction();

        // Update status transaksi ke settlement
        $transaksi->status = 'settlement';
        $transaksi->metode_pembayaran = 'cash';
        $transaksi->save();

        // Update status kursi jadi terjual
        $kursiList = is_array($transaksi->kursi) ? $transaksi->kursi : json_decode($transaksi->kursi, true);
        
        foreach ($kursiList as $nomorKursi) {
            Kursi::where('jadwal_id', $transaksi->jadwal_id)
                ->where('nomorkursi', $nomorKursi)
                ->update(['status' => 'terjual']);
        }

        // ✅ Generate Tiket untuk setiap kursi
        $this->generateTiket($transaksi);

        DB::commit();

        Log::info('Pembayaran Cash Berhasil', [
            'transaksi_id' => $id,
            'kursi' => $kursiList
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pembayaran berhasil!'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error pembayaran cash: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}

// Update method updateStatusPembayaran
public function updateStatusPembayaran(Request $request, $id)
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
            'new_status' => $newStatus,
        ]);

        // Update status kursi ketika pembayaran settlement
        if ($newStatus === 'settlement' && $oldStatus !== 'settlement') {
            $kursiList = is_array($transaksi->kursi) ? $transaksi->kursi : json_decode($transaksi->kursi, true);
            
            foreach ($kursiList as $nomorKursi) {
                Kursi::where('jadwal_id', $transaksi->jadwal_id)
                    ->where('nomorkursi', $nomorKursi)
                    ->update(['status' => 'terjual']);
            }

            // ✅ Generate Tiket untuk setiap kursi
            $this->generateTiket($transaksi);
            
            Log::info('Kursi status updated to terjual', [
                'transaksi_id' => $id,
                'kursi' => $kursiList
            ]);
        }
        
        // Jika transaksi dibatalkan, kembalikan kursi jadi tersedia
        if ($newStatus === 'batal' && in_array($oldStatus, ['pending', 'challenge'])) {
            $kursiList = is_array($transaksi->kursi) ? $transaksi->kursi : json_decode($transaksi->kursi, true);
            
            foreach ($kursiList as $nomorKursi) {
                Kursi::where('jadwal_id', $transaksi->jadwal_id)
                    ->where('nomorkursi', $nomorKursi)
                    ->update(['status' => 'tersedia']);
            }
            
            Log::info('Kursi status updated to tersedia (cancelled)', [
                'transaksi_id' => $id,
                'kursi' => $kursiList
            ]);
        }

        DB::commit();

        Log::info('✅ Update status berhasil');

        return response()->json([
            'success' => true,
            'message' => 'Status berhasil diupdate'
        ]);
        
    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('❌ Error updating status: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

// ✅ Method untuk Generate Tiket
private function generateTiket($transaksi)
{
    try {
        $kursiList = is_array($transaksi->kursi) ? $transaksi->kursi : json_decode($transaksi->kursi, true);
        
        foreach ($kursiList as $nomorKursi) {
            // Cari kursi_id berdasarkan nomor kursi
            $kursi = Kursi::where('jadwal_id', $transaksi->jadwal_id)
                ->where('nomorkursi', $nomorKursi)
                ->first();
            
            if ($kursi) {
                // Generate kode tiket unik
                $kodetiket = $this->generateKodeTiket($transaksi->id, $nomorKursi);
                
                // Buat tiket baru
                Tiket::create([
                    'transaksi_id' => $transaksi->id,
                    'kursi_id' => $kursi->id,
                    'jadwal_id' => $transaksi->jadwal_id,
                    'kodetiket' => $kodetiket
                ]);
                
                Log::info('Tiket berhasil dibuat', [
                    'kodetiket' => $kodetiket,
                    'kursi' => $nomorKursi
                ]);
            }
        }
    } catch (\Exception $e) {
        Log::error('Error generating tiket: ' . $e->getMessage());
        throw $e;
    }
}

// ✅ Generate Kode Tiket Unik (SHORT VERSION)
private function generateKodeTiket($transaksiId, $nomorKursi)
{
    // Format pendek: FLX-MMDD-TID-KURSI
    // Contoh: FLX-1104-15-A5 (hanya 14 karakter)
    
    $month = date('m'); // 2 digit bulan
    $day = date('d');   // 2 digit tanggal
    $random = strtoupper(substr(md5(uniqid(rand(), true)), 0, 2)); // 2 karakter random
    
    // Format: FLX-MMDD-TID-KURSI-XX
    return "FLX{$month}{$day}-{$transaksiId}-{$nomorKursi}";
}

// ✅ Halaman Riwayat Tiket
public function riwayatTiket(Request $request)
{
    try {
        $search = $request->get('search');
        
        $tikets = Tiket::with(['transaksi.user', 'kursi', 'jadwal.film', 'jadwal.studio'])
            ->when($search, function($query, $search) {
                return $query->where('kodetiket', 'like', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('riwayat-tiket-kasir', compact('tikets', 'search'));
        
    } catch (\Exception $e) {
        Log::error('Error riwayat tiket: ' . $e->getMessage());
        return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

// ✅ Detail Tiket
public function detailTiket($id)
{
    try {
        $tiket = Tiket::with(['transaksi.user', 'kursi', 'jadwal.film', 'jadwal.studio'])
            ->findOrFail($id);
        
        return view('detail-tiket-kasir', compact('tiket'));
        
    } catch (\Exception $e) {
        Log::error('Error detail tiket: ' . $e->getMessage());
        return back()->with('error', 'Tiket tidak ditemukan');
    }
}

public function riwayatTransaksi(Request $request)
{
    $query = Transaksi::with(['user', 'jadwal.film']);
    
    // Filter pencarian (ID atau Nama User)
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('id', 'like', '%' . $search . '%')
              ->orWhereHas('user', function($q2) use ($search) {
                  $q2->where('name', 'like', '%' . $search . '%')
                     ->orWhere('email', 'like', '%' . $search . '%');
              });
        });
    }
    
    // Filter Snap Token
    if ($request->filled('snap_token')) {
        $query->where('snap_token', 'like', '%' . $request->snap_token . '%');
    }
    
    // Filter Status
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }
    
    // Order by terbaru
    $transaksis = $query->orderBy('created_at', 'desc')->paginate(15);
    
    return view('riwayat-transaksi', compact('transaksis'));
}


    /**
 * Show detail transaksi (Read-only view)
 */
public function showDetailTransaksi($id)
{
    try {
        $transaksi = Transaksi::with(['jadwal.film', 'jadwal.studio', 'user'])
            ->findOrFail($id);
        
        Log::info('=== View Detail Transaksi ===', [
            'id' => $transaksi->id,
            'status' => $transaksi->status,
            'user' => $transaksi->user->name ?? 'N/A'
        ]);

        return view('detail-transaksi', compact('transaksi'));
        
    } catch (\Exception $e) {
        Log::error('=== Error in showDetailTransaksi ===');
        Log::error('Message: ' . $e->getMessage());
        
        return redirect()->route('riwayat-transaksi')
            ->with('error', 'Transaksi tidak ditemukan.');
    }
}

    /**
     * Laporan Keuangan (dari tabel keuangan)
     */
    public function laporanKeuangan(Request $request)
{
    // Default: tampilkan data 7 hari terakhir
    $tanggalMulai = $request->filled('tanggal_mulai') 
        ? Carbon::parse($request->tanggal_mulai) 
        : Carbon::today()->subDays(6);
    
    $tanggalSelesai = $request->filled('tanggal_selesai') 
        ? Carbon::parse($request->tanggal_selesai) 
        : Carbon::today();

    // Pendapatan per hari dari tabel transaksi
    $pendapatanPerHari = Transaksi::selectRaw('DATE(tanggaltransaksi) as tanggal, SUM(totalharga) as total, COUNT(*) as jumlah_transaksi')
        ->whereBetween('tanggaltransaksi', [$tanggalMulai->startOfDay(), $tanggalSelesai->endOfDay()])
        ->where('status', 'settlement')
        ->groupBy('tanggal')
        ->orderBy('tanggal', 'desc')
        ->get();

    // Total keseluruhan
    $totalPendapatan = $pendapatanPerHari->sum('total');
    $totalTransaksi = $pendapatanPerHari->sum('jumlah_transaksi');

    // Film terlaris
    $filmTerlaris = Transaksi::selectRaw('jadwal_id, COUNT(*) as jumlah_transaksi, SUM(totalharga) as total_pendapatan')
        ->with(['jadwal.film'])
        ->whereBetween('tanggaltransaksi', [$tanggalMulai->startOfDay(), $tanggalSelesai->endOfDay()])
        ->where('status', 'settlement')
        ->groupBy('jadwal_id')
        ->orderBy('jumlah_transaksi', 'desc')
        ->limit(10)
        ->get();

    // Metode pembayaran
    $metodePembayaran = Transaksi::selectRaw('metode_pembayaran, COUNT(*) as jumlah, SUM(totalharga) as total')
        ->whereBetween('tanggaltransaksi', [$tanggalMulai->startOfDay(), $tanggalSelesai->endOfDay()])
        ->where('status', 'settlement')
        ->groupBy('metode_pembayaran')
        ->get();

    return view('laporan-keuangan', compact(
        'pendapatanPerHari',
        'totalPendapatan',
        'totalTransaksi',
        'filmTerlaris',
        'metodePembayaran',
        'tanggalMulai',
        'tanggalSelesai'
    ));
}
}