<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran | TicketLy</title>
    @vite('resources/css/app.css')
</head>
<body class="bg-gradient-to-r from-white to-[#D6E4F0] min-h-screen">
    @include('components.navbar')

    <div class="flex justify-center py-12">
        <div class="bg-white/25 backdrop-blur-md rounded-2xl shadow-xl w-full max-w-3xl p-10 border border-white/30">
            
            <!-- Logo -->
            <div class="flex justify-center mb-2">
                <img src="img/Brand.png" alt="Logo" class="w-[80px]">
            </div>

            <!-- Judul -->
            <h1 class="text-2xl font-bold text-[#14274E] text-center mb-8">
                Detail Pembayaran
            </h1>

            <!-- Detail Transaksi -->
                <div class="flex justify-between px-6 py-2 border-b border-gray-200 font-bold mb-3">
                    <span>Jenis</span>
                    <span>Keterangan</span>
                </div>
                
                <div class="ml-10 mr-10">
                    <div class="flex justify-between px-6 py-[5px]">
                        <span>Film</span>
                        <span>Pengepungan di Bukit Duri</span>
                    </div>
                    <div class="flex justify-between px-6 py-[5px]">
                        <span>Jadwal</span>
                        <span>29-11-2025, 09.00</span>
                    </div>
                    <div class="flex justify-between px-6 py-[5px]">
                        <span>Studio</span>
                        <span>Studio 1</span>
                    </div>
                    <div class="flex justify-between px-6 py-[5px]">
                        <span>Kursi</span>
                        <span>A1, B2</span>
                    </div>
                    <div class="flex justify-between px-6 py-[5px] font-semibold">
                        <span>Total Harga</span>
                        <span>Rp 25.000</span>
                    </div>
                    <div class="flex justify-between px-6 py-[5px] font-semibold">
                        <span>Status</span>
                        <span id="status" class="text-red-600 font-medium">Pending</span>
                    </div>
                </div>
                    

            <!-- Metode Pembayaran -->
                <form id="formBayar" class="mt-6">
                    <div class="flex items-center justify-between gap-4 ml-15 mr-15">
                        <label class="text-[#14274E] font-semibold whitespace-nowrap">
                            Metode Pembayaran
                        </label>
                        <select 
                            name="metode" 
                            class="w-40 border border-gray-300 rounded-md px-3 py-2 text-gray-700 text-sm 
                                focus:outline-none focus:ring-2 focus:ring-[#14274E]"
                            required>
                            <option value="">Pilih</option>
                            <option value="QRIS">QRIS</option>
                            <option value="Transfer Bank">Transfer Bank</option>
                            <option value="E-Wallet">E-Wallet</option>
                        </select>
                    </div>


                <!-- Tombol Bayar -->
                <div class="text-center mt-8">
                    <button
                        type="submit"
                        class="bg-[#14274E] hover:bg-[#0F1E3B] text-white px-30 py-2.5 rounded-md font-semibold shadow-md transition cursor-pointer">
                        Bayar
                    </button>
                </div>
            </form>

            <!-- Notifikasi -->
            <div id="notif" class="hidden mt-8 text-center font-medium text-lg"></div>
        </div>
    </div>

    <script>
        const form = document.getElementById('formBayar');
        const notif = document.getElementById('notif');
        const statusEl = document.getElementById('status');

        form.addEventListener('submit', function (e) {
            e.preventDefault();
            const metode = form.metode.value;
            if (!metode) {
                alert('Pilih metode pembayaran terlebih dahulu!');
                return;
            }

            notif.textContent = '✅ Pembayaran dengan ' + metode + ' berhasil!';
            notif.classList.remove('hidden', 'text-red-600');
            notif.classList.add('text-green-600');
            statusEl.textContent = 'PAID';
            statusEl.classList.remove('text-red-600');
            statusEl.classList.add('text-green-600');
        });
    </script>

    @include('components.footer')
</body>
</html>
