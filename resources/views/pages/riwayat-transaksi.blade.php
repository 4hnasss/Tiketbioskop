<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Riwayat Transaksi | TicketLy</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-white to-[#E3F2FD] min-h-screen font-sans">

  @include('components.navbar')

  <div class="container mx-auto px-4 py-10">
    <h1 class="text-3xl font-bold text-center mb-10 text-[#14274E]">
      🎟️ Riwayat Transaksi
    </h1>

    @if($transaksis->isEmpty())
      <div class="text-center py-16">
        <svg class="mx-auto h-14 w-14 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <p class="mt-4 text-gray-600">Belum ada transaksi yang tercatat.</p>
        <a href="/" class="mt-6 inline-block px-6 py-2 bg-[#1E56A0] text-white rounded-full hover:bg-[#14274E] transition duration-200">
          Pesan Tiket Sekarang
        </a>
      </div>
    @else

    <div class="space-y-6">
      @foreach($transaksis as $transaksi)
        @php
          // Format kursi agar rapi
          $kursi = $transaksi->kursi;
          if (is_string($kursi)) {
              $decoded = json_decode($kursi, true);
              if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                  $kursiArray = $decoded;
              } else {
                  $kursiArray = explode(',', $kursi);
              }
          } else {
              $kursiArray = (array) $kursi;
          }
          $kursiText = implode(', ', array_map('trim', $kursiArray));

          // Total harga fallback
          $total = $transaksi->total ?? $transaksi->jumlah_bayar ?? $transaksi->harga_total ?? 0;

          // Tanggal tayang
          $tanggalTayang = $transaksi->jadwal->tanggal_tayang ?? $transaksi->created_at;
          $tanggalFormat = \Carbon\Carbon::parse($tanggalTayang)->format('d M Y H:i');

          // Status style
          $statusMap = [
              'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Menunggu Pembayaran'],
              'settlement' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Pembayaran Selesai'],
              'expire' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Kedaluwarsa'],
              'cancel' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Dibatalkan'],
              'challenge' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'label' => 'Menunggu Verifikasi'],
          ];
          $status = $statusMap[$transaksi->status] ?? ['bg'=>'bg-gray-100','text'=>'text-gray-700','label'=>ucfirst($transaksi->status)];
        @endphp

        <!-- Kartu Transaksi -->
        <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition duration-200 border border-gray-100 p-6">
          <div class="flex flex-col sm:flex-row items-center">
            
            <!-- Poster Film -->
            <img src="{{ asset('img/' . $transaksi->jadwal->film->poster) }}" 
                 alt="{{ $transaksi->jadwal->film->judul }}" 
                 class="w-24 h-32 rounded-xl object-cover shadow-md">

            <!-- Info -->
            <div class="flex-1 sm:ml-6 mt-4 sm:mt-0 text-center sm:text-left">
              <h2 class="text-lg font-semibold text-[#14274E]">{{ $transaksi->jadwal->film->judul }}</h2>
              <p class="text-gray-500 text-sm mt-1">{{ $transaksi->jadwal->studio->nama_studio }}</p>

              <div class="mt-3 space-y-1 text-sm text-gray-700">
                <p><strong>Kursi:</strong> {{ $kursiText ?: '-' }}</p>
                <p><strong>Tanggal Tayang:</strong> {{ $tanggalFormat }}</p>
                <p><strong>Total:</strong> Rp {{ number_format($total, 0, ',', '.') }}</p>
                @if($transaksi->metode_pembayaran)
                  <p><strong>Metode:</strong> {{ ucfirst($transaksi->metode_pembayaran) }}</p>
                @endif
              </div>

              <span class="inline-block mt-3 px-3 py-1 text-xs font-semibold rounded-full {{ $status['bg'] }} {{ $status['text'] }}">
                {{ $status['label'] }}
              </span>
            </div>
          </div>

          <!-- Tombol Aksi -->
          <div class="mt-5 flex flex-col sm:flex-row justify-center sm:justify-end gap-3">
            <!-- Tombol Detail -->
            <a href="{{ route('transaksi', $transaksi->id) }}" 
               class="px-5 py-2 text-sm font-medium bg-[#1E56A0] text-white rounded-full hover:bg-[#14274E] transition duration-200 shadow-md text-center">
              Detail
            </a>

            <!-- Tombol Lanjutkan Pembayaran (jika masih pending) -->
            @if($transaksi->status === 'pending')
              <a href="{{ route('transaksi', $transaksi->id) }}" 
                 class="px-5 py-2 text-sm font-medium bg-yellow-500 text-white rounded-full hover:bg-yellow-600 transition duration-200 shadow-md text-center">
                Lanjutkan Pembayaran
              </a>
            @endif
          </div>
        </div>
      @endforeach
    </div>
    @endif
  </div>

  @include('components.footer')

</body>
</html>
