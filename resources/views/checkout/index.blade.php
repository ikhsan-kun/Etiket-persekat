@extends('layouts.app')

@section('title', 'Checkout - ' . $match->opponent)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12" style="padding-top: 7rem;" x-data="checkoutForm()">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-black font-display text-white mb-2">Pilih Tiket</h1>
            <p class="text-dark-300">Persekat vs {{ $match->opponent }} &bull; {{ $match->match_date->translatedFormat('d M Y') }}</p>
        </div>
        <div class="hidden sm:flex items-center gap-2 text-sm">
            <span class="w-8 h-8 rounded-full bg-primary-600 text-white flex items-center justify-center font-bold">1</span>
            <span class="text-white font-medium">Pilih</span>
            <div class="w-8 h-px bg-dark-600 mx-2"></div>
            <span class="w-8 h-8 rounded-full bg-dark-800 text-dark-400 flex items-center justify-center font-bold border border-dark-600">2</span>
            <span class="text-dark-400 font-medium">Bayar</span>
        </div>
    </div>

    <form action="{{ route('checkout.store', $match) }}" method="POST" id="checkout-form">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Ticket Selection & Map -->
            <div class="md:col-span-2 space-y-6">
                <!-- Stadium Map & View Card -->
                <div class="bg-dark-900 border border-dark-800 rounded-3xl p-6 shadow-xl space-y-6">
                    <div>
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path></svg>
                            Peta & Denah Tribun
                        </h3>
                        <p class="text-xs text-dark-400">Pilih area tribun langsung di peta atau pilih dari daftar kartu di bawah untuk melihat estimasi pandangan ke lapangan.</p>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-center">
                        <!-- SVG Interactive Map -->
                        <div class="lg:col-span-7 flex justify-center bg-dark-950 rounded-2xl p-4 border border-dark-800/80 relative overflow-hidden">
                            <svg viewBox="0 0 400 300" class="w-full max-w-[280px] sm:max-w-[320px] h-auto drop-shadow-[0_8px_24px_rgba(0,0,0,0.4)]">
                                <!-- Field / Pitch -->
                                <rect x="120" y="90" width="160" height="120" rx="8" fill="#15803d" stroke="#166534" stroke-width="2" />
                                <line x1="200" y1="90" x2="200" y2="210" stroke="#ffffff" stroke-width="1.5" stroke-opacity="0.6" />
                                <circle cx="200" cy="150" r="25" fill="none" stroke="#ffffff" stroke-width="1.5" stroke-opacity="0.6" />
                                <rect x="120" y="120" width="20" height="60" fill="none" stroke="#ffffff" stroke-width="1.5" stroke-opacity="0.6" />
                                <rect x="260" y="120" width="20" height="60" fill="none" stroke="#ffffff" stroke-width="1.5" stroke-opacity="0.6" />
                                
                                <!-- Utara Stand (Top) -->
                                <path d="M 90,15 C 150,0 250,0 310,15 L 280,75 C 240,65 160,65 120,75 Z"
                                      @click="selectTribuneByName('utara')"
                                      class="transition-all duration-300 cursor-pointer stroke-[1.5px]"
                                      :class="activeTribuneType === 'utara' ? 'fill-primary-600 stroke-primary-400 drop-shadow-[0_0_8px_#ef4444]' : 'fill-dark-800/90 stroke-dark-700/80 hover:fill-dark-700'" />
                                <text x="200" y="45" fill="#ffffff" text-anchor="middle" font-size="9" font-weight="bold" class="pointer-events-none select-none uppercase tracking-wide opacity-80">UTARA</text>

                                <!-- Selatan Stand (Bottom) -->
                                <path d="M 90,285 C 150,300 250,300 310,285 L 280,225 C 240,235 160,235 120,225 Z"
                                      @click="selectTribuneByName('selatan')"
                                      class="transition-all duration-300 cursor-pointer stroke-[1.5px]"
                                      :class="activeTribuneType === 'selatan' ? 'fill-primary-600 stroke-primary-400 drop-shadow-[0_0_8px_#ef4444]' : 'fill-dark-800/90 stroke-dark-700/80 hover:fill-dark-700'" />
                                <text x="200" y="258" fill="#ffffff" text-anchor="middle" font-size="9" font-weight="bold" class="pointer-events-none select-none uppercase tracking-wide opacity-80">SELATAN</text>

                                <!-- VIP / Barat Stand (Left) -->
                                <path d="M 90,15 C 30,70 30,230 90,285 L 120,225 C 80,180 80,120 120,75 Z"
                                      @click="selectTribuneByName('vip')"
                                      class="transition-all duration-300 cursor-pointer stroke-[1.5px]"
                                      :class="activeTribuneType === 'vip' ? 'fill-primary-600 stroke-primary-400 drop-shadow-[0_0_8px_#ef4444]' : 'fill-dark-800/90 stroke-dark-700/80 hover:fill-dark-700'" />
                                <text x="68" y="150" fill="#ffffff" text-anchor="middle" font-size="9" font-weight="bold" transform="rotate(-90 68 150)" class="pointer-events-none select-none uppercase tracking-wide opacity-80">VIP / BARAT</text>

                                <!-- Timur Stand (Right) -->
                                <path d="M 310,15 C 370,70 370,230 310,285 L 280,225 C 320,180 320,120 280,75 Z"
                                      @click="selectTribuneByName('timur')"
                                      class="transition-all duration-300 cursor-pointer stroke-[1.5px]"
                                      :class="activeTribuneType === 'timur' ? 'fill-primary-600 stroke-primary-400 drop-shadow-[0_0_8px_#ef4444]' : 'fill-dark-800/90 stroke-dark-700/80 hover:fill-dark-700'" />
                                <text x="332" y="150" fill="#ffffff" text-anchor="middle" font-size="9" font-weight="bold" transform="rotate(90 332 150)" class="pointer-events-none select-none uppercase tracking-wide opacity-80">TIMUR</text>
                            </svg>
                        </div>

                        <!-- View Preview -->
                        <div class="lg:col-span-5 space-y-4">
                            <div class="bg-dark-950/50 border border-dark-800 rounded-2xl p-4 space-y-3">
                                <span class="text-xs font-semibold text-primary-500 uppercase tracking-widest block">Sudut Pandang Lapangan</span>
                                <div class="relative rounded-xl overflow-hidden aspect-video bg-dark-900 border border-dark-800 flex items-center justify-center">
                                    <img src="/images/stadium_hero.png" x-show="activeTribuneType === 'vip'" class="w-full h-full object-cover animate-fade-in" alt="VIP View">
                                    <img src="/images/tim.jpg" x-show="activeTribuneType === 'timur'" class="w-full h-full object-cover animate-fade-in" alt="East View">
                                    <img src="/images/gor.jpg" x-show="activeTribuneType === 'utara'" class="w-full h-full object-cover animate-fade-in" alt="North View">
                                    <img src="/images/player_celebration.png" x-show="activeTribuneType === 'selatan'" class="w-full h-full object-cover animate-fade-in" alt="South View">
                                    
                                    <div class="absolute bottom-2 left-2 bg-dark-950/80 backdrop-blur px-2.5 py-1 rounded-md border border-dark-800 text-[10px] font-semibold text-white font-mono" x-text="activeTribuneName"></div>
                                </div>
                                <div class="space-y-1">
                                    <h4 class="text-sm font-bold text-white" x-text="activeTribuneName"></h4>
                                    <p class="text-xs text-dark-400 leading-relaxed min-h-[50px]" x-text="activeTribuneDescription"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ticket Cards List -->
                @foreach($match->ticketCategories as $index => $category)
                    <div class="border transition-all duration-300 rounded-2xl p-6 cursor-pointer"
                         @click="activeTribuneIndex = {{ $index }}"
                         :class="[
                             activeTribuneIndex === {{ $index }} ? 'border-primary-500 bg-dark-800/60 ring-1 ring-primary-500/20 shadow-lg' : 'bg-dark-900 border-dark-800 hover:border-primary-500/30',
                             {{ $category->isAvailable() ? '1' : '0' }} === 1 ? '' : 'opacity-60 cursor-not-allowed'
                         ]">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <h3 class="text-xl font-bold text-white">{{ $category->name }}</h3>
                                    <span x-show="activeTribuneIndex === {{ $index }}" class="w-2.5 h-2.5 rounded-full bg-primary-500 animate-ping"></span>
                                </div>
                                <p class="text-primary-400 font-semibold text-lg mb-2">Rp {{ number_format($category->price, 0, ',', '.') }}</p>
                                @if($category->isAvailable())
                                    <span class="text-xs text-dark-400">Tersisa {{ $category->availableQuota() }} tiket</span>
                                @else
                                    <span class="badge bg-primary-500/20 text-primary-500">Habis</span>
                                @endif
                            </div>
                            
                            @if($category->isAvailable())
                                <div class="flex items-center gap-4 bg-dark-800 rounded-xl p-2 border border-dark-700" @click.stop>
                                    <button type="button" @click="decrement({{ $index }})" class="w-10 h-10 rounded-lg flex items-center justify-center text-dark-300 hover:text-white hover:bg-dark-700 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                    </button>
                                    
                                    <input type="hidden" name="tickets[{{ $index }}][category_id]" value="{{ $category->id }}">
                                    <input type="number" name="tickets[{{ $index }}][quantity]" x-model="tickets[{{ $index }}].quantity" readonly class="w-12 text-center bg-transparent text-white font-bold text-xl border-none focus:ring-0 p-0" min="0" max="{{ min(4, $category->availableQuota()) }}">
                                    
                                    <button type="button" @click="increment({{ $index }}, {{ min(4, $category->availableQuota()) }})" class="w-10 h-10 rounded-lg flex items-center justify-center text-dark-300 hover:text-white hover:bg-dark-700 transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500" :disabled="totalTickets >= 4">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Order Summary -->
            <div class="md:col-span-1">
                <div class="sticky top-24 bg-dark-900 border border-dark-800 rounded-3xl p-6 shadow-2xl">
                    <h3 class="text-lg font-bold text-white mb-6 border-b border-dark-800 pb-4">Ringkasan Pesanan</h3>
                    
                    <div class="space-y-4 mb-6 min-h-[100px]">
                        <template x-for="(ticket, index) in selectedTickets" :key="index">
                            <div class="flex justify-between text-sm">
                                <div class="text-dark-300">
                                    <span x-text="ticket.quantity"></span>x <span x-text="ticket.name"></span>
                                </div>
                                <div class="text-white font-medium">
                                    Rp <span x-text="formatMoney(ticket.subtotal)"></span>
                                </div>
                            </div>
                        </template>
                        
                        <div x-show="totalTickets === 0" class="text-dark-400 text-sm italic text-center py-4">
                            Belum ada tiket dipilih
                        </div>
                    </div>
                    
                    <div class="border-t border-dark-800 pt-4 mb-6">
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-dark-300">Total Tiket</span>
                            <span class="text-white font-bold"><span x-text="totalTickets"></span> / 4</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-white font-bold">Total Harga</span>
                            <span class="text-primary-400 font-bold text-xl">Rp <span x-text="formatMoney(totalAmount)"></span></span>
                        </div>
                    </div>

                    <button type="submit" class="btn-primary w-full py-4 relative group overflow-hidden" :disabled="totalTickets === 0" :class="{ 'opacity-50 cursor-not-allowed': totalTickets === 0 }">
                        <span class="relative z-10 font-bold">Lanjutkan Pembayaran</span>
                        <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out"></div>
                    </button>
                    
                    <p class="text-xs text-dark-500 text-center mt-4">
                        Dengan menekan tombol di atas, Anda menyetujui <a href="#" class="text-primary-500 hover:underline">Syarat & Ketentuan</a> yang berlaku.
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
function checkoutForm() {
    return {
        activeTribuneIndex: 0,
        tickets: [
            @foreach($match->ticketCategories as $index => $category)
            {
                index: {{ $index }},
                id: {{ $category->id }},
                name: '{{ $category->name }}',
                price: {{ $category->price }},
                quantity: 0
            },
            @endforeach
        ],
        
        get activeTribuneName() {
            return this.tickets[this.activeTribuneIndex]?.name || 'Pilih Tribun';
        },
        
        get activeTribuneType() {
            const name = this.activeTribuneName.toLowerCase();
            if (name.includes('vip')) return 'vip';
            if (name.includes('timur')) return 'timur';
            if (name.includes('utara')) return 'utara';
            if (name.includes('selatan')) return 'selatan';
            return 'vip';
        },

        get activeTribuneDescription() {
            const type = this.activeTribuneType;
            if (type === 'vip') {
                return 'Pandangan terbaik di garis tengah lapangan dengan kursi tunggal yang nyaman di bawah atap peneduh utama.';
            } else if (type === 'timur') {
                return 'Tribun terbuka di sisi timur lapangan. Sangat cocok untuk menikmati atmosfer pertandingan yang semarak bersama keluarga.';
            } else if (type === 'utara') {
                return 'Tribun berdiri di belakang gawang utara. Basis utama kelompok suporter garis keras yang bernyanyi tanpa henti.';
            } else if (type === 'selatan') {
                return 'Tribun berdiri di belakang gawang selatan. Atmosfer ramah suporter dan dekat dengan aksi menyerang pertahanan lawan.';
            }
            return 'Silakan pilih tribun untuk melihat rincian.';
        },

        selectTribuneByName(name) {
            const foundIndex = this.tickets.findIndex(t => t.name.toLowerCase().includes(name));
            if (foundIndex !== -1) {
                this.activeTribuneIndex = foundIndex;
            }
        },
        
        get totalTickets() {
            return this.tickets.reduce((sum, ticket) => sum + parseInt(ticket.quantity || 0), 0);
        },
        
        get totalAmount() {
            return this.tickets.reduce((sum, ticket) => sum + (parseInt(ticket.quantity || 0) * ticket.price), 0);
        },
        
        get selectedTickets() {
            return this.tickets.filter(t => t.quantity > 0).map(t => ({
                ...t,
                subtotal: t.quantity * t.price
            }));
        },
        
        increment(index, max) {
            if (this.totalTickets >= 4) {
                alert('Maksimal pembelian adalah 4 tiket per akun.');
                return;
            }
            if (this.tickets[index].quantity < max) {
                this.tickets[index].quantity++;
            }
        },
        
        decrement(index) {
            if (this.tickets[index].quantity > 0) {
                this.tickets[index].quantity--;
            }
        },
        
        formatMoney(amount) {
            return new Intl.NumberFormat('id-ID').format(amount);
        }
    }
}
</script>
@endsection
