<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Film | Flixora</title>
    @vite('resources/css/app.css')
</head>
<body>

    @include('components.navbar')

    <div class="pl-[150px] pr-[150px] bg-gradient-to-b from-white to-[#D6E4F0] min-h-screen p-10">
        
        <h1 class="text-4xl font-bold mb-6">Film</h1>

        {{-- Tombol filter --}}
        <div class="flex items-center gap-3 mb-8">
            <button id="btn-lagi" 
                class="px-4 py-2 rounded-full font-medium focus:outline-none text-white bg-[#14274E] border border-[#14274E] transition">
                Lagi tayang
            </button>
            <button id="btn-akan"
                class="px-4 py-2 rounded-full font-medium focus:outline-none text-[#14274E] border border-[#14274E] bg-transparent transition">
                Akan tayang
            </button>

            {{-- Input pencarian --}}
            <div class="flex-1 flex justify-end">
                <div class="relative">
                    <input type="text" placeholder="Cari Film" 
                        class="border text-[13px] border-[#14274E] rounded-full px-6 py-2 w-[150px] h-[35px] focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" 
                        class="absolute right-5 top-2.5 h-5 w-[15px] text-gray-500" fill="none" 
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                            d="M21 21l-4.35-4.35M10 18a8 8 0 100-16 8 8 0 000 16z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Section: Lagi Tayang --}}
        <div id="section-lagi" class="grid grid-cols-2 md:grid-cols-4 gap-6">
            @for ($i = 0; $i < 12; $i++)
            <div class="flex flex-col items-start">
                <a href="/detailfilm">
                    <img src="/img/Alie.jpg" alt="Film" class="w-full rounded-lg shadow-md">
                </a>
                <p class="mt-2 font-semibold text-sm">NGERI NGERI SEDAP</p>
                <p class="text-xs text-gray-500 flex items-center mt-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    1h 39m
                </p>
            </div>
            @endfor
        </div>

        {{-- Section: Akan Tayang --}}
        <div id="section-akan" class="hidden grid grid-cols-2 md:grid-cols-4 gap-6">
            @for ($i = 0; $i < 8; $i++)
            <div class="flex flex-col items-start">
                <a href="/detailfilm">
                    <img src="/img/Alie.jpg" alt="Film" class="w-full rounded-lg shadow-md">
                </a>
                <p class="mt-2 font-semibold text-sm">JUMBO</p>
                <p class="text-xs text-gray-500 flex items-center mt-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    2h 05m
                </p>
            </div>
            @endfor
        </div>

        {{-- Script toggle section --}}
        <script>
            const btnLagi = document.getElementById('btn-lagi');
            const btnAkan = document.getElementById('btn-akan');
            const sectionLagi = document.getElementById('section-lagi');
            const sectionAkan = document.getElementById('section-akan');

            btnLagi.addEventListener('click', () => {
                // tampilkan section "Lagi Tayang"
                sectionLagi.classList.remove('hidden');
                sectionAkan.classList.add('hidden');

                // ubah gaya tombol
                btnLagi.classList.add('bg-[#14274E]', 'text-white');
                btnLagi.classList.remove('bg-transparent', 'text-[#14274E]');
                
                btnAkan.classList.remove('bg-[#14274E]', 'text-white');
                btnAkan.classList.add('bg-transparent', 'text-[#14274E]');
            });

            btnAkan.addEventListener('click', () => {
                // tampilkan section "Akan Tayang"
                sectionAkan.classList.remove('hidden');
                sectionLagi.classList.add('hidden');

                // ubah gaya tombol
                btnAkan.classList.add('bg-[#14274E]', 'text-white');
                btnAkan.classList.remove('bg-transparent', 'text-[#14274E]');
                
                btnLagi.classList.remove('bg-[#14274E]', 'text-white');
                btnLagi.classList.add('bg-transparent', 'text-[#14274E]');
            });
        </script>
    </div>
</body>
</html>
