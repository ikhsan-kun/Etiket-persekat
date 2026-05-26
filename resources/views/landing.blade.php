@extends('layouts.app')

@section('title', 'Situs Resmi Persekat Tegal - Laskar Ki Gede Sebayu')

@section('content')

{{-- ===== HERO SECTION (Full-screen stadium background) ===== --}}
<section class="relative w-full min-h-screen flex items-center justify-center overflow-hidden">
    {{-- Background Image --}}
    <div class="absolute inset-0">
        <img src="{{ asset('images/stadium_hero.png') }}" alt="Stadion" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-t from-dark-950 via-dark-950/70 to-dark-950/30"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-dark-950/60 to-transparent"></div>
    </div>

    {{-- Content --}}
    <div class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-16">
        <div class="max-w-3xl">
            <div class="mb-6 animate-slide-up">
                <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-600/20 border border-primary-500/30 text-primary-400 font-bold text-xs tracking-widest uppercase backdrop-blur-md">
                    <span class="w-2 h-2 rounded-full bg-primary-500 animate-pulse"></span>
                    Official Ticketing
                </span>
            </div>

            <h1 class="text-4xl sm:text-5xl md:text-7xl lg:text-8xl font-black font-display text-white uppercase leading-[0.9] tracking-tight mb-6 animate-slide-up" style="animation-delay:.1s;text-shadow:0 4px 30px rgba(0,0,0,.5)">
                Laskar<br>Ki Gede<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary-400 to-primary-600">Sebayu</span>
            </h1>

            <p class="text-base sm:text-lg md:text-xl text-dark-200 max-w-xl mb-8 animate-slide-up font-medium leading-relaxed" style="animation-delay:.2s">
                Dukung langsung perjuangan Persekat Tegal dari tribun stadion. Pesan tiket resmi secara online, cepat, dan aman tanpa antre.
            </p>

            <div class="flex flex-col sm:flex-row gap-4 animate-slide-up" style="animation-delay:.3s">
                <a href="{{ route('matches.index') }}" class="inline-flex items-center justify-center px-8 py-4 bg-primary-600 text-white font-bold rounded-xl hover:bg-primary-500 transition-all shadow-[0_0_30px_rgba(220,38,38,0.4)] hover:shadow-[0_0_40px_rgba(220,38,38,0.6)] uppercase tracking-wider text-sm group">
                    Beli Tiket Sekarang
                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </a>
                <a href="#jadwal" class="inline-flex items-center justify-center px-8 py-4 bg-white/10 backdrop-blur border-2 border-white/20 text-white font-bold rounded-xl hover:bg-white/20 hover:border-white/40 transition-all uppercase tracking-wider text-sm">
                    Lihat Jadwal
                </a>
            </div>
        </div>
    </div>

    {{-- Scroll Indicator --}}
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-10 animate-bounce hidden md:flex flex-col items-center gap-2">
        <span class="text-dark-400 text-[10px] uppercase tracking-widest font-bold">Scroll</span>
        <svg class="w-5 h-5 text-dark-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
    </div>
</section>


{{-- ===== NEXT MATCH HIGHLIGHT BAR ===== --}}
@if($upcomingMatches->count() > 0)
@php $nextMatch = $upcomingMatches->first(); @endphp
<section class="relative z-20 bg-dark-900 border-y border-dark-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row items-center justify-between py-6 gap-6">
            <div class="flex flex-col sm:flex-row items-center gap-6 text-center sm:text-left">
                <div class="text-center px-6 sm:border-r border-dark-700 flex-shrink-0">
                    <span class="block text-primary-500 font-bold text-xs uppercase tracking-widest">{{ $nextMatch->match_date->translatedFormat('M') }}</span>
                    <span class="block text-white font-black text-4xl leading-none">{{ $nextMatch->match_date->format('d') }}</span>
                    <span class="block text-dark-400 text-xs mt-1">{{ $nextMatch->match_date->format('H:i') }} WIB</span>
                </div>
                <div>
                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold uppercase tracking-widest bg-primary-600/20 text-primary-400 rounded mb-2">Pertandingan Selanjutnya</span>
                    <h3 class="text-xl sm:text-2xl font-black text-white font-display uppercase tracking-wide">
                        Persekat <span class="text-primary-500 mx-1">VS</span> {{ $nextMatch->opponent }}
                    </h3>
                    <p class="text-sm text-dark-400 mt-1 flex items-center gap-1 justify-center sm:justify-start">
                        <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        {{ $nextMatch->location }}
                    </p>
                </div>
            </div>
            <a href="{{ route('matches.show', $nextMatch) }}" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-4 bg-white text-dark-950 font-bold rounded-xl hover:bg-primary-500 hover:text-white transition-all uppercase tracking-wider text-sm shadow-xl flex-shrink-0">
                Dapatkan Tiket
            </a>
        </div>
    </div>
</section>
@endif


{{-- ===== STATS BAR ===== --}}
<section class="bg-dark-950 py-12 border-b border-dark-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-8">
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-black text-white font-display mb-1">{{ $upcomingMatches->count() }}</div>
                <div class="text-xs font-bold text-dark-400 uppercase tracking-widest">Pertandingan</div>
            </div>
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-black text-primary-500 font-display mb-1">
                    {{ $upcomingMatches->sum(fn($m) => $m->ticketCategories->sum('quota')) }}
                </div>
                <div class="text-xs font-bold text-dark-400 uppercase tracking-widest">Total Tiket</div>
            </div>
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-black text-white font-display mb-1">
                    {{ $upcomingMatches->sum(fn($m) => $m->ticketCategories->sum('sold')) }}
                </div>
                <div class="text-xs font-bold text-dark-400 uppercase tracking-widest">Tiket Terjual</div>
            </div>
            <div class="text-center">
                <div class="text-3xl md:text-4xl font-black text-primary-500 font-display mb-1">24/7</div>
                <div class="text-xs font-bold text-dark-400 uppercase tracking-widest">Online Booking</div>
            </div>
        </div>
    </div>
</section>


{{-- ===== JADWAL PERTANDINGAN ===== --}}
<section id="jadwal" class="py-16 md:py-24 bg-dark-950">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 mb-12">
            <div>
                <span class="text-primary-500 font-bold text-xs uppercase tracking-widest mb-2 block">Schedule</span>
                <h2 class="text-3xl md:text-5xl font-black text-white font-display uppercase tracking-tight">Jadwal Tanding</h2>
            </div>
            <a href="{{ route('matches.index') }}" class="hidden sm:inline-flex items-center text-sm font-bold uppercase tracking-wider text-dark-300 hover:text-white transition-colors group">
                Semua Jadwal
                <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>

        @if($upcomingMatches->count() > 0)
        <div class="space-y-4">
            @foreach($upcomingMatches as $match)
            <a href="{{ route('matches.show', $match) }}" class="group block bg-dark-900 border border-dark-800 rounded-2xl hover:border-primary-500/50 transition-all duration-300 overflow-hidden">
                <div class="flex flex-col md:flex-row items-stretch">
                    {{-- Date --}}
                    <div class="flex-shrink-0 bg-dark-800/50 flex items-center justify-center p-6 md:w-32 border-b md:border-b-0 md:border-r border-dark-700/50">
                        <div class="text-center">
                            <span class="block text-primary-500 font-bold text-xs uppercase tracking-widest">{{ $match->match_date->translatedFormat('M') }}</span>
                            <span class="block text-white font-black text-3xl md:text-4xl leading-none">{{ $match->match_date->format('d') }}</span>
                            <span class="block text-dark-400 text-xs mt-1 font-medium">{{ $match->match_date->format('H:i') }}</span>
                        </div>
                    </div>

                    {{-- Match Info --}}
                    <div class="flex-1 p-5 md:p-6 flex flex-col sm:flex-row items-start sm:items-center gap-4">
                        <div class="flex-1 min-w-0">
                            <div class="flex flex-wrap items-center gap-2 mb-2">
                                <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold uppercase tracking-widest rounded {{ $match->status === 'live' ? 'bg-primary-500 text-white shadow-[0_0_10px_rgba(220,38,38,0.5)]' : 'bg-dark-800 text-dark-300 border border-dark-700' }}">
                                    @if($match->status === 'live')
                                        <span class="w-1.5 h-1.5 rounded-full bg-white mr-1.5 animate-pulse"></span>LIVE
                                    @else
                                        UPCOMING
                                    @endif
                                </span>
                                @php
                                    $totalAvail = $match->ticketCategories->sum(fn($c) => $c->quota - $c->sold);
                                @endphp
                                @if($totalAvail > 0)
                                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold uppercase tracking-widest bg-success-500/20 text-success-500 rounded">{{ $totalAvail }} Tiket Tersedia</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 text-[10px] font-bold uppercase tracking-widest bg-primary-500/20 text-primary-400 rounded">SOLD OUT</span>
                                @endif
                            </div>
                            <h3 class="text-lg md:text-xl font-black text-white uppercase tracking-wide group-hover:text-primary-400 transition-colors">
                                Persekat <span class="text-primary-500">vs</span> {{ $match->opponent }}
                            </h3>
                            <p class="text-sm text-dark-400 mt-1 truncate">{{ $match->location }}</p>
                        </div>

                        {{-- Price & CTA --}}
                        <div class="flex items-center gap-4 flex-shrink-0 w-full sm:w-auto">
                            <div class="text-left sm:text-right">
                                <span class="block text-[10px] text-dark-400 uppercase tracking-widest font-bold">Mulai</span>
                                <span class="text-lg font-black text-primary-400">Rp {{ number_format($match->lowest_price, 0, ',', '.') }}</span>
                            </div>
                            <div class="hidden sm:flex w-12 h-12 rounded-xl bg-dark-800 group-hover:bg-primary-600 items-center justify-center transition-all flex-shrink-0 border border-dark-700 group-hover:border-primary-500">
                                <svg class="w-5 h-5 text-dark-400 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <div class="mt-8 text-center sm:hidden">
            <a href="{{ route('matches.index') }}" class="inline-flex items-center justify-center w-full px-8 py-4 bg-dark-800 text-white font-bold rounded-xl hover:bg-dark-700 transition-all uppercase tracking-wider text-sm border border-dark-700">Lihat Semua Jadwal</a>
        </div>
        @else
        <div class="text-center py-20 bg-dark-900 border border-dark-800 rounded-2xl">
            <div class="w-20 h-20 bg-dark-800 rounded-full flex items-center justify-center mx-auto mb-4 border border-dark-700">
                <svg class="w-10 h-10 text-dark-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <h3 class="text-2xl font-black text-white uppercase mb-2">Belum Ada Jadwal</h3>
            <p class="text-dark-400">Jadwal pertandingan selanjutnya belum tersedia saat ini.</p>
        </div>
        @endif
    </div>
</section>


{{-- ===== CARA BELI TIKET ===== --}}
<section class="py-16 md:py-24 bg-dark-900 border-t border-dark-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <div>
                <span class="text-primary-500 font-bold text-xs uppercase tracking-widest mb-2 block">How It Works</span>
                <h2 class="text-3xl md:text-5xl font-black text-white font-display uppercase tracking-tight mb-8">Cara Beli Tiket</h2>

                <div class="space-y-8">
                    <div class="flex items-start gap-5 group">
                        <div class="w-14 h-14 rounded-2xl bg-dark-800 flex items-center justify-center text-primary-500 font-black text-xl flex-shrink-0 border border-dark-700 group-hover:bg-primary-600 group-hover:text-white group-hover:border-primary-500 transition-all shadow-lg">1</div>
                        <div>
                            <h4 class="text-lg font-bold text-white mb-1 uppercase tracking-wide">Pilih Laga & Tribun</h4>
                            <p class="text-dark-400 leading-relaxed">Buka halaman jadwal pertandingan, pilih laga yang ingin ditonton, lalu tentukan kategori tribun dan jumlah tiket.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-5 group">
                        <div class="w-14 h-14 rounded-2xl bg-dark-800 flex items-center justify-center text-primary-500 font-black text-xl flex-shrink-0 border border-dark-700 group-hover:bg-primary-600 group-hover:text-white group-hover:border-primary-500 transition-all shadow-lg">2</div>
                        <div>
                            <h4 class="text-lg font-bold text-white mb-1 uppercase tracking-wide">Bayar Online</h4>
                            <p class="text-dark-400 leading-relaxed">Lakukan pembayaran menggunakan Transfer Bank, GoPay, ShopeePay, QRIS, atau metode lainnya melalui Midtrans.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-5 group">
                        <div class="w-14 h-14 rounded-2xl bg-primary-600 flex items-center justify-center text-white font-black text-xl flex-shrink-0 border border-primary-500 shadow-[0_0_20px_rgba(220,38,38,0.4)]">3</div>
                        <div>
                            <h4 class="text-lg font-bold text-white mb-1 uppercase tracking-wide">Scan & Masuk</h4>
                            <p class="text-dark-400 leading-relaxed">E-Ticket otomatis terbit dengan QR Code unik. Tunjukkan di gerbang stadion — tidak perlu cetak tiket fisik.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Player Image --}}
            <div class="relative hidden lg:block">
                <div class="absolute -inset-8 bg-primary-600/10 blur-[80px] rounded-full"></div>
                <div class="relative rounded-3xl overflow-hidden shadow-2xl border border-dark-700/50 group aspect-[4/5]">
                    <img src="{{ asset('images/player_celebration.png') }}" alt="Pemain Persekat" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-dark-950 via-transparent to-transparent"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-8">
                        <div class="text-white font-black text-xl uppercase tracking-widest mb-1 border-l-4 border-primary-500 pl-4">Laskar Ki Gede Sebayu</div>
                        <p class="text-dark-300 text-sm pl-5">Bersama kita kuat. Bersama kita menang!</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


{{-- ===== CTA SECTION ===== --}}
<section class="relative py-20 overflow-hidden">
    <div class="absolute inset-0">
        <img src="{{ asset('images/stadium_hero.png') }}" alt="Stadion" class="w-full h-full object-cover opacity-20">
        <div class="absolute inset-0 bg-gradient-to-r from-primary-900/90 to-dark-950/95"></div>
    </div>
    <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-3xl md:text-5xl font-black text-white font-display uppercase tracking-tight mb-4">Jangan Sampai Kehabisan!</h2>
        <p class="text-lg text-dark-200 mb-8 max-w-2xl mx-auto">Tiket pertandingan Persekat selalu cepat habis. Pesan sekarang dan jadilah bagian dari sejarah Laskar Ki Gede Sebayu.</p>
        <a href="{{ route('matches.index') }}" class="inline-flex items-center justify-center px-10 py-5 bg-white text-dark-950 font-black rounded-xl hover:bg-primary-500 hover:text-white transition-all uppercase tracking-wider text-sm shadow-2xl">
            Pesan Tiket Sekarang
        </a>
    </div>
</section>

@endsection
