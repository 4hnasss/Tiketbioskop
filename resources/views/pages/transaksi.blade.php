<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Riwayat Transaksi | TicketLy</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-white to-[#E3F2FD] min-h-screen font-sans">

    {{-- Navbar --}}
    @include('components.navbar')

    <div class="container mx-auto px-4 md:px-0 py-8">
        <h1 class="text-2xl font-bold text-center mb-8 text-[#14274E]">Riwayat Transaksi</h1>

        <div class="space-y-4">
                @foreach($transaksis as $transaksi)
                        <a href="{{ route('tiket.show', $transaksi->id) }}" class="block cursor-pointer">
                        <div class="flex items-center bg-white backdrop-blur-sm rounded-xl shadow-2xl p-4 border border-gray-100 hover:shadow-md transition duration-200">
                            <img src="{{ $transaksi->jadwal->film->poster }}" alt="Poster Film" class="w-20 h-28 rounded-lg object-cover shadow-sm">
                            <div class="flex-1 ml-4">
                                <h2 class="text-lg font-semibold text-[#14274E] mb-1">{{ $transaksi->jadwal->film->judul }}</h2>
                                <p class="text-gray-600 text-xs mb-0.5"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($transaksi->tanggaltransaksi)->format('d-m-Y H:i') }}</p>
                                <p class="text-gray-600 text-xs mb-0.5"><strong>Jadwal ID:</strong> {{ $transaksi->jadwal_id }}</p>
                                <p class="text-gray-600 text-xs mb-0.5"><strong>Total:</strong> Rp {{ number_format($transaksi->totalharga, 0, ',', '.') }}</p>

                                @php
                                    $statusMap = [
                                        'panding' => ['color' => 'yellow', 'text' => 'Menunggu Pembayaran'],
                                        'selesai' => ['color' => 'green', 'text' => 'Pembayaran Selesai'],
                                        'batal' => ['color' => 'red', 'text' => 'Dibatalkan'],
                                        'challenge' => ['color' => 'orange', 'text' => 'Menunggu Verifikasi'],
                                    ];
                                    $status = $statusMap[$transaksi->status] ?? ['color' => 'gray', 'text' => ucfirst($transaksi->status)];
                                @endphp

                                <span class="inline-block mt-1 px-2 py-0.5 text-xs font-medium rounded-full bg-{{ $status['color'] }}-100 text-{{ $status['color'] }}-600">
                                    {{ $status['text'] }}
                                </span>
                            </div>
                        </div>
                    @if($transaksi->tiket)
                        </a>
                    @endif
                @endforeach

            </div>
        </div>
    </div>
    {{-- Footer --}}
    @include('components.footer')

</body>
</html>
