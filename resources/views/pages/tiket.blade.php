<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket | Flixora</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
</head>
<body class="bg-gradient-to-b from-white to-[#E3F2FD] min-h-screen font-sans text-gray-800">

    {{-- Navbar --}}
    @include('components.navbar')

    <div class="container mx-auto px-4 py-10">
        <h1 class="text-2xl font-bold text-center mb-4 text-[#0A1D56]">Tiket Kamu</h1>
        <p class="text-center text-gray-600 mb-10">{{ count($kursiArray) }} tiket untuk {{ $transaksi->jadwal->film->judul }}</p>

        <div class="space-y-8">
            @foreach($kursiArray as $index => $kursi)
            <div class="flex flex-col items-center">
                {{-- Tiket untuk setiap kursi --}}
                <div id="ticket-card-{{ $index }}" 
                    class="relative w-full max-w-3xl bg-white shadow-2xl rounded-2xl overflow-hidden border border-gray-200 flex flex-col md:flex-row">

                    {{-- Kiri (Poster) --}}
                    <div class="relative md:w-1/3 w-full bg-gradient-to-b from-[#0A1D56] to-[#14274E] flex flex-col items-center justify-center p-5">
                        <img src="{{ asset('img/' . $transaksi->jadwal->film->poster) }}" 
                             alt="Poster Film" 
                             class="w-44 h-60 object-cover rounded-lg shadow-lg mb-4 border border-white/30">
                    </div>

                    {{-- Tengah (Detail Film) --}}
                    <div class="flex-1 bg-white px-6 py-6">
                        <h2 class="text-2xl font-extrabold text-[#0A1D56] mb-4 tracking-wide">{{ $transaksi->jadwal->film->judul }}</h2>
                        <div class="grid grid-cols-2 gap-y-1 text-sm text-gray-700 leading-relaxed">
                            <p><strong>Studio:</strong> {{ $transaksi->jadwal->studio->nama_studio ?? '-' }}</p>
                            <p><strong>Kursi:</strong> {{ $kursi }}</p>
                            <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($transaksi->jadwal->tanggal)->format('d-m-Y') }}</p>
                            <p><strong>Jam:</strong> {{ \Carbon\Carbon::parse($transaksi->jadwal->jamtayang)->format('H:i') }}</p>
                        </div>
                        
                        {{-- Harga per tiket --}}
                        @php
                            $hargaPerTiket = $transaksi->totalharga / count($kursiArray);
                        @endphp
                        <p class="mt-3 text-sm text-[#0A1D56]">
                            <strong>Harga:</strong> Rp {{ number_format($hargaPerTiket, 0, ',', '.') }}
                        </p>

                        <p class="mt-3 text-sm">
                            <strong>Status:</strong> 
                            <span class="px-2 py-0.5 text-xs bg-emerald-500 text-white rounded-md">Pembayaran Selesai</span>
                        </p>

                        <div class="border-t border-dashed border-gray-300 my-4"></div>

                        <p class="text-sm font-mono text-gray-700">
                            <strong>Kode Tiket:</strong> 
                            <span class="kode-tiket">FLX-{{ strtoupper(substr(md5($transaksi->id . $kursi), 0, 8)) }}</span>
                        </p>
                    </div>

                    {{-- Strip Kanan --}}
                    <div class="relative md:w-[80px] w-full bg-[#0A1D56] flex flex-col justify-between items-center py-4">
                        <div class="w-[2px] h-20 bg-white opacity-90 rounded-sm"></div>
                        <div class="text-[10px] rotate-90 whitespace-nowrap tracking-widest font-semibold text-white/70">
                            FLIXORA TICKET
                        </div>
                        <div class="w-[2px] h-20 bg-white opacity-90 rounded-sm"></div>
                    </div>

                    {{-- Potongan garis di tengah --}}
                    <div class="absolute top-0 bottom-0 left-[calc(100%-80px)] hidden md:block border-dashed border-l-2 border-gray-300"></div>
                </div>

                {{-- Tombol Aksi untuk setiap tiket --}}
                <div class="flex flex-col sm:flex-row justify-center items-center gap-3 mt-4">
                    <button onclick="downloadTicket({{ $index }})" 
                        class="px-5 py-2 bg-[#0A1D56] text-white text-sm font-semibold rounded-full shadow-sm hover:bg-[#14274E] active:scale-95 transition-transform">
                        Download Tiket {{ $kursi }}
                    </button>
                </div>
            </div>

            {{-- Separator antar tiket --}}
            @if(!$loop->last)
                <div class="border-t-2 border-dashed border-gray-300 my-8"></div>
            @endif
            @endforeach
        </div>

        {{-- Tombol kembali --}}
        <div class="flex justify-center mt-10">
            <a href="{{ route('transaksi.riwayat') }}" 
               class="px-6 py-3 bg-white text-[#0A1D56] text-sm font-semibold border-2 border-[#0A1D56] rounded-full shadow-sm hover:bg-[#0A1D56] hover:text-white active:scale-95 transition-transform">
                Kembali ke Riwayat Transaksi
            </a>
        </div>
    </div>

    {{-- Footer --}}
    @include('components.footer')

    {{-- Script Download PNG --}}
    <script>
        function downloadTicket(index) {
            const card = document.getElementById('ticket-card-' + index);
            html2canvas(card, { scale: 2, backgroundColor: "#ffffff" }).then(canvas => {
                const link = document.createElement('a');
                const kursi = card.querySelector('.kode-tiket').textContent.trim();
                link.download = 'Tiket-Flixora-' + kursi + '.png';
                link.href = canvas.toDataURL('image/png');
                link.click();
            });
        }
    </script>

</body>
</html>