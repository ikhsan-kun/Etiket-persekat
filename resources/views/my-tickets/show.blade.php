@extends('layouts.app')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-6">
        <a href="{{ route('my-tickets.index') }}" class="inline-flex items-center text-dark-400 hover:text-white transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar Tiket
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Detail Pesanan (Sidebar) -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-dark-900 border border-dark-800 rounded-3xl p-6 shadow-xl sticky top-24">
                <h3 class="text-xl font-bold text-white mb-6 border-b border-dark-800 pb-4">Detail Pesanan</h3>
                
                <div class="space-y-4 mb-6">
                    <div>
                        <span class="block text-sm text-dark-400 mb-1">ID Pesanan</span>
                        <span class="font-mono text-white font-bold">{{ $order->order_number }}</span>
                    </div>
                    <div>
                        <span class="block text-sm text-dark-400 mb-1">Status Pembayaran</span>
                        @if($order->status === 'paid')
                            <span class="badge bg-success-500/20 text-success-500 border border-success-500/20">Lunas pada {{ $order->paid_at->format('d/m/Y H:i') }}</span>
                        @elseif($order->status === 'pending')
                            <span class="badge bg-warning-500/20 text-warning-500 border border-warning-500/20 animate-pulse">Menunggu Pembayaran</span>
                        @else
                            <span class="badge bg-primary-500/20 text-primary-400 border border-primary-500/20">{{ ucfirst($order->status) }}</span>
                        @endif
                    </div>
                    <div>
                        <span class="block text-sm text-dark-400 mb-1">Total Pembayaran</span>
                        <span class="text-lg text-primary-400 font-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                @if($order->status === 'pending')
                    <a href="{{ route('payment.show', $order) }}" class="btn-primary w-full py-3 mb-3">
                        Lanjut Bayar
                    </a>
                @endif
            </div>
        </div>

        <!-- E-Tickets (Main Content) -->
        <div class="lg:col-span-2 space-y-6">
            @if($order->status === 'paid')
                <div class="bg-success-500/10 border border-success-500/20 rounded-2xl p-4 mb-8 flex items-start gap-4">
                    <div class="w-10 h-10 rounded-full bg-success-500/20 flex items-center justify-center flex-shrink-0 mt-1">
                        <svg class="w-5 h-5 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-white font-bold mb-1">E-Ticket Berhasil Diterbitkan</h4>
                        <p class="text-dark-300 text-sm">Tunjukkan QR Code di bawah ini kepada petugas saat di gerbang masuk stadion. Satu barcode berlaku untuk satu kali scan.</p>
                    </div>
                </div>

                <div class="space-y-8">
                    @foreach($order->eTickets as $index => $ticket)
                        @php
                            $match = $ticket->orderItem->ticketCategory->match;
                        @endphp
                        
                        <!-- E-Ticket Card -->
                        <div class="relative bg-dark-900 border border-dark-800 rounded-3xl overflow-hidden shadow-2xl {{ $ticket->is_used ? 'opacity-60' : '' }}">
                            <!-- Semi-circles for ticket punch effect -->
                            <div class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-1/2 w-8 h-8 bg-dark-950 rounded-full border-r border-dark-800 z-10 hidden md:block"></div>
                            <div class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-1/2 w-8 h-8 bg-dark-950 rounded-full border-l border-dark-800 z-10 hidden md:block"></div>

                            <div class="flex flex-col md:flex-row">
                                <!-- Match Details Left Side -->
                                <div class="p-6 md:p-8 flex-1 border-b md:border-b-0 md:border-r border-dark-800 border-dashed relative">
                                    @if($ticket->is_used)
                                        <div class="absolute inset-0 flex items-center justify-center z-20 pointer-events-none">
                                            <div class="transform -rotate-12 border-4 border-primary-500/80 text-primary-500/80 px-6 py-2 rounded-xl text-3xl font-black tracking-widest backdrop-blur-sm shadow-2xl">
                                                SUDAH DIGUNAKAN
                                            </div>
                                        </div>
                                    @endif

                                    <div class="flex items-center gap-3 mb-6">
                                        <div class="w-10 h-10 bg-primary-600 rounded-lg flex items-center justify-center shadow-lg">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <span class="text-dark-400 text-xs font-bold uppercase tracking-wider block">Tiket Resmi</span>
                                            <span class="text-white font-display font-bold">Persekat Tegal</span>
                                        </div>
                                    </div>

                                    <h2 class="text-2xl font-black text-white mb-2 leading-tight">Persekat vs {{ $match->opponent }}</h2>
                                    
                                    <div class="grid grid-cols-2 gap-4 mt-8">
                                        <div>
                                            <span class="text-dark-400 text-xs uppercase tracking-wider block mb-1">Tanggal</span>
                                            <span class="text-white font-medium">{{ $match->match_date->translatedFormat('d M Y') }}</span>
                                        </div>
                                        <div>
                                            <span class="text-dark-400 text-xs uppercase tracking-wider block mb-1">Waktu</span>
                                            <span class="text-white font-medium">{{ $match->match_date->format('H:i') }} WIB</span>
                                        </div>
                                        <div>
                                            <span class="text-dark-400 text-xs uppercase tracking-wider block mb-1">Lokasi</span>
                                            <span class="text-white font-medium">{{ $match->location }}</span>
                                        </div>
                                        <div>
                                            <span class="text-dark-400 text-xs uppercase tracking-wider block mb-1">Kategori Tribun</span>
                                            <span class="text-primary-400 font-bold">{{ $ticket->orderItem->ticketCategory->name }}</span>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-8 pt-6 border-t border-dark-800">
                                        <div class="text-dark-400 text-xs">Pemegang Tiket:</div>
                                        <div class="text-white font-semibold">{{ $order->user->name }}</div>
                                    </div>
                                </div>

                                <!-- QR Code Right Side -->
                                <div class="p-6 md:p-8 flex flex-col items-center justify-center min-w-[250px] bg-dark-800/20">
                                    <div class="bg-white p-3 rounded-2xl shadow-xl mb-4">
                                        <!-- Using QR Server API for frontend QR generation -->
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($ticket->ticket_code) }}&margin=0" alt="QR Code" class="w-40 h-40">
                                    </div>
                                    <span class="font-mono text-white font-bold tracking-widest bg-dark-950 px-4 py-2 rounded-lg border border-dark-700">
                                        {{ $ticket->ticket_code }}
                                    </span>
                                    
                                    @if($ticket->is_used)
                                        <span class="text-primary-400 text-xs mt-3 text-center">
                                            Digunakan: {{ $ticket->used_at->format('d/m/Y H:i') }}
                                        </span>
                                    @else
                                        <span class="text-success-500 text-xs mt-3 text-center flex items-center gap-1">
                                            <span class="w-2 h-2 rounded-full bg-success-500 animate-pulse"></span>
                                            Tersedia / Siap Scan
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-dark-900 border border-dark-800 rounded-3xl p-12 text-center shadow-xl">
                    <div class="w-20 h-20 bg-dark-800 rounded-full flex items-center justify-center mx-auto mb-4 border border-dark-700">
                        <svg class="w-10 h-10 text-dark-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">E-Ticket Belum Tersedia</h3>
                    <p class="text-dark-400 mb-6 max-w-md mx-auto">Selesaikan pembayaran Anda terlebih dahulu untuk mendapatkan E-Ticket yang dapat digunakan saat masuk ke stadion.</p>
                    @if($order->status === 'pending')
                        <a href="{{ route('payment.show', $order) }}" class="btn-primary">Bayar Sekarang</a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
