<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ubah Password | Flixora</title>
    @vite('resources/css/app.css')
    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-[#f4f7ff] font-sans text-gray-800">
    @include('components.navbar')

    <div class="max-w-3xl mx-auto mt-20 mb-20 bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <!-- Header -->
        <div class="px-10 py-8 border-b border-gray-200">
            <h2 class="text-2xl font-semibold text-gray-800">Ubah Password</h2>
            <p class="text-gray-500 text-sm mt-1">Pastikan password baru Anda aman dan mudah diingat.</p>
        </div>

        <!-- Form -->
        <form action="{{ route('update-password') }}" method="POST" class="p-10 space-y-6">
            @csrf

            @if ($errors->any())
                <div class="bg-red-100 text-red-700 px-4 py-2 rounded-lg text-sm">
                    <ul class="list-disc ml-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('success'))
                <div class="bg-green-100 text-green-700 px-4 py-2 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div>
                <label class="block text-sm text-gray-600 mb-1">Password Saat Ini</label>
                <input type="password" name="current_password" required
                    class="w-full bg-[#f9fbff] border border-gray-200 rounded-lg px-4 py-2.5 text-gray-700 focus:ring-2 focus:ring-[#4a90e2] outline-none">
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Password Baru</label>
                <input type="password" name="new_password" required
                    class="w-full bg-[#f9fbff] border border-gray-200 rounded-lg px-4 py-2.5 text-gray-700 focus:ring-2 focus:ring-[#4a90e2] outline-none">
                <p class="text-xs text-gray-400 mt-1">Minimal 8 karakter.</p>
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Konfirmasi Password Baru</label>
                <input type="password" name="new_password_confirmation" required
                    class="w-full bg-[#f9fbff] border border-gray-200 rounded-lg px-4 py-2.5 text-gray-700 focus:ring-2 focus:ring-[#4a90e2] outline-none">
            </div>

            <!-- Tombol -->
            <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('profile') }}" 
                   class="px-5 py-2.5 rounded-lg bg-gray-100 text-gray-600 font-medium hover:bg-gray-200 transition">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2.5 rounded-lg bg-[#4a90e2] text-white font-medium hover:bg-[#357abd] transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    @include('components.footer')

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
