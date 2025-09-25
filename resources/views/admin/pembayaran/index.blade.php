@extends('layouts.admin')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white p-8 rounded-2xl shadow-lg mb-8 border border-pink-100">
        <h1 class="text-2xl font-bold text-pink-700 mb-6 flex items-center gap-2">
            <svg class="w-7 h-7 text-pink-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-3.9 0-7 1.79-7 4v2c0 2.21 3.1 4 7 4s7-1.79 7-4v-2c0-2.21-3.1-4-7-4zm0 6c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
            Data Pembayaran
        </h1>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm border-separate border-spacing-y-2">
                <thead class="bg-pink-100 text-pink-900 text-xs uppercase tracking-wider">
                    <tr>
                        <th class="p-3 text-left">No</th>
                        <th class="p-3 text-left">Nama Penyetor</th>
                        <th class="p-3 text-left">Tanggal</th>
                        <th class="p-3 text-left">Bukti</th>
                        <th class="p-3 text-left">No Resi</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="p-3 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pembayarans as $key => $pembayaran)
                    <tr class="bg-white even:bg-pink-50 border-b border-pink-100 rounded-lg hover:shadow hover:bg-pink-50 transition">
                        <td class="p-3 rounded-l-lg">{{ $pembayarans->firstItem() + $key }}</td>
                        <td class="p-3">{{ $pembayaran->nama_penyetor }}</td>
                        <td class="p-3">{{ \Carbon\Carbon::parse($pembayaran->created_at)->format('d M Y H:i') }}</td>
                        <td class="p-3">
                            <a href="{{ asset('storage/' . $pembayaran->bukti) }}" target="_blank">
                                <img src="{{ asset('storage/' . $pembayaran->bukti) }}" class="w-20 h-16 object-contain border rounded shadow-sm hover:scale-110 transition" loading="lazy">
                            </a>
                        </td>
                        <td class="p-3">
                            <input type="text" name="no_resi" id="noResi-{{ $pembayaran->id }}" 
                                value="{{ $pembayaran->no_resi }}" 
                                class="w-full border px-2 py-1 text-xs mb-1 rounded focus:ring-2 focus:ring-pink-200 transition" 
                                placeholder="No Resi">
                            <a href="#" onclick="generateResi({{ $pembayaran->id }}); return false;" 
                                class="text-blue-600 text-xs underline block mb-2 hover:text-blue-800">Generate Resi</a>
                        </td>
                        <td class="p-3">
                            @php
                                $color = 'bg-gray-200 text-gray-700';
                                if ($pembayaran->status == 'menunggu') $color = 'bg-gray-200 text-gray-700';
                                elseif ($pembayaran->status == 'diproses') $color = 'bg-yellow-200 text-yellow-900';
                                elseif ($pembayaran->status == 'dikirim') $color = 'bg-blue-200 text-blue-900';
                                elseif ($pembayaran->status == 'selesai') $color = 'bg-green-200 text-green-900';
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-bold {{ $color }} capitalize shadow-sm">
                                {{ $pembayaran->status }}
                            </span>
                        </td>
                        <td class="p-3 rounded-r-lg space-y-2">
                            <form action="{{ route('admin.pembayaran.update', $pembayaran->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="no_resi" id="hiddenNoResi-{{ $pembayaran->id }}">
                                <select name="status" class="w-full border px-2 py-1 text-xs mb-2 rounded focus:ring-2 focus:ring-pink-200 transition">
                                    <option value="menunggu" {{ $pembayaran->status == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                                    <option value="diproses" {{ $pembayaran->status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                                    <option value="dikirim" {{ $pembayaran->status == 'dikirim' ? 'selected' : '' }}>Dikirim</option>
                                    <option value="selesai" {{ $pembayaran->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                </select>
                                <button type="submit" onclick="syncResiBeforeSubmit('{{ $pembayaran->id }}')" 
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs w-full font-semibold transition">Proses</button>
                            </form>
                            <form action="{{ route('admin.pembayaran.destroy', $pembayaran->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-xs w-full font-semibold transition">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- Custom Pagination 10 --}}
        @if($pembayarans->lastPage() > 1)
            <div class="mt-8 flex justify-center gap-2 flex-wrap">
                @php
                    $current = $pembayarans->currentPage();
                    $totalPages = $pembayarans->lastPage();
                    $groupSize = 10;
                    $group = ceil($current / $groupSize);
                    $start = ($group - 1) * $groupSize + 1;
                    $end = min($group * $groupSize, $totalPages);

                    $queryArr = request()->except('page');
                @endphp

                {{-- Prev Group --}}
                @if($start > 1)
                    <a href="{{ $pembayarans->appends($queryArr)->url($start - 1) }}" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded transition">← Prev</a>
                @endif

                {{-- Page Numbers --}}
                @for($page = $start; $page <= $end; $page++)
                    <a href="{{ $pembayarans->appends($queryArr)->url($page) }}"
                        class="px-3 py-1 rounded transition {{ $page == $current ? 'bg-pink-600 text-white font-bold shadow' : 'bg-gray-100 hover:bg-gray-200' }}">
                        {{ $page }}
                    </a>
                @endfor

                {{-- Next Group --}}
                @if($end < $totalPages)
                    <a href="{{ $pembayarans->appends($queryArr)->url($end + 1) }}" class="px-3 py-1 bg-gray-100 hover:bg-gray-200 rounded transition">Next →</a>
                @endif
            </div>
        @endif
    </div>
</div>

<script>
    function generateResi(id) {
        const today = new Date();
        const dd = String(today.getDate()).padStart(2, '0');
        const mm = String(today.getMonth() + 1).padStart(2, '0');
        const yy = String(today.getFullYear()).slice(2, 4);
        const datePart = dd + mm + yy;
        const randomPart = Math.random().toString(36).substr(2, 5).toUpperCase();
        const resi = 'LRZ-' + datePart + '-' + randomPart;
        document.getElementById('noResi-' + id).value = resi;
    }

    function syncResiBeforeSubmit(id) {
        const inputVal = document.getElementById('noResi-' + id).value;
        document.getElementById('hiddenNoResi-' + id).value = inputVal;
    }
</script>
@endsection
