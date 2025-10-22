<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Film | Flixora</title>
  @vite('resources/css/app.css')
  <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="bg-gradient-to-b from-[#f4f8ff] to-[#d9e5f5] min-h-screen text-gray-800">
@include('components.navbar')

<main class="flex justify-center py-16 px-4 md:px-10">
  <div class="relative max-w-4xl w-full bg-white/70 backdrop-blur-md border border-white/40 rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition duration-500 p-8">

    {{-- Efek dekorasi --}}
    <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-bl from-[#3b82f6]/40 to-transparent rounded-bl-full blur-2xl opacity-30"></div>
    <div class="absolute bottom-0 left-0 w-40 h-40 bg-gradient-to-tr from-[#14274E]/30 to-transparent rounded-tr-full blur-2xl opacity-40"></div>

    {{-- Bagian atas: poster + info --}}
    <div class="flex flex-col md:flex-row items-start md:items-center gap-8 mb-8 relative z-10">
      <img src="{{ asset($film->poster) }}" alt="{{ $film->judul }}" class="w-full md:w-[230px] h-[320px] object-cover rounded-2xl shadow-md">

      <div class="flex flex-col justify-between space-y-3 md:space-y-4">
        <h1 class="text-3xl font-bold text-[#14274E]">{{ $film->judul }}</h1>
        <p class="text-gray-600 text-sm">{{ $film->genre }}</p>

        <div class="flex flex-wrap gap-2 text-[#1E56A0] text-sm font-medium items-center">
          <i data-lucide="calendar" class="w-4 h-4"></i>
          <span>{{ $film->tanggalmulai }} - {{ $film->tanggalselesai }}</span>
        </div>

        <span class="inline-block bg-[#e6efff] text-[#14274E] text-xs px-3 py-1 rounded-full font-medium w-fit">{{ $film->durasi }}</span>
      </div>
    </div>

    {{-- Sinopsis --}}
    <div class="bg-white/70 border border-[#14274E]/10 rounded-2xl p-6 shadow-sm hover:shadow-md transition mb-8">
      <h3 class="text-lg font-semibold text-[#14274E] flex items-center gap-2 mb-3">
        <i data-lucide="align-left" class="w-5 h-5"></i> Sinopsis
      </h3>
      <p class="text-sm text-gray-700 leading-relaxed">
        {{ $film->deskripsi }}
      </p>
    </div>

    {{-- Jadwal --}}
    @foreach ($jadwals as $tanggal => $jadwalList)
    <div class="bg-white/70 border border-[#14274E]/10 rounded-2xl p-6 shadow-sm hover:shadow-md transition mb-6">
      <div class="flex items-center justify-between mb-3">
        <h3 class="text-lg font-semibold text-[#14274E] flex items-center gap-2">
          <i data-lucide="clock" class="w-5 h-5"></i> Jadwal Tayang
        </h3>
        <input 
          type="date" 
          id="tanggal" 
          value="{{ $tanggal }}"
          class="border border-[#14274E]/40 text-[#14274E] text-sm px-3 py-1.5 rounded-md bg-white/60 cursor-pointer hover:border-[#14274E]/70 transition"
        />
      </div>

      <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
        @foreach ($jadwalList as $jadwal)
          <a 
            href="{{ route('kursi', ['film' => $film->id, 'jadwal' => $jadwal->id]) }}"
            class="relative overflow-hidden group border border-[#14274E]/50 rounded-full py-2 text-center text-[#14274E] font-semibold text-sm transition hover:bg-[#14274E] hover:text-white hover:shadow-md">
            {{ date('H:i', strtotime($jadwal->jamtayang)) }}
            <div class="absolute inset-0 bg-gradient-to-r from-[#14274E]/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
          </a>
        @endforeach
      </div>
    </div>
    @endforeach

    {{-- Tombol kembali --}}
    <div class="pt-4 text-center">
      <a href="/film" class="inline-block bg-[#14274E] text-white text-sm px-6 py-2 rounded-full font-medium hover:bg-[#1E3A8A] transition transform hover:scale-105 shadow-md hover:shadow-lg">
        Kembali ke Daftar Film
      </a>
    </div>

  </div>
</main>

@include('components.footer')
<script>lucide.createIcons();</script>
</body>
</html>
