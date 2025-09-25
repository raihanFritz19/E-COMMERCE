@extends('layouts.admin')

@section('content')
@php
use Carbon\Carbon;

$now = Carbon::now();
$startOfMonth = $now->copy()->startOfMonth();
$endOfMonth = $now->copy()->endOfMonth();
$startDay = $startOfMonth->dayOfWeekIso;
$daysInMonth = $now->daysInMonth;
$today = $now->format('Y-m-d');

// Index penjualan berdasarkan tanggal
$penjualanByDate = [];
foreach ($penjualans as $item) {
    $dateKey = Carbon::parse($item->tanggal)->format('Y-m-d');
    $penjualanByDate[$dateKey] = $item;
}
@endphp

<style>
    .calendar-day {
        transition: box-shadow 0.2s, transform 0.2s;
    }
    .calendar-day:hover {
        box-shadow: 0 4px 16px #34d39933;
        transform: translateY(-2px) scale(1.03);
        z-index: 1;
    }
    .calendar-today {
        border: 2px solid #059669 !important;
        box-shadow: 0 0 0 2px #a7f3d0;
        background: linear-gradient(135deg, #d1fae5 80%, #fff 100%);
    }
    .calendar-badge {
        display: inline-block;
        padding: 2px 8px;
        background: #34d399;
        color: #fff;
        border-radius: 999px;
        font-size: 12px;
        font-weight: bold;
        margin-top: 5px;
        margin-bottom: 2px;
    }
    .calendar-order {
        color: #059669;
        font-size: 13px;
        font-weight: 600;
    }
    .calendar-empty {
        background: repeating-linear-gradient(
            135deg,
            #f3f4f6,
            #f3f4f6 8px,
            #f9fafb 8px,
            #f9fafb 16px
        );
    }
</style>

<div class="bg-white p-6 rounded-xl shadow-md">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-semibold text-green-700">Kalender Penjualan - {{ $now->translatedFormat('F Y') }}</h1>
        <a href="{{ route('penjualan.print') }}" target="_blank"
           class="inline-block px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
            Cetak PDF
        </a>
    </div>

    <div class="grid grid-cols-7 gap-2 text-center text-sm font-semibold text-green-800 mb-2">
        <div>Sen</div>
        <div>Sel</div>
        <div>Rab</div>
        <div>Kam</div>
        <div>Jum</div>
        <div>Sab</div>
        <div>Min</div>
    </div>

    <div class="grid grid-cols-7 gap-2 text-sm">
        @for ($i = 1; $i < $startDay; $i++)
            <div class="h-24 rounded calendar-empty"></div>
        @endfor

        @for ($day = 1; $day <= $daysInMonth; $day++)
            @php
                $tanggal = $now->copy()->day($day)->format('Y-m-d');
                $penjualan = $penjualanByDate[$tanggal] ?? null;
                $isToday = $tanggal === $today;
            @endphp
            <div class="border rounded-lg p-2 h-24 relative bg-white calendar-day {{ $isToday ? 'calendar-today' : '' }}">
                <div class="font-bold text-green-900 text-base">
                    {{ $day }}
                    @if($isToday)
                        <span class="absolute top-2 right-2 inline-block w-2 h-2 bg-green-500 rounded-full"></span>
                    @endif
                </div>
                @if($penjualan)
                    <div class="calendar-badge mt-1">Rp {{ number_format($penjualan->total, 0, ',', '.') }}</div>
                    <div class="calendar-order">{{ $penjualan->jumlah }} order</div>
                @else
                    <div class="text-gray-300 text-xs mt-4">-</div>
                @endif
            </div>
        @endfor
    </div>

    <div class="mt-6 text-right">
        <span class="inline-block bg-green-100 text-green-700 rounded px-3 py-1 text-xs font-semibold border border-green-300">
            <span class="inline-block w-2 h-2 bg-green-500 rounded-full mr-1"></span> Hari ini
        </span>
    </div>
</div>
@endsection