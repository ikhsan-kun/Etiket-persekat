@extends('layouts.admin')

@section('title', 'Laporan Penjualan')

@section('content')
<div class="space-y-6">
    <!-- Filters & Export -->
    <div class="bg-dark-900 border border-dark-800 rounded-3xl p-6 shadow-sm">
        <form action="{{ route('admin.reports.index') }}" method="GET" class="flex flex-col md:flex-row gap-4 items-end">
            <div class="w-full md:w-auto">
                <label for="date_from" class="label-field text-xs">Dari Tanggal</label>
                <input type="date" id="date_from" name="date_from" value="{{ $dateFrom }}" class="input-field py-2 text-sm" style="color-scheme: dark;">
            </div>
            <div class="w-full md:w-auto">
                <label for="date_to" class="label-field text-xs">Sampai Tanggal</label>
                <input type="date" id="date_to" name="date_to" value="{{ $dateTo }}" class="input-field py-2 text-sm" style="color-scheme: dark;">
            </div>
            <div class="w-full md:w-auto flex gap-3">
                <button type="submit" class="btn-primary py-2 px-6">Terapkan Filter</button>
                <a href="{{ route('admin.reports.export-csv', ['date_from' => $dateFrom, 'date_to' => $dateTo]) }}" class="btn-secondary py-2 px-6">
                    <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Export CSV
                </a>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-dark-900 border border-dark-800 rounded-3xl p-6 shadow-sm flex items-center gap-6">
            <div class="w-14 h-14 rounded-2xl bg-primary-500/10 flex items-center justify-center border border-primary-500/20 flex-shrink-0">
                <svg class="w-7 h-7 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="text-dark-400 font-medium mb-1">Total Pendapatan (Lunas)</p>
                <h3 class="text-3xl font-bold text-white">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                <p class="text-xs text-dark-500 mt-1">Periode: {{ date('d/m/Y', strtotime($dateFrom)) }} - {{ date('d/m/Y', strtotime($dateTo)) }}</p>
            </div>
        </div>

        <div class="bg-dark-900 border border-dark-800 rounded-3xl p-6 shadow-sm flex items-center gap-6">
            <div class="w-14 h-14 rounded-2xl bg-success-500/10 flex items-center justify-center border border-success-500/20 flex-shrink-0">
                <svg class="w-7 h-7 text-success-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            </div>
            <div>
                <p class="text-dark-400 font-medium mb-1">Total Transaksi (Lunas)</p>
                <h3 class="text-3xl font-bold text-white">{{ number_format($totalOrders, 0, ',', '.') }} <span class="text-lg text-dark-500 font-normal">Pesanan</span></h3>
                <p class="text-xs text-dark-500 mt-1">Periode: {{ date('d/m/Y', strtotime($dateFrom)) }} - {{ date('d/m/Y', strtotime($dateTo)) }}</p>
            </div>
        </div>
    </div>

    <!-- Match Sales Table -->
    <div class="bg-dark-900 border border-dark-800 rounded-3xl shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-dark-800">
            <h3 class="text-lg font-bold text-white">Rincian Penjualan per Pertandingan</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-dark-300">
                <thead class="text-xs text-dark-400 uppercase bg-dark-800/50">
                    <tr>
                        <th scope="col" class="px-6 py-4">Pertandingan</th>
                        <th scope="col" class="px-6 py-4">Tanggal Kick-off</th>
                        <th scope="col" class="px-6 py-4 text-center">Tiket Terjual</th>
                        <th scope="col" class="px-6 py-4 text-right">Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($matchSales as $match)
                        <tr class="border-b border-dark-800 hover:bg-dark-800/20">
                            <td class="px-6 py-4 font-bold text-white text-base">vs {{ $match->opponent }}</td>
                            <td class="px-6 py-4">{{ date('d M Y, H:i', strtotime($match->match_date)) }} WIB</td>
                            <td class="px-6 py-4 text-center font-medium text-white">{{ number_format($match->tickets_sold, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right font-bold text-primary-400">Rp {{ number_format($match->revenue, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center text-dark-400">
                                Tidak ada data penjualan lunas pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
