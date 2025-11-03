<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Transaksi | Flixora</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
</head>

<body class="bg-gradient-to-r from-white to-[#E3F2FD] min-h-screen font-sans">

    <!-- Include Navbar -->
    @include('components.navbar')

    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <div class="mb-6">
            <a href="{{ route('transaksi.riwayat') }}" class="text-[#1E56A0] hover:text-[#14274E] font-semibold flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Riwayat
            </a>
        </div>

        <h1 class="text-3xl font-bold text-center mb-8 text-[#14274E]">Detail Transaksi</h1>

        {{-- Main Card --}}
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            {{-- Film Info Section --}}
            <div class="bg-gradient-to-r from-[#1E56A0] to-[#14274E] p-6 text-white">
                <div class="flex items-start space-x-6">
                    <img src="{{ asset('img/' . $transaksi->jadwal->film->poster) }}" 
                         alt="Poster Film" 
                         class="w-32 h-44 rounded-lg object-cover shadow-lg">
                    
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold mb-3">{{ $transaksi->jadwal->film->judul }}</h2>
                        <div class="space-y-2 text-sm">
                            <p><strong>Studio:</strong> {{ $transaksi->jadwal->studio->nama_studio }}</p>
                            <p><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d F Y') }}</p>
                            <p><strong>Waktu:</strong> {{ \Carbon\Carbon::parse($transaksi->jadwal->jamtayang)->format('H:i') }}</p>
                            <p><strong>Durasi:</strong> {{ $transaksi->jadwal->film->durasi }} menit</p>
                        </div>

                        @php
                            $statusMap = [
                                'pending' => ['bg'=>'bg-yellow-400','text'=>'text-yellow-900','label'=>'Menunggu Pembayaran'],
                                'settlement' => ['bg'=>'bg-green-400','text'=>'text-green-900','label'=>'Pembayaran Selesai'],
                                'batal' => ['bg'=>'bg-red-400','text'=>'text-red-900','label'=>'Dibatalkan'],
                                'challenge' => ['bg'=>'bg-orange-400','text'=>'text-orange-900','label'=>'Menunggu Verifikasi']
                            ];
                            $status = $statusMap[$transaksi->status] ?? ['bg'=>'bg-gray-400','text'=>'text-gray-900','label'=>$transaksi->status];
                        @endphp
                        
                        <div class="mt-4">
                            <span class="inline-block px-4 py-2 text-sm font-bold rounded-full {{ $status['bg'] }} {{ $status['text'] }}">
                                {{ $status['label'] }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Transaction Details --}}
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-[#14274E] mb-4">Informasi Pembayaran</h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-600">ID Transaksi</p>
                        <p class="font-semibold text-[#14274E]">#{{ $transaksi->id }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600">Tanggal Pemesanan</p>
                        <p class="font-semibold text-[#14274E]">{{($transaksi->tanggaltransaksi) }}</p>
                    </div>
                    @if($transaksi->metode_pembayaran)
                    <div>
                        <p class="text-gray-600">Metode Pembayaran</p>
                        <p class="font-semibold text-[#14274E]">{{ ucfirst($transaksi->metode_pembayaran) }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-gray-600">Total Pembayaran</p>
                        <p class="font-bold text-xl text-[#1E56A0]">Rp {{ number_format($transaksi->totalharga, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>

            {{-- Tickets Section --}}
            <div class="p-6">
                <h3 class="text-xl font-bold text-[#14274E] mb-4">Tiket</h3>
                <div class="space-y-3">
                    @forelse($transaksi->tikets as $index => $tiket)
                        <div class="border-2 border-dashed border-[#1E56A0] rounded-lg p-4 bg-blue-50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div class="bg-[#1E56A0] text-white w-12 h-12 rounded-full flex items-center justify-center font-bold text-lg">
                                        {{ $index + 1 }}
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-600">Kursi</p>
                                        <p class="text-xl font-bold text-[#14274E]">{{ $tiket->kursi->nomor_kursi }}</p>
                                    </div>
                                </div>
                                
                                <div class="text-right">
                                    <p class="text-sm text-gray-600">Kode Tiket</p>
                                    @if($tiket->kodetiket)
                                        <p class="text-lg font-mono font-bold text-[#1E56A0]">{{ $tiket->kodetiket }}</p>
                                    @else
                                        <p class="text-sm text-gray-400 italic">Belum tersedia</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500">
                            <p>Tidak ada tiket ditemukan</p>
                        </div>
                    @endforelse
                </div>

                {{-- Total Summary --}}
                <div class="mt-6 pt-6 border-t-2 border-gray-200">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-gray-600">Total Tiket</p>
                            <p class="text-2xl font-bold text-[#14274E]">{{ $transaksi->tikets->count() }} Tiket</p>
                        </div>
                        <div class="text-right">
                            <p class="text-gray-600">Total Harga</p>
                            <p class="text-3xl font-bold text-[#1E56A0]">Rp {{ number_format($transaksi->totalharga, 0, ',', '.') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="p-6 bg-gray-50 border-t border-gray-200">
                @if($transaksi->status === 'pending')
                    <button onclick="payNow()" 
                            class="w-full bg-[#1E56A0] text-white py-3 rounded-lg font-semibold hover:bg-[#14274E] transition duration-200 shadow-lg">
                        Bayar Sekarang
                    </button>
                    
                    <script>
                        function payNow() {
                            snap.pay('{{ $transaksi->snap_token }}', {
                                onSuccess: function(result) {
                                    window.location.href = '{{ route("transaksi.riwayat") }}';
                                },
                                onPending: function(result) {
                                    alert('Menunggu pembayaran');
                                },
                                onError: function(result) {
                                    alert('Pembayaran gagal');
                                },
                                onClose: function() {
                                    console.log('Popup ditutup');
                                }
                            });
                        }
                    </script>
                @elseif($transaksi->status === 'settlement')
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <p class="text-lg font-semibold text-green-700">Pembayaran Berhasil!</p>
                        <p class="text-sm text-gray-600 mt-2">Tunjukkan kode tiket di atas saat memasuki studio</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Include Footer -->
    @include('components.footer')
</body>
</html>