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

        // Film yang sedang tayang (sama dengan UserController)
        $films = Film::where('tanggalmulai', '<=', $today)
            ->where('tanggalselesai', '>=', $today)
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
        
        // Ambil jadwal yang masih available
        $jadwals = Jadwal::where('film_id', $filmId)
            ->where('tanggal', '>=', Carbon::today())
            ->with(['studio', 'harga'])
            ->orderBy('tanggal', 'asc')
            ->orderBy('jamtayang', 'asc')
            ->get();
        
        return view('pilih-jadwal', compact('film', 'jadwals'));
    }

    /**
     * Pilih Kursi (sama dengan UserController)
     */
public function pilihkursi($film_id, $jadwal_id)
{
    $film = Film::findOrFail($film_id);
    $jadwal = Jadwal::with(['film', 'harga', 'studio'])->findOrFail($jadwal_id);

    $kursi = Kursi::where('jadwal_id', $jadwal_id)
        ->where('studio_id', $jadwal->studio_id)
        ->get();

    $hargaPerKursi = $jadwal->harga->harga ?? 20000;

    return view('pilih-kursi', compact('film', 'jadwal', 'kursi', 'hargaPerKursi'));
}

    /**
     * Proses Pemesanan Tiket oleh Kasir (Pembayaran Cash)
     */
    public function prosesBooking(Request $request)
    {
        try {
            Log::info('=== Kasir Proses Booking ===');
            Log::info('Request Data:', $request->all());

            $validated = $request->validate([
                'film_id' => 'required|exists:films,id',
                'jadwal_id' => 'required|exists:jadwals,id',
                'kursi' => 'required|array|min:1',
                'kursi.*' => 'required|string',
                'hargaPerKursi' => 'required|numeric|min:0',
                'nama_pemesan' => 'required|string|max:255',
                'email_pemesan' => 'nullable|email',
                'no_hp_pemesan' => 'required|string|max:20',
            ]);

            DB::beginTransaction();

            $kursiList = $validated['kursi'];
            $jadwalId = $validated['jadwal_id'];
            $hargaPerKursi = $validated['hargaPerKursi'];

            // ✅ CEK: Pastikan kursi masih tersedia
            foreach ($kursiList as $nomorKursi) {
                $kursiCheck = Kursi::where('jadwal_id', $jadwalId)
                    ->where('nomorkursi', $nomorKursi)
                    ->first();
                
                if (!$kursiCheck || $kursiCheck->status !== 'tersedia') {
                    DB::rollBack();
                    return back()->with('error', "Kursi {$nomorKursi} sudah tidak tersedia. Silakan pilih kursi lain.");
                }
            }

            $jadwal = Jadwal::with(['film', 'studio'])->findOrFail($jadwalId);
            $totalHarga = count($kursiList) * $hargaPerKursi;

            // ✅ Simpan transaksi (status langsung settlement karena cash)
            $transaksi = Transaksi::create([
                'user_id' => Auth::id(), // Kasir yang input
                'jadwal_id' => $jadwalId,
                'kursi' => $kursiList,
                'totalharga' => $totalHarga,
                'status' => 'settlement', // Langsung settlement untuk cash
                'metode_bayar' => 'cash',
                'tanggaltransaksi' => now(),
            ]);

            Log::info('Transaksi Created:', ['id' => $transaksi->id]);

            // ✅ UPDATE: Set status kursi jadi "terjual" langsung
            foreach ($kursiList as $nomorKursi) {
                Kursi::where('jadwal_id', $jadwalId)
                    ->where('nomorkursi', $nomorKursi)
                    ->update(['status' => 'terjual']);
                
                Log::info('Kursi sold:', [
                    'nomor' => $nomorKursi,
                    'status' => 'terjual'
                ]);
            }

            // ✅ Simpan ke tabel keuangan
            Keuangan::create([
                'transaksi_id' => $transaksi->id,
                'totalpemesanan' => $totalHarga,
                'tanggal' => Carbon::today(),
            ]);

            Log::info('Keuangan Created:', [
                'transaksi_id' => $transaksi->id,
                'total' => $totalHarga
            ]);

            DB::commit();

            return redirect()->route('detail-transaksi', $transaksi->id)
                ->with('success', 'Transaksi berhasil! Tiket telah dicetak.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            Log::error('Validation Error:', $e->errors());
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('=== Error Proses Booking ===');
            Log::error('Error Message: ' . $e->getMessage());
            Log::error('Error Trace: ' . $e->getTraceAsString());
            
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Detail Transaksi
     */
    public function detailTransaksi($id)
    {
        $transaksi = Transaksi::with(['jadwal.film', 'jadwal.studio', 'user'])
            ->findOrFail($id);
        
        // Decode kursi jika masih dalam format JSON string
        $kursiArray = is_array($transaksi->kursi) 
            ? $transaksi->kursi 
            : json_decode($transaksi->kursi, true);
        
        return view('detail-transaksi', compact('transaksi', 'kursiArray'));
    }

    /**
     * Riwayat Transaksi Semua User
     */
    public function riwayatTransaksi(Request $request)
    {
        $query = Transaksi::with(['jadwal.film', 'jadwal.studio', 'user'])
            ->orderBy('created_at', 'desc');

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal')) {
            $query->whereDate('created_at', $request->tanggal);
        }

        // Filter berdasarkan pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQ) use ($search) {
                      $userQ->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $transaksis = $query->paginate(20);

        return view('riwayat-transaksi', compact('transaksis'));
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