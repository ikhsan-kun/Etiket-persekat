<section>
    <form method="post" action="{{ route('password.update') }}" class="space-y-6">
        @csrf
        @method('put')

        <!-- Current Password -->
        <div>
            <label for="update_password_current_password" class="block text-xs font-bold uppercase tracking-wider text-dark-300 mb-2">Password Saat Ini</label>
            <input id="update_password_current_password" name="current_password" type="password" required autocomplete="current-password"
                   class="w-full px-5 py-3 bg-dark-950 border border-dark-800 rounded-xl text-white placeholder-dark-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                   placeholder="••••••••">
            @error('current_password', 'updatePassword')
                <p class="text-primary-400 text-xs font-medium mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- New Password -->
        <div>
            <label for="update_password_password" class="block text-xs font-bold uppercase tracking-wider text-dark-300 mb-2">Password Baru</label>
            <input id="update_password_password" name="password" type="password" required autocomplete="new-password"
                   class="w-full px-5 py-3 bg-dark-950 border border-dark-800 rounded-xl text-white placeholder-dark-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                   placeholder="••••••••">
            @error('password', 'updatePassword')
                <p class="text-primary-400 text-xs font-medium mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div>
            <label for="update_password_password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-dark-300 mb-2">Konfirmasi Password Baru</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password"
                   class="w-full px-5 py-3 bg-dark-950 border border-dark-800 rounded-xl text-white placeholder-dark-500 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-all"
                   placeholder="••••••••">
            @error('password_confirmation', 'updatePassword')
                <p class="text-primary-400 text-xs font-medium mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Save Button and status message -->
        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-primary-600 text-white font-bold rounded-xl hover:bg-primary-500 transition-all shadow-[0_0_15px_rgba(220,38,38,0.2)] hover:shadow-[0_0_25px_rgba(220,38,38,0.4)] uppercase tracking-wider text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 focus:ring-offset-dark-900">
                Perbarui Password
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }"
                   x-show="show"
                   x-transition
                   x-init="setTimeout(() => show = false, 3000)"
                   class="text-sm text-success-500 font-semibold flex items-center gap-1.5 animate-fade-in">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                    Password diperbarui
                </p>
            @endif
        </div>
    </form>
</section>
