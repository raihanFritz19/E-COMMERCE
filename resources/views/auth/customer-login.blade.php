<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-pink-50 px-4">
        <div class="max-w-md w-full bg-white p-8 rounded-lg shadow-lg border border-pink-100">
            <div class="text-center">
                <img src="{{ asset('images/Logo-larizza-kitchen.jpg') }}" alt="Larizza Logo" class="mx-auto h-16 w-16 rounded-full mb-4">
                <h2 class="text-2xl font-bold text-pink-700">Login Customer</h2>
                <p class="mt-1 text-sm text-gray-600">Selamat datang kembali! Silakan login untuk melanjutkan.</p>
            </div>

            @if($errors->any())
                <div class="mt-4 text-sm text-red-600 bg-red-100 border border-red-200 rounded p-3">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('customer.login') }}" class="mt-6 space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" name="email" type="email" required autofocus autocomplete="username"
                           class="w-full mt-1 border border-pink-300 rounded-md shadow-sm p-2 focus:ring-pink-400 focus:border-pink-400"
                           value="{{ old('email') }}">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" name="password" type="password" required autocomplete="current-password"
                           class="w-full mt-1 border border-pink-300 rounded-md shadow-sm p-2 focus:ring-pink-400 focus:border-pink-400">
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center text-sm text-gray-600">
                        <input type="checkbox" name="remember" class="rounded border-gray-300 text-pink-500 shadow-sm focus:ring-pink-400">
                        <span class="ml-2">Ingat saya</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm text-pink-600 hover:underline">Lupa password?</a>
                </div>
                <div>
                    <button type="submit"
                            class="w-full py-2 px-4 bg-pink-600 hover:bg-pink-700 text-white rounded-md font-semibold focus:outline-none focus:ring-2 focus:ring-pink-400">
                        Masuk
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
