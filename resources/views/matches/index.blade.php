@extends('layouts.app')

@section('title', 'Jadwal Pertandingan')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" style="padding-top: 7rem;">
    <!-- Header -->
    <div class="mb-12">
        <h1 class="text-4xl font-black font-display text-white mb-4">Jadwal <span class="text-primary-500">Pertandingan</span></h1>
        <p class="text-dark-300 text-lg">Daftar pertandingan Laskar Ki Gede Sebayu yang tiketnya sudah dapat dipesan.</p>
    </div>

    @if($matches->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
            @foreach($matches as $match)
                <div class="card-hover p-0 overflow-hidden group flex flex-col h-full bg-dark-900 border border-dark-800 rounded-2xl relative">
                    <!-- Match Date Badge -->
                    <div class="absolute top-4 right-4 z-20 bg-dark-900/90 backdrop-blur border border-dark-700 px-3 py-2 rounded-xl text-center shadow-lg">
                        <span class="block text-primary-500 text-xs font-bold uppercase">{{ $match->match_date->translatedFormat('M') }}</span>
                        <span class="block text-white text-xl font-black leading-none">{{ $match->match_date->format('d') }}</span>
                    </div>

                    <!-- Banner -->
                    <div class="relative h-48 bg-dark-800 overflow-hidden">
                        @if($match->banner_image)
                            <img src="{{ asset('storage/' . $match->banner_image) }}" alt="{{ $match->opponent }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-dark-800 to-dark-900 flex items-center justify-center group-hover:scale-105 transition-transform duration-700">
                                <div class="text-center">
                                    <div class="w-16 h-16 bg-dark-800 rounded-full flex items-center justify-center mx-auto mb-2 border border-dark-700 shadow-inner">
                                        <svg class="w-8 h-8 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <span class="text-dark-500 font-semibold uppercase tracking-wider text-sm">VS {{ $match->opponent }}</span>
                                </div>
                            </div>
                        @endif
                        <div class="absolute inset-0 bg-gradient-to-t from-dark-900 to-transparent"></div>
                    </div>

                    <!-- Content -->
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="flex items-center gap-2 mb-3">
                            <span class="badge {{ $match->status === 'live' ? 'bg-primary-500/20 text-primary-500' : 'bg-dark-800 text-dark-300 border border-dark-700' }}">
                                @if($match->status === 'live')
                                    <span class="w-1.5 h-1.5 rounded-full bg-primary-500 mr-1.5 animate-pulse"></span>
                                    Sedang Berlangsung
                                @else
                                    Segera Datang
                                @endif
                            </span>
                            
                            @if($match->total_available > 0)
                                <span class="badge bg-success-500/20 text-success-500 border border-success-500/20">Tiket Tersedia</span>
                            @else
                                <span class="badge bg-primary-500/20 text-primary-500 border border-primary-500/20">Sold Out</span>
                            @endif
                        </div>

                        <h3 class="text-2xl font-bold text-white mb-2 font-display">Persekat vs {{ $match->opponent }}</h3>
                        
                        <div class="space-y-2 mb-6 text-sm text-dark-300">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $match->match_date->translatedFormat('l, d F Y - H:i') }} WIB
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                {{ $match->location }}
                            </div>
                        </div>

                        <div class="mt-auto border-t border-dark-800 pt-4 flex items-center justify-between">
                            <div>
                                <span class="block text-xs text-dark-400 mb-1">Mulai Dari</span>
                                <span class="text-lg font-bold text-primary-400">Rp {{ number_format($match->lowest_price, 0, ',', '.') }}</span>
                            </div>
                            <a href="{{ route('matches.show', $match) }}" class="btn-primary px-4 py-2 text-sm">
                                Beli Tiket
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-8 flex justify-center">
            {{ $matches->links() }}
        </div>
    @else
        <div class="text-center py-24 bg-dark-900 border border-dark-800 rounded-2xl">
            <div class="w-20 h-20 bg-dark-800 rounded-full flex items-center justify-center mx-auto mb-4 border border-dark-700">
                <svg class="w-10 h-10 text-dark-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Belum Ada Jadwal</h3>
            <p class="text-dark-400">Jadwal pertandingan selanjutnya belum tersedia saat ini.</p>
        </div>
    @endif
</div>
@endsection
