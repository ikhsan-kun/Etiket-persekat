<section class="space-y-6" x-data="{ open: {{ $errors->userDeletion->isNotEmpty() ? 'true' : 'false' }} }">
    <div>
        <p class="text-dark-300 text-sm mb-5 leading-relaxed">
            Setelah akun Anda dihapus, semua data dan sumber daya yang terkait akan dihapus secara permanen. Silakan unduh data penting apa pun sebelum melanjutkan.
        </p>
        
        <button type="button" @click="open = true" class="inline-flex items-center justify-center px-6 py-3 bg-primary-600/10 text-primary-400 hover:bg-primary-600 hover:text-white border border-primary-500/20 hover:border-transparent font-semibold rounded-xl transition-all duration-200 cursor-pointer">
            Hapus Akun Saya
        </button>
    </div>

    <!-- Modal Background overlay and container -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-dark-950/85 backdrop-blur-md"
         style="display: none;">
         
        <!-- Modal Card -->
        <div @click.away="open = false" 
             class="bg-dark-900 border border-dark-800 rounded-2xl w-full max-w-lg p-6 lg:p-8 shadow-2xl relative animate-slide-up">
            
            <div class="flex items-center gap-3 mb-4 text-primary-500">
                <div class="w-10 h-10 rounded-full bg-primary-500/10 flex items-center justify-center">
                    <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-white">Hapus Akun Permanen?</h3>
            </div>
            
            <p class="text-dark-400 text-sm mb-6 leading-relaxed">
                Tindakan ini tidak dapat dibatalkan. Masukkan password akun Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun ini secara permanen.
            </p>

            <form method="post" action="{{ route('profile.destroy') }}" class="space-y-6">
                @csrf
                @method('delete')

                <div>
                    <label for="password" class="block text-xs font-bold uppercase tracking-wider text-dark-300 mb-2">Password Anda</label>
                    <input id="password" name="password" type="password" required
                           class="w-full px-5 py-3 bg-dark-950 border border-dark-800 rounded-xl text-white placeholder-dark-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                           placeholder="Masukkan password Anda">
                    @error('password', 'userDeletion')
                        <p class="text-primary-400 text-xs font-medium mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex flex-col sm:flex-row justify-end gap-3 pt-2">
                    <button type="button" @click="open = false" class="btn-secondary w-full sm:w-auto px-6 py-2.5">
                        Batal
                    </button>
                    
                    <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-primary-600 text-white font-bold rounded-xl hover:bg-primary-500 transition-all shadow-[0_0_15px_rgba(220,38,38,0.2)] hover:shadow-[0_0_25px_rgba(220,38,38,0.4)] uppercase tracking-wider text-sm">
                        Hapus Akun
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
