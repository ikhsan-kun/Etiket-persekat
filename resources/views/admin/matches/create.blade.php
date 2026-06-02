@extends('layouts.admin')

@section('title', 'Tambah Pertandingan')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.matches.index') }}" class="inline-flex items-center text-dark-400 hover:text-white transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <form action="{{ route('admin.matches.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8" x-data="matchForm()">
        @csrf

        <!-- Informasi Utama -->
        <div class="bg-dark-900 border border-dark-800 rounded-3xl p-6 md:p-8 shadow-sm">
            <h3 class="text-xl font-bold text-white mb-6 border-b border-dark-800 pb-4">Informasi Utama</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <label for="opponent" class="label-field">Tim Lawan <span class="text-primary-500">*</span></label>
                    <input type="text" id="opponent" name="opponent" value="{{ old('opponent') }}" required class="input-field" placeholder="Contoh: PSMS Medan">
                    @error('opponent') <span class="text-primary-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                
                <div>
                    <label for="match_date" class="label-field">Tanggal & Waktu Kick-off <span class="text-primary-500">*</span></label>
                    <input type="datetime-local" id="match_date" name="match_date" value="{{ old('match_date') }}" required class="input-field" style="color-scheme: dark;">
                    @error('match_date') <span class="text-primary-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="location" class="label-field">Lokasi / Stadion <span class="text-primary-500">*</span></label>
                    <input type="text" id="location" name="location" value="{{ old('location', 'Stadion Wijaya Kusuma, Tegal') }}" required class="input-field">
                    @error('location') <span class="text-primary-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="label-field">Deskripsi</label>
                    <textarea id="description" name="description" rows="4" class="input-field">{{ old('description') }}</textarea>
                    <p class="text-dark-500 text-xs mt-1">Gunakan Markdown untuk format text.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="status" class="label-field">Status <span class="text-primary-500">*</span></label>
                    <select id="status" name="status" required class="input-field">
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft (Sembunyikan)</option>
                        <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>Published (Buka Penjualan)</option>
                    </select>
                </div>

                <div>
                    <label for="banner_image" class="label-field">Banner / Poster (Opsional)</label>
                    <input type="file" id="banner_image" name="banner_image" accept="image/*" class="w-full text-sm text-dark-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-dark-800 file:text-white hover:file:bg-dark-700 cursor-pointer">
                    @error('banner_image') <span class="text-primary-400 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <!-- Kategori Tiket -->
        <div class="bg-dark-900 border border-dark-800 rounded-3xl p-6 md:p-8 shadow-sm">
            <div class="flex items-center justify-between border-b border-dark-800 pb-4 mb-6">
                <div>
                    <h3 class="text-xl font-bold text-white mb-1">Kategori Tiket</h3>
                    <p class="text-dark-400 text-sm">Tambahkan tribun, harga, dan kuota tiket.</p>
                </div>
                <button type="button" @click="addCategory()" class="btn-outline px-4 py-2 text-sm border-dark-600 text-white hover:border-primary-500">
                    + Tambah Kategori
                </button>
            </div>

            <div class="space-y-4">
                <template x-for="(category, index) in categories" :key="index">
                    <div class="flex flex-col md:flex-row gap-4 items-start md:items-center bg-dark-800/50 p-4 rounded-2xl border border-dark-700/50">
                        <div class="flex-1 w-full">
                            <label class="label-field text-xs">Nama Kategori</label>
                            <input type="text" x-model="category.name" :name="'categories['+index+'][name]'" required class="input-field py-2 text-sm" placeholder="Contoh: VIP">
                        </div>
                        <div class="w-full md:w-1/3">
                            <label class="label-field text-xs">Harga (Rp)</label>
                            <input type="number" x-model="category.price" :name="'categories['+index+'][price]'" required class="input-field py-2 text-sm" min="0" placeholder="50000">
                        </div>
                        <div class="w-full md:w-1/4">
                            <label class="label-field text-xs">Kuota</label>
                            <input type="number" x-model="category.quota" :name="'categories['+index+'][quota]'" required class="input-field py-2 text-sm" min="1" placeholder="100">
                        </div>
                        <div class="pt-6">
                            <button type="button" @click="removeCategory(index)" x-show="categories.length > 1" class="text-dark-500 hover:text-primary-400 p-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                </template>
            </div>
            @error('categories') <span class="text-primary-400 text-xs mt-2 block">{{ $message }}</span> @enderror
        </div>

        <div class="flex justify-end gap-4">
            <a href="{{ route('admin.matches.index') }}" class="btn-secondary">Batal</a>
            <button type="submit" class="btn-primary">Simpan Pertandingan</button>
        </div>
    </form>
</div>

<script>
function matchForm() {
    return {
        categories: [
            { name: 'Tribun VIP',     price: 60000, quota: 1000 },
            { name: 'Tribun Timur',   price: 50000, quota: 1000 },
            { name: 'Tribun Utara',   price: 35000, quota: 1500 },
            { name: 'Tribun Selatan', price: 35000, quota: 1500 }
        ],
        addCategory() {
            this.categories.push({ name: '', price: '', quota: '' });
        },
        removeCategory(index) {
            this.categories.splice(index, 1);
        }
    }
}
</script>
@endsection
