<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Pembayaran | Flixora</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
</head>

<body class="bg-gradient-to-b from-[#F5F8FC] to-[#E3EAF5] min-h-screen flex flex-col">

  <!-- Konten -->
  <main class="flex-grow flex flex-col items-center justify-center px-4">
    <div class="bg-white shadow-md rounded-xl w-full max-w-lg p-8 text-center border border-gray-200">
      <img src="{{ asset('img/Brand.png') }}" alt="Flixora" class="h-10 mx-auto mb-2">
      <h1 class="text-xl font-semibold text-gray-800 mb-6">Detail Pembayaran</h1>

      <div class="text-left mb-6">
        <div class="grid grid-cols-2 py-1 border-b border-gray-200">
          <p class="font-semibold">Film</p>
          <p>{{ $transaksi->jadwal->film->judul }}</p>
        </div>
        <div class="grid grid-cols-2 py-1 border-b border-gray-200">
          <p class="font-semibold">Jadwal</p>
          <p>{{ $transaksi->jadwal->tanggal }} {{ $transaksi->jadwal->jam_tayang }}</p>
        </div>
        <div class="grid grid-cols-2 py-1 border-b border-gray-200">
          <p class="font-semibold">Studio</p>
          <p>{{ $transaksi->jadwal->studio->nama_studio }}</p>
        </div>
<div class="grid grid-cols-2 py-1 border-b border-gray-200">
  <p class="font-semibold">Kursi</p>
  <p>
    {{-- ✅ Karena $transaksi->kursi sudah array, langsung join --}}
    @if(is_array($transaksi->kursi))
      {{ implode(', ', $transaksi->kursi) }}
    @else
      {{ $transaksi->kursi }}
    @endif
  </p>
</div>

        <div class="grid grid-cols-2 py-2 border-b border-gray-200 mt-2">
          <p class="font-semibold">Total Harga</p>
          <p>Rp {{ number_format($transaksi->totalharga, 0, ',', '.') }}</p>
        </div>
        <div class="grid grid-cols-2 py-2 border-b border-gray-200">
          <p class="font-semibold">Status</p>
          <p id="statusText">{{ ucfirst($transaksi->status) }}</p>
        </div>
        <div class="grid grid-cols-2 py-2 border-b border-gray-200">
          <p class="font-semibold">Kode Token</p>
          <p id="statusText">{{ ucfirst($transaksi->snap_token) }}</p>
        </div>
      </div>

      @if($transaksi->status !== 'settlement')
        <button id="payNow" class="w-full bg-[#0B1B3F] text-white py-2.5 rounded-md font-semibold hover:bg-[#152a6d] transition">
          Bayar
        </button>
      @endif
    </div>
  </main>

  <script>
    const snapToken = "{{ $transaksi->snap_token }}";
    const transaksiId = "{{ $transaksi->id }}";
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    const payBtn = document.getElementById("payNow");
    if (payBtn) {
      payBtn.addEventListener("click", function () {
        snap.pay(snapToken, {
          onSuccess: function (result) { updateStatus('settlement', result.payment_type); },
          onPending: function () { updateStatus('pending'); },
          onError: function () { alert("Pembayaran gagal."); }
        });
      });
    }

    function updateStatus(status, metode) {
      fetch(`/transaksi/${transaksiId}/update-status`, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          "X-CSRF-TOKEN": csrfToken
        },
        body: JSON.stringify({ status, metode_pembayaran: metode || 'unknown' })
      }).then(() => {
        document.getElementById("statusText").textContent = status;
        if (status === 'settlement') {
          alert("Pembayaran berhasil!");
          window.location.href = "/riwayat-transaksi?status=success";
        }
      });
    }
  </script>
</body>
</html>
