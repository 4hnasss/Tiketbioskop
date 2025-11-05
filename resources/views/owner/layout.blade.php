{{-- resources/views/owner/layout.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Owner Dashboard') - Flixora</title>
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    
    <style>
        body {
            background: linear-gradient(to right, #ffffff, #D6E4F0);
            min-height: 100vh;
        }

        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: #D6E4F0;
        }
        
        ::-webkit-scrollbar-thumb {
            background: #1E56A0;
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: #14274E;
        }
    </style>
    
    @stack('styles')
</head>
<body class="">
    <!-- Navbar -->
    <nav class="bg-white/90 backdrop-blur-md w-full h-[79px] flex items-center px-8 shadow-md sticky top-0 z-50 rounded-b-xl">
        {{-- Logo di kiri --}}
        <div class="flex items-center">
            <a href="{{ route('owner.dashboard') }}">
                <img src="/img/Brand.png" alt="Flixora" class="w-[115px] h-[60px]">
            </a>
        </div>

        {{-- Spacer untuk push menu ke kanan --}}
        <div class="flex-1"></div>

        {{-- Menu di kanan --}}
        <div class="flex space-x-6 items-center">
            <a href="{{ route('owner.dashboard') }}" 
               class="text-[#14274E] hover:text-[#1E56A0] font-serif transition {{ request()->routeIs('owner.dashboard') ? 'text-[#1E56A0] font-semibold' : '' }}">
                Dashboard
            </a>
            
            <a href="{{ route('owner.transaksi') }}" 
               class="text-[#14274E] hover:text-[#1E56A0] font-serif transition {{ request()->routeIs('owner.transaksi') || request()->routeIs('owner.detail-transaksi') ? 'text-[#1E56A0] font-semibold' : '' }}">
                Transaksi
            </a>
            
            <a href="{{ route('owner.laporan') }}" 
               class="text-[#14274E] hover:text-[#1E56A0] font-serif transition {{ request()->routeIs('owner.laporan') ? 'text-[#1E56A0] font-semibold' : '' }}">
                Laporan
            </a>

            {{-- User & Logout --}}
            <div class="flex items-center space-x-3 ml-3 pl-3 border-l border-[#D6E4F0]">
                <span class="text-[#14274E] font-serif text-sm">
                    {{ auth()->user()->name }}
                </span>
                
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" 
                        class="px-5 py-2 rounded-full bg-gradient-to-r from-[#3b82f6] to-[#22d3ee] text-white font-serif text-sm hover:scale-105 transition transform cursor-pointer shadow-md">
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="py-8 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="bg-green-50 border-2 border-green-400 text-green-800 px-6 py-4 rounded-xl mb-6 shadow-md" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle text-xl mr-3"></i>
                        <span class="font-serif font-semibold">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-50 border-2 border-red-400 text-red-800 px-6 py-4 rounded-xl mb-6 shadow-md" role="alert">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle text-xl mr-3"></i>
                        <span class="font-serif font-semibold">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white/90 backdrop-blur-md mt-16 border-t-2 border-[#D6E4F0] rounded-t-xl">
        <div class="max-w-7xl mx-auto px-8 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="space-y-4">
                    <img src="/img/Brand.png" alt="Flixora" class="w-[115px] h-[60px]">
                    <p class="text-[#14274E] font-serif text-sm leading-relaxed">
                        Sistem manajemen cinema terpadu untuk monitoring transaksi dan pendapatan secara real-time.
                    </p>
                </div>

                <div class="space-y-4">
                    <h4 class="text-lg font-bold text-[#14274E]">Menu Cepat</h4>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('owner.dashboard') }}" class="text-[#14274E] hover:text-[#1E56A0] font-serif text-sm flex items-center space-x-2">
                                <i class="fas fa-chevron-right text-xs"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('owner.transaksi') }}" class="text-[#14274E] hover:text-[#1E56A0] font-serif text-sm flex items-center space-x-2">
                                <i class="fas fa-chevron-right text-xs"></i>
                                <span>Data Transaksi</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('owner.laporan') }}" class="text-[#14274E] hover:text-[#1E56A0] font-serif text-sm flex items-center space-x-2">
                                <i class="fas fa-chevron-right text-xs"></i>
                                <span>Laporan Pendapatan</span>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="space-y-4">
                    <h4 class="text-lg font-bold text-[#14274E]">Informasi Kontak</h4>
                    <ul class="space-y-3">
                        <li class="flex items-center space-x-3 text-[#14274E] font-serif text-sm">
                            <i class="fas fa-envelope text-[#1E56A0]"></i>
                            <span>owner@flixora.com</span>
                        </li>
                        <li class="flex items-center space-x-3 text-[#14274E] font-serif text-sm">
                            <i class="fas fa-phone text-[#1E56A0]"></i>
                            <span>+62 812-3456-7890</span>
                        </li>
                        <li class="flex items-center space-x-3 text-[#14274E] font-serif text-sm">
                            <i class="fas fa-map-marker-alt text-[#1E56A0]"></i>
                            <span>Jakarta, Indonesia</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t-2 border-[#D6E4F0] mt-8 pt-6 text-center">
                <p class="text-[#14274E] font-serif text-sm">
                    &copy; {{ date('Y') }} <span class="font-semibold">Flixora Cinema</span>. All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>