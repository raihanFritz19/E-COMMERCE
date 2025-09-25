@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto py-12 px-4 sm:px-8">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-3xl font-extrabold text-green-800 flex items-center gap-2">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
            Tambah Produk
        </h2>
        <a href="{{ route('admin.produk.index') }}"
           class="inline-flex items-center gap-1 bg-gray-200 hover:bg-gray-300 text-green-700 text-sm font-semibold px-5 py-2 rounded-lg border border-gray-300 shadow transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
            Kembali ke Produk
        </a>
    </div>

    <form action="{{ route('admin.produk.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-8 rounded-2xl shadow-lg border border-green-100 space-y-7">
        @csrf

        <div>
            <label class="block text-green-900 font-semibold mb-2">Nama Produk</label>
            <input type="text" name="nama" class="w-full border border-green-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-200" required>
        </div>

        <div>
            <label class="block text-green-900 font-semibold mb-2">Kategori</label>
            <select name="kategori" class="w-full border border-green-200 rounded-lg px-4 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-green-200" required>
                <option value="">-- Pilih Kategori --</option>
                <option value="Kue Kering">Makanan Kering</option>
                <option value="Kue Basah">Makanan Basah</option>
                <option value="Kue Tradisional">Kue Tradisional</option>
            </select>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-7">
            <div>
                <label class="block text-green-900 font-semibold mb-2">Harga</label>
                <input type="number" name="harga" min="0" class="w-full border border-green-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-200" required>
            </div>
            <div>
                <label class="block text-green-900 font-semibold mb-2">Stok</label>
                <input type="number" name="stok" min="0" class="w-full border border-green-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-200" required>
            </div>
        </div>

        <div>
            <label class="block text-green-900 font-semibold mb-2">Deskripsi</label>
            <textarea name="deskripsi" rows="4" class="w-full border border-green-200 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-200" placeholder="Tulis deskripsi produk..."></textarea>
        </div>

        <div>
            <label class="block text-green-900 font-semibold mb-2">Foto Produk</label>
            <input type="file" name="foto" id="foto" accept="image/*"
                class="w-full text-sm text-green-700 file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-green-100 file:text-green-700 hover:file:bg-green-200 rounded-lg">
            <div id="preview" class="mt-4">
                <img src="#" alt="Preview Foto" class="max-w-xs rounded-lg border-2 border-green-200 shadow hidden" id="preview-img">
            </div>
        </div>

        <div class="text-right">
           <button type="submit" class="bg-green-600 hover:bg-green-700 text-white text-lg font-bold px-10 py-2 rounded-lg shadow-md transition duration-300 flex items-center gap-2 justify-end mx-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                Simpan
            </button>
        </div>
    </form>
</div>

{{-- Preview Foto Produk --}}
<script>
    document.getElementById('foto').addEventListener('change', function (event) {
        const [file] = event.target.files;
        const previewImg = document.getElementById('preview-img');
        if (file) {
            previewImg.src = URL.createObjectURL(file);
            previewImg.classList.remove('hidden');
        } else {
            previewImg.src = '#';
            previewImg.classList.add('hidden');
        }
    });
</script>
@endsection