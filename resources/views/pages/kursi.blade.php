<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pilih Kursi | TicketLy</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-b from-[#f7faff] to-[#dbe9ff] min-h-screen font-sans text-gray-800">
    
@include('components.navbar')

<div class="text-center mt-10">
    <h1 class="text-2xl md:text-3xl font-bold tracking-wide text-[#0A1D56]">{{ strtoupper($film->judul) }}</h1>
    <p class="text-[#1E56A0] text-sm mt-1">
        {{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d F Y') }} | 
        {{ \Carbon\Carbon::parse($jadwal->jamtayang)->format('H:i') }}
    </p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-10 mt-10 px-6 lg:px-20">
    {{-- AREA KURSI --}}
    <div class="lg:col-span-2 flex flex-col items-center">
        <div class="bg-white shadow-xl border border-gray-100 rounded-2xl p-8 w-full max-w-[750px] transition duration-300 hover:shadow-2xl">
            
            {{-- LAYAR --}}
            <div class="text-center mb-8">
                <div class="inline-block bg-gradient-to-r from-[#A8C0FF] to-[#3f2b96] rounded-full h-2 w-48 mb-3"></div>
                <p class="text-sm text-gray-600 font-medium tracking-wide">LAYAR</p>
            </div>

            {{-- KURSI --}}
            <div id="seatArea" class="flex flex-col gap-4 items-center font-semibold text-gray-700 text-sm"></div>

            {{-- SEAT PILIHAN --}}
            <div id="selectedSeats" class="flex flex-wrap justify-center gap-2 text-sm text-gray-700 mt-6"></div>
        </div>

        {{-- LEGEND --}}
        <div class="grid grid-cols-2 sm:grid-cols-2 gap-x-10 gap-y-3 justify-items-start mt-6 text-xs">
            <div class="flex items-center gap-2">
                <div class="w-5 h-5 bg-[#14274E] rounded border border-[#14274E]"></div>
                <span class="text-gray-700">Tersedia</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-5 h-5 bg-[#1E56A0] rounded border border-[#1E56A0]"></div>
                <span class="text-gray-700">Dipesan</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-5 h-5 bg-[#A01E1E] rounded border border-[#A01E1E]"></div>
                <span class="text-gray-700">Tidak Tersedia</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-5 h-5 bg-[#92A500] rounded border border-[#92A500]"></div>
                <span class="text-gray-700">Terjual</span>
            </div>
        </div>
    </div>

    {{-- RINGKASAN PESANAN --}}
    <div class="lg:col-span-1 flex justify-center relative">
        <div class="bg-white shadow-lg border border-gray-100 w-full max-w-[320px] h-fit rounded-2xl p-6 sticky top-24 hover:shadow-xl transition duration-300">
            <h3 class="text-lg font-bold text-[#0A1D56] mb-4 text-center">Ringkasan Pesanan</h3>
            <div class="border-b border-gray-200 pb-4 mb-4">
                <p class="text-sm font-semibold text-gray-800 mb-1">Kursi</p>
                <div id="seatsSummary" class="text-sm text-gray-600 min-h-6 mb-2">
                    <span class="text-gray-500">-</span>
                </div>
                <div class="text-xs text-gray-600">
                    <span id="seatCount">0</span> kursi × Rp <span id="pricePerSeat">{{ $hargaPerKursi }}</span>
                </div>
            </div>
            <div class="flex justify-between items-center mb-6">
                <span class="font-semibold text-gray-900">Total</span>
                <span class="text-2xl font-bold text-[#0A1D56]">Rp <span id="total">0</span></span>
            </div>
            <a href="#" id="buyButton" class="block text-center border-2 border-[#0A1D56] text-[#0A1D56] font-semibold py-2 rounded-full opacity-50 cursor-not-allowed">
                Bayar Sekarang
            </a>
        </div>
    </div>
</div>

@include('components.footer')

<script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const seatArea = document.getElementById("seatArea");
    const selectedSeats = document.getElementById("selectedSeats");
    const seatSummary = document.getElementById("seatsSummary");
    const seatCount = document.getElementById("seatCount");
    const total = document.getElementById("total");
    const buyButton = document.getElementById("buyButton");
    
    const seatPrice = {{ $hargaPerKursi }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const kursiData = @json($kursi);
    let chosen = [];
    let currentTransaksiId = null;

    // 🔹 Kelompokkan kursi per baris
    const grouped = {};
    kursiData.forEach(k => {
        const row = k.nomorkursi.charAt(0);
        if (!grouped[row]) grouped[row] = [];
        grouped[row].push(k);
    });

    // 🔹 Render kursi per baris
    Object.keys(grouped).forEach(row => {
        const rowDiv = document.createElement("div");
        rowDiv.className = "flex items-center gap-3 mb-2";
        
        const labelLeft = document.createElement("span");
        labelLeft.className = "w-6 text-center font-semibold";
        labelLeft.textContent = row;
        rowDiv.appendChild(labelLeft);
        
        const block = document.createElement("div");
        block.className = "flex gap-2";
        
        grouped[row].sort((a,b) => parseInt(a.nomorkursi.slice(1)) - parseInt(b.nomorkursi.slice(1)));
        
        const midIndex = Math.ceil(grouped[row].length / 2);
        
        grouped[row].forEach((k, index) => {
            block.appendChild(makeSeat(k));
            if (index + 1 === midIndex) {
                const aisle = document.createElement("div");
                aisle.className = "w-8";
                block.appendChild(aisle);
            }
        });
        
        rowDiv.appendChild(block);
        
        const labelRight = labelLeft.cloneNode(true);
        rowDiv.appendChild(labelRight);
        
        seatArea.appendChild(rowDiv);
    });

    // 🔹 Fungsi buat kursi
    function makeSeat(k) {
        const seat = document.createElement("div");
        seat.dataset.seat = k.nomorkursi;
        seat.className = "w-8 h-8 flex items-center justify-center text-white text-xs font-bold rounded border-2 cursor-pointer transition-all duration-150 shadow-sm";
        seat.textContent = k.nomorkursi.replace(/[A-Z]/, "");
        
        switch (k.status) {
            case 'terjual':
                seat.classList.add("bg-[#92A500]", "border-[#92A500]", "cursor-not-allowed", "opacity-70");
                break;
            case 'tidaktersedia':
                seat.classList.add("bg-[#A01E1E]", "border-[#A01E1E]", "cursor-not-allowed", "opacity-70");
                break;
            case 'dipesan':
                seat.classList.add("bg-[#1E56A0]", "border-[#1E56A0]", "cursor-not-allowed", "opacity-70");
                break;
            default:
                seat.classList.add("bg-[#14274E]", "border-[#14274E]", "hover:scale-105", "hover:opacity-80");
                seat.addEventListener("click", () => toggleSeat(k.nomorkursi, seat));
                break;
        }
        return seat;
    }

    // 🔹 Toggle kursi dipilih
    function toggleSeat(code, el) {
        const index = chosen.indexOf(code);
        if (index !== -1) {
            chosen.splice(index, 1);
            el.classList.remove("bg-blue-600", "border-blue-600");
            el.classList.add("bg-[#14274E]", "border-[#14274E]");
        } else {
            chosen.push(code);
            el.classList.add("bg-blue-600", "border-blue-600");
            el.classList.remove("bg-[#14274E]", "border-[#14274E]");
        }
        updateSummary();
    }

    // 🔹 Update ringkasan kursi & total
    function updateSummary() {
        if (chosen.length === 0) {
            selectedSeats.innerHTML = "Belum ada kursi yang dipilih";
            seatSummary.innerHTML = "<span class='text-gray-500'>-</span>";
            buyButton.classList.add("opacity-50", "cursor-not-allowed");
        } else {
            selectedSeats.innerHTML = chosen.map(c => 
                `<span class='px-2 py-1 bg-blue-100 text-blue-700 rounded text-sm'>${c}</span>`
            ).join(" ");
            seatSummary.innerHTML = chosen.join(", ");
            buyButton.classList.remove("opacity-50", "cursor-not-allowed");
        }
        seatCount.textContent = chosen.length;
        total.textContent = (chosen.length * seatPrice).toLocaleString("id-ID");
    }

    // 🔹 Tombol bayar
    buyButton.addEventListener("click", async function(e) {
        e.preventDefault();
        if (chosen.length === 0) return showError("Silakan pilih kursi terlebih dahulu!");

        try {
            const res = await fetch("/buat-pembayaran", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken
                },
                body: JSON.stringify({
                    kursi: chosen,
                    hargaPerKursi: seatPrice,
                    jadwal_id: {{ $jadwal->id }}
                })
            });

            const data = await res.json();
            
            if (!data.snapToken) return showError("Gagal membuat pembayaran.");

            // 🔹 Simpan transaksi ID
            currentTransaksiId = data.transaksiId;

            // 🔹 Jalankan Midtrans Snap popup
            snap.pay(data.snapToken, {
                onSuccess: function(result) {
                    console.log('✅ Payment SUCCESS:', result);
                    console.log('📊 Transaction status:', result.transaction_status);
                    console.log('💳 Payment type:', result.payment_type);
                    
                    // 🔹 Pastikan status adalah 'settlement' atau 'capture' untuk sukses
                    const finalStatus = result.transaction_status === 'capture' ? 'settlement' : result.transaction_status;
                    
                    // 🔹 Update status ke selesai SEBELUM redirect
                    updateStatus(currentTransaksiId, finalStatus, result.payment_type)
                        .then((response) => {
                            console.log('✅ Status updated successfully:', response);
                            
                            // 🔹 Tunggu sebentar untuk pastikan database commit
                            setTimeout(() => {
                                window.location.href = `/transaksi?status=success&id=${currentTransaksiId}`;
                            }, 500);
                        })
                        .catch(err => {
                            console.error('❌ Error updating status:', err);
                            // Tetap redirect meskipun update gagal
                            window.location.href = `/transaksi?status=success&id=${currentTransaksiId}`;
                        });
                },
                onPending: function(result) {
                    console.log('⏳ Payment PENDING:', result);
                    console.log('📊 Transaction status:', result.transaction_status);
                    console.log('💳 Payment type:', result.payment_type);
                    
                    // 🔹 Untuk pending, TIDAK perlu update karena default sudah pending
                    // Langsung redirect saja
                    console.log('⏳ Status tetap PENDING, redirecting...');
                    
                    setTimeout(() => {
                        window.location.href = `/transaksi?status=pending&id=${currentTransaksiId}`;
                    }, 500);
                },
                onError: function(result) {
                    console.error('❌ Payment ERROR:', result);
                    console.log('📊 Error details:', result);
                    showError("Terjadi kesalahan saat pembayaran.");
                },
                onClose: function() {
                    // 🔹 User menutup popup - cek status transaksi
                    console.log('🔒 Popup ditutup, memeriksa status transaksi...');
                    
                    // Tunggu sebentar untuk memastikan callback dari Midtrans selesai
                    setTimeout(() => {
                        // Cek status terbaru dari database
                        checkTransactionStatus(currentTransaksiId);
                    }, 1500);
                }
            });
        } catch (err) {
            console.error('❌ Error:', err);
            showError("Gagal membuat pembayaran.");
        }
    });

    // 🔹 Fungsi cek status transaksi (via backend)
    function checkTransactionStatus(transaksiId) {
        console.log('🔍 Checking transaction status for ID:', transaksiId);
        
        fetch(`/transaksi/${transaksiId}/cek-status`, {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            console.log('📊 Current status from DB:', data);
            
            // 🔹 Redirect berdasarkan status terkini
            if (data.status === 'settlement' || data.status === 'capture' || data.status === 'selesai') {
                // Pembayaran sudah selesai
                console.log('✅ Payment completed, redirecting to success page...');
                window.location.href = `/transaksi?status=success&id=${transaksiId}`;
            } else if (data.status === 'pending') {
                // Masih pending - user belum bayar atau sedang proses
                console.log('⏳ Payment still pending, redirecting...');
                window.location.href = `/transaksi?status=pending&id=${transaksiId}`;
            } else {
                // Status lain (batal, expire, dll)
                console.log('ℹ️ Payment closed without completion');
                window.location.href = `/transaksi?status=closed&id=${transaksiId}`;
            }
        })
        .catch(err => {
            console.error('❌ Error checking status:', err);
            // Default redirect ke transaksi jika gagal cek status
            window.location.href = '/transaksi';
        });
    }

    // 🔹 Fungsi update status transaksi
    function updateStatus(transaksiId, status, metode) {
        console.log('🔄 Updating status...', {transaksiId, status, metode});
        
        return fetch(`/transaksi/${transaksiId}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                status: status,
                metode_pembayaran: metode || 'unknown'
            })
        })
        .then(response => {
            console.log('📡 Response status:', response.status);
            return response.json();
        })
        .then(result => {
            console.log('✅ Update result:', result);
            if (!result.success) {
                throw new Error(result.message || 'Update failed');
            }
            return result;
        })
        .catch(error => {
            console.error('❌ Error in updateStatus:', error);
            throw error;
        });
    }

    // 🔹 Notifikasi error kecil (toast)
    function showError(message) {
        const errAlert = document.createElement('div');
        errAlert.className = 'fixed top-6 right-6 bg-red-500 text-white px-4 py-2 rounded-xl shadow-lg transition-all duration-300 z-50';
        errAlert.textContent = message;
        document.body.appendChild(errAlert);
        setTimeout(() => errAlert.remove(), 2500);
    }
});
</script>

</body>
</html>