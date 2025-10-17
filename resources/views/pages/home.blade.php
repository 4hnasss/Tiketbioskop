<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flixora | Home</title>
    @vite('resources/css/app.css')
</head>
<body>
    <div class="bg-gradient-to-r from-white to-[#D6E4F0] min-h-screen flex flex-col ">
        {{-- Navbar --}}
        @include('components.navbar')

        <div class="flex flex-col pr-[130px] pl-[130px]">
            {{-- Section: Lagi tayang --}}
        <div class="container mx-auto px-6">
            <div class="flex justify-between items-center mt-6 mb-4">
                <p class="text-[25px] text-black font-bold">Lagi tayang</p>
                <a href="/film" class="border-2 border-[#14274E] rounded-[25px] bg-[#D6E4F0] flex items-center px-2 gap-2 text-[13px] font-serif text-[#14274E] font-medium hover:text-[#319fff] hover:border-[#319fff] mt-4 cursor-pointer">
                    Lihat Semua <span class="text-xl">➜</span>
                </a>
            </div>
            

            {{-- Grid Film Lagi Tayang --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
                <a href="/detailfilm">
                    <div class="bg-white rounded-lg shadow hover:shadow-md transition">
                        <img src="/img/pangepungan.jpg" alt="pangepungan" class="w-full h-[356px] object-cover rounded-t-lg">
                        <div class="p-3 text-center flex-col items-center">
                            <p class="text-[20px] font-semibold text-black ">Pangepungan Di Bukit Duri</p>
                            <div class=" gap-2 text-black text-xs mt-1 ">
                                <span class="rounded-[2px] h-full bg-[#D6E4F0]">1h54m</span>
                                <span>|</span>
                                <span>action</span>
                            </div>
                            <p class="text-xs text-black-400 mt-5 font-serif text-center">Flixora</p>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        {{-- Section: Akan tayang --}}
        <div class="container mx-auto px-6 mt-10">
            <div class="flex justify-between items-center mb-4">
                <p class="text-[25px] text-black font-bold">Akan tayang</p>
                <a href="/film" class="border-2 border-[#14274E] rounded-[25px] bg-[#D6E4F0] flex items-center px-2 gap-2 text-[13px] font-serif text-[#14274E] font-medium hover:text-[#319fff] hover:border-[#319fff] mt-4 cursor-pointer">
                    Lihat Semua <span class="text-xl">➜</span>
                </a>
            </div>

            {{-- Grid Film Akan Tayang --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <a href="/login">
                    <div class="bg-white rounded-lg shadow hover:shadow-md transition kursor-pinter">
                        <img src="/img/Alie.jpg" alt="" class="w-full h-[220px] object-cover rounded-t-lg">
                        <div class="p-2">
                            <p class="text-[13px] font-medium text-gray-800 leading-tight"></p>
                            <p class="text-[11px] text-blue-600 font-medium mt-1">Tayang :</p>
                            <p class="text-[11px] text-gray-500">/p>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        </div>
        {{-- Footer --}}
        @include('components.footer')
    </div>

    @vite('resources/js/app.js')
</body>
</html>
