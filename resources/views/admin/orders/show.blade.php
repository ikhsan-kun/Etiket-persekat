@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . $order->order_number)

@section('content')
<div class="max-w-5xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center text-dark-400 hover:text-white transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Order Info Sidebar -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-dark-900 border border-dark-800 rounded-3xl p-6 shadow-sm">
                <div class="text-center pb-6 border-b border-dark-800 mb-6">
                    <h3 class="text-xl font-bold font-mono text-white mb-2">{{ $order->order_number }}</h3>
                    @if($order->status === 'paid')
                        <span class="badge bg-success-500/20 text-success-500 border border-success-500/20 px-3 py-1 text-sm">Lunas</span>
                    @elseif($order->status === 'pending')
                        <span class="badge bg-warning-500/20 text-warning-500 border border-warning-500/20 px-3 py-1 text-sm">Menunggu Pembayaran</span>
                    @elseif($order->status === 'failed')
                        <span class="badge bg-primary-500/20 text-primary-400 border border-primary-500/20 px-3 py-1 text-sm">Gagal</span>
                    @else
                        <span class="badge bg-dark-700/50 text-dark-300 border border-dark-600/50 px-3 py-1 text-sm">Kadaluarsa</span>
                    @endif
                </div>

                <div class="space-y-4">
                    <div>
                        <span class="text-xs text-dark-400 block mb-1">Tanggal Pesanan</span>
                        <span class="text-white font-medium">{{ $order->created_at->format('d M Y, H:i') }}</span>
                    </div>
                    @if($order->paid_at)
                    <div>
                        <span class="text-xs text-dark-400 block mb-1">Tanggal Lunas</span>
                        <span class="text-white font-medium">{{ $order->paid_at->format('d M Y, H:i') }}</span>
                    </div>
                    @endif
                    @if($order->payment_type)
                    <div>
                        <span class="text-xs text-dark-400 block mb-1">Metode Pembayaran</span>
                        <span class="text-white font-medium uppercase">{{ str_replace('_', ' ', $order->payment_type) }}</span>
                    </div>
                    @endif
                    @if($order->midtrans_transaction_id)
                    <div>
                        <span class="text-xs text-dark-400 block mb-1">ID Transaksi Midtrans</span>
                        <span class="text-white font-mono text-xs">{{ $order->midtrans_transaction_id }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Customer Info -->
            <div class="bg-dark-900 border border-dark-800 rounded-3xl p-6 shadow-sm">
                <h3 class="font-bold text-white mb-4 border-b border-dark-800 pb-3">Informasi Pembeli</h3>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-dark-800 flex items-center justify-center border border-dark-700">
                        <span class="text-primary-400 font-bold text-lg">{{ substr($order->user->name, 0, 1) }}</span>
                    </div>
                    <div>
                        <div class="font-bold text-white">{{ $order->user->name }}</div>
                        <div class="text-sm text-dark-400">{{ $order->user->email }}</div>
                        <div class="text-sm text-dark-400">{{ $order->user->phone ?? '-' }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items & Tickets -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-dark-900 border border-dark-800 rounded-3xl p-6 shadow-sm">
                <h3 class="font-bold text-white mb-6 border-b border-dark-800 pb-3">Rincian Pembelian</h3>
                
                <div class="overflow-x-auto mb-6">
                    <table class="w-full text-sm text-left text-dark-300">
                        <thead class="text-xs text-dark-400 uppercase border-b border-dark-800">
                            <tr>
                                <th class="pb-3 font-medium">Item Tiket</th>
                                <th class="pb-3 font-medium text-right">Harga</th>
                                <th class="pb-3 font-medium text-center">Qty</th>
                                <th class="pb-3 font-medium text-right">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-dark-800/50">
                            @foreach($order->items as $item)
                            <tr>
                                <td class="py-4">
                                    <div class="font-bold text-white">{{ $item->ticketCategory->name }}</div>
                                    <div class="text-xs text-dark-400 mt-1">vs {{ $item->ticketCategory->match->opponent }}</div>
                                </td>
                                <td class="py-4 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td class="py-4 text-center">{{ $item->quantity }}</td>
                                <td class="py-4 text-right font-bold text-white">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="pt-4 text-right font-bold text-dark-300">Total Keseluruhan:</td>
                                <td class="pt-4 text-right font-bold text-primary-400 text-lg">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            <!-- E-Tickets List if Paid -->
            @if($order->status === 'paid' && $order->eTickets->count() > 0)
            <div class="bg-dark-900 border border-dark-800 rounded-3xl p-6 shadow-sm">
                <h3 class="font-bold text-white mb-6 border-b border-dark-800 pb-3">Status E-Ticket ({{ $order->eTickets->count() }})</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($order->eTickets as $ticket)
                    <div class="border {{ $ticket->is_used ? 'border-dark-700 bg-dark-800/30' : 'border-success-500/30 bg-success-500/5' }} rounded-xl p-4 flex items-center gap-4">
                        <div class="w-12 h-12 bg-white rounded-lg p-1 flex-shrink-0">
                            <!-- Using QR Server API just for visual representation in admin panel -->
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode($ticket->ticket_code) }}&margin=0" alt="QR Code" class="w-full h-full opacity-{{ $ticket->is_used ? '50' : '100' }}">
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="font-mono text-sm font-bold text-white truncate mb-1">{{ $ticket->ticket_code }}</div>
                            <div class="text-xs text-dark-300 truncate">{{ $ticket->orderItem->ticketCategory->name }}</div>
                            <div class="mt-2">
                                @if($ticket->is_used)
                                    <span class="text-xs text-primary-400 font-medium bg-primary-500/10 px-2 py-1 rounded">
                                        Telah di-scan: {{ $ticket->used_at->format('d/m H:i') }}
                                    </span>
                                @else
                                    <span class="text-xs text-success-500 font-medium bg-success-500/10 px-2 py-1 rounded">
                                        Tersedia (Belum di-scan)
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
