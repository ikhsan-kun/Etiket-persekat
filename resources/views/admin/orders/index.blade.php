@extends('layouts.admin')

@section('title', 'Manajemen Pesanan')

@section('content')
<div class="bg-dark-900 border border-dark-800 rounded-2xl shadow-sm overflow-hidden">
    <!-- Header/Toolbar -->
    <div class="px-6 py-5 border-b border-dark-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form action="{{ route('admin.orders.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 w-full max-w-2xl">
            <div class="relative flex-1">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-dark-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari ID Pesanan, Nama, atau Email..." class="pl-10 pr-4 py-2 bg-dark-800 border border-dark-700 rounded-xl text-white text-sm focus:ring-primary-500 focus:border-primary-500 w-full">
            </div>
            <select name="status" class="px-4 py-2 bg-dark-800 border border-dark-700 rounded-xl text-white text-sm focus:ring-primary-500 focus:border-primary-500" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Lunas</option>
                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Gagal</option>
                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Kadaluarsa</option>
            </select>
        </form>
    </div>

    <!-- Table -->
    <!-- Desktop View -->
    <div class="hidden md:block overflow-x-auto">
        <table class="w-full text-sm text-left text-dark-300">
            <thead class="text-xs text-dark-400 uppercase bg-dark-800/50">
                <tr>
                    <th scope="col" class="px-6 py-4">ID Pesanan</th>
                    <th scope="col" class="px-6 py-4">Informasi Pembeli</th>
                    <th scope="col" class="px-6 py-4">Pertandingan</th>
                    <th scope="col" class="px-6 py-4">Total Pembayaran</th>
                    <th scope="col" class="px-6 py-4">Status</th>
                    <th scope="col" class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr class="border-b border-dark-800 hover:bg-dark-800/20">
                        <td class="px-6 py-4 font-mono text-white font-bold">
                            {{ $order->order_number }}
                            <div class="text-xs font-sans text-dark-500 font-normal mt-1">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-medium text-white">{{ $order->user->name }}</div>
                            <div class="text-xs text-dark-400 mt-1">{{ $order->user->email }}</div>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $primaryMatch = $order->items->first()?->ticketCategory->match;
                                $totalTickets = $order->items->sum('quantity');
                            @endphp
                            @if($primaryMatch)
                                <div class="text-white truncate max-w-[200px]">vs {{ $primaryMatch->opponent }}</div>
                                <div class="text-xs text-dark-400 mt-1">{{ $totalTickets }} Tiket</div>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-6 py-4 font-bold text-white">
                            Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            @if($order->status === 'paid')
                                <span class="badge bg-success-500/20 text-success-500">Lunas</span>
                            @elseif($order->status === 'pending')
                                <span class="badge bg-warning-500/20 text-warning-500">Pending</span>
                            @elseif($order->status === 'failed')
                                <span class="badge bg-primary-500/20 text-primary-400">Gagal</span>
                            @else
                                <span class="badge bg-dark-700/50 text-dark-300">Kadaluarsa</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn-outline px-3 py-1.5 text-xs border-dark-600 hover:border-primary-500">
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="text-dark-400 mb-2">Tidak ada pesanan ditemukan.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Mobile View -->
    <div class="block md:hidden divide-y divide-dark-800">
        @forelse($orders as $order)
            <div class="p-4 space-y-3">
                <div class="flex justify-between items-start">
                    <div>
                        <span class="font-mono text-white font-bold block">{{ $order->order_number }}</span>
                        <span class="text-xs text-dark-500">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div>
                        @if($order->status === 'paid')
                            <span class="badge bg-success-500/20 text-success-500">Lunas</span>
                        @elseif($order->status === 'pending')
                            <span class="badge bg-warning-500/20 text-warning-500">Pending</span>
                        @elseif($order->status === 'failed')
                            <span class="badge bg-primary-500/20 text-primary-400">Gagal</span>
                        @else
                            <span class="badge bg-dark-700/50 text-dark-300">Kadaluarsa</span>
                        @endif
                    </div>
                </div>
                
                <div class="text-sm">
                    <div class="text-dark-400">Pembeli: <span class="text-white font-medium">{{ $order->user->name }}</span></div>
                    <div class="text-xs text-dark-500">{{ $order->user->email }}</div>
                </div>

                <div class="flex justify-between items-center pt-1">
                    <div>
                        @php
                            $primaryMatch = $order->items->first()?->ticketCategory->match;
                            $totalTickets = $order->items->sum('quantity');
                        @endphp
                        @if($primaryMatch)
                            <div class="text-xs text-dark-300">vs {{ $primaryMatch->opponent }}</div>
                            <div class="text-xs text-dark-500">{{ $totalTickets }} Tiket</div>
                        @else
                            -
                        @endif
                    </div>
                    <div class="text-right">
                        <span class="text-xs text-dark-500 block">Total</span>
                        <span class="font-bold text-white">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="pt-1">
                    <a href="{{ route('admin.orders.show', $order) }}" class="btn-outline w-full justify-center py-2 text-xs border-dark-600 hover:border-primary-500">
                        Detail Pesanan
                    </a>
                </div>
            </div>
        @empty
            <div class="p-6 text-center text-dark-400 text-sm">Tidak ada pesanan ditemukan.</div>
        @endforelse
    </div>

    @if($orders->hasPages())
        <div class="px-6 py-4 border-t border-dark-800">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection
