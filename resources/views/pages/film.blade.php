<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Film | Flixora</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-b from-[#e0f2fe] to-[#ffffff] min-h-screen font-sans text-gray-800 flex flex-col">

    {{-- Navbar --}}
    @include('components.navbar')

    <div class="px-[40px] py-10">

        <h1 class="text-3xl md:text-4xl font-bold text-[#14274E] mb-6">Film</h1>

        {{-- Tombol filter section --}}
        <div class="flex items-center gap-3 mb-8">
            <button id="btn-lagi" class="px-5 py-2 rounded-full font-medium text-white bg-[#14274E] border border-[#14274E] transition duration-300 cursor-pointer">
                Lagi Tayang
            </button>
            <button id="btn-akan" class="px-5 py-2 rounded-full font-medium text-[#14274E] border border-[#14274E] bg-transparent hover:bg-[#14274E]/10 transition duration-300 cursor-pointer">
                Akan Tayang
            </button>
        </div>

        {{-- Section: Lagi Tayang & Akan Tayang --}}
        @foreach(['lagi' => $filmPlayNow, 'akan' => $filmUpcoming] as $type => $films)
            <div id="section-{{ $type }}" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-5 {{ $type == 'akan' ? 'hidden' : '' }}">
                @forelse($films as $film)
                    <div class="relative group flex flex-col items-start">

                        {{-- Poster --}}
                        <div class="relative w-full overflow-hidden rounded-lg shadow-md">
                            <img src="{{ asset('img/' . $film->poster) }}" 
                                 alt="{{ $film->judul }}" 
                                 class="w-full h-[320px] object-cover rounded-lg transition duration-500 group-hover:scale-105">

                            {{-- Overlay putih + tombol detail --}}
                            <div class="absolute inset-0 bg-white/80 opacity-0 group-hover:opacity-100 transition duration-500 flex items-center justify-center">
                                <a href="{{ route('film.detailfilm', $film->id) }}" 
                                   class="px-5 py-2 border-2 border-[#14274E] text-[#14274E] font-medium rounded-full shadow-sm hover:bg-[#14274E]/10 transition duration-300">
                                    Detail
                                </a>
                            </div>
                        </div>

                        {{-- Judul --}}
                        <p class="mt-2 font-semibold text-sm text-[#14274E] leading-tight">
                            {{ $film->judul }}
                        </p>

                        {{-- Durasi --}}
                        <p class="text-xs text-gray-600 flex items-center mt-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1 text-[#14274E]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ floor($film->durasi / 60) }}h {{ $film->durasi % 60 }}m
                        </p>

                        {{-- Jam tayang (sudah difilter di Controller) --}}
                        <div class="mt-2 flex flex-wrap gap-2">
                            @if($film->jadwals->count())
                                @foreach($film->jadwals as $jadwal)
                                    <a href="{{ route('kursi', ['film'=>$film->id, 'jadwal'=>$jadwal->id]) }}" 
                                       class="px-3 py-1 text-sm bg-[#E7EEF8] border border-[#14274E] text-[#14274E] rounded-full hover:bg-[#14274E] hover:text-white transition duration-300">
                                       {{ date('H:i', strtotime($jadwal->jamtayang)) }}
                                    </a>
                                @endforeach
                            @else
                                <span class="text-gray-400 text-xs">Tidak ada jadwal hari ini</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 col-span-full text-center py-10">
                        Tidak ada film untuk kategori ini.
                    </p>
                @endforelse
            </div>
        @endforeach

    </div>

    {{-- Script toggle section --}}
    <script>
        const btnLagi = document.getElementById('btn-lagi');
        const btnAkan = document.getElementById('btn-akan');
        const sectionLagi = document.getElementById('section-lagi');
        const sectionAkan = document.getElementById('section-akan');

        function setActive(activeBtn, inactiveBtn) {
            activeBtn.classList.add('bg-[#14274E]', 'text-white');
            activeBtn.classList.remove('bg-transparent', 'text-[#14274E]');
            inactiveBtn.classList.remove('bg-[#14274E]', 'text-white');
            inactiveBtn.classList.add('bg-transparent', 'text-[#14274E]');
        }

        btnLagi.addEventListener('click', () => {
            sectionLagi.classList.remove('hidden');
            sectionAkan.classList.add('hidden');
            setActive(btnLagi, btnAkan);
        });

        btnAkan.addEventListener('click', () => {
            sectionAkan.classList.remove('hidden');
            sectionLagi.classList.add('hidden');
            setActive(btnAkan, btnLagi);
        });
    </script>

    {{-- Footer --}}
    @include('components.footer')

</body>
</html>
