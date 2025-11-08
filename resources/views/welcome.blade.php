<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Kasir | Flixora</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(to right, #ffffff, #D6E4F0);
        }

        /* Animasi fade-in yang lebih halus */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.95);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Kelas untuk animasi dengan easing yang lebih halus */
        .animate-on-load {
            opacity: 0;
            animation: fadeInUp 1s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
        }

        .animate-fade-in {
            opacity: 0;
            animation: fadeIn 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
        }

        .animate-scale-in {
            opacity: 0;
            animation: scaleIn 0.8s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
        }

        /* Delay yang lebih pendek untuk alur yang lebih fluid */
        .delay-100 { animation-delay: 0.05s; }
        .delay-200 { animation-delay: 0.1s; }
        .delay-300 { animation-delay: 0.15s; }
        .delay-400 { animation-delay: 0.2s; }
        .delay-500 { animation-delay: 0.25s; }
        .delay-600 { animation-delay: 0.3s; }
        .delay-700 { animation-delay: 0.35s; }
        .delay-800 { animation-delay: 0.4s; }

        /* Animasi hover yang lebih smooth pada tombol */
        .btn-hover-scale {
            transition: transform 0.2s cubic-bezier(0.25, 0.46, 0.45, 0.94), box-shadow 0.2s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        }
        .btn-hover-scale:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>
<body class="min-h-screen text-[#14274E]">
    {{-- Navbar --}}
    @include('components.nav')

    <div class="max-w-7xl mx-auto py-10 px-6 animate-on-load">
        {{-- Alert Messages --}}
        @if(session('error'))
            <div class="bg-red-50 border-2 border-red-400 text-red-800 p-4 mb-6 rounded-xl shadow-md animate-fade-in delay-100">
                <p class="font-bold text-sm mb-1">❌ Error!</p>
                <p class="text-sm">{{ session('error') }}</p>
            </div>
        @endif

        @if(session('success'))
            <div class="bg-green-50 border-2 border-green-400 text-green-800 p-4 mb-6 rounded-xl shadow-md animate-fade-in delay-100">
                <p class="font-bold text-sm mb-1">✅ Berhasil!</p>
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        @endif

        {{-- Welcome Card --}}
        <div class="bg-gradient-to-br from-[#1E56A0] to-[#14274E] rounded-2xl shadow-xl p-6 mb-8 text-white animate-fade-in delay-200">
            <h2 class="text-2xl font-bold mb-2">Selamat Datang, {{ $user->name }}! 👋</h2>
            <p class="text-base">Anda login sebagai <span class="font-semibold">Kasir Flixora</span></p>
            <p class="mt-2 text-sm opacity-90">{{ \Carbon\Carbon::now()->isoFormat('dddd, D MMMM YYYY') }}</p>
        </div>

        {{-- Statistik Hari Ini --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
            @php
                $cards = [
                    ['title' => 'Transaksi Hari Ini', 'value' => $transaksiHariIni, 'icon' => 'clipboard-list', 'color' => 'from-[#1E56A0] to-[#14274E]'],
                    ['title' => 'Pendapatan Hari Ini', 'value' => 'Rp '.number_format($pendapatanHariIni, 0, ',', '.'), 'icon' => 'currency-dollar', 'color' => 'from-green-500 to-emerald-600'],
                    ['title' => 'Tiket Terjual', 'value' => $tiketTerjualHariIni, 'icon' => 'ticket', 'color' => 'from-purple-500 to-fuchsia-600'],
                ];
            @endphp

            @foreach ($cards as $index => $card)
            <div class="bg-white/80 backdrop-blur-md rounded-xl shadow-lg border-2 border-[#D6E4F0] hover:shadow-xl transition transform hover:scale-105 animate-scale-in delay-{{ ($index + 1) * 100 }}">
                <div class="flex items-center justify-between p-5">
                    <div>
                        <p class="text-[#14274E]/70 text-xs font-semibold mb-2">{{ $card['title'] }}</p>
                        <p class="text-2xl font-bold text-[#14274E]">{{ $card['value'] }}</p>
                    </div>
                    <div class="bg-gradient-to-br {{ $card['color'] }} p-3 rounded-xl shadow-md">
                        @if ($card['icon'] === 'clipboard-list')
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2"/>
                            </svg>
                        @elseif ($card['icon'] === 'currency-dollar')
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8V7m0 10v1"/>
                            </svg>
                        @else
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                            </svg>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Menu Utama --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
            @php
                $menus = [
                    ['route' => 'kasir.pesan-tiket', 'title' => 'Pesan Tiket', 'desc' => 'Buat pesanan tiket baru untuk pengunjung', 'color' => 'from-[#1E56A0] to-[#14274E]'],
                    ['route' => 'riwayat-transaksi', 'title' => 'Riwayat Transaksi', 'desc' => 'Lihat semua transaksi pengunjung', 'color' => 'from-emerald-500 to-green-600'],
                    ['route' => 'kasir.laporan-keuangan', 'title' => 'Laporan Keuangan', 'desc' => 'Lihat pendapatan dan statistik', 'color' => 'from-purple-500 to-pink-600'],
                ];
            @endphp

            @foreach ($menus as $index => $menu)
            <a href="{{ route($menu['route']) }}" class="block bg-white/80 backdrop-blur-md rounded-xl shadow-lg hover:shadow-xl hover:scale-105 transition p-6 border-2 border-[#D6E4F0] group btn-hover-scale animate-scale-in delay-{{ ($index + 1) * 200 }}">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-gradient-to-br {{ $menu['color'] }} p-3 rounded-xl shadow-md group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5h6a2 2 0 012 2v10a2 2 0 01-2 2H9a2 2 0 01-2-2V7a2 2 0 012-2z"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-lg font-bold text-[#14274E] mb-2">{{ $menu['title'] }}</h3>
                <p class="text-[#14274E]/70 text-sm mb-3">{{ $menu['desc'] }}</p>
                <span class="text-[#1E56A0] font-semibold text-sm group-hover:text-[#14274E] transition flex items-center">
                    Lihat Detail
                    <svg class="w-4 h-4 ml-2 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </span>
            </a>
            @endforeach
        </div>
    </div>

    @include('components.footer')
</body>
</html>
