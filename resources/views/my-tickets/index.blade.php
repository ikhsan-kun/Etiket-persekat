@extends('layouts.app')

@section('title', 'Tiket Saya')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" style="padding-top: 7rem;">
    <div class="mb-8">
        <h1 class="text-3xl font-black font-display text-white mb-2">Tiket Saya</h1>
        <p class="text-dark-300">Riwayat pemesanan tiket Anda.</p>
    </div>

    @if($orders->count() > 0)
        <div class="space-y-6">
            @foreach($orders as $order)
                <div class="bg-dark-900 border border-dark-800 rounded-2xl p-6 lg:p-8 flex flex-col md:flex-row gap-6 md:items-center justify-between hover:border-dark-700 transition-colors">
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-3 mb-4">
                            <span class="text-dark-400 font-mono text-sm border border-dark-700 px-2 py-1 rounded-md">{{ $order->order_number }}</span>
                            <span class="text-dark-500 text-sm">&bull;</span>
                            <span class="text-dark-300 text-sm">{{ $order->created_at->translatedFormat('d M Y, H:i') }}</span>
                            <span class="text-dark-500 text-sm">&bull;</span>
                            
                            @if($order->status === 'paid')
                                <span class="badge bg-success-500/20 text-success-500 border border-success-500/20">Lunas</span>
                            @elseif($order->status === 'pending')
                                <span class="badge bg-warning-500/20 text-warning-500 border border-warning-500/20">Menunggu Pembayaran</span>
                            @elseif($order->status === 'failed')
                                <span class="badge bg-primary-500/20 text-primary-400 border border-primary-500/20">Gagal</span>
                            @else
                                <span class="badge bg-dark-700/50 text-dark-300 border border-dark-600/50">Kadaluarsa</span>
                            @endif
                        </div>

                        @php
                            // Get the primary match (assuming single match per order for simplicity in UI)
                            $primaryMatch = $order->items->first()?->ticketCategory->match;
                        @endphp

                        @if($primaryMatch)
                            <h3 class="text-xl font-bold text-white mb-2">Persekat vs {{ $primaryMatch->opponent }}</h3>
                            <p class="text-dark-400 text-sm mb-4">
                                {{ $order->items->sum('quantity') }} Tiket &bull; Total Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </p>
                        @endif
                    </div>

                    <div class="flex flex-col sm:flex-row items-center gap-3 w-full md:w-auto">
                        @if($order->status === 'pending')
                            <a href="{{ route('payment.show', $order) }}" class="btn-primary w-full sm:w-auto px-6 py-2">
                                Bayar Sekarang
                            </a>
                        @endif
                        <a href="{{ route('my-tickets.show', $order) }}" class="btn-secondary w-full sm:w-auto px-6 py-2">
                            Detail Pesanan
                        </a>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-8 flex justify-center">
            {{ $orders->links() }}
        </div>
    @else
        <div class="text-center py-24 bg-dark-900 border border-dark-800 rounded-2xl">
            <div class="w-20 h-20 bg-dark-800 rounded-full flex items-center justify-center mx-auto mb-4 border border-dark-700">
                <svg class="w-10 h-10 text-dark-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Belum Ada Tiket</h3>
            <p class="text-dark-400 mb-6">Anda belum pernah melakukan pemesanan tiket.</p>
            <a href="{{ route('matches.index') }}" class="btn-primary">Beli Tiket Sekarang</a>
        </div>
    @endif
</div>
@endsection
