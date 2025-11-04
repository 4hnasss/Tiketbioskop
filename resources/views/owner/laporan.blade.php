@extends('owner.layout')

@section('title', 'Laporan Pendapatan')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">
            <i class="fas fa-file-invoice-dollar mr-2 text-indigo-600"></i>Laporan Pendapatan
        </h1>
    </div>

    <!-- Filter Bulan dan Tahun -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <form method="GET" action="{{ route('owner.laporan') }}" class="flex items-end space-x-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Bulan</label>
                <select name="bulan" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ $bulan == $m ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                        </option>
                    @endfor
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tahun</label>
                <select name="tahun" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                    @for($y = \Carbon\Carbon::now()->year; $y >= \Carbon\Carbon::now()->year - 5; $y--)
                        <option value="{{ $y }}" {{ $tahun == $y ? 'selected' : '' }}>{{ $y }}</option>
                    @endfor
                </select>
            </div>

            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2 rounded-lg transition duration-200">
                <i class="fas fa-filter mr-2"></i>Filter
            </button>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium">Total Pendapatan Bulan Ini</p>
                    <p class="text-3xl font-bold mt-2">Rp {{ number_format($totalBulanIni, 0, ',', '.') }}</p>
                </div>
                <div class="bg-green-400 bg-opacity-50 rounded-full p-4">
                    <i class="fas fa-money-bill-wave text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium">Transaksi Bulan Ini</p>
                    <p class="text-3xl font-bold mt-2">{{ number_format($totalTransaksiBulanIni) }}</p>
                </div>
                <div class="bg-blue-400 bg-opacity-50 rounded-full p-4">
                    <i class="fas fa-receipt text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Pendapatan Harian -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">
            <i class="fas fa-chart-line mr-2 text-indigo-600"></i>Pendapatan Harian - {{ \Carbon\Carbon::create()->month($bulan)->format('F') }} {{ $tahun }}
        </h2>
        <div class="relative h-96">
            <canvas id="harianChart"></canvas>
        </div>
    </div>

    <!-- Tabel Pendapatan Harian -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">
            <i class="fas fa-table mr-2 text-indigo-600"></i>Detail Pendapatan Harian
        </h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Transaksi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pendapatanHarian as $item)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d F Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full">
                                {{ number_format($item->jumlah_transaksi) }} transaksi
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                            Rp {{ number_format($item->total, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-3xl mb-2"></i>
                            <p>Tidak ada data untuk periode ini</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Chart Pendapatan Bulanan -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">
            <i class="fas fa-chart-bar mr-2 text-indigo-600"></i>Pendapatan Bulanan - Tahun {{ $tahun }}
        </h2>
        <div class="relative h-96">
            <canvas id="bulananChart"></canvas>
        </div>
    </div>

    <!-- Tabel Pendapatan Bulanan -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">
            <i class="fas fa-table mr-2 text-indigo-600"></i>Detail Pendapatan Bulanan
        </h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bulan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Transaksi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pendapatanBulanan as $item)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ \Carbon\Carbon::create()->month($item->bulan)->format('F') }} {{ $tahun }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full">
                                {{ number_format($item->jumlah_transaksi) }} transaksi
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                            Rp {{ number_format($item->total, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-3xl mb-2"></i>
                            <p>Tidak ada data untuk tahun ini</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Chart Pendapatan Tahunan -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">
            <i class="fas fa-chart-area mr-2 text-indigo-600"></i>Pendapatan Tahunan
        </h2>
        <div class="relative h-96">
            <canvas id="tahunanChart"></canvas>
        </div>
    </div>

    <!-- Tabel Pendapatan Tahunan -->
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h2 class="text-xl font-bold text-gray-900 mb-6">
            <i class="fas fa-table mr-2 text-indigo-600"></i>Detail Pendapatan Tahunan
        </h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Transaksi</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($pendapatanTahunan as $item)
                    <tr class="hover:bg-gray-50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                            {{ $item->tahun }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full">
                                {{ number_format($item->jumlah_transaksi) }} transaksi
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                            Rp {{ number_format($item->total, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-3xl mb-2"></i>
                            <p>Tidak ada data pendapatan</p>
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
                backgroundColor: 'rgba(79, 70, 229, 0.6)',
                borderColor: 'rgb(79, 70, 229)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
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
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
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
                borderColor: 'rgb(16, 185, 129)',
                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointHoverRadius: 7
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
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
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
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
                backgroundColor: 'rgba(249, 115, 22, 0.6)',
                borderColor: 'rgb(249, 115, 22)',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                tooltip: {
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
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
@endpush