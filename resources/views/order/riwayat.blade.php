@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-10 px-4">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Riwayat Pesanan</h1>

    @if ($orders->count())
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="w-full text-sm text-gray-700">
                <thead class="bg-pink-100 text-left font-semibold">
                    <tr>
                        <th class="p-4">ID Transaksi</th>
                        <th class="p-4">Tanggal</th>
                        <th class="p-4">Total</th>
                        <th class="p-4">Status</th>
                        <th class="p-4">Status Pembayaran</th>
                        <th class="p-4">No. Resi</th>
                        <th class="p-4">Konfirmasi Pengiriman</th>
                        <th class="p-4">Testimoni</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        @php
                            $pengiriman = $order->pengiriman;
                            $konfirmasi = $order->konfirmasiPembayaran;
                        @endphp
                        <tr class="border-b hover:bg-gray-50">
                            <td class="p-4 font-mono">{{ $order->id }}</td>
                            <td class="p-4">{{ $order->created_at->format('d M Y H:i') }}</td>
                            <td class="p-4">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                            <td class="p-4 capitalize {{ $order->status == 'pending' ? 'text-yellow-600' : 'text-green-600' }}">
                                {{ $order->status }}
                            </td>
                            <td class="p-4">
                                @if ($order->status === 'settlement')
                                    <span class="text-green-600 font-semibold">Sudah Bayar</span>
                                @elseif ($order->status === 'pending')
                                    <span class="text-yellow-600 font-semibold">Belum Bayar</span>
                                @elseif (in_array($order->status, ['cancel', 'expire', 'deny']))
                                    <span class="text-red-600 font-semibold">Gagal</span>
                                @else
                                    <span class="text-gray-600">{{ ucfirst($order->status) }}</span>
                                @endif
                            </td>
                            <td class="p-4">
                                @if($konfirmasi && $konfirmasi->no_resi)
                                    <span class="text-sm text-gray-800">{{ $konfirmasi->no_resi }}</span>
                                @else
                                    <span class="text-sm italic text-gray-400">(belum diinput)</span>
                                @endif
                            </td>
                            <td class="p-4">
                                @if($pengiriman && $pengiriman->status == 'dikirim')
                                    <form action="{{ route('pengiriman.terima', $pengiriman->id) }}" method="POST" onsubmit="return confirm('Konfirmasi bahwa barang sudah diterima?')">
                                        @csrf
                                        <button type="submit" class="mt-2 bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-xs w-full">
                                            Barang Diterima
                                        </button>
                                    </form>
                                @else
                                    <span class="text-gray-500 text-xs italic">
                                        {{ $pengiriman ? ucfirst($pengiriman->status) : 'Belum dikirim' }}
                                    </span>
                                @endif
                            </td>
                            <td class="p-4">
                                @foreach ($order->items as $item)
                                    @php
                                        $testimoni = \App\Models\Testimoni::where('order_id', $order->id)
                                            ->where('produk_id', $item->produk_id)
                                            ->first();
                                    @endphp

                                    <div class="mb-4">
                                        <div class="text-sm text-gray-600 mb-1">
                                            Produk: {{ $item->produk->nama ?? 'Produk tidak ditemukan' }}
                                        </div>

                                        @if ($testimoni)
                                            <div class="border rounded bg-gray-100 p-2 mb-1 text-gray-700">
                                                {{ $testimoni->isi }}
                                            </div>
                                            <div class="flex items-center mb-2">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i <= $testimoni->rating)
                                                        <span class="text-yellow-400">&#9733;</span>
                                                    @else
                                                        <span class="text-gray-300">&#9733;</span>
                                                    @endif
                                                @endfor
                                            </div>
                                        @elseif ($pengiriman && $pengiriman->status == 'diterima')
                                            <form action="{{ route('testimoni.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="order_id" value="{{ $order->id }}">
                                                <input type="hidden" name="produk_id" value="{{ $item->produk_id }}">

                                                <textarea name="isi" class="border rounded w-full p-2 mb-2" rows="2" placeholder="Tulis testimoni..." required></textarea>

                                                <label class="block text-sm text-gray-600 mb-1">Rating:</label>
                                                <select name="rating" required class="border rounded w-full p-1 mb-2">
                                                    <option value="">Pilih rating</option>
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <option value="{{ $i }}">{{ $i }} Bintang</option>
                                                    @endfor
                                                </select>

                                                <button type="submit" class="bg-pink-500 hover:bg-pink-600 text-white px-3 py-1 rounded text-xs">
                                                    Kirim
                                                </button>
                                            </form>
                                        @else
                                            <div class="text-sm italic text-gray-400">Testimoni dapat diisi setelah barang diterima.</div>
                                        @endif
                                    </div>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-gray-600">Belum ada pesanan yang dilakukan.</p>
    @endif
</div>
@endsection
