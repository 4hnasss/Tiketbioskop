<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flixora | Home</title>
    @vite('resources/css/app.css')
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        /* Animasi fade-in untuk elemen */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
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
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        /* Kelas untuk animasi */
        .animate-on-load {
            opacity: 0;
            animation: fadeInUp 0.8s ease-out forwards;
        }

        .animate-fade-in {
            opacity: 0;
            animation: fadeIn 0.6s ease-out forwards;
        }

        .animate-scale-in {
            opacity: 0;
            animation: scaleIn 0.6s ease-out forwards;
        }

        /* Delay untuk animasi bertahap */
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }
        .delay-500 { animation-delay: 0.5s; }
        .delay-600 { animation-delay: 0.6s; }

        /* Animasi hover untuk tombol */
        .btn-hover-scale {
            transition: transform 0.3s ease, background-color 0.3s ease;
        }
        .btn-hover-scale:hover {
            transform: scale(1.05);
        }
    </style>
</head>

<body class="bg-gradient-to-b from-[#e0f2fe] to-[#ffffff] min-h-screen font-sans text-gray-800 flex flex-col">

    {{-- Navbar --}}
    @include('components.navbar')

    <main class="flex flex-col w-full pt-8 flex-grow space-y-12 px-6 sm:px-10 md:px-16 lg:px-28 xl:px-36 mt-10">

        {{-- Section: Sedang Tayang --}}
        <section class="animate-on-load">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl md:text-3xl font-serif font-bold">Sedang Tayang</h2>
                <a href="{{ route('film') }}" class="animate-fade-in delay-100 text-sm text-[#3b82f6] font-medium hover:underline btn-hover-scale">Lihat Semua</a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-4 gap-4">
                @foreach ($filmPlayNow->take(4) as $index => $film)
                    <div class="animate-scale-in delay-{{ min(($index + 1) * 100, 400) }} group relative flex flex-col items-center transition duration-500 hover:scale-[1.02]">
                        {{-- Card Poster --}}
                        <div class="relative rounded-lg overflow-hidden shadow-md w-full">
                            <img src="{{ asset('img/' . $film->poster) }}" alt="{{ $film->judul }}" class="w-full aspect-[2/3] object-cover transition-all duration-500">
                            {{-- Overlay gradasi putih dari bawah ke atas --}}
                            <div class="absolute inset-0 flex flex-col justify-end items-center opacity-0 group-hover:opacity-100 transition-all duration-500 ease-in-out bg-gradient-to-t from-white via-white/70 to-transparent">
                                <div class="w-full px-4 pb-4 text-center">
                                    {{-- Tombol --}}
                                    <div class="flex justify-center items-center gap-2 mb-2">
                                        <button type="button" data-trailer="{{ asset('trailers/' . $film->trailer) }}" class="see-trailer-btn btn-hover-scale flex items-center gap-1 px-4 py-1.5 bg-white border border-gray-300 text-gray-800 text-xs font-semibold rounded-full shadow-sm hover:bg-gray-100 transition cursor-pointer">
                                            <i data-lucide="play" class="w-4 h-4"></i> See Trailer
                                        </button>
                                        <a href="{{ route('film.detailfilm', $film->id) }}" class="btn-hover-scale flex items-center gap-1 px-4 py-1.5 bg-black text-white text-xs font-semibold rounded-full shadow-sm hover:bg-gray-900 transition cursor-pointer">
                                            <i data-lucide='ticket' class='w-3.5 h-3.5'></i> Buy tickets
                                        </a>
                                    </div>
                                    {{-- Info tambahan --}}
                                    <p class="text-gray-700 text-xs flex justify-center items-center gap-1 mb-1">
                                        <span class="bg-yellow-400 text-black px-1.5 py-0.5 rounded text-[10px] font-bold">{{ $film->genre }}</span>
                                        {{ floor($film->durasi / 60) }}h {{ $film->durasi % 60 }}m
                                    </p>
                                    {{-- Tanggal selesai --}}
                                    <p class="text-gray-500 text-xs"> Selesai tayang: {{ $film->tanggalselesai }} </p>
                                </div>
                            </div>
                        </div>
                        {{-- Judul di bawah poster --}}
                        <h3 class="mt-2 text-sm md:text-base font-semibold text-gray-800 line-clamp-1 opacity-0 group-hover:opacity-100 translate-y-2 group-hover:translate-y-0 transition-all duration-500 ease-out">
                            {{ $film->judul }}
                        </h3>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Section: Akan Tayang --}}
        <section class="animate-on-load delay-200">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl md:text-3xl font-serif font-bold">Akan Tayang</h2>
                <a href="{{ route('film') }}" class="animate-fade-in delay-300 text-sm text-[#3b82f6] font-medium hover:underline btn-hover-scale">Lihat Semua</a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach ($filmUpcoming->take(6) as $index => $film)
                    <div class="animate-scale-in delay-{{ min(($index + 1) * 100, 600) }} group relative flex flex-col items-center transition duration-500 hover:scale-[1.02]">
                        {{-- Card Poster --}}
                        <div class="relative rounded-lg overflow-hidden shadow-md w-full">
                            <img src="{{ asset('img/' . $film->poster) }}" alt="{{ $film->judul }}" class="w-full aspect-[2/3] object-cover transition-all duration-500">
                            {{-- Overlay gradasi putih dari bawah ke atas untuk tombol --}}
                            <div class="absolute inset-0 flex flex-col justify-end items-center opacity-0 group-hover:opacity-100 transition-all duration-500 ease-in-out bg-gradient-to-t from-white via-white/70 to-transparent">
                                <div class="w-full px-3 pb-3 text-center">
                                    {{-- Tombol lebih kecil agar proporsional --}}
                                    <div class="flex justify-center items-center gap-2 mb-1">
                                        <button type="button" data-trailer="{{ asset('trailers/' . $film->trailer) }}" class="see-trailer-btn btn-hover-scale flex items-center gap-1 px-3 py-1 bg-white border border-gray-300 text-gray-800 text-[10px] font-semibold rounded-full shadow-sm hover:bg-gray-100 transition cursor-pointer">
                                            <i data-lucide="play" class="w-3 h-3"></i> See Trailer
                                        </button>
                                        <a href="{{ route('film.detailfilm', $film->id) }}" class="btn-hover-scale flex items-center gap-1 px-3 py-1 bg-black text-white text-[10px] font-semibold rounded-full shadow-sm hover:bg-gray-900 transition cursor-pointer">
                                            <i data-lucide='ticket' class='w-3 h-3'></i> Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- Judul --}}
                        <h3 class="mt-2 text-sm md:text-base font-semibold text-gray-800 line-clamp-1 opacity-0 group-hover:opacity-100 translate-y-1 group-hover:translate-y-0 transition-all duration-500 ease-out">
                            {{ $film->judul }}
                        </h3>
                        {{-- Tanggal tayang selalu terlihat --}}
                        <p class="text-xs text-gray-500 mt-1">Tayang: {{ $film->tanggalmulai }}</p>
                    </div>
                @endforeach
            </div>
        </section>

    </main>

    {{-- Popup Trailer --}}
    <div id="trailerModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 hidden transition-opacity duration-300 cursor-pointer">
        <div id="trailerContainer" class="relative bg-white rounded-2xl overflow-hidden shadow-2xl w-[90%] max-w-2xl scale-95 opacity-0 transition-all duration-300 cursor-default"
             onclick="event.stopPropagation()">
            <video id="trailerVideo" class="w-full" controls preload="metadata"></video>
            <button id="closeTrailerBtn" type="button" 
                    class="btn-hover-scale absolute top-2 right-2 bg-[#14274E]/80 hover:bg-[#14274E] text-white rounded-full p-2 shadow-lg transition cursor-pointer">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>
    </div>

    @include('components.footer')

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof lucide !== '') lucide.createIcons();

            const modal = document.getElementById('trailerModal');
            const container = document.getElementById('trailerContainer');
            const video = document.getElementById('trailerVideo');
            const closeBtn = document.getElementById('closeTrailerBtn');
            const trailerBtns = document.querySelectorAll('.see-trailer-btn');

            function openTrailer(url) {
                video.src = url;
                video.currentTime = 0;
                modal.classList.remove('hidden');
                requestAnimationFrame(() => {
                    modal.classList.add('opacity-100');
                    container.classList.add('opacity-100', 'scale-100');
                });
                const playPromise = video.play();
                if (playPromise) playPromise.catch(err => console.warn(err));
            }

            function closeTrailer() {
                video.pause();
                video.removeAttribute('src');
                video.load();
                modal.classList.remove('opacity-100');
                container.classList.remove('opacity-100', 'scale-100');
                setTimeout(() => { modal.classList.add('hidden'); }, 300);
            }

            trailerBtns.forEach(btn => {
                btn.addEventListener('click', e => {
                    e.stopPropagation();
                    const url = btn.getAttribute('data-trailer');
                    if (!url) {
                        alert('Trailer tidak tersedia untuk film ini.');
                        return;
                    }
                    openTrailer(url);
                });
            });

            modal.addEventListener('click', e => { if(e.target === modal) closeTrailer(); });
            closeBtn.addEventListener('click', closeTrailer);
        });
    </script>

    @vite('resources/js/app.js')
</body>
</html>
