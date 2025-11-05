{{-- resources/views/kasir/riwayat-transaksi.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi - Kasir</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(to right, #ffffff, #D6E4F0);
        }
    </style>
</head>
<body>
    @include('components.nav')

    <main class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
        {{-- Alert Messages --}}
        @if(session('error'))
            <div class="bg-red-50 border-2 border-red-400 text-red-800 p-5 mb-6 rounded-xl shadow-md">
                <p class="font-bold"><i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}</p>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-50 border-2 border-green-400 text-green-800 p-5 mb-6 rounded-xl shadow-md">
                <p class="font-bold"><i class="fas fa-check-circle mr-2"></i>{{ session('success') }}</p>
            </div>
        @endif

        <!-- Header -->
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-[#14274E] flex items-center">
                <i class="fas fa-history mr-3 text-[#1E56A0]"></i>
                Riwayat Transaksi
            </h2>
            <p class="text-[#14274E]/70 mt-2">Semua transaksi dari pengguna</p>
        </div>

        <!-- Filter -->
        <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl p-8 mb-8 border-2 border-[#D6E4F0]">
            <form method="GET" action="{{ route('riwayat-transaksi') }}" id="filterForm" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-bold text-[#14274E] mb-2">Pencarian</label>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="ID atau Nama User..."
                           class="w-full px-4 py-3 border-2 border-[#D6E4F0] rounded-xl focus:ring-2 focus:ring-[#1E56A0] focus:border-transparent bg-white/50">
                </div>
                <div>
                    <label class="block text-sm font-bold text-[#14274E] mb-2">Snap Token</label>
                    <input type="text" 
                           name="snap_token" 
                           id="snapTokenInput"
                           value="{{ request('snap_token') }}"
                           placeholder="Ketik untuk mencari..."
                           class="w-full px-4 py-3 border-2 border-[#D6E4F0] rounded-xl focus:ring-2 focus:ring-[#1E56A0] focus:border-transparent bg-white/50">
                </div>
                <div>
                    <label class="block text-sm font-bold text-[#14274E] mb-2">Status</label>
                    <select name="status" 
                            id="statusSelect"
                            class="w-full px-4 py-3 border-2 border-[#D6E4F0] rounded-xl focus:ring-2 focus:ring-[#1E56A0] focus:border-transparent bg-white/50">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="settlement" {{ request('status') == 'settlement' ? 'selected' : '' }}>Settlement</option>
                        <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Batal</option>
                    </select>
                </div>
                <div class="flex items-end space-x-2">
                    <button type="submit" class="flex-1 bg-gradient-to-r from-[#1E56A0] to-[#14274E] hover:scale-105 text-white px-4 py-3 rounded-xl transition transform shadow-lg font-semibold">
                        Filter
                    </button>
                    <a href="{{ route('riwayat-transaksi') }}" class="bg-[#D6E4F0] hover:bg-[#1E56A0] hover:text-white text-[#14274E] px-4 py-3 rounded-xl transition font-semibold">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl overflow-hidden border-2 border-[#D6E4F0]">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-[#D6E4F0]">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider">ID</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider">Kode Token</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider">User</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider">Film</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider">Kursi</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider">Total</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider">Metode</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#D6E4F0]">
                        @forelse($transaksis as $transaksi)
                            <tr class="hover:bg-[#D6E4F0]/50 transition">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-[#1E56A0]">
                                    #{{ $transaksi->id }}
                                </td>
                                <td class="px-6 py-4 text-sm text-[#14274E]/70">
                                    <div class="max-w-[120px] truncate font-mono text-xs" title="{{ $transaksi->snap_token }}">
                                        {{ $transaksi->snap_token }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="font-bold text-[#14274E]">{{ $transaksi->user->name ?? 'N/A' }}</div>
                                    <div class="text-xs text-[#14274E]/70 truncate max-w-[150px]">{{ $transaksi->user->email ?? '' }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <div class="font-bold text-[#14274E]">{{ Str::limit($transaksi->jadwal->film->judul ?? 'N/A', 30) }}</div>
                                    <div class="text-xs text-[#14274E]/70">
                                        {{ \Carbon\Carbon::parse($transaksi->jadwal->tanggal ?? now())->format('d/m/Y') }} - 
                                        {{ \Carbon\Carbon::parse($transaksi->jadwal->jamtayang ?? now())->format('H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    @php
                                        $kursiArray = is_array($transaksi->kursi) ? $transaksi->kursi : json_decode($transaksi->kursi, true);
                                        $jumlahKursi = is_array($kursiArray) ? count($kursiArray) : 0;
                                    @endphp
                                    <span class="font-bold text-[#14274E]">{{ $jumlahKursi }}</span> kursi
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-[#14274E]">
                                    Rp {{ number_format($transaksi->totalharga, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-gradient-to-r from-[#1E56A0] to-[#14274E] text-white">
                                        {{ strtoupper($transaksi->metode_pembayaran ?? 'N/A') }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($transaksi->status == 'settlement')
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800">
                                            Settlement
                                        </span>
                                    @elseif($transaksi->status == 'pending')
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @else
                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800">
                                            {{ ucfirst($transaksi->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <a href="{{ route('detail-transaksi', $transaksi->id) }}" 
                                       class="text-[#1E56A0] hover:text-[#14274E] font-bold transition">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-6 py-16 text-center">
                                    <i class="fas fa-inbox text-5xl text-[#D6E4F0] mb-3"></i>
                                    <p class="text-[#14274E]/70 text-lg">Tidak ada transaksi ditemukan</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($transaksis->hasPages())
                <div class="px-6 py-4 border-t-2 border-[#D6E4F0] bg-[#D6E4F0]/30">
                    {{ $transaksis->appends(request()->query())->links() }}
                </div>
            @endif
        </div>

        <!-- Summary Statistics -->
        @if($transaksis->count() > 0)
            <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl p-6 border-2 border-[#D6E4F0]">
                    <p class="text-sm text-[#14274E]/70 font-semibold mb-2">Total Transaksi</p>
                    <p class="text-3xl font-bold text-[#1E56A0]">{{ $transaksis->total() }}</p>
                </div>
                <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl p-6 border-2 border-[#D6E4F0]">
                    <p class="text-sm text-[#14274E]/70 font-semibold mb-2">Halaman</p>
                    <p class="text-3xl font-bold text-[#14274E]">{{ $transaksis->currentPage() }} / {{ $transaksis->lastPage() }}</p>
                </div>
                <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl p-6 border-2 border-[#D6E4F0]">
                    <p class="text-sm text-[#14274E]/70 font-semibold mb-2">Menampilkan</p>
                    <p class="text-3xl font-bold text-[#14274E]">{{ $transaksis->count() }} dari {{ $transaksis->total() }}</p>
                </div>
            </div>
        @endif
    </main>

    @include('components.footer')

    <script>
        let snapTokenTimeout;
        const snapTokenInput = document.getElementById('snapTokenInput');
        const filterForm = document.getElementById('filterForm');
        const statusSelect = document.getElementById('statusSelect');

        if (snapTokenInput) {
            snapTokenInput.addEventListener('input', function() {
                clearTimeout(snapTokenTimeout);
                snapTokenTimeout = setTimeout(function() {
                    filterForm.submit();
                }, 500);
            });
        }

        if (statusSelect) {
            statusSelect.addEventListener('change', function() {
                filterForm.submit();
            });
        }
    </script>
</body>
</html>