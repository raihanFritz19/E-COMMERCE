@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">
    <h1 class="text-2xl font-bold mb-6 text-green-800 flex items-center gap-2">
        <svg class="w-7 h-7 text-green-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-2a2 2 0 012-2h2a2 2 0 012 2v2M15 17h2a2 2 0 002-2v-4.5a2 2 0 00-.59-1.41l-6-6a2 2 0 00-2.82 0l-6 6A2 2 0 003 10.5V16a2 2 0 002 2h2"></path></svg>
        Daftar Pengiriman
    </h1>

    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 p-3 bg-red-100 text-red-800 rounded shadow">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white shadow p-6 rounded-lg mb-8">
        <form action="{{ route('admin.pengiriman.store') }}" method="POST" class="flex flex-col md:flex-row gap-4 md:gap-8 items-center">
            @csrf
            <div class="flex flex-col gap-2 w-full md:w-auto">
                <label for="select-customer" class="text-sm font-semibold text-green-800">Customer</label>
                <select name="user_id" id="select-customer" class="border p-2 rounded w-64 focus:ring-2 focus:ring-green-200" required>
                    <option value="">Pilih Customer</option>
                    @foreach($usersSiapKirim->unique('user_id') as $data)
                        <option value="{{ $data->user_id }}">{{ $data->user->name ?? 'Tanpa Nama' }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex flex-col gap-2 w-full md:w-auto">
                <label for="select-order" class="text-sm font-semibold text-green-800">Order</label>
                <select name="order_id" id="select-order" required class="border p-2 rounded w-64 focus:ring-2 focus:ring-green-200">
                    <option value="">-- Pilih Order --</option>
                </select>
            </div>
            <div class="flex flex-col gap-2 w-full md:w-auto">
                <label for="kurir" class="text-sm font-semibold text-green-800">Kurir</label>
                <input type="text" name="kurir" id="kurir" placeholder="Kurir (contoh: JNE, J&T)" required class="border p-2 rounded w-48 focus:ring-2 focus:ring-green-200">
            </div>
            <button type="submit" class="bg-green-600 flex items-center gap-1 text-white px-5 py-2 rounded shadow hover:bg-green-700 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2l4-4"></path></svg>
                Buat Pengiriman
            </button>
        </form>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="min-w-full text-sm border-separate border-spacing-y-2">
            <thead class="bg-green-100 text-green-800">
                <tr>
                    <th class="px-4 py-3 text-left">No</th>
                    <th class="px-4 py-3 text-left">Nama Customer</th>
                    <th class="px-4 py-3 text-left">Alamat</th>
                    <th class="px-4 py-3 text-left">Kurir</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-800">
                @forelse($pengirimen as $i => $pengiriman)
                    <tr class="hover:bg-green-50 transition border-b border-gray-100 bg-gray-50 even:bg-white rounded-lg">
                        <td class="px-4 py-3 rounded-l-lg">{{ $pengirimen->firstItem() + $i }}</td>
                        <td class="px-4 py-3">{{ $pengiriman->user->name ?? 'User tidak ditemukan' }}</td>
                        <td class="px-4 py-3">{{ $pengiriman->alamat }}</td>
                        <td class="px-4 py-3">{{ $pengiriman->kurir }}</td>
                        <td class="px-4 py-3">
                            @if($pengiriman->status == 'dikirim')
                                <span class="inline-flex items-center px-3 py-1 bg-yellow-200 text-yellow-900 rounded-full text-xs font-bold shadow-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3"></path></svg>
                                    Dalam Pengiriman
                                </span>
                            @elseif($pengiriman->status == 'diterima')
                                <span class="inline-flex items-center px-3 py-1 bg-green-200 text-green-900 rounded-full text-xs font-bold shadow-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                                    Diterima
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 bg-gray-200 text-gray-900 rounded-full text-xs font-bold shadow-sm">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4"></circle></svg>
                                    Belum Dikirim
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($pengiriman->created_at)->format('d-m-Y') }}</td>
                        <td class="px-4 py-3 rounded-r-lg text-center">
                            <a href="{{ route('admin.pengiriman.show', $pengiriman->id) }}" class="text-green-700 hover:underline font-semibold inline-flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"></circle><path d="M2.05 13a9 9 0 0117.9 0"></path></svg>
                                Detail
                            </a>
                            @if(in_array($pengiriman->status, ['dikirim', 'diterima']) && $pengiriman->resi)
                                <a href="https://track.biteship.com/{{ $pengiriman->resi }}" target="_blank" class="text-indigo-600 hover:underline text-sm inline-flex items-center gap-1 ml-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                    Lacak
                                </a>
                            @endif
                            @if($pengiriman->status !== 'diterima')
                                <form action="{{ route('admin.pengiriman.updateStatus', $pengiriman->id) }}" method="POST" class="inline-block ml-2" onsubmit="return confirm('Ubah status pengiriman?')">
                                    @csrf
                                    <button class="text-blue-600 hover:underline text-sm inline-flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"></path></svg>
                                        Ubah Status
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-gray-500">Tidak ada data pengiriman.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $pengirimen->links() }}
    </div>
</div>

<script>
    const selectCustomer = document.getElementById('select-customer');
    const orderSelect = document.getElementById('select-order');

    selectCustomer.addEventListener('change', function () {
        const userId = this.value;

        fetch(`/admin/get-orders/${userId}`)
            .then(res => res.json())
            .then(data => {
                orderSelect.innerHTML = '';
                const defaultOption = document.createElement('option');
                defaultOption.text = '-- Pilih Order --';
                defaultOption.value = '';
                orderSelect.appendChild(defaultOption);

                data.forEach(order => {
                    const tanda = order.is_terkirim ? '✓' : '';
                    const option = document.createElement('option');
                    option.value = order.id;
                    option.text = `${order.nama} - ${order.alamat} ${tanda}`;
                    orderSelect.appendChild(option);
                });
            });
    });
</script>
@endsection
