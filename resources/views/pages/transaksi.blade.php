<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Detail Pembayaran | Flixora</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
</head>

<body class="bg-gradient-to-b from-[#F5F8FC] to-[#E3EAF5] min-h-screen flex flex-col">

  <!-- MAIN CONTENT -->
  <main class="flex-grow flex flex-col items-center justify-center px-4">
    <div class="bg-white shadow-md rounded-xl w-full max-w-lg p-8 text-center border border-gray-200">
      
      <!-- LOGO -->
      <img src="{{ asset('img/Brand.png') }}" alt="Flixora" class="h-10 mx-auto mb-2">
      
      <!-- TITLE -->
      <h1 class="text-xl font-semibold text-gray-800 mb-6">Detail Pembayaran</h1>

      <!-- ✅ COUNTDOWN TIMER (HANYA TAMPIL JIKA PENDING) -->
      @if($transaksi->status === 'pending' && $transaksi->payment_expired_at)
        <div id="countdownContainer" class="bg-yellow-50 border-2 border-yellow-400 rounded-lg p-4 mb-6">
          <div class="flex items-center justify-center gap-2 text-yellow-800 mb-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="font-semibold">Waktu Pembayaran</span>
          </div>
          <div id="countdown" class="text-3xl font-bold text-red-600">
            --:--
          </div>
          <p class="text-xs text-gray-600 mt-2">Selesaikan pembayaran sebelum waktu habis</p>
        </div>
      @endif

      <!-- ✅ EXPIRED MESSAGE -->
      @if($transaksi->status === 'expired')
        <div class="bg-red-50 border-2 border-red-400 rounded-lg p-4 mb-6">
          <div class="flex items-center justify-center gap-2 text-red-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="font-semibold">Waktu Pembayaran Habis</span>
          </div>
          <p class="text-sm text-gray-600 mt-2">Transaksi ini telah kadaluarsa. Silakan buat transaksi baru.</p>
        </div>
      @endif

      <!-- TRANSACTION DETAILS -->
      <div class="text-left mb-6">
        <!-- Film -->
        <div class="grid grid-cols-2 py-1 border-b border-gray-200">
          <p class="font-semibold">Film</p>
          <p>{{ $transaksi->jadwal->film->judul }}</p>
        </div>

        <!-- Jadwal -->
        <div class="grid grid-cols-2 py-1 border-b border-gray-200">
          <p class="font-semibold">Jadwal</p>
          <p>{{ $transaksi->jadwal->tanggal }} {{ $transaksi->jadwal->jamtayang }}</p>
        </div>

        <!-- Studio -->
        <div class="grid grid-cols-2 py-1 border-b border-gray-200">
          <p class="font-semibold">Studio</p>
          <p>{{ $transaksi->jadwal->studio->nama_studio }}</p>
        </div>

        <!-- Kursi -->
        <div class="grid grid-cols-2 py-1 border-b border-gray-200">
          <p class="font-semibold">Kursi</p>
          <p>
            @if(is_array($transaksi->kursi))
              {{ implode(', ', $transaksi->kursi) }}
            @else
              {{ $transaksi->kursi }}
            @endif
          </p>
        </div>

        <!-- Total Harga -->
        <div class="grid grid-cols-2 py-2 border-b border-gray-200 mt-2">
          <p class="font-semibold">Total Harga</p>
          <p>Rp {{ number_format($transaksi->totalharga, 0, ',', '.') }}</p>
        </div>

        <!-- Status -->
        <div class="grid grid-cols-2 py-2 border-b border-gray-200">
          <p class="font-semibold">Status</p>
          <p id="statusText" class="font-semibold 
            {{ $transaksi->status === 'settlement' ? 'text-green-600' : 
               ($transaksi->status === 'expired' ? 'text-red-600' : 'text-yellow-600') }}">
            @if($transaksi->status === 'settlement')
              Pembayaran Berhasil
            @elseif($transaksi->status === 'expired')
              Kadaluarsa
            @elseif($transaksi->status === 'pending')
              Menunggu Pembayaran
            @else
              {{ ucfirst($transaksi->status) }}
            @endif
          </p>
        </div>
      </div>

      <!-- ACTION BUTTONS -->
      @if($transaksi->status === 'pending')
        <!-- Pay Now Button -->
        <button id="payNow" class="w-full bg-[#0B1B3F] text-white py-2.5 rounded-md font-semibold hover:bg-[#152a6d] transition">
          Bayar Sekarang
        </button>
      @elseif($transaksi->status === 'settlement')
        <!-- Success Message -->
        <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-4">
          <div class="flex items-center justify-center gap-2 text-green-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span class="font-semibold">Pembayaran Berhasil!</span>
          </div>
        </div>

        <!-- View Transaction History Button -->
        <a href="/riwayat-transaksi" class="w-full inline-block bg-[#0B1B3F] text-white py-2.5 rounded-md font-semibold hover:bg-[#152a6d] transition">
          Lihat Riwayat Transaksi
        </a>
      @elseif($transaksi->status === 'expired')
        <!-- Back to Home Button -->
        <a href="/" class="w-full inline-block bg-[#0B1B3F] text-white py-2.5 rounded-md font-semibold hover:bg-[#152a6d] transition">
          Kembali ke Beranda
        </a>
      @endif
    </div>
  </main>

  <script>
    // ===========================
    // DATA INITIALIZATION
    // ===========================
    const snapToken = "{{ $transaksi->snap_token }}";
    const transaksiId = "{{ $transaksi->id }}";
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    let currentStatus = "{{ $transaksi->status }}";
    
    @if($transaksi->payment_expired_at)
      const expiredAt = new Date("{{ $transaksi->payment_expired_at->toIso8601String() }}");
    @else
      const expiredAt = null;
    @endif

    // ===========================
    // COUNTDOWN TIMER
    // ===========================
    let countdownInterval = null;

    function startCountdown() {
      if (!expiredAt || currentStatus !== 'pending') return;

      const countdownElement = document.getElementById('countdown');
      const countdownContainer = document.getElementById('countdownContainer');
      
      if (!countdownElement) return;

      countdownInterval = setInterval(() => {
        const now = new Date().getTime();
        const distance = expiredAt.getTime() - now;

        if (distance <= 0) {
          // Waktu habis
          clearInterval(countdownInterval);
          clearInterval(statusCheckInterval);
          
          countdownElement.textContent = "00:00";
          countdownContainer.classList.remove('bg-yellow-50', 'border-yellow-400');
          countdownContainer.classList.add('bg-red-50', 'border-red-400');
          
          // Update status to expired
          currentStatus = 'expired';
          
          console.log('Countdown reached 0 - Payment expired');
          
          // IMPORTANT: Redirect immediately
          alert('Waktu pembayaran telah habis!');
          window.location.href = '/riwayat-transaksi';
          
          return;
        }

        // Hitung menit dan detik
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        // Tampilkan countdown
        countdownElement.textContent = 
          String(minutes).padStart(2, '0') + ':' + 
          String(seconds).padStart(2, '0');

        // Ubah warna jika kurang dari 30 detik
        if (distance < 30000) {
          countdownElement.classList.add('text-red-600', 'animate-pulse');
        }
      }, 1000);
    }

    // Start countdown saat halaman load
    if (currentStatus === 'pending' && expiredAt) {
      startCountdown();
    }

    // ===========================
    // PERIODIC STATUS CHECK
    // ===========================
    let statusCheckInterval = null;

    function checkPaymentStatus() {
      if (currentStatus !== 'pending') return;

      statusCheckInterval = setInterval(() => {
        fetch(`/transaksi/${transaksiId}/check-status`, {
          method: 'GET',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          }
        })
        .then(response => response.json())
        .then(data => {
          console.log('Status check response:', data);
          
          if (data.expired || data.status === 'expired') {
            // Payment expired
            clearInterval(statusCheckInterval);
            clearInterval(countdownInterval);
            currentStatus = 'expired';
            
            console.log('Payment expired - redirecting...');
            
            // Redirect ke riwayat transaksi
            alert('Waktu pembayaran telah habis. Transaksi dibatalkan.');
            window.location.href = '/riwayat-transaksi';
            
          } else if (data.status === 'settlement') {
            // Payment success
            clearInterval(statusCheckInterval);
            clearInterval(countdownInterval);
            currentStatus = 'settlement';
            
            console.log('Payment success - reloading...');
            window.location.reload();
          }
        })
        .catch(error => {
          console.error('Error checking status:', error);
        });
      }, 3000); // Check setiap 3 detik (lebih cepat)
    }

    // Start status check
    if (currentStatus === 'pending') {
      checkPaymentStatus();
    }

    // ===========================
    // PAYMENT HANDLER
    // ===========================
    const payBtn = document.getElementById("payNow");
    
    if (payBtn) {
      payBtn.addEventListener("click", function () {
        // Cek apakah waktu masih valid
        if (expiredAt) {
          const now = new Date().getTime();
          if (now >= expiredAt.getTime()) {
            alert('Waktu pembayaran telah habis. Silakan buat transaksi baru.');
            window.location.reload();
            return;
          }
        }

        snap.pay(snapToken, {
          onSuccess: function (result) { 
            console.log('Payment success:', result);
            clearInterval(countdownInterval);
            clearInterval(statusCheckInterval);
            updateStatus('settlement', result.payment_type); 
          },
          onPending: function (result) { 
            console.log('Payment pending:', result);
            updateStatus('pending'); 
          },
          onError: function (result) { 
            console.error('Payment error:', result);
            alert("Pembayaran gagal. Silakan coba lagi."); 
          },
          onClose: function() {
            console.log('Payment popup closed');
          }
        });
      });
    }

    // ===========================
    // UPDATE STATUS FUNCTION
    // ===========================
    function updateStatus(status, metode) {
      console.log('Updating status to:', status);
      
      fetch(`/transaksi/${transaksiId}/update-status`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": csrfToken,
          "Accept": "application/json"
        },
        body: JSON.stringify({ 
          status: status, 
          metode_pembayaran: metode || 'midtrans' 
        })
      })
      .then(response => response.json())
      .then(data => {
        console.log('Update status response:', data);
        
        if (data.success) {
          currentStatus = status;
          
          // Redirect if payment successful
          if (status === 'settlement') {
            alert("Pembayaran berhasil! Tiket Anda sedang diproses.");
            window.location.reload();
          }
        } else {
          console.error('Failed to update status:', data);
        }
      })
      .catch(error => {
        console.error('Error:', error);
      });
    }

    // ===========================
    // CLEANUP ON PAGE UNLOAD
    // ===========================
    window.addEventListener('beforeunload', () => {
      if (countdownInterval) clearInterval(countdownInterval);
      if (statusCheckInterval) clearInterval(statusCheckInterval);
    });
  </script>
</body>
</html>