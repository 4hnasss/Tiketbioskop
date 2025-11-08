{{-- resources/views/kasir/pilih-jadwal.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Jadwal - {{ $film->judul }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(to right, #ffffff, #D6E4F0);
        }
    </style>
</head>
<body>
    @include('components.nav')

    <div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
        <!-- Film Info -->
        <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl p-8 mb-8 border-2 border-[#D6E4F0]">
            <div class="flex items-start space-x-8">
                @if($film->poster)
                    <img src="{{ asset('img/' . $film->poster) }}" alt="{{ $film->judul }}" class="w-40 h-60 object-cover rounded-2xl shadow-lg">
                @else
                    <div class="w-40 h-60 bg-[#D6E4F0] rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-16 h-16 text-[#14274E]/30" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>
                        </svg>
                    </div>
                @endif
                <div class="flex-1">
                    <h2 class="text-3xl font-bold text-[#14274E] mb-4">{{ $film->judul }}</h2>
                    <div class="grid grid-cols-2 gap-6">
                        <div class="bg-[#D6E4F0]/50 rounded-xl p-4">
                            <span class="text-[#14274E]/70 font-semibold block mb-1">Genre</span>
                            <span class="font-bold text-[#14274E] text-lg">{{ $film->genre }}</span>
                        </div>
                        <div class="bg-[#D6E4F0]/50 rounded-xl p-4">
                            <span class="text-[#14274E]/70 font-semibold block mb-1">Durasi</span>
                            <span class="font-bold text-[#14274E] text-lg">{{ $film->durasi }} menit</span>
                        </div>
                        <div class="col-span-2 bg-gradient-to-r from-[#1E56A0] to-[#14274E] rounded-xl p-4 text-white">
                            <span class="opacity-90 font-semibold block mb-1">Harga</span>
                            <span class="font-bold text-2xl">
                                @if(isset($hargaMin) && isset($hargaMax) && $hargaMin != $hargaMax)
                                    Rp {{ number_format($hargaMin, 0, ',', '.') }} - Rp {{ number_format($hargaMax, 0, ',', '.') }}
                                @else
                                    Rp {{ number_format($hargaMin ?? 20000, 0, ',', '.') }}
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Jadwal List -->
        <div class="mb-6">
            <h3 class="text-2xl font-bold text-[#14274E] mb-6">
                <i class="far fa-calendar-alt mr-3 text-[#1E56A0]"></i>Pilih Jadwal Tayang
            </h3>
        </div>

        @if($jadwals->count() > 0)
            <div class="space-y-6">
                @php
                    $jadwalByDate = $jadwals->groupBy(function($item) {
                        return \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d');
                    });
                @endphp

                @foreach($jadwalByDate as $tanggal => $jadwalGroup)
                    <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl p-8 border-2 border-[#D6E4F0]">
                        <h4 class="text-xl font-bold text-[#14274E] mb-6 flex items-center">
                            <i class="far fa-calendar mr-3 text-[#1E56A0]"></i>
                            {{ \Carbon\Carbon::parse($tanggal)->isoFormat('dddd, D MMMM YYYY') }}
                        </h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                            @foreach($jadwalGroup as $jadwal)
                                <a href="{{ route('pilih-kursi', ['film' => $film->id, 'jadwal' => $jadwal->id]) }}" 
                                   class="border-2 border-[#D6E4F0] rounded-xl p-5 text-center hover:border-[#1E56A0] hover:bg-[#1E56A0] hover:text-white hover:scale-105 transition-all duration-200 group shadow-md bg-white/50">
                                    <div class="text-2xl font-bold text-[#1E56A0] group-hover:text-white mb-2">
                                        {{ \Carbon\Carbon::parse($jadwal->jamtayang)->format('H:i') }}
                                    </div>
                                    <div class="text-xs text-[#14274E]/70 group-hover:text-white font-semibold">
                                         {{ $jadwal->studio->nama_studio }}
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-yellow-50 border-2 border-yellow-200 rounded-2xl p-12 text-center shadow-xl">
                <svg class="w-20 h-20 text-yellow-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-[#14274E] text-lg font-semibold">Tidak ada jadwal tersedia untuk film ini</p>
            </div>
        @endif
    </div>

    @include('components.footer')
</body>
</html>