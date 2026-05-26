<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Beli tiket pertandingan Persekat Tegal secara online. Mudah, cepat, dan aman.">
        <title>{{ config('app.name', 'Tiket Persekat') }} - @yield('title', 'Dashboard')</title>
        <link rel="icon" href="{{ asset('favicon.ico') }}">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased" x-data="{ mobileMenu: false }">
        <div class="min-h-screen bg-dark-950">

            <!-- ========== NAVBAR ========== -->
            <nav class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
                 x-data="{ scrolled: false }"
                 x-init="window.addEventListener('scroll', () => { scrolled = window.scrollY > 30 })"
                 :class="scrolled ? 'bg-dark-950/95 backdrop-blur-xl shadow-lg shadow-dark-950/50 border-b border-dark-800/50' : 'bg-transparent'">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between h-20">

                        <!-- Logo -->
                        <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                            <img src="{{ asset('logo.png') }}" alt="Persekat" class="h-12 w-12 object-contain rounded-full border-2 border-primary-500/30 group-hover:border-primary-500 transition-colors shadow-lg">
                            <div class="hidden sm:block">
                                <span class="block text-lg font-black font-display text-white uppercase tracking-wide leading-tight">Persekat</span>
                                <span class="block text-[10px] font-bold text-primary-500 uppercase tracking-[0.2em]">Kota Tegal</span>
                            </div>
                        </a>

                        <!-- Desktop Nav -->
                        <div class="hidden lg:flex items-center gap-1">
                            <a href="{{ route('home') }}" class="px-4 py-2 text-sm font-bold uppercase tracking-wider {{ request()->routeIs('home') ? 'text-primary-500' : 'text-dark-200 hover:text-white' }} transition-colors">Beranda</a>
                            <a href="{{ route('matches.index') }}" class="px-4 py-2 text-sm font-bold uppercase tracking-wider {{ request()->routeIs('matches.*') ? 'text-primary-500' : 'text-dark-200 hover:text-white' }} transition-colors">Pertandingan</a>
                            @auth
                            <a href="{{ route('my-tickets.index') }}" class="px-4 py-2 text-sm font-bold uppercase tracking-wider {{ request()->routeIs('my-tickets.*') ? 'text-primary-500' : 'text-dark-200 hover:text-white' }} transition-colors">Tiket Saya</a>
                            @endauth
                        </div>

                        <!-- Right Side -->
                        <div class="flex items-center gap-3">
                            @auth
                                <div x-data="{ open: false }" class="relative hidden lg:block">
                                    <button @click="open = !open" class="flex items-center gap-2 px-3 py-2 rounded-xl hover:bg-dark-800/50 transition-colors">
                                        <div class="w-9 h-9 bg-primary-600 rounded-full flex items-center justify-center shadow-md">
                                            <span class="text-white text-sm font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                        </div>
                                        <span class="text-sm font-semibold text-white max-w-[120px] truncate">{{ Auth::user()->name }}</span>
                                        <svg class="w-4 h-4 text-dark-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-56 bg-dark-900 border border-dark-700 rounded-2xl shadow-2xl py-2 z-50">
                                        <div class="px-4 py-3 border-b border-dark-800">
                                            <p class="text-xs text-dark-400">Masuk sebagai</p>
                                            <p class="text-sm font-bold text-white truncate">{{ Auth::user()->email }}</p>
                                        </div>
                                        <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-dark-300 hover:text-white hover:bg-dark-800 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            Profil Saya
                                        </a>
                                        @if(Auth::user()->isAdmin())
                                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-primary-400 hover:text-primary-300 hover:bg-dark-800 transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.066 2.573c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.573 1.066c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.066-2.573c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            Admin Panel
                                        </a>
                                        @endif
                                        <hr class="border-dark-800 my-1">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="flex items-center gap-3 w-full px-4 py-2.5 text-sm text-dark-400 hover:text-primary-400 hover:bg-dark-800 transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                                                Keluar
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <a href="{{ route('login') }}" class="hidden lg:inline-flex text-sm font-bold uppercase tracking-wider text-dark-200 hover:text-white transition-colors px-4 py-2">Masuk</a>
                                <a href="{{ route('register') }}" class="hidden lg:inline-flex items-center px-5 py-2.5 bg-primary-600 text-white text-sm font-bold uppercase tracking-wider rounded-xl hover:bg-primary-500 transition-all shadow-lg shadow-primary-600/25">Daftar</a>
                            @endauth

                            <!-- Mobile Hamburger -->
                            <button @click="mobileMenu = !mobileMenu" class="lg:hidden p-2 text-dark-300 hover:text-white rounded-lg hover:bg-dark-800/50 transition-colors">
                                <svg x-show="!mobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                                <svg x-show="mobileMenu" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Mobile Menu (Full-screen Overlay) -->
                <div x-show="mobileMenu" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0 -translate-y-4" class="lg:hidden bg-dark-950/98 backdrop-blur-xl border-t border-dark-800/50 absolute top-full left-0 right-0 shadow-2xl">
                    <div class="max-w-7xl mx-auto px-4 py-6 space-y-1">
                        <a href="{{ route('home') }}" class="block px-4 py-3 text-base font-bold uppercase tracking-wider text-dark-200 hover:text-white hover:bg-dark-800/50 rounded-xl transition-colors">Beranda</a>
                        <a href="{{ route('matches.index') }}" class="block px-4 py-3 text-base font-bold uppercase tracking-wider text-dark-200 hover:text-white hover:bg-dark-800/50 rounded-xl transition-colors">Pertandingan</a>
                        @auth
                            <a href="{{ route('my-tickets.index') }}" class="block px-4 py-3 text-base font-bold uppercase tracking-wider text-dark-200 hover:text-white hover:bg-dark-800/50 rounded-xl transition-colors">Tiket Saya</a>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-base font-bold uppercase tracking-wider text-dark-200 hover:text-white hover:bg-dark-800/50 rounded-xl transition-colors">Profil</a>
                            @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.dashboard') }}" class="block px-4 py-3 text-base font-bold uppercase tracking-wider text-primary-400 hover:bg-dark-800/50 rounded-xl transition-colors">Admin Panel</a>
                            @endif
                            <hr class="border-dark-800 my-3">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-3 text-base font-bold uppercase tracking-wider text-dark-400 hover:text-primary-400 hover:bg-dark-800/50 rounded-xl transition-colors">Keluar</button>
                            </form>
                        @else
                            <hr class="border-dark-800 my-3">
                            <div class="flex gap-3 px-4">
                                <a href="{{ route('login') }}" class="flex-1 text-center py-3 border-2 border-dark-700 text-white font-bold uppercase tracking-wider rounded-xl hover:border-primary-500 transition-colors text-sm">Masuk</a>
                                <a href="{{ route('register') }}" class="flex-1 text-center py-3 bg-primary-600 text-white font-bold uppercase tracking-wider rounded-xl hover:bg-primary-500 transition-colors text-sm">Daftar</a>
                            </div>
                        @endauth
                    </div>
                </div>
            </nav>

            <!-- Flash Messages -->
            @if(session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)" class="fixed top-24 right-4 z-[60] bg-success-500/20 border border-success-500/30 text-success-500 px-6 py-3 rounded-xl shadow-xl backdrop-blur-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        {{ session('success') }}
                        <button @click="show = false" class="ml-2">&times;</button>
                    </div>
                </div>
            @endif
            @if(session('error'))
                <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)" class="fixed top-24 right-4 z-[60] bg-primary-500/20 border border-primary-500/30 text-primary-400 px-6 py-3 rounded-xl shadow-xl backdrop-blur-sm">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        {{ session('error') }}
                        <button @click="show = false" class="ml-2">&times;</button>
                    </div>
                </div>
            @endif

            <!-- Page Content -->
            <main>
                @yield('content')
            </main>

            <!-- ========== FOOTER ========== -->
            <footer class="bg-dark-900 border-t border-dark-800">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-8">
                        <div>
                            <div class="flex items-center gap-3 mb-4">
                                <img src="{{ asset('logo.png') }}" alt="Persekat" class="h-10 w-10 object-contain rounded-full">
                                <div>
                                    <span class="block text-base font-black font-display text-white uppercase">Persekat Tegal</span>
                                    <span class="block text-[10px] text-dark-400 uppercase tracking-widest">Laskar Ki Gede Sebayu</span>
                                </div>
                            </div>
                            <p class="text-sm text-dark-400 leading-relaxed">Platform resmi pemesanan tiket pertandingan Persekat Tegal. Dukung langsung perjuangan Laskar Ki Gede Sebayu dari tribun stadion.</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Menu</h4>
                            <ul class="space-y-2 text-sm text-dark-400">
                                <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a></li>
                                <li><a href="{{ route('matches.index') }}" class="hover:text-white transition-colors">Jadwal Pertandingan</a></li>
                                <li><a href="#" class="hover:text-white transition-colors">Syarat & Ketentuan</a></li>
                                <li><a href="#" class="hover:text-white transition-colors">Kebijakan Privasi</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Kontak</h4>
                            <ul class="space-y-2 text-sm text-dark-400">
                                <li class="flex items-center gap-2"><svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg> Stadion Wijaya Kusuma, Tegal</li>
                                <li class="flex items-center gap-2"><svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg> info@persekat.id</li>
                            </ul>
                        </div>
                    </div>
                    <div class="border-t border-dark-800 pt-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                        <span class="text-xs text-dark-500">&copy; {{ date('Y') }} Persekat Tegal. All rights reserved.</span>
                        <div class="flex gap-4">
                            <a href="#" class="w-9 h-9 rounded-full bg-dark-800 flex items-center justify-center text-dark-400 hover:text-white hover:bg-primary-600 transition-all" aria-label="Instagram">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                            </a>
                            <a href="#" class="w-9 h-9 rounded-full bg-dark-800 flex items-center justify-center text-dark-400 hover:text-white hover:bg-primary-600 transition-all" aria-label="Facebook">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                            <a href="#" class="w-9 h-9 rounded-full bg-dark-800 flex items-center justify-center text-dark-400 hover:text-white hover:bg-primary-600 transition-all" aria-label="Twitter">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
