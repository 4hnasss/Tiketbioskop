<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi #{{ $transaksi->id }} - Flixora</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">

    @include('components.nav')

    <main class="max-w-5xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('riwayat-transaksi') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Riwayat Transaksi
            </a>
        </div>

        <!-- Header -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">Detail Transaksi #{{ $transaksi->id }}</h1>
                    <p class="text-sm text-gray-600">
                        Dibuat pada {{ $transaksi->created_at->translatedFormat('d F Y, H:i') }} WIB
                    </p>
                </div>
                <div class="mt-4 md:mt-0">
                    @if($transaksi->status == 'settlement')
                        <span class="px-4 py-2 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                            ✓ Settlement
                        </span>
                    @elseif($transaksi->status == 'pending')
                        <span class="px-4 py-2 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            ⏳ Pending
                        </span>
                    @elseif($transaksi->status == 'batal')
                        <span class="px-4 py-2 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                            ✕ Batal
                        </span>
                    @else
                        <span class="px-4 py-2 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                            {{ ucfirst($transaksi->status) }}
                        </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Main Info -->
            <div class="md:col-span-2 space-y-6">
                <!-- Film Info -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 pb-3 border-b">Informasi Film</h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-indigo-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm text-gray-600">Judul Film</p>
                                <p class="font-semibold text-gray-900">{{ $transaksi->jadwal->film->judul ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-indigo-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm text-gray-600">Tanggal & Waktu Tayang</p>
                                <p class="font-semibold text-gray-900">
                                    {{ \Carbon\Carbon::parse($transaksi->jadwal->tanggal)->translatedFormat('d F Y') }}
                                </p>
                                <p class="text-sm text-gray-700">
                                    {{ \Carbon\Carbon::parse($transaksi->jadwal->jamtayang)->format('H:i') }} WIB
                                </p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-indigo-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            <div class="flex-1">
                                <p class="text-sm text-gray-600">Studio</p>
                                <p class="font-semibold text-gray-900">{{ $transaksi->jadwal->studio->nama_studio ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kursi Info -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 pb-3 border-b">Kursi yang Dipesan</h2>
                    
                    <div class="flex flex-wrap gap-2">
                        @php
                            $kursiArray = is_array($transaksi->kursi) ? $transaksi->kursi : json_decode($transaksi->kursi, true);
                        @endphp
                        
                        @if(is_array($kursiArray) && count($kursiArray) > 0)
                            @foreach($kursiArray as $kursi)
                                <span class="px-4 py-2 bg-indigo-100 text-indigo-800 text-sm font-semibold rounded-lg">
                                    {{ $kursi }}
                                </span>
                            @endforeach
                        @else
                            <p class="text-gray-500">Tidak ada kursi</p>
                        @endif
                    </div>

                    <div class="mt-4 pt-4 border-t">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-700">Jumlah Kursi</span>
                            <span class="font-semibold text-gray-900">{{ count($kursiArray ?? []) }} kursi</span>
                        </div>
                    </div>
                </div>

                <!-- User Info -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 pb-3 border-b">Informasi Pembeli</h2>
                    
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <div>
                                <p class="text-sm text-gray-600">Nama</p>
                                <p class="font-semibold text-gray-900">{{ $transaksi->user->name ?? 'N/A' }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <div>
                                <p class="text-sm text-gray-600">Email</p>
                                <p class="font-semibold text-gray-900">{{ $transaksi->user->email ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="md:col-span-1 space-y-6">
                <!-- Payment Summary -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 pb-3 border-b">Ringkasan Pembayaran</h2>
                    
                    <div class="space-y-3">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Metode Pembayaran</span>
                            <span class="font-semibold text-gray-900">
                                {{ strtoupper($transaksi->metode_pembayaran ?? 'N/A') }}
                            </span>
                        </div>

                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Jumlah Tiket</span>
                            <span class="font-semibold text-gray-900">
                                {{ count($kursiArray ?? []) }} tiket
                            </span>
                        </div>

                        @if(count($kursiArray ?? []) > 0)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Harga per Tiket</span>
                                <span class="font-semibold text-gray-900">
                                    Rp {{ number_format($transaksi->totalharga / count($kursiArray), 0, ',', '.') }}
                                </span>
                            </div>
                        @endif

                        <div class="pt-3 border-t">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-bold text-gray-900">Total</span>
                                <span class="text-2xl font-bold text-indigo-600">
                                    Rp {{ number_format($transaksi->totalharga, 0, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status Timeline -->
                <div class="bg-white rounded-lg shadow-md p-6">
                    <h2 class="text-lg font-bold text-gray-900 mb-4 pb-3 border-b">Status Transaksi</h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-start gap-3">
                            <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-green-500"></div>
                            <div class="flex-1">
                                <p class="text-sm font-semibold text-gray-900">Transaksi Dibuat</p>
                                <p class="text-xs text-gray-600">
                                    {{ $transaksi->created_at->translatedFormat('d M Y, H:i') }}
                                </p>
                            </div>
                        </div>

                        @if($transaksi->status === 'settlement')
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-green-500"></div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900">Pembayaran Berhasil</p>
                                    <p class="text-xs text-gray-600">
                                        {{ $transaksi->updated_at->translatedFormat('d M Y, H:i') }}
                                    </p>
                                </div>
                            </div>
                        @elseif($transaksi->status === 'pending')
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-yellow-500"></div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900">Menunggu Pembayaran</p>
                                    <p class="text-xs text-gray-600">Silakan selesaikan pembayaran</p>
                                </div>
                            </div>
                        @elseif($transaksi->status === 'batal')
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0 w-2 h-2 mt-2 rounded-full bg-red-500"></div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-gray-900">Transaksi Dibatalkan</p>
                                    <p class="text-xs text-gray-600">
                                        {{ $transaksi->updated_at->translatedFormat('d M Y, H:i') }}
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Action Buttons -->
                @if($transaksi->status === 'settlement')
                    <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg shadow-md p-6 border border-green-200">
                        <div class="text-center">
                            <svg class="w-12 h-12 text-green-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm font-semibold text-green-800">Transaksi Selesai</p>
                            <p class="text-xs text-green-600 mt-1">Pembayaran berhasil dikonfirmasi</p>
                        </div>
                    </div>
                @elseif($transaksi->status === 'pending')
                    <a href="{{ route('transaksi-kasir', $transaksi->id) }}" 
                       class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white text-center font-semibold py-3 rounded-lg transition shadow-md">
                        Lanjutkan Pembayaran
                    </a>
                @endif
            </div>
        </div>
    </main>

</body>
</html>