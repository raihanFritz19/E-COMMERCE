@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 px-2 md:px-0">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Sidebar -->
        <aside class="bg-white p-6 rounded-2xl shadow-md border border-pink-200 md:col-span-1">
            <h3 class="text-xl font-bold mb-6 text-pink-700 flex items-center gap-2">
                <svg class="w-6 h-6 text-pink-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18"/></svg>
                Produk Kue
            </h3>
            <ul class="space-y-3">
                <li>
                    <a href="?kategori=Kue Basah" class="block text-center py-2 px-4 rounded-lg border-2 border-pink-500 text-pink-600 font-semibold hover:bg-pink-600 hover:text-white transition shadow-sm hover:shadow-md">
                        Makanan Basah
                    </a>
                </li>
                <li>
                    <a href="?kategori=Kue Kering" class="block text-center py-2 px-4 rounded-lg border-2 border-pink-500 text-pink-600 font-semibold hover:bg-pink-600 hover:text-white transition shadow-sm hover:shadow-md">
                        Makanan Kering
                    </a>
                </li>
                <li>
                    <a href="?kategori=Kue Tradisional" class="block text-center py-2 px-4 rounded-lg border-2 border-pink-500 text-pink-600 font-semibold hover:bg-pink-600 hover:text-white transition shadow-sm hover:shadow-md">
                        Kue Tradisional
                    </a>
                </li>
            </ul>
        </aside>

        <!-- Produk & Pencarian -->
        <section class="md:col-span-3">
            <!-- Search Bar -->
             <div class="mb-8">
                <form method="GET" action="{{ route('produk.index') }}" class="flex flex-col sm:flex-row gap-3 items-stretch">
                    <div class="relative flex-2 w-full">
                        <input type="text" name="q" placeholder="Cari kue favoritmu..." value="{{ request('q') }}"
                            class="border border-pink-200 rounded-lg px-4 py-2 focus:ring-2 focus:ring-pink-400 focus:border-pink-400 w-full pr-10 transition shadow-sm" />
                        <button type="submit" class="absolute right-2 top-2" title="Cari">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-pink-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 4a7 7 0 100 14 7 7 0 000-14zm0 0l6 6m-6-6l-6 6" />
                            </svg>
                        </button>
                    </div>
                    <button type="submit" class="bg-pink-600 text-white px-6 py-2 rounded-lg hover:bg-pink-700 font-semibold transition shadow flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
                        Cari
                    </button>
                    <button
                        type="button"
                        onclick="window.location='{{ route('produk.index') }}'"
                        class="bg-white border border-pink-300 text-pink-600 px-6 py-2 rounded-lg hover:bg-pink-50 hover:text-pink-700 font-semibold transition shadow flex items-center gap-2"
                        title="Refresh / Reset Filter">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582M20 20v-5h-.581M5.42 9A7.975 7.975 0 0 1 12 4c2.21 0 4.21.891 5.66 2.34m.92 4.66A7.975 7.975 0 0 1 12 20c-2.21 0-4.21-.891-5.66-2.34"></path></svg>
                        Refresh
                    </button>
                </form>
            </div>

            <!-- Produk List -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($produks as $produk)
                    <div class="bg-white p-5 rounded-2xl shadow-md hover:shadow-lg transition flex flex-col h-full border border-pink-100 relative group">
                        <!-- Label kategori badge pojok -->
                        <span class="absolute left-0 top-0 m-3 px-3 py-1 rounded-full text-xs font-bold bg-pink-100 text-pink-700 shadow hidden md:inline-block">{{ $produk->kategori }}</span>
                        
                        {{-- FOTO PRODUK --}}
                        @if($produk->foto)
                            <img src="{{ asset('storage/' . $produk->foto) }}" alt="{{ $produk->nama }}" class="h-52 w-full object-cover rounded-lg mb-4 border border-pink-100 shadow-sm group-hover:scale-105 transition" loading="lazy">
                        @else
                            <div class="h-52 bg-gray-100 flex items-center justify-center text-gray-400 rounded-lg mb-4 border border-pink-100">Tidak ada foto</div>
                        @endif

                        <div class="mb-4 flex-1 flex flex-col">
                            <h3 class="font-bold text-gray-900 text-xl mb-1">{{ $produk->nama }}</h3>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-pink-50 text-pink-600">
                                    Stok: {{ $produk->stok }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 mb-2">{{ Str::limit($produk->deskripsi, 60) }}</p>
                            <p class="text-pink-700 font-extrabold text-2xl mt-auto mb-1">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                        </div>

                        <div class="flex gap-2 mt-auto">
                            @if($produk->stok > 0)
                                <form action="{{ route('keranjang.tambah', $produk->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    <button type="submit" class="w-full bg-pink-600 text-white py-2 rounded-lg font-semibold hover:bg-pink-700 transition text-sm shadow">
                                        <svg class="w-4 h-4 inline-block mr-1 -mt-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.6 8.8A1 1 0 007 23h10a1 1 0 001-1.2L17 13M7 13V6a1 1 0 011-1h3m4 0h2a1 1 0 011 1v3" /></svg>
                                        Masukkan Keranjang
                                    </button>
                                </form>
                            @else
                                <button class="w-full bg-gray-400 text-white py-2 rounded-lg font-semibold cursor-not-allowed text-xs shadow" disabled>
                                    Stok Habis
                                </button>
                            @endif
                            <a href="{{ route('produk.show', $produk->id) }}" class="flex-1 text-center bg-pink-50 text-pink-700 py-2 rounded-lg hover:bg-pink-100 transition text-sm font-semibold shadow">
                                Detail
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="text-gray-500 text-lg font-semibold col-span-3 py-10 text-center">Tidak ada produk ditemukan.</div>
                @endforelse
            </div>
        </section>
    </div>
</div>
@endsection