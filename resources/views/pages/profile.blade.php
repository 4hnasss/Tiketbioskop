<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Pengguna | Flixora</title>
    @vite('resources/css/app.css')
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-[#f4f7ff] font-sans text-gray-800">
    @include('components.navbar')

    <div class="max-w-5xl mx-auto mt-16 mb-20 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Header -->
        <div class="flex items-center justify-between px-10 py-8 border-b border-gray-200">
            <div class="flex items-center gap-5">
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=4a90e2&color=fff&size=100" 
                     alt="User Avatar" 
                     class="w-20 h-20 rounded-full shadow-sm">
                <div>
                    <h2 class="text-2xl font-semibold text-gray-800">{{ $user->name }}</h2>
                    <p class="text-gray-500 text-sm">{{ $user->email }}</p>
                </div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="flex border-b border-gray-200 text-sm font-medium text-gray-500">
            <button class="px-6 py-3 text-[#4a90e2] border-b-2 border-[#4a90e2] font-semibold">Profil</button>
        </div>

        <!-- Konten Profil -->
        <div class="p-10 space-y-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi Akun</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Nama Lengkap</label>
                        <input type="text" value="{{ $user->name }}" readonly
                            class="w-full bg-[#f9fbff] border border-gray-200 rounded-lg px-4 py-2.5 text-gray-700">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Email</label>
                        <input type="email" value="{{ $user->email }}" readonly
                            class="w-full bg-[#f9fbff] border border-gray-200 rounded-lg px-4 py-2.5 text-gray-700">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Nomor HP</label>
                        <input type="text" value="{{ $user->nohp }}" readonly
                            class="w-full bg-[#f9fbff] border border-gray-200 rounded-lg px-4 py-2.5 text-gray-700">
                    </div>

                    <div>
                        <label class="block text-sm text-gray-600 mb-1">Password</label>
                        <input type="password" value="********" readonly
                            class="w-full bg-[#f9fbff] border border-gray-200 rounded-lg px-4 py-2.5 text-gray-700">
                        <a href="{{ route('ubah-password') }}" 
                           class="text-[#4a90e2] text-sm mt-1 inline-block hover:underline font-medium">
                            Ubah Password
                        </a>
                    </div>
                </div>
            </div>

            <!-- Tombol -->
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('home') }}" 
                   class="px-5 py-2.5 rounded-lg bg-[#4a90e2] text-white font-medium hover:bg-[#357abd] transition">
                    Kembali Ke Beranda
                </a>
            </div>
        </div>
    </div>

    @include('components.footer')
    <script>
        lucide.createIcons();
    </script>
</body>
</html>
