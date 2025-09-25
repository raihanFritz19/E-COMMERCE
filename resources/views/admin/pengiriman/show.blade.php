@extends('layouts.admin')

@section('content')
<div class="max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold text-green-800 mb-8 flex items-center gap-2">
        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2M15 17h2a2 2 0 002-2v-4.5a2 2 0 00-.59-1.41l-6-6a2 2 0 00-2.82 0l-6 6A2 2 0 003 10.5V16a2 2 0 002 2h2"></path></svg>
        Detail Pengiriman
    </h1>

    <div class="bg-white p-7 rounded-xl shadow-md space-y-5 border border-green-100">
        <div class="flex flex-col md:flex-row gap-6">
            <div class="flex-1">
                <div class="mb-3">
                    <div class="text-xs text-gray-500 font-semibold">Nama Customer</div>
                    <div class="text-lg font-bold text-green-900">{{ $pengiriman->user->name ?? '-' }}</div>
                </div>
                <div class="mb-3">
                    <div class="text-xs text-gray-500 font-semibold">Alamat</div>
                    <div class="text-base text-gray-700">{{ $pengiriman->alamat }}</div>
                </div>
                <div class="mb-3">
                    <div class="text-xs text-gray-500 font-semibold">Kurir</div>
                    <div class="text-base text-gray-700">{{ $pengiriman->kurir }}</div>
                </div>
            </div>
            <div class="flex-1 flex flex-col gap-5">
                <div class="mb-3">
                    <div class="text-xs text-gray-500 font-semibold">Status</div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold
                        @if($pengiriman->status == 'dikirim') bg-yellow-200 text-yellow-900
                        @elseif($pengiriman->status == 'diterima') bg-green-200 text-green-900
                        @else bg-gray-200 text-gray-900 @endif">
                        @if($pengiriman->status == 'dikirim')
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3"></path></svg>
                        @elseif($pengiriman->status == 'diterima')
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                        @else
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4"></circle></svg>
                        @endif
                        {{ ucfirst($pengiriman->status) }}
                    </span>
                </div>
                <div class="mb-3">
                    <div class="text-xs text-gray-500 font-semibold">Tanggal Dibuat</div>
                    <div class="text-base">{{ \Carbon\Carbon::parse($pengiriman->created_at)->format('d M Y, H:i') }}</div>
                </div>
            </div>
        </div>
        <div class="pt-4">
            <a href="{{ route('admin.pengiriman.index') }}" 
                class="inline-flex items-center px-5 py-2 bg-green-600 text-white rounded shadow hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 transition duration-150 ease-in-out text-sm font-semibold">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"></path></svg>
                Kembali ke daftar pengiriman
            </a>
        </div>
    </div>

    @if($pengiriman->order && $pengiriman->order->items->count())
    <div class="mt-8">
        <h2 class="text-lg font-bold text-green-700 mb-3 flex items-center gap-2">
            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7h18M3 12h18M3 17h18"></path></svg>
            Produk yang Dikirim
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
                    @foreach($pengiriman->order->items as $index => $item)
                    <tr class="bg-gray-50 even:bg-white rounded-lg hover:bg-green-50 transition">
                        <td class="px-4 py-2 rounded-l-lg">{{ $index + 1 }}</td>
                        <td class="px-4 py-2">{{ $item->nama_produk ?? ($item->produk->nama ?? '-') }}</td>
                        <td class="px-4 py-2">{{ $item->jumlah ?? $item->qty }}</td>
                        <td class="px-4 py-2">Rp{{ number_format($item->harga, 0, ',', '.') }}</td>
                        <td class="px-4 py-2 rounded-r-lg">Rp{{ number_format(($item->jumlah ?? $item->qty) * $item->harga, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection