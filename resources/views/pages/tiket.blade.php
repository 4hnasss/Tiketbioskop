<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket Saya | TicketLy</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-r from-white to-[#D6E4F0] min-h-screen">
    @include('components.navbar')

    <div class="flex justify-center py-12 px-4">
        <div class="w-full max-w-3xl bg-white/40 backdrop-blur-md rounded-2xl shadow-xl p-10 border border-white/50">
            
            <!-- Header Tiket -->
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-[#14274E]">🎟️ E-Ticket Anda</h1>
                <p class="text-gray-600 mt-1">Tunjukkan tiket ini di pintu masuk bioskop</p>
            </div>

            <!-- Data Tiket -->
            <div class="bg-white/70 rounded-xl p-6 shadow-md border border-gray-200">
                <div class="flex justify-between items-center border-b border-gray-300 pb-3 mb-4">
                    <div>
                        <h2 class="text-xl font-bold text-[#14274E]">Pengepungan di Bukit Duri</h2>
                        <p class="text-sm text-gray-600">Studio 1 • 20 Okt 2025, 19:30</p>
                    </div>
                    <img src="{{ asset('img/film-bukit-duri.jpg') }}" alt="Poster Film" class="w-16 h-20 rounded-md object-cover shadow">
                </div>

                <!-- Info Tiket -->
                <div class="grid grid-cols-2 gap-4 text-gray-700">
                    <div>
                        <p><span class="font-semibold text-[#14274E]">Kode Tiket:</span> TKT20251020001</p>
                        <p><span class="font-semibold text-[#14274E]">Nomor Transaksi:</span> TRX102938</p>
                        <p><span class="font-semibold text-[#14274E]">Kursi:</span> C5, C6</p>
                    </div>
                    <div>
                        <p><span class="font-semibold text-[#14274E]">Tanggal Pesan:</span> 19 Okt 2025</p>
                        <p><span class="font-semibold text-[#14274E]">Metode Bayar:</span> QRIS</p>
                        <p><span class="font-semibold text-[#14274E]">Status:</span> <span class="text-green-600 font-semibold">PAID</span></p>
                    </div>
                </div>

                <!-- QR Code -->
                <div class="flex justify-center mt-6">
                    <div class="bg-white p-4 rounded-xl shadow-md border border-gray-200">
                        <img src="{{ asset('img/qrcode-sample.png') }}" alt="QR Code Tiket" class="w-32 h-32">
                        <p class="text-center text-sm text-gray-600 mt-2">Scan untuk validasi tiket</p>
                    </div>
                </div>

                <!-- Info tambahan -->
                <div class="mt-6 border-t border-gray-300 pt-4 text-sm text-gray-600">
                    <p>📍 Lokasi: TicketLy Cinema - Cabang Jakarta Pusat</p>
                    <p>⏰ Mohon datang 15 menit sebelum jam tayang.</p>
                    <p>⚠️ Tiket yang sudah dibeli tidak dapat dikembalikan.</p>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="text-center mt-10">
                <button onclick="window.print()" 
                    class="bg-gradient-to-r from-[#14274E] to-[#394867] hover:from-[#0F1E3B] hover:to-[#26324D] text-white px-10 py-2.5 rounded-full font-semibold shadow-md hover:shadow-lg transition-all duration-300">
                    🖨️ Cetak Tiket
                </button>
            </div>
        </div>
    </div>

    @include('components.footer')
</body>
</html>
