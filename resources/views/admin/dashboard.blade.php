@extends('layouts.admin')

@section('title', 'Dashboard Admin')

@section('content')
<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Revenue -->
    <div class="bg-dark-900 border border-dark-800 rounded-2xl p-6 shadow-sm">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-sm text-dark-400 font-medium mb-1">Total Pendapatan</p>
                <h3 class="text-2xl font-bold text-white">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-success-500/10 flex items-center justify-center border border-success-500/20">
                <svg class="w-5 h-5 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
        <p class="text-xs text-dark-400">
            Bulan ini:
            <span class="text-success-400 font-semibold">Rp {{ number_format($revenueThisMonth, 0, ',', '.') }}</span>
        </p>
    </div>

    <!-- Tickets Sold -->
    <div class="bg-dark-900 border border-dark-800 rounded-2xl p-6 shadow-sm">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-sm text-dark-400 font-medium mb-1">Tiket Terjual</p>
                <h3 class="text-2xl font-bold text-white">{{ number_format($totalTicketsSold, 0, ',', '.') }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-primary-500/10 flex items-center justify-center border border-primary-500/20">
                <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path></svg>
            </div>
        </div>
        <p class="text-xs text-dark-400">
            Kuota tersisa:
            <span class="text-primary-400 font-semibold">{{ number_format($totalQuota - $totalTicketsSold, 0, ',', '.') }}</span>
            <span class="text-dark-500">/ {{ number_format($totalQuota, 0, ',', '.') }}</span>
        </p>
    </div>

    <!-- Total Orders -->
    <div class="bg-dark-900 border border-dark-800 rounded-2xl p-6 shadow-sm">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-sm text-dark-400 font-medium mb-1">Total Pesanan</p>
                <h3 class="text-2xl font-bold text-white">{{ number_format($totalOrders, 0, ',', '.') }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-blue-500/10 flex items-center justify-center border border-blue-500/20">
                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
            </div>
        </div>
        <p class="text-xs text-dark-400">
            Lunas:
            <span class="text-blue-400 font-semibold">{{ number_format($paidOrders, 0, ',', '.') }}</span>
            &nbsp;·&nbsp; Pending:
            <span class="text-warning-400 font-semibold">{{ number_format($pendingOrders, 0, ',', '.') }}</span>
        </p>
    </div>

    <!-- Scanned Tickets -->
    <div class="bg-dark-900 border border-dark-800 rounded-2xl p-6 shadow-sm">
        <div class="flex justify-between items-start mb-4">
            <div>
                <p class="text-sm text-dark-400 font-medium mb-1">Tiket Discan</p>
                <h3 class="text-2xl font-bold text-white">{{ number_format($ticketsScanned, 0, ',', '.') }}</h3>
            </div>
            <div class="w-10 h-10 rounded-xl bg-warning-500/10 flex items-center justify-center border border-warning-500/20">
                <svg class="w-5 h-5 text-warning-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
            </div>
        </div>
        <p class="text-xs text-dark-400">
            Belum discan:
            <span class="text-warning-400 font-semibold">{{ number_format($totalTicketsSold - $ticketsScanned, 0, ',', '.') }}</span>
            dari {{ number_format($totalTicketsSold, 0, ',', '.') }} tiket
        </p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    <!-- Main Content Area -->
    <div class="lg:col-span-2 space-y-8">
        
        <!-- Upcoming Matches Progress -->
        <div class="bg-dark-900 border border-dark-800 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-dark-800 flex justify-between items-center">
                <h3 class="text-lg font-bold text-white">Status Penjualan Tiket</h3>
                <a href="{{ route('admin.matches.index') }}" class="text-sm text-primary-400 hover:text-primary-300">Kelola Pertandingan &rarr;</a>
            </div>
            <div class="p-6 space-y-6">
                @forelse($upcomingMatches as $match)
                    @php
                        $totalQuota = $match->ticketCategories->sum('quota');
                        $totalSold = $match->ticketCategories->sum('sold');
                        $percentage = $totalQuota > 0 ? min(100, round(($totalSold / $totalQuota) * 100)) : 0;
                    @endphp
                    <div>
                        <div class="flex justify-between items-end mb-2">
                            <div>
                                <h4 class="text-white font-semibold mb-1">vs {{ $match->opponent }}</h4>
                                <p class="text-xs text-dark-400">{{ $match->match_date->format('d M Y') }}</p>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-bold text-white">{{ $percentage }}%</span>
                                <p class="text-xs text-dark-400">{{ $totalSold }} / {{ $totalQuota }} terjual</p>
                            </div>
                        </div>
                        <div class="w-full bg-dark-800 rounded-full h-2.5">
                            <div class="bg-gradient-to-r from-primary-600 to-primary-400 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-6 text-dark-400">
                        Tidak ada pertandingan mendatang yang dipublikasikan.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-dark-900 border border-dark-800 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-dark-800 flex justify-between items-center">
                <h3 class="text-lg font-bold text-white">Pesanan Terbaru</h3>
                <a href="{{ route('admin.orders.index') }}" class="text-sm text-primary-400 hover:text-primary-300">Semua Pesanan &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-dark-300">
                    <thead class="text-xs text-dark-400 uppercase bg-dark-800/50">
                        <tr>
                            <th scope="col" class="px-6 py-3">ID Pesanan</th>
                            <th scope="col" class="px-6 py-3">Pembeli</th>
                            <th scope="col" class="px-6 py-3">Pertandingan</th>
                            <th scope="col" class="px-6 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentOrders as $order)
                            <tr class="border-b border-dark-800 hover:bg-dark-800/20">
                                <td class="px-6 py-4 font-mono text-white">
                                    <a href="{{ route('admin.orders.show', $order) }}" class="hover:text-primary-400">{{ $order->order_number }}</a>
                                </td>
                                <td class="px-6 py-4">{{ $order->user->name }}</td>
                                <td class="px-6 py-4 truncate max-w-xs">
                                    {{ $order->items->first()?->ticketCategory->match->opponent ?? '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($order->status === 'paid')
                                        <span class="badge bg-success-500/20 text-success-500">Lunas</span>
                                    @elseif($order->status === 'pending')
                                        <span class="badge bg-warning-500/20 text-warning-500">Pending</span>
                                    @else
                                        <span class="badge bg-dark-700/50 text-dark-300">{{ ucfirst($order->status) }}</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-dark-400">Belum ada pesanan masuk.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Sidebar Area -->
    <div class="lg:col-span-1 space-y-8">
        
        <!-- Quick Actions -->
        <div class="bg-dark-900 border border-dark-800 rounded-2xl shadow-sm p-6">
            <h3 class="text-lg font-bold text-white mb-4">Aksi Cepat</h3>
            <div class="space-y-3">
                <a href="{{ route('admin.gate.index') }}" class="btn-primary w-full justify-start py-3">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                    Buka Scanner QR Code
                </a>
                <a href="{{ route('admin.matches.create') }}" class="btn-secondary w-full justify-start py-3 bg-dark-800 text-white border-dark-700 hover:bg-dark-700">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Tambah Pertandingan Baru
                </a>
            </div>
        </div>

        <!-- Top Matches Revenue -->
        <div class="bg-dark-900 border border-dark-800 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-6 py-5 border-b border-dark-800">
                <h3 class="text-lg font-bold text-white">Pendapatan Terbesar</h3>
            </div>
            <div class="p-6 space-y-4">
                @forelse($revenueByMatch as $index => $rev)
                    <div class="flex items-center gap-4">
                        <div class="w-8 h-8 rounded-full bg-dark-800 flex items-center justify-center font-bold text-dark-400 border border-dark-700">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-white truncate">vs {{ $rev->opponent }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-sm font-bold text-primary-400">Rp {{ number_format($rev->revenue, 0, ',', '.') }}</span>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4 text-dark-400">Belum ada data pendapatan.</div>
                @endforelse
            </div>
        </div>

    </div>
</div>
@endsection
