@extends('owner.layout')

@section('title', 'Detail Transaksi')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-gray-900">
            <i class="fas fa-file-invoice mr-2 text-indigo-600"></i>Detail Transaksi #{{ $transaksi->id }}
        </h1>
        <a href="{{ route('owner.transaksi') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg transition duration-200">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Info Transaksi -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">
                    <i class="fas fa-info-circle mr-2 text-indigo-600"></i>Status Transaksi
                </h2>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 mb-2">Status Pembayaran</p>
                        @if($transaksi->status == 'settlement')
                            <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                <i class="fas fa-check-circle mr-2"></i>Selesai
                            </span>
                        @elseif($transaksi->status == 'pending')
                            <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                <i class="fas fa-clock mr-2"></i>Pending
                            </span>
                        @else
                            <span class="px-4 py-2 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                <i class="fas fa-times-circle mr-2"></i>{{ ucfirst($transaksi->status) }}
                            </span>
                        @endif
                    </div>
                    
                    @if($transaksi->metode_bayar)
                    <div>
                        <p class="text-sm text-gray-600 mb-2">Metode Pembayaran</p>
                        <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-lg font-medium">
                            <i class="fas fa-credit-card mr-2"></i>{{ strtoupper($transaksi->metode_bayar) }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Info Customer -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">
                    <i class="fas fa-user mr-2 text-indigo-600"></i>Informasi Customer
                </h2>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Nama</p>
                        <p class="font-semibold text-gray-900">{{ $transaksi->user->name ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-semibold text-gray-900">{{ $transaksi->user->email ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Info Film -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">
                    <i class="fas fa-film mr-2 text-indigo-600"></i>Informasi Film
                </h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Judul Film</p>
                        <p class="font-semibold text-gray-900">{{ $transaksi->jadwal->film->judul ?? '-' }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Tanggal Tayang</p>
                            <p class="font-semibold text-gray-900">
                                {{ $transaksi->jadwal->tanggal ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Jam Tayang</p>
                            <p class="font-semibold text-gray-900">
                                {{ $transaksi->jadwal->jamtayang ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Kursi -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">
                    <i class="fas fa-chair mr-2 text-indigo-600"></i>Kursi yang Dipesan
                </h2>
                @if($transaksi->kursi)
                    <div class="flex flex-wrap gap-2">
                        @foreach($transaksi->kursi as $kursi)
                            <span class="px-4 py-2 bg-indigo-100 text-indigo-800 rounded-lg font-medium">
                                {{ $kursi }}
                            </span>
                        @endforeach
                    </div>
                    <p class="mt-4 text-sm text-gray-600">
                        Total: <span class="font-semibold">{{ count($transaksi->kursi) }} kursi</span>
                    </p>
                @else
                    <p class="text-gray-500">Tidak ada data kursi</p>
                @endif
            </div>
        </div>

        <!-- Summary -->
        <div class="space-y-6">
            <!-- Total Pembayaran -->
            <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-lg shadow-lg p-6 text-white">
                <h3 class="text-lg font-semibold mb-2">Total Pembayaran</h3>
                <p class="text-4xl font-bold">Rp {{ number_format($transaksi->totalharga, 0, ',', '.') }}</p>
            </div>

            <!-- Timeline -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">
                    <i class="fas fa-clock mr-2 text-indigo-600"></i>Timeline
                </h2>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 text-blue-600">
                                <i class="fas fa-plus"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-semibold text-gray-900">Transaksi Dibuat</p>
                            <p class="text-sm text-gray-500">
                                {{ $transaksi->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                    </div>

                    @if($transaksi->tanggaltransaksi)
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-green-100 text-green-600">
                                <i class="fas fa-calendar"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-semibold text-gray-900">Tanggal Transaksi</p>
                            <p class="text-sm text-gray-500">
                                {{ $transaksi->tanggaltransaksi->format('d M Y, H:i') }}
                            </p>
                        </div>
                    </div>
                    @endif

                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-10 h-10 rounded-full bg-purple-100 text-purple-600">
                                <i class="fas fa-edit"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-semibold text-gray-900">Terakhir Update</p>
                            <p class="text-sm text-gray-500">
                                {{ $transaksi->updated_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tiket -->
            @if($transaksi->tikets->count() > 0)
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-4">
                    <i class="fas fa-ticket-alt mr-2 text-indigo-600"></i>Tiket
                </h2>
                <div class="space-y-2">
                    @foreach($transaksi->tikets as $tiket)
                    <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                        <p class="text-sm font-semibold text-gray-900">Tiket #{{ $tiket->id }}</p>
                        <p class="text-xs text-gray-600">{{ $tiket->kodetiket ?? '-' }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection