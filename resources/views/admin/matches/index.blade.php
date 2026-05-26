@extends('layouts.admin')

@section('title', 'Manajemen Pertandingan')

@section('content')
<div class="bg-dark-900 border border-dark-800 rounded-2xl shadow-sm overflow-hidden">
    <!-- Header/Toolbar -->
    <div class="px-6 py-5 border-b border-dark-800 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <form action="{{ route('admin.matches.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-dark-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari tim lawan..." class="pl-10 pr-4 py-2 bg-dark-800 border border-dark-700 rounded-xl text-white text-sm focus:ring-primary-500 focus:border-primary-500 w-full sm:w-64">
            </div>
            <select name="status" class="px-4 py-2 bg-dark-800 border border-dark-700 rounded-xl text-white text-sm focus:ring-primary-500 focus:border-primary-500" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                <option value="live" {{ request('status') == 'live' ? 'selected' : '' }}>Live</option>
                <option value="finished" {{ request('status') == 'finished' ? 'selected' : '' }}>Finished</option>
            </select>
        </form>
        
        <a href="{{ route('admin.matches.create') }}" class="btn-primary py-2 px-4 text-sm whitespace-nowrap">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Tambah Pertandingan
        </a>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-dark-300">
            <thead class="text-xs text-dark-400 uppercase bg-dark-800/50">
                <tr>
                    <th scope="col" class="px-6 py-4">Lawan & Waktu</th>
                    <th scope="col" class="px-6 py-4">Lokasi</th>
                    <th scope="col" class="px-6 py-4">Status</th>
                    <th scope="col" class="px-6 py-4">Tiket Terjual</th>
                    <th scope="col" class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($matches as $match)
                    <tr class="border-b border-dark-800 hover:bg-dark-800/20">
                        <td class="px-6 py-4">
                            <div class="font-bold text-white text-base">vs {{ $match->opponent }}</div>
                            <div class="text-xs text-dark-400 mt-1">{{ $match->match_date->translatedFormat('d M Y, H:i') }} WIB</div>
                        </td>
                        <td class="px-6 py-4">
                            {{ $match->location }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="badge 
                                {{ $match->status === 'published' ? 'bg-blue-500/20 text-blue-400' : '' }}
                                {{ $match->status === 'live' ? 'bg-primary-500/20 text-primary-400' : '' }}
                                {{ $match->status === 'finished' ? 'bg-success-500/20 text-success-500' : '' }}
                                {{ $match->status === 'draft' ? 'bg-dark-700 text-dark-300 border border-dark-600' : '' }}
                            ">
                                {{ ucfirst($match->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $totalQuota = $match->ticketCategories->sum('quota');
                                $totalSold = $match->ticketCategories->sum('sold');
                            @endphp
                            <div class="font-medium text-white">{{ $totalSold }} <span class="text-dark-500 font-normal">/ {{ $totalQuota }}</span></div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('admin.matches.edit', $match) }}" class="p-2 text-dark-400 hover:text-white bg-dark-800 hover:bg-dark-700 rounded-lg transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                @if($totalSold == 0)
                                    <form action="{{ route('admin.matches.destroy', $match) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pertandingan ini?');" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-dark-400 hover:text-primary-400 bg-dark-800 hover:bg-dark-700 rounded-lg transition-colors" title="Hapus">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="text-dark-400 mb-2">Tidak ada data pertandingan.</div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($matches->hasPages())
        <div class="px-6 py-4 border-t border-dark-800">
            {{ $matches->links() }}
        </div>
    @endif
</div>
@endsection
