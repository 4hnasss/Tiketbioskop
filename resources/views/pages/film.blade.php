<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Film | Flixora</title>
    @vite('resources/css/app.css')
    <style>
        /* ===========================
           ANIMASI ENTRY HALAMAN
        =========================== */
        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-40px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Animasi untuk tombol filter */
        @keyframes buttonPop {
            0% {
                opacity: 0;
                transform: scale(0.8) translateY(20px);
            }
            60% {
                transform: scale(1.05) translateY(0);
            }
            100% {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        /* Animasi untuk poster cards */
        @keyframes cardFadeIn {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.9);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Animasi shine effect untuk button */
        @keyframes shine {
            0% {
                left: -100%;
            }
            100% {
                left: 100%;
            }
        }

        /* Kelas animasi */
        .animate-fade-in-down {
            opacity: 0;
            animation: fadeInDown 0.8s ease-out forwards;
        }

        .animate-fade-in-left {
            opacity: 0;
            animation: fadeInLeft 0.7s ease-out forwards;
        }

        .animate-scale-in {
            opacity: 0;
            animation: scaleIn 0.6s ease-out forwards;
        }

        .animate-slide-in-up {
            opacity: 0;
            animation: slideInUp 0.7s ease-out forwards;
        }

        .animate-button-pop {
            opacity: 0;
            animation: buttonPop 0.6s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
        }

        .animate-card-fade-in {
            opacity: 0;
            animation: cardFadeIn 0.6s ease-out forwards;
        }

        /* Hover effects */
        .film-card {
            transition: all 0.3s ease;
        }

        .film-card:hover {
            transform: translateY(-8px);
        }

        /* Button shine effect */
        .button-shine {
            position: relative;
            overflow: hidden;
        }

        .button-shine::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
            transition: left 0.5s;
        }

        .button-shine:hover::before {
            animation: shine 0.8s;
        }

        /* Filter button active state animation */
        .filter-btn {
            transition: all 0.3s ease;
        }

        .filter-btn:active {
            transform: scale(0.95);
        }

        /* Delay classes */
        .delay-100 { animation-delay: 0.1s; }
        .delay-200 { animation-delay: 0.2s; }
        .delay-300 { animation-delay: 0.3s; }
        .delay-400 { animation-delay: 0.4s; }
        .delay-500 { animation-delay: 0.5s; }
        .delay-600 { animation-delay: 0.6s; }
        .delay-700 { animation-delay: 0.7s; }
        .delay-800 { animation-delay: 0.8s; }
        .delay-900 { animation-delay: 0.9s; }
        .delay-1000 { animation-delay: 1s; }

        /* Section transition */
        .section-transition {
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .section-hidden {
            opacity: 0;
            transform: translateY(20px);
            display: none;
        }

        .section-visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body class="bg-gradient-to-b from-[#e0f2fe] to-[#ffffff] min-h-screen font-sans text-gray-800 flex flex-col">

    {{-- Navbar --}}
    @include('components.navbar')

    <div class="px-[40px] py-10">

        <h1 class="animate-fade-in-down text-3xl md:text-4xl font-bold text-[#14274E] mb-6">Film</h1>

        {{-- Tombol filter section --}}
        <div class="flex items-center gap-3 mb-8">
            <button id="btn-lagi" class="animate-button-pop delay-100 filter-btn button-shine px-5 py-2 rounded-full font-medium text-white bg-[#14274E] border border-[#14274E] transition duration-300 cursor-pointer hover:shadow-lg">
                Lagi Tayang
            </button>
            <button id="btn-akan" class="animate-button-pop delay-200 filter-btn button-shine px-5 py-2 rounded-full font-medium text-[#14274E] border border-[#14274E] bg-transparent hover:bg-[#14274E]/10 transition duration-300 cursor-pointer hover:shadow-md">
                Akan Tayang
            </button>
        </div>

        {{-- Section: Lagi Tayang & Akan Tayang --}}
        @foreach(['lagi' => $filmPlayNow, 'akan' => $filmUpcoming] as $type => $films)
            <div id="section-{{ $type }}" class="section-transition grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-5 {{ $type == 'akan' ? 'section-hidden' : 'section-visible' }}">
                @forelse($films as $index => $film)
                    <div class="animate-card-fade-in delay-{{ min(($index + 3) * 100, 1000) }} film-card relative group flex flex-col items-start">

                        {{-- Poster --}}
                        <div class="relative w-full overflow-hidden rounded-lg shadow-md">
                            <img src="{{ asset('img/' . $film->poster) }}" 
                                 alt="{{ $film->judul }}" 
                                 class="w-full h-[320px] object-cover rounded-lg transition duration-500 group-hover:scale-105">

                            {{-- Overlay putih + tombol --}}
                            <div class="absolute inset-0 bg-white/80 opacity-0 group-hover:opacity-100 transition duration-500 flex items-center justify-center">
                                <div class="flex flex-col gap-2">
                                    <button type="button" 
                                            data-trailer="{{ asset('trailers/' . $film->trailer) }}" 
                                            class="see-trailer-btn button-shine px-5 py-2 border-2 border-[#14274E] text-[#14274E] font-medium rounded-full shadow-sm hover:bg-[#14274E]/10 transition duration-300 transform hover:scale-105">
                                        🎬 Lihat Trailer
                                    </button>
                                    <a href="{{ route('film.detailfilm', $film->id) }}" 
                                       class="button-shine px-5 py-2 bg-[#14274E] text-white font-medium rounded-full shadow-sm hover:bg-[#1E56A0] transition duration-300 transform hover:scale-105">
                                        🎫 Beli Tiket
                                    </a>
                                </div>
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
                    </div>
                @empty
                    <div class="animate-fade-in-down delay-300 text-gray-500 col-span-full text-center py-10">
                        <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>
                        </svg>
                        <p class="text-lg font-medium">Tidak ada film untuk kategori ini.</p>
                    </div>
                @endforelse
            </div>
        @endforeach

    </div>

    {{-- Popup Trailer --}}
    <div id="trailerModal" class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 hidden transition-opacity duration-300">
        <div id="trailerContainer" class="relative bg-white rounded-2xl overflow-hidden shadow-2xl w-[90%] max-w-2xl scale-95 opacity-0 transition-all duration-300">
            <video id="trailerVideo" class="w-full" controls preload="metadata"></video>
            <button id="closeTrailerBtn" type="button" 
                    class="absolute top-2 right-2 bg-[#14274E]/80 hover:bg-[#14274E] text-white rounded-full p-2 shadow-lg transition cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
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

        function switchSection(showSection, hideSection) {
            // Hide current section with fade out
            hideSection.classList.remove('section-visible');
            hideSection.classList.add('section-hidden');
            
            // Show new section with fade in after a short delay
            setTimeout(() => {
                showSection.classList.remove('section-hidden');
                showSection.classList.add('section-visible');
                
                // Re-trigger card animations
                const cards = showSection.querySelectorAll('.film-card');
                cards.forEach((card, index) => {
                    card.style.animation = 'none';
                    setTimeout(() => {
                        card.style.animation = '';
                    }, 10);
                });
            }, 150);
        }

        btnLagi.addEventListener('click', () => {
            if (!sectionLagi.classList.contains('section-visible')) {
                switchSection(sectionLagi, sectionAkan);
                setActive(btnLagi, btnAkan);
            }
        });

        btnAkan.addEventListener('click', () => {
            if (!sectionAkan.classList.contains('section-visible')) {
                switchSection(sectionAkan, sectionLagi);
                setActive(btnAkan, btnLagi);
            }
        });

        // ===========================
        // TRAILER POPUP HANDLER
        // ===========================
        const modal = document.getElementById('trailerModal');
        const container = document.getElementById('trailerContainer');
        const video = document.getElementById('trailerVideo');
        const closeBtn = document.getElementById('closeTrailerBtn');
        const trailerBtns = document.querySelectorAll('.see-trailer-btn');

        function openTrailer(url) {
            if (!url) {
                alert('Trailer tidak tersedia untuk film ini.');
                return;
            }
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

        // Event listeners untuk trailer buttons
        document.addEventListener('click', (e) => {
            if (e.target.closest('.see-trailer-btn')) {
                e.stopPropagation();
                const btn = e.target.closest('.see-trailer-btn');
                const url = btn.getAttribute('data-trailer');
                openTrailer(url);
            }
        });

        modal.addEventListener('click', (e) => { 
            if(e.target === modal) closeTrailer(); 
        });
        
        closeBtn.addEventListener('click', closeTrailer);
    </script>

    {{-- Footer --}}
    @include('components.footer')

</body>
</html>