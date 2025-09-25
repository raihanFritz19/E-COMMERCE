@extends('layouts.admin')

@section('content')
<div class="bg-white p-6 rounded-xl shadow-md">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-semibold text-green-700">Daftar Pelanggan</h1>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full border text-sm text-left rounded-lg shadow-sm">
            <thead class="bg-green-100 text-green-800">
                <tr>
                    <th class="px-4 py-2 border">No</th>
                    <th class="px-4 py-2 border">Nama</th>
                    <th class="px-4 py-2 border">Email</th>
                    <th class="px-4 py-2 border">Tanggal Daftar</th>
                    <th class="px-4 py-2 border">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @forelse($pelanggans as $pelanggan)
                    <tr class="hover:bg-green-50 transition">
                        <td class="px-4 py-2 border">{{ $loop->iteration }}</td>
                        <td class="px-4 py-2 border">{{ $pelanggan->name }}</td>
                        <td class="px-4 py-2 border">{{ $pelanggan->email }}</td>
                        <td class="px-4 py-2 border">{{ $pelanggan->created_at->format('d M Y H:i') }}</td>
                        <td class="px-4 py-2 border flex gap-2">
                            <form action="{{ route('admin.pelanggan.destroy', $pelanggan->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus pelanggan ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-gray-500 py-4">Belum ada pelanggan terdaftar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $pelanggans->links() }}
    </div>
</div>
@endsection
