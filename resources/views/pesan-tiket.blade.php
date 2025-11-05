{{-- resources/views/kasir/pesan-tiket.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Tiket - Kasir</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(to right, #ffffff, #D6E4F0);
        }
    </style>
</head>
<body class="min-h-screen">
    @include('components.nav')

    <div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-3xl font-bold text-[#14274E]">
                    <i class="fas fa-ticket-alt mr-3 text-[#1E56A0]"></i>Daftar Film
                </h2>
                <p class="text-[#14274E]/70 mt-2">Pilih film yang ingin dipesan</p>
            </div>

            <!-- Search Inputs -->
            <div class="flex flex-col sm:flex-row gap-3">
                <input type="text" id="searchJudul" placeholder="Cari judul..." 
                       class="px-4 py-3 border-2 border-[#D6E4F0] rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#1E56A0] focus:border-transparent bg-white/80 backdrop-blur text-[#14274E] font-medium">
                <input type="text" id="searchGenre" placeholder="Cari genre..." 
                       class="px-4 py-3 border-2 border-[#D6E4F0] rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#1E56A0] focus:border-transparent bg-white/80 backdrop-blur text-[#14274E] font-medium">
                <input type="text" id="searchDurasi" placeholder="Durasi (menit)" 
                       class="px-4 py-3 border-2 border-[#D6E4F0] rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#1E56A0] focus:border-transparent bg-white/80 backdrop-blur text-[#14274E] font-medium">
                <input type="text" id="searchHarga" placeholder="Harga (Rp)" 
                       class="px-4 py-3 border-2 border-[#D6E4F0] rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-[#1E56A0] focus:border-transparent bg-white/80 backdrop-blur text-[#14274E] font-medium">
            </div>
        </div>

        @if($films->count() > 0)
            <div class="overflow-x-auto bg-white/80 backdrop-blur-md shadow-xl rounded-2xl border-2 border-[#D6E4F0]">
                <table class="min-w-full" id="filmTable">
                    <thead class="bg-[#D6E4F0]">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider">Poster</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider">Judul</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider">Genre</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider">Durasi</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider">Harga</th>
                            <th class="px-6 py-4 text-left text-xs font-bold text-[#14274E] uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#D6E4F0]">
                        @foreach($films as $film)
                        <tr class="hover:bg-[#D6E4F0]/50 transition">
                            <td class="px-6 py-4">
                                @if($film->poster)
                                    <img src="{{ asset('img/' . $film->poster) }}" alt="{{ $film->judul }}" class="w-16 h-24 object-cover rounded-lg shadow-md">
                                @else
                                    <div class="w-16 h-24 bg-[#D6E4F0] flex items-center justify-center text-[#14274E]/50 text-xs rounded-lg shadow-md">
                                        No Image
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 font-bold text-[#14274E]">{{ $film->judul }}</td>
                            <td class="px-6 py-4 text-[#14274E]/70 font-medium">{{ $film->genre }}</td>
                            <td class="px-6 py-4 text-[#14274E]/70 font-medium">{{ $film->durasi }} menit</td>
                            <td class="px-6 py-4 font-bold text-[#1E56A0]">
                                @php
                                    $harga = $film->jadwal->first()->harga->harga ?? 20000;
                                @endphp
                                Rp {{ number_format($harga, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('kasir.pilih-jadwal', $film->id) }}" 
                                   class="inline-block text-white bg-gradient-to-r from-[#1E56A0] to-[#14274E] px-5 py-2.5 rounded-xl hover:scale-105 transition transform shadow-md font-semibold">
                                    Pilih Jadwal
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-yellow-50 border-2 border-yellow-200 rounded-2xl p-12 text-center shadow-xl">
                <svg class="w-20 h-20 text-yellow-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <p class="text-[#14274E] text-lg font-semibold">Tidak ada film yang sedang tayang</p>
            </div>
        @endif
    </div>

    @include('components.footer')

    <script>
        const searchJudul = document.getElementById('searchJudul');
        const searchGenre = document.getElementById('searchGenre');
        const searchDurasi = document.getElementById('searchDurasi');
        const searchHarga = document.getElementById('searchHarga');
        const rows = document.querySelectorAll('#filmTable tbody tr');

        function filterTable() {
            const judul = searchJudul.value.toLowerCase();
            const genre = searchGenre.value.toLowerCase();
            const durasi = searchDurasi.value.toLowerCase();
            const harga = searchHarga.value.toLowerCase();

            rows.forEach(row => {
                const rowJudul = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const rowGenre = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const rowDurasi = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
                const rowHarga = row.querySelector('td:nth-child(5)').textContent.toLowerCase();

                if(rowJudul.includes(judul) && rowGenre.includes(genre) && rowDurasi.includes(durasi) && rowHarga.includes(harga)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        searchJudul.addEventListener('keyup', filterTable);
        searchGenre.addEventListener('keyup', filterTable);
        searchDurasi.addEventListener('keyup', filterTable);
        searchHarga.addEventListener('keyup', filterTable);
    </script>
</body>
</html>