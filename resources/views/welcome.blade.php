<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>SISTEM INFORMASI PENJUALAN KOPI NTT BERBASIS WEB</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <script src="https://cdn.tailwindcss.com"></script>
        @endif
        
        <style>
            :root { --accent-coffee: #c5a059; --emerald-dark: #1a392a; }
            .text-accent { color: var(--accent-coffee) !important; }
            .bg-accent { background-color: var(--accent-coffee) !important; }
            /* UX: Dropdown muncul saat hover di desktop */
            @media (min-width: 1024px) {
                .dropdown:hover .dropdown-menu { display: block; margin-top: 0; }
            }
        </style>
    </head>

    {{-- PERBAIKAN 1: Hapus items-center dan justify-center dari body agar navbar tidak terdorong keluar layar --}}
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] min-h-screen flex flex-col overflow-x-hidden font-sans">
        
        {{-- HEADER / NAVIGATION (Akan selalu berada di paling atas) --}}
        <header class="w-full border-b border-gray-100 dark:border-gray-800 z-50 bg-white/80 dark:bg-[#0a0a0a]/80 backdrop-blur-md sticky top-0">
            <div class="max-w-5xl mx-auto p-4 sm:p-6 flex flex-col sm:flex-row items-center justify-between gap-4">
                
                @if (Route::has('login'))
                    <div class="fw-bold text-2xl font-black tracking-tight uppercase text-center sm:text-left">
                        KOPI <span class="text-accent">NTT</span>
                    </div>

                    <nav class="flex flex-col sm:flex-row items-center gap-4 sm:gap-6 w-full sm:w-auto">
                        {{-- MENU TENGAH --}}
                        <div class="flex flex-wrap justify-center gap-4 font-semibold text-sm">
                            <a href="/" class="hover:text-accent transition">Katalog</a>
                            @auth
                                <a href="{{ route('riwayat.pesanan') }}" class="hover:text-accent transition">Lacak Pesanan</a>
                            @endauth
                        </div>

                        @auth
                            {{-- TAMPILAN JIKA SUDAH LOGIN --}}
                            <div class="flex flex-wrap justify-center gap-2">
                                @if(Auth::user()->role == 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="px-4 py-1.5 bg-accent text-white rounded-sm text-xs font-bold uppercase text-center">Admin Panel</a>
                                @elseif(Auth::user()->role == 'pengirim')
                                    <a href="{{ route('pengirim.index') }}" class="px-4 py-1.5 bg-blue-600 text-white rounded-sm text-xs font-bold uppercase text-center">Kurir Dashboard</a>
                                @elseif(Auth::user()->role == 'pemilik')
                                    <a href="{{ route('admin.pemilik.dashboard') }}" class="px-4 py-1.5 bg-green-600 text-white rounded-sm text-xs font-bold uppercase text-center">Laporan</a>
                                @endif

                                <form action="{{ route('logout') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-4 py-1.5 border border-red-500 text-red-500 hover:bg-red-500 hover:text-white rounded-sm text-xs font-bold uppercase transition-all w-full sm:w-auto">Keluar</button>
                                </form>
                            </div>
                        @else
                            {{-- TAMPILAN JIKA BELUM LOGIN --}}
                            <div class="flex flex-wrap items-center justify-center gap-2">
                                <a href="{{ route('login') }}" class="px-5 py-2 sm:py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-gray-300 hover:border-gray-500 rounded-sm text-sm font-bold transition">
                                    Log in
                                </a>

                                @if (Route::has('register'))
                                    <div class="relative group dropdown">
                                        <button class="px-5 py-2 sm:py-1.5 bg-[#1b1b18] text-white rounded-sm text-sm font-bold dropdown-toggle shadow-md" data-bs-toggle="dropdown">
                                            Registrasi
                                        </button>
                                        <div class="absolute right-0 sm:right-0 mt-2 w-48 bg-white border shadow-lg rounded-sm hidden dropdown-menu z-50">
                                            <a href="{{ route('register.pelanggan') }}" class="block px-4 py-3 sm:py-2 text-xs sm:text-sm hover:bg-gray-100 text-gray-800 font-medium">Sebagai Pelanggan</a>
                                            <a href="{{ route('register.pengirim') }}" class="block px-4 py-3 sm:py-2 text-xs sm:text-sm hover:bg-gray-100 text-gray-800 font-medium border-t">Sebagai Pengirim (Kurir)</a>
                                            <a href="{{ route('register.admin') }}" class="block px-4 py-3 sm:py-2 text-xs sm:text-sm hover:bg-gray-100 text-gray-800 font-medium border-t">Sebagai Admin</a>
                                            <a href="{{ route('register.pemilik') }}" class="block px-4 py-3 sm:py-2 text-xs sm:text-sm hover:bg-gray-100 text-gray-800 font-medium border-t">Sebagai Pemilik</a>
                                            <a href="{{ route('register') }}" class="hidden"></a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endauth
                    </nav>
                @endif
            </div>
        </header>

        {{-- MAIN CONTENT (Dibungkus flex-grow agar mengisi sisa ruang dan posisinya di tengah layar) --}}
        <div class="flex-grow flex items-center justify-center p-4 sm:p-8 w-full">
            <main class="flex w-full max-w-md sm:max-w-xl md:max-w-2xl lg:max-w-4xl flex-col-reverse lg:flex-row shadow-2xl rounded-xl overflow-hidden bg-white dark:bg-[#161615]">
                
                {{-- Bagian Kiri (Teks) --}}
                <div class="flex-1 p-6 sm:p-8 lg:p-14 xl:p-16 flex flex-col justify-center dark:text-[#EDEDEC]">
                    <h1 class="mb-3 sm:mb-4 text-2xl sm:text-3xl font-bold leading-tight text-center sm:text-left">
                        SISTEM INFORMASI <br>
                        <span class="text-accent uppercase text-3xl sm:text-4xl">Penjualan Kopi NTT</span> <br>
                        <span class="text-gray-500 dark:text-gray-400 font-medium text-[11px] sm:text-xs tracking-[0.2em]">BERBASIS WEB</span>
                    </h1>

                    <p class="mb-6 text-[#706f6c] dark:text-[#A1A09A] text-sm sm:text-base leading-relaxed text-center sm:text-left">
                        Menyediakan akses digital untuk menikmati keajaiban cita rasa kopi terbaik langsung dari tanah Flobamora ke genggaman Anda.
                    </p>

                    <ul class="flex flex-col mb-8 gap-3 font-medium text-sm sm:text-base">
                        <li class="flex items-start sm:items-center gap-3">
                            <i class="bi bi-patch-check-fill text-accent text-lg mt-0.5 sm:mt-0"></i>
                            <span>Produk Kopi Asli NTT (Bajawa, Manggarai, Alor)</span>
                        </li>
                        <li class="flex items-start sm:items-center gap-3">
                            <i class="bi bi-patch-check-fill text-accent text-lg mt-0.5 sm:mt-0"></i>
                            <span>Transaksi Aman & Terintegrasi Midtrans</span>
                        </li>
                        <li class="flex items-start sm:items-center gap-3">
                            <i class="bi bi-patch-check-fill text-accent text-lg mt-0.5 sm:mt-0"></i>
                            <span>Pelacakan Pesanan Real-Time</span>
                        </li>
                    </ul>

                    <div class="flex flex-col sm:flex-row gap-3 mt-auto">
                        <a href="{{ route('home') }}" class="inline-block w-full sm:w-auto px-8 py-3.5 bg-[#1b1b18] hover:bg-black rounded-sm text-white font-bold transition-all text-center uppercase tracking-widest text-sm shadow-lg shadow-black/20">
                            Mulai Belanja Sekarang
                        </a>
                    </div>

                    <p class="mt-8 text-[10px] sm:text-xs uppercase tracking-widest text-[#706f6c] dark:text-[#A1A09A] text-center sm:text-left">
                        &copy; {{ date('Y') }} SI Penjualan Kopi NTT - npm 22120068_Eulogius Jawa
                    </p>
                </div>

                {{-- Bagian Kanan (Gambar/Logo) --}}
                <div class="bg-[#1a392a] relative w-full lg:w-[45%] h-[280px] sm:h-[350px] lg:h-auto shrink-0 overflow-hidden">
                    <div class="absolute inset-0 flex items-center justify-center opacity-60">
                         <img src="https://images.unsplash.com/photo-1447933601403-0c6688de566e?q=80&w=2000" class="object-cover w-full h-full transition-all duration-1000" alt="Kopi NTT Background">
                    </div>
                    <div class="relative h-full flex items-center justify-center p-8 bg-gradient-to-t from-[#1a392a] to-transparent">
                         <div class="text-center">
                             <h2 class="text-white text-5xl sm:text-6xl lg:text-7xl font-black italic tracking-tighter leading-none">KOPI<br><span class="text-accent">NTT</span></h2>
                             <p class="text-accent font-bold tracking-[0.5em] mt-3 sm:mt-4 uppercase text-[10px] sm:text-xs">Authentic Taste</p>
                         </div>
                    </div>
                </div>
            </main>
        </div>

    </body>
</html>