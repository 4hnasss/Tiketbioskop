<nav class="bg-white/90 backdrop-blur-md w-full h-[79px] flex items-center px-8 shadow-md sticky top-0 z-50 rounded-b-xl">
    {{-- Logo di kiri --}}
    <div class="flex items-center">
        <img src="/img/Brand.png" alt="Flixora" class="w-[115px] h-[60px]">
    </div>

    {{-- Spacer untuk push menu ke kanan --}}
    <div class="flex-1"></div>

    {{-- Menu / Auth Buttons di kanan --}}
    <div class="flex space-x-6 items-center">
        @if(Auth::check())
            {{-- Jika sudah login --}}
            <a href="{{ route('film') }}" class="text-[#14274E] hover:text-[#3b82f6] font-serif transition">Film</a>
            <a href="{{ route('profile') }}" class="text-[#14274E] hover:text-[#3b82f6] font-serif transition">Profile</a>
            <a href="{{ route('transaksi') }}" class="text-[#14274E] hover:text-[#3b82f6] font-serif transition">Transaksi</a>

            {{-- Logout --}}
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                    class="px-5 py-2 rounded-full bg-gradient-to-r from-[#3b82f6] to-[#22d3ee] text-white font-serif hover:scale-105 transition transform cursor-pointer shadow-md">
                    Logout
                </button>
            </form>
        @else
            {{-- Jika belum login --}}
            <a href="{{ route('login.form') }}" class="text-[#14274E] hover:text-[#3b82f6] font-serif transition">Login</a>
            <a href="{{ route('register') }}" 
                class="px-5 py-2 rounded-full bg-gradient-to-r from-[#3b82f6] to-[#22d3ee] text-white font-serif hover:scale-105 transition transform flex items-center justify-center shadow-md">
                Register
            </a>
        @endif
    </div>
</nav>
