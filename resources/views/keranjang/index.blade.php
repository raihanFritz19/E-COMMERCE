@extends('layouts.app')

@section('content')

@php
    use Illuminate\Support\Str;
    $tanggal = now()->format('Ymd');
    $trx_id = 'TRX-' . $tanggal . '-' . strtoupper(Str::random(5));
    $total = 0;
@endphp

<div class="max-w-7xl mx-auto mt-10 px-4">
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-6">Keranjang Belanja</h1>

    @if (session('success'))
        <div class="mb-6 p-4 bg-green-100 text-green-800 rounded shadow">{{ session('success') }}</div>
    @endif

    @if (count($items))
        <div class="flex flex-col md:flex-row gap-6">
            {{-- Keranjang --}}
            <div class="md:w-2/3 w-full">
                {{-- Tabel Desktop --}}
                <div class="hidden md:block">
                    <table class="w-full text-sm text-gray-700 bg-white shadow rounded overflow-hidden">
                        <thead class="bg-pink-100 text-left text-sm font-semibold uppercase">
                            <tr>
                                <th class="p-4">Produk</th>
                                <th class="p-4 text-center">Harga</th>
                                <th class="p-4 text-center">Jumlah</th>
                                <th class="p-4 text-center">Subtotal</th>
                                <th class="p-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($items as $id => $item)
                                @php
                                    $subtotal = $item['qty'] * $item['harga'];
                                    $total += $subtotal;
                                @endphp
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-4 flex items-center gap-3">
                                        <img src="{{ asset('storage/' . $item['foto']) }}" alt="{{ $item['nama'] }}" class="w-14 h-14 object-cover rounded-lg border">
                                        <span class="font-medium">{{ $item['nama'] }}</span>
                                    </td>
                                    <td class="p-4 text-center">Rp {{ number_format($item['harga'], 0, ',', '.') }}</td>
                                    <td class="p-4 text-center">
                                        <form action="{{ route('keranjang.update', $id) }}" method="POST" class="flex items-center justify-center gap-2">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" name="aksi" value="kurang" class="w-8 h-8 bg-gray-200 rounded hover:bg-gray-300 text-lg">−</button>
                                            <span class="min-w-[24px] text-center">{{ $item['qty'] }}</span>
                                            <button type="submit" name="aksi" value="tambah" class="w-8 h-8 bg-gray-200 rounded hover:bg-gray-300 text-lg">+</button>
                                        </form>
                                    </td>
                                    <td class="p-4 text-center">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                    <td class="p-4 text-center">
                                        <form action="{{ route('keranjang.hapus', $id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button onclick="return confirm('Hapus produk ini dari keranjang?')" class="text-red-600 text-xl hover:text-red-800">
                                                <i class="bi bi-trash3-fill"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                            <tr class="bg-gray-100 font-semibold text-gray-800">
                                <td colspan="3" class="p-4 text-right">Total Belanja:</td>
                                <td class="p-2 text-center text-pink-600 text-lg">Rp {{ number_format($total, 0, ',', '.') }}</td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="mt-6">
                        <a href="{{ route('produk.index') }}" class="inline-block bg-pink-600 text-white px-6 py-2 rounded-lg hover:bg-pink-700 transition">
                            ← Kembali ke Produk
                        </a>
                    </div>
                </div>

                {{-- Mobile Card --}}
                <div class="md:hidden space-y-4 mt-4">
                    @foreach ($items as $id => $item)
                        <div class="bg-white shadow rounded p-4">
                            <div class="flex gap-4">
                                <img src="{{ asset('storage/' . $item['foto']) }}" alt="{{ $item['nama'] }}" class="w-20 h-20 object-cover rounded border">
                                <div class="flex-1">
                                    <h3 class="font-semibold text-lg">{{ $item['nama'] }}</h3>
                                    <p class="text-sm text-gray-600">Rp {{ number_format($item['harga'], 0, ',', '.') }}</p>
                                    <p class="text-sm text-gray-600">Subtotal: <span class="font-medium">Rp {{ number_format($item['qty'] * $item['harga'], 0, ',', '.') }}</span></p>
                                </div>
                            </div>
                            <div class="mt-3 flex justify-between items-center">
                                <form action="{{ route('keranjang.update', $id) }}" method="POST" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" name="aksi" value="kurang" class="w-8 h-8 bg-gray-200 rounded hover:bg-gray-300 text-lg">−</button>
                                    <span class="min-w-[24px] text-center">{{ $item['qty'] }}</span>
                                    <button type="submit" name="aksi" value="tambah" class="w-8 h-8 bg-gray-200 rounded hover:bg-gray-300 text-lg">+</button>
                                </form>
                                <form action="{{ route('keranjang.hapus', $id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button onclick="return confirm('Hapus produk ini dari keranjang?')" class="text-sm text-red-500 hover:underline">Hapus</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Form Checkout --}}
            <div class="md:w-1/3 w-full bg-white shadow rounded-lg p-6 space-y-6">
                <h2 class="text-xl font-bold text-gray-800 border-b pb-2">Informasi Pemesan</h2>

                @auth
                    <form id="checkout-form" class="space-y-5 text-sm text-gray-700">
                        @csrf

                        <div>
                            <label class="block font-medium mb-1">ID Transaksi</label>
                            <input type="text" name="trx_id_display" value="{{ $trx_id }}" readonly
                                class="w-full p-2.5 bg-gray-100 border border-gray-300 rounded-md text-sm font-mono text-gray-600">
                            <input type="hidden" name="trx_id" value="{{ $trx_id }}">
                        </div>

                        <div>
                            <label class="block font-medium mb-1">Nama</label>
                            <input type="text" readonly value="{{ auth()->user()->name }}"
                                class="w-full p-2.5 bg-gray-100 border border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label class="block font-medium mb-1">Email</label>
                            <input type="email" readonly value="{{ auth()->user()->email }}"
                                class="w-full p-2.5 bg-gray-100 border border-gray-300 rounded-md">
                        </div>

                        <div>
                            <label for="alamat" class="block font-medium mb-1">Alamat Lengkap</label>
                            <textarea name="alamat" id="alamat" rows="3" required
                                class="w-full p-2.5 border border-gray-300 rounded-md focus:outline-pink-500"></textarea>
                        </div>

                        <div>
                            <label for="telepon" class="block font-medium mb-1">No. Telepon</label>
                            <input type="text" name="telepon" id="telepon" required maxlength="12"
                                class="w-full p-2.5 border border-gray-300 rounded-md focus:outline-pink-500">
                        </div>

                        <div>
                            <label for="kota" class="block font-medium mb-1">Kota Tujuan</label>
                            <select id="kota" name="kota" required class="w-full p-2.5 border border-gray-300 rounded-md focus:outline-pink-500">
                                <option value="">-- Pilih Kota --</option>
                                <option value="Jakarta">Jakarta</option>
                                <option value="Bogor">Bogor</option>
                                <option value="Bandung">Bandung</option>
                                <option value="Bekasi">Bekasi</option>
                                {{-- Tambah sesuai data di tabel ongkir --}}
                            </select>
                        </div>

                        <div>
                            <label for="ongkir" class="block font-medium mb-1">Ongkos Kirim</label>
                            <input type="text" id="ongkir" name="ongkir" readonly class="w-full p-2.5 bg-gray-100 border border-gray-300 rounded-md text-pink-600 font-bold" value="Rp 0">
                        </div>

                        <div>
                            <label for="total_akhir" class="block font-medium mb-1">Total Bayar</label>
                            <input type="text" id="total_akhir" name="total_akhir" readonly class="w-full p-2.5 bg-gray-100 border border-gray-300 rounded-md text-pink-600 font-bold" value="Rp {{ number_format($total, 0, ',', '.') }}">
                        </div>

                        <div class="pt-2">
                            <button type="button" id="pay-button"
                                class="w-full bg-pink-600 hover:bg-pink-700 text-white py-2 rounded-md transition">Lanjutkan ke Checkout</button>
                        </div>
                    </form>
                @else
                    <p class="text-sm text-gray-600 mb-4">Silakan login untuk melanjutkan proses checkout.</p>
                    <a href="{{ route('customer.login') }}"
                        class="block text-center bg-blue-600 text-white py-2 rounded hover:bg-blue-700">Login</a>
                @endauth
            </div>
        </div>
    @else
        <div class="text-center">
            <p class="text-gray-600 mb-4">Keranjang kamu masih kosong.</p>
            <a href="{{ route('produk.index') }}"
                class="bg-pink-600 text-white px-6 py-2 rounded hover:bg-pink-700 transition">Lihat Produk</a>
        </div>
    @endif
</div>

{{-- Midtrans --}}
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script>
    const totalBelanja = {{ $total }};

    // Saat memilih kota, ambil ongkir dan hitung total bayar
    document.getElementById('kota').addEventListener('change', function () {
        fetch('/checkout/ongkir', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ kota: this.value })
        })
        .then(response => response.json())
        .then(data => {
            const ongkir = parseInt(data.ongkir); // konversi string ke angka
            document.getElementById('ongkir').value = 'Rp ' + ongkir.toLocaleString();
            document.getElementById('total_akhir').value = 'Rp ' + (totalBelanja + ongkir).toLocaleString();
        });
    });

    // Tombol Checkout Midtrans
    document.getElementById('pay-button').addEventListener('click', function () {
        fetch('/checkout/token', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                alamat: document.getElementById('alamat').value,
                telepon: document.getElementById('telepon').value,
                trx_id: '{{ $trx_id }}',
                kota: document.getElementById('kota').value,
                ongkir: document.getElementById('ongkir').value.replace(/[^\d]/g, '') // hilangkan format Rp dan titik
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.token) {
                snap.pay(data.token, {
                    onSuccess: function(result) {
                        // Bersihkan keranjang jika pembayaran berhasil
                        fetch("{{ route('keranjang.clear') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            }
                        }).then(() => {
                            window.location.href = "/order/konfirmasi/{{ $trx_id }}";
                        });
                    },
                    onPending: function(result) {
                        alert("Transaksi sedang diproses. Cek kembali nanti.");
                    },
                    onError: function(result) {
                        alert("Terjadi kesalahan saat pembayaran.");
                        console.error(result);
                    },
                    onClose: function() {
                        alert("Anda menutup popup sebelum menyelesaikan pembayaran.");
                    }
                });
            } else {
                alert('Gagal mendapatkan token pembayaran.');
                console.error(data);
            }
        })
        .catch(error => {
            alert('Gagal menghubungi server.');
            console.error(error);
        });
    });
</script>

@endsection