<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
    /**
     * Dashboard Owner - Menampilkan overview transaksi dan chart
     */
    public function dashboard(Request $request)
    {
        // Get filter periode dari request (default: bulan ini)
        $periode = $request->get('periode', 'bulan');
        
        // Statistik Umum
        $totalTransaksi = Transaksi::count();
        $totalPendapatan = Transaksi::where('status', 'settlement')->sum('totalharga');
        $transaksiHariIni = Transaksi::whereDate('tanggaltransaksi', Carbon::today())->count();
        $pendapatanHariIni = Transaksi::whereDate('tanggaltransaksi', Carbon::today())
            ->where('status', 'settlement')
            ->sum('totalharga');
        
        // Transaksi Terbaru (10 terakhir)
        $transaksiTerbaru = Transaksi::with(['user', 'jadwal'])
            ->orderBy('tanggaltransaksi', 'desc')
            ->limit(10)
            ->get();
        
        // Data untuk Chart Berdasarkan Periode
        $chartData = $this->getChartData($periode);
        
        return view('owner.dashboard', compact(
            'totalTransaksi',
            'totalPendapatan',
            'transaksiHariIni',
            'pendapatanHariIni',
            'transaksiTerbaru',
            'chartData',
            'periode'
        ));
    }

    /**
     * Halaman Transaksi - Menampilkan semua transaksi dengan filter
     */
    public function transaksi(Request $request)
    {
        $query = Transaksi::with(['user', 'jadwal']);
        
        // Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        // Filter berdasarkan tanggal
        if ($request->has('tanggal_dari') && $request->tanggal_dari != '') {
            $query->whereDate('tanggaltransaksi', '>=', $request->tanggal_dari);
        }
        
        if ($request->has('tanggal_sampai') && $request->tanggal_sampai != '') {
            $query->whereDate('tanggaltransaksi', '<=', $request->tanggal_sampai);
        }
        
        // Search berdasarkan user atau ID transaksi
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $transaksis = $query->orderBy('tanggaltransaksi', 'desc')->paginate(20);
        
        return view('owner.transaksi', compact('transaksis'));
    }

    /**
     * Detail Transaksi
     */
    public function detailTransaksi($id)
    {
        $transaksi = Transaksi::with(['user', 'jadwal.film', 'tikets'])->findOrFail($id);
        
        return view('owner.detail-transaksi', compact('transaksi'));
    }

    /**
     * Laporan Pendapatan
     */
    public function laporan(Request $request)
    {
        $tahun = $request->get('tahun', Carbon::now()->year);
        $bulan = $request->get('bulan', Carbon::now()->month);
        
        // Pendapatan per hari dalam bulan tertentu
        $pendapatanHarian = Transaksi::select(
                DB::raw('DATE(tanggaltransaksi) as tanggal'),
                DB::raw('SUM(totalharga) as total'),
                DB::raw('COUNT(*) as jumlah_transaksi')
            )
            ->where('status', 'settlement')
            ->whereYear('tanggaltransaksi', $tahun)
            ->whereMonth('tanggaltransaksi', $bulan)
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get();
        
        // Pendapatan per bulan dalam tahun tertentu
        $pendapatanBulanan = Transaksi::select(
                DB::raw('MONTH(tanggaltransaksi) as bulan'),
                DB::raw('SUM(totalharga) as total'),
                DB::raw('COUNT(*) as jumlah_transaksi')
            )
            ->where('status', 'settlement')
            ->whereYear('tanggaltransaksi', $tahun)
            ->groupBy('bulan')
            ->orderBy('bulan', 'asc')
            ->get();
        
        // Pendapatan per tahun
        $pendapatanTahunan = Transaksi::select(
                DB::raw('YEAR(tanggaltransaksi) as tahun'),
                DB::raw('SUM(totalharga) as total'),
                DB::raw('COUNT(*) as jumlah_transaksi')
            )
            ->where('status', 'settlement')
            ->groupBy('tahun')
            ->orderBy('tahun', 'desc')
            ->get();
        
        // Total pendapatan bulan ini
        $totalBulanIni = Transaksi::where('status', 'settlement')
            ->whereYear('tanggaltransaksi', $tahun)
            ->whereMonth('tanggaltransaksi', $bulan)
            ->sum('totalharga');
        
        // Total transaksi bulan ini
        $totalTransaksiBulanIni = Transaksi::where('status', 'settlement')
            ->whereYear('tanggaltransaksi', $tahun)
            ->whereMonth('tanggaltransaksi', $bulan)
            ->count();
        
        return view('owner.laporan', compact(
            'pendapatanHarian',
            'pendapatanBulanan',
            'pendapatanTahunan',
            'tahun',
            'bulan',
            'totalBulanIni',
            'totalTransaksiBulanIni'
        ));
    }

    /**
     * Helper: Get Chart Data berdasarkan periode
     */
    private function getChartData($periode)
    {
        $chartData = [
            'labels' => [],
            'data' => []
        ];
        
        switch ($periode) {
            case 'hari':
                // Data 30 hari terakhir
                for ($i = 29; $i >= 0; $i--) {
                    $date = Carbon::today()->subDays($i);
                    $chartData['labels'][] = $date->format('d M');
                    
                    $pendapatan = Transaksi::whereDate('tanggaltransaksi', $date)
                        ->where('status', 'settlement')
                        ->sum('totalharga');
                    
                    $chartData['data'][] = (float) $pendapatan;
                }
                break;
                
            case 'bulan':
                // Data 12 bulan terakhir
                for ($i = 11; $i >= 0; $i--) {
                    $date = Carbon::now()->subMonths($i);
                    $chartData['labels'][] = $date->format('M Y');
                    
                    $pendapatan = Transaksi::whereYear('tanggaltransaksi', $date->year)
                        ->whereMonth('tanggaltransaksi', $date->month)
                        ->where('status', 'settlement')
                        ->sum('totalharga');
                    
                    $chartData['data'][] = (float) $pendapatan;
                }
                break;
                
            case 'tahun':
                // Data 5 tahun terakhir
                for ($i = 4; $i >= 0; $i--) {
                    $year = Carbon::now()->subYears($i)->year;
                    $chartData['labels'][] = $year;
                    
                    $pendapatan = Transaksi::whereYear('tanggaltransaksi', $year)
                        ->where('status', 'settlement')
                        ->sum('totalharga');
                    
                    $chartData['data'][] = (float) $pendapatan;
                }
                break;
        }
        
        return $chartData;
    }
}