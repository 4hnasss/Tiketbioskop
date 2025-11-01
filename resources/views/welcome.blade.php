<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kasir | Flixora</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gradient-to-b from-[#e0f2fe] to-[#ffffff] min-h-screen text-[#14274E] font-serif">
    {{-- Navbar --}}
    @include('components.nav')

    <div class="max-w-7xl mx-auto py-10 px-6">
        {{-- Alert Messages --}}
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-lg shadow-sm">
                <p class="font-semibold">❌ Error!</p>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-lg shadow-sm">
                <p class="font-semibold">✅ Berhasil!</p>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        {{-- Welcome Card --}}
        <div class="bg-gradient-to-r from-[#3b82f6] to-[#9333ea] rounded-2xl shadow-xl p-8 mb-10 text-white">
            <h2 class="text-3xl font-extrabold mb-2">Selamat Datang, {{ $user->name }}! 👋</h2>
            <p>Anda login sebagai <span class="font-semibold">Kasir Flixora</span></p>
            <p class="mt-2 text-sm opacity-80">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}</p>
        </div>

        {{-- Statistik Hari Ini --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            @php
                $cards = [
                    ['title' => 'Transaksi Hari Ini', 'value' => $transaksiHariIni, 'icon' => 'clipboard-list', 'color' => 'from-blue-400 to-blue-600'],
                    ['title' => 'Pendapatan Hari Ini', 'value' => 'Rp '.number_format($pendapatanHariIni, 0, ',', '.'), 'icon' => 'currency-dollar', 'color' => 'from-green-400 to-emerald-500'],
                    ['title' => 'Tiket Terjual', 'value' => $tiketTerjualHariIni, 'icon' => 'ticket', 'color' => 'from-purple-400 to-fuchsia-500'],
                ];
            @endphp

            @foreach ($cards as $card)
            <div class="bg-white/70 backdrop-blur-md rounded-xl shadow-md border border-white/40 hover:shadow-xl transition">
                <div class="flex items-center justify-between p-6">
                    <div>
                        <p class="text-gray-600 text-sm font-medium">{{ $card['title'] }}</p>
                        <p class="text-3xl font-bold mt-2 text-[#14274E]">{{ $card['value'] }}</p>
                    </div>
                    <div class="bg-gradient-to-br {{ $card['color'] }} p-4 rounded-full shadow-md">
                        @if ($card['icon'] === 'clipboard-list')
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                            </svg>
                        @elseif ($card['icon'] === 'currency-dollar')
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V7m0 10v1"/>
                            </svg>
                        @else
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                            </svg>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Menu Utama --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @php
                $menus = [
                    ['route' => 'kasir.pesan-tiket', 'title' => 'Pesan Tiket', 'desc' => 'Buat pesanan tiket baru untuk pengunjung', 'color' => 'from-sky-400 to-indigo-500'],
                    ['route' => 'kasir.riwayat-transaksi', 'title' => 'Riwayat Transaksi', 'desc' => 'Lihat semua transaksi pengunjung', 'color' => 'from-emerald-400 to-green-500'],
                    ['route' => 'kasir.laporan-keuangan', 'title' => 'Laporan Keuangan', 'desc' => 'Lihat pendapatan dan statistik', 'color' => 'from-purple-400 to-pink-500'],
                ];
            @endphp

            @foreach ($menus as $menu)
            <a href="{{ route($menu['route']) }}" class="block bg-white/70 backdrop-blur-md rounded-xl shadow-md hover:shadow-xl hover:scale-[1.02] transition p-6 border border-white/40 group">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-gradient-to-br {{ $menu['color'] }} p-4 rounded-full shadow-md group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5h6a2 2 0 012 2v10a2 2 0 01-2 2H9a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-xl font-semibold text-[#14274E] mb-2">{{ $menu['title'] }}</h3>
                <p class="text-gray-600 mb-4">{{ $menu['desc'] }}</p>
                <span class="text-[#3b82f6] font-medium group-hover:text-[#2563eb] transition">
                    Lihat Detail →
                </span>
            </a>
            @endforeach
        </div>
    </div>
</body>
</html>
