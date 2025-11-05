{{-- resources/views/owner/laporan.blade.php --}}
@extends('owner.layout')

@section('title', 'Laporan Pendapatan')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-4xl font-bold text-[#14274E]">
            <i class="fas fa-file-invoice-dollar mr-3 text-[#1E56A0]"></i>Laporan Pendapatan
        </h1>
    </div>

    <!-- Filter Bulan dan Tahun -->
    <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl p-8">
        <form method="GET" action="{{ route('owner.laporan') }}" class="flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm font-bold text-[#14274E] mb-2">
                    <i class="far fa-calendar mr-2"></i>Bulan
                </label>
                <select name="bulan" class="px-5 py-3 border-2 border-[#D6E4F0] rounded-xl focus:ring-2 focus:ring-[#1E56A0] focus:border-transparent bg-white/50 backdrop-blur text-[#14274E] font-semibold">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <div>
                <label class="block text-sm font-bold text-[#14274E] mb-2">
                    <i class="far fa-calendar-alt mr-2"></i>Tahun
                </label>
                <select name="tahun" class="px-5 py-3 border-2 border-[#D6E4F0] rounded-xl focus:ring-2 focus:ring-[#1E56A0] focus:border-transparent bg-white/50 backdrop-blur text-[#14274E] font-semibold">
                    @for($y = \Carbon\Carbon::now()->year; $y >= \Carbon\Carbon::now()->year - 5; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <button type="submit" class="px-6 py-3 rounded-full bg-gradient-to-r from-[#1E56A0] to-[#14274E] text-white hover:scale-105 transition transform shadow-lg">
                <i class="fas fa-filter mr-2"></i>Filter
            </button>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gradient-to-br from-[#10b981] to-[#059669] rounded-2xl shadow-xl p-8 text-white transform hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-2">Total Pendapatan Bulan Ini</p>
                    <p class="text-4xl font-bold">Rp {{ number_format($totalBulanIni, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white/20 backdrop-blur rounded-full p-5">
                    <i class="fas fa-money-bill-wave text-4xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-[#1E56A0] to-[#14274E] rounded-2xl shadow-xl p-8 text-white transform hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-2">Transaksi Bulan Ini</p>
                    <p class="text-4xl font-bold">{{ number_format($totalTransaksiBulanIni) }}</p>
                </div>
                <div class="bg-white/20 backdrop-blur rounded-full p-5">
                    <i class="fas fa-receipt text-4xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Pendapatan Harian -->
    <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl p-8">
        <h2 class="text-2xl font-bold text-[#14274E] mb-8">
            <i class="fas fa-chart-line mr-3 text-[#1E56A0]"></i>Pendapatan Harian - {{ \Carbon\Carbon::create()->month($bulan)->format('F') }} {{ $tahun }}
        </h2>
        <div class="relative h-96">
            <canvas id="harianChart"></canvas>
        </div>
    </div>

    <!-- Tabel Pendapatan Harian -->
    <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl p-8">
        <h2 class="text-2xl font-bold text-[#14274E] mb-8">
            <i class="fas fa-table mr-3 text-[#1E56A0]"></i>Detail Pendapatan Harian
        </h2>
        <div class="overflow-x-auto rounded-xl">
            <table class="min-w-full">
                <thead class="bg-[#D6E4F0]">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider rounded-tl-xl">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider">Jumlah Transaksi</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider rounded-tr-xl">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#D6E4F0]">
                    @forelse($pendapatanHarian as $item)
                    <tr class="hover:bg-[#D6E4F0]/50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-[#14274E] font-semibold">
                            <i class="far fa-calendar mr-2 text-[#1E56A0]"></i>
                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d F Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="px-4 py-2 bg-gradient-to-r from-[#1E56A0] to-[#14274E] text-white rounded-full font-bold">
                                {{ number_format($item->jumlah_transaksi) }} transaksi
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-[#059669]">
                            Rp {{ number_format($item->total, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-16 text-center">
                            <i class="fas fa-inbox text-5xl text-[#D6E4F0] mb-3"></i>
                            <p class="text-[#14274E] font-semibold">Tidak ada data untuk periode ini</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Chart Pendapatan Bulanan -->
    <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl p-8">
        <h2 class="text-2xl font-bold text-[#14274E] mb-8">
            <i class="fas fa-chart-bar mr-3 text-[#1E56A0]"></i>Pendapatan Bulanan - Tahun {{ $tahun }}
        </h2>
        <div class="relative h-96">
            <canvas id="bulananChart"></canvas>
        </div>
    </div>

    <!-- Tabel Pendapatan Bulanan -->
    <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl p-8">
        <h2 class="text-2xl font-bold text-[#14274E] mb-8">
            <i class="fas fa-table mr-3 text-[#1E56A0]"></i>Detail Pendapatan Bulanan
        </h2>
        <div class="overflow-x-auto rounded-xl">
            <table class="min-w-full">
                <thead class="bg-[#D6E4F0]">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider rounded-tl-xl">Bulan</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider">Jumlah Transaksi</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider rounded-tr-xl">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#D6E4F0]">
                    @forelse($pendapatanBulanan as $item)
                    <tr class="hover:bg-[#D6E4F0]/50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-[#14274E] font-semibold">
                            <i class="far fa-calendar mr-2 text-[#1E56A0]"></i>
                            {{ \Carbon\Carbon::create()->month($item->bulan)->format('F') }} {{ $tahun }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="px-4 py-2 bg-gradient-to-r from-[#1E56A0] to-[#14274E] text-white rounded-full font-bold">
                                {{ number_format($item->jumlah_transaksi) }} transaksi
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-[#059669]">
                            Rp {{ number_format($item->total, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-16 text-center">
                            <i class="fas fa-inbox text-5xl text-[#D6E4F0] mb-3"></i>
                            <p class="text-[#14274E] font-semibold">Tidak ada data untuk tahun ini</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Chart Pendapatan Tahunan -->
    <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl p-8">
        <h2 class="text-2xl font-bold text-[#14274E] mb-8">
            <i class="fas fa-chart-area mr-3 text-[#1E56A0]"></i>Pendapatan Tahunan
        </h2>
        <div class="relative h-96">
            <canvas id="tahunanChart"></canvas>
        </div>
    </div>

    <!-- Tabel Pendapatan Tahunan -->
    <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl p-8">
        <h2 class="text-2xl font-bold text-[#14274E] mb-8">
            <i class="fas fa-table mr-3 text-[#1E56A0]"></i>Detail Pendapatan Tahunan
        </h2>
        <div class="overflow-x-auto rounded-xl">
            <table class="min-w-full">
                <thead class="bg-[#D6E4F0]">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider rounded-tl-xl">Tahun</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider">Jumlah Transaksi</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider rounded-tr-xl">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#D6E4F0]">
                    @forelse($pendapatanTahunan as $item)
                    <tr class="hover:bg-[#D6E4F0]/50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-[#14274E]">
                            <i class="far fa-calendar-alt mr-2 text-[#1E56A0]"></i>
                            {{ $item->tahun }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <span class="px-4 py-2 bg-gradient-to-r from-[#1E56A0] to-[#14274E] text-white rounded-full font-bold">
                                {{ number_format($item->jumlah_transaksi) }} transaksi
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-[#059669]">
                            Rp {{ number_format($item->total, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-16 text-center">
                            <i class="fas fa-inbox text-5xl text-[#D6E4F0] mb-3"></i>
                            <p class="text-[#14274E] font-semibold">Tidak ada data pendapatan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Data Pendapatan Harian
    const harianLabels = @json($pendapatanHarian->pluck('tanggal')->map(fn($d) => \Carbon\Carbon::parse($d)->format('d M')));
    const harianData = @json($pendapatanHarian->pluck('total'));

    const harianChart = new Chart(document.getElementById('harianChart'), {
        type: 'bar',
        data: {
            labels: harianLabels,
            datasets: [{
                label: 'Pendapatan Harian (Rp)',
                data: harianData,
                backgroundColor: 'rgba(30, 86, 160, 0.7)',
                borderColor: '#1E56A0',
                borderWidth: 2,
                borderRadius: 8,
                hoverBackgroundColor: '#14274E'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        font: {
                            size: 13,
                            weight: 'bold'
                        },
                        color: '#14274E'
                    }
                },
                tooltip: {
                    backgroundColor: '#14274E',
                    titleFont: {  size: 14 },
                    bodyFont: { size: 13 },
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            return 'Pendapatan: Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        font: { size: 12 },
                        color: '#14274E',
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    },
                    grid: { color: 'rgba(214, 228, 240, 0.3)' }
                },
                x: {
                    ticks: {
                        font: { size: 12 },
                        color: '#14274E'
                    },
                    grid: { display: false }
                }
            }
        }
    });

    // Data Pendapatan Bulanan
    const bulananLabels = @json($pendapatanBulanan->pluck('bulan')->map(fn($m) => \Carbon\Carbon::create()->month($m)->format('F')));
    const bulananData = @json($pendapatanBulanan->pluck('total'));

    const bulananChart = new Chart(document.getElementById('bulananChart'), {
        type: 'line',
        data: {
            labels: bulananLabels,
            datasets: [{
                label: 'Pendapatan Bulanan (Rp)',
                data: bulananData,
                borderColor: '#10b981',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 6,
                pointHoverRadius: 8,
                pointBackgroundColor: '#10b981',
                pointBorderColor: '#fff',
                pointBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        font: { size: 13, weight: 'bold' },
                        color: '#14274E'
                    }
                },
                tooltip: {
                    backgroundColor: '#14274E',
                    titleFont: { size: 14 },
                    bodyFont: { size: 13 },
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            return 'Pendapatan: Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        font: { size: 12 },
                        color: '#14274E',
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    },
                    grid: { color: 'rgba(214, 228, 240, 0.3)' }
                },
                x: {
                    ticks: {
                        font: { size: 12 },
                        color: '#14274E'
                    },
                    grid: { display: false }
                }
            }
        }
    });

    // Data Pendapatan Tahunan
    const tahunanLabels = @json($pendapatanTahunan->pluck('tahun'));
    const tahunanData = @json($pendapatanTahunan->pluck('total'));

    const tahunanChart = new Chart(document.getElementById('tahunanChart'), {
        type: 'bar',
        data: {
            labels: tahunanLabels,
            datasets: [{
                label: 'Pendapatan Tahunan (Rp)',
                data: tahunanData,
                backgroundColor: 'rgba(249, 115, 22, 0.7)',
                borderColor: '#f59e0b',
                borderWidth: 2,
                borderRadius: 8,
                hoverBackgroundColor: '#d97706'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        font: {size: 13, weight: 'bold' },
                        color: '#14274E'
                    }
                },
                tooltip: {
                    backgroundColor: '#14274E',
                    titleFont: { size: 14 },
                    bodyFont: { size: 13 },
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            return 'Pendapatan: Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        font: { size: 12 },
                        color: '#14274E',
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    },
                    grid: { color: 'rgba(214, 228, 240, 0.3)' }
                },
                x: {
                    ticks: {
                        font: { size: 12 },
                        color: '#14274E'
                    },
                    grid: { display: false }
                }
            }
        }
    });
</script>
@endpush