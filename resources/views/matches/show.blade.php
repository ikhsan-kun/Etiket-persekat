@extends('layouts.app')

@section('title', 'Persekat vs ' . $match->opponent)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="mb-6">
        <a href="{{ route('matches.index') }}" class="inline-flex items-center text-dark-400 hover:text-white transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Jadwal
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Match Info -->
        <div class="lg:col-span-2 space-y-8">
            <!-- Banner & Header -->
            <div class="bg-dark-900 border border-dark-800 rounded-3xl overflow-hidden shadow-2xl relative">
                <!-- Status Badge -->
                <div class="absolute top-6 right-6 z-20">
                    <span class="badge {{ $match->status === 'live' ? 'bg-primary-500 text-white' : 'bg-dark-800/90 text-white backdrop-blur border border-dark-700' }} px-4 py-2 text-sm">
                        @if($match->status === 'live')
                            <span class="w-2 h-2 rounded-full bg-white mr-2 animate-pulse"></span>
                            Sedang Berlangsung
                        @else
                            Segera Datang
                        @endif
                    </span>
                </div>

                <!-- Banner Image -->
                <div class="relative h-64 md:h-80 bg-dark-800">
                    @if($match->banner_image)
                        <img src="{{ asset('storage/' . $match->banner_image) }}" alt="{{ $match->opponent }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-dark-800 to-dark-900 flex items-center justify-center">
                            <div class="text-center">
                                <div class="w-24 h-24 bg-dark-800 rounded-full flex items-center justify-center mx-auto mb-4 border border-dark-700 shadow-inner">
                                    <svg class="w-12 h-12 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="absolute inset-0 bg-gradient-to-t from-dark-900 via-dark-900/40 to-transparent"></div>
                    
                    <!-- Title Over Banner -->
                    <div class="absolute bottom-0 left-0 w-full p-8">
                        <h1 class="text-4xl md:text-5xl font-black font-display text-white mb-2 leading-tight">
                            Persekat <span class="text-primary-500 text-3xl md:text-4xl font-bold mx-2">VS</span> {{ $match->opponent }}
                        </h1>
                    </div>
                </div>

                <!-- Match Details -->
                <div class="p-8 border-t border-dark-800">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-primary-600/10 flex items-center justify-center border border-primary-500/20 flex-shrink-0">
                                <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-dark-400 text-sm font-medium mb-1">Tanggal & Waktu</h3>
                                <p class="text-white font-semibold text-lg">{{ $match->match_date->translatedFormat('l, d F Y') }}</p>
                                <p class="text-primary-400 font-medium">{{ $match->match_date->format('H:i') }} WIB</p>
                            </div>
                        </div>

                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 rounded-xl bg-primary-600/10 flex items-center justify-center border border-primary-500/20 flex-shrink-0">
                                <svg class="w-6 h-6 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-dark-400 text-sm font-medium mb-1">Lokasi</h3>
                                <p class="text-white font-semibold text-lg">{{ $match->location }}</p>
                                <a href="https://maps.google.com/?q={{ urlencode($match->location) }}" target="_blank" class="text-primary-400 hover:text-primary-300 text-sm font-medium inline-flex items-center mt-1">
                                    Lihat Peta
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    @if($match->description)
                        <div>
                            <h3 class="text-lg font-bold text-white mb-3">Deskripsi Pertandingan</h3>
                            <div class="text-dark-300 leading-relaxed space-y-4">
                                {{ \Illuminate\Mail\Markdown::parse($match->description) }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Important Info -->
            <div class="bg-dark-800/50 border border-dark-700/50 rounded-2xl p-6">
                <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-warning-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Informasi Penting
                </h3>
                <ul class="space-y-3 text-dark-300 text-sm">
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-dark-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Satu akun maksimal dapat membeli 4 tiket.
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-dark-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        E-Ticket tidak perlu dicetak, cukup tunjukkan QR Code dari HP Anda saat di gerbang.
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-dark-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Gerbang stadion dibuka 2 jam sebelum kick-off.
                    </li>
                    <li class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-dark-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Dilarang membawa senjata tajam, flare, dan minuman keras ke dalam stadion.
                    </li>
                </ul>
            </div>
        </div>

        <!-- Ticket Selection Sticky Sidebar -->
        <div class="lg:col-span-1">
            <div class="sticky top-24 bg-dark-900 border border-dark-800 rounded-3xl p-6 shadow-2xl">
                <h2 class="text-2xl font-bold text-white mb-2 font-display">Pesan Tiket</h2>
                <p class="text-dark-400 text-sm mb-6">Pilih kategori tribun di halaman selanjutnya.</p>

                <div class="space-y-4 mb-8">
                    @foreach($match->ticketCategories as $category)
                        <div class="flex items-center justify-between p-4 rounded-xl border {{ $category->isAvailable() ? 'bg-dark-800/50 border-dark-700' : 'bg-dark-950 border-dark-800 opacity-60' }}">
                            <div>
                                <h4 class="text-white font-semibold mb-1">{{ $category->name }}</h4>
                                <span class="text-primary-400 font-bold">Rp {{ number_format($category->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="text-right">
                                @if($category->isAvailable())
                                    <span class="text-xs text-dark-400 block mb-1">Tersisa</span>
                                    <span class="badge bg-success-500/20 text-success-500 border border-success-500/20">{{ $category->availableQuota() }}</span>
                                @else
                                    <span class="badge bg-primary-500/20 text-primary-500 border border-primary-500/20">Habis</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($match->hasAvailableTickets())
                    <a href="{{ route('checkout.index', $match) }}" class="btn-primary w-full py-4 text-lg">
                        Lanjut Pilih Tiket
                    </a>
                @else
                    <button disabled class="btn-secondary w-full py-4 text-lg cursor-not-allowed opacity-50">
                        Tiket Habis
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
