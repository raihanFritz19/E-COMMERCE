<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>E-Commerce Lariizza</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Load Assets dengan Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- External Assets --}}
    <link rel="icon" type="image/jpeg" href="{{ asset('images/Logo-larizza-kitchen.jpg') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" />
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js" defer></script>

    {{-- Midtrans Snap.js (defer agar tidak blokir render) --}}
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="{{ config('midtrans.client_key') }}" defer></script>
</head>
<body class="flex flex-col min-h-screen bg-gray-100 text-gray-800" x-data="{ openNav: false }">

    <!-- Navbar -->
    <header class="bg-white shadow sticky top-0 z-50">
        <div class="container mx-auto flex items-center justify-between py-3 px-4">
            <!-- Logo -->
            <div class="flex items-center space-x-2">
                <img src="{{ asset('images/Logo-larizza-kitchen.jpg') }}" alt="Larizza Logo"
                    class="h-10 w-10 rounded-full object-cover">
                <span class="text-xl font-bold text-pink-600 tracking-wide">
                    Larizza <span class="text-green-600">Kitchen</span>
                </span>
            </div>

            <!-- Hamburger (mobile only) -->
            <button class="md:hidden text-2xl" @click="openNav = !openNav">
                <i class="bi bi-list"></i>
            </button>

            <!-- Navigation Desktop -->
            <nav class="hidden md:flex flex-wrap items-center gap-5 text-sm md:text-base font-medium">
                <a href="/" class="text-gray-700 hover:text-pink-600 transition">Beranda</a>
                <a href="/produk" class="text-gray-700 hover:text-pink-600 transition">Produk</a>
                @auth
                    @if(auth()->user()->role === 'customer')
                        <div x-data="{ open: false }" class="relative">
                            <button @click="open = !open"
                                class="flex items-center space-x-1 text-gray-700 hover:text-pink-600 transition focus:outline-none">
                                <i class="bi bi-cart3 mr-1"></i>
                                <span>Keranjang</span>
                                <i class="bi bi-caret-down-fill text-xs ml-1"></i>
                            </button>
                            <div x-show="open" @click.away="open = false"
                                class="absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded shadow-lg z-50">
                                <a href="/keranjang"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">🛒 Lihat Keranjang</a>
                                <a href="/pesanan"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Konfirmasi Pembayaran</a>
                            </div>
                        </div>
                        <a href="{{ route('order.riwayat') }}"
                            class="text-gray-700 hover:text-pink-600 transition">Riwayat</a>
                    @endif
                    @if(auth()->user()->role === 'admin')
                        <a href="/admin" class="text-gray-700 hover:text-pink-600 transition">Dashboard</a>
                    @endif
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open"
                            class="flex items-center space-x-1 text-gray-700 hover:text-pink-600 transition focus:outline-none">
                            <span><i class="bi bi-person-fill mr-1"></i><strong>{{ auth()->user()->name }}</strong></span>
                            <i class="bi bi-caret-down-fill text-xs ml-1"></i>
                        </button>
                        <div x-show="open" @click.away="open = false"
                            class="absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg z-50">
                            <a href="{{ route('password.update.custom') }}"
                                class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">🔒 Ubah Password</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">🚪 Logout</button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('customer.login') }}" class="text-pink-600 hover:underline">Login</a>
                    <a href="{{ route('customer.register') }}" class="text-green-600 hover:underline">Daftar</a>
                @endauth
            </nav>
        </div>

        <!-- Mobile Menu -->
        <div x-show="openNav" class="md:hidden bg-white shadow-md p-4 space-y-3">
            <a href="/" class="block text-gray-700 hover:text-pink-600">Beranda</a>
            <a href="/produk" class="block text-gray-700 hover:text-pink-600">Produk</a>
            <a href="/keranjang" class="block text-gray-700 hover:text-pink-600">Keranjang</a>
            <a href="/pesanan" class="block text-gray-700 hover:text-pink-600">Pesanan</a>
            @auth
                <a href="{{ route('order.riwayat') }}" class="block text-gray-700 hover:text-pink-600">Riwayat</a>
                @if(auth()->user()->role === 'admin')
                    <a href="/admin" class="block text-gray-700 hover:text-pink-600">Dashboard</a>
                @endif
                <a href="{{ route('password.update.custom') }}" class="block text-gray-700 hover:text-pink-600">Ubah Password</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left text-red-600 hover:text-pink-600">Logout</button>
                </form>
            @else
                <a href="{{ route('customer.login') }}" class="block text-pink-600 hover:underline">Login</a>
                <a href="{{ route('customer.register') }}" class="block text-green-600 hover:underline">Daftar</a>
            @endauth
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 container mx-auto p-6">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t py-2 px-4 mt-6">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 text-sm text-gray-700">
            <div>
                <h3 class="text-lg font-semibold mb-3 text-green-700">Lokasi Kami</h3>
                <div class="rounded overflow-hidden shadow-md">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.0448754867693!2d106.97265757399039!3d-6.257819193730697!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e698d2f24695345%3A0xa5267aa0e478167f!2sLarIzza%20Kitchen!5e0!3m2!1sen!2sid!4v1753626424451!5m2!1sen!2sid"
                        class="w-full h-44 rounded shadow-md" style="border:0;" allowfullscreen="" loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-3 text-green-700">Navigasi</h3>
                <ul class="space-y-2">
                    <li><a href="/" class="hover:underline">Beranda</a></li>
                    <li><a href="/produk" class="hover:underline">Produk</a></li>
                    <li><a href="/keranjang" class="hover:underline">Keranjang</a></li>
                    <li><a href="/pesanan" class="hover:underline">Pesanan</a></li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-3 text-green-700">Kontak Kami</h3>
                <p class="mb-2">Jl. Elang 3 No.21 Blok B.8<br>RT.002/RW.010, Pekayon Jaya<br>Kec. Bekasi Selatan, Kota Bks</p>
                <p class="mb-1"><a href="https://wa.link/dd25gc" class="text-green-600 hover:underline"><i class="bi bi-whatsapp"></i> WhatsApp</a></p>
                <p class="mb-1"><a href="https://www.facebook.com/larizza.kitchen/" class="text-blue-600 hover:underline"><i class="bi bi-facebook"></i> Facebook</a></p>
                <p><a href="https://www.instagram.com/larizzakitchen/" class="text-pink-600 hover:underline"><i class="bi bi-instagram"></i> Instagram</a></p>
            </div>
        </div>
        <div class="text-center text-xs text-gray-500 mt-5 border-t pt-2">
            &copy; {{ date('Y') }} Larizza Kitchen. All rights reserved.
        </div>
    </footer>

    <!--Start of Tawk.to Script (async load)-->
    <script async src="https://embed.tawk.to/6843b871bd14f0190719edef/1it4lfo87"></script>
</body>
</html>
