@extends('layouts.admin')

@section('content')
<h1 class="text-2xl font-bold mb-6 text-red-800">Log Transaksi Gagal</h1>

{{-- Tabel Log Transaksi Gagal --}}
<div class="overflow-x-auto bg-white shadow-md rounded-lg">
    <table class="w-full text-sm border">
        <thead class="bg-red-100 text-red-800">
            <tr>
                <th class="px-4 py-3 border">No</th>
                <th class="px-4 py-3 border">Order ID</th>
                <th class="px-4 py-3 border">Status</th>
                <th class="px-4 py-3 border">Alasan</th>
                <th class="px-4 py-3 border">Waktu</th>
            </tr>
        </thead>
        <tbody class="text-gray-700">
            @forelse($logs as $index => $log)
            <tr class="hover:bg-red-50 transition">
                <td class="px-4 py-3 border">{{ $logs->firstItem() + $index }}</td>
                <td class="px-4 py-3 border font-mono">{{ $log->order_id }}</td>
                <td class="px-4 py-3 border text-center capitalize text-red-700 font-semibold">{{ $log->status }}</td>
                <td class="px-4 py-3 border">{{ $log->reason }}</td>
                <td class="px-4 py-3 border">{{ $log->created_at->format('d-m-Y H:i') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center py-4 text-gray-500">Belum ada log transaksi gagal.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

{{-- Pagination --}}
@if($logs->lastPage() > 1)
    <div class="mt-6 flex justify-start gap-2 flex-wrap">
        @php
            $current = $logs->currentPage();
            $totalPages = $logs->lastPage();
            $group = ceil($current / 10);
            $start = ($group - 1) * 10 + 1;
            $end = min($group * 10, $totalPages);
        @endphp

        @if($start > 1)
            <a href="{{ $logs->url($start - 10) }}" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded">← Prev</a>
        @endif

        @for($page = $start; $page <= $end; $page++)
            <a href="{{ $logs->url($page) }}"
                class="px-3 py-1 rounded {{ $page == $current ? 'bg-red-600 text-white' : 'bg-gray-100 hover:bg-gray-200' }}">
                {{ $page }}
            </a>
        @endfor

        @if($end < $totalPages)
            <a href="{{ $logs->url($start + 10) }}" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded">Next →</a>
        @endif
    </div>
@endif
@endsection
