<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kalender Penjualan - {{ $now->translatedFormat('F Y') }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        h2 {
            text-align: center;
            color: #2e7d32;
            margin-bottom: 20px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(7, 1fr);
            gap: 8px;
        }

        .day-header {
            background-color: #f0f0f0;
            text-align: center;
            font-weight: bold;
            padding: 6px 0;
            border: 1px solid #ccc;
        }

        .day {
            border: 1px solid #ccc;
            padding: 6px;
            height: 90px;
            vertical-align: top;
            position: relative;
        }

        .today {
            background-color: #e8f5e9;
            border: 2px solid #66bb6a;
        }

        .day-number {
            font-weight: bold;
            color: #2e7d32;
            margin-bottom: 4px;
        }

        .sales-info {
            font-size: 11px;
            margin-top: 4px;
            color: #444;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 11px;
            color: #888;
        }
    </style>
</head>
<body>

    <h2>Kalender Penjualan - {{ $now->translatedFormat('F Y') }}</h2>

    @php
        use Carbon\Carbon;

        $startOfMonth = $now->copy()->startOfMonth();
        $startDay = $startOfMonth->dayOfWeekIso;
        $daysInMonth = $now->daysInMonth;
        $today = $now->format('Y-m-d');

        $penjualanByDate = [];
        foreach ($penjualans as $item) {
            $dateKey = Carbon::parse($item->tanggal)->format('Y-m-d');
            $penjualanByDate[$dateKey] = $item;
        }

        $dayNames = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
    @endphp

    {{-- Header hari --}}
    <div class="grid">
        @foreach ($dayNames as $dayName)
            <div class="day-header">{{ $dayName }}</div>
        @endforeach

        {{-- Kosong di awal bulan --}}
        @for ($i = 1; $i < $startDay; $i++)
            <div class="day"></div>
        @endfor

        {{-- Tanggal-tanggal --}}
        @for ($day = 1; $day <= $daysInMonth; $day++)
            @php
                $tanggal = $now->copy()->day($day)->format('Y-m-d');
                $penjualan = $penjualanByDate[$tanggal] ?? null;
                $isToday = $tanggal === $today;
            @endphp

            <div class="day {{ $isToday ? 'today' : '' }}">
                <div class="day-number">{{ $day }}</div>
                @if ($penjualan)
                    <div class="sales-info">
                        <strong>Rp</strong> {{ number_format($penjualan->total, 0, ',', '.') }}<br>
                        {{ $penjualan->jumlah }} order
                    </div>
                @endif
            </div>
        @endfor
    </div>

    <div class="footer">
        Dicetak pada: {{ now()->translatedFormat('d F Y H:i') }}
    </div>
</body>
</html>
