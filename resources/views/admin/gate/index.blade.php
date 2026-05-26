@extends('layouts.admin')

@section('title', 'Validasi Gerbang (Scanner)')

@section('content')
<div class="max-w-3xl mx-auto" x-data="scannerApp()">
    
    <div class="bg-dark-900 border border-dark-800 rounded-3xl p-6 md:p-8 shadow-xl">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-white mb-2">Scanner E-Ticket</h2>
            <p class="text-dark-400">Gunakan scanner QR Code atau masukkan kode tiket secara manual untuk memvalidasi tiket masuk penonton.</p>
        </div>

        <!-- Manual Input Form -->
        <div class="mb-10 max-w-md mx-auto">
            <form @submit.prevent="validateTicket" class="flex gap-2">
                <input type="text" x-model="ticketCode" placeholder="Contoh: PRSK-A1B2C3D4" class="input-field uppercase font-mono font-bold tracking-widest text-center" required :disabled="loading">
                <button type="submit" class="btn-primary px-6" :disabled="loading">
                    <span x-show="!loading">Cek</span>
                    <span x-show="loading" class="animate-spin w-5 h-5 border-2 border-white border-t-transparent rounded-full"></span>
                </button>
            </form>
            <p class="text-xs text-dark-500 text-center mt-2">Untuk simulasi, masukkan kode E-Ticket dari riwayat pesanan.</p>
        </div>

        <!-- Result Card -->
        <div x-show="result" x-transition class="border rounded-2xl p-6" :class="resultClass">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0" :class="iconBgClass">
                    <svg x-show="isSuccess" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    <svg x-show="!isSuccess" class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-bold mb-1" :class="textClass" x-text="message"></h3>
                    
                    <div x-show="ticketData" class="mt-4 bg-dark-950/50 rounded-xl p-4 border border-dark-800/50">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="block text-dark-400 text-xs uppercase mb-1">Kode Tiket</span>
                                <span class="text-white font-mono font-bold" x-text="ticketData?.ticket_code"></span>
                            </div>
                            <div>
                                <span class="block text-dark-400 text-xs uppercase mb-1">Pemegang Tiket</span>
                                <span class="text-white font-semibold" x-text="ticketData?.holder_name"></span>
                            </div>
                            <div class="col-span-2">
                                <span class="block text-dark-400 text-xs uppercase mb-1">Pertandingan</span>
                                <span class="text-white font-semibold" x-text="ticketData?.match"></span>
                                <span class="text-dark-400 ml-1" x-text="ticketData?.match_date"></span>
                            </div>
                            <div class="col-span-2">
                                <span class="block text-dark-400 text-xs uppercase mb-1">Kategori Tribun</span>
                                <span class="text-primary-400 font-bold text-lg" x-text="ticketData?.category"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Waiting State -->
        <div x-show="!result && !loading" class="text-center py-12 border-2 border-dashed border-dark-800 rounded-2xl">
            <svg class="w-12 h-12 text-dark-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
            </svg>
            <p class="text-dark-400">Menunggu input kode tiket...</p>
        </div>
    </div>
</div>

<script>
function scannerApp() {
    return {
        ticketCode: '',
        loading: false,
        result: false,
        isSuccess: false,
        message: '',
        ticketData: null,
        
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

        async validateTicket() {
            if (!this.ticketCode) return;
            
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
                
                if (this.isSuccess) {
                    // Clear input on success
                    this.ticketCode = '';
                }
            } catch (error) {
                this.isSuccess = false;
                this.message = 'Terjadi kesalahan sistem. Coba lagi.';
                this.ticketData = null;
                this.result = true;
            } finally {
                this.loading = false;
            }
        }
    }
}
</script>
@endsection
