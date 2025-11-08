{{-- resources/views/owner/dashboard.blade.php --}}
@extends('owner.layout')

@section('title', 'Dashboard Owner')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-4xl font-bold text-[#14274E]">
            <i class="fas fa-tachometer-alt mr-3 text-[#1E56A0]"></i>Dashboard Owner
        </h1>
        <div class="text-sm text-[#14274E] bg-white/80 backdrop-blur px-4 py-2 rounded-full shadow-md">
            <i class="far fa-calendar mr-2 text-[#1E56A0]"></i>{{ \Carbon\Carbon::now()->format('d F Y') }}
        </div>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Transaksi -->
        <div class="bg-gradient-to-br from-[#1E56A0] to-[#14274E] rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-2">Total Transaksi</p>
                    <p class="text-4xl font-bold">{{ number_format($totalTransaksi) }}</p>
                </div>
                <div class="bg-white/20 backdrop-blur rounded-full p-4">
                    <i class="fas fa-receipt text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Pendapatan -->
        <div class="bg-gradient-to-br from-[#10b981] to-[#059669] rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-green-100 text-sm font-medium mb-2">Total Pendapatan</p>
                    <p class="text-3xl font-bold">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white/20 backdrop-blur rounded-full p-4">
                    <i class="fas fa-money-bill-wave text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Transaksi Hari Ini -->
        <div class="bg-gradient-to-br from-[#8b5cf6] to-[#7c3aed] rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium mb-2">Transaksi Hari Ini</p>
                    <p class="text-4xl font-bold">{{ number_format($transaksiHariIni) }}</p>
                </div>
                <div class="bg-white/20 backdrop-blur rounded-full p-4">
                    <i class="fas fa-shopping-cart text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Pendapatan Hari Ini -->
        <div class="bg-gradient-to-br from-[#f59e0b] to-[#d97706] rounded-2xl shadow-xl p-6 text-white transform hover:scale-105 transition-transform">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-orange-100 text-sm font-medium mb-2">Pendapatan Hari Ini</p>
                    <p class="text-3xl font-bold">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</p>
                </div>
                <div class="bg-white/20 backdrop-blur rounded-full p-4">
                    <i class="fas fa-chart-line text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl p-8">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 gap-4">
            <h2 class="text-2xl font-bold text-[#14274E]">
                <i class="fas fa-chart-bar mr-3 text-[#1E56A0]"></i>Grafik Pendapatan
            </h2>
            
            <div class="flex flex-col lg:flex-row gap-4 w-full lg:w-auto">
                <!-- Filter Periode -->
                <div class="flex space-x-2">
                    <a href="{{ route('owner.dashboard', array_merge(request()->only(['start_date', 'end_date']), ['periode' => 'hari'])) }}" 
                       class="px-5 py-2.5 rounded-full transition duration-200 {{ $periode == 'hari' ? 'bg-gradient-to-r from-[#1E56A0] to-[#14274E] text-white shadow-lg' : 'bg-[#D6E4F0] text-[#14274E] hover:bg-[#1E56A0] hover:text-white' }}">
                        Per Hari
                    </a>
                    <a href="{{ route('owner.dashboard', array_merge(request()->only(['start_date', 'end_date']), ['periode' => 'bulan'])) }}" 
                       class="px-5 py-2.5 rounded-full transition duration-200 {{ $periode == 'bulan' ? 'bg-gradient-to-r from-[#1E56A0] to-[#14274E] text-white shadow-lg' : 'bg-[#D6E4F0] text-[#14274E] hover:bg-[#1E56A0] hover:text-white' }}">
                        Per Bulan
                    </a>
                    <a href="{{ route('owner.dashboard', array_merge(request()->only(['start_date', 'end_date']), ['periode' => 'tahun'])) }}" 
                       class="px-5 py-2.5 rounded-full transition duration-200  {{ $periode == 'tahun' ? 'bg-gradient-to-r from-[#1E56A0] to-[#14274E] text-white shadow-lg' : 'bg-[#D6E4F0] text-[#14274E] hover:bg-[#1E56A0] hover:text-white' }}">
                        Per Tahun
                    </a>
                </div>

                <!-- Filter Tanggal -->
                <form method="GET" action="{{ route('owner.dashboard') }}" class="flex items-center gap-2 bg-[#D6E4F0] rounded-full px-4 py-2">
                    <input type="hidden" name="periode" value="{{ $periode }}">
                    
                    <div class="flex items-center gap-2">
                        <i class="fas fa-calendar-alt text-[#1E56A0]"></i>
                        <input type="date" 
                               name="start_date" 
                               value="{{ request('start_date') }}"
                               class="bg-transparent border-none text-sm text-[#14274E] focus:outline-none focus:ring-0 w-36"
                               placeholder="Start Date">
                    </div>
                    
                    <span class="text-[#14274E]">-</span>
                    
                    <div class="flex items-center gap-2">
                        <input type="date" 
                               name="end_date" 
                               value="{{ request('end_date') }}"
                               class="bg-transparent border-none text-sm text-[#14274E] focus:outline-none focus:ring-0 w-36"
                               placeholder="End Date">
                    </div>
                    
                    <button type="submit" 
                            class="ml-2 px-4 py-1.5 bg-[#1E56A0] text-white rounded-full hover:bg-[#14274E] transition text-sm font-semibold">
                        <i class="fas fa-filter mr-1"></i>Filter
                    </button>
                    
                    @if(request('start_date') || request('end_date'))
                        <a href="{{ route('owner.dashboard', ['periode' => $periode]) }}" 
                           class="ml-1 px-3 py-1.5 bg-red-500 text-white rounded-full hover:bg-red-600 transition text-sm">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </form>
            </div>
        </div>

        @if(request('start_date') || request('end_date'))
            <div class="mb-4 px-4 py-2 bg-blue-50 border-l-4 border-[#1E56A0] rounded">
                <p class="text-sm text-[#14274E]">
                    <i class="fas fa-info-circle mr-2 text-[#1E56A0]"></i>
                    Menampilkan data dari 
                    <strong>{{ request('start_date') ? \Carbon\Carbon::parse(request('start_date'))->format('d M Y') : 'awal' }}</strong>
                    sampai 
                    <strong>{{ request('end_date') ? \Carbon\Carbon::parse(request('end_date'))->format('d M Y') : 'sekarang' }}</strong>
                </p>
            </div>
        @endif

        <div class="relative h-96">
            <canvas id="pendapatanChart"></canvas>
        </div>
    </div>

    <!-- Transaksi Terbaru -->
    <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl p-8">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-bold text-[#14274E]">
                <i class="fas fa-history mr-3 text-[#1E56A0]"></i>Transaksi Terbaru
            </h2>
            <a href="{{ route('owner.transaksi') }}" class="text-[#1E56A0] hover:text-[#14274E] font-serif font-semibold transition">
                Lihat Semua <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>

        <div class="overflow-x-auto rounded-xl">
            <table class="min-w-full">
                <thead class="bg-[#D6E4F0]">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider rounded-tl-xl">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider">Total</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider rounded-tr-xl">Metode</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#D6E4F0]">
                    @forelse($transaksiTerbaru as $transaksi)
                    <tr class="hover:bg-[#D6E4F0]/50 transition duration-150">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-[#1E56A0]">
                            #{{ $transaksi->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-[#14274E]">
                            {{ $transaksi->tanggaltransaksi ? $transaksi->tanggaltransaksi->format('d/m/Y H:i') : '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-[#14274E] font-semibold">
                            {{ $transaksi->user->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-[#14274E]">
                            Rp {{ number_format($transaksi->totalharga, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($transaksi->status == 'settlement')
                                <span class="px-4 py-1.5 inline-flex text-xs font-bold rounded-full bg-green-100 text-green-800 shadow-sm">
                                    <i class="fas fa-check-circle mr-1.5"></i>Selesai
                                </span>
                            @elseif($transaksi->status == 'pending')
                                <span class="px-4 py-1.5 inline-flex text-xs font-bold rounded-full bg-yellow-100 text-yellow-800 shadow-sm">
                                    <i class="fas fa-clock mr-1.5"></i>Pending
                                </span>
                            @else
                                <span class="px-4 py-1.5 inline-flex text-xs font-bold rounded-full bg-red-100 text-red-800 shadow-sm">
                                    <i class="fas fa-times-circle mr-1.5"></i>{{ ucfirst($transaksi->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-[#14274E]">
                            <span class="px-3 py-1 bg-[#D6E4F0] rounded-full text-xs font-semibold">
                                {{ $transaksi->metode_pembayaran ?? '-' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <i class="fas fa-inbox text-5xl text-[#D6E4F0] mb-3"></i>
                            <p class="text-[#14274E]">Belum ada transaksi</p>
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
    const chartData = @json($chartData);
    
    const ctx = document.getElementById('pendapatanChart').getContext('2d');
    const pendapatanChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: chartData.data,
                borderColor: '#1E56A0',
                backgroundColor: 'rgba(30, 86, 160, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointHoverRadius: 8,
                pointBackgroundColor: '#1E56A0',
                pointBorderColor: '#fff',
                pointBorderWidth: 3,
                pointHoverBackgroundColor: '#14274E',
                pointHoverBorderColor: '#fff',
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
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
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 13
                    },
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
                        font: {
                            family: 'serif',
                            size: 12
                        },
                        color: '#14274E',
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    },
                    grid: {
                        color: 'rgba(214, 228, 240, 0.3)'
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 12
                        },
                        color: '#14274E'
                    },
                    grid: {
                        display: false
                    }
                }
            }
        }
    });
</script>
@endpush