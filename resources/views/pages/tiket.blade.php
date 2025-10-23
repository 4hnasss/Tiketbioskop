<!-- resources/views/pages/tiket.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket | Flixora</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-b from-white to-[#E3F2FD] min-h-screen font-sans text-gray-800">

    {{-- Navbar --}}
    @include('components.navbar')

    <div class="container mx-auto px-4 md:px-0 py-8">
        <h1 class="text-2xl font-bold text-center mb-8 text-[#14274E]">Tiket Kamu</h1>

        <div class="max-w-3xl mx-auto bg-white shadow-xl rounded-2xl p-6 border border-gray-100 space-y-6">

            @foreach($tiket as $t)
            <div class="flex flex-col md:flex-row gap-4 items-center bg-gray-50 p-4 rounded-lg shadow-sm">
                {{-- Poster Film --}}
                <img src="{{ $t->jadwal->film->poster }}" alt="Poster Film" class="w-36 h-48 rounded-lg object-cover shadow-sm">

                <div class="flex-1">
                    <h2 class="text-xl font-semibold text-[#14274E] mb-2">{{ $t->jadwal->film->judul }}</h2>
                    <p class="text-gray-600 text-sm mb-1"><strong>Studio:</strong> {{ $t->jadwal->studio->nama ?? '-' }}</p>
                    <p class="text-gray-600 text-sm mb-1"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($t->jadwal->tanggal)->translatedFormat('d F Y') }}</p>
                    <p class="text-gray-600 text-sm mb-1"><strong>Jam Tayang:</strong> {{ \Carbon\Carbon::parse($t->jadwal->jamtayang)->format('H:i') }}</p>
                    <p class="text-gray-600 text-sm mb-1"><strong>Kursi:</strong> {{ $t->kursi->nomorkursi ?? '-' }}</p>
                    <p class="text-gray-600 text-sm mb-1"><strong>Total:</strong> Rp {{ number_format($t->transaksi->totalharga ?? 0, 0, ',', '.') }}</p>

                    {{-- Status --}}
                    @php
                        $statusMap = [
                            'panding' => ['color' => 'yellow', 'text' => 'Menunggu Pembayaran'],
                            'selesai' => ['color' => 'green', 'text' => 'Pembayaran Selesai'],
                            'batal' => ['color' => 'red', 'text' => 'Dibatalkan'],
                            'challenge' => ['color' => 'orange', 'text' => 'Menunggu Verifikasi'],
                        ];
                        $status = $statusMap[$t->transaksi->status ?? ''] ?? ['color' => 'gray', 'text' => ucfirst($t->transaksi->status ?? 'Tidak Diketahui')];
                    @endphp

                    <span class="inline-block mt-2 px-3 py-1 text-xs font-medium rounded-full bg-{{ $status['color'] }}-100 text-{{ $status['color'] }}-600">
                        {{ $status['text'] }}
                    </span>
                </div>
            </div>
            @endforeach

        </div>

        <div class="text-center mt-6">
            <a href="{{ route('transaksi') }}" class="inline-block px-6 py-2 text-white bg-[#0A1D56] rounded-full shadow hover:bg-[#14274E] transition">
                Kembali ke Riwayat Transaksi
            </a>
        </div>
    </div>

    {{-- Footer --}}
    @include('components.footer')
</body>
</html>
