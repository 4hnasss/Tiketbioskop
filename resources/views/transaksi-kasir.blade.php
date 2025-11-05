{{-- resources/views/kasir/transaksi-pembayaran.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Transaksi Pembayaran | Kasir - Flixora</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(to right, #ffffff, #D6E4F0);
        }
    </style>
</head>

<body class="min-h-screen text-[#14274E]">
    @include('components.nav')

    <div class="container mx-auto px-6 py-10">
        <div class="max-w-5xl mx-auto">
            
            {{-- Header --}}
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-[#14274E] mb-2">Pembayaran Tiket</h1>
                <p class="text-[#14274E]/70 text-base">ID Transaksi: <span class="font-bold text-[#1E56A0]">#{{ $transaksi->id }}</span></p>
            </div>

            <div class="grid md:grid-cols-2 gap-6">
                
                {{-- Detail Transaksi --}}
                <div class="bg-white/80 backdrop-blur-md rounded-xl shadow-lg p-6 border-2 border-[#D6E4F0]">
                    <h2 class="text-xl font-bold text-[#14274E] mb-5 pb-3 border-b-2 border-[#D6E4F0] flex items-center">
                        <i class="fas fa-clipboard-list mr-3 text-[#1E56A0]"></i>
                        Detail Pesanan
                    </h2>
                    
                    <div class="space-y-4">
                        <div class="flex items-start gap-3 bg-[#D6E4F0]/30 rounded-lg p-3">
                            <i class="fas fa-film text-[#1E56A0] text-lg mt-1"></i>
                            <div class="flex-1">
                                <p class="text-xs text-[#14274E]/70 font-semibold mb-1">Film</p>
                                <p class="font-bold text-[#14274E] text-base">{{ $transaksi->jadwal->film->judul }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 bg-[#D6E4F0]/30 rounded-lg p-3">
                            <i class="far fa-calendar-alt text-[#1E56A0] text-lg mt-1"></i>
                            <div class="flex-1">
                                <p class="text-xs text-[#14274E]/70 font-semibold mb-1">Tanggal & Waktu</p>
                                <p class="font-bold text-[#14274E] text-sm">
                                    {{ \Carbon\Carbon::parse($transaksi->jadwal->tanggal)->translatedFormat('d F Y') }}
                                </p>
                                <p class="text-xs text-[#14274E]/80 font-semibold">{{ \Carbon\Carbon::parse($transaksi->jadwal->jamtayang)->format('H:i') }} WIB</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 bg-[#D6E4F0]/30 rounded-lg p-3">
                            <i class="fas fa-building text-[#1E56A0] text-lg mt-1"></i>
                            <div class="flex-1">
                                <p class="text-xs text-[#14274E]/70 font-semibold mb-1">Studio</p>
                                <p class="font-bold text-[#14274E] text-sm">{{ $transaksi->jadwal->studio->nama_studio }}</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-3 bg-[#D6E4F0]/30 rounded-lg p-3">
                            <i class="fas fa-chair text-[#1E56A0] text-lg mt-1"></i>
                            <div class="flex-1">
                                <p class="text-xs text-[#14274E]/70 font-semibold mb-2">Kursi</p>
                                <div class="flex flex-wrap gap-2">
                                    @foreach(is_array($transaksi->kursi) ? $transaksi->kursi : json_decode($transaksi->kursi) as $kursi)
                                        <span class="px-3 py-1 bg-gradient-to-r from-[#1E56A0] to-[#14274E] text-white text-xs font-bold rounded-lg shadow-md">{{ $kursi }}</span>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="pt-4 border-t-2 border-[#D6E4F0]">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-[#14274E]/80 font-semibold text-sm">Jumlah Kursi</span>
                                <span class="font-bold text-[#14274E] text-sm">{{ count(is_array($transaksi->kursi) ? $transaksi->kursi : json_decode($transaksi->kursi)) }} kursi</span>
                            </div>
                            <div class="flex justify-between items-center bg-gradient-to-r from-[#1E56A0] to-[#14274E] rounded-lg p-3 text-white">
                                <span class="text-base font-bold">Total Harga</span>
                                <span class="text-2xl font-bold">Rp {{ number_format($transaksi->totalharga, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pilih Metode Pembayaran --}}
                <div class="bg-white/80 backdrop-blur-md rounded-xl shadow-lg p-6 border-2 border-[#D6E4F0]">
                    <h2 class="text-xl font-bold text-[#14274E] mb-5 pb-3 border-b-2 border-[#D6E4F0] flex items-center">
                        <i class="fas fa-credit-card mr-3 text-[#1E56A0]"></i>
                        Metode Pembayaran
                    </h2>
                    
                    @if($transaksi->status === 'pending')
                        <div class="space-y-3">
                            
                            {{-- Cash Payment --}}
                            <div class="border-2 border-[#D6E4F0] rounded-lg p-4 hover:border-[#1E56A0] hover:bg-[#D6E4F0]/30 transition cursor-pointer payment-method" data-method="cash">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_method" id="cash" value="cash" class="w-4 h-4 text-[#1E56A0] focus:ring-[#1E56A0]">
                                    <label for="cash" class="flex-1 cursor-pointer">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <div class="bg-green-100 p-2.5 rounded-lg">
                                                    <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
                                                </div>
                                                <div>
                                                    <p class="font-bold text-[#14274E] text-sm">Cash</p>
                                                    <p class="text-xs text-[#14274E]/70">Pembayaran tunai</p>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            {{-- E-wallet / Debit --}}
                            <div class="border-2 border-[#D6E4F0] rounded-lg p-4 hover:border-[#1E56A0] hover:bg-[#D6E4F0]/30 transition cursor-pointer payment-method" data-method="online">
                                <div class="flex items-center gap-3">
                                    <input type="radio" name="payment_method" id="online" value="online" class="w-4 h-4 text-[#1E56A0] focus:ring-[#1E56A0]">
                                    <label for="online" class="flex-1 cursor-pointer">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-3">
                                                <div class="bg-blue-100 p-2.5 rounded-lg">
                                                    <i class="fas fa-credit-card text-blue-600 text-xl"></i>
                                                </div>
                                                <div>
                                                    <p class="font-bold text-[#14274E] text-sm">E-wallet / Debit Card</p>
                                                    <p class="text-xs text-[#14274E]/70">GoPay, OVO, Dana, dll</p>
                                                </div>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <button id="processPaymentBtn" disabled class="w-full bg-gray-300 text-gray-500 font-bold py-3 text-sm rounded-lg cursor-not-allowed transition">
                                Pilih Metode Pembayaran
                            </button>

                        </div>
                    @elseif($transaksi->status === 'settlement')
                        <div class="text-center py-10">
                            <div class="bg-green-100 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-check-circle text-green-600 text-4xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-[#14274E] mb-2">Pembayaran Berhasil!</h3>
                            <p class="text-[#14274E]/70 text-sm mb-6">Transaksi telah diselesaikan</p>
                            <a href="{{ route('riwayat-transaksi') }}" class="inline-block bg-gradient-to-r from-[#1E56A0] to-[#14274E] text-white font-bold px-6 py-2.5 text-sm rounded-lg hover:scale-105 transition transform shadow-lg">
                                Lihat Riwayat Transaksi
                            </a>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-[#14274E]/70 text-sm">Status: <span class="font-bold">{{ ucfirst($transaksi->status) }}</span></p>
                        </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    @include('components.footer')

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const transaksiId = {{ $transaksi->id }};
        const snapToken = @json($transaksi->snap_token ?? null);
        
        let selectedMethod = null;

        document.querySelectorAll('.payment-method').forEach(method => {
            method.addEventListener('click', function() {
                const radio = this.querySelector('input[type="radio"]');
                radio.checked = true;
                selectedMethod = radio.value;
                
                const btn = document.getElementById('processPaymentBtn');
                btn.disabled = false;
                btn.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
                btn.classList.add('bg-gradient-to-r', 'from-[#1E56A0]', 'to-[#14274E]', 'text-white', 'hover:scale-105', 'cursor-pointer', 'transform', 'shadow-lg');
                btn.textContent = selectedMethod === 'cash' ? 'Konfirmasi Pembayaran Cash' : 'Bayar dengan E-wallet/Debit';
                
                document.querySelectorAll('.payment-method').forEach(m => {
                    m.classList.remove('border-[#1E56A0]', 'bg-[#D6E4F0]/50');
                });
                this.classList.add('border-[#1E56A0]', 'bg-[#D6E4F0]/50');
            });
        });

        document.getElementById('processPaymentBtn')?.addEventListener('click', async function() {
            if (!selectedMethod) {
                alert('Pilih metode pembayaran terlebih dahulu!');
                return;
            }

            if (selectedMethod === 'cash') {
                if (confirm('Konfirmasi pembayaran tunai sebesar Rp {{ number_format($transaksi->totalharga, 0, ',', '.') }}?')) {
                    this.disabled = true;
                    this.textContent = 'Memproses...';
                    
                    try {
                        const response = await fetch(`/kasir/transaksi-kasir/${transaksiId}/pembayaran-cash`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            alert('Pembayaran berhasil!');
                            window.location.href = '{{ route("riwayat-transaksi") }}';
                        } else {
                            alert(data.message || 'Pembayaran gagal!');
                            this.disabled = false;
                            this.textContent = 'Konfirmasi Pembayaran Cash';
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan: ' + error.message);
                        this.disabled = false;
                        this.textContent = 'Konfirmasi Pembayaran Cash';
                    }
                }
            } else if (selectedMethod === 'online') {
                if (!snapToken) {
                    alert('Token pembayaran tidak tersedia. Silakan coba lagi.');
                    return;
                }

                this.disabled = true;
                this.textContent = 'Membuka halaman pembayaran...';

                snap.pay(snapToken, {
                    onSuccess: async function(result) {
                        const response = await fetch(`/kasir/transaksi-kasir/${transaksiId}/update-status`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                status: 'settlement',
                                metode_pembayaran: result.payment_type
                            })
                        });

                        const data = await response.json();

                        if (data.success) {
                            alert('Pembayaran berhasil!');
                            window.location.href = '{{ route("riwayat-transaksi") }}';
                        }
                    },
                    onPending: async function(result) {
                        await fetch(`/kasir/transaksi-kasir/${transaksiId}/update-status`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            },
                            body: JSON.stringify({
                                status: 'pending',
                                metode_pembayaran: result.payment_type
                            })
                        });
                        alert('Pembayaran pending. Silakan selesaikan pembayaran.');
                        location.reload();
                    },
                    onError: function(result) {
                        alert('Pembayaran gagal. Silakan coba lagi.');
                        location.reload();
                    },
                    onClose: function() {
                        document.getElementById('processPaymentBtn').disabled = false;
                        document.getElementById('processPaymentBtn').textContent = 'Bayar dengan E-wallet/Debit';
                    }
                });
            }
        });
    </script>
</body>
</html>