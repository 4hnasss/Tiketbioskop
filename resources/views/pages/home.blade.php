<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flixora | Home</title>
    @vite('resources/css/app.css')
</head>
<body>
    <div class="bg-gradient-to-r from-white to-[#D6E4F0] min-h-screen flex flex-col">
        {{-- Navbar --}}
        @include('components.navbar')

        <div class="flex flex-col pr-[130px] pl-[130px]">
            {{-- Section: Lagi Tayang --}}
            <div class="container mx-auto px-6">
                <div class="flex justify-between items-center mt-6 mb-4">
                    <p class="text-[25px] text-black font-bold">Sedang Tayang</p>
                    <a href="/film" class="border-2 border-[#14274E] rounded-[25px] bg-[#D6E4F0] flex items-center px-2 gap-2 text-[13px] font-serif text-[#14274E] font-medium hover:text-[#319fff] hover:border-[#319fff] mt-4 cursor-pointer">
                        Lihat Semua <span class="text-xl">➜</span>
                    </a>
                </div>

                {{-- Grid Film Lagi Tayang --}}
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-5">
                    @foreach ($filmPlayNow as $film)
                        <a href="/detailfilm/{{ $film->id }}">
                            <div class="bg-white rounded-lg shadow hover:shadow-md transition flex flex-col h-[460px] overflow-hidden">
                                
                                {{-- Poster Film --}}
                                <div class="w-full h-[340px] overflow-hidden">
                                    <img src="{{ asset($film->poster) }}" 
                                        alt="{{ $film->judul }}" 
                                        class="w-full h-full object-cover rounded-t-lg">
                                </div>

                                {{-- Info Film --}}
                                <div class="flex flex-col justify-between flex-grow p-3 text-center">
                                    <div>
                                        <p class="text-[18px] font-bold text-black line-clamp-2 h-[48px] leading-tight">
                                            {{ $film->judul }}
                                        </p>
                                        <div class="flex items-center justify-center gap-2 text-black text-xs mt-2">
                                            <span class="rounded-[3px] bg-[#D6E4F0] px-2 py-[2px]">{{ $film->durasi }}</span>
                                            <span>|</span>
                                            <span>{{ $film->genre }}</span>
                                        </div>
                                    </div>

                                    {{-- Label Flixora di bawah --}}
                                    <p class="text-xs text-gray-500 font-serif mt-4">Flixora</p>
                                </div>

                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Section: Akan Tayang --}}
            <div class="container mx-auto px-6 mt-10">
                <div class="flex justify-between items-center mb-4">
                    <p class="text-[25px] text-black font-bold">Akan Tayang</p>
                    <a href="/film" class="border-2 border-[#14274E] rounded-[25px] bg-[#D6E4F0] flex items-center px-2 gap-2 text-[13px] font-serif text-[#14274E] font-medium hover:text-[#319fff] hover:border-[#319fff] mt-4 cursor-pointer">
                        Lihat Semua <span class="text-xl">➜</span>
                    </a>
                </div>

                {{-- Grid Film Akan Tayang --}}
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    @foreach ($filmUpcoming as $film)
                        <a href="/detailfilm/{{ $film->id }}">
                            <div class="bg-white rounded-lg shadow hover:shadow-md transition cursor-pointer">
                                <img src="{{ asset($film->poster) }}" alt="{{ $film->judul }}" class="w-full h-[220px] object-cover rounded-t-lg">
                                <div class="p-2">
                                    <p class="text-[13px] font-medium text-gray-800 leading-tight">{{ $film->judul }}</p>
                                    <p class="text-[11px] text-blue-600 font-medium mt-1">Tayang: {{$film->tanggalmulai }}</p>
                                    <p class="text-[11px] text-gray-500">Selesai: {{ $film->tanggalselesai}}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Footer --}}
        @include('components.footer')
    </div>

    @vite('resources/js/app.js')
</body>
</html>
