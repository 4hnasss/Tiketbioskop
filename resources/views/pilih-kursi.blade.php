<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Pilih Kursi | Kasir - Flixora</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-b from-[#f7faff] to-[#dbe9ff] min-h-screen font-sans text-gray-800">
@include('components.nav')

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
      <div class="bg-white shadow-xl border border-gray-100 rounded-2xl p-8 w-full max-w-[850px] transition duration-300 hover:shadow-2xl">
        <div class="text-center mb-8">
          <div class="inline-block bg-gradient-to-r from-[#A8C0FF] to-[#3f2b96] rounded-full h-2 w-64 mb-3"></div>
          <p class="text-sm text-gray-600 font-medium tracking-wide">LAYAR</p>
        </div>

        <div id="seatArea" class="flex flex-col gap-4 items-center font-semibold text-gray-700 text-sm"></div>
        
        <div id="selectedSeats" class="flex flex-wrap justify-center gap-2 text-sm text-gray-700 mt-6 min-h-8">
          <span class="text-gray-500">Belum ada kursi yang dipilih</span>
        </div>
      </div>

      {{-- LEGEND --}}
      <div class="grid grid-cols-2 gap-x-8 gap-y-3 justify-items-start mt-6 text-xs">
        <div class="flex items-center gap-2">
          <div class="w-6 h-6 bg-blue-600 rounded border-2 border-blue-700 shadow-sm"></div>
          <span>Tersedia</span>
        </div>
        <div class="flex items-center gap-2">
          <div class="w-6 h-6 bg-green-500 rounded border-2 border-green-600 shadow-sm"></div>
          <span>Dipilih</span>
        </div>
        <div class="flex items-center gap-2">
          <div class="w-6 h-6 bg-orange-500 rounded border-2 border-orange-600 shadow-sm"></div>
          <span>Dipesan</span>
        </div>
        <div class="flex items-center gap-2">
          <div class="w-6 h-6 bg-red-500 rounded border-2 border-red-600 shadow-sm"></div>
          <span>Terjual</span>
        </div>
      </div>
    </div>

    {{-- RINGKASAN PESANAN --}}
    <div class="lg:col-span-1 flex justify-center relative">
      <div class="bg-white shadow-lg border border-gray-100 w-full max-w-[320px] h-fit rounded-2xl p-6 sticky top-24 hover:shadow-xl transition duration-300">
        <h3 class="text-lg font-bold text-[#0A1D56] mb-4 text-center">Ringkasan Pesanan</h3>

        <div class="border-b border-gray-200 pb-4 mb-4">
          <p class="text-sm font-semibold text-gray-800 mb-1">Kursi</p>
          <div id="seatsSummary" class="text-sm text-gray-600 min-h-6 mb-2"><span class="text-gray-500">-</span></div>
          <div class="text-xs text-gray-600"><span id="seatCount">0</span> kursi × Rp <span id="pricePerSeat">{{ number_format($hargaPerKursi, 0, ',', '.') }}</span></div>
        </div>

        <div class="flex justify-between items-center mb-6">
          <span class="font-semibold text-gray-900">Total</span>
          <span class="text-2xl font-bold text-[#0A1D56]">Rp <span id="total">0</span></span>
        </div>

        <button id="buyButton" class="w-full border-2 border-[#0A1D56] text-[#0A1D56] font-semibold py-2 rounded-full opacity-50 cursor-not-allowed transition hover:bg-[#0A1D56] hover:text-white hover:opacity-100">
          Lanjutkan
        </button>
      </div>
    </div>
  </div>

  @include('components.footer')

  <script>
    const kursiData = @json($kursi);
    const seatPrice = {{ $hargaPerKursi }};
    const jadwalId = {{ $jadwal->id }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    const seatArea = document.getElementById("seatArea");
    const selectedSeats = document.getElementById("selectedSeats");
    const seatCount = document.getElementById("seatCount");
    const total = document.getElementById("total");
    const buyButton = document.getElementById("buyButton");
    const seatsSummary = document.getElementById("seatsSummary");

    let chosen = [];

    // Group kursi by row
    const grouped = {};
    kursiData.forEach(k => {
      const row = k.nomorkursi.charAt(0);
      if (!grouped[row]) grouped[row] = [];
      grouped[row].push(k);
    });

    // Render seats with split layout
    Object.keys(grouped).sort().forEach(row => {
      const rowDiv = document.createElement("div");
      rowDiv.className = "flex items-center gap-4 mb-3";

      const labelLeft = document.createElement("span");
      labelLeft.className = "w-6 text-center font-bold text-gray-700";
      labelLeft.textContent = row;
      rowDiv.appendChild(labelLeft);

      const sortedSeats = grouped[row].sort((a,b) => 
        parseInt(a.nomorkursi.slice(1)) - parseInt(b.nomorkursi.slice(1))
      );

      const totalSeats = sortedSeats.length;
      const halfPoint = Math.ceil(totalSeats / 2);
      
      const leftSeats = sortedSeats.slice(0, halfPoint);
      const rightSeats = sortedSeats.slice(halfPoint);

      const leftBlock = document.createElement("div");
      leftBlock.className = "flex gap-2";
      leftSeats.forEach(k => leftBlock.appendChild(makeSeat(k)));
      rowDiv.appendChild(leftBlock);

      const aisle = document.createElement("div");
      aisle.className = "w-8 flex items-center justify-center";
      aisle.innerHTML = '<div class="w-1 h-6 bg-gray-300 rounded"></div>';
      rowDiv.appendChild(aisle);

      const rightBlock = document.createElement("div");
      rightBlock.className = "flex gap-2";
      rightSeats.forEach(k => rightBlock.appendChild(makeSeat(k)));
      rowDiv.appendChild(rightBlock);

      const labelRight = document.createElement("span");
      labelRight.className = "w-6 text-center font-bold text-gray-700";
      labelRight.textContent = row;
      rowDiv.appendChild(labelRight);

      seatArea.appendChild(rowDiv);
    });

    function makeSeat(k) {
      const seat = document.createElement("div");
      seat.dataset.seat = k.nomorkursi;
      seat.textContent = k.nomorkursi.replace(/[A-Z]/, "");
      seat.className = "w-9 h-9 flex items-center justify-center text-white text-xs font-bold rounded-md border-2 cursor-pointer transition-all duration-200 shadow-sm";
      
      if (k.status === "terjual") {
        seat.classList.add("bg-red-500", "border-red-600", "cursor-not-allowed", "opacity-80");
        seat.title = "Terjual";
      } else if (k.status === "dipesan") {
        seat.classList.add("bg-orange-500", "border-orange-600", "cursor-not-allowed", "opacity-80");
        seat.title = "Sedang Dipesan";
      } else if (k.status === "tidaktersedia") {
        seat.classList.add("bg-gray-400", "border-gray-500", "cursor-not-allowed", "opacity-60");
        seat.title = "Tidak Tersedia";
      } else {
        seat.classList.add("bg-blue-600", "border-blue-700", "hover:bg-blue-700", "hover:scale-110", "hover:shadow-md");
        seat.title = "Klik untuk memilih";
        seat.addEventListener("click", () => toggleSeat(k.nomorkursi, seat));
      }
      
      return seat;
    }

    function toggleSeat(code, el) {
      const idx = chosen.indexOf(code);
      if (idx !== -1) {
        chosen.splice(idx, 1);
        el.classList.remove("bg-green-500", "border-green-600", "scale-110");
        el.classList.add("bg-blue-600", "border-blue-700");
      } else {
        chosen.push(code);
        el.classList.remove("bg-blue-600", "border-blue-700");
        el.classList.add("bg-green-500", "border-green-600", "scale-110");
      }
      updateSummary();
    }

    function updateSummary() {
      if (chosen.length === 0) {
        selectedSeats.innerHTML = "<span class='text-gray-500'>Belum ada kursi yang dipilih</span>";
        seatsSummary.innerHTML = "<span class='text-gray-500'>-</span>";
        buyButton.classList.add("opacity-50", "cursor-not-allowed");
        buyButton.disabled = true;
      } else {
        selectedSeats.innerHTML = chosen.sort().map(seat => 
          `<span class="px-2 py-1 bg-green-100 text-green-700 rounded-md text-xs font-semibold">${seat}</span>`
        ).join(' ');
        seatsSummary.textContent = chosen.sort().join(", ");
        buyButton.classList.remove("opacity-50", "cursor-not-allowed");
        buyButton.disabled = false;
      }

      seatCount.textContent = chosen.length;
      total.textContent = (chosen.length * seatPrice).toLocaleString("id-ID");
    }

    buyButton.addEventListener("click", async (e) => {
      e.preventDefault();
      
      if (chosen.length === 0) {
        alert("Pilih kursi terlebih dahulu!");
        return;
      }

      buyButton.disabled = true;
      buyButton.textContent = "Memproses...";
      buyButton.classList.add("opacity-50");

      try {
        const payload = {
          kursi: chosen,
          hargaPerKursi: seatPrice,
          jadwal_id: jadwalId
        };

        const res = await fetch("{{ route('proses-booking') }}", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
            "X-Requested-With": "XMLHttpRequest",
            "Accept": "application/json"
          },
          body: JSON.stringify(payload)
        });

        const data = await res.json();

        
        if (data.success && data.transaksiId) {
          console.log("✅ Transaksi berhasil dibuat!");
          window.location.href = `/kasir/transaksi-kasir/${data.transaksiId}`;
        } else {
          console.error("❌ Transaksi gagal:", data);
          alert(data.message || "Gagal membuat transaksi.");
          
          buyButton.disabled = false;
          buyButton.textContent = "Lanjutkan";
          buyButton.classList.remove("opacity-50");
        }
      } catch (err) {
        console.error("❌ Error:", err);
        alert("Terjadi kesalahan: " + err.message);
        
        buyButton.disabled = false;
        buyButton.textContent = "Lanjutkan";
        buyButton.classList.remove("opacity-50");
      }
    });

    updateSummary();
  </script>
</body>
</html>