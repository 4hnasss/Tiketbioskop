<nav class="bg-white shadow-md w-full h-[79px] flex justify-center items-center p-[5px]">
        {{-- Logo --}}
        <div class="flex-col ml-[1px] ">
            <img src="/img/Brand.png" alt="Flixora" class="w-[115px] h-[60px]">
        </div>

        {{-- Search --}}
        <div class="flex-1 px-[3px] flex items-center ">
            <div class="relative w-fit">
                <img src="/img/search.png" alt="search" 
                    class="absolute left-3 top-1/2  w-4 h-4 ">
                
                <input type="text" placeholder="Cari Film" 
                class="bg-gray-200 mt-[20px] w-[202px] h-[33px]  border-1 border-[#14274E] rounded-full pl-10 pr-4 py-2 focus:outline-none focus:ring-2 text-[15px]">
            </div>
        </div>


        {{-- Auth Buttons --}}
        <div class="flex space-x-3 items-center">
            <a href="/login" class="text-[#14274E] hover:text-blue-600 font-serif">Login</a>
            <a href="/registrasi" class=" px-6 border-2 border-[#14274E] w-[110px] h-[35px] rounded-full text-[#14274E] text-center font-serif hover:bg-[#e8f0fc] transition flex items-center">
                Register
            </a>
            <a href="/tiket" class="text-[#14274E] hover:text-blue-600 font-serif">tiket</a>
        </div>
</nav>
