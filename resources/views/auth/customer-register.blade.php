<x-guest-layout>
            <div class="text-center">
                <img src="{{ asset('images/Logo-larizza-kitchen.jpg') }}" alt="Larizza Logo" class="mx-auto h-16 w-16 rounded-full mb-4">
                <h2 class="text-2xl font-bold text-pink-700">Daftar Akun Customer</h2>
            </div>

            <form method="POST" action="{{ route('customer.register') }}" class="mt-6 space-y-5">
                @csrf

                <!-- Nama -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Nama Lengkap</label>
                    <input id="name" name="name" type="text" required autofocus
                           value="{{ old('name') }}"
                           class="w-full mt-1 border border-pink-300 rounded-md shadow-sm p-2 focus:ring-pink-400 focus:border-pink-400">
                    <x-input-error :messages="$errors->get('name')" class="mt-1 text-sm text-red-500" />
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" name="email" type="email" required
                           value="{{ old('email') }}"
                           class="w-full mt-1 border border-pink-300 rounded-md shadow-sm p-2 focus:ring-pink-400 focus:border-pink-400">
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm text-red-500" />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input id="password" name="password" type="password" required
                           class="w-full mt-1 border border-pink-300 rounded-md shadow-sm p-2 focus:ring-pink-400 focus:border-pink-400">
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-sm text-red-500" />
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required
                           class="w-full mt-1 border border-pink-300 rounded-md shadow-sm p-2 focus:ring-pink-400 focus:border-pink-400">
                </div>

                <!-- Submit -->
                <div>
                    <button type="submit"
                            class="w-full py-2 px-4 bg-pink-600 hover:bg-pink-700 text-white rounded-md font-semibold focus:outline-none focus:ring-2 focus:ring-pink-400">
                        Daftar
                    </button>
                </div>
            </form>

            <div class="text-center mt-4 text-sm">
                Sudah punya akun? <a href="{{ route('customer.login') }}" class="text-pink-600 hover:underline">Masuk di sini</a>
            </div>
        </div>
    </div>
</x-guest-layout>
