<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Saya | TicketLy</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-[#f4f7ff] font-sans text-gray-800 min-h-screen">
    @include('components.navbar')

    <div class="max-w-3xl mx-auto mt-20 mb-16 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Header -->
        <div class="px-8 py-6 bg-[#4a90e2] text-white flex justify-between items-center rounded-t-2xl">
            <div>
                <h1 class="text-2xl font-bold">🎟️ E-Ticket</h1>
                <p class="text-sm opacity-80">Tunjukkan tiket ini di pintu masuk bioskop</p>
            </div>
            <i data-lucide="ticket" class="w-8 h-8"></i>
        </div>

        <!-- Konten Tiket -->
        <div class="p-8 space-y-6">
            <!-- Film & Jadwal -->
            <div class="flex items-center gap-4 border-b border-gray-200 pb-4">
                <img src="{{ asset($tiket->jadwal->film->poster ?? 'img/default.jpg') }}" 
                     alt="Poster Film" 
                     class="w-20 h-28 rounded-lg object-cover shadow-sm border border-gray-200">
                <div>
                    <h2 class="text-xl font-semibold text-gray-800">{{ $tiket->jadwal->film->judul ?? 'Judul Film' }}</h2>
                    <p class="text-sm text-gray-600">
                        {{ $tiket->jadwal->studio->nama_studio ?? 'Studio 1' }} • 
                        {{ $tiket->jadwal->tanggal ? \Carbon\Carbon::parse($tiket->jadwal->tanggal)->format('d M Y, H:i') : '-' }}
                    </p>
                </div>
            </div>

            <!-- Informasi Tiket -->
            <div class="grid md:grid-cols-2 gap-4 text-sm text-gray-700">
                <div class="space-y-2">
                    <p><span class="font-semibold text-[#4a90e2]">Kode Tiket:</span> {{ $tiket->kodetiket ?? '-' }}</p>
                    <p><span class="font-semibold text-[#4a90e2]">Kursi:</span> {{ $tiket->kursi->kodekursi ?? '-' }}</p>
                    <p><span class="font-semibold text-[#4a90e2]">Nomor Transaksi:</span> {{ $tiket->transaksi->id ?? '-' }}</p>
                </div>
                <div class="space-y-2">
                    <p><span class="font-semibold text-[#4a90e2]">Tanggal Pesan:</span> {{ $tiket->created_at ? $tiket->created_at->format('d M Y') : '-' }}</p>
                    <p><span class="font-semibold text-[#4a90e2]">Status:</span> 
                        <span class="text-green-600 font-semibold">
                            {{ strtoupper($tiket->transaksi->status ?? 'PAID') }}
                        </span>
                    </p>
                </div>
            </div>

            <!-- Catatan -->
            <div class="pt-5 border-t border-gray-200 text-xs text-gray-600 space-y-1">
                <p>📍 Lokasi: {{ $tiket->jadwal->studio->bioskop->nama_bioskop ?? 'TicketLy Cinema' }}</p>
                <p>⏰ Mohon datang 15 menit sebelum jam tayang.</p>
                <p>⚠️ Tiket yang sudah dibeli tidak dapat dikembalikan.</p>
            </div>
        </div>

        <!-- Tombol -->
        <div class="flex justify-center px-8 py-6 bg-[#f9fbff] border-t border-gray-100">
            <button onclick="window.print()" 
                class="flex items-center gap-2 px-6 py-2.5 rounded-lg bg-[#4a90e2] text-white font-medium hover:bg-[#357abd] transition">
                Cetak Tiket
            </button>
        </div>
    </div>

    @include('components.footer')
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>lucide.createIcons();</script>
</body>
</html>
