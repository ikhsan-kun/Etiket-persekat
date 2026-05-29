@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12" style="padding-top: 7rem;">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black font-display text-white mb-2">Pembayaran</h1>
            <p class="text-dark-300">Selesaikan pembayaran untuk mengamankan tiket Anda.</p>
        </div>
        <div class="hidden sm:flex items-center gap-2 text-sm">
            <span class="w-8 h-8 rounded-full bg-success-500 text-white flex items-center justify-center font-bold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </span>
            <span class="text-white font-medium">Pilih</span>
            <div class="w-8 h-px bg-success-500 mx-2"></div>
            <span class="w-8 h-8 rounded-full bg-primary-600 text-white flex items-center justify-center font-bold">2</span>
            <span class="text-white font-medium">Bayar</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Order Info -->
        <div class="space-y-6">
            <div class="bg-dark-900 border border-dark-800 rounded-3xl p-6 shadow-xl">
                <div class="flex justify-between items-start mb-6 border-b border-dark-800 pb-6">
                    <div>
                        <span class="text-dark-400 text-sm block mb-1">ID Pesanan</span>
                        <span class="text-white font-mono font-bold">{{ $order->order_number }}</span>
                    </div>
                    <div class="text-right">
                        <span class="text-dark-400 text-sm block mb-1">Status</span>
                        <span class="badge bg-warning-500/20 text-warning-500 border border-warning-500/20 animate-pulse">Menunggu Pembayaran</span>
                    </div>
                </div>

                <div class="space-y-4 mb-6">
                    @foreach($order->items as $item)
                        <div class="flex justify-between items-center text-sm">
                            <div class="text-white">
                                <span class="font-bold">{{ $item->quantity }}x</span> {{ $item->ticketCategory->name }}
                                <div class="text-dark-400 text-xs mt-1">Persekat vs {{ $item->ticketCategory->match->opponent }}</div>
                            </div>
                            <div class="text-white font-medium">
                                Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="border-t border-dark-800 pt-4 flex justify-between items-center">
                    <span class="text-white font-bold text-lg">Total Pembayaran</span>
                    <span class="text-primary-400 font-bold text-2xl">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Time Limit -->
            <div class="bg-dark-800/50 border border-dark-700/50 rounded-2xl p-6 flex items-center gap-4" x-data="timer('{{ $order->expired_at->toIso8601String() }}')">
                <div class="w-12 h-12 rounded-full bg-warning-500/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6 text-warning-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-1">Batas Waktu Pembayaran</h4>
                    <p class="text-warning-500 font-mono font-bold text-lg" x-text="timeDisplay">00:00:00</p>
                </div>
            </div>
        </div>

        <!-- Payment Actions -->
        <div>
            <div class="bg-dark-900 border border-dark-800 rounded-3xl p-6 shadow-xl sticky top-24">
                <h3 class="text-xl font-bold text-white mb-6">Pilih Metode Pembayaran</h3>
                
                @if(config('midtrans.server_key') && $order->midtrans_snap_token)
                    <button id="pay-button" class="btn-primary w-full py-4 text-lg mb-4">
                        Bayar Sekarang
                    </button>
                    <p class="text-center text-dark-400 text-sm">
                        Mendukung Virtual Account (BCA, Mandiri, BNI, dll), GoPay, ShopeePay, QRIS, dan Kartu Kredit.
                    </p>
                @elseif(config('midtrans.server_key') && !$order->midtrans_snap_token)
                    <div class="bg-warning-500/10 border border-warning-500/20 rounded-xl p-4 mb-6">
                        <p class="text-warning-400 text-sm">
                            <strong>Perhatian:</strong> Gagal mendapatkan token pembayaran dari Midtrans. Silakan muat ulang halaman ini.
                        </p>
                        <a href="{{ route('payment.show', $order) }}" class="btn-primary w-full py-3 mt-3">
                            Muat Ulang Halaman
                        </a>
                    </div>
                @else
                    <div class="bg-primary-500/10 border border-primary-500/20 rounded-xl p-4 mb-6">
                        <p class="text-primary-400 text-sm mb-4">
                            <strong>Mode Development:</strong> Midtrans belum dikonfigurasi. Gunakan tombol di bawah untuk simulasi pembayaran berhasil.
                        </p>
                        <form action="{{ route('payment.dummy', $order) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn-primary w-full py-4 text-lg">
                                Simulasi Bayar Berhasil
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('timer', (expiryDate) => ({
        timeDisplay: '00:00:00',
        interval: null,
        
        init() {
            const end = new Date(expiryDate).getTime();
            
            this.interval = setInterval(() => {
                const now = new Date().getTime();
                const distance = end - now;
                
                if (distance < 0) {
                    clearInterval(this.interval);
                    this.timeDisplay = 'KADALUARSA';
                    // Redirect to payment page so server redirects to my-tickets
                    window.location.href = "{{ route('payment.show', $order) }}";
                    return;
                }
                
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                
                this.timeDisplay = 
                    (hours < 10 ? "0" + hours : hours) + ":" + 
                    (minutes < 10 ? "0" + minutes : minutes) + ":" + 
                    (seconds < 10 ? "0" + seconds : seconds);
            }, 1000);
        }
    }));
});
</script>

@if(config('midtrans.server_key') && $order->midtrans_snap_token)
<script src="{{ config('midtrans.snap_url') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script type="text/javascript">
    document.getElementById('pay-button').onclick = function () {
        snap.pay('{{ $order->midtrans_snap_token }}', {
            onSuccess: function (result) {
                window.location.href = "{{ route('my-tickets.show', $order) }}";
            },
            onPending: function (result) {
                // Do nothing, still waiting
            },
            onError: function (result) {
                alert("Pembayaran gagal!");
            },
            onClose: function () {
                // User closed popup
            }
        });
    };
</script>
@endif
@endsection
