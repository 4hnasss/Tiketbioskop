<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket {{ $kursi }} - {{ $transaksi->jadwal->film->judul }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'navy': '#0A1D56',
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50 min-h-screen py-8">
    
    <!-- Control Buttons -->
    <div class="no-print max-w-3xl mx-auto px-4 mb-6">
        <div class="flex justify-between items-center">
            <a href="{{ route('detail-transaksi', $transaksi->id) }}" 
               class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-2.5 rounded-lg font-semibold flex items-center gap-2 transition text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
            
            <div class="flex gap-3">
                <button onclick="window.print()" 
                        class="bg-blue-700 hover:bg-blue-800 text-white px-5 py-2.5 rounded-lg font-semibold flex items-center gap-2 transition text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Print
                </button>
                <button onclick="downloadTicket()" 
                        class="bg-green-700 hover:bg-green-800 text-white px-5 py-2.5 rounded-lg font-semibold flex items-center gap-2 transition text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download PNG
                </button>
            </div>
        </div>
    </div>

    <!-- Ticket Container -->
    <div class="max-w-3xl mx-auto px-4">
        <div id="ticket" class="bg-yellow-50 shadow-xl" style="background-color: #F5E6D3;">
            
            <!-- Header -->
            <div class="border-b-2 border-dashed border-gray-400 px-8 py-4">
                <div class="flex justify-between items-center">
                    <div>
                        <p class="text-sm font-bold text-gray-700 mb-1" style="letter-spacing: 0.15em;">FLIXORA</p>
                    </div>
                    <div class="text-right">
                        <h1 class="text-2xl font-black text-gray-900 tracking-wider uppercase ">XORA</h1>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="grid grid-cols-2 divide-x-2 divide-dashed divide-gray-400">
                
                <!-- Left Side -->
                <div class="px-8 py-6 space-y-4">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900 mb-2 uppercase">
                            {{ $transaksi->jadwal->film->judul }}
                        </h2>
                    </div>

                    <div class="space-y-2 text-sm font-mono">
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-700">DATE</span>
                            <span class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($transaksi->jadwal->tanggal)->format('d-M-Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-700">TIME</span>
                            <span class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($transaksi->jadwal->jamtayang)->format('H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-700">ROW</span>
                            <span class="font-bold text-gray-900">{{ substr($kursi, 0, 1) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-700">SEAT</span>
                            <span class="font-bold text-gray-900">{{ substr($kursi, 1) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-700">THEATRE</span>
                            <span class="font-bold text-gray-900">{{ $transaksi->jadwal->studio->nama_studio }}</span>
                        </div>
                    </div>

                    <div class="pt-4 space-y-1 text-sm font-mono">
                        <div class="flex justify-between">
                            <span class="font-bold text-gray-900">Rp {{ number_format($hargaPerKursi, 0, ',', '.') }}</span>
                            <span class="font-semibold text-gray-700">CASH</span>
                        </div>
                        <div>
                            <p class="text-xs font-bold text-gray-900">{{ $transaksi->id }}{{ $kursi }}-{{ $transaksi->user->name ?? 'GUEST' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">INCL. TAX</p>
                            <p class="text-xs font-semibold text-gray-700">{{ now()->format('Y-m-d H:i:s') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Right Side -->
                <div class="px-8 py-6 flex flex-col justify-between relative">
                    <!-- Watermark X -->
                    <div class="absolute inset-0 flex items-center justify-center opacity-10 pointer-events-none">
                        <svg class="w-80 h-64" viewBox="0 0 100 100">
                            <text x="50" y="50" font-size="45" font-weight="bold" text-anchor="middle" dominant-baseline="middle" fill="#0A1D56">XORA</text>
                        </svg>
                    </div>

                    <div class="relative z-10">
                        <h2 class="text-xl font-bold text-gray-900 mb-4 uppercase">
                            {{ $transaksi->jadwal->film->judul }}
                        </h2>

                        <div class="space-y-2 text-sm font-mono">
                            <div>
                                <span class="font-semibold text-gray-700 mr-2">DATE</span>
                                <span class="text-gray-500">:</span>
                                <span class="font-bold text-gray-900 ml-2">{{ \Carbon\Carbon::parse($transaksi->jadwal->tanggal)->format('D, d-M') }}</span>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-700 mr-2">TIME</span>
                                <span class="text-gray-500">:</span>
                                <span class="font-bold text-gray-900 ml-2">{{ \Carbon\Carbon::parse($transaksi->jadwal->jamtayang)->format('H:i') }}</span>
                            </div>
                            <div>
                                <span class="font-semibold text-gray-700 mr-2">ROW</span>
                                <span class="text-gray-500">:</span>
                                <span class="font-bold text-gray-900 ml-2">{{ substr($kursi, 0, 1) }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Large Seat Number -->
                    <div class="relative z-10 text-right">
                        <p class="font-semibold text-gray-700 text-sm mb-1">SEAT :</p>
                        <p class="text-8xl font-black text-navy leading-none" style="color: #0A1D56;">{{ substr($kursi, 1) }}</p>
                    </div>

                    <div class="relative z-10 text-sm font-mono space-y-1">
                        <div class="flex justify-between">
                            <span class="font-semibold text-gray-700">PRICE</span>
                            <span class="font-bold text-gray-900">Rp {{ number_format($hargaPerKursi, 0, ',', '.') }} CASH</span>
                        </div>
                        <div>
                            <p class="text-xs text-gray-600">INCL. TAX</p>
                            <p class="text-xs font-bold text-gray-900">{{ $transaksi->id }}{{ $kursi }}-{{ $transaksi->user->name ?? 'GUEST' }}</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <style>
        @media print {
            body {
                margin: 0;
                padding: 0;
                background: white !important;
            }
            .no-print {
                display: none !important;
            }
            #ticket {
                box-shadow: none !important;
                max-width: 100%;
                page-break-inside: avoid;
            }
            @page {
                size: A5 landscape;
                margin: 0;
            }
        }
    </style>

    <script>
        async function downloadTicket() {
            const ticket = document.getElementById('ticket');
            const downloadBtn = event.target.closest('button');
            const originalHTML = downloadBtn.innerHTML;
            
            try {
                downloadBtn.innerHTML = `
                    <svg class="animate-spin w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <span>Processing...</span>
                `;
                downloadBtn.disabled = true;

                await new Promise(resolve => setTimeout(resolve, 500));

                const canvas = await html2canvas(ticket, {
                    backgroundColor: '#F5E6D3',
                    scale: 3,
                    logging: false,
                    useCORS: true
                });

                canvas.toBlob(function(blob) {
                    const url = URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    const filmTitle = '{{ str_replace([" ", "/", "\\", ":", "*", "?", "\"", "<", ">", "|"], "-", $transaksi->jadwal->film->judul) }}';
                    link.download = `Tiket-${filmTitle}-Kursi-{{ $kursi }}.png`;
                    link.href = url;
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    URL.revokeObjectURL(url);

                    downloadBtn.innerHTML = originalHTML;
                    downloadBtn.disabled = false;

                    showNotification('✓ Tiket berhasil didownload!', 'success');
                }, 'image/png', 1.0);

            } catch (error) {
                console.error('Download error:', error);
                downloadBtn.innerHTML = originalHTML;
                downloadBtn.disabled = false;
                showNotification('❌ Gagal download. Silakan gunakan Print.', 'error');
            }
        }

        function showNotification(message, type) {
            const bgColor = type === 'success' ? 'bg-green-600' : 'bg-red-600';
            const notification = document.createElement('div');
            notification.className = `no-print fixed top-4 left-1/2 transform -translate-x-1/2 px-6 py-3 rounded-lg shadow-xl text-white font-bold z-50 ${bgColor} transition-all duration-300`;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translate(-50%, -10px)';
                setTimeout(() => notification.remove(), 300);
            }, 2500);
        }

        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                downloadTicket();
            }
        });
    </script>

</body>
</html>