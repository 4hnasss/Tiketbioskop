<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna | Flixora</title>
    @vite('resources/css/app.css')
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-gradient-to-b from-[#e8f0ff] to-[#fdfdff] font-sans text-gray-800 min-h-screen">
    @include('components.navbar')

    <div class="max-w-5xl mx-auto mt-24 mb-24 bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row items-center justify-between px-10 py-10 bg-gradient-to-r from-[#4a90e2] to-[#357abd] text-white">
            <div class="flex items-center gap-5">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=ffffff&color=4a90e2&size=120"
                     alt="User Avatar" 
                     class="w-24 h-24 rounded-full border-4 border-white shadow-md">
                <div>
                    <h2 class="text-3xl font-bold">{{ $user->name }}</h2>
                    <p class="text-sm opacity-90">{{ $user->email }}</p>
                </div>
            </div>
            <div class="mt-6 sm:mt-0">
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-white text-[#4a90e2] font-semibold hover:bg-gray-100 transition">
                    <i data-lucide="home" class="w-5 h-5"></i> Beranda
                </a>
            </div>
        </div>

        <!-- Tabs -->
        <div class="flex justify-center border-b border-gray-200 bg-[#f9fbff]">
            <button class="px-8 py-4 text-[#4a90e2] border-b-2 border-[#4a90e2] font-semibold text-sm tracking-wide">
                Profil Pengguna
            </button>
        </div>

        <!-- Konten Profil -->
        <div class="p-10 grid md:grid-cols-2 gap-8">
            <!-- Kolom 1 -->
            <div class="space-y-5">
                <h3 class="text-lg font-semibold text-gray-700 mb-2">Informasi Akun</h3>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Nama Lengkap</label>
                    <input type="text" value="{{ $user->name }}" readonly
                        class="w-full bg-[#f9fbff] border border-gray-200 rounded-xl px-4 py-2.5 text-gray-700 shadow-sm focus:ring-2 focus:ring-[#4a90e2]">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Email</label>
                    <input type="email" value="{{ $user->email }}" readonly
                        class="w-full bg-[#f9fbff] border border-gray-200 rounded-xl px-4 py-2.5 text-gray-700 shadow-sm focus:ring-2 focus:ring-[#4a90e2]">
                </div>

                <div>
                    <label class="block text-sm text-gray-600 mb-1">Nomor HP</label>
                    <input type="text" value="{{ $user->nohp }}" readonly
                        class="w-full bg-[#f9fbff] border border-gray-200 rounded-xl px-4 py-2.5 text-gray-700 shadow-sm focus:ring-2 focus:ring-[#4a90e2]">
                </div>
            </div>

            <!-- Kolom 2 (opsional tambahan info atau tombol aksi lain) -->
            <div class="bg-[#f9fbff] border border-gray-100 rounded-2xl p-6 flex flex-col justify-between shadow-inner">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 mb-3">Aktivitas Akun</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">
                        Terakhir login: <span class="font-medium text-gray-800">{{ $user->last_login ?? 'Baru login' }}</span><br>
                        Tanggal bergabung: <span class="font-medium text-gray-800">{{ $user->created_at->format('d M Y') }}</span>
                    </p>
                </div>
                <div class="mt-6">
                    <a href="{{ route('transaksi.riwayat')}} " 
                       class="inline-flex items-center justify-center gap-2 w-full px-5 py-3 bg-[#4a90e2] text-white font-medium rounded-xl hover:bg-[#357abd] transition">
                        <i data-lucide="ticket" class="w-5 h-5"></i> Lihat Riwayat Pemesanan
                    </a>
                </div>
            </div>
        </div>

        <!-- Footer Tombol -->
        <div class="px-10 py-6 bg-[#f9fbff] border-t border-gray-100 flex justify-end">
            <a href="{{ route('home') }}" 
               class="px-6 py-2.5 rounded-lg bg-[#4a90e2] text-white font-medium hover:bg-[#357abd] transition flex items-center gap-2">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Beranda
            </a>
        </div>
    </div>

    @include('components.footer')
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
