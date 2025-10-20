<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya | TicketLy</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-r from-white to-[#D6E4F0] min-h-screen">
    @include('components.navbar')

    <div class="flex justify-center py-12 px-4">
        <div class="w-full max-w-4xl space-y-10">

            <!-- ========== SECTION: INFORMASI PROFIL ========== -->
            <section class="bg-white/30 backdrop-blur-md rounded-2xl shadow-xl p-10 border border-white/40">
                <div class="text-center mb-8">
                    <img src="{{ asset('img/user-icon.png') }}" alt="User Icon"
                        class="w-20 h-20 rounded-full mx-auto mb-3 border-2 border-[#14274E] shadow">
                    <h1 class="text-2xl font-bold text-[#14274E] tracking-wide">Informasi Profil</h1>
                    <p class="text-gray-600">User</p>
                </div>

                <!-- Notifikasi -->
                <div id="notif" class="hidden mb-4 text-center text-green-600 font-medium">
                    ✅ Profil berhasil diperbarui!
                </div>

                <!-- Form Profil -->
                <form id="profileForm" class="space-y-5">
                    <div>
                        <label class="block text-[#14274E] font-semibold mb-1">Nama Lengkap</label>
                        <input type="text" id="name" value="Alburhani Suhani"
                            class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#14274E]">
                    </div>

                    <div>
                        <label class="block text-[#14274E] font-semibold mb-1">Email</label>
                        <input type="email" id="email" value="alburhani@example.com"
                            class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#14274E]">
                    </div>

                    <div>
                        <label class="block text-[#14274E] font-semibold mb-1">Nomor HP</label>
                        <input type="text" id="nohp" value="081234567890"
                            class="w-full border border-gray-300 rounded-md px-4 py-2 focus:outline-none focus:ring-2 focus:ring-[#14274E]">
                    </div>

                    <div class="text-center mt-8">
                        <button type="button" id="saveBtn"
                            class="bg-gradient-to-r from-[#14274E] to-[#394867] hover:from-[#0F1E3B] hover:to-[#26324D] text-white px-10 py-2.5 rounded-full font-semibold shadow-md hover:shadow-lg transition-all duration-300">
                            💾 Simpan Perubahan
                        </button>
                    </div>
                </form>
            </section>

            <!-- PEMBATAS VISUAL -->
            <div class="flex justify-center">
                <div class="h-[2px] w-1/3 bg-gradient-to-r from-transparent via-[#14274E]/40 to-transparent rounded-full"></div>
            </div>

            <!-- ========== SECTION: RIWAYAT PEMESANAN ========== -->
            <section class="bg-white/30 backdrop-blur-md rounded-2xl shadow-xl p-10 border border-white/40">
                <h2 class="text-xl font-bold text-[#14274E] mb-6 flex items-center gap-2">
                    🎟️ Riwayat Pemesanan
                </h2>

                <!-- Jika belum ada pesanan -->
                <!-- <p class="text-gray-600 italic">Belum ada riwayat pemesanan.</p> -->

                <!-- Daftar tiket -->
                <div class="space-y-4">
                    <!-- Tiket 1 -->
                    <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 shadow border border-white/30 hover:shadow-md transition">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-semibold text-[#14274E] text-lg">Pengepungan di Bukit Duri</h3>
                                <p class="text-gray-700 text-sm">Studio 1 • 20 Okt 2025, 19:30</p>
                                <p class="text-gray-500 text-sm">Kursi: C5, C6</p>
                            </div>
                            <div class="text-right">
                                <span class="text-green-600 font-semibold">PAID</span>
                                <p class="text-sm font-medium text-[#14274E]">Rp 70.000</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tiket 2 -->
                    <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 shadow border border-white/30 hover:shadow-md transition">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-semibold text-[#14274E] text-lg">Legenda Laut Selatan</h3>
                                <p class="text-gray-700 text-sm">Studio 2 • 15 Okt 2025, 21:00</p>
                                <p class="text-gray-500 text-sm">Kursi: B3, B4</p>
                            </div>
                            <div class="text-right">
                                <span class="text-red-600 font-semibold">PENDING</span>
                                <p class="text-sm font-medium text-[#14274E]">Rp 50.000</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tiket 3 -->
                    <div class="bg-white/60 backdrop-blur-sm rounded-xl p-5 shadow border border-white/30 hover:shadow-md transition">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="font-semibold text-[#14274E] text-lg">Operasi Matahari Hitam</h3>
                                <p class="text-gray-700 text-sm">Studio 3 • 10 Okt 2025, 18:00</p>
                                <p class="text-gray-500 text-sm">Kursi: A1</p>
                            </div>
                            <div class="text-right">
                                <span class="text-green-600 font-semibold">PAID</span>
                                <p class="text-sm font-medium text-[#14274E]">Rp 35.000</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

        </div>
    </div>

    @include('components.footer')

    <script>
        // Simulasi tombol "Simpan"
        document.getElementById('saveBtn').addEventListener('click', function() {
            const notif = document.getElementById('notif');
            notif.classList.remove('hidden');
            notif.textContent = "✅ Data profil berhasil disimpan (simulasi).";
            setTimeout(() => notif.classList.add('hidden'), 3000);
        });
    </script>
</body>
</html>
