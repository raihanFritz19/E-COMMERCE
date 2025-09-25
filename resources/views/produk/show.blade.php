@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto mt-10 px-4">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white p-6 rounded-lg shadow">

        {{-- FOTO PRODUK --}}
        <div class="flex justify-center items-start">
            @if($produk->foto)
                <img src="{{ asset('storage/' . $produk->foto) }}" alt="{{ $produk->nama }}"
                     class="w-full max-w-md rounded-lg object-cover border">
            @else
                <div class="w-full h-64 bg-gray-100 flex items-center justify-center text-gray-400 rounded-lg">Tidak ada foto</div>
            @endif
        </div>

        {{-- DETAIL PRODUK --}}
        <div>
            <h1 class="text-2xl font-bold text-gray-800 mb-2">{{ $produk->nama }}</h1>
            <p class="text-gray-500 mb-1"><span class="font-semibold">Kategori:</span> {{ $produk->kategori }}</p>
            <p class="text-gray-500 mb-1"><span class="font-semibold">Stok:</span> {{ $produk->stok }}</p>
            <p class="text-green-600 font-bold text-xl mt-2 mb-4">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>

            <h2 class="text-lg font-semibold text-gray-800 mb-2">Deskripsi</h2>
            <p class="text-gray-600 leading-relaxed mb-6">{{ $produk->deskripsi }}</p>

            <div class="flex flex-col sm:flex-row gap-3">
                @if($produk->stok > 0)
                    <form action="{{ route('keranjang.tambah', $produk->id) }}" method="POST" class="flex-1">
                        @csrf
                        <button type="submit" class="w-full bg-pink-600 text-white py-2 rounded hover:bg-pink-700 transition">
                            Masukkan Keranjang
                        </button>
                    </form>
                @else
                    <button class="w-full bg-gray-400 text-white py-0.5 rounded cursor-not-allowed text-xs" disabled>
                        Stok Habis
                    </button>
                @endif
                <a href="{{ route('produk.index') }}" class="flex-1 text-center bg-gray-200 text-gray-800 py-2 rounded hover:bg-gray-300 transition">
                    Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection