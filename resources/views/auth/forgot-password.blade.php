<x-guest-layout>
    <div class="max-w-md w-full bg-white p-6 mt-10 mx-auto rounded-lg shadow-md border border-pink-100">
        <h2 class="text-2xl font-semibold text-center text-pink-600 mb-6">Lupa Password</h2>

        @if (session('status'))
            <div class="bg-green-100 text-green-700 p-3 mb-4 rounded text-sm text-center">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email" name="email" type="email" required autofocus
                    class="w-full mt-1 p-2 border border-pink-300 rounded-md shadow-sm focus:ring-pink-400 focus:border-pink-400">
            </div>

            <button type="submit"
                class="w-full py-2 px-4 bg-pink-600 hover:bg-pink-700 text-white rounded-md font-semibold focus:outline-none focus:ring-2 focus:ring-pink-400">
                Kirim Link Reset Password
            </button>

            <div class="text-center">
                <a href="{{ route('customer.login') }}" class="text-sm text-gray-600 hover:text-pink-600">Kembali ke Login</a>
            </div>
        </form>
    </div>
</x-guest-layout>
