@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">
    <h1 class="text-2xl font-bold mb-8 text-green-800 flex items-center gap-2">
        <svg class="w-7 h-7 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M9 16h6"></path></svg>
        Data Transaksi
    </h1>

    {{-- Form Pencarian --}}
    <form method="GET" action="{{ route('admin.transaksi.index') }}" class="mb-8 flex flex-col md:flex-row gap-3 md:gap-2 items-center">
        <input type="text" name="search" placeholder="Cari nama customer..."
            value="{{ request('search') }}"
            class="border border-green-300 rounded-lg p-2 w-full md:w-72 shadow-sm focus:ring-2 focus:ring-green-200 transition" />
        <button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 shadow font-semibold transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/></svg>
            Cari
        </button>
        <button type="button"
            onclick="window.location='{{ route('admin.transaksi.index') }}'"
            class="bg-gray-200 text-green-700 px-4 py-2 rounded-lg hover:bg-gray-300 shadow font-semibold transition flex items-center gap-2"
            title="Refresh / Reset">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582M20 20v-5h-.581M5.42 9A7.975 7.975 0 0 1 12 4c2.21 0 4.21.891 5.66 2.34m.92 4.66A7.975 7.975 0 0 1 12 20c-2.21 0-4.21-.891-5.66-2.34"></path></svg>
            Refresh
        </button>
    </form>

    {{-- Total --}}
    <div class="mb-5 text-right text-green-900 font-semibold text-lg">
        Total Semua Transaksi: <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded shadow ml-2">Rp{{ number_format($totalTransaksi, 0, ',', '.') }}</span>
    </div>
    <div class="overflow-x-auto bg-white shadow-lg rounded-2xl border border-green-100">
        <table class="min-w-full text-sm border-separate border-spacing-y-2">
            <thead class="bg-green-100 text-green-800 text-xs uppercase tracking-wider">
                <tr>
                    <th class="px-4 py-3 text-left">No</th>
                    <th class="px-4 py-3 text-left">Nama Customer</th>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">Total Transaksi</th>
                    <th class="px-4 py-3 text-left">Status</th>
                    <th class="px-4 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="text-gray-800">
            @forelse($transaksis as $i => $transaksi)
            <tr class="bg-white even:bg-green-50 rounded-lg hover:shadow hover:bg-green-50 transition">
                <td class="px-4 py-3 rounded-l-lg">{{ $transaksis->firstItem() + $i }}</td>
                <td class="px-4 py-3">{{ optional($transaksi->user)->name ?? 'User  tidak ditemukan' }}</td>
                <td class="px-4 py-3">{{ \Carbon\Carbon::parse($transaksi->created_at)->format('d-m-Y') }}</td>
                <td class="px-4 py-3 font-bold text-green-700">
                    Rp{{ number_format($transaksi->total ?? 0, 0, ',', '.') }}
                </td>
                <td class="px-4 py-3 text-center">
                    @if($transaksi->status === 'selesai')
                        <span class="inline-block px-3 py-1 text-xs font-bold rounded-full bg-green-200 text-green-900 shadow-sm">
                            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                            Selesai
                        </span>
                    @else
                        <form action="{{ route('admin.transaksi.selesaikan', $transaksi->id) }}" method="POST" onsubmit="return confirm('Yakin ubah jadi selesai?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="inline-flex items-center gap-1 px-3 py-1 text-xs rounded-full bg-yellow-200 text-yellow-800 hover:bg-yellow-300 font-semibold shadow-sm transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="4"/></svg>
                                {{ ucfirst($transaksi->status) }} → <span class="underline">Selesai</span>
                            </button>
                        </form>
                    @endif
                </td>
                <td class="px-4 py-3 rounded-r-lg text-center">
                    <a href="{{ route('admin.transaksi.show', $transaksi->id) }}" class="text-green-700 hover:underline font-semibold inline-flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"></circle><path d="M2.05 13a9 9 0 0117.9 0"></path></svg>
                        Lihat
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center py-4 text-gray-500">Belum ada transaksi ditemukan.</td>
            </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination Custom --}}
    @if($transaksis->lastPage() > 1)
        <div class="mt-8 flex justify-center gap-2 flex-wrap">
            @php
                $current = $transaksis->currentPage();
                $totalPages = $transaksis->lastPage();
                $groupSize = 10;
                $group = ceil($current / $groupSize);
                $start = ($group - 1) * $groupSize + 1;
                $end = min($group * $groupSize, $totalPages);

                $queryArr = request()->except('page');
            @endphp

            {{-- Prev Group --}}
            @if($start > 1)
                <a href="{{ $transaksis->appends($queryArr)->url($start - 1) }}" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded transition">← Prev</a>
            @endif

            {{-- Page Numbers --}}
            @for($page = $start; $page <= $end; $page++)
                <a href="{{ $transaksis->appends($queryArr)->url($page) }}"
                    class="px-3 py-1 rounded transition {{ $page == $current ? 'bg-green-600 text-white font-bold shadow' : 'bg-gray-100 hover:bg-gray-200' }}">
                    {{ $page }}
                </a>
            @endfor

            {{-- Next Group --}}
            @if($end < $totalPages)
                <a href="{{ $transaksis->appends($queryArr)->url($end + 1) }}" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded transition">Next →</a>
            @endif
        </div>
    @endif
</div>
@endsection