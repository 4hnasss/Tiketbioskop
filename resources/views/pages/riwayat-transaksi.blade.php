<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Riwayat Transaksi | Flixora</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-white to-[#E3F2FD] min-h-screen font-sans">

  <!-- NAVBAR -->
  @include('components.navbar')

  <!-- MAIN CONTENT -->
  <div class="container mx-auto px-4 py-8">
    
    <!-- PAGE TITLE -->
    <h1 class="text-2xl font-bold text-center mb-8 text-[#14274E]">Riwayat Transaksi</h1>

    <!-- ALERT MESSAGES -->
    @if(session('error'))
      <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
        {{ session('error') }}
      </div>
    @endif

    <!-- TRANSACTION LIST -->
    <div class="space-y-4">
      @forelse($transaksis as $transaksi)
        <div class="flex items-center bg-white rounded-xl shadow-md p-4 border border-gray-100 hover:shadow-lg transition duration-200">
          
          <!-- POSTER IMAGE -->
          <img src="{{ asset('img/' . $transaksi->jadwal->film->poster) }}" 
               alt="Poster Film" 
               class="w-20 h-28 rounded-lg object-cover shadow-sm">

          <!-- TRANSACTION DETAILS -->
          <div class="flex-1 ml-4">
            <!-- Film Title -->
            <h2 class="text-lg font-semibold text-[#14274E] mb-1">
              {{ $transaksi->jadwal->film->judul }}
            </h2>
            
            <!-- Seat Information -->
            <p class="text-gray-600 text-xs mb-1">
              <strong>Kursi:</strong> 
              @if(is_array($transaksi->kursi))
                {{ implode(', ', $transaksi->kursi) }}
              @elseif(is_string($transaksi->kursi))
                @php
                  $kursiArray = json_decode($transaksi->kursi, true);
                @endphp
                @if(is_array($kursiArray))
                  {{ implode(', ', $kursiArray) }}
                @else
                  {{ $transaksi->kursi }}
                @endif
              @else
                <span class="text-gray-400">-</span>
              @endif
            </p>

            <!-- Date -->
            <p class="text-gray-600 text-xs">
              <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d-m-Y') }}
            </p>

            <!-- Total Price -->
            <p class="text-gray-600 text-xs">
              <strong>Total:</strong> Rp {{ number_format($transaksi->totalharga, 0, ',', '.') }}
            </p>

            <!-- Payment Method -->
            @if($transaksi->metode_pembayaran)
              <p class="text-gray-600 text-xs">
                <strong>Metode:</strong> {{ ucfirst($transaksi->metode_pembayaran) }}
              </p>
            @endif

            <!-- Status Mapping -->
            @php
              $map = [
                'pending' => [
                  'bg' => 'bg-yellow-100',
                  'text' => 'text-yellow-700',
                  'label' => 'Menunggu Pembayaran'
                ],
                'settlement' => [
                  'bg' => 'bg-green-100',
                  'text' => 'text-green-700',
                  'label' => 'Pembayaran Selesai'
                ],
                'expired' => [
                  'bg' => 'bg-red-100',
                  'text' => 'text-red-700',
                  'label' => 'Dibatalkan'
                ],
                'challenge' => [
                  'bg' => 'bg-orange-100',
                  'text' => 'text-orange-700',
                  'label' => 'Menunggu Verifikasi'
                ]
              ];
              $s = $map[$transaksi->status] ?? [
                'bg' => 'bg-gray-100',
                'text' => 'text-gray-700',
                'label' => $transaksi->status
              ];
            @endphp

            <!-- Snap Token -->
            <p class="text-gray-600 text-xs">
              <strong>Kode Token:</strong> {{ $transaksi->snap_token }}
            </p>

            <!-- STATUS AND ACTIONS -->
            <div class="mt-3 flex items-center justify-between">
              <!-- Status Badge -->
              <span class="inline-block px-2 py-0.5 text-xs font-medium rounded-full {{ $s['bg'] }} {{ $s['text'] }}">
                {{ $s['label'] }}
              </span>

              <!-- Action Buttons -->
              <div class="space-x-2">
                <!-- Detail Link -->
                <a href="{{ route('transaksidetail', $transaksi->id) }}" 
                   class="text-[#1E56A0] text-sm font-semibold hover:underline">
                  Detail
                </a>

                <!-- Continue Payment Button (if pending) -->
                @if($transaksi->status === 'pending')
                  <a href="{{ route('transaksi.show', $transaksi->id) }}" 
                     class="bg-[#1E56A0] text-white text-xs px-3 py-1.5 rounded-full hover:bg-[#14274E] transition">
                    Lanjutkan Pembayaran
                  </a>
                @endif
              </div>
            </div>
          </div>
        </div>
      @empty
        <!-- EMPTY STATE -->
        <div class="text-center py-12">
          <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <p class="mt-4 text-gray-500">Belum ada transaksi</p>
          <a href="/" class="mt-4 inline-block px-6 py-2 bg-[#1E56A0] text-white rounded-full hover:bg-[#14274E] transition">
            Pesan Tiket Sekarang
          </a>
        </div>
      @endforelse
    </div>
  </div>

  <!-- FOOTER -->
  @include('components.footer')
</body>
</html>