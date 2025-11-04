<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Detail Film | TicketLy</title>
  @vite('resources/css/app.css')
  <script src="https://unpkg.com/lucide@latest"></script>
</head>

<body class="bg-gradient-to-b from-[#f4f8ff] to-[#d9e5f5] min-h-screen text-gray-800">
  @include('components.navbar')

  <main class="flex justify-center py-16 px-4 md:px-10">
    <div class="relative max-w-4xl w-full bg-white/70 backdrop-blur-md border border-white/40 rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition duration-500 p-8">

      {{-- Dekorasi latar --}}
      <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-bl from-[#3b82f6]/40 to-transparent rounded-bl-full blur-2xl opacity-30"></div>
      <div class="absolute bottom-0 left-0 w-40 h-40 bg-gradient-to-tr from-[#14274E]/30 to-transparent rounded-tr-full blur-2xl opacity-40"></div>

      {{-- Poster & Info Film --}}
      <div class="flex flex-col md:flex-row items-start md:items-center gap-8 mb-8 relative z-10">
        <div class="relative group w-full md:w-[230px] h-[320px] rounded-2xl overflow-hidden shadow-md cursor-pointer"
             onclick="bukaTrailer()">
          <img src="{{ asset('img/' . $film->poster) }}" 
               alt="{{ $film->judul }}" 
               class="w-full h-full object-cover transition duration-300 group-hover:scale-105">
          <div onclick="bukaTrailer()" 
               class="absolute inset-0 bg-white/60 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center cursor-pointer">
            <button class="see-trailer-btn flex items-center gap-1 px-4 py-1.5 bg-white border border-gray-300 text-gray-800 text-xs font-semibold rounded-full shadow-sm hover:bg-gray-100 transition cursor-pointer">
              <i data-lucide="play" class="w-4 h-4"></i> Trailer
            </button>
          </div>
        </div>

        <div class="flex flex-col justify-between space-y-3 md:space-y-4">
          <h1 class="text-3xl font-bold text-[#14274E]">{{ $film->judul }}</h1>
          <p class="text-gray-600 text-sm">{{ $film->genre }}</p>

          <div class="flex flex-wrap gap-2 text-[#1E56A0] text-sm font-medium items-center">
            <i data-lucide="calendar" class="w-4 h-4"></i>
            <span>{{ $film->tanggalmulai }} - {{ $film->tanggalselesai }}</span>
          </div>

          <span class="inline-block bg-[#e6efff] text-[#14274E] text-xs px-3 py-1 rounded-full font-medium w-fit">
            {{ floor($film->durasi / 60) }}h {{ $film->durasi % 60 }}m
          </span>
        </div>
      </div>

      {{-- Sinopsis --}}
      <div class="bg-white/60 border border-[#14274E]/10 rounded-2xl p-6 shadow-sm hover:shadow-md transition mb-8">
        <div class="flex items-center gap-2 mb-3">
          <i data-lucide="align-left" class="w-5 h-5 text-[#14274E]"></i>
          <h3 class="text-lg font-semibold text-[#14274E]">Sinopsis</h3>
        </div>
        <p class="text-sm text-gray-700 leading-relaxed border-l-4 border-[#1E56A0]/20 pl-4 italic">
          {{ $film->deskripsi }}
        </p>
      </div>
{{-- Jadwal Tayang --}}
@php
    use Carbon\Carbon;
    $today = Carbon::today()->toDateString();
    $tanggal = $tanggal ?? $today;
    $jadwalHariIni = $jadwals[$tanggal] ?? collect();
@endphp

<div class="bg-white/70 border border-[#14274E]/10 rounded-2xl p-6 shadow-sm hover:shadow-md transition mb-6">
    <div class="flex items-center justify-between mb-3">
        <h3 class="text-lg font-semibold text-[#14274E] flex items-center gap-2">
            <i data-lucide="clock" class="w-5 h-5"></i> Jadwal Tayang
        </h3>

        <input 
            type="date" 
            id="tanggal" 
            value="{{ $tanggal }}" 
            min="{{ $today }}" 
            max="{{ $film->tanggalselesai }}" 
            onchange="ubahTanggal(this.value)"
            class="border border-[#14274E]/40 text-[#14274E] text-sm px-3 py-1.5 rounded-md bg-white/60 cursor-pointer hover:border-[#14274E]/70 transition"
        />
    </div>

    <div id="daftarJadwal" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-3">
        @forelse ($jadwalHariIni as $jadwal)
            <a 
                href="{{ route('kursi', ['film' => $film->id, 'jadwal' => $jadwal->id]) }}"
                class="relative overflow-hidden group border border-[#14274E]/50 rounded-full py-2 text-center text-[#14274E] font-semibold text-sm transition hover:bg-[#14274E] hover:text-white hover:shadow-md">
                {{ date('H:i', strtotime($jadwal->jamtayang)) }}
                <span class="text-xs block text-gray-500 group-hover:text-white/80">
                    {{ $jadwal->studio->nama_studio }}
                </span>
            </a>
        @empty
            <div class="col-span-full text-center py-4">
                <span class="text-gray-400 text-sm">Belum ada jadwal untuk tanggal ini</span>
            </div>
        @endforelse
    </div>
</div>

      {{-- Tombol Kembali --}}
      <div class="pt-4 text-center">
        <a href="/film" 
           class="inline-block bg-[#14274E] text-white text-sm px-6 py-2 rounded-full font-medium hover:bg-[#1E3A8A] transition transform hover:scale-105 shadow-md hover:shadow-lg">
          Kembali ke Daftar Film
        </a>
      </div>
    </div>
  </main>

  {{-- Popup Trailer --}}
  <div id="trailerModal" 
       class="fixed inset-0 bg-black/70 backdrop-blur-sm flex items-center justify-center z-50 hidden cursor-pointer"
       onclick="tutupTrailer(event)">
    <div class="relative bg-white rounded-2xl overflow-hidden shadow-2xl w-[90%] max-w-2xl cursor-default"
         onclick="event.stopPropagation()">
      <video id="trailerVideo" class="w-full" controls>
        <source src="{{ asset('trailers/' . $film->trailer) }}" type="video/mp4">
        Browser Anda tidak mendukung pemutar video.
      </video>
      <button onclick="tutupTrailer(event)" 
              class="absolute top-2 right-2 bg-[#14274E]/80 hover:bg-[#14274E] text-white rounded-full p-2 shadow-lg transition cursor-pointer">
        <i data-lucide="x" class="w-5 h-5"></i>
      </button>
    </div>
  </div>

  @include('components.footer')

  <script>
    lucide.createIcons();

    // Ganti tanggal otomatis
    function ubahTanggal(tanggal) {
      const params = new URLSearchParams(window.location.search);
      params.set('tanggal', tanggal);
      window.location.search = params.toString();
    }

    // Popup trailer
    const modal = document.getElementById('trailerModal');
    const video = document.getElementById('trailerVideo');

    function bukaTrailer() {
      modal.classList.remove('hidden');
      video.play();
    }

    function tutupTrailer(event) {
      if (event.target === modal || event.target.closest('button')) {
        video.pause();
        video.currentTime = 0;
        modal.classList.add('hidden');
      }
    }
  </script>
</body>
</html>
