<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Riwayat Tiket | Kasir - Flixora</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-b from-[#f7faff] to-[#dbe9ff] min-h-screen font-sans text-gray-800">
@include('components.nav')

<div class="container mx-auto px-6 py-10">
  <div class="max-w-7xl mx-auto">
    
    {{-- Header --}}
    <div class="flex justify-between items-center mb-8">
      <div>
        <h1 class="text-3xl font-bold text-[#0A1D56] mb-2">Riwayat Tiket</h1>
        <p class="text-gray-600">Kelola dan lacak semua tiket yang telah diterbitkan</p>
      </div>
    </div>

    {{-- Search Bar --}}
    <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 border border-gray-100">
      <form method="GET" action="{{ route('riwayat-tiket-kasir') }}" class="flex gap-4">
        <div class="flex-1 relative">
          <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
          </svg>
          <input 
            type="text" 
            name="search" 
            value="{{ $search ?? '' }}"
            placeholder="Cari kode tiket... (contoh: FLX-20250104-T1-A1-ABCD)" 
            class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#1E56A0] focus:outline-none"
          >
        </div>
        <button type="submit" class="bg-[#0A1D56] text-white px-8 py-3 rounded-xl hover:bg-[#1E56A0] transition font-semibold">
          Cari
        </button>
        @if($search)
          <a href="{{ route('riwayat-tiket-kasir') }}" class="bg-gray-200 text-gray-700 px-8 py-3 rounded-xl hover:bg-gray-300 transition font-semibold">
            Reset
          </a>
        @endif
      </form>
    </div>

    {{-- Hasil Pencarian --}}
    @if($search)
      <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded">
        <p class="text-blue-700">
          <strong>Hasil pencarian:</strong> "{{ $search }}" - 
          <span class="font-semibold">{{ $tikets->total() }} tiket ditemukan</span>
        </p>
      </div>
    @endif

    {{-- Tiket List --}}
    @if($tikets->count() > 0)
      <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gradient-to-r from-[#0A1D56] to-[#1E56A0] text-white">
              <tr>
                <th class="px-6 py-4 text-left text-sm font-semibold">Kode Tiket</th>
                <th class="px-6 py-4 text-left text-sm font-semibold">Film</th>
                <th class="px-6 py-4 text-left text-sm font-semibold">Studio</th>
                <th class="px-6 py-4 text-left text-sm font-semibold">Kursi</th>
                <th class="px-6 py-4 text-left text-sm font-semibold">Tanggal & Waktu</th>
                <th class="px-6 py-4 text-left text-sm font-semibold">Customer</th>
                <th class="px-6 py-4 text-left text-sm font-semibold">Diterbitkan</th>
                <th class="px-6 py-4 text-center text-sm font-semibold">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              @foreach($tikets as $tiket)
                <tr class="hover:bg-gray-50 transition">
                  <td class="px-6 py-4">
                    <span class="font-mono text-sm font-semibold text-[#1E56A0] bg-blue-50 px-3 py-1 rounded">
                      {{ $tiket->kodetiket }}
                    </span>
                  </td>
                  <td class="px-6 py-4">
                    <p class="font-semibold text-gray-900">{{ $tiket->jadwal->film->judul }}</p>
                    <p class="text-xs text-gray-500">{{ $tiket->jadwal->film->genre }}</p>
                  </td>
                  <td class="px-6 py-4 text-gray-700">
                    {{ $tiket->jadwal->studio->nama_studio }}
                  </td>
                  <td class="px-6 py-4">
                    <span class="px-3 py-1 bg-[#1E56A0] text-white text-sm font-semibold rounded">
                      {{ $tiket->kursi->nomorkursi }}
                    </span>
                  </td>
                  <td class="px-6 py-4">
                    <p class="font-semibold text-gray-900">
                      {{ \Carbon\Carbon::parse($tiket->jadwal->tanggal)->translatedFormat('d M Y') }}
                    </p>
                    <p class="text-sm text-gray-600">
                      {{ \Carbon\Carbon::parse($tiket->jadwal->jamtayang)->format('H:i') }} WIB
                    </p>
                  </td>
                  <td class="px-6 py-4">
                    <p class="font-semibold text-gray-900">{{ $tiket->transaksi->user->name }}</p>
                    <p class="text-xs text-gray-500">ID: #{{ $tiket->transaksi->id }}</p>
                  </td>
                  <td class="px-6 py-4 text-sm text-gray-600">
                    {{ $tiket->created_at->translatedFormat('d M Y H:i') }}
                  </td>
                  <td class="px-6 py-4 text-center">
                    <a href="{{ route('detail-tiket-kasir', $tiket->id) }}" 
                       class="inline-flex items-center gap-2 bg-[#0A1D56] text-white px-4 py-2 rounded-lg hover:bg-[#1E56A0] transition text-sm font-semibold">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                      </svg>
                      Detail
                    </a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        {{-- Pagination --}}
        @if($tikets->hasPages())
          <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
            {{ $tikets->links() }}
          </div>
        @endif
      </div>

      {{-- Summary --}}
      <div class="mt-6 bg-white rounded-xl shadow p-6 border border-gray-100">
        <div class="flex items-center justify-between">
          <div class="flex items-center gap-3">
            <svg class="w-8 h-8 text-[#1E56A0]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
            </svg>
            <div>
              <p class="text-sm text-gray-600">Total Tiket Terdaftar</p>
              <p class="text-2xl font-bold text-[#0A1D56]">{{ $tikets->total() }} Tiket</p>
            </div>
          </div>
          <div class="text-right">
            <p class="text-sm text-gray-600">Halaman {{ $tikets->currentPage() }} dari {{ $tikets->lastPage() }}</p>
          </div>
        </div>
      </div>

    @else
      <div class="bg-white rounded-2xl shadow-lg p-12 text-center border border-gray-100">
        <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
        </svg>
        <h3 class="text-xl font-bold text-gray-900 mb-2">
          @if($search)
            Tidak Ada Tiket Ditemukan
          @else
            Belum Ada Tiket
          @endif
        </h3>
        <p class="text-gray-600 mb-6">
          @if($search)
            Tidak ada tiket dengan kode "{{ $search }}"
          @else
            Tiket akan muncul setelah transaksi berhasil
          @endif
        </p>
        @if($search)
          <a href="{{ route('riwayat-tiket-kasir') }}" class="inline-block bg-[#0A1D56] text-white px-6 py-3 rounded-xl hover:bg-[#1E56A0] transition font-semibold">
            Lihat Semua Tiket
          </a>
        @endif
      </div>
    @endif

  </div>
</div>

@include('components.footer')

</body>
</html>