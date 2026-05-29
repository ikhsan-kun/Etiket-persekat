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
            <!-- Ticket Selection -->
            <div class="md:col-span-2 space-y-4">
                @foreach($match->ticketCategories as $index => $category)
                    <div class="bg-dark-900 border {{ $category->isAvailable() ? 'border-dark-700 hover:border-primary-500/50' : 'border-dark-800 opacity-60' }} rounded-2xl p-6 transition-colors">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <h3 class="text-xl font-bold text-white mb-1">{{ $category->name }}</h3>
                                <p class="text-primary-400 font-semibold text-lg mb-2">Rp {{ number_format($category->price, 0, ',', '.') }}</p>
                                @if($category->isAvailable())
                                    <span class="text-xs text-dark-400">Tersisa {{ $category->availableQuota() }} tiket</span>
                                @else
                                    <span class="badge bg-primary-500/20 text-primary-500">Habis</span>
                                @endif
                            </div>
                            
                            @if($category->isAvailable())
                                <div class="flex items-center gap-4 bg-dark-800 rounded-xl p-2 border border-dark-700">
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
