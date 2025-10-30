<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Pembayaran | TicketLy</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
</head>

<body class="bg-gradient-to-b from-[#f5f8fc] to-[#dbe7f4] min-h-screen flex flex-col">

  <!-- Konten utama -->
  <main class="flex-1 flex flex-col items-center justify-center py-10">
    <div class="bg-white rounded-xl shadow-md w-full max-w-lg p-10 border border-gray-200">
      
      <!-- Logo tengah -->
      <div class="flex flex-col items-center mb-6">
        <img src="{{ asset('img/Brand.png') }}" alt="Flixora" class="w-15 h-12 mb-2">
        <h1 class="text-xl font-semibold text-gray-800">Detail Pembayaran</h1>
      </div>

      <!-- Tabel informasi -->
<table class="w-full text-sm text-gray-700 border-t border-gray-300">
  <tbody>
    <tr class="border-b">
      <td class="py-2 font-semibold w-1/3">Film</td>
      <td class="py-2 text-right">{{ $transaksi->jadwal->film->judul }}</td>
    </tr>

    <tr class="border-b">
      <td class="py-2 font-semibold">Jadwal</td>
      <td class="py-2 text-right">
        {{ \Carbon\Carbon::parse($transaksi->jadwal->tanggal_tayang)->format('d-m-Y, H:i') }}
      </td>
    </tr>

    <tr class="border-b">
      <td class="py-2 font-semibold">Studio</td>
      <td class="py-2 text-right">{{ $transaksi->jadwal->studio->nama_studio }}</td>
    </tr>
<tr class="border-b">
  <td class="py-2 font-semibold">Kursi</td>
  <td class="py-2 text-right">
    {{ !empty($nomorkursi) ? implode(', ', $nomorkursi) : '-' }}
  </td>
</tr>

<tr class="border-b">
  <td class="py-2 font-semibold">Total Harga</td>
  <td class="py-2 text-right">
    Rp {{ number_format($totalHarga, 0, ',', '.') }}
  </td>
</tr>


    <tr class="border-b">
      <td class="py-2 font-semibold">Status</td>
      <td class="py-2 text-right capitalize" id="statusText">{{ $transaksi->status }}</td>
    </tr>
  </tbody>
</table>


      <!-- Tombol Bayar -->
      <button id="payNow" 
              class="mt-8 w-full bg-[#0A1D56] text-white py-2 rounded-md hover:bg-[#1E56A0] transition duration-200 font-semibold">
        Bayar
      </button>
    </div>
  </main>
<script>
  const snapToken = "{{ $transaksi->snap_token }}";
  const transaksiId = "{{ $transaksi->id }}";
  const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

  document.getElementById("payNow").addEventListener("click", function () {
    snap.pay(snapToken, {
      onSuccess: function (result) {
        updateStatus('settlement');
      },
      onPending: function () {
        updateStatus('pending');
      },
      onError: function () {
        alert("Pembayaran gagal.");
      }
    });
  });

  function updateStatus(status) {
    fetch(`/transaksi/${transaksiId}/update-status`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": csrfToken
      },
      body: JSON.stringify({
        status: status
      })
    }).then(() => {
      document.getElementById("statusText").textContent = status;
      if (status === 'settlement') {
        alert("Pembayaran berhasil!");
        window.location.href = "{{ route('transaksi.riwayat') }}?status=success";
      }
    });
  }
</script>

</body>
</html>
