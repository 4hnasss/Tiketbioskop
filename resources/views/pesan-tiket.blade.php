<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Tiket - Kasir</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 min-h-screen">
    <!-- Navbar -->
    @include('components.nav')

    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">Daftar Film</h2>
                <p class="text-gray-600">Pilih film yang ingin dipesan</p>
            </div>

            <!-- Search Inputs -->
            <div class="flex flex-col sm:flex-row gap-2">
                <input type="text" id="searchJudul" placeholder="Cari judul..." class="px-4 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring focus:ring-indigo-200">
                <input type="text" id="searchGenre" placeholder="Cari genre..." class="px-4 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring focus:ring-indigo-200">
                <input type="text" id="searchDurasi" placeholder="Durasi (menit)" class="px-4 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring focus:ring-indigo-200">
                <input type="text" id="searchHarga" placeholder="Harga (Rp)" class="px-4 py-2 border border-gray-300 rounded shadow-sm focus:outline-none focus:ring focus:ring-indigo-200">
            </div>
        </div>

        @if($films->count() > 0)
            <div class="overflow-x-auto bg-white shadow-md rounded-lg">
                <table class="min-w-full divide-y divide-gray-200" id="filmTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Poster</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Genre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Durasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
@foreach($films as $film)
<tr>
    <td class="px-6 py-4">
        @if($film->poster)
            <img src="{{ asset('img/' . $film->poster) }}" alt="{{ $film->judul }}" class="w-16 h-20 object-cover rounded">
        @else
            <div class="w-16 h-20 bg-gray-200 flex items-center justify-center text-gray-400 text-sm rounded">
                No Image
            </div>
        @endif
    </td>
    <td class="px-6 py-4 font-medium text-gray-800">{{ $film->judul }}</td>
    <td class="px-6 py-4 text-gray-600">{{ $film->genre }}</td>
    <td class="px-6 py-4 text-gray-600">{{ $film->durasi }} menit</td>
    
    {{-- Ambil harga dari relasi jadwal->harga --}}
    <td class="px-6 py-4 font-semibold text-indigo-600">
        @php
            $harga = $film->jadwal->first()->harga->harga ?? 20000;
        @endphp
        Rp {{ number_format($harga, 0, ',', '.') }}
    </td>

    <td class="px-6 py-4">
        <a href="{{ route('kasir.pilih-jadwal', $film->id) }}" class="text-white bg-indigo-600 px-4 py-2 rounded hover:bg-indigo-700 transition">Pilih Jadwal</a>
    </td>
</tr>
@endforeach

                    </tbody>
                </table>
            </div>
        @else
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-8 text-center mt-6">
                <svg class="w-16 h-16 text-yellow-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
                <p class="text-gray-600 text-lg">Tidak ada film yang sedang tayang</p>
            </div>
        @endif
    </div>

    <!-- Script pencarian multi-kolom -->
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
