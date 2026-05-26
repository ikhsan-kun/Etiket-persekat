<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Daftar Akun - {{ config('app.name', 'Tiket Persekat') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Outfit:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-white antialiased bg-dark-950 selection:bg-primary-500 selection:text-white">
        
        <div class="min-h-screen flex flex-col md:flex-row-reverse">
            
            <!-- Right Side - Branding / Image (Hidden on Mobile) -->
            <div class="hidden md:flex md:w-1/2 lg:w-3/5 bg-dark-900 relative items-center justify-center overflow-hidden">
                <!-- Abstract Stadium / Brand Graphic -->
                <div class="absolute inset-0 z-0">
                    <div class="absolute inset-0 bg-gradient-to-bl from-primary-900/80 via-dark-950 to-dark-950 z-10"></div>
                    <!-- Hexagon or Grid Pattern -->
                    <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNykiLz48L3N2Zz4=')] z-10"></div>
                    <!-- Large Glow -->
                    <div class="absolute bottom-0 right-0 w-[800px] h-[800px] bg-primary-600/20 rounded-full blur-[120px] z-0 translate-x-1/4 translate-y-1/4"></div>
                </div>

                <div class="relative z-20 w-full max-w-lg px-8 text-right flex flex-col items-end">
                    <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-700 rounded-2xl flex items-center justify-center shadow-lg shadow-primary-600/30 mb-8 border border-primary-500/20">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                    
                    <h1 class="text-5xl lg:text-7xl font-black font-display uppercase tracking-tight leading-none mb-6">
                        GABUNG<br>
                        <span class="text-primary-500">BERSAMA KAMI</span>
                    </h1>
                    
                    <p class="text-xl text-dark-300 font-medium mb-12 max-w-md">
                        Jadilah bagian dari Laskar Ki Gede Sebayu. Daftarkan akunmu sekarang untuk pengalaman ticketing terbaik.
                    </p>
                </div>
            </div>

            <!-- Left Side - Form -->
            <div class="flex-1 flex items-center justify-center p-6 sm:p-12 lg:p-16 bg-dark-950 relative z-10">
                <!-- Mobile Only Header -->
                <div class="md:hidden absolute top-6 left-6 right-6 flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 bg-gradient-to-br from-primary-500 to-primary-700 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                        </div>
                        <span class="font-bold font-display tracking-wider uppercase">Persekat</span>
                    </div>
                    <a href="{{ route('home') }}" class="text-xs font-bold uppercase text-dark-400 hover:text-white">Beranda</a>
                </div>

                <div class="w-full max-w-md mt-16 md:mt-0">
                    <div class="mb-8">
                        <a href="{{ route('home') }}" class="hidden md:inline-flex items-center text-sm font-bold uppercase tracking-wider text-dark-400 hover:text-white transition-colors mb-8">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Kembali ke Beranda
                        </a>
                        <h2 class="text-3xl font-black font-display uppercase tracking-wide text-white mb-2">Daftar Akun Baru</h2>
                        <p class="text-dark-400 font-medium">Lengkapi data diri Anda di bawah ini.</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}" class="space-y-5">
                        @csrf

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-xs font-bold uppercase tracking-wider text-dark-300 mb-2">Nama Lengkap</label>
                            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" 
                                   class="w-full px-5 py-3 bg-dark-900 border border-dark-800 rounded-xl text-white placeholder-dark-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                                   placeholder="Contoh: Budi Santoso">
                            @error('name')
                                <p class="text-primary-400 text-xs font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div>
                            <label for="email" class="block text-xs font-bold uppercase tracking-wider text-dark-300 mb-2">Email Address</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" 
                                   class="w-full px-5 py-3 bg-dark-900 border border-dark-800 rounded-xl text-white placeholder-dark-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                                   placeholder="Masukkan email Anda">
                            @error('email')
                                <p class="text-primary-400 text-xs font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-xs font-bold uppercase tracking-wider text-dark-300 mb-2">Password</label>
                            <input id="password" type="password" name="password" required autocomplete="new-password" 
                                   class="w-full px-5 py-3 bg-dark-900 border border-dark-800 rounded-xl text-white placeholder-dark-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                                   placeholder="••••••••">
                            @error('password')
                                <p class="text-primary-400 text-xs font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-dark-300 mb-2">Konfirmasi Password</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" 
                                   class="w-full px-5 py-3 bg-dark-900 border border-dark-800 rounded-xl text-white placeholder-dark-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                                   placeholder="••••••••">
                            @error('password_confirmation')
                                <p class="text-primary-400 text-xs font-medium mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="pt-2">
                            <button type="submit" class="w-full inline-flex items-center justify-center px-8 py-4 bg-primary-600 text-white font-bold rounded-xl hover:bg-primary-500 transition-all shadow-[0_0_20px_rgba(220,38,38,0.3)] hover:shadow-[0_0_30px_rgba(220,38,38,0.5)] uppercase tracking-wider text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 focus:ring-offset-dark-950">
                                Daftar Sekarang
                            </button>
                        </div>
                        
                        <div class="text-center mt-6">
                            <p class="text-sm font-medium text-dark-400">
                                Sudah punya akun? 
                                <a href="{{ route('login') }}" class="text-white font-bold hover:text-primary-400 transition-colors border-b-2 border-primary-500/30 hover:border-primary-500 pb-1 ml-1">Masuk disini</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </body>
</html>
