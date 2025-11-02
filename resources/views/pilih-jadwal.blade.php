<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pilih Jadwal - {{ $film->judul }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    @include('components.nav')

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Film Info -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="flex items-start space-x-6">
                @if($film->poster)
                    <img src="{{ asset('img/' . $film->poster) }}" alt="{{ $film->judul }}" class="w-32 h-48 object-cover rounded-lg">
                @else
                    <div class="w-32 h-48 bg-gray-200 rounded-lg flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z"/>
                        </svg>
                    </div>
                @endif
                <div class="flex-1">
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $film->judul }}</h2>
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-600">Genre:</span>
                            <span class="font-medium ml-2">{{ $film->genre }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Durasi:</span>
                            <span class="font-medium ml-2">{{ $film->durasi }} menit</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Harga:</span>
                            <span class="font-medium ml-2 text-indigo-600">
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
            <h3 class="text-xl font-bold text-gray-800 mb-4">Pilih Jadwal Tayang</h3>
        </div>

        @if($jadwals->count() > 0)
            <div class="space-y-4">
                @php
                    $jadwalByDate = $jadwals->groupBy(function($item) {
                        return \Carbon\Carbon::parse($item->tanggal)->format('Y-m-d');
                    });
                @endphp

                @foreach($jadwalByDate as $tanggal => $jadwalGroup)
                    <div class="bg-white rounded-lg shadow-md p-6">
                        <h4 class="text-lg font-bold text-gray-800 mb-4">
                            {{ \Carbon\Carbon::parse($tanggal)->isoFormat('dddd, D MMMM YYYY') }}
                        </h4>
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
                            @foreach($jadwalGroup as $jadwal)
                                <a href="{{ route('pilih-kursi', ['film' => $film->id, 'jadwal' => $jadwal->id]) }}" 
                                   class="border-2 border-indigo-200 rounded-lg p-4 text-center hover:border-indigo-500 hover:bg-indigo-50 transition group">
                                    <div class="text-2xl font-bold text-indigo-600 mb-1">
                                        {{ \Carbon\Carbon::parse($jadwal->jamtayang)->format('H:i') }}
                                    </div>
                                    <div class="text-xs text-gray-600">
                                        Studio {{ $jadwal->studio->nama_studio }}
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-8 text-center">
                <svg class="w-16 h-16 text-yellow-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-gray-600 text-lg">Tidak ada jadwal tersedia untuk film ini</p>
            </div>
        @endif
    </div>
</body>
</html>