<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Riwayat Transaksi | Flixora</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    /* ===========================
       ANIMASI ENTRY HALAMAN
    =========================== */
    @keyframes fadeInDown {
      from {
        opacity: 0;
        transform: translateY(-30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(40px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes slideInRight {
      from {
        opacity: 0;
        transform: translateX(-50px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    @keyframes scaleIn {
      from {
        opacity: 0;
        transform: scale(0.9);
      }
      to {
        opacity: 1;
        transform: scale(1);
      }
    }

    @keyframes bounceIn {
      0% {
        opacity: 0;
        transform: scale(0.3);
      }
      50% {
        transform: scale(1.05);
      }
      70% {
        transform: scale(0.9);
      }
      100% {
        opacity: 1;
        transform: scale(1);
      }
    }

    /* Animasi untuk empty state */
    @keyframes float {
      0%, 100% {
        transform: translateY(0);
      }
      50% {
        transform: translateY(-10px);
      }
    }

    /* Kelas animasi */
    .animate-fade-in-down {
      opacity: 0;
      animation: fadeInDown 0.8s ease-out forwards;
    }

    .animate-fade-in-up {
      opacity: 0;
      animation: fadeInUp 0.8s ease-out forwards;
    }

    .animate-slide-in-right {
      opacity: 0;
      animation: slideInRight 0.6s ease-out forwards;
    }

    .animate-scale-in {
      opacity: 0;
      animation: scaleIn 0.7s ease-out forwards;
    }

    .animate-bounce-in {
      opacity: 0;
      animation: bounceIn 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
    }

    .animate-float {
      animation: float 3s ease-in-out infinite;
    }

    /* Hover effects */
    .transaction-card {
      transition: all 0.3s ease;
    }

    .transaction-card:hover {
      transform: translateX(5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .transaction-card:hover img {
      transform: scale(1.05);
    }

    .transaction-card img {
      transition: transform 0.3s ease;
    }

    /* Button hover effects */
    .btn-action {
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }

    .btn-action::before {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      width: 0;
      height: 0;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.3);
      transform: translate(-50%, -50%);
      transition: width 0.6s, height 0.6s;
    }

    .btn-action:hover::before {
      width: 300px;
      height: 300px;
    }

    .btn-action:active {
      transform: scale(0.95);
    }

    /* Delay classes */
    .delay-100 { animation-delay: 0.1s; }
    .delay-200 { animation-delay: 0.2s; }
    .delay-300 { animation-delay: 0.3s; }
    .delay-400 { animation-delay: 0.4s; }
    .delay-500 { animation-delay: 0.5s; }
    .delay-600 { animation-delay: 0.6s; }
    .delay-700 { animation-delay: 0.7s; }
    .delay-800 { animation-delay: 0.8s; }
    .delay-900 { animation-delay: 0.9s; }
    .delay-1000 { animation-delay: 1s; }
  </style>
</head>

<body class="bg-gradient-to-r from-white to-[#E3F2FD] min-h-screen font-sans">

  <!-- NAVBAR -->
  @include('components.navbar')

  <!-- MAIN CONTENT -->
  <div class="container mx-auto px-4 py-8">
    
    <!-- PAGE TITLE -->
    <h1 class="animate-fade-in-down text-2xl font-bold text-center mb-8 text-[#14274E]">Riwayat Transaksi</h1>

    <!-- ALERT MESSAGES -->
    @if(session('error'))
      <div class="animate-bounce-in delay-200 mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
        {{ session('error') }}
      </div>
    @endif

    <!-- TRANSACTION LIST -->
    <div class="space-y-4">
      @forelse($transaksis as $index => $transaksi)
        <div class="animate-slide-in-right delay-{{ min(($index + 2) * 100, 900) }} transaction-card flex items-center bg-white rounded-xl shadow-md p-4 border border-gray-100">
          
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
                   class="text-[#1E56A0] text-sm font-semibold hover:underline hover:text-[#14274E] transition-colors duration-300">
                  Detail
                </a>

                <!-- Continue Payment Button (if pending) -->
                @if($transaksi->status === 'pending')
                  <a href="{{ route('transaksi.show', $transaksi->id) }}" 
                     class="btn-action inline-block bg-[#1E56A0] text-white text-xs px-3 py-1.5 rounded-full hover:bg-[#14274E] transition-all duration-300 hover:shadow-lg">
                    Lanjutkan Pembayaran
                  </a>
                @endif
              </div>
            </div>
          </div>
        </div>
      @empty
        <!-- EMPTY STATE -->
        <div class="animate-fade-in-up delay-300 text-center py-12">
          <svg class="animate-float mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <p class="mt-4 text-gray-500 text-lg">Belum ada transaksi</p>
          <p class="mt-2 text-gray-400 text-sm">Mulai pesan tiket favorit Anda sekarang!</p>
          <a href="/" class="btn-action mt-6 inline-block px-6 py-2 bg-[#1E56A0] text-white rounded-full hover:bg-[#14274E] transition-all duration-300 hover:shadow-lg">
            🎬 Pesan Tiket Sekarang
          </a>
        </div>
      @endforelse
    </div>
  </div>

  <!-- FOOTER -->
  @include('components.footer')
</body>
</html>