@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white p-8 rounded-2xl shadow-lg border border-green-100">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-green-800 flex items-center gap-2">
                <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18"/>
                </svg>
                Daftar Produk
            </h1>
            <a href="{{ route('admin.produk.create') }}"
               class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm px-5 py-2 rounded-lg shadow transition font-semibold">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                Tambah Produk
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 bg-green-100 text-green-800 rounded-lg shadow font-semibold">
                {{ session('success') }}
            </div>
        @endif   

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm border-separate border-spacing-y-2">
                <thead class="bg-green-100 text-green-800 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="px-4 py-3 text-left">No</th>
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-left">Kategori</th>
                        <th class="px-4 py-3 text-left">Harga</th>
                        <th class="px-4 py-3 text-left">Stok</th>
                        <th class="px-4 py-3 text-left">Deskripsi</th>
                        <th class="px-4 py-3 text-left">Foto</th>
                        <th class="px-4 py-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800">
                    @foreach($produks as $produk)
                        <tr class="bg-white even:bg-green-50 rounded-lg hover:shadow hover:bg-green-50 transition">
                            <td class="px-4 py-3 rounded-l-lg">{{ ($produks->firstItem() ?? 1) + $loop->index }}</td>
                            <td class="px-4 py-3 font-semibold">{{ $produk->nama }}</td>
                            <td class="px-4 py-3">{{ $produk->kategori }}</td>
                            <td class="px-4 py-3 font-bold text-green-700">Rp{{ number_format($produk->harga) }}</td>
                            <td class="px-4 py-3">{{ $produk->stok }}</td>
                            <td class="px-4 py-3">{{ Str::limit($produk->deskripsi, 50) }}</td>
                            <td class="px-4 py-3">
                                @if($produk->foto)
                                    <a href="{{ asset('storage/' . $produk->foto) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $produk->foto) }}" class="w-16 h-16 object-cover rounded shadow border border-green-200 hover:scale-105 transition" loading="lazy">
                                    </a>
                                @else
                                    <span class="text-gray-400 italic">Tidak ada foto</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 rounded-r-lg space-y-2 min-w-[120px]">
                                <a href="{{ route('admin.produk.edit', $produk->id) }}"
                                   class="inline-flex items-center gap-1 bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded text-xs shadow font-semibold transition">
                                   <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6-6m2-2a2.828 2.828 0 114 4l-9 9H5v-4l9-9z"></path></svg>
                                   Edit
                                </a>
                                <form action="{{ route('admin.produk.destroy', $produk->id) }}" method="POST" class="inline-block"
                                      onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center gap-1 bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded text-xs shadow font-semibold transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-end">
            {{ $produks->links() }}
        </div>
    </div>
</div>
@endsection