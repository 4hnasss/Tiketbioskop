<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pilih Kursi | TicketLy</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-white to-[#D6E4F0] w-full">

  @include('components.navbar')

  <!-- JUDUL -->
  <div class="text-center text-[20px] font-bold mt-[32px]">
    <p>PANGEPUNGAN DI BUKIT DURI</p>
    <span class="text-[15px] text-[#1E56A0]">29 November 2025 20.00</span>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 px-[20px] mt-[50px]">

    <!-- MAIN -->
    <div class="lg:col-span-2 flex flex-col items-center">
      <div class="bg-[#D6E4F0] rounded-lg shadow-md p-8 mb-8 w-full max-w-[750px]">

        <!-- LAYAR -->
        <div class="text-center mb-8">
          <div class="inline-block bg-gradient-to-r from-gray-400 to-gray-300 rounded-full h-2 w-48 mb-4"></div>
          <p class="text-sm text-gray-600">LAYAR</p>
        </div>

        <!-- AREA KURSI -->
        <div id="seatArea" class="flex flex-col gap-4 items-center font-semibold text-gray-700 text-sm"></div>

        <!-- PILIHAN KURSI -->
        <div id="selectedSeats" class="flex flex-wrap justify-center gap-2 text-sm text-gray-700 mt-6"></div>
      </div>

      <!-- KETERANGAN -->
      <div class="grid grid-cols-2 sm:grid-cols-2 gap-x-8 gap-y-3 justify-items-start mb-8 text-xs mx-auto w-fit">
        <div class="flex items-center gap-2">
          <div class="w-6 h-6 bg-[#14274E] rounded border border-[#14274E]"></div>
          <span class="text-gray-600">Tersedia</span>
        </div>
        <div class="flex items-center gap-2">
          <div class="w-6 h-6 bg-[#1E56A0] rounded border border-[#1E56A0]"></div>
          <span class="text-gray-600">Dipesan</span>
        </div>
        <div class="flex items-center gap-2">
          <div class="w-6 h-6 bg-[#A01E1E] rounded border border-[#A01E1E]"></div>
          <span class="text-gray-600">Tidak Tersedia</span>
        </div>
        <div class="flex items-center gap-2">
          <div class="w-6 h-6 bg-[#92A500] rounded border border-[#92A500]"></div>
          <span class="text-gray-600">Terjual</span>
        </div>
      </div>
    </div>

    <!-- SIDEBAR -->
    <div class="lg:col-span-1 flex justify-center">
      <div class="bg-transparent border-2 border-[#14274E] w-full max-w-[300px] h-[370px] rounded-lg shadow-md p-6 sticky top-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-3">Ringkasan Pesanan</h3>
        <div class="border-b border-gray-200 pb-3 mb-3">
          <p class="text-sm font-semibold text-gray-900 mb-2">Kursi</p>
          <div id="seatsSummary" class="text-sm text-gray-600 min-h-6 mb-2">
            <span class="text-gray-500">-</span>
          </div>
          <div class="text-xs text-gray-600">
            <span id="seatCount">0</span> kursi × Rp <span id="pricePerSeat">20000</span>
          </div>
        </div>

        <div class="mb-4">
          <div class="flex justify-between items-center mb-2">
            <span class="text-gray-600">Subtotal</span>
            <span class="font-semibold text-gray-900">Rp <span id="subtotal">0</span></span>
          </div>
          <div class="flex justify-between items-center pb-2 border-b border-gray-200 mb-2">
            <span class="text-gray-600">Biaya Admin</span>
            <span class="font-semibold text-gray-900">Rp <span id="adminFee">5000</span></span>
          </div>
          <div class="flex justify-between items-center">
            <span class="font-semibold text-gray-900">Total</span>
            <span class="text-2xl font-bold text-[#14274E]">Rp <span id="total">0</span></span>
          </div>
        </div>

        <a href="/transaksi" id="buyButton" disabled class="inline-block bg-[#14274E] hover:bg-blue-900 text-white font-semibold px-[110px] py-2 rounded-lg transition duration-200">
          Beli
        </a>

      </div>
    </div>
  </div>

  <!-- SCRIPT -->
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const seatArea = document.getElementById("seatArea");
      const selectedSeats = document.getElementById("selectedSeats");
      const seatSummary = document.getElementById("seatsSummary");
      const seatCount = document.getElementById("seatCount");
      const subtotal = document.getElementById("subtotal");
      const total = document.getElementById("total");
      const buyButton = document.getElementById("buyButton");

      const seatPrice = 20000;
      const adminFee = 5000;

      const rows = ["A", "B", "C", "D", "E", "F", "G"];
      const seatsPerSide = 6;
      const soldSeats = ["A1", "B2", "C4", "D7", "F9"]; 
      const unavailableSeats = ["B3", "E11"]; 
      const reservedSeats = ["C3", "E6"]; 

      let chosen = [];

      // Generate kursi (mulai dari kanan = 1)
      rows.forEach(row => {
        const rowDiv = document.createElement("div");
        rowDiv.className = "flex items-center gap-3";

        // Label kiri
        const labelLeft = document.createElement("span");
        labelLeft.className = "w-6 text-center";
        labelLeft.textContent = row;
        rowDiv.appendChild(labelLeft);

        // Blok kiri (kursi 12–7)
        const leftBlock = document.createElement("div");
        leftBlock.className = "flex gap-2";
        for (let i = 12; i >= 7; i--) {
          leftBlock.appendChild(makeSeat(row + i));
        }
        rowDiv.appendChild(leftBlock);

        // Jarak tengah
        const gap = document.createElement("div");
        gap.className = "mx-6";
        rowDiv.appendChild(gap);

        // Blok kanan (kursi 6–1)
        const rightBlock = document.createElement("div");
        rightBlock.className = "flex gap-2";
        for (let i = 6; i >= 1; i--) {
          rightBlock.appendChild(makeSeat(row + i));
        }
        rowDiv.appendChild(rightBlock);

        // Label kanan
        const labelRight = document.createElement("span");
        labelRight.className = "w-6 text-center";
        labelRight.textContent = row;
        rowDiv.appendChild(labelRight);

        seatArea.appendChild(rowDiv);
      });

      function makeSeat(code) {
        const seat = document.createElement("div");
        seat.className = "w-8 h-8 flex items-center justify-center text-white text-xs font-bold rounded border-2 cursor-pointer transition";
        seat.textContent = code.replace(/[A-Z]/, ""); // tampilkan nomor kursi

        if (soldSeats.includes(code)) {
          seat.classList.add("bg-[#92A500]", "border-[#92A500]", "cursor-not-allowed");
        } else if (unavailableSeats.includes(code)) {
          seat.classList.add("bg-[#A01E1E]", "border-[#A01E1E]", "cursor-not-allowed");
        } else if (reservedSeats.includes(code)) {
          seat.classList.add("bg-[#1E56A0]", "border-[#1E56A0]", "cursor-not-allowed");
        } else {
          seat.classList.add("bg-[#14274E]", "border-[#14274E]", "hover:opacity-80");
          seat.addEventListener("click", () => toggleSeat(code, seat));
        }
        return seat;
      }

      function toggleSeat(code, el) {
        if (chosen.includes(code)) {
          chosen = chosen.filter(c => c !== code);
          el.classList.remove("bg-blue-600", "border-blue-600");
          el.classList.add("bg-[#14274E]", "border-[#14274E]");
        } else {
          chosen.push(code);
          el.classList.add("bg-blue-600", "border-blue-600");
          el.classList.remove("bg-[#14274E]", "border-[#14274E]");
        }
        updateSummary();
      }

      function updateSummary() {
        if (chosen.length === 0) {
          selectedSeats.innerHTML = "Belum ada kursi yang dipilih";
          seatSummary.innerHTML = "<span class='text-gray-500'>-</span>";
          buyButton.disabled = true;
        } else {
          selectedSeats.innerHTML = chosen
            .map(c => `<span class='px-2 py-1 bg-blue-100 text-blue-700 rounded text-sm'>${c}</span>`)
            .join("");
          seatSummary.innerHTML = chosen.join(", ");
          buyButton.disabled = false;
        }

        seatCount.textContent = chosen.length;
        const sub = chosen.length * seatPrice;
        subtotal.textContent = sub.toLocaleString("id-ID");
        total.textContent = (sub + adminFee).toLocaleString("id-ID");
      }
    });
  </script>

  @include('components.footer')
</body>
</html>
