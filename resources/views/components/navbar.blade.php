<nav class="bg-white shadow-md w-full h-[79px] flex items-center px-8">
    {{-- Logo di kiri --}}
    <div class="flex items-center">
        <img src="/img/Brand.png" alt="Flixora" class="w-[115px] h-[60px]">
    </div>

    {{-- Spacer untuk push menu ke kanan --}}
    <div class="flex-1"></div>

    {{-- Menu / Auth Buttons di kanan --}}
    <div class="flex space-x-4 items-center">
        @if(Auth::check())
            {{-- Jika sudah login --}}
            <a href="{{ route('film') }}" class="text-[#14274E] hover:text-blue-600 font-serif">Film</a>
            <a href="{{ route('profile') }}" class="text-[#14274E] hover:text-blue-600 font-serif">Profile</a>
            <a href="{{ route('transaksi') }}" class="text-[#14274E] hover:text-blue-600 font-serif">Transaksi</a>
            

            {{-- Logout --}}
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" 
                    class="px-4 border-2 border-[#14274E] w-[110px] h-[35px] rounded-full text-[#14274E] text-center font-serif hover:bg-[#e8f0fc] transition cursor-pointer">
                    Logout
                </button>
            </form>
        @else
            {{-- Jika belum login --}}
            <a href="{{ route('login.form') }}" class="text-[#14274E] hover:text-blue-600 font-serif">Login</a>
            <a href="/registrasi" 
                class="px-6 border-2 border-[#14274E] w-[110px] h-[35px] rounded-full text-[#14274E] text-center font-serif hover:bg-[#e8f0fc] transition flex items-center justify-center">
                Register
            </a>
        @endif
    </div>
</nav>
