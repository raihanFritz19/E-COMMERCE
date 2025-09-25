@extends('layouts.app')

@section('content')

<div class="max-w-2xl mx-auto py-10 px-4">
    <h1 class="text-2xl font-bold text-gray-800 mb-6">Konfirmasi Pembayaran</h1>

    @if ($order)
        <div class="mb-6 p-4 bg-blue-50 text-blue-700 rounded shadow">
            <strong>ID Transaksi:</strong> {{ $order->id }}<br>
            <strong>Total:</strong> Rp {{ number_format($order->total, 0, ',', '.') }}<br> <!-- GANTI subtotal jadi total -->
            <strong>Status:</strong> {{ ucfirst($order->status) }}<br>
        </div>
    @endif

    {{-- Validasi --}}
    @if ($errors->any())
        <div class="mb-4 bg-red-100 text-red-700 p-3 rounded">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li class="text-sm">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Sukses --}}
    @if (session('success'))
        <div class="mb-4 bg-green-100 text-green-700 p-3 rounded">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('order.konfirmasi') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow rounded p-6 space-y-5">
        @csrf

        {{-- Input trx_id WAJIB ADA DI DALAM FORM --}}
        @if($order)
            <input type="hidden" name="trx_id" value="{{ $order->id }}">
        @endif

        {{-- Nama Penyetor otomatis --}}
        <div>
            <label for="nama_penyetor" class="block text-sm font-medium text-gray-700">Nama Penyetor</label>
            <input type="text" id="nama_penyetor" value="{{ auth()->user()->name }}" readonly
                class="w-full mt-1 p-2 border border-gray-300 rounded bg-gray-100 text-gray-700">
        </div>
        <input type="hidden" name="nama_penyetor" value="{{ auth()->user()->name }}">
        <div>
            <label for="bukti" class="block text-sm font-medium text-gray-700">Foto Bukti</label>
            <input type="file" name="bukti" id="bukti"
                class="mt-1 block w-full text-sm text-gray-700 border border-gray-300 rounded cursor-pointer bg-white file:mr-4 file:py-2 file:px-4 file:border-0 file:text-sm file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100"
                accept="image/*" required>
            <p class="text-xs text-gray-500 mt-1">Foto bukti harus JPG maksimal 2MB</p>
        </div>

        <div class="text-right pt-2">
            <button type="submit"
                class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                Kirim
            </button>
        </div>
    </form>
</div>
@endsection