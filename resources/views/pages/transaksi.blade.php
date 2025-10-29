<!DOCTYPE html>
<html lang="id">
    
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Tambahkan ini di bagian <head> HTML -->
<meta name="csrf-token" content="{{ csrf_token() }}">


    <title>Riwayat Transaksi | TicketLy</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-r from-white to-[#E3F2FD] min-h-screen font-sans">
    
@include('components.navbar')

<div class="container mx-auto px-4 py-8">
    
    <h1 class="text-2xl font-bold text-center mb-8 text-[#14274E]">Riwayat Transaksi</h1>

    <div class="space-y-4">
        @forelse($transaksis as $transaksi)
            <div id="transaksi-{{ $transaksi->id }}" class="flex items-center bg-white rounded-xl shadow-md p-4 border border-gray-100 hover:shadow-lg transition duration-200">
                <img src="{{ asset('img/' . $transaksi->jadwal->film->poster) }}" 
                     alt="Poster" 
                     class="w-20 h-28 rounded-lg object-cover shadow-sm">

                <div class="flex-1 ml-4">
                    <h2 class="text-lg font-semibold text-[#14274E] mb-1">{{ $transaksi->jadwal->film->judul }}</h2>
                    
                    @php
                        $kursiArray = json_decode($transaksi->kursi, true);
                        $kursiText = is_array($kursiArray) ? implode(', ', $kursiArray) : '-';
                    @endphp
                    
                    <p class="text-gray-600 text-xs">
                        <strong>Kursi:</strong> {{ $kursiText }}
                    </p>
                    <p class="text-gray-600 text-xs">
                        <strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($transaksi->tanggaltransaksi)->format('d-m-Y H:i') }}
                    </p>
                    <p class="text-gray-600 text-xs">
                        <strong>Total:</strong> Rp {{ number_format($transaksi->totalharga, 0, ',', '.') }}
                    </p>
                    
                    @if($transaksi->metode_pembayaran)
                        <p class="text-gray-600 text-xs">
                            <strong>Metode:</strong> {{ ucfirst($transaksi->metode_pembayaran) }}
                        </p>
                    @endif

                    @php
                        $map = [
                            'pending' => ['bg'=>'bg-yellow-100','text'=>'text-yellow-700','label'=>'Menunggu Pembayaran'],
                            'success' => ['bg'=>'bg-green-100','text'=>'text-green-700','label'=>'Pembayaran Selesai'],
                            'batal' => ['bg'=>'bg-red-100','text'=>'text-red-700','label'=>'Dibatalkan'],
                            'challenge' => ['bg'=>'bg-orange-100','text'=>'text-orange-700','label'=>'Menunggu Verifikasi']
                        ];
                        $s = $map[$transaksi->status] ?? ['bg'=>'bg-gray-100','text'=>'text-gray-700','label'=>$transaksi->status];
                    @endphp

                    <span id="status-{{ $transaksi->id }}" class="inline-block mt-2 px-2 py-0.5 text-xs font-medium rounded-full {{ $s['bg'] }} {{ $s['text'] }}">
                        {{ $s['label'] }}
                    </span>
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="mt-4 text-gray-500">Belum ada transaksi</p>
                <a href="/" class="mt-4 inline-block px-6 py-2 bg-[#1E56A0] text-white rounded-full hover:bg-[#14274E] transition">
                    Pesan Tiket Sekarang
                </a>
            </div>
        @endforelse
    </div>
</div>

@include('components.footer')

<script>
    
document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');
   const transaksiId = urlParams.get('id');


    console.log('📄 Page loaded with params:', { status, transaksiId });

    // 🔁 Jalankan update status jika pembayaran berhasil
    if (status === 'success' && transaksiId) {
        updateStatus(transaksiId, 'success', 'midtrans')
        .then(() => {
            showNotification('✅ Pembayaran berhasil! Tiket Anda sudah aktif.', 'success', () => {
                window.location.href = '/transaksi';
            });
        })
        .catch(() => {
            showError('❌ Gagal mengupdate status transaksi');
            window.location.href = '/transaksi';
        });
        window.history.replaceState({}, document.title, window.location.pathname);
    }

    // 🔁 Jika masih pending
    else if (status === 'pending' && transaksiId) {
        updateStatus(transaksiId, 'pending', 'midtrans')
        .then(() => {
            showNotification('⏳ Pembayaran sedang diproses.', 'warning', () => {
                window.location.href = '/transaksi';
            });
        })
        .catch(() => {
            showError('❌ Gagal mengupdate status transaksi');
            window.location.href = '/transaksi';
        });
        window.history.replaceState({}, document.title, window.location.pathname);
    }

    // 🔁 Jika popup ditutup atau gagal
    else if (status === 'closed') {
        showNotification('ℹ️ Popup pembayaran ditutup. Silakan cek status transaksi Anda.', 'info', () => {
            window.location.href = '/transaksi';
        });
        window.history.replaceState({}, document.title, window.location.pathname);
    }

    // 🔄 Fungsi update status transaksi ke server
    function updateStatus(transaksiId, status, metode) {
        console.log('🔄 Updating status...', { transaksiId, status, metode });
        return fetch(`/transaksi/${transaksiId}/update-status`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                status: status,
                metode_pembayaran: metode || 'manual'
            })
        })
        .then(response => response.json())
        .then(result => {
            console.log('✅ Update result:', result);
            if (!result.success) throw new Error(result.message || 'Update failed');
            return result;
        });
    }

    // 🔔 Notifikasi error kecil
    function showError(message) {
        const errAlert = document.createElement('div');
        errAlert.className = 'fixed top-6 right-6 bg-red-500 text-white px-4 py-2 rounded-xl shadow-lg transition-all duration-300 z-50';
        errAlert.textContent = message;
        document.body.appendChild(errAlert);
        setTimeout(() => errAlert.remove(), 2500);
    }

    // 🔔 Notifikasi modal dengan callback
    function showNotification(message, type = 'success', onClose = null) {
        const bgColors = {
            success: 'bg-green-500',
            warning: 'bg-yellow-500',
            info: 'bg-blue-500',
            error: 'bg-red-500'
        };
        const icons = {
            success: '✅',
            warning: '⏳',
            info: 'ℹ️',
            error: '❌'
        };
        const bgColor = bgColors[type] || 'bg-gray-500';

        const backdrop = document.createElement('div');
        backdrop.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
        backdrop.style.animation = 'fadeIn 0.3s ease-out';

        const modal = document.createElement('div');
        modal.className = 'bg-white rounded-2xl shadow-2xl p-6 max-w-md mx-4 transform transition-all duration-300';
        modal.style.animation = 'slideIn 0.3s ease-out';

        modal.innerHTML = `
            <div class="text-center">
                <div class="text-5xl mb-4">${icons[type]}</div>
                <p class="text-gray-800 text-lg mb-6">${message}</p>
                <button id="closeNotif" class="px-8 py-2 ${bgColor} text-white rounded-full font-semibold hover:opacity-90 transition duration-200 shadow-lg">
                    OK
                </button>
            </div>
        `;
        backdrop.appendChild(modal);
        document.body.appendChild(backdrop);

        modal.querySelector('#closeNotif').addEventListener('click', () => {
            backdrop.style.opacity = '0';
            setTimeout(() => {
                backdrop.remove();
                if (onClose) onClose();
            }, 300);
        });

        backdrop.addEventListener('click', (e) => {
            if (e.target === backdrop) {
                backdrop.style.opacity = '0';
                setTimeout(() => {
                    backdrop.remove();
                    if (onClose) onClose();
                }, 300);
            }
        });
    }

    // 🔧 Tambahkan animasi CSS
    const style = document.createElement('style');
    style.textContent = `
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes slideIn {
            from { transform: scale(0.9) translateY(-20px); opacity: 0; }
            to { transform: scale(1) translateY(0); opacity: 1; }
        }
    `;
    document.head.appendChild(style);
});
</script>


</body>
</html>