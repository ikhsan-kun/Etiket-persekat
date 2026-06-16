<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verifikasi Dokumen Digital - Persekat Tegal</title>
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #030712; /* Tailwind gray-950 */
        }
        @keyframes pulse-glowing {
            0%, 100% {
                box-shadow: 0 0 20px 2px rgba(16, 185, 129, 0.15);
                border-color: rgba(16, 185, 129, 0.3);
            }
            50% {
                box-shadow: 0 0 30px 8px rgba(16, 185, 129, 0.3);
                border-color: rgba(16, 185, 129, 0.6);
            }
        }
        @keyframes pulse-glowing-error {
            0%, 100% {
                box-shadow: 0 0 20px 2px rgba(239, 68, 68, 0.15);
                border-color: rgba(239, 68, 68, 0.3);
            }
            50% {
                box-shadow: 0 0 30px 8px rgba(239, 68, 68, 0.3);
                border-color: rgba(239, 68, 68, 0.6);
            }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }
        .glowing-valid {
            animation: pulse-glowing 3s infinite ease-in-out;
        }
        .glowing-error {
            animation: pulse-glowing-error 3s infinite ease-in-out;
        }
        .float-card {
            animation: float 6s infinite ease-in-out;
        }
        .ticket-dashed-line {
            background-image: linear-gradient(to right, rgba(75, 85, 99, 0.4) 50%, rgba(255, 255, 255, 0) 0%);
            background-position: bottom;
            background-size: 15px 1px;
            background-repeat: repeat-x;
        }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col justify-between relative overflow-x-hidden">

    <!-- Premium Ambient Lighting Backgrounds -->
    <div class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-primary-600/5 rounded-full blur-[140px] pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-success-600/5 rounded-full blur-[140px] pointer-events-none"></div>

    <!-- Portal Header (Standalone, Minimalist) -->
    <header class="w-full py-6 border-b border-dark-800/40 bg-dark-950/80 backdrop-blur-md z-20">
        <div class="max-w-7xl mx-auto px-6 flex items-center justify-between">
            <a href="/" class="flex items-center gap-3 group">
                <img src="{{ asset('logo.png') }}" alt="Persekat Logo" class="h-10 w-10 object-contain rounded-full border border-dark-700">
                <div>
                    <span class="block text-sm font-black font-display text-white uppercase tracking-wider">Persekat Tegal</span>
                    <span class="block text-[9px] font-bold text-primary-500 uppercase tracking-widest">Digital Document Portal</span>
                </div>
            </a>
            
            <div class="flex items-center gap-2 bg-dark-900 border border-dark-800 rounded-full px-3 py-1.5">
                <span class="w-2 h-2 rounded-full bg-success-500 animate-pulse"></span>
                <span class="text-[10px] font-bold text-dark-300 uppercase tracking-wider">Enkripsi SSL Aktif</span>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow flex items-center justify-center py-16 px-4 z-10" x-data="{ copied: false }">
        <div class="max-w-2xl w-full">
            <!-- Glassmorphism Card -->
            <div class="bg-dark-900/60 backdrop-blur-2xl border border-dark-800/80 rounded-[32px] p-8 md:p-10 shadow-2xl relative overflow-hidden float-card {{ $isValid ? 'glowing-valid' : 'glowing-error' }}">
                
                <!-- Security line pattern -->
                <div class="absolute inset-0 bg-[linear-gradient(45deg,rgba(255,255,255,0.005)_25%,transparent_25%,transparent_50%,rgba(255,255,255,0.005)_50%,rgba(255,255,255,0.005)_75%,transparent_75%,transparent)] bg-[length:30px_30px] opacity-40 pointer-events-none"></div>

                @if($isValid)
                    <!-- ==================== VALID STATE ==================== -->
                    <div class="flex flex-col items-center text-center">
                        <!-- Rotating digital seal -->
                        <div class="relative w-32 h-32 mb-6 flex items-center justify-center">
                            <div class="absolute inset-0 border-2 border-dashed border-success-500/30 rounded-full animate-[spin_45s_linear_infinite]"></div>
                            <div class="absolute w-24 h-24 bg-success-500/10 rounded-full flex items-center justify-center"></div>
                            
                            <div class="relative w-16 h-16 bg-success-500 rounded-full flex items-center justify-center shadow-lg border-4 border-dark-900">
                                <svg class="w-8 h-8 text-dark-950" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            
                            <svg class="absolute w-full h-full animate-[spin_25s_linear_infinite]" viewBox="0 0 100 100">
                                <path id="sealPath" d="M 50,50 m -37,0 a 37,37 0 1,1 74,0 a 37,37 0 1,1 -74,0" fill="none"></path>
                                <text class="fill-success-500/60 font-bold uppercase tracking-[0.16em] text-[7.2px]">
                                    <textPath href="#sealPath" startOffset="50%" text-anchor="middle">
                                        • VERIFIED ORIGINAL DOCUMENT • TEGAL E-TICKET
                                    </textPath>
                                </text>
                            </svg>
                        </div>

                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-black bg-success-500/10 text-success-400 border border-success-500/20 uppercase tracking-[0.15em] mb-4">
                            <span class="w-2.5 h-2.5 rounded-full bg-success-500 animate-[ping_1.5s_infinite]"></span>
                            Dokumen Sah & Terverifikasi
                        </span>

                        <h1 class="text-3xl md:text-4xl font-extrabold text-white font-display tracking-tight mb-2">Tanda Tangan Valid</h1>
                        <p class="text-dark-400 text-sm max-w-md mx-auto leading-relaxed mb-8">
                            Dokumen digital ini dinyatakan otentik dan bersumber dari basis data resmi e-ticket Persekat Tegal.
                        </p>
                    </div>

                    <!-- Certificate Metadata -->
                    <div class="bg-dark-950/70 border border-dark-800 rounded-2xl overflow-hidden shadow-inner mb-8">
                        <div class="bg-dark-900/80 px-6 py-4 border-b border-dark-800 flex justify-between items-center">
                            <span class="text-[10px] font-bold text-dark-400 uppercase tracking-widest">Sertifikat Laporan Penjualan</span>
                            <span class="text-[9px] font-mono text-success-500 font-bold bg-success-500/10 px-2.5 py-1 rounded-md">SECURE SIGN</span>
                        </div>

                        <div class="p-6 space-y-4">
                            <!-- Row 1 -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <span class="text-[10px] text-dark-500 uppercase tracking-wider block font-bold mb-1">Tipe Laporan</span>
                                    <div class="flex items-center gap-2 text-white">
                                        <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span class="font-bold text-sm">Laporan Penjualan Tiket</span>
                                    </div>
                                </div>
                                <div>
                                    <span class="text-[10px] text-dark-500 uppercase tracking-wider block font-bold mb-1">Penandatangan Resmi</span>
                                    <div class="flex items-center gap-2 text-white">
                                        <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        <span class="font-bold text-sm">{{ $signee ?? 'Leiskiroh' }} (Bendahara)</span>
                                    </div>
                                </div>
                            </div>

                            <div class="ticket-dashed-line h-px w-full my-2"></div>

                            <!-- Row 2 -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <span class="text-[10px] text-dark-500 uppercase tracking-wider block font-bold mb-1">Periode Laporan</span>
                                    <div class="flex items-center gap-2 text-white">
                                        <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                        <span class="font-bold text-sm">
                                            {{ $dateFrom ? date('d M Y', strtotime($dateFrom)) : '-' }} s.d. {{ $dateTo ? date('d M Y', strtotime($dateTo)) : '-' }}
                                        </span>
                                    </div>
                                </div>
                                <div>
                                    <span class="text-[10px] text-dark-500 uppercase tracking-wider block font-bold mb-1">Dicetak Oleh</span>
                                    <div class="flex items-center gap-2 text-white">
                                        <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                        <span class="font-bold text-sm">{{ $printedBy ?? 'Sistem' }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="ticket-dashed-line h-px w-full my-2"></div>

                            <!-- Row 3 -->
                            <div>
                                <span class="text-[10px] text-dark-500 uppercase tracking-wider block font-bold mb-3">Statistik Summary</span>
                                <div class="grid grid-cols-3 gap-3">
                                    <div class="text-center bg-dark-900 border border-dark-800 p-4 rounded-xl">
                                        <span class="text-[9px] text-dark-500 uppercase tracking-wider block font-semibold mb-1">Pendapatan</span>
                                        <span class="text-success-400 font-black text-xs sm:text-sm block truncate">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="text-center bg-dark-900 border border-dark-800 p-4 rounded-xl">
                                        <span class="text-[9px] text-dark-500 uppercase tracking-wider block font-semibold mb-1">Tiket</span>
                                        <span class="text-white font-black text-xs sm:text-sm block">{{ number_format($totalTickets, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="text-center bg-dark-900 border border-dark-800 p-4 rounded-xl">
                                        <span class="text-[9px] text-dark-500 uppercase tracking-wider block font-semibold mb-1">Pesanan</span>
                                        <span class="text-white font-black text-xs sm:text-sm block">{{ number_format($totalOrders, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                @else
                    <!-- ==================== INVALID STATE ==================== -->
                    <div class="flex flex-col items-center text-center">
                        <div class="relative w-32 h-32 mb-6 flex items-center justify-center">
                            <div class="absolute inset-0 border-2 border-dashed border-primary-500/30 rounded-full"></div>
                            <div class="absolute w-24 h-24 bg-primary-500/10 rounded-full flex items-center justify-center"></div>
                            <div class="relative w-16 h-16 bg-primary-600 rounded-full flex items-center justify-center shadow-lg border-4 border-dark-900">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                        </div>

                        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-xs font-black bg-primary-500/10 text-primary-400 border border-primary-500/20 uppercase tracking-[0.15em] mb-4">
                            Verifikasi Gagal
                        </span>

                        <h1 class="text-3xl md:text-4xl font-extrabold text-white font-display tracking-tight mb-2">Dokumen Tidak Valid</h1>
                        <p class="text-dark-400 text-sm max-w-md mx-auto leading-relaxed mb-8">
                            Tanda tangan digital tidak valid, kedaluwarsa, atau parameter laporan telah dimodifikasi. Pastikan Anda memindai kode QR dari dokumen laporan yang asli dan belum diubah.
                        </p>
                    </div>
                @endif

                <!-- Navigation & Share Actions -->
                <div class="mt-8 flex flex-col sm:flex-row justify-center items-center gap-4">
                    <a href="/" class="w-full sm:w-auto text-center px-6 py-3.5 bg-dark-850 hover:bg-dark-800 text-white font-bold text-sm rounded-2xl transition-all border border-dark-700 hover:border-dark-600 shadow-md">
                        Kembali ke Home
                    </a>
                    
                    @if($isValid)
                    <button @click="navigator.clipboard.writeText(window.location.href); copied = true; setTimeout(() => copied = false, 2000)" 
                            class="w-full sm:w-auto text-center px-6 py-3.5 bg-dark-800 hover:bg-dark-750 text-dark-300 hover:text-white font-bold text-sm rounded-2xl transition-all border border-dark-700 hover:border-dark-600 shadow-md flex items-center justify-center gap-2">
                        <svg x-show="!copied" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                        <svg x-show="copied" class="w-4 h-4 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        <span x-text="copied ? 'Tautan Disalin!' : 'Salin Tautan'"></span>
                    </button>
                    
                    <a href="javascript:window.print();" class="w-full sm:w-auto text-center px-6 py-3.5 bg-primary-600 hover:bg-primary-500 text-white font-bold text-sm rounded-2xl transition-all shadow-lg shadow-primary-600/20 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                        Cetak Sertifikat
                    </a>
                    @endif
                </div>

            </div>
        </div>
    </main>

    <!-- Portal Footer -->
    <footer class="w-full py-6 border-t border-dark-800/40 bg-dark-950/80 backdrop-blur-md z-20 text-center">
        <p class="text-dark-500 text-xs">
            &copy; {{ date('Y') }} Persekat Tegal Digital Signature Verification System. Seluruh hak cipta dilindungi.
        </p>
    </footer>

</body>
</html>
