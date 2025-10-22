<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Flixora | Home</title>
@vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-b from-[#e0f2fe] to-[#ffffff] min-h-screen font-sans text-gray-800 flex flex-col">

    {{-- Navbar --}}
    @include('components.navbar')

    <main class="flex flex-col w-full pt-8 flex-grow space-y-12 px-6 sm:px-10 md:px-16 lg:px-28 xl:px-36 mt-10">

        {{-- Section: Sedang Tayang --}}
        <section>
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl md:text-3xl font-serif font-bold">Sedang Tayang</h2>
                <a href="/film" class="text-sm text-[#3b82f6] font-medium hover:underline">Lihat Semua</a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 gap-4">
                @foreach ($filmPlayNow as $film)
                    <div class="group relative bg-white/20 backdrop-blur-md rounded-lg shadow-md overflow-hidden transform transition duration-500 hover:scale-105">
                        <img src="{{ asset($film->poster) }}" alt="{{ $film->judul }}" class="w-full aspect-[2/3] object-cover">

                        {{-- Overlay putih + tombol detail --}}
                        <div class="absolute inset-0 bg-white/80 opacity-0 group-hover:opacity-100 transition duration-500 flex items-center justify-center">
                            <a href="{{ route('film.detailfilm', $film->id) }}" 
                               class="px-5 py-2 border-2 border-[#14274E] text-[#14274E] font-medium rounded-full shadow-sm hover:bg-[#14274E]/10 transition duration-300">
                                Detail
                            </a>
                        </div>

                        <div class="mt-2 text-center p-2">
                            <p class="text-sm md:text-base font-semibold font-serif line-clamp-2">{{ $film->judul }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">{{ $film->durasi }} | {{ $film->genre }}</p>
                            <p class="text-xs text-gray-400 italic mt-1">Flixora</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Section: Akan Tayang --}}
        <section>
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl md:text-3xl font-serif font-bold">Akan Tayang</h2>
                <a href="/film" class="text-sm text-[#3b82f6] font-medium hover:underline">Lihat Semua</a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach ($filmUpcoming as $film)
                    <div class="group relative bg-white/30 backdrop-blur-md rounded-lg shadow-md overflow-hidden transform transition duration-500 hover:scale-105 h-[360px]">
                        <div class="w-full h-[260px] overflow-hidden">
                            <img src="{{ asset($film->poster) }}" alt="{{ $film->judul }}" class="w-full h-full object-cover">
                        </div>

                        {{-- Overlay putih + tombol detail --}}
                        <div class="absolute inset-0 bg-white/80 opacity-0 group-hover:opacity-100 transition duration-500 flex items-center justify-center">
                            <a href="{{ route('film.detailfilm', $film->id) }}" 
                               class="px-5 py-2 border-2 border-[#14274E] text-[#14274E] font-medium rounded-full shadow-sm hover:bg-[#14274E]/10 transition duration-300">
                                Detail
                            </a>
                        </div>

                        <div class="flex-1 flex flex-col justify-between p-2 text-center">
                            <div>
                                <p class="text-sm md:text-base font-semibold font-serif line-clamp-2">{{ $film->judul }}</p>
                                <p class="text-xs text-gray-500 mt-0.5">Tayang: {{ $film->tanggalmulai }}</p>
                            </div>
                            <p class="text-xs text-gray-400 italic mt-1">Flixora</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

    </main>

    {{-- Footer --}}
    @include('components.footer')

    @vite('resources/js/app.js')
</body>
</html>
