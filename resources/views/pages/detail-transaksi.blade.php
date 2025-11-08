<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Transaksi | Flixora</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
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

    @keyframes fadeInLeft {
      from {
        opacity: 0;
        transform: translateX(-40px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    @keyframes fadeInRight {
      from {
        opacity: 0;
        transform: translateX(40px);
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

    @keyframes slideInUp {
      from {
        opacity: 0;
        transform: translateY(30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
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

    @keyframes ticketSlide {
      from {
        opacity: 0;
        transform: translateX(-50px) rotate(-5deg);
      }
      to {
        opacity: 1;
        transform: translateX(0) rotate(0deg);
      }
    }

    /* Animasi untuk button pulse */
    @keyframes buttonPulse {
      0%, 100% {
        transform: scale(1);
        box-shadow: 0 4px 6px rgba(30, 86, 160, 0.3);
      }
      50% {
        transform: scale(1.03);
        box-shadow: 0 6px 12px rgba(30, 86, 160, 0.5);
      }
    }

    /* Animasi shine effect */
    @keyframes shine {
      0% {
        left: -100%;
      }
      100% {
        left: 100%;
      }
    }

    /* Kelas animasi */
    .animate-fade-in-down {
      opacity: 0;
      animation: fadeInDown 0.7s ease-out forwards;
    }

    .animate-fade-in-up {
      opacity: 0;
      animation: fadeInUp 0.8s ease-out forwards;
    }

    .animate-fade-in-left {
      opacity: 0;
      animation: fadeInLeft 0.7s ease-out forwards;
    }

    .animate-fade-in-right {
      opacity: 0;
      animation: fadeInRight 0.7s ease-out forwards;
    }

    .animate-scale-in {
      opacity: 0;
      animation: scaleIn 0.6s ease-out forwards;
    }

    .animate-slide-in-up {
      animation: slideInUp 0.7s ease-out forwards;
    }

    .animate-bounce-in {
      opacity: 0;
      animation: bounceIn 0.8s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
    }

    .animate-ticket-slide {
      opacity: 0;
      animation: ticketSlide 0.6s ease-out forwards;
    }

    .button-pulse {
      animation: buttonPulse 2s infinite;
    }

    /* Button shine effect */
    .button-shine {
      position: relative;
      overflow: hidden;
    }

    .button-shine::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255,255,255,0.4), transparent);
      transition: left 0.5s;
    }

    .button-shine:hover::before {
      animation: shine 0.8s;
    }

    /* Card hover effects */
    .ticket-card {
      transition: all 0.3s ease;
    }

    .ticket-card:hover {
      transform: translateY(-5px) scale(1.02);
      box-shadow: 0 8px 20px rgba(30, 86, 160, 0.2);
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

    /* Loading spinner for button */
    @keyframes spin {
      to { transform: rotate(360deg); }
    }

    .animate-spin {
      animation: spin 1s linear infinite;
    }
  </style>
</head>

<body class="bg-gradient-to-r from-white to-[#E3F2FD] min-h-screen font-sans">

  <!-- NAVBAR -->
  @include('components.navbar')

  <!-- MAIN CONTENT -->
  <div class="container mx-auto px-4 py-8 max-w-4xl">
    
    <!-- BACK BUTTON -->
    <div class="animate-fade-in-down mb-6">
      <a href="{{ route('transaksi.riwayat') }}" class="text-[#1E56A0] hover:text-[#14274E] font-semibold flex items-center transition-all duration-300 hover:translate-x-[-5px]">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Riwayat
      </a>
    </div>

    <!-- PAGE TITLE -->
    <h1 class="animate-fade-in-down delay-100 text-3xl font-bold text-center mb-8 text-[#14274E]">Detail Transaksi</h1>

    <!-- MAIN CARD -->
    <div class="animate-scale-in delay-200 bg-white rounded-2xl shadow-xl overflow-hidden">
      
      <!-- FILM INFO SECTION -->
      <div class="bg-gradient-to-r from-[#1E56A0] to-[#14274E] p-6 text-white">
        <div class="flex items-start space-x-6">
          <!-- Poster Image -->
          <img src="{{ asset('img/' . $transaksi->jadwal->film->poster) }}" 
               alt="Poster Film" 
               class="animate-fade-in-left delay-300 w-32 h-44 rounded-lg object-cover shadow-lg hover:scale-105 transition-transform duration-300">
          
          <!-- Film Details -->
          <div class="flex-1">
            <!-- Film Title -->
            <h2 class="animate-fade-in-right delay-300 text-2xl font-bold mb-3">{{ $transaksi->jadwal->film->judul }}</h2>
            
            <!-- Film Information -->
            <div class="space-y-2 text-sm">
              <p class="animate-fade-in-right delay-400"><strong>Studio:</strong> {{ $transaksi->jadwal->studio->nama_studio }}</p>
              <p class="animate-fade-in-right delay-500"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d F Y') }}</p>
              <p class="animate-fade-in-right delay-600"><strong>Waktu:</strong> {{ \Carbon\Carbon::parse($transaksi->jadwal->jamtayang)->format('H:i') }}</p>
              <p class="animate-fade-in-right delay-700"><strong>Durasi:</strong> {{ $transaksi->jadwal->film->durasi }} menit</p>
            </div>

            <!-- Status Badge -->
            @php
              $statusMap = [
                'pending' => [
                  'bg' => 'bg-yellow-400',
                  'text' => 'text-yellow-900',
                  'label' => 'Menunggu Pembayaran'
                ],
                'settlement' => [
                  'bg' => 'bg-green-400',
                  'text' => 'text-green-900',
                  'label' => 'Pembayaran Selesai'
                ],
                'batal' => [
                  'bg' => 'bg-red-400',
                  'text' => 'text-red-900',
                  'label' => 'Dibatalkan'
                ],
                'challenge' => [
                  'bg' => 'bg-orange-400',
                  'text' => 'text-orange-900',
                  'label' => 'Menunggu Verifikasi'
                ]
              ];
              $status = $statusMap[$transaksi->status] ?? [
                'bg' => 'bg-gray-400',
                'text' => 'text-gray-900',
                'label' => $transaksi->status
              ];
            @endphp
            
            <div class="animate-bounce-in delay-800 mt-4">
              <span class="inline-block px-4 py-2 text-sm font-bold rounded-full {{ $status['bg'] }} {{ $status['text'] }}">
                {{ $status['label'] }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- TRANSACTION DETAILS -->
      <div class="p-6 border-b border-gray-200">
        <h3 class="animate-fade-in-up delay-400 text-xl font-bold text-[#14274E] mb-4">Informasi Pembayaran</h3>
        
        <div class="grid grid-cols-2 gap-4 text-sm">
          <!-- Transaction ID -->
          <div class="animate-slide-in-up delay-500">
            <p class="text-gray-600">ID Transaksi</p>
            <p class="font-semibold text-[#14274E]">#{{ $transaksi->id }}</p>
          </div>

          <!-- Order Date -->
          <div class="animate-slide-in-up delay-600">
            <p class="text-gray-600">Tanggal Pemesanan</p>
            <p class="font-semibold text-[#14274E]">{{ $transaksi->tanggaltransaksi }}</p>
          </div>

          <!-- Payment Method -->
          @if($transaksi->metode_pembayaran)
            <div class="animate-slide-in-up delay-700">
              <p class="text-gray-600">Metode Pembayaran</p>
              <p class="font-semibold text-[#14274E]">{{ ucfirst($transaksi->metode_pembayaran) }}</p>
            </div>
          @endif

          <!-- Total Payment -->
          <div class="animate-slide-in-up delay-800">
            <p class="text-gray-600">Total Pembayaran</p>
            <p class="font-bold text-xl text-[#1E56A0]">Rp {{ number_format($transaksi->totalharga, 0, ',', '.') }}</p>
          </div>
        </div>
      </div>

      <!-- TICKETS SECTION -->
      <div class="p-6">
        <h3 class="animate-fade-in-up delay-500 text-xl font-bold text-[#14274E] mb-4">Tiket</h3>
        
        <!-- Ticket List -->
        <div class="space-y-3">
          @forelse($transaksi->tikets as $index => $tiket)
            <div class="animate-ticket-slide delay-{{ min(($index + 6) * 100, 1000) }} ticket-card border-2 border-dashed border-[#1E56A0] rounded-lg p-4 bg-blue-50">
              <div class="flex items-center justify-between">
                <!-- Ticket Number & Seat -->
                <div class="flex items-center space-x-4">
                  <div class="bg-[#1E56A0] text-white w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg shadow-md">
                    {{ $index + 1 }}
                  </div>
                  <div>
                    <p class="text-sm text-gray-600">Kursi</p>
                    <p class="text-xl font-bold text-[#14274E]">{{ $tiket->kursi->nomor_kursi }}</p>
                  </div>
                </div>
                
                <!-- Ticket Code -->
                <div class="text-right">
                  <p class="text-sm text-gray-600">Kode Tiket</p>
                  @if($tiket->kodetiket)
                    <p class="text-lg font-mono font-bold text-[#1E56A0]">{{ $tiket->kodetiket }}</p>
                  @else
                    <p class="text-sm text-gray-400 italic">Belum tersedia</p>
                  @endif
                </div>
              </div>
            </div>
          @empty
            <!-- Empty State -->
            <div class="animate-fade-in-up delay-700 text-center py-8 text-gray-500">
              <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
              </svg>
              <p>Tidak ada tiket ditemukan</p>
            </div>
          @endforelse
        </div>

        <!-- TOTAL SUMMARY -->
        <div class="animate-fade-in-up delay-900 mt-6 pt-6 border-t-2 border-gray-200">
          <div class="flex justify-between items-center">
            <!-- Total Tickets -->
            <div>
              <p class="text-gray-600">Total Tiket</p>
              <p class="text-2xl font-bold text-[#14274E]">{{ $transaksi->tikets->count() }} Tiket</p>
            </div>

            <!-- Total Price -->
            <div class="text-right">
              <p class="text-gray-600">Total Harga</p>
              <p class="text-3xl font-bold text-[#1E56A0]">Rp {{ number_format($transaksi->totalharga, 0, ',', '.') }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- ACTION BUTTONS -->
      <div class="p-6 bg-gray-50 border-t border-gray-200">
        @if($transaksi->status === 'pending')
          <!-- Pay Now Button -->
          <button id="payButton" onclick="payNow()" 
                  class="animate-slide-in-up delay-1000 button-shine button-pulse w-full bg-[#1E56A0] text-white py-3 rounded-lg font-semibold hover:bg-[#14274E] transition-all duration-300 shadow-lg hover:shadow-xl transform hover:scale-105">
            💳 Bayar Sekarang
          </button>
          
          <!-- Payment Script -->
          <script>
            function payNow() {
              const button = document.getElementById('payButton');
              const originalContent = button.innerHTML;
              
              // Animasi loading
              button.innerHTML = '<span class="flex items-center justify-center gap-2"><svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Memproses...</span>';
              button.disabled = true;
              button.classList.remove('button-pulse');
              
              snap.pay('{{ $transaksi->snap_token }}', {
                onSuccess: function(result) {
                  // Animasi sukses
                  button.innerHTML = '<span class="flex items-center justify-center gap-2"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Berhasil!</span>';
                  button.classList.add('bg-green-600');
                  
                  setTimeout(() => {
                    window.location.href = '{{ route("transaksi.riwayat") }}';
                  }, 1000);
                },
                onPending: function(result) {
                  button.innerHTML = originalContent;
                  button.disabled = false;
                  button.classList.add('button-pulse');
                  alert('⏳ Menunggu pembayaran');
                },
                onError: function(result) {
                  button.innerHTML = originalContent;
                  button.disabled = false;
                  button.classList.add('button-pulse');
                  alert('❌ Pembayaran gagal');
                },
                onClose: function() {
                  button.innerHTML = originalContent;
                  button.disabled = false;
                  button.classList.add('button-pulse');
                  console.log('Popup ditutup');
                }
              });
            }
          </script>

        @elseif($transaksi->status === 'settlement')
          <!-- Success Message -->
          <div class="animate-bounce-in delay-700 text-center">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
              <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
              </svg>
            </div>
            <p class="text-lg font-semibold text-green-700">✅ Pembayaran Berhasil!</p>
            <p class="text-sm text-gray-600 mt-2">Tunjukkan kode tiket di atas saat memasuki studio</p>
          </div>
        @endif
      </div>
    </div>
  </div>

  <!-- FOOTER -->
  @include('components.footer')
</body>
</html>