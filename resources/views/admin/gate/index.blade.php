@extends('layouts.admin')

@section('title', 'Validasi Gerbang (Scanner)')

@section('content')
<!-- Include HTML5-QRCode Library -->
<script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<div class="max-w-3xl mx-auto" x-data="scannerApp()">
    <div class="bg-dark-900 border border-dark-800 rounded-3xl p-6 md:p-8 shadow-xl">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-white mb-2">Scanner E-Ticket</h2>
            <p class="text-dark-400">Pindai QR Code tiket menggunakan kamera mobile atau masukkan kode secara manual untuk validasi.</p>
        </div>

        <!-- Navigation Tabs -->
        <div class="flex border-b border-dark-800 mb-8 max-w-md mx-auto">
            <button @click="switchTab('camera')" 
                    :class="activeTab === 'camera' ? 'border-primary-500 text-white font-bold' : 'border-transparent text-dark-400 hover:text-white'"
                    class="flex-1 py-3 text-center border-b-2 font-medium transition-all focus:outline-none cursor-pointer">
                <span class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Kamera Scan
                </span>
            </button>
            <button @click="switchTab('manual')" 
                    :class="activeTab === 'manual' ? 'border-primary-500 text-white font-bold' : 'border-transparent text-dark-400 hover:text-white'"
                    class="flex-1 py-3 text-center border-b-2 font-medium transition-all focus:outline-none cursor-pointer">
                <span class="flex items-center justify-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                    Input Manual
                </span>
            </button>
        </div>

        <!-- Camera Tab View -->
        <div x-show="activeTab === 'camera'" class="mb-8 flex flex-col items-center animate-fade-in">
            <!-- Camera Viewport -->
            <div class="relative w-full max-w-md aspect-square rounded-2xl overflow-hidden border border-dark-800 bg-dark-950 shadow-2xl mb-6 flex flex-col items-center justify-center">
                <!-- html5-qrcode reader div -->
                <div id="reader" class="absolute inset-0 w-full h-full" x-show="scannerActive"></div>
                
                <!-- Initial State (Inactive) -->
                <div x-show="!scannerActive" class="text-center p-6 z-10 flex flex-col items-center justify-center">
                    <div class="w-16 h-16 bg-dark-900 border border-dark-800 rounded-full flex items-center justify-center mb-4 text-dark-400">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-white mb-2">Kamera Belum Aktif</h3>
                    <p class="text-sm text-dark-400 mb-6 max-w-xs">Izinkan akses kamera untuk memindai QR code tiket secara langsung.</p>
                    
                    <button @click="startScanner()" class="btn-primary flex items-center gap-2 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Aktifkan Kamera
                    </button>
                </div>
                
                <!-- Error Permission Message -->
                <div x-show="errorMessage" class="absolute inset-0 bg-dark-950/95 flex flex-col items-center justify-center p-6 text-center z-20">
                    <svg class="w-12 h-12 text-primary-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <h4 class="text-white font-bold mb-2">Akses Kamera Bermasalah</h4>
                    <p class="text-sm text-dark-400 mb-4 max-w-xs" x-text="errorMessage"></p>
                    <button @click="startScanner()" class="btn-secondary btn-sm cursor-pointer">Coba Lagi</button>
                </div>

                <!-- Custom Scan Overlay (Visible only when scanning) -->
                <div x-show="scannerActive && !loading && !result" class="absolute inset-0 pointer-events-none flex flex-col justify-between p-6 z-10">
                    <!-- Scanner reticle corners -->
                    <div class="absolute inset-0 border-2 border-transparent">
                        <div class="absolute top-10 left-10 w-10 h-10 border-t-4 border-l-4 border-success-500 rounded-tl-lg"></div>
                        <div class="absolute top-10 right-10 w-10 h-10 border-t-4 border-r-4 border-success-500 rounded-tr-lg"></div>
                        <div class="absolute bottom-10 left-10 w-10 h-10 border-b-4 border-l-4 border-success-500 rounded-bl-lg"></div>
                        <div class="absolute bottom-10 right-10 w-10 h-10 border-b-4 border-r-4 border-success-500 rounded-br-lg"></div>
                    </div>
                    
                    <!-- Glowing Scanning Laser Line -->
                    <div class="absolute left-10 right-10 h-0.5 bg-gradient-to-r from-transparent via-success-500 to-transparent shadow-[0_0_10px_#22c55e] animate-scan z-10"></div>
                </div>

                <!-- Camera Loading / processing state -->
                <div x-show="loading" class="absolute inset-0 bg-dark-950/75 flex flex-col items-center justify-center p-6 text-center z-20">
                    <span class="animate-spin w-10 h-10 border-4 border-primary-500 border-t-transparent rounded-full mb-3"></span>
                    <p class="text-sm text-dark-300">Memproses validasi tiket...</p>
                </div>

                <!-- Camera Result Overlay (shows countdown or status) -->
                <div x-show="result && !loading && autoResume && resumeCountdown > 0" 
                     class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-dark-900/90 border border-dark-700/50 backdrop-blur-md px-4 py-2 rounded-full flex items-center gap-2 text-xs font-semibold text-white shadow-xl z-20">
                    <span class="w-2 h-2 rounded-full bg-primary-500 animate-ping"></span>
                    Scan berikutnya dalam <span class="text-primary-400 font-bold" x-text="resumeCountdown"></span>s...
                </div>
            </div>

            <!-- Camera Controls -->
            <div x-show="scannerActive" class="w-full max-w-md space-y-4">
                <!-- Camera Select Dropdown -->
                <div class="flex items-center gap-3 bg-dark-900/50 border border-dark-800 px-4 py-3 rounded-xl">
                    <svg class="w-5 h-5 text-dark-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 7.89H17v4.18H21.21z"></path>
                    </svg>
                    <select x-model="selectedCameraId" @change="changeCamera($event.target.value)" class="bg-transparent text-sm text-white w-full focus:outline-none font-medium cursor-pointer">
                        <template x-for="camera in cameras" :key="camera.id">
                            <option :value="camera.id" x-text="camera.label || 'Kamera ' + (cameras.indexOf(camera) + 1)" class="bg-dark-900 text-white"></option>
                        </template>
                    </select>
                </div>

                <!-- Auto Resume Option & Stop Button -->
                <div class="flex items-center justify-between gap-4">
                    <label class="flex items-center gap-2 cursor-pointer text-sm text-dark-300 hover:text-white select-none">
                        <input type="checkbox" x-model="autoResume" class="w-4 h-4 rounded border-dark-700 bg-dark-950 text-primary-600 focus:ring-primary-500 cursor-pointer">
                        Auto-scan selanjutnya
                    </label>

                    <button @click="stopScanner()" class="btn-secondary py-2 px-4 text-xs font-bold border border-primary-500/30 text-primary-400 hover:bg-primary-950/20 flex items-center gap-1 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                        </svg>
                        Tutup Kamera
                    </button>
                </div>

                <!-- Controls after scan (if autoResume is disabled or to resume manually) -->
                <div x-show="result" class="flex gap-2">
                    <button @click="resumeScanning()" class="w-full btn-primary py-2.5 text-sm font-bold flex items-center justify-center gap-2 shadow-success-500/20 bg-success-600 hover:bg-success-700 cursor-pointer">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 7.89H17v4.18H21.21z"></path>
                        </svg>
                        Scan Tiket Lainnya
                    </button>
                </div>
            </div>
        </div>

        <!-- Manual Input Tab View -->
        <div x-show="activeTab === 'manual'" class="mb-10 max-w-md mx-auto animate-fade-in">
            <form @submit.prevent="validateManualTicket" class="flex gap-2">
                <input type="text" x-model="ticketCode" placeholder="Contoh: PRSK-A1B2C3D4" class="input-field uppercase font-mono font-bold tracking-widest text-center" required :disabled="loading">
                <button type="submit" class="btn-primary px-6 cursor-pointer" :disabled="loading">
                    <span x-show="!loading">Cek</span>
                    <span x-show="loading" class="animate-spin w-5 h-5 border-2 border-white border-t-transparent rounded-full"></span>
                </button>
            </form>
            <p class="text-xs text-dark-500 text-center mt-2">Untuk simulasi, masukkan kode E-Ticket dari riwayat pesanan.</p>
        </div>

        <!-- Result Card (Unified for both methods) -->
        <div x-show="result" x-transition class="border rounded-2xl p-6 mb-6" :class="resultClass">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0" :class="iconBgClass">
                    <svg x-show="isSuccess" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <svg x-show="!isSuccess" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold mb-1" :class="textClass" x-text="message"></h3>
                    
                    <div x-show="ticketData" class="mt-4 bg-dark-950/50 rounded-xl p-4 border border-dark-800/50 text-left">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="block text-dark-400 text-xs uppercase mb-1">Kode Tiket</span>
                                <span class="text-white font-mono font-bold" x-text="ticketData?.ticket_code"></span>
                            </div>
                            <div>
                                <span class="block text-dark-400 text-xs uppercase mb-1">Pemegang Tiket</span>
                                <span class="text-white font-semibold" x-text="ticketData?.holder_name"></span>
                            </div>
                            <div class="col-span-2 text-left">
                                <span class="block text-dark-400 text-xs uppercase mb-1">Pertandingan</span>
                                <span class="text-white font-semibold" x-text="ticketData?.match"></span>
                                <span class="text-dark-400 ml-1 text-xs" x-text="ticketData?.match_date"></span>
                            </div>
                            <div class="col-span-2 text-left">
                                <span class="block text-dark-400 text-xs uppercase mb-1">Kategori Tribun</span>
                                <span class="text-primary-400 font-bold text-lg" x-text="ticketData?.category"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Waiting State -->
        <div x-show="!result && !loading && (!scannerActive || activeTab === 'manual')" class="text-center py-12 border-2 border-dashed border-dark-800 rounded-2xl">
            <svg class="w-12 h-12 text-dark-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
            </svg>
            <p class="text-dark-400" x-text="activeTab === 'camera' ? 'Klik Aktifkan Kamera untuk memulai scanning...' : 'Menunggu input kode tiket...'"></p>
        </div>
    </div>
</div>

<style>
@keyframes scan {
    0% { top: 10%; }
    50% { top: 90%; }
    100% { top: 10%; }
}
.animate-scan {
    animation: scan 2.5s ease-in-out infinite;
}
#reader video {
    object-fit: cover !important;
}
/* Hide built-in clunky elements of HTML5-QRCode */
#reader img[alt="Info icon"] {
    display: none !important;
}
#reader div {
    border: none !important;
}
#reader #html5-qrcode-anchor-scan-type-change {
    display: none !important;
}
</style>

<script>
function scannerApp() {
    return {
        activeTab: 'camera',
        ticketCode: '',
        loading: false,
        result: false,
        isSuccess: false,
        message: '',
        ticketData: null,
        
        // Camera properties
        scannerActive: false,
        html5QrCode: null,
        cameras: [],
        selectedCameraId: '',
        autoResume: true,
        resumeCountdown: 0,
        countdownInterval: null,
        errorMessage: '',
        
        init() {
            // Unload safety
            window.addEventListener('beforeunload', () => {
                this.stopScanner();
            });
        },
        
        get resultClass() {
            return this.isSuccess 
                ? 'bg-success-500/10 border-success-500/30' 
                : 'bg-primary-500/10 border-primary-500/30';
        },
        get iconBgClass() {
            return this.isSuccess ? 'bg-success-500' : 'bg-primary-500';
        },
        get textClass() {
            return this.isSuccess ? 'text-success-500' : 'text-primary-500';
        },

        async switchTab(tab) {
            this.activeTab = tab;
            this.result = false;
            this.ticketCode = '';
            this.errorMessage = '';
            
            if (tab === 'camera') {
                // Do not auto-start camera, let user click to activate
            } else {
                await this.stopScanner();
            }
        },

        async startScanner() {
            this.errorMessage = '';
            this.result = false;
            this.loading = false;
            
            await this.stopScanner();
            
            try {
                this.html5QrCode = new Html5Qrcode("reader");
                
                // Fetch cameras list
                const devices = await Html5Qrcode.getCameras();
                this.cameras = devices || [];
                
                if (this.cameras.length === 0) {
                    throw new Error("Tidak ada kamera yang terdeteksi di perangkat Anda.");
                }
                
                // Decide camera
                let cameraId = localStorage.getItem('preferred_camera_id');
                if (cameraId && !this.cameras.some(c => c.id === cameraId)) {
                    cameraId = null;
                }
                
                if (!cameraId) {
                    // Default to back/rear camera if available
                    const backCamera = this.cameras.find(c => 
                        c.label.toLowerCase().includes('back') || 
                        c.label.toLowerCase().includes('rear') || 
                        c.label.toLowerCase().includes('environment') ||
                        c.label.toLowerCase().includes('belakang')
                    );
                    cameraId = backCamera ? backCamera.id : this.cameras[0].id;
                }
                
                this.selectedCameraId = cameraId;
                localStorage.setItem('preferred_camera_id', cameraId);
                
                this.scannerActive = true;
                
                // Configure scanner
                const config = {
                    fps: 15,
                    qrbox: (width, height) => {
                        const minEdge = Math.min(width, height);
                        const size = Math.floor(minEdge * 0.7);
                        return { width: size, height: size };
                    },
                    aspectRatio: 1.0
                };
                
                await this.html5QrCode.start(
                    cameraId,
                    config,
                    (decodedText) => this.onScanSuccess(decodedText),
                    (errorMessage) => {
                        // ignore noise / scanner search frame logs
                    }
                );
            } catch (err) {
                console.error("Gagal memulai scanner:", err);
                this.errorMessage = err.message || "Gagal mendapatkan izin akses kamera. Pastikan situs ini diizinkan untuk mengakses kamera pada browser Anda.";
                this.scannerActive = false;
                if (this.html5QrCode) {
                    try {
                        this.html5QrCode.clear();
                    } catch (e) {}
                    this.html5QrCode = null;
                }
            }
        },

        async changeCamera(cameraId) {
            if (!cameraId || cameraId === this.selectedCameraId) return;
            this.selectedCameraId = cameraId;
            localStorage.setItem('preferred_camera_id', cameraId);
            if (this.scannerActive) {
                await this.startScanner();
            }
        },

        async stopScanner() {
            this.clearCountdown();
            if (this.html5QrCode && this.html5QrCode.isScanning) {
                try {
                    await this.html5QrCode.stop();
                } catch (err) {
                    console.error("Error stopping scanner:", err);
                }
            }
            if (this.html5QrCode) {
                try {
                    this.html5QrCode.clear();
                } catch (e) {}
                this.html5QrCode = null;
            }
            this.scannerActive = false;
        },

        async onScanSuccess(decodedText) {
            if (this.loading) return;
            
            // Pause scanner immediately so it doesn't fire multiple API calls
            if (this.html5QrCode && this.html5QrCode.isScanning) {
                try {
                    this.html5QrCode.pause(true);
                } catch (e) {
                    console.error("Error pausing scanner:", e);
                }
            }
            
            this.ticketCode = decodedText;
            await this.validateTicket(true);
        },

        async validateManualTicket() {
            await this.validateTicket(false);
        },

        async validateTicket(isFromCamera = false) {
            if (!this.ticketCode) {
                if (isFromCamera) {
                    this.resumeScanning();
                }
                return;
            }
            
            this.loading = true;
            this.result = false;
            
            try {
                const response = await fetch('{{ route("admin.gate.validate") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ ticket_code: this.ticketCode.toUpperCase() })
                });
                
                const data = await response.json();
                
                this.isSuccess = data.success;
                this.message = data.message;
                this.ticketData = data.ticket || null;
                this.result = true;
                
                // Play audio feedback
                this.playBeep(this.isSuccess ? 'success' : 'error');
                
                if (this.isSuccess) {
                    this.ticketCode = '';
                }
                
                if (isFromCamera) {
                    if (this.autoResume) {
                        this.startCountdownAndResume();
                    }
                }
            } catch (error) {
                this.isSuccess = false;
                this.message = 'Terjadi kesalahan sistem. Coba lagi.';
                this.ticketData = null;
                this.result = true;
                
                this.playBeep('error');
                
                if (isFromCamera && this.autoResume) {
                    this.startCountdownAndResume();
                }
            } finally {
                this.loading = false;
            }
        },

        startCountdownAndResume() {
            this.clearCountdown();
            this.resumeCountdown = 3;
            this.countdownInterval = setInterval(() => {
                this.resumeCountdown--;
                if (this.resumeCountdown <= 0) {
                    this.clearCountdown();
                    this.resumeScanning();
                }
            }, 1000);
        },
        
        clearCountdown() {
            if (this.countdownInterval) {
                clearInterval(this.countdownInterval);
                this.countdownInterval = null;
            }
            this.resumeCountdown = 0;
        },
        
        resumeScanning() {
            this.clearCountdown();
            this.result = false;
            if (this.html5QrCode && this.html5QrCode.isScanning) {
                try {
                    this.html5QrCode.resume();
                } catch (e) {
                    console.error("Error resuming scanner:", e);
                }
            }
        },

        playBeep(type = 'success') {
            try {
                const AudioContextClass = window.AudioContext || window.webkitAudioContext;
                if (!AudioContextClass) return;
                
                const audioCtx = new AudioContextClass();
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                
                if (type === 'success') {
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(880, audioCtx.currentTime); // A5 note
                    gain.gain.setValueAtTime(0.08, audioCtx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.15);
                    osc.start();
                    osc.stop(audioCtx.currentTime + 0.15);
                } else {
                    osc.type = 'sawtooth';
                    osc.frequency.setValueAtTime(150, audioCtx.currentTime); // Low buzz
                    gain.gain.setValueAtTime(0.12, audioCtx.currentTime);
                    gain.gain.exponentialRampToValueAtTime(0.001, audioCtx.currentTime + 0.35);
                    osc.start();
                    osc.stop(audioCtx.currentTime + 0.35);
                }
            } catch (e) {
                console.error("Gagal memutar audio feedback:", e);
            }
        }
    }
}
</script>
@endsection
