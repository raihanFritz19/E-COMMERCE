<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>

    {{-- Gunakan hasil build manual dari manifest.json --}}
    <link rel="stylesheet" href="{{ asset('build/assets/app-B0U_IGEg.css') }}">
    <link rel="icon" type="image/jpeg" href="{{ asset('images/Logo-larizza-kitchen.jpg') }}">
    <script src="{{ asset('build/assets/app-Bf4POITK.js') }}" type="module"></script>

    <script defer>
        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('toggleIcon');
            input.type = input.type === 'password' ? 'text' : 'password';
            icon.classList.toggle('bi-eye');
            icon.classList.toggle('bi-eye-slash');
        }
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center px-4">
    <div class="bg-white shadow-lg rounded-lg overflow-hidden w-full max-w-4xl flex flex-col md:flex-row">
        
        {{-- Ilustrasi atau Slogan --}}
        <div class="hidden md:flex flex-col justify-center items-center bg-green-600 text-white p-8 w-full md:w-1/2">
            <h2 class="text-3xl font-bold mb-2">Selamat Datang Admin</h2>
            <p class="text-sm opacity-80 text-center">Kelola produk, pesanan, dan pengguna dari panel ini</p>
        </div>

        {{-- Form Login --}}
        <div class="w-full md:w-1/2 p-8">
            <h1 class="text-2xl font-bold text-gray-700 text-center mb-6">Admin Login</h1>

            @if($errors->any())
                <div class="bg-red-100 text-red-700 text-sm p-2 rounded mb-4 text-center">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" id="email" class="w-full border border-gray-300 p-2 rounded focus:ring-green-500 focus:border-green-500" required>
                </div>

                <div class="relative">
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" name="password" id="password" class="w-full border border-gray-300 p-2 rounded pr-10 focus:ring-green-500 focus:border-green-500" required>
                    <span onclick="togglePassword()" class="absolute right-3 top-9 text-gray-500 cursor-pointer">
                        <i id="toggleIcon" class="bi bi-eye text-lg"></i>
                    </span>
                </div>

                <div class="text-right">
                    <a href="{{ route('admin.password.request') }}" class="text-sm text-green-600 hover:underline">Lupa Password?</a>
                </div>

                <button type="submit" class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700 transition">
                    Login
                </button>
            </form>
        </div>
    </div>
</body>
</html>
