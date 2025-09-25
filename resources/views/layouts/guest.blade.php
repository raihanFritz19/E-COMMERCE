<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Larizza Kitchen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Gunakan hasil build dari public/build/assets --}}
    <link rel="stylesheet" href="{{ asset('build/assets/app-B0U_IGEg.css') }}">
    <script src="{{ asset('build/assets/app-Bf4POITK.js') }}" type="module"></script>
</head>
<body class="bg-pink-50 text-gray-800 flex flex-col min-h-screen">

    <!-- Navbar -->
    <header class="bg-white shadow sticky top-0 z-10">
        <div class="max-w-7xl mx-auto flex justify-between items-center px-4 py-3">
            <div class="flex items-center gap-2">
                <img src="{{ asset('images/Logo-larizza-kitchen.jpg') }}" alt="Larizza Logo" class="h-8 w-8 rounded-full object-cover">
                <span class="text-lg font-bold text-green-700">Larizza Kitchen</span>
            </div>
            <nav class="space-x-4 text-sm md:text-base">
                <a href="{{ url('/') }}" class="hover:text-green-600">Beranda</a>
                <a href="{{ url('/produk') }}" class="hover:text-green-600">Produk</a>
            </nav>
        </div>
    </header>

    <!-- Content -->
    <main class="flex-1 flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            {{ $slot }}
        </div>
    </main>

    <!-- Footer -->
    <footer class="text-center text-xs text-gray-500 py-4 border-t">
        &copy; {{ date('Y') }} Larizza Kitchen. All rights reserved.
    </footer>

</body>
</html>
