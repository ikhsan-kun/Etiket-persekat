<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="description" content="Admin Panel - Sistem Tiket Persekat Tegal">

        <title>{{ config('app.name', 'Tiket Persekat') }} - Admin @yield('title', 'Dashboard')</title>

        <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('logo.png') }}">
        <link rel="apple-touch-icon" href="{{ asset('logo.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased" x-data="{ sidebarOpen: false }">
        <div class="min-h-screen bg-dark-950 flex">
            <!-- Sidebar Overlay (Mobile) -->
            <div x-show="sidebarOpen" @click="sidebarOpen = false"
                 x-transition:enter="transition-opacity ease-linear duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition-opacity ease-linear duration-300"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-black/60 z-40 lg:hidden">
            </div>

            <!-- Sidebar -->
            <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                   class="fixed inset-y-0 left-0 z-50 w-64 bg-dark-900 border-r border-dark-800 transform transition-transform duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-auto lg:z-auto">
                <div class="flex flex-col h-full">
                    <!-- Logo -->
                    <div class="flex items-center gap-3 px-6 py-5 border-b border-dark-800">
                        <img src="{{ asset('logo.png') }}" alt="Logo Persekat" class="w-10 h-10 object-contain rounded-xl">
                        <div>
                            <span class="text-lg font-bold font-display text-white">Admin</span>
                            <p class="text-xs text-dark-400">Tiket Persekat</p>
                        </div>
                    </div>

                    <!-- Nav Items -->
                    <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                        @php
                            $navItems = [
                                ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                                ['route' => 'admin.matches.index', 'label' => 'Pertandingan', 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                                ['route' => 'admin.orders.index', 'label' => 'Pesanan', 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
                                ['route' => 'admin.reports.index', 'label' => 'Laporan', 'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                                ['route' => 'admin.gate.index', 'label' => 'Validasi Gerbang', 'icon' => 'M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z'],
                            ];
                        @endphp

                        @foreach($navItems as $item)
                            <a href="{{ route($item['route']) }}"
                               class="flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200
                                      {{ request()->routeIs($item['route'] . '*') ? 'bg-primary-600/20 text-primary-400 shadow-sm' : 'text-dark-400 hover:text-white hover:bg-dark-800' }}">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                                </svg>
                                {{ $item['label'] }}
                            </a>
                        @endforeach
                    </nav>

                    <!-- User Section -->
                    <div class="px-4 py-4 border-t border-dark-800">
                        <div class="flex items-center gap-3 px-2 mb-3">
                            <div class="w-9 h-9 bg-primary-600/20 rounded-full flex items-center justify-center">
                                <span class="text-primary-400 text-sm font-bold">{{ substr(Auth::user()->name, 0, 1) }}</span>
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-dark-400 truncate">{{ Auth::user()->email }}</p>
                            </div>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('home') }}" class="flex-1 text-center text-xs py-2 rounded-lg bg-dark-800 text-dark-400 hover:text-white transition-colors">
                                Situs Utama
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full text-xs py-2 rounded-lg bg-dark-800 text-dark-400 hover:text-primary-400 transition-colors">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex-1 flex flex-col min-h-screen lg:ml-0">
                <!-- Top Bar -->
                <header class="sticky top-0 z-30 bg-dark-950/80 backdrop-blur-xl border-b border-dark-800/50">
                    <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 h-16">
                        <div class="flex items-center gap-4">
                            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden text-dark-400 hover:text-white p-1">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                                </svg>
                            </button>
                            <h1 class="text-lg font-semibold text-white font-display">@yield('title', 'Dashboard')</h1>
                        </div>
                    </div>
                </header>

                <!-- Flash Messages -->
                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                         class="mx-4 sm:mx-6 lg:mx-8 mt-4 bg-success-500/20 border border-success-500/30 text-success-500 px-4 py-3 rounded-xl">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm">{{ session('success') }}</span>
                            <button @click="show = false" class="ml-auto">&times;</button>
                        </div>
                    </div>
                @endif

                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 5000)"
                         class="mx-4 sm:mx-6 lg:mx-8 mt-4 bg-primary-500/20 border border-primary-500/30 text-primary-400 px-4 py-3 rounded-xl">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            <span class="text-sm">{{ session('error') }}</span>
                            <button @click="show = false" class="ml-auto">&times;</button>
                        </div>
                    </div>
                @endif

                <!-- Page Content -->
                <main class="flex-1 px-4 sm:px-6 lg:px-8 py-6">
                    @yield('content')
                </main>
            </div>
        </div>
    </body>
</html>
