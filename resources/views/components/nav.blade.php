<nav class="bg-white/90 backdrop-blur-md w-full h-[80px] flex items-center justify-between px-8 shadow-md sticky top-0 z-50 rounded-b-xl">
    {{-- Kiri: Logo + Menu --}}
    <div class="flex items-center space-x-10">
        {{-- Logo --}}
        <a href="{{ route('kasir.welcome') }}">
            <img src="/img/Brand.png" alt="Flixora" class="w-[115px] h-[60px] object-contain">
        </a>

        {{-- Menu Navigasi --}}
        <div class="hidden md:flex items-center space-x-6 font-serif">
            @php
                $navItems = [
                    ['route' => 'kasir.welcome', 'label' => 'Dashboard'],
                    ['route' => 'kasir.pesan-tiket', 'label' => 'Pesan Tiket'],
                    ['route' => 'riwayat-transaksi', 'label' => 'Riwayat'],
                    ['route' => 'kasir.laporan-keuangan', 'label' => 'Laporan'],
                ];
            @endphp

            @foreach ($navItems as $item)
                <a href="{{ route($item['route']) }}"
                   class="relative text-[#14274E] font-medium text-sm py-2 transition-all duration-200
                          {{ request()->routeIs($item['route']) 
                               ? 'text-[#3b82f6] after:content-[\'\'] after:absolute after:w-full after:h-[2px] after:bg-[#3b82f6] after:left-0 after:-bottom-0.5 after:rounded-full' 
                               : 'hover:text-[#3b82f6] hover:after:content-[\'\'] hover:after:absolute hover:after:w-full hover:after:h-[2px] hover:after:bg-[#3b82f6] hover:after:left-0 hover:after:-bottom-0.5 hover:after:rounded-full' }}">
                    {{ $item['label'] }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Kanan: User Info & Logout --}}
    <div class="flex items-center space-x-5">
        <span class="hidden sm:inline text-[#14274E] font-serif text-sm">
            👋 <span class="font-semibold">{{ Auth::user()->name }}</span>
        </span>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="px-5 py-2.5 rounded-full text-sm font-semibold bg-gradient-to-r 
                       from-[#3b82f6] to-[#22d3ee] text-white shadow-md 
                       hover:scale-105 hover:shadow-lg transition-all duration-200">
                Logout
            </button>
        </form>
    </div>
</nav>
