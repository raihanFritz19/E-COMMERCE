@extends('layouts.app')

@section('content')
{{-- Banner --}}
<div class="relative bg-cover bg-center rounded-lg shadow mb-10" style="background-image: url('{{ asset('images/welcome.jpg') }}'); height: 400px;">
    <div class="bg-black/40 w-full h-full absolute top-0 left-0 rounded-lg"></div>
    <div class="relative z-10 flex flex-col items-center justify-center text-center h-full px-4 text-white">
        <h1 class="text-3xl md:text-4xl font-extrabold mb-2">Selamat Datang di <span class="text-green-300">Toko Online Kami</span></h1>
        <p class="text-base md:text-lg">Belanja mudah, cepat, dan terpercaya bersama <span class="text-pink-300 font-semibold">Larizza Kitchen</span>.</p>
    </div>
</div>

{{-- Navigasi Kategori --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">
    <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
        <h2 class="text-xl font-semibold text-gray-800">Produk Unggulan</h2>
        <p class="mt-1 text-gray-600">Lihat berbagai produk terbaik kami.</p>
        <a href="/produk" class="inline-block mt-4 text-green-600 hover:underline font-semibold">Lihat Produk</a>
    </div>

    <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
        <h2 class="text-xl font-semibold text-gray-800">Keranjang</h2>
        <p class="mt-1 text-gray-600">Cek barang yang sudah kamu pilih.</p>
        <a href="/keranjang" class="inline-block mt-4 text-pink-600 hover:underline font-semibold">Lihat Keranjang</a>
    </div>

    <div class="bg-white p-6 rounded-lg shadow hover:shadow-lg transition">
        <h2 class="text-xl font-semibold text-gray-800">Pesanan</h2>
        <p class="mt-1 text-gray-600">Lacak status pesanan kamu di sini.</p>
        <a href="/riwayat" class="inline-block mt-4 text-green-600 hover:underline font-semibold">Lihat Pesanan</a>
    </div>
</div>

{{-- Produk Terbaru --}}
@if($produkTerbaru->count())
    <h2 class="text-3xl font-bold text-center text-green-700 mb-10">Produk Terbaru</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8 mb-16">
        @foreach ($produkTerbaru as $produk)
            <div class="bg-white border-2 border-green-300 rounded-3xl shadow-md hover:shadow-lg transition duration-300 transform hover:-translate-y-1 p-5 flex flex-col items-center text-center">
                <img src="{{ asset('storage/' . $produk->foto) }}" alt="{{ $produk->nama }}" class="w-full h-60 object-cover rounded-xl mb-4">
                <h3 class="text-lg font-bold text-green-800">{{ $produk->nama }}</h3>
                <p class="text-gray-600 text-sm mt-1 mb-3">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                <a href="{{ route('produk.show', $produk->id) }}" class="inline-block mt-auto px-8 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition">
                    Lihat Detail
                </a>
            </div>
        @endforeach
    </div>
@endif

{{-- Testimoni Pelanggan (Tanpa Swiper) --}}
@if($testimonis->count())
    <h2 class="text-2xl font-bold mb-6 text-center text-green-700">Testimoni Pelanggan</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 px-4 md:px-16 mb-16">
        @foreach($testimonis as $testimoni)
            <div class="bg-white shadow rounded-xl p-6 h-full flex flex-col justify-between">
                <p class="italic mb-3">“{{ $testimoni->isi }}”</p>
                <p class="text-sm text-gray-700">
                    – <strong>{{ $testimoni->user->name }}</strong> tentang <em>{{ $testimoni->produk->nama }}</em>
                </p>
                @if($testimoni->rating)
                    <div class="text-yellow-400 mt-2">
                        @for ($i = 0; $i < $testimoni->rating; $i++)
                            ★
                        @endfor
                        @for ($i = $testimoni->rating; $i < 5; $i++)
                            ☆
                        @endfor
                    </div>
                @endif
            </div>
        @endforeach
    </div>
@else
    <p class="text-center text-gray-500 mb-10">Belum ada testimoni pelanggan.</p>
@endif
@endsection