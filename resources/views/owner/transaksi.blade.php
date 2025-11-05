{{-- resources/views/owner/transaksi.blade.php --}}
@extends('owner.layout')

@section('title', 'Data Transaksi')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <h1 class="text-4xl font-bold text-[#14274E]">
            <i class="fas fa-exchange-alt mr-3 text-[#1E56A0]"></i>Data Transaksi
        </h1>
    </div>

    <!-- Filter Section -->
    <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl p-8">
        <form method="GET" action="{{ route('owner.transaksi') }}" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                <!-- Search -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-bold text-[#14274E] mb-2">
                        <i class="fas fa-search mr-2"></i>Cari Transaksi
                    </label>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}"
                           placeholder="ID atau Nama Customer..." 
                           class="w-full px-4 py-3 border-2 border-[#D6E4F0] rounded-xl focus:ring-2 focus:ring-[#1E56A0] focus:border-transparent bg-white/50 backdrop-blur text-[#14274E] ">
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-bold text-[#14274E] mb-2">
                        <i class="fas fa-filter mr-2"></i>Status
                    </label>
                    <select name="status" class="w-full px-4 py-3 border-2 border-[#D6E4F0] rounded-xl focus:ring-2 focus:ring-[#1E56A0] focus:border-transparent bg-white/50 backdrop-blur text-[#14274E]">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="settlement" {{ request('status') == 'settlement' ? 'selected' : '' }}>Selesai</option>
                        <option value="dibatalkan" {{ request('status') == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>
                </div>

                <!-- Tanggal Dari -->
                <div>
                    <label class="block text-sm font-bold text-[#14274E] mb-2">
                        <i class="far fa-calendar mr-2"></i>Dari Tanggal
                    </label>
                    <input type="date" 
                           name="tanggal_dari" 
                           value="{{ request('tanggal_dari') }}"
                           class="w-full px-4 py-3 border-2 border-[#D6E4F0] rounded-xl focus:ring-2 focus:ring-[#1E56A0] focus:border-transparent bg-white/50 backdrop-blur text-[#14274E]">
                </div>

                <!-- Tanggal Sampai -->
                <div>
                    <label class="block text-sm font-bold text-[#14274E] mb-2">
                        <i class="far fa-calendar mr-2"></i>Sampai Tanggal
                    </label>
                    <input type="date" 
                           name="tanggal_sampai" 
                           value="{{ request('tanggal_sampai') }}"
                           class="w-full px-4 py-3 border-2 border-[#D6E4F0] rounded-xl focus:ring-2 focus:ring-[#1E56A0] focus:border-transparent bg-white/50 backdrop-blur text-[#14274E]">
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex space-x-3">
                <button type="submit" class="px-6 py-3 rounded-full bg-gradient-to-r from-[#1E56A0] to-[#14274E] text-white hover:scale-105 transition transform shadow-lg">
                    <i class="fas fa-search mr-2"></i>Filter
                </button>
                <a href="{{ route('owner.transaksi') }}" class="px-6 py-3 rounded-full bg-[#D6E4F0] text-[#14274E] hover:bg-[#1E56A0] hover:text-white transition shadow-lg">
                    <i class="fas fa-redo mr-2"></i>Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table Section -->
    <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-[#D6E4F0]">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider">Film</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider">Kursi</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider">Total</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider">Metode</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#D6E4F0]">
                    @forelse($transaksis as $transaksi)
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
                        <td class="px-6 py-4 text-sm text-[#14274E] font-semibold">
                            {{ $transaksi->jadwal->film->judul ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($transaksi->kursi)
                                <span class="px-3 py-1.5 bg-[#D6E4F0] text-[#14274E] rounded-full text-xs font-bold">
                                    {{ is_array($transaksi->kursi) ? count($transaksi->kursi) : 0 }} kursi
                                </span>
                            @else
                                <span class="text-[#14274E]">-</span>
                            @endif
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
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            @if($transaksi->metode_pembayaran)
                                <span class="px-3 py-1 bg-[#1E56A0] text-white rounded-full text-xs font-bold">
                                    {{ strtoupper($transaksi->metode_pembayaran) }}
                                </span>
                            @else
                                <span class="text-[#14274E]">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('owner.detail-transaksi', $transaksi->id) }}" 
                               class="px-4 py-2 bg-[#1E56A0] text-white rounded-full hover:bg-[#14274E] transition font-bold text-xs">
                                <i class="fas fa-eye mr-1"></i>Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-6 py-16 text-center">
                            <i class="fas fa-inbox text-5xl text-[#D6E4F0] mb-3"></i>
                            <p class="text-[#14274E] font-semibold">Tidak ada data transaksi</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($transaksis->hasPages())
        <div class="bg-[#D6E4F0]/50 backdrop-blur px-6 py-4">
            {{ $transaksis->links() }}
        </div>
        @endif
    </div>
</div>
@endsection