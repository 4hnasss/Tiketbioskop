<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Film | Flixora</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-r from-white to-[#D6E4F0] min-h-screen">
@include('components.navbar')
        
        {{-- Konten Utama --}}
            <div class="max-w-[900px] mx-auto mt-10 px-4">
                <h1 class="text-3xl font-bold mb-8">Detail film</h1>

            <div class="ml-[30px] mr-[30px]">
                {{-- Bagian Atas --}}
                <div class="flex gap-6 mb-8">
                    {{-- Poster Film --}}
                    <img src="{{asset($film->poster)}}" alt="Poster Film" class="w-[180px] h-[260px] object-cover rounded-[5px] shadow">

                    {{-- Detail Info --}}
                    <div class="flex flex-col justify-start">
                        <p class="text-[12px] text-[#1E56A0] font-semibold mb-5">
                            Tayang : {{$film->tanggalmulai}} {{--tanggal mulai--}}
                            Selesai : {{$film->tanggalselesai}} {{--tanggal selesai--}}
                        </p>
                        <h2 class="text-lg font-bold mt-2 pb-3 ">{{$film->judul}}</h2> {{--judul--}}
                        <p class="text-gray-600 text-sm mt-1">{{$film->genre}}</p> {{--genre--}}
                        <span class="mt-3 inline-block w-fit bg-gray-200 text-gray-700 text-xs px-3 py-1 rounded">
                            {{$film->durasi}} {{--durasi--}}
                        </span>
                    </div>
                </div>

                {{-- Tab Navigasi --}}
                <div class="flex border-b border-gray-300 mb-6">
                    <p class="text-[#14274E] text-[25px] font-bold px-2">
                        Detail
                    </p>
                </div>

                {{-- Sinopsis --}}
                <div class="bg-white/60 border border-gray-300 rounded-2xl p-6 shadow-sm mr-[110px] ml-[110px] b-24 ">
                    <h3 class="text-lg font-semibold mb-5">Sinopsis</h3>
                    <div class="text-sm text-gray-700 leading-relaxed space-y-4">

                    {{--deskripsi--}}
                        <p>
                            {{$film->deskripsi}}
                        </p>
                    </div>
                </div>

                @foreach ($jadwals as $tanggal => $jadwalList)
                    <div class="flex mt-10">
                        <p class="text-[#14274E] text-[25px] font-bold px-2">
                            Jadwal
                        </p>
                        <div class="ml-auto">
                            <input 
                                type="date" 
                                id="tanggal" 
                                value="{{ $tanggal }}"
                                class="border-3 w-[143px] border-[#14274E] text-[#14274E] text-sm px-4 py-1 rounded-[5px] transition mb-[10px] cursor-pointer"
                            />
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-5 pr-[200px] pl-[200px] mt-10 mb-[100px]">
                        @foreach ($jadwalList as $jadwal)
                            <a 
                                href="{{ route('kursi', ['film' => $film->id, 'jadwal' => $jadwal->id]) }}"
                                class="w-full border-3 bg-[#D6E4F0] border-[#14274E] text-black text-center font-bold text-[15px] px-4 py-2 rounded-full hover:bg-blue-900 hover:text-white transition cursor-pointer">
                                {{ date('H:i', strtotime($jadwal->jamtayang)) }}
                            </a>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
@include('components.footer')
</body>
</html>
