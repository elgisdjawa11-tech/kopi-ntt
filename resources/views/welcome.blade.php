<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>SISTEM INFORMASI PENJUALAN KOPI NTT BERBASIS WEB</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <!-- Bootstrap Icons untuk Icon Checklist -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <!-- Flowbite/Bootstrap JS untuk Dropdown -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
        @endif
        
        <style>
            :root { --accent-coffee: #d4a373; }
            .text-accent { color: var(--accent-coffee); }
            .bg-accent { background-color: var(--accent-coffee); }
            /* Memastikan Dropdown muncul saat hover untuk UX yang lebih baik */
            .dropdown:hover .dropdown-menu { display: block; }
        </style>
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        
        {{-- HEADER / NAVIGATION --}}
        <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6">
            @if (Route::has('login'))
                <nav class="flex items-center justify-between gap-4">
                    <div class="fw-bold text-lg font-semibold tracking-tight uppercase">
                        KOPI <span class="text-accent">NTT</span>
                    </div>
                    <div class="flex items-center gap-4">
                        @auth
                            {{-- TAMPILAN JIKA SUDAH LOGIN --}}
                            <div class="flex gap-2">
                                @if(Auth::user()->role == 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="px-4 py-1.5 bg-accent text-white rounded-sm text-xs font-bold uppercase">Admin Panel</a>
                                @elseif(Auth::user()->role == 'pengirim')
                                    <a href="{{ route('pengirim.index') }}" class="px-4 py-1.5 bg-blue-600 text-white rounded-sm text-xs font-bold uppercase">Kurir Dashboard</a>
                                @elseif(Auth::user()->role == 'pemilik')
                                    <a href="{{ route('admin.laporan.penjualan') }}" class="px-4 py-1.5 bg-green-600 text-white rounded-sm text-xs font-bold uppercase">Laporan</a>
                                @endif

                                <form action="{{ route('logout') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-4 py-1.5 border border-red-500 text-red-500 hover:bg-red-500 hover:text-white rounded-sm text-xs font-bold uppercase transition-all">Keluar</button>
                                </form>
                            </div>
                        @else
                            {{-- TAMPILAN JIKA BELUM LOGIN --}}
                            <a href="{{ route('login') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] rounded-sm text-sm">
                                Log in
                            </a>

                            {{-- Dropdown Registrasi Berdasarkan Entitas (Solusi untuk Error Route register not defined) --}}
                            <div class="relative group dropdown">
                                <button class="inline-block px-5 py-1.5 bg-[#1b1b18] text-white rounded-sm text-sm font-semibold">
                                    Register <i class="bi bi-chevron-down text-[10px]"></i>
                                </button>
                                <div class="absolute right-0 mt-0 w-48 bg-white border shadow-lg rounded-sm hidden dropdown-menu z-50">
                                    <a href="{{ route('register.pelanggan') }}" class="block px-4 py-2 text-xs hover:bg-gray-100 text-gray-800">Sebagai Pelanggan</a>
                                    <a href="{{ route('register.pengirim') }}" class="block px-4 py-2 text-xs hover:bg-gray-100 text-gray-800 border-t">Sebagai Pengirim (Kurir)</a>
                                    <a href="{{ route('register.admin') }}" class="block px-4 py-2 text-xs hover:bg-gray-100 text-gray-800 border-t">Sebagai Admin</a>
                                    <a href="{{ route('register.pemilik') }}" class="block px-4 py-2 text-xs hover:bg-gray-100 text-gray-800 border-t">Sebagai Pemilik</a>
                                </div>
                            </div>
                        @endauth
                    </div>
                </nav>
            @endif
        </header>

        {{-- MAIN CONTENT --}}
        <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow">
            <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row">
                
                {{-- Bagian Kiri (Teks) --}}
                <div class="text-[13px] leading-[20px] flex-1 p-6 pb-6 lg:p-20 lg:pb-10 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] rounded-bl-lg rounded-br-lg lg:rounded-tl-lg lg:rounded-br-none">
                    
                    <h1 class="mb-2 text-2xl font-bold leading-tight">
                        SISTEM INFORMASI <br>
                        <span class="text-accent uppercase text-3xl">Penjualan Kopi NTT</span> <br>
                        <span class="text-gray-500 dark:text-gray-400 font-medium text-sm tracking-[0.2em]">BERBASIS WEB</span>
                    </h1>

                    <p class="mb-6 text-[#706f6c] dark:text-[#A1A09A] text-sm">
                        Menyediakan akses digital untuk menikmati keajaiban cita rasa kopi terbaik langsung dari tanah Flobamora ke genggaman Anda.
                    </p>

                    <ul class="flex flex-col mb-8 gap-3 font-medium">
                        <li class="flex items-center gap-3">
                            <i class="bi bi-patch-check-fill text-accent text-lg"></i>
                            <span>Produk Kopi Asli NTT (Bajawa, Manggarai, Alor)</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="bi bi-patch-check-fill text-accent text-lg"></i>
                            <span>Transaksi Aman & Terintegrasi Midtrans</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="bi bi-patch-check-fill text-accent text-lg"></i>
                            <span>Pelacakan Pesanan Real-Time</span>
                        </li>
                    </ul>

                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('home') }}" class="inline-block px-10 py-3 bg-[#1b1b18] hover:bg-black rounded-sm text-white font-bold transition-all text-center uppercase tracking-widest text-sm shadow-lg shadow-black/20">
                            Mulai Belanja Sekarang
                        </a>
                    </div>

                    <p class="mt-12 text-[10px] uppercase tracking-widest text-[#706f6c] dark:text-[#A1A09A]">
                        &copy; {{ date('Y') }} SI Penjualan Kopi NTT - Elgis Jawa
                    </p>
                </div>

                {{-- Bagian Kanan (Gambar/Logo) --}}
                <div class="bg-[#2a1b15] relative lg:-ml-px -mb-px lg:mb-0 rounded-t-lg lg:rounded-t-none lg:rounded-r-lg aspect-[335/364] lg:aspect-auto w-full lg:w-[438px] shrink-0 overflow-hidden shadow-2xl">
                    <div class="absolute inset-0 flex items-center justify-center opacity-40">
                         <img src="https://img.freepik.com/free-photo/fresh-coffee-beans-spilled-from-sack-cup_1268-28318.jpg" class="object-cover w-full h-full grayscale hover:grayscale-0 transition-all duration-1000">
                    </div>
                    <div class="relative h-full flex items-center justify-center p-12 bg-gradient-to-t from-[#2a1b15] to-transparent">
                         <div class="text-center">
                             <h2 class="text-white text-7xl font-black italic tracking-tighter leading-none">KOPI<br><span class="text-accent">NTT</span></h2>
                             <p class="text-accent font-bold tracking-[0.5em] mt-4 uppercase text-xs">Authentic Taste</p>
                         </div>
                    </div>
                </div>
            </main>
        </div>

        <div class="h-10 hidden lg:block"></div>
    </body>
</html>