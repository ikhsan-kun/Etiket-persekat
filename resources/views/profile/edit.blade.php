@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12" style="padding-top: 7.5rem;">
    <!-- Page Header -->
    <div class="mb-8 animate-fade-in">
        <h1 class="text-3xl font-black font-display text-white mb-2 uppercase tracking-wide">Profil Saya</h1>
        <p class="text-dark-300">Kelola informasi profil dan keamanan akun Anda.</p>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Left Column: User Card -->
        <div class="space-y-6 animate-slide-up">
            <div class="card flex flex-col items-center text-center p-8 bg-dark-900 border border-dark-800">
                <!-- Avatar -->
                <div class="w-24 h-24 bg-gradient-to-br from-primary-500 to-primary-700 rounded-full flex items-center justify-center shadow-lg shadow-primary-600/20 mb-5 border-2 border-primary-500/30">
                    <span class="text-white text-4xl font-black font-display uppercase">{{ substr($user->name, 0, 1) }}</span>
                </div>
                
                <h3 class="text-xl font-bold text-white mb-1">{{ $user->name }}</h3>
                <p class="text-dark-400 text-sm mb-4">{{ $user->email }}</p>
                
                <div class="w-full border-t border-dark-800 my-4"></div>
                
                <div class="w-full flex justify-between items-center text-sm py-2">
                    <span class="text-dark-400">Status Akun</span>
                    @if($user->isAdmin())
                        <span class="badge bg-primary-500/20 text-primary-400 border border-primary-500/20">Administrator</span>
                    @else
                        <span class="badge bg-success-500/20 text-success-500 border border-success-500/20">Suporter</span>
                    @endif
                </div>
                
                <div class="w-full flex justify-between items-center text-sm py-2">
                    <span class="text-dark-400">Bergabung Sejak</span>
                    <span class="text-white font-medium">{{ $user->created_at->translatedFormat('d M Y') }}</span>
                </div>
            </div>
            
            <!-- Quick navigation links -->
            <div class="card p-6 bg-dark-900 border border-dark-800 space-y-3">
                <a href="{{ route('my-tickets.index') }}" class="flex items-center justify-between p-3 rounded-xl hover:bg-dark-800/50 transition-colors text-dark-300 hover:text-white group">
                    <div class="flex items-center gap-3">
                        <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z" />
                        </svg>
                        <span class="font-medium text-sm">Tiket Saya</span>
                    </div>
                    <svg class="w-4 h-4 text-dark-500 group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </a>
            </div>
        </div>
        
        <!-- Right Column: Settings Forms -->
        <div class="lg:col-span-2 space-y-8 animate-slide-up">
            
            <!-- Update Profile Information Form Card -->
            <div class="card p-6 lg:p-8 bg-dark-900 border border-dark-800">
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-white mb-1">Informasi Profil</h3>
                    <p class="text-dark-400 text-sm">Perbarui nama dan alamat email akun Anda.</p>
                </div>
                
                @include('profile.partials.update-profile-information-form')
            </div>
            
            <!-- Update Password Form Card -->
            <div class="card p-6 lg:p-8 bg-dark-900 border border-dark-800">
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-white mb-1">Perbarui Password</h3>
                    <p class="text-dark-400 text-sm">Pastikan akun Anda menggunakan password yang acak dan aman.</p>
                </div>
                
                @include('profile.partials.update-password-form')
            </div>
            
            <!-- Delete Account Card -->
            <div class="card p-6 lg:p-8 bg-dark-900 border border-primary-500/20">
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-primary-500 mb-1">Hapus Akun</h3>
                    <p class="text-dark-400 text-sm">Setelah akun Anda dihapus, semua data dan sumber daya yang terkait akan dihapus secara permanen.</p>
                </div>
                
                @include('profile.partials.delete-user-form')
            </div>
            
        </div>
    </div>
</div>
@endsection
