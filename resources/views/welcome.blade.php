<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flixora | Home</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-b from-white to-blue-100 min-h-screen flex flex-col">

    {{-- Navbar --}}
    <nav class="bg-white shadow-md">
        <div class="container mx-auto flex items-center justify-between py-3 px-4">
            {{-- Logo --}}
            <div class="flex items-center space-x-2">
                <img src="/images/logo.png" alt="Flixora" class="h-8">
                <span class="text-xl font-bold font-serif">Flixora</span>
            </div>

            {{-- Search --}}
            <div class="flex-1 px-6">
                <input type="text" placeholder="Cari Film"
                    class="w-full md:w-96 border rounded-full px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            {{-- Auth Buttons --}}
            <div class="flex space-x-3">
                <a href="/" class="text-gray-600 hover:text-blue-600">Login</a>
                <a href="#" class="px-4 py-2 border border-blue-500 rounded-full text-blue-600 hover:bg-blue-500 hover:text-white transition">
                    Register
                </a>
            </div>
        </div>
    </nav>

    {{-- Content --}}
    <main class="flex-1 container mx-auto px-4 mt-6">

        {{-- Lagi Tayang --}}
        <section class="mb-10">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold">Lagi tayang</h2>
                <a href="#" class="text-blue-600 flex items-center">Lihat Semua <span class="ml-1">➜</span></a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-6">
                @foreach (range(1,10) as $i)
                    <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
                        <img src="/images/film{{ $i }}.jpg" alt="Film" class="w-full h-72 object-cover">
                        <div class="p-3">
                            <h3 class="font-bold text-sm uppercase">Judul Film {{ $i }}</h3>
                            <p class="text-xs text-gray-500">1h 39m • Drama Komedi</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

        {{-- Akan Tayang --}}
        <section>
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-bold">Akan tayang</h2>
                <a href="#" class="text-blue-600 flex items-center">Lihat Semua <span class="ml-1">➜</span></a>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-6">
                @foreach (range(1,24) as $i)
                    <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
                        <img src="/images/film{{ $i }}.jpg" alt="Film" class="w-full h-60 object-cover">
                        <div class="p-3">
                            <p class="text-xs text-blue-600">Tayang: September 20</p>
                            <h3 class="font-semibold text-sm">Judul Film {{ $i }}</h3>
                            <p class="text-xs text-gray-500">1h 39m</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>

    </main>

    {{-- Footer --}}
    <footer class="bg-white shadow-inner py-6 mt-10">
        <div class="container mx-auto flex flex-col items-center space-y-4">
            {{-- Logo --}}
            <img src="/images/logo.png" alt="Flixora" class="h-10">

            {{-- Copyright --}}
            <p class="text-sm text-gray-500">
                Copyrights © 2007-2025 <span class="font-semibold">Flixora</span>. All rights reserved.
            </p>

            {{-- Sosmed --}}
            <div class="flex space-x-4 text-gray-700">
                <a href="#"><i class="fab fa-facebook"></i></a>
                <a href="#"><i class="fab fa-tiktok"></i></a>
                <a href="#"><i class="fab fa-x-twitter"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
            </div>
        </div>
    </footer>

    @vite('resources/js/app.js')
</body>
</html>
