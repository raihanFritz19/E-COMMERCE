@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-2xl font-bold text-green-800 flex items-center gap-2">
            <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M9 16h6"></path></svg>
            Detail Transaksi
        </h1>
        <a href="{{ route('admin.transaksi.index') }}" class="inline-flex items-center px-5 py-2 bg-green-100 hover:bg-green-200 text-green-700 rounded-lg shadow font-semibold transition">
            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
            Kembali
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="p-5 border border-green-100 rounded-xl bg-gray-50 shadow-sm">
            <div class="text-xs text-gray-500 font-semibold">Nama Customer</div>
            <div class="font-bold text-lg text-green-900">{{ $transaksi->user->name ?? 'User tidak ditemukan' }}</div>
        </div>
        <div class="p-5 border border-green-100 rounded-xl bg-gray-50 shadow-sm">
            <div class="text-xs text-gray-500 font-semibold">Alamat</div>
            <div class="text-base text-gray-700">{{ $transaksi->alamat }}</div>
        </div>
        <div class="p-5 border border-green-100 rounded-xl bg-gray-50 shadow-sm">
            <div class="text-xs text-gray-500 font-semibold">Telepon</div>
            <div class="text-base text-gray-700">{{ $transaksi->telepon }}</div>
        </div>
        <div class="p-5 border border-green-100 rounded-xl bg-gray-50 shadow-sm">
            <div class="text-xs text-gray-500 font-semibold">Tanggal Transaksi</div>
            <div class="text-base text-gray-700">{{ $transaksi->created_at->format('d-m-Y') }}</div>
        </div>
        <div class="p-5 border border-green-100 rounded-xl bg-gray-50 shadow-sm">
            <div class="text-xs text-gray-500 font-semibold">Status Pembayaran</div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold
                {{ $transaksi->status === 'selesai' ? 'bg-green-200 text-green-900' : 'bg-yellow-200 text-yellow-900' }}">
                @if($transaksi->status === 'selesai')
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                @else
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4"/></svg>
                @endif
                {{ ucfirst($transaksi->status) }}
            </span>
        </div>
        <div class="p-5 border border-green-100 rounded-xl bg-gray-50 shadow-sm">
            <div class="text-xs text-gray-500 font-semibold">Total Transaksi</div>
            <div class="font-bold text-2xl text-green-700">Rp{{ number_format($transaksi->total, 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="mt-10">
        <h2 class="text-lg font-bold text-green-700 mb-3 flex items-center gap-2">
            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18"></path></svg>
            Detail Produk dalam Transaksi
        </h2>
        <div class="overflow-x-auto bg-white rounded-lg shadow">
            <table class="min-w-full text-sm border-separate border-spacing-y-2">
                <thead class="bg-green-100 text-green-800">
                    <tr>
                        <th class="px-4 py-2 text-left">No.</th>
                        <th class="px-4 py-2 text-left">Nama Produk</th>
                        <th class="px-4 py-2 text-left">Jumlah</th>
                        <th class="px-4 py-2 text-left">Harga</th>
                        <th class="px-4 py-2 text-left">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transaksi->items as $index => $item)
                    <tr class="bg-gray-50 even:bg-white rounded-lg hover:bg-green-50 transition">
                        <td class="px-4 py-2 rounded-l-lg">{{ $index + 1 }}</td>
                        <td class="px-4 py-2">{{ $item->produk->nama ?? 'Produk tidak ditemukan' }}</td>
                        <td class="px-4 py-2">{{ $item->qty }}</td>
                        <td class="px-4 py-2">Rp{{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 rounded-r-lg">Rp{{ number_format($item->qty * $item->harga, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection