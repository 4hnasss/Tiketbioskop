{{-- resources/views/owner/detail-transaksi.blade.php --}}
@extends('owner.layout')

@section('title', 'Detail Transaksi')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-4xl font-bold text-[#14274E]">
            <i class="fas fa-file-invoice mr-3 text-[#1E56A0]"></i>Detail Transaksi #{{ $transaksi->id }}
        </h1>
        <a href="{{ route('owner.transaksi') }}" class="px-6 py-3 rounded-full bg-[#D6E4F0] text-[#14274E] hover:bg-[#1E56A0] hover:text-white transition shadow-lg">
            <i class="fas fa-arrow-left mr-2"></i>Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Info Transaksi -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Status Card -->
            <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl p-8">
                <h2 class="text-2xl font-bold text-[#14274E] mb-6">
                    <i class="fas fa-info-circle mr-3 text-[#1E56A0]"></i>Status Transaksi
                </h2>
                <div class="flex items-center justify-between flex-wrap gap-4">
                    <div>
                        <p class="text-sm text-[#14274E] mb-3 font-semibold">Status Pembayaran</p>
                        @if($transaksi->status == 'settlement')
                            <span class="px-6 py-3 inline-flex text-sm font-bold rounded-full bg-green-100 text-green-800 shadow-md">
                                <i class="fas fa-check-circle mr-2"></i>Selesai
                            </span>
                        @elseif($transaksi->status == 'pending')
                            <span class="px-6 py-3 inline-flex text-sm font-bold rounded-full bg-yellow-100 text-yellow-800 shadow-md">
                                <i class="fas fa-clock mr-2"></i>Pending
                            </span>
                        @else
                            <span class="px-6 py-3 inline-flex text-sm font-bold rounded-full bg-red-100 text-red-800 shadow-md">
                                <i class="fas fa-times-circle mr-2"></i>{{ ucfirst($transaksi->status) }}
                            </span>
                        @endif
                    </div>
                    
                    @if($transaksi->metode_bayar)
                    <div>
                        <p class="text-sm text-[#14274E] mb-3 font-semibold">Metode Pembayaran</p>
                        <span class="px-6 py-3 bg-gradient-to-r from-[#1E56A0] to-[#14274E] text-white rounded-full font-bold shadow-md">
                            <i class="fas fa-credit-card mr-2"></i>{{ strtoupper($transaksi->metode_bayar) }}
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Info Customer -->
            <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl p-8">
                <h2 class="text-2xl font-bold text-[#14274E] mb-6">
                    <i class="fas fa-user mr-3 text-[#1E56A0]"></i>Informasi Customer
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-[#D6E4F0]/50 rounded-xl p-4">
                        <p class="text-sm text-[#14274E] mb-2 font-semibold">Nama</p>
                        <p class="font-bold text-[#14274E] text-lg">{{ $transaksi->user->name ?? '-' }}</p>
                    </div>
                    <div class="bg-[#D6E4F0]/50 rounded-xl p-4">
                        <p class="text-sm text-[#14274E] mb-2 font-semibold">Email</p>
                        <p class="font-bold text-[#14274E] text-lg">{{ $transaksi->user->email ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Info Film -->
            <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl p-8">
                <h2 class="text-2xl font-bold text-[#14274E] mb-6">
                    <i class="fas fa-film mr-3 text-[#1E56A0]"></i>Informasi Film
                </h2>
                <div class="space-y-4">
                    <div class="bg-[#D6E4F0]/50 rounded-xl p-4">
                        <p class="text-sm text-[#14274E] mb-2 font-semibold">Judul Film</p>
                        <p class="font-bold text-[#14274E] text-xl">{{ $transaksi->jadwal->film->judul ?? '-' }}</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-[#D6E4F0]/50 rounded-xl p-4">
                            <p class="text-sm text-[#14274E] mb-2 font-semibold">Tanggal Tayang</p>
                            <p class="font-bold text-[#14274E] text-lg">
                                <i class="far fa-calendar mr-2 text-[#1E56A0]"></i>{{ $transaksi->jadwal->tanggal ?? '-' }}
                            </p>
                        </div>
                        <div class="bg-[#D6E4F0]/50 rounded-xl p-4">
                            <p class="text-sm text-[#14274E] mb-2 font-semibold">Jam Tayang</p>
                            <p class="font-bold text-[#14274E] text-lg">
                                <i class="far fa-clock mr-2 text-[#1E56A0]"></i>{{ $transaksi->jadwal->jamtayang ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Info Kursi -->
            <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl p-8">
                <h2 class="text-2xl font-bold text-[#14274E] mb-6">
                    <i class="fas fa-chair mr-3 text-[#1E56A0]"></i>Kursi yang Dipesan
                </h2>
                @if($transaksi->kursi)
                    <div class="flex flex-wrap gap-3 mb-6">
                        @foreach($transaksi->kursi as $kursi)
                            <span class="px-5 py-3 bg-gradient-to-r from-[#1E56A0] to-[#14274E] text-white rounded-xl font-bold shadow-md hover:scale-105 transition transform">
                                {{ $kursi }}
                            </span>
                        @endforeach
                    </div>
                    <div class="bg-[#D6E4F0]/50 rounded-xl p-4 inline-block">
                        <p class="text-sm text-[#14274E] font-semibold">
                            <i class="fas fa-check-circle mr-2 text-[#1E56A0]"></i>
                            Total: <span class="font-bold text-lg">{{ count($transaksi->kursi) }} kursi</span>
                        </p>
                    </div>
                @else
                    <p class="text-[#14274E]">Tidak ada data kursi</p>
                @endif
            </div>
        </div>

        <!-- Summary -->
        <div class="space-y-6">
            <!-- Total Pembayaran -->
            <div class="bg-gradient-to-br from-[#1E56A0] to-[#14274E] rounded-2xl shadow-xl p-8 text-white">
                <div class="flex items-center mb-4">
                    <i class="fas fa-money-bill-wave text-3xl mr-3"></i>
                    <h3 class="text-xl font-bold">Total Pembayaran</h3>
                </div>
                <p class="text-5xl font-bold">Rp {{ number_format($transaksi->totalharga, 0, ',', '.') }}</p>
            </div>

            <!-- Timeline -->
            <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl p-8">
                <h2 class="text-2xl font-bold text-[#14274E] mb-6">
                    <i class="fas fa-clock mr-3 text-[#1E56A0]"></i>Timeline
                </h2>
                <div class="space-y-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 text-white shadow-lg">
                                <i class="fas fa-plus"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-bold text-[#14274E]">Transaksi Dibuat</p>
                            <p class="text-sm text-[#14274E] mt-1">
                                {{ $transaksi->created_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                    </div>

                    @if($transaksi->tanggaltransaksi)
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-br from-green-400 to-green-600 text-white shadow-lg">
                                <i class="fas fa-calendar"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-bold text-[#14274E]">Tanggal Transaksi</p>
                            <p class="text-sm text-[#14274E] mt-1">
                                {{ $transaksi->tanggaltransaksi->format('d M Y, H:i') }}
                            </p>
                        </div>
                    </div>
                    @endif

                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 text-white shadow-lg">
                                <i class="fas fa-edit"></i>
                            </div>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-bold text-[#14274E]">Terakhir Update</p>
                            <p class="text-sm text-[#14274E] mt-1">
                                {{ $transaksi->updated_at->format('d M Y, H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tiket -->
            @if($transaksi->tikets->count() > 0)
            <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl p-8">
                <h2 class="text-2xl font-bold text-[#14274E] mb-6">
                    <i class="fas fa-ticket-alt mr-3 text-[#1E56A0]"></i>Tiket
                </h2>
                <div class="space-y-3">
                    @foreach($transaksi->tikets as $tiket)
                    <div class="p-4 bg-gradient-to-r from-[#D6E4F0] to-white rounded-xl border-2 border-[#1E56A0] shadow-md hover:scale-105 transition transform">
                        <p class="text-sm font-bold text-[#14274E]">Tiket #{{ $tiket->id }}</p>
                        <p class="text-xs text-[#1E56A0] font-semibold mt-1">{{ $tiket->kodetiket ?? '-' }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection