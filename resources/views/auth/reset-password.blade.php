<x-guest-layout>
    <div class="max-w-md w-full bg-white p-6 mt-10 mx-auto rounded-lg shadow-md border border-pink-100">
        <h2 class="text-2xl font-semibold text-center text-pink-600 mb-6">Reset Password</h2>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 mb-4 rounded text-sm text-center">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}" class="space-y-5">
        @csrf
        @method('PUT')
            <input type="hidden" name="token" value="{{ $token }}">
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $email) }}" required
                    class="w-full mt-1 p-2 border border-pink-300 rounded-md shadow-sm focus:ring-pink-400 focus:border-pink-400">
            </div>
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                <input id="password" name="password" type="password" required
                    class="w-full mt-1 p-2 border border-pink-300 rounded-md shadow-sm focus:ring-pink-400 focus:border-pink-400">
            </div>
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                <input id="password_confirmation" name="password_confirmation" type="password" required
                    class="w-full mt-1 p-2 border border-pink-300 rounded-md shadow-sm focus:ring-pink-400 focus:border-pink-400">
            </div>
            <button type="submit"
                class="w-full py-2 px-4 bg-pink-600 hover:bg-pink-700 text-white rounded-md font-semibold focus:outline-none focus:ring-2 focus:ring-pink-400">
                Simpan Password Baru
            </button>
        </form>
    </div>
</x-guest-layout>
