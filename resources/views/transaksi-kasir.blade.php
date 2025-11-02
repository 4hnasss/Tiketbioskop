<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Transaksi Pembayaran | Kasir - Flixora</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
</head>

<body class="bg-gradient-to-b from-[#f7faff] to-[#dbe9ff] min-h-screen font-sans text-gray-800">
@include('components.nav')

  <div class="container mx-auto px-6 py-10">
    <div class="max-w-4xl mx-auto">
      
      {{-- Header --}}
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-[#0A1D56] mb-2">Pembayaran Tiket</h1>
        <p class="text-gray-600">ID Transaksi: <span class="font-semibold text-[#1E56A0]">#{{ $transaksi->id }}</span></p>
      </div>

      <div class="grid md:grid-cols-2 gap-6">
        
        {{-- Detail Transaksi --}}
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
          <h2 class="text-xl font-bold text-[#0A1D56] mb-4 pb-3 border-b border-gray-200">Detail Pesanan</h2>
          
          <div class="space-y-4">
            <div class="flex items-start gap-3">
              <svg class="w-5 h-5 text-[#1E56A0] mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"></path>
              </svg>
              <div class="flex-1">
                <p class="text-sm text-gray-600">Film</p>
                <p class="font-semibold text-gray-900">{{ $transaksi->jadwal->film->judul }}</p>
              </div>
            </div>

            <div class="flex items-start gap-3">
              <svg class="w-5 h-5 text-[#1E56A0] mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
              </svg>
              <div class="flex-1">
                <p class="text-sm text-gray-600">Tanggal & Waktu</p>
                <p class="font-semibold text-gray-900">
                  {{ \Carbon\Carbon::parse($transaksi->jadwal->tanggal)->translatedFormat('d F Y') }}
                </p>
                <p class="text-sm text-gray-700">{{ \Carbon\Carbon::parse($transaksi->jadwal->jamtayang)->format('H:i') }} WIB</p>
              </div>
            </div>

            <div class="flex items-start gap-3">
              <svg class="w-5 h-5 text-[#1E56A0] mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
              </svg>
              <div class="flex-1">
                <p class="text-sm text-gray-600">Studio</p>
                <p class="font-semibold text-gray-900">{{ $transaksi->jadwal->studio->namastudio }}</p>
              </div>
            </div>

            <div class="flex items-start gap-3">
              <svg class="w-5 h-5 text-[#1E56A0] mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
              </svg>
              <div class="flex-1">
                <p class="text-sm text-gray-600">Kursi</p>
                <div class="flex flex-wrap gap-2 mt-1">
                  @foreach(is_array($transaksi->kursi) ? $transaksi->kursi : json_decode($transaksi->kursi) as $kursi)
                    <span class="px-3 py-1 bg-[#1E56A0] text-white text-sm font-semibold rounded-md">{{ $kursi }}</span>
                  @endforeach
                </div>
              </div>
            </div>

            <div class="pt-4 border-t border-gray-200">
              <div class="flex justify-between items-center mb-2">
                <span class="text-gray-700">Jumlah Kursi</span>
                <span class="font-semibold">{{ count(is_array($transaksi->kursi) ? $transaksi->kursi : json_decode($transaksi->kursi)) }} kursi</span>
              </div>
              <div class="flex justify-between items-center">
                <span class="text-lg font-bold text-gray-900">Total Harga</span>
                <span class="text-2xl font-bold text-[#0A1D56]">Rp {{ number_format($transaksi->totalharga, 0, ',', '.') }}</span>
              </div>
            </div>
          </div>
        </div>

        {{-- Pilih Metode Pembayaran --}}
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
          <h2 class="text-xl font-bold text-[#0A1D56] mb-4 pb-3 border-b border-gray-200">Metode Pembayaran</h2>
          
          @if($transaksi->status === 'pending')
            <div class="space-y-4">
              
              {{-- Cash Payment --}}
              <div class="border-2 border-gray-200 rounded-xl p-4 hover:border-[#1E56A0] transition cursor-pointer payment-method" data-method="cash">
                <div class="flex items-center gap-3">
                  <input type="radio" name="payment_method" id="cash" value="cash" class="w-5 h-5 text-[#1E56A0] focus:ring-[#1E56A0]">
                  <label for="cash" class="flex-1 cursor-pointer">
                    <div class="flex items-center justify-between">
                      <div class="flex items-center gap-3">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        <div>
                          <p class="font-semibold text-gray-900">Cash</p>
                          <p class="text-sm text-gray-600">Pembayaran tunai</p>
                        </div>
                      </div>
                    </div>
                  </label>
                </div>
              </div>

              {{-- E-wallet / Debit --}}
              <div class="border-2 border-gray-200 rounded-xl p-4 hover:border-[#1E56A0] transition cursor-pointer payment-method" data-method="online">
                <div class="flex items-center gap-3">
                  <input type="radio" name="payment_method" id="online" value="online" class="w-5 h-5 text-[#1E56A0] focus:ring-[#1E56A0]">
                  <label for="online" class="flex-1 cursor-pointer">
                    <div class="flex items-center justify-between">
                      <div class="flex items-center gap-3">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        <div>
                          <p class="font-semibold text-gray-900">E-wallet / Debit Card</p>
                          <p class="text-sm text-gray-600">GoPay, OVO, Dana, dll</p>
                        </div>
                      </div>
                    </div>
                  </label>
                </div>
              </div>

              <button id="processPaymentBtn" disabled class="w-full bg-gray-300 text-gray-500 font-bold py-3 rounded-full cursor-not-allowed transition">
                Pilih Metode Pembayaran
              </button>

            </div>
          @elseif($transaksi->status === 'settlement')
            <div class="text-center py-8">
              <svg class="w-20 h-20 mx-auto text-green-500 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              <h3 class="text-2xl font-bold text-gray-900 mb-2">Pembayaran Berhasil!</h3>
              <p class="text-gray-600 mb-6">Transaksi telah diselesaikan</p>
              <a href="{{ route('riwayat-transaksi') }}" class="inline-block bg-[#0A1D56] text-white font-semibold px-8 py-3 rounded-full hover:bg-[#1E56A0] transition">
                Lihat Riwayat Transaksi
              </a>
            </div>
          @else
            <div class="text-center py-8">
              <p class="text-gray-600">Status: <span class="font-semibold">{{ ucfirst($transaksi->status) }}</span></p>
            </div>
          @endif
        </div>

      </div>
    </div>
  </div>

  @include('components.footer')

  <script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const transaksiId = {{ $transaksi->id }};
    const snapToken = @json($transaksi->snap_token ?? null);
    
    let selectedMethod = null;

    // Payment method selection
    document.querySelectorAll('.payment-method').forEach(method => {
      method.addEventListener('click', function() {
        const radio = this.querySelector('input[type="radio"]');
        radio.checked = true;
        selectedMethod = radio.value;
        
        // Update button
        const btn = document.getElementById('processPaymentBtn');
        btn.disabled = false;
        btn.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
        btn.classList.add('bg-[#0A1D56]', 'text-white', 'hover:bg-[#1E56A0]', 'cursor-pointer');
        btn.textContent = selectedMethod === 'cash' ? 'Konfirmasi Pembayaran Cash' : 'Bayar dengan E-wallet/Debit';
        
        // Highlight selected
        document.querySelectorAll('.payment-method').forEach(m => {
          m.classList.remove('border-[#1E56A0]', 'bg-blue-50');
        });
        this.classList.add('border-[#1E56A0]', 'bg-blue-50');
      });
    });

    // Process payment button
  // Process payment button
document.getElementById('processPaymentBtn')?.addEventListener('click', async function() {
  if (!selectedMethod) {
    alert('Pilih metode pembayaran terlebih dahulu!');
    return;
  }

  if (selectedMethod === 'cash') {
    // Cash payment - confirm and direct settlement
    if (confirm('Konfirmasi pembayaran tunai sebesar Rp {{ number_format($transaksi->totalharga, 0, ',', '.') }}?')) {
      this.disabled = true;
      this.textContent = 'Memproses...';
      
      try {
        // ✅ FIX: Tambahkan /kasir prefix dan ganti ke transaksi-kasir
        const response = await fetch(`/kasir/transaksi-kasir/${transaksiId}/pembayaran-cash`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          }
        });

        const data = await response.json();

        if (data.success) {
          alert('Pembayaran berhasil!');
          window.location.href = '{{ route("riwayat-transaksi") }}';
        } else {
          alert(data.message || 'Pembayaran gagal!');
          this.disabled = false;
          this.textContent = 'Konfirmasi Pembayaran Cash';
        }
      } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan: ' + error.message);
        this.disabled = false;
        this.textContent = 'Konfirmasi Pembayaran Cash';
      }
    }
  } else if (selectedMethod === 'online') {
    // Online payment - open Midtrans Snap
    if (!snapToken) {
      alert('Token pembayaran tidak tersedia. Silakan coba lagi.');
      return;
    }

    this.disabled = true;
    this.textContent = 'Membuka halaman pembayaran...';

    snap.pay(snapToken, {
      onSuccess: async function(result) {
        console.log('✅ Payment success:', result);
        
        try {
          // ✅ FIX: Tambahkan /kasir prefix dan error handling
          const response = await fetch(`/kasir/transaksi-kasir/${transaksiId}/update-status`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': csrfToken,
              'Accept': 'application/json'
            },
            body: JSON.stringify({
              status: 'settlement',
              metode_pembayaran: result.payment_type
            })
          });

          const data = await response.json();
          console.log('Update status response:', data);

          if (data.success) {
            alert('Pembayaran berhasil!');
            window.location.href = '{{ route("riwayat-transaksi") }}';
          } else {
            alert('Pembayaran berhasil, tapi gagal update status. Silakan hubungi kasir.');
            location.reload();
          }
        } catch (error) {
          console.error('Error updating status:', error);
          alert('Pembayaran berhasil, tapi gagal update status. Silakan hubungi kasir.');
          location.reload();
        }
      },
      onPending: async function(result) {
        console.log('Payment pending:', result);
        
        // ✅ FIX: Tambahkan /kasir prefix
        await fetch(`/kasir/transaksi-kasir/${transaksiId}/update-status`, {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          },
          body: JSON.stringify({
            status: 'pending',
            metode_pembayaran: result.payment_type
          })
        });

        alert('Pembayaran pending. Silakan selesaikan pembayaran.');
        location.reload();
      },
      onError: function(result) {
        console.error('Payment error:', result);
        alert('Pembayaran gagal. Silakan coba lagi.');
        location.reload();
      },
      onClose: function() {
        console.log('Payment popup closed');
        document.getElementById('processPaymentBtn').disabled = false;
        document.getElementById('processPaymentBtn').textContent = 'Bayar dengan E-wallet/Debit';
      }
    });
  }
});
</script>
</body>
</html>