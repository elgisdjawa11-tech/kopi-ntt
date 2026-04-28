<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>SISTEM INFORMASI PENJUALAN KOPI NTT BERBASIS WEB</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                /* Style bawaan tetap dipertahankan agar layout tidak berantakan */
                {{-- [Bagian Style Tailwind v4 yang sangat panjang tadi tetap di sini] --}}
            </style>
        @endif
        
        <style>
            /* Custom Style untuk Accent Kopi */
            :root { --accent-coffee: #d4a373; }
            .text-accent { color: var(--accent-coffee); }
            .bg-accent { background-color: var(--accent-coffee); }
        </style>
    </head>
    <body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
        <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6 not-has-[nav]:hidden">
            @if (Route::has('login'))
                <nav class="flex items-center justify-between gap-4">
                    <div class="fw-bold text-lg font-semibold tracking-tight">
                        KOPI <span class="text-accent">NTT</span>
                    </div>
                    <div class="flex gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] border-[#19140035] hover:border-[#1915014a] border text-[#1b1b18] dark:border-[#3E3E3A] dark:hover:border-[#62605b] rounded-sm text-sm leading-normal">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="inline-block px-5 py-1.5 dark:text-[#EDEDEC] text-[#1b1b18] border border-transparent hover:border-[#19140035] dark:hover:border-[#3E3E3A] rounded-sm text-sm leading-normal">
                                Log in
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-block px-5 py-1.5 dark:bg-white dark:text-black bg-[#1b1b18] text-white rounded-sm text-sm leading-normal">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </div>
                </nav>
            @endif
        </header>

        <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
            <main class="flex max-w-[335px] w-full flex-col-reverse lg:max-w-4xl lg:flex-row">
                <div class="text-[13px] leading-[20px] flex-1 p-6 pb-6 lg:p-20 lg:pb-10 bg-white dark:bg-[#161615] dark:text-[#EDEDEC] shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d] rounded-bl-lg rounded-br-lg lg:rounded-tl-lg lg:rounded-br-none">
                    
                    {{-- JUDUL TUGAS AKHIR --}}
                    <h1 class="mb-2 text-xl font-bold leading-tight">
                        SISTEM INFORMASI <br>
                        <span class="text-accent uppercase">Penjualan Kopi NTT</span> <br>
                        <span class="text-gray-500 dark:text-gray-400 font-medium text-sm">BERBASIS WEB</span>
                    </h1>

                    <p class="mb-6 text-[#706f6c] dark:text-[#A1A09A]">
                        Menyediakan akses digital untuk menikmati keajaiban cita rasa kopi terbaik langsung dari tanah Flobamora ke genggaman Anda.
                    </p>

                    <ul class="flex flex-col mb-8 gap-2">
                        <li class="flex items-center gap-3">
                            <i class="bi bi-check2-circle text-accent"></i>
                            <span>Produk Kopi Asli NTT</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="bi bi-check2-circle text-accent"></i>
                            <span>Transaksi Aman & Terverifikasi</span>
                        </li>
                        <li class="flex items-center gap-3">
                            <i class="bi bi-check2-circle text-accent"></i>
                            <span>Pengiriman Langsung ke Alamat</span>
                        </li>
                    </ul>

                    <ul class="flex gap-3 text-sm leading-normal">
                        <li>
                            <a href="{{ route('home') }}" class="inline-block dark:bg-white dark:text-black hover:bg-opacity-90 px-8 py-2.5 bg-[#1b1b18] rounded-sm text-white font-semibold transition-all text-center uppercase tracking-wider">
                                Mulai Belanja Sekarang
                            </a>
                        </li>
                    </ul>

                    <p class="mt-10 text-[11px] text-[#706f6c] dark:text-[#A1A09A]">
                        &copy; {{ date('Y') }} SI Penjualan Kopi NTT - Elgis Jawa
                    </p>
                </div>

                {{-- Bagian Kanan (Gambar/Logo) --}}
                <div class="bg-[#fff2f2] dark:bg-[#1D0002] relative lg:-ml-px -mb-px lg:mb-0 rounded-t-lg lg:rounded-t-none lg:rounded-r-lg aspect-[335/364] lg:aspect-auto w-full lg:w-[438px] shrink-0 overflow-hidden">
                    {{-- Kamu bisa mengganti SVG ini dengan gambar kopi asli NTT --}}
                    <div class="absolute inset-0 flex items-center justify-center opacity-20">
                         <img src="https://img.freepik.com/free-photo/fresh-coffee-beans-spilled-from-sack-cup_1268-28318.jpg" class="object-cover w-full h-full">
                    </div>
                    <div class="relative h-full flex items-center justify-center p-12">
                         <div class="text-center">
                             <h2 class="text-[#F53003] text-6xl font-black italic tracking-tighter">KOPI NTT</h2>
                             <p class="text-[#F53003] font-bold tracking-widest mt-2 uppercase">Authentic Taste</p>
                         </div>
                    </div>
                    <div class="absolute inset-0 rounded-t-lg lg:rounded-t-none lg:rounded-r-lg shadow-[inset_0px_0px_0px_1px_rgba(26,26,0,0.16)] dark:shadow-[inset_0px_0px_0px_1px_#fffaed2d]"></div>
                </div>
            </main>
        </div>

        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif
    </body>
</html>