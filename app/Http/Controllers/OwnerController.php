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
    
    // Get filter tanggal dari request
    $startDate = $request->get('start_date');
    $endDate = $request->get('end_date');
    
    // Statistik Umum (tidak terpengaruh filter tanggal)
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
    
    // Data untuk Chart Berdasarkan Periode dan Filter Tanggal
    $chartData = $this->getChartData($periode, $startDate, $endDate);
    
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
 * Generate data untuk chart berdasarkan periode dan filter tanggal
 */
private function getChartData($periode, $startDate = null, $endDate = null)
{
    $labels = [];
    $data = [];
    
    // Base query dengan filter status settlement
    $baseQuery = Transaksi::where('status', 'settlement');
    
    // Terapkan filter tanggal jika ada
    if ($startDate) {
        $baseQuery->whereDate('tanggaltransaksi', '>=', $startDate);
    }
    if ($endDate) {
        $baseQuery->whereDate('tanggaltransaksi', '<=', $endDate);
    }
    
    switch ($periode) {
        case 'hari':
            // Grafik per hari (7 hari terakhir atau sesuai range)
            if ($startDate && $endDate) {
                // Jika ada filter tanggal, gunakan range tersebut
                $start = Carbon::parse($startDate);
                $end = Carbon::parse($endDate);
                $days = $start->diffInDays($end) + 1;
                
                // Batasi maksimal 30 hari untuk performa
                if ($days > 30) {
                    $days = 30;
                    $start = $end->copy()->subDays(29);
                }
                
                for ($i = 0; $i < $days; $i++) {
                    $date = $start->copy()->addDays($i);
                    $labels[] = $date->format('d M');
                    
                    $pendapatan = Transaksi::where('status', 'settlement')
                        ->whereDate('tanggaltransaksi', $date)
                        ->sum('totalharga');
                    
                    $data[] = $pendapatan;
                }
            } else {
                // Default: 7 hari terakhir
                for ($i = 6; $i >= 0; $i--) {
                    $date = Carbon::today()->subDays($i);
                    $labels[] = $date->format('d M');
                    
                    $pendapatan = Transaksi::where('status', 'settlement')
                        ->whereDate('tanggaltransaksi', $date)
                        ->sum('totalharga');
                    
                    $data[] = $pendapatan;
                }
            }
            break;
            
        case 'bulan':
            // Grafik per bulan (12 bulan terakhir atau sesuai range)
            if ($startDate && $endDate) {
                $start = Carbon::parse($startDate)->startOfMonth();
                $end = Carbon::parse($endDate)->endOfMonth();
                $months = $start->diffInMonths($end) + 1;
                
                // Batasi maksimal 12 bulan
                if ($months > 12) {
                    $months = 12;
                    $start = $end->copy()->subMonths(11)->startOfMonth();
                }
                
                for ($i = 0; $i < $months; $i++) {
                    $month = $start->copy()->addMonths($i);
                    $labels[] = $month->format('M Y');
                    
                    $pendapatan = Transaksi::where('status', 'settlement')
                        ->whereYear('tanggaltransaksi', $month->year)
                        ->whereMonth('tanggaltransaksi', $month->month)
                        ->sum('totalharga');
                    
                    $data[] = $pendapatan;
                }
            } else {
                // Default: 12 bulan terakhir
                for ($i = 11; $i >= 0; $i--) {
                    $month = Carbon::now()->subMonths($i);
                    $labels[] = $month->format('M Y');
                    
                    $pendapatan = Transaksi::where('status', 'settlement')
                        ->whereYear('tanggaltransaksi', $month->year)
                        ->whereMonth('tanggaltransaksi', $month->month)
                        ->sum('totalharga');
                    
                    $data[] = $pendapatan;
                }
            }
            break;
            
        case 'tahun':
            // Grafik per tahun (5 tahun terakhir atau sesuai range)
            if ($startDate && $endDate) {
                $start = Carbon::parse($startDate)->startOfYear();
                $end = Carbon::parse($endDate)->endOfYear();
                $years = $start->diffInYears($end) + 1;
                
                // Batasi maksimal 10 tahun
                if ($years > 10) {
                    $years = 10;
                    $start = $end->copy()->subYears(9)->startOfYear();
                }
                
                for ($i = 0; $i < $years; $i++) {
                    $year = $start->copy()->addYears($i);
                    $labels[] = $year->format('Y');
                    
                    $pendapatan = Transaksi::where('status', 'settlement')
                        ->whereYear('tanggaltransaksi', $year->year)
                        ->sum('totalharga');
                    
                    $data[] = $pendapatan;
                }
            } else {
                // Default: 5 tahun terakhir
                for ($i = 4; $i >= 0; $i--) {
                    $year = Carbon::now()->subYears($i);
                    $labels[] = $year->format('Y');
                    
                    $pendapatan = Transaksi::where('status', 'settlement')
                        ->whereYear('tanggaltransaksi', $year->year)
                        ->sum('totalharga');
                    
                    $data[] = $pendapatan;
                }
            }
            break;
    }
    
    return [
        'labels' => $labels,
        'data' => $data
    ];
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

   
}