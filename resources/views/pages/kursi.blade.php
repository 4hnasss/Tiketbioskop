<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pilih Kursi | TicketLy</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-b from-[#f7faff] to-[#dbe9ff] min-h-screen font-sans text-gray-800">

  @include('components.navbar')

  <!-- Header -->
  <div class="text-center mt-10">
    <h1 class="text-2xl md:text-3xl font-bold tracking-wide text-[#0A1D56]">{{ strtoupper($film->judul) }}</h1>
    <p class="text-[#1E56A0] text-sm mt-1">
      {{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d F Y') }}
      {{ \Carbon\Carbon::parse($jadwal->jamtayang)->format('H:i') }}
    </p>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 mt-10 px-6 lg:px-20">

    <!-- MAIN SEAT AREA -->
    <div class="lg:col-span-2 flex flex-col items-center">
      <div class="bg-white shadow-xl border border-gray-100 rounded-2xl p-8 w-full max-w-[750px] transition duration-300 hover:shadow-2xl">

        <!-- Layar -->
        <div class="text-center mb-8">
          <div class="inline-block bg-gradient-to-r from-[#A8C0FF] to-[#3f2b96] rounded-full h-2 w-48 mb-3"></div>
          <p class="text-sm text-gray-600 font-medium tracking-wide">LAYAR</p>
        </div>

        <!-- Kursi -->
        <div id="seatArea" class="flex flex-col gap-4 items-center font-semibold text-gray-700 text-sm"></div>

        <!-- Pilihan kursi -->
        <div id="selectedSeats" class="flex flex-wrap justify-center gap-2 text-sm text-gray-700 mt-6"></div>
      </div>

      <!-- Keterangan -->
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

<!-- SIDEBAR -->
<div class="lg:col-span-1 flex justify-center relative">
  <div class="bg-white shadow-lg border border-gray-100 w-full max-w-[320px] h-fit rounded-2xl p-6 
              sticky top-24 self-start hover:shadow-xl transition duration-300">
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

    <a href="#" id="buyButton"
      class="block text-center border-2 border-[#0A1D56] text-[#0A1D56] font-semibold py-2 rounded-full transition duration-300 hover:bg-[#0A1D56] hover:text-white opacity-50 cursor-not-allowed">
      Beli
    </a>
  </div>
</div>

  </div>


  <!-- Midtrans Script -->
  <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.clientKey') }}"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const seatArea = document.getElementById("seatArea");
    const selectedSeats = document.getElementById("selectedSeats");
    const seatSummary = document.getElementById("seatsSummary");
    const seatCount = document.getElementById("seatCount");
    const total = document.getElementById("total");
    const buyButton = document.getElementById("buyButton");

    const seatPrice = {{ $hargaPerKursi }};
    const snapToken = "{{ $snapToken ?? '' }}"; // Token dari controller

    // Data kursi (hilangkan duplikasi berdasarkan nomorkursi)
    const kursiDataRaw = @json($kursi);
    const kursiData = Object.values(
        kursiDataRaw.reduce((acc, k) => {
            acc[k.nomorkursi] = k;
            return acc;
        }, {})
    );

    let chosen = [];

    // Group kursi per baris (contoh: A1, A2, B1...)
    const grouped = {};
    kursiData.forEach(k => {
        const row = k.nomorkursi.charAt(0);
        if (!grouped[row]) grouped[row] = [];
        grouped[row].push(k);
    });

    // Render kursi ke tampilan
    Object.keys(grouped).forEach(row => {
        const rowDiv = document.createElement("div");
        rowDiv.className = "flex items-center gap-3";

        const labelLeft = document.createElement("span");
        labelLeft.className = "w-6 text-center";
        labelLeft.textContent = row;
        rowDiv.appendChild(labelLeft);

        const block = document.createElement("div");
        block.className = "flex gap-2";

        grouped[row].sort((a, b) => parseInt(a.nomorkursi.slice(1)) - parseInt(b.nomorkursi.slice(1)));
        const totalSeats = grouped[row].length;
        const midIndex = Math.ceil(totalSeats / 2);

        grouped[row].forEach((k, index) => {
            block.appendChild(makeSeat(k));
            if (index + 1 === midIndex) {
                const aisle = document.createElement("div");
                aisle.className = "w-8"; // jarak antar blok kursi
                block.appendChild(aisle);
            }
        });

        rowDiv.appendChild(block);

        const labelRight = document.createElement("span");
        labelRight.className = "w-6 text-center";
        labelRight.textContent = row;
        rowDiv.appendChild(labelRight);

        seatArea.appendChild(rowDiv);
    });

    // Fungsi membuat elemen kursi
    function makeSeat(kursi) {
        const seat = document.createElement("div");
        seat.className = "w-8 h-8 flex items-center justify-center text-white text-xs font-bold rounded border-2 cursor-pointer transition";
        seat.textContent = kursi.nomorkursi.replace(/[A-Z]/, "");

        switch (kursi.status) {
            case "terjual":
                seat.classList.add("bg-[#92A500]", "border-[#92A500]", "cursor-not-allowed");
                break;
            case "tidaktersedia":
                seat.classList.add("bg-[#A01E1E]", "border-[#A01E1E]", "cursor-not-allowed");
                break;
            case "dipesan":
                seat.classList.add("bg-[#1E56A0]", "border-[#1E56A0]", "cursor-not-allowed");
                break;
            default:
                seat.classList.add("bg-[#14274E]", "border-[#14274E]", "hover:opacity-80");
                seat.addEventListener("click", () => toggleSeat(kursi.nomorkursi, seat));
        }
        return seat;
    }

    // Fungsi toggle (pilih / batal pilih kursi)
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

    // Update tampilan ringkasan kursi & total harga
    function updateSummary() {
        if (chosen.length === 0) {
            selectedSeats.innerHTML = "Belum ada kursi yang dipilih";
            seatSummary.innerHTML = "<span class='text-gray-500'>-</span>";
            buyButton.disabled = true;
            buyButton.classList.add("opacity-50", "cursor-not-allowed");
        } else {
            selectedSeats.innerHTML = chosen
                .map(c => `<span class='px-2 py-1 bg-blue-100 text-blue-700 rounded text-sm'>${c}</span>`)
                .join(" ");
            seatSummary.innerHTML = chosen.join(", ");
            buyButton.disabled = false;
            buyButton.classList.remove("opacity-50", "cursor-not-allowed");
        }

        seatCount.textContent = chosen.length;
        const totalHarga = chosen.length * seatPrice;
        total.textContent = totalHarga.toLocaleString("id-ID");
    }

    // ======= MIDTRANS SNAP BUTTON =======
    buyButton.addEventListener("click", function (e) {
        e.preventDefault();

        if (chosen.length === 0) {
            alert("Silakan pilih kursi terlebih dahulu!");
            return;
        }

        if (!snapToken) {
            alert("Token pembayaran tidak tersedia.");
            return;
        }

        // Jalankan popup pembayaran Midtrans
        snap.pay(snapToken, {
            onSuccess: function(result) {
                alert("Pembayaran sukses!");
                console.log(result);
                window.location.href = "/transaksi";
            },
            onPending: function(result) {
                alert("Menunggu pembayaran...");
                console.log(result);
            },
            onError: function(result) {
                alert("Terjadi kesalahan saat pembayaran!");
                console.error(result);
            },
            onClose: function() {
                alert("Kamu menutup jendela pembayaran.");
            }
        });
    });
});
</script>


  @include('components.footer')
</body>
</html>
