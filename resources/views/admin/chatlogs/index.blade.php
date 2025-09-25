@extends('layouts.admin')

@section('content')
<div class="max-w-5xl mx-auto">
    <h2 class="text-2xl font-bold mb-6 text-green-700">📜 Riwayat Chat Chatbase</h2>

    @if(session('success'))
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.chatlogs.import') }}" method="POST" enctype="multipart/form-data" class="mb-6 flex items-center gap-4">
        @csrf
        <input type="file" name="csv" class="border px-3 py-2 rounded shadow-sm bg-white" required>
        <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
            Upload CSV
        </button>
    </form>

    <div class="overflow-auto bg-white rounded shadow">
        <table class="min-w-full text-sm text-left border">
            <thead class="bg-gray-100 text-gray-600 font-semibold">
                <tr>
                    <th class="px-4 py-2 border">Waktu</th>
                    <th class="px-4 py-2 border">Pesan Pelanggan</th>
                    <th class="px-4 py-2 border">Balasan Bot</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $log)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2 border">
                            {{ optional($log->created_at)->format('d M Y H:i') ?? '-' }}
                        </td>
                        <td class="px-4 py-2 border">{{ $log->user_message }}</td>
                        <td class="px-4 py-2 border text-green-700">{{ $log->bot_reply }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-gray-500 py-4">Belum ada data chat</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $logs->links() }}
    </div>
</div>
@endsection
