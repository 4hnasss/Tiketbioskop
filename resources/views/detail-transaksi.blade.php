<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi #{{ $transaksi->id }} - Kasir</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    @include('components.nav')
    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Detail Transaksi</h2>
                <p class="text-gray-600">Informasi lengkap transaksi #{{ $transaksi->id }}</p>
            </div>
            <a href="{{ route('kasir.riwayat-transaksi') }}" 
               class="flex items-center space-x-2 text-gray-600 hover:text-gray-900">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Kembali</span>
            </a>
        </div>

        <div class="max-w-4xl mx-auto">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Status Pembayaran</h3>
                    @if($transaksi->status === 'settlement')
                        <span class="px-4 py-2 rounded-full bg-green-100 text-green-800 font-semibold flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>LUNAS</span>
                        </span>
                    @elseif($transaksi->status === 'pending')
                        <span class="px-4 py-2 rounded-full bg-yellow-100 text-yellow-800 font-semibold flex items-center space-x-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>PENDING</span>
                        </span>
                    @else
                        <span class="px-4 py-2 rounded-full bg-red-100 text-red-800 font-semibold">
                            {{ strtoupper($transaksi->status) }}
                        </span>
                    @endif
                </div>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="text-gray-600">ID Transaksi:</span>
                        <p class="font-medium text-gray-800">#{{ $transaksi->id }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Metode Pembayaran:</span>
                        <p class="font-medium text-gray-800">{{ strtoupper($transaksi->metode_pembayaran ?? 'CASH') }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">Tanggal Transaksi:</span>
                        <p class="font-medium text-gray-800">{{ $transaksi->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <span class="text-gray-600">User:</span>
                        <p class="font-medium text-gray-800">{{ $transaksi->user->name ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Film Info -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Informasi Film</h3>
                <div class="flex items-start space-x-6">
                    @if($transaksi->jadwal->film->poster)
                        <img src="{{ asset('img/' . $transaksi->jadwal->film->poster) }}" 
                             alt="{{ $transaksi->jadwal->film->judul }}" 
                             class="w-32 h-48 object-cover rounded-lg shadow-md">
                    @else
                        <div class="w-32 h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>
                            </svg>
                        </div>
                    @endif
                    <div class="flex-1">
                        <h4 class="text-xl font-bold text-gray-800 mb-3">{{ $transaksi->jadwal->film->judul }}</h4>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Tanggal Tayang:</span>
                                <p class="font-medium">{{ \Carbon\Carbon::parse($transaksi->jadwal->tanggal)->isoFormat('dddd, D MMMM YYYY') }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Jam Tayang:</span>
                                <p class="font-medium">{{ \Carbon\Carbon::parse($transaksi->jadwal->jamtayang)->format('H:i') }} WIB</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Studio:</span>
                                <p class="font-medium">{{ $transaksi->jadwal->studio->namastudio ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Durasi:</span>
                                <p class="font-medium">{{ $transaksi->jadwal->film->durasi }} menit</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Genre:</span>
                                <p class="font-medium">{{ $transaksi->jadwal->film->genre ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-600">Rating:</span>
                                <p class="font-medium">{{ $transaksi->jadwal->film->rating ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kursi & Harga -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Detail Pemesanan</h3>
                
                <div class="mb-6">
                    <p class="text-sm text-gray-600 mb-3 font-medium">Kursi yang Dipesan:</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($kursiArray as $kursi)
                            <span class="px-4 py-2 bg-indigo-100 text-indigo-800 rounded-lg font-semibold text-sm border-2 border-indigo-200">
                                {{ $kursi }}
                            </span>
                        @endforeach
                    </div>
                </div>

                <div class="border-t pt-4 space-y-2">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Jumlah Kursi:</span>
                        <span class="font-medium text-gray-900">{{ count($kursiArray) }} kursi</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Harga per Kursi:</span>
                        <span class="font-medium text-gray-900">Rp {{ number_format($transaksi->totalharga / count($kursiArray), 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between text-lg font-bold pt-2 border-t">
                        <span>Total Pembayaran:</span>
                        <span class="text-indigo-600">Rp {{ number_format($transaksi->totalharga, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('kasir.riwayat-transaksi') }}" 
                   class="bg-gray-200 hover:bg-gray-300 text-gray-800 text-center font-medium py-3 rounded-lg transition flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span>Kembali ke Riwayat</span>
                </a>
                <a href="{{ route('kasir.pesan-tiket') }}" 
                   class="bg-indigo-600 hover:bg-indigo-700 text-white text-center font-medium py-3 rounded-lg transition flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                    <span>Pesan Tiket Baru</span>
                </a>
                <button onclick="window.print()" 
                   class="bg-green-600 hover:bg-green-700 text-white text-center font-medium py-3 rounded-lg transition flex items-center justify-center space-x-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    <span>Cetak</span>
                </button>
            </div>
        </div>
    </main>

    <style>
        @media print {
            nav, .no-print, button {
                display: none !important;
            }
            body {
                background: white;
            }
        }
    </style>
</body>
</html>