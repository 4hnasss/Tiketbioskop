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

        <div class="space-y-4"> <!-- jarak antar card lebih lega -->
            <!-- Transaksi 1 -->
            <a href="{{Route('tiket')}}" class="block cursor-pointer">
                <div class="flex items-center bg-white backdrop-blur-sm rounded-xl shadow-2xl p-4 border border-gray-100 hover:shadow-md transition duration-200">
                    <img src="/img/Alie.jpg" alt="Poster Film" class="w-20 h-28 rounded-lg object-cover shadow-sm">
                    <div class="flex-1 ml-4">
                        <h2 class="text-lg font-semibold text-[#14274E] mb-1">Pengepungan di Bukit Duri</h2>
                        <p class="text-gray-600 text-xs mb-0.5"><strong>Tanggal:</strong> 29-11-2025 09:00</p>
                        <p class="text-gray-600 text-xs mb-0.5"><strong>Jadwal ID:</strong> 1</p>
                        <p class="text-gray-600 text-xs mb-0.5"><strong>Total:</strong> Rp 25.000</p>
                        <span class="inline-block mt-1 px-2 py-0.5 text-xs font-medium rounded-full bg-red-100 text-red-600">Pending</span>
                    </div>
                </div>
            </a>

            <!-- Transaksi 2 -->
            <a href="{{Route('tiket')}}" class="block cursor-pointer">
                <div class="flex items-center bg-white backdrop-blur-sm rounded-xl shadow-2xl p-4 border border-gray-100 hover:shadow-md transition duration-200">
                    <img src="/img/pangepungan.jpg" alt="Poster Film" class="w-20 h-28 rounded-lg object-cover shadow-sm">
                    <div class="flex-1 ml-4">
                        <h2 class="text-lg font-semibold text-[#14274E] mb-1">Perjalanan Waktu</h2>
                        <p class="text-gray-600 text-xs mb-0.5"><strong>Tanggal:</strong> 30-11-2025 14:30</p>
                        <p class="text-gray-600 text-xs mb-0.5"><strong>Jadwal ID:</strong> 2</p>
                        <p class="text-gray-600 text-xs mb-0.5"><strong>Total:</strong> Rp 40.000</p>
                        <span class="inline-block mt-1 px-2 py-0.5 text-xs font-medium rounded-full bg-green-100 text-green-600">Selesai</span>
                    </div>
                </div>
            </a>

            <!-- Transaksi 3 -->
            <a href="{{Route('tiket')}}" class="block cursor-pointer">
                <div class="flex items-center bg-white backdrop-blur-sm rounded-xl shadow-2xl p-4 border border-gray-100 hover:shadow-md transition duration-200">
                    <img src="/img/home.jpg" alt="Poster Film" class="w-20 h-28 rounded-lg object-cover shadow-sm">
                    <div class="flex-1 ml-4">
                        <h2 class="text-lg font-semibold text-[#14274E] mb-1">Misteri Hutan</h2>
                        <p class="text-gray-600 text-xs mb-0.5"><strong>Tanggal:</strong> 01-12-2025 11:00</p>
                        <p class="text-gray-600 text-xs mb-0.5"><strong>Jadwal ID:</strong> 3</p>
                        <p class="text-gray-600 text-xs mb-0.5"><strong>Total:</strong> Rp 30.000</p>
                        <span class="inline-block mt-1 px-2 py-0.5 text-xs font-medium rounded-full bg-gray-100 text-gray-600">Batal</span>
                    </div>
                </div>
            </a>
        </div>
    </div>
    {{-- Footer --}}
    @include('components.footer')

</body>
</html>
