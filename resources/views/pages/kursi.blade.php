<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Pilih Kursi | Flixora</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    /* ===========================
       ANIMASI ENTRY HALAMAN
    =========================== */
    @keyframes fadeInDown {
      from {
        opacity: 0;
        transform: translateY(-30px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(40px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes fadeInLeft {
      from {
        opacity: 0;
        transform: translateX(-40px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    @keyframes fadeInRight {
      from {
        opacity: 0;
        transform: translateX(40px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    @keyframes scaleIn {
      from {
        opacity: 0;
        transform: scale(0.8);
      }
      to {
        opacity: 1;
        transform: scale(1);
      }
    }

    /* Animasi untuk kursi saat render */
    @keyframes seatPop {
      0% {
        opacity: 0;
        transform: scale(0.5) rotate(-10deg);
      }
      60% {
        transform: scale(1.1) rotate(5deg);
      }
      100% {
        opacity: 1;
        transform: scale(1) rotate(0deg);
      }
    }

    /* Animasi klik kursi */
    @keyframes seatClick {
      0% {
        transform: scale(1);
      }
      50% {
        transform: scale(0.85) rotate(5deg);
      }
      100% {
        transform: scale(1.1) rotate(0deg);
      }
    }

    /* Animasi button pulse */
    @keyframes pulse {
      0%, 100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(10, 29, 86, 0.7);
      }
      50% {
        transform: scale(1.05);
        box-shadow: 0 0 0 10px rgba(10, 29, 86, 0);
      }
    }

    /* Animasi shimmer untuk button */
    @keyframes shimmer {
      0% {
        background-position: -1000px 0;
      }
      100% {
        background-position: 1000px 0;
      }
    }

    /* Kelas animasi */
    .animate-fade-in-down {
      opacity: 0;
      animation: fadeInDown 0.8s ease-out forwards;
    }

    .animate-fade-in-up {
      opacity: 0;
      animation: fadeInUp 0.8s ease-out forwards;
    }

    .animate-fade-in-left {
      opacity: 0;
      animation: fadeInLeft 0.8s ease-out forwards;
    }

    .animate-fade-in-right {
      opacity: 0;
      animation: fadeInRight 0.8s ease-out forwards;
    }

    .animate-scale-in {
      opacity: 0;
      animation: scaleIn 0.7s ease-out forwards;
    }

    .seat-pop {
      animation: seatPop 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55) forwards;
    }

    .seat-click-anim {
      animation: seatClick 0.3s ease-out;
    }

    .button-pulse {
      animation: pulse 2s infinite;
    }

    .button-shimmer {
      background: linear-gradient(90deg, 
        transparent, 
        rgba(255,255,255,0.3), 
        transparent
      );
      background-size: 1000px 100%;
      animation: shimmer 2s infinite;
    }

    /* Delay classes */
    .delay-100 { animation-delay: 0.1s; }
    .delay-200 { animation-delay: 0.2s; }
    .delay-300 { animation-delay: 0.3s; }
    .delay-400 { animation-delay: 0.4s; }
    .delay-500 { animation-delay: 0.5s; }
    .delay-600 { animation-delay: 0.6s; }
    .delay-700 { animation-delay: 0.7s; }
  </style>
</head>

<body class="bg-gradient-to-b from-[#f7faff] to-[#dbe9ff] min-h-screen font-sans text-gray-800">
  @include('components.navbar')

  <!-- HEADER -->
  <div class="animate-fade-in-down text-center mt-10">
    <h1 class="text-2xl md:text-3xl font-bold tracking-wide text-[#0A1D56]">
      {{ strtoupper($film->judul) }}
    </h1>
    <p class="text-[#1E56A0] text-sm mt-1">
      {{ \Carbon\Carbon::parse($jadwal->tanggal)->translatedFormat('d F Y') }} |
      {{ \Carbon\Carbon::parse($jadwal->jamtayang)->format('H:i') }}
    </p>
  </div>

  <!-- MAIN CONTENT -->
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-10 mt-10 px-6 lg:px-20">
    
    <!-- AREA KURSI -->
    <div class="animate-fade-in-left delay-200 lg:col-span-2 flex flex-col items-center">
      <div class="bg-white shadow-xl border border-gray-100 rounded-2xl p-8 w-full max-w-[850px] transition duration-300 hover:shadow-2xl">
        
        <!-- LAYAR -->
        <div class="text-center mb-8">
          <div class="inline-block bg-gradient-to-r from-[#A8C0FF] to-[#3f2b96] rounded-full h-2 w-64 mb-3"></div>
          <p class="text-sm text-gray-600 font-medium tracking-wide">LAYAR</p>
        </div>

        <!-- SEAT GRID -->
        <div id="seatArea" class="flex flex-col gap-4 items-center font-semibold text-gray-700 text-sm"></div>
        
        <!-- SELECTED SEATS DISPLAY -->
        <div id="selectedSeats" class="flex flex-wrap justify-center gap-2 text-sm text-gray-700 mt-6 min-h-8">
          <span class="text-gray-500">Belum ada kursi yang dipilih</span>
        </div>
      </div>

      <!-- LEGEND -->
      <div class="animate-fade-in-up delay-400 grid grid-cols-2 gap-x-8 gap-y-3 justify-items-start mt-6 text-xs">
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

    <!-- RINGKASAN PESANAN -->
    <div class="animate-fade-in-right delay-300 lg:col-span-1 flex justify-center relative">
      <div class="bg-white shadow-lg border border-gray-100 w-full max-w-[320px] h-fit rounded-2xl p-6 sticky top-24 hover:shadow-xl transition duration-300">
        <h3 class="text-lg font-bold text-[#0A1D56] mb-4 text-center">Ringkasan Pesanan</h3>

        <!-- SEAT DETAILS -->
        <div class="border-b border-gray-200 pb-4 mb-4">
          <p class="text-sm font-semibold text-gray-800 mb-1">Kursi</p>
          <div id="seatsSummary" class="text-sm text-gray-600 min-h-6 mb-2">
            <span class="text-gray-500">-</span>
          </div>
          <div class="text-xs text-gray-600">
            <span id="seatCount">0</span> kursi × Rp <span id="pricePerSeat">{{ $hargaPerKursi }}</span>
          </div>
        </div>

        <!-- TOTAL PRICE -->
        <div class="flex justify-between items-center mb-6">
          <span class="font-semibold text-gray-900">Total</span>
          <span class="text-2xl font-bold text-[#0A1D56]">Rp <span id="total">0</span></span>
        </div>

        <!-- BUY BUTTON -->
        <a href="#" id="buyButton" class="relative overflow-hidden block text-center border-2 border-[#0A1D56] text-[#0A1D56] font-semibold py-2 rounded-full opacity-50 cursor-not-allowed transition hover:bg-[#0A1D56] hover:text-white hover:opacity-100">
          <span class="relative z-10">Bayar Sekarang</span>
        </a>
      </div>
    </div>
  </div>

  @include('components.footer')

  <script>
    // ===========================
    // DATA INITIALIZATION
    // ===========================
    const kursiData = @json($kursi);
    const seatPrice = {{ $hargaPerKursi }};
    const jadwalId = {{ $jadwal->id }};
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // DOM Elements
    const seatArea = document.getElementById("seatArea");
    const selectedSeats = document.getElementById("selectedSeats");
    const seatCount = document.getElementById("seatCount");
    const total = document.getElementById("total");
    const buyButton = document.getElementById("buyButton");
    const seatsSummary = document.getElementById("seatsSummary");

    let chosen = [];

    // ===========================
    // SEAT RENDERING
    // ===========================
    
    // Group kursi by row
    const grouped = {};
    kursiData.forEach(k => {
      const row = k.nomorkursi.charAt(0);
      if (!grouped[row]) grouped[row] = [];
      grouped[row].push(k);
    });

    // Render seats with split layout (left-aisle-right)
    let seatAnimDelay = 0;
    Object.keys(grouped).sort().forEach(row => {
      const rowDiv = document.createElement("div");
      rowDiv.className = "flex items-center gap-4 mb-3";

      // Row label (kiri)
      const labelLeft = document.createElement("span");
      labelLeft.className = "w-6 text-center font-bold text-gray-700";
      labelLeft.textContent = row;
      rowDiv.appendChild(labelLeft);

      // Sort seats by number
      const sortedSeats = grouped[row].sort((a, b) => 
        parseInt(a.nomorkursi.slice(1)) - parseInt(b.nomorkursi.slice(1))
      );

      // Split seats into left and right sections
      const totalSeats = sortedSeats.length;
      const halfPoint = Math.ceil(totalSeats / 2);
      
      const leftSeats = sortedSeats.slice(0, halfPoint);
      const rightSeats = sortedSeats.slice(halfPoint);

      // Left section
      const leftBlock = document.createElement("div");
      leftBlock.className = "flex gap-2";
      leftSeats.forEach(k => {
        const seat = makeSeat(k, seatAnimDelay);
        leftBlock.appendChild(seat);
        seatAnimDelay += 20; // Increment delay
      });
      rowDiv.appendChild(leftBlock);

      // Aisle (jalan tengah)
      const aisle = document.createElement("div");
      aisle.className = "w-8 flex items-center justify-center";
      aisle.innerHTML = '<div class="w-1 h-6 bg-gray-300 rounded"></div>';
      rowDiv.appendChild(aisle);

      // Right section
      const rightBlock = document.createElement("div");
      rightBlock.className = "flex gap-2";
      rightSeats.forEach(k => {
        const seat = makeSeat(k, seatAnimDelay);
        rightBlock.appendChild(seat);
        seatAnimDelay += 20; // Increment delay
      });
      rowDiv.appendChild(rightBlock);

      // Row label (kanan)
      const labelRight = document.createElement("span");
      labelRight.className = "w-6 text-center font-bold text-gray-700";
      labelRight.textContent = row;
      rowDiv.appendChild(labelRight);

      seatArea.appendChild(rowDiv);
    });

    // ===========================
    // SEAT CREATION FUNCTION
    // ===========================
    function makeSeat(k, delay) {
      const seat = document.createElement("div");
      seat.dataset.seat = k.nomorkursi;
      seat.textContent = k.nomorkursi.replace(/[A-Z]/, "");
      seat.className = "seat-pop w-9 h-9 flex items-center justify-center text-white text-xs font-bold rounded-md border-2 cursor-pointer transition-all duration-200 shadow-sm";
      seat.style.animationDelay = `${delay}ms`;
      
      // Status-based styling
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
        // Status: tersedia
        seat.classList.add("bg-blue-600", "border-blue-700", "hover:bg-blue-700", "hover:scale-110", "hover:shadow-md");
        seat.title = "Klik untuk memilih";
        seat.addEventListener("click", () => toggleSeat(k.nomorkursi, seat));
      }
      
      return seat;
    }

    // ===========================
    // SEAT SELECTION LOGIC
    // ===========================
    function toggleSeat(code, el) {
      // Trigger click animation
      el.classList.add('seat-click-anim');
      setTimeout(() => el.classList.remove('seat-click-anim'), 300);

      const idx = chosen.indexOf(code);
      
      if (idx !== -1) {
        // Deselect seat
        chosen.splice(idx, 1);
        el.classList.remove("bg-green-500", "border-green-600", "scale-110");
        el.classList.add("bg-blue-600", "border-blue-700");
      } else {
        // Select seat
        chosen.push(code);
        el.classList.remove("bg-blue-600", "border-blue-700");
        el.classList.add("bg-green-500", "border-green-600", "scale-110");
      }
      
      updateSummary();
    }

    // ===========================
    // UPDATE SUMMARY
    // ===========================
    function updateSummary() {
      if (chosen.length === 0) {
        selectedSeats.innerHTML = "<span class='text-gray-500'>Belum ada kursi yang dipilih</span>";
        seatsSummary.innerHTML = "<span class='text-gray-500'>-</span>";
        buyButton.classList.add("opacity-50", "cursor-not-allowed");
        buyButton.classList.remove("button-pulse");
        buyButton.style.pointerEvents = "none";
      } else {
        selectedSeats.innerHTML = chosen.sort().map(seat => 
          `<span class="px-2 py-1 bg-green-100 text-green-700 rounded-md text-xs font-semibold animate-scale-in">${seat}</span>`
        ).join(' ');
        seatsSummary.textContent = chosen.sort().join(", ");
        buyButton.classList.remove("opacity-50", "cursor-not-allowed");
        buyButton.classList.add("button-pulse");
        buyButton.style.pointerEvents = "auto";
      }

      seatCount.textContent = chosen.length;
      total.textContent = (chosen.length * seatPrice).toLocaleString("id-ID");
    }

    // ===========================
    // PAYMENT PROCESS
    // ===========================
    buyButton.addEventListener("click", async (e) => {
      e.preventDefault();
      
      if (chosen.length === 0) {
        alert("Pilih kursi terlebih dahulu!");
        return;
      }

      // Animasi loading pada button
      buyButton.disabled = true;
      const originalContent = buyButton.innerHTML;
      buyButton.innerHTML = '<span class="relative z-10 flex items-center justify-center gap-2"><svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Memproses...</span>';
      buyButton.classList.add("opacity-50");
      buyButton.classList.remove("button-pulse");

      console.log("=== DEBUG INFO ===");
      console.log("Kursi terpilih:", chosen);
      console.log("Harga per kursi:", seatPrice);
      console.log("Jadwal ID:", jadwalId);

      try {
        const payload = {
          kursi: chosen,
          hargaPerKursi: seatPrice,
          jadwal_id: jadwalId
        };
        
        console.log("Payload yang dikirim:", payload);

        const res = await fetch("/buat-pembayaran", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
            "X-Requested-With": "XMLHttpRequest",
            "Accept": "application/json"
          },
          body: JSON.stringify(payload)
        });

        console.log("Response status:", res.status);

        const contentType = res.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
          const text = await res.text();
          console.error("Response bukan JSON:", text);
          throw new Error("Server tidak mengembalikan JSON. Cek console untuk detail.");
        }

        const data = await res.json();
        console.log("Response data:", data);

        if (data.success && data.transaksiId) {
          console.log("✅ Transaksi berhasil dibuat!");
          // Animasi sukses sebelum redirect
          buyButton.innerHTML = '<span class="relative z-10 flex items-center justify-center gap-2"><svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>Berhasil!</span>';
          buyButton.classList.remove("opacity-50");
          buyButton.classList.add("bg-green-500", "border-green-600");
          
          setTimeout(() => {
            window.location.href = `/transaksi/${data.transaksiId}`;
          }, 500);
        } else {
          console.error("❌ Transaksi gagal:", data);
          alert(data.message || "Gagal membuat transaksi. Cek console untuk detail.");
          
          buyButton.disabled = false;
          buyButton.innerHTML = originalContent;
          buyButton.classList.remove("opacity-50");
        }
      } catch (err) {
        console.error("❌ Error:", err);
        alert("Terjadi kesalahan: " + err.message);
        
        buyButton.disabled = false;
        buyButton.innerHTML = originalContent;
        buyButton.classList.remove("opacity-50");
      }
    });

    // ===========================
    // INITIALIZATION
    // ===========================
    updateSummary();
  </script>
</body>
</html>