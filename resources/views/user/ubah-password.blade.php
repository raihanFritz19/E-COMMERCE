@extends('layouts.app')

@section('content')
<div class="flex justify-center items-center min-h-[60vh] px-4">
    <div class="bg-white shadow-md rounded-lg p-6 w-full sm:max-w-md">
        <h2 class="text-2xl font-semibold text-center text-green-700 mb-6">Ubah Password</h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 text-sm p-3 rounded mb-4 text-center">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 text-red-700 text-sm p-3 rounded mb-4 text-center">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.update.custom') }}" class="space-y-4">
            @csrf
            <div>
                <label for="old_password" class="block text-sm font-medium text-gray-700 mb-1">Password Lama</label>
                <input type="password" name="old_password" id="old_password"
                    class="block w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-green-400"
                    required>
            </div>

            <div>
                <label for="new_password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                <input type="password" name="new_password" id="new_password"
                    class="block w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-green-400"
                    required>
            </div>

            <div>
                <label for="new_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                    class="block w-full p-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-green-400"
                    required>
            </div>

            <div class="flex justify-end">
                <button type="submit"
                    class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection