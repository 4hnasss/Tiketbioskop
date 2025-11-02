<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Jadwal;
use App\Models\Kursi;
use App\Models\Transaksi;
use App\Models\Keuangan;
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
        
        // Statistik hari ini dari tabel keuangan
        $today = Carbon::today();
        
        // Total transaksi hari ini
        $transaksiHariIni = Keuangan::whereDate('tanggal', $today)->count();
        
        // Total pendapatan hari ini
        $pendapatanHariIni = Keuangan::whereDate('tanggal', $today)
            ->sum('totalpemesanan');
        
        // Tiket terjual hari ini (hitung dari kursi dalam transaksi)
        $tiketTerjualHariIni = Transaksi::whereHas('keuangan', function($q) use ($today) {
                $q->whereDate('tanggal', $today);
            })
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

    /**
     * Halaman Pesan Tiket - Pilih Film
     */
public function pesanTiket()
{
    $today = Carbon::today()->toDateString();

    // Ambil film + jadwal + harga
    $films = Film::where('tanggalmulai', '<=', $today)
        ->where('tanggalselesai', '>=', $today)
        ->with(['jadwal.harga']) // muat relasi harga
        ->orderBy('judul', 'asc')
        ->get();
    
    return view('pesan-tiket', compact('films'));
}


    /**
     * Pilih Jadwal Film
     */
public function pilihJadwal($filmId)
{
    $film = Film::findOrFail($filmId);

    // Ambil semua jadwal film (beserta studio dan harga)
    $jadwals = Jadwal::where('film_id', $filmId)
        ->where('tanggal', '>=', Carbon::today())
        ->with(['studio', 'harga'])
        ->orderBy('tanggal', 'asc')
        ->orderBy('jamtayang', 'asc')
        ->get();

    // Ambil list harga dari relasi harga di jadwal
    $hargaList = $jadwals->pluck('harga.harga')->filter();

    // Hitung harga min dan max
    $hargaMin = $hargaList->min();
    $hargaMax = $hargaList->max();

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
        
        // Validasi input
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
            'metode_pembayaran' => $transaksi->metode_pembayaran
        ]);

        // Update status kursi ketika pembayaran settlement
        if ($newStatus === 'settlement' && $oldStatus !== 'settlement') {
            $kursiList = is_array($transaksi->kursi) ? $transaksi->kursi : json_decode($transaksi->kursi, true);
            
            foreach ($kursiList as $nomorKursi) {
                Kursi::where('jadwal_id', $transaksi->jadwal_id)
                    ->where('nomorkursi', $nomorKursi)
                    ->update(['status' => 'terjual']);
            }
            
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
        Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

public function riwayatTransaksi(Request $request)
{
    // Ambil input filter
    $search  = $request->input('search');
    $tanggal = $request->input('tanggal');
    $status  = $request->input('status');

    // Query dasar: ambil semua transaksi + relasi
    $query = \App\Models\Transaksi::with(['jadwal.film', 'jadwal.studio', 'user'])
        ->orderBy('created_at', 'desc');

    // Filter: pencarian (ID / nama user)
    if (!empty($search)) {
        $query->where(function ($q) use ($search) {
            $q->where('id', 'like', "%{$search}%")
              ->orWhereHas('user', function ($q2) use ($search) {
                  $q2->where('name', 'like', "%{$search}%")
                     ->orWhere('email', 'like', "%{$search}%");
              });
        });
    }

    // Filter: tanggal transaksi
    if (!empty($tanggal)) {
        $query->whereDate('created_at', $tanggal);
    }

    // Filter: status pembayaran
    if (!empty($status)) {
        $query->where('status', $status);
    }

    // Ambil hasil
    $transaksis = $query->paginate(10)->withQueryString();

    // Kirim ke view
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

        // Pendapatan per hari dari tabel keuangan
        $pendapatanPerHari = Keuangan::selectRaw('DATE(tanggal) as tanggal, SUM(totalpemesanan) as total, COUNT(*) as jumlah_transaksi')
            ->whereBetween('tanggal', [$tanggalMulai->startOfDay(), $tanggalSelesai->endOfDay()])
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'desc')
            ->get();

        // Total keseluruhan
        $totalPendapatan = $pendapatanPerHari->sum('total');
        $totalTransaksi = $pendapatanPerHari->sum('jumlah_transaksi');

        // Film terlaris (dari transaksi yang ada di keuangan)
        $filmTerlaris = Transaksi::selectRaw('jadwal_id, COUNT(*) as jumlah_transaksi, SUM(totalharga) as total_pendapatan')
            ->with(['jadwal.film'])
            ->whereHas('keuangan', function($q) use ($tanggalMulai, $tanggalSelesai) {
                $q->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);
            })
            ->where('status', 'settlement')
            ->groupBy('jadwal_id')
            ->orderBy('jumlah_transaksi', 'desc')
            ->limit(10)
            ->get();

        // Metode pembayaran
        $metodePembayaran = Transaksi::selectRaw('metode_pembayaran, COUNT(*) as jumlah, SUM(totalharga) as total')
            ->whereHas('keuangan', function($q) use ($tanggalMulai, $tanggalSelesai) {
                $q->whereBetween('tanggal', [$tanggalMulai, $tanggalSelesai]);
            })
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