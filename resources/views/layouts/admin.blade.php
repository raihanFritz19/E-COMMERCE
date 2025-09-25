<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Admin Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    {{-- Load Assets via Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="icon" type="image/jpeg" href="{{ asset('images/Logo-larizza-kitchen.jpg') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 font-sans text-gray-800">

@php
    $menuItems = [
        ['label' => 'Dashboard', 'icon' => 'fa-tachometer-alt', 'route' => 'admin.dashboard'],
        ['label' => 'Produk', 'icon' => 'fa-box', 'route' => 'admin.produk.index'],
        ['label' => 'Transaksi', 'icon' => 'fa-shopping-cart', 'route' => 'admin.transaksi.index'],
        ['label' => 'Pembayaran', 'icon' => 'fa-money-bill-wave', 'route' => 'admin.pembayaran.index'],
        ['label' => 'Pengiriman', 'icon' => 'fa-truck', 'route' => 'admin.pengiriman.index'],
        ['label' => 'Penjualan', 'icon' => 'fa-chart-line', 'route' => 'admin.penjualan.index'],
        ['label' => 'Pelanggan', 'icon' => 'fa-users', 'route' => 'admin.pelanggan.index'],
    ];
@endphp

<div x-data="{ sidebarOpen: false }" class="flex min-h-screen">

    <!-- Sidebar (Desktop) -->
    <aside class="bg-gray-800 w-64 hidden md:block shadow-lg">
        <div class="p-6 border-b border-gray-700 text-center">
            <img src="{{ asset('images/Logo-larizza-kitchen.jpg') }}" alt="Logo"
                 class="w-12 h-12 mx-auto rounded-full shadow mb-2">
            <h2 class="text-xl font-bold text-green-400">Admin Panel</h2>
            <p class="text-sm text-gray-400">Administrator</p>
        </div>

        <nav class="px-4 py-4 space-y-2 text-sm">
            @foreach ($menuItems as $item)
                @php $isActive = request()->routeIs($item['route']); @endphp
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-3 px-3 py-2 rounded-md transition-all
                   {{ $isActive ? 'bg-green-600 text-white font-semibold' : 'text-gray-300 hover:bg-green-500 hover:text-white' }}">
                    <i class="fas {{ $item['icon'] }} {{ $isActive ? 'text-white' : 'text-green-400' }}"></i>
                    {{ $item['label'] }}
                </a>
            @endforeach

            <form method="POST" action="{{ route('admin.logout') }}" class="pt-4">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-md text-red-400 hover:bg-red-600 hover:text-white transition">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </nav>
    </aside>

    <!-- Mobile Sidebar Toggle -->
    <button @click="sidebarOpen = !sidebarOpen"
            class="md:hidden fixed top-3 left-3 z-50 p-2 text-white bg-green-600 rounded-md shadow-md focus:outline-none">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Mobile Sidebar Overlay -->
    <div x-show="sidebarOpen"
         x-transition.opacity
         class="fixed inset-0 z-40 bg-black bg-opacity-50 md:hidden"
         @click="sidebarOpen = false"></div>

    <!-- Mobile Sidebar Panel -->
    <aside x-show="sidebarOpen"
           x-transition:enter="transition ease-out duration-200"
           x-transition:enter-start="transform -translate-x-full"
           x-transition:enter-end="transform translate-x-0"
           x-transition:leave="transition ease-in duration-150"
           x-transition:leave-start="transform translate-x-0"
           x-transition:leave-end="transform -translate-x-full"
           class="fixed top-0 left-0 z-50 w-3/4 max-w-xs bg-gray-800 shadow-lg h-full p-4 md:hidden">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg text-green-400 font-bold">Admin Panel</h2>
            <button @click="sidebarOpen = false" class="text-gray-300 hover:text-white">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <nav class="space-y-2 text-sm">
            @foreach ($menuItems as $item)
                <a href="{{ route($item['route']) }}"
                   class="flex items-center gap-2 px-3 py-2 rounded-md transition-all
                   {{ request()->routeIs($item['route']) ? 'bg-green-600 text-white font-semibold' : 'text-gray-300 hover:bg-green-500 hover:text-white' }}">
                    <i class="fas {{ $item['icon'] }} {{ request()->routeIs($item['route']) ? 'text-white' : 'text-green-400' }}"></i>
                    {{ $item['label'] }}
                </a>
            @endforeach

            <form method="POST" action="{{ route('admin.logout') }}" class="pt-4">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-2 px-3 py-2 rounded-md text-red-400 hover:bg-red-600 hover:text-white transition">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 p-3 md:p-6 overflow-y-auto">
        <div class="bg-white rounded shadow p-4 md:p-6">
            @yield('content')
        </div>
    </main>

</div>

</body>
</html>
