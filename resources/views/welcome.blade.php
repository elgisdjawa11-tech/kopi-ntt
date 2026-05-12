<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="theme-color" content="#1a392a">

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
            :root {
                --accent-coffee: #c5a059;
                --emerald-dark: #1a392a;
            }

            * { box-sizing: border-box; }

            body {
                font-family: 'Instrument Sans', sans-serif;
                margin: 0;
                padding: 0;
                background-color: #FDFDFC;
                min-height: 100vh;
                display: flex;
                flex-direction: column;
            }

            /* ─── DARK MODE ─── */
            @media (prefers-color-scheme: dark) {
                body { background-color: #0a0a0a; color: #EDEDEC; }
                .card-left { background-color: #161615 !important; color: #EDEDEC; }
                .nav-brand { color: #EDEDEC; }
                .nav-link-item { color: #EDEDEC; }
                .login-btn { color: #EDEDEC !important; }
                .desc-text { color: #A1A09A !important; }
                .copyright-text { color: #A1A09A !important; }
                .dropdown-menu-custom { background: #1e1e1c !important; border-color: #333 !important; }
                .dropdown-menu-custom a { color: #EDEDEC !important; }
                .dropdown-menu-custom a:hover { background: #2a2a28 !important; }
            }

            .text-accent { color: var(--accent-coffee) !important; }
            .bg-accent { background-color: var(--accent-coffee) !important; }

            /* ─── WRAPPER ─── */
            .page-wrapper {
                width: 100%;
                max-width: 1100px;
                margin: 0 auto;
                padding: 16px;
                display: flex;
                flex-direction: column;
                min-height: 100vh;
            }

            /* ─── HEADER ─── */
            header {
                width: 100%;
                margin-bottom: 20px;
            }

            nav.main-nav {
                display: flex;
                align-items: center;
                justify-content: space-between;
                gap: 12px;
                flex-wrap: nowrap;
            }

            .nav-brand {
                font-size: 1.1rem;
                font-weight: 700;
                letter-spacing: 0.05em;
                text-transform: uppercase;
                white-space: nowrap;
                flex-shrink: 0;
            }

            .nav-center {
                display: none;
            }

            @media (min-width: 768px) {
                .nav-center {
                    display: flex;
                    gap: 20px;
                }
                .nav-link-item {
                    font-size: 0.875rem;
                    color: #1b1b18;
                    text-decoration: none;
                    transition: color 0.2s;
                }
                .nav-link-item:hover { color: var(--accent-coffee); }
            }

            .nav-actions {
                display: flex;
                align-items: center;
                gap: 8px;
                flex-shrink: 0;
            }

            /* ─── BUTTONS ─── */
            .btn-login {
                display: inline-block;
                padding: 7px 14px;
                font-size: 0.8rem;
                border-radius: 4px;
                text-decoration: none;
                border: 1px solid transparent;
                transition: border-color 0.2s;
                white-space: nowrap;
            }
            .btn-login:hover { border-color: rgba(26,26,0,0.2); }
            .login-btn { color: #1b1b18; }

            .btn-register {
                display: inline-block;
                padding: 7px 14px;
                background-color: #1b1b18;
                color: #fff;
                font-size: 0.8rem;
                font-weight: 600;
                border-radius: 4px;
                border: none;
                cursor: pointer;
                white-space: nowrap;
            }

            /* hide default bootstrap dropdown toggle arrow */
            .btn-register::after { display: none; }

            .dropdown-menu-custom {
                position: absolute;
                right: 0;
                top: calc(100% + 4px);
                min-width: 200px;
                background: #fff;
                border: 1px solid #e5e5e5;
                border-radius: 6px;
                box-shadow: 0 8px 24px rgba(0,0,0,0.12);
                z-index: 9999;
                display: none;
                overflow: hidden;
            }
            .dropdown-menu-custom.show { display: block; }

            .dropdown-menu-custom a {
                display: block;
                padding: 10px 16px;
                font-size: 0.8rem;
                color: #333;
                text-decoration: none;
                border-bottom: 1px solid #f0f0f0;
                transition: background 0.15s;
            }
            .dropdown-menu-custom a:last-child { border-bottom: none; }
            .dropdown-menu-custom a:hover { background: #f5f5f5; }

            .btn-admin {
                padding: 7px 12px;
                background-color: var(--accent-coffee);
                color: #fff;
                border-radius: 4px;
                font-size: 0.75rem;
                font-weight: 700;
                text-transform: uppercase;
                text-decoration: none;
                white-space: nowrap;
            }
            .btn-kurir {
                padding: 7px 12px;
                background-color: #2563eb;
                color: #fff;
                border-radius: 4px;
                font-size: 0.75rem;
                font-weight: 700;
                text-transform: uppercase;
                text-decoration: none;
                white-space: nowrap;
            }
            .btn-laporan {
                padding: 7px 12px;
                background-color: #16a34a;
                color: #fff;
                border-radius: 4px;
                font-size: 0.75rem;
                font-weight: 700;
                text-transform: uppercase;
                text-decoration: none;
                white-space: nowrap;
            }
            .btn-logout {
                padding: 7px 12px;
                border: 1px solid #ef4444;
                color: #ef4444;
                background: transparent;
                border-radius: 4px;
                font-size: 0.75rem;
                font-weight: 700;
                text-transform: uppercase;
                cursor: pointer;
                transition: all 0.2s;
                white-space: nowrap;
            }
            .btn-logout:hover { background: #ef4444; color: #fff; }

            /* ─── MOBILE NAV LINKS (below header) ─── */
            .mobile-nav-links {
                display: flex;
                gap: 16px;
                margin-bottom: 12px;
                padding: 0 2px;
            }
            .mobile-nav-links a {
                font-size: 0.82rem;
                color: #555;
                text-decoration: none;
            }
            .mobile-nav-links a:hover { color: var(--accent-coffee); }

            @media (min-width: 768px) {
                .mobile-nav-links { display: none; }
            }

            /* ─── MAIN LAYOUT ─── */
            main.hero {
                display: flex;
                flex-direction: column;
                flex: 1;
                border-radius: 12px;
                overflow: hidden;
                box-shadow: 0 20px 60px rgba(0,0,0,0.12);
            }

            @media (min-width: 900px) {
                main.hero { flex-direction: row; }
            }

            /* ─── LEFT CARD ─── */
            .card-left {
                background: #fff;
                padding: 28px 24px 32px;
                box-shadow: inset 0 0 0 1px rgba(26,26,0,0.12);
                display: flex;
                flex-direction: column;
                flex: 1;
            }

            @media (min-width: 480px) {
                .card-left { padding: 36px 32px; }
            }

            @media (min-width: 900px) {
                .card-left { padding: 56px 48px; border-radius: 0; }
            }

            .hero-heading {
                margin: 0 0 8px;
                line-height: 1.15;
            }

            .hero-heading .label-top {
                display: block;
                font-size: 0.9rem;
                font-weight: 600;
                letter-spacing: 0.08em;
                text-transform: uppercase;
                color: #555;
                margin-bottom: 4px;
            }

            .hero-heading .brand-name {
                display: block;
                font-size: clamp(1.8rem, 7vw, 2.8rem);
                font-weight: 800;
                color: var(--accent-coffee);
                text-transform: uppercase;
                letter-spacing: -0.01em;
                line-height: 1;
            }

            .hero-heading .label-bottom {
                display: block;
                font-size: 0.7rem;
                font-weight: 500;
                letter-spacing: 0.22em;
                color: #888;
                margin-top: 6px;
                text-transform: uppercase;
            }

            .desc-text {
                color: #706f6c;
                font-size: 0.88rem;
                line-height: 1.65;
                margin: 16px 0 24px;
            }

            /* ─── FEATURE LIST ─── */
            .feature-list {
                list-style: none;
                padding: 0;
                margin: 0 0 28px;
                display: flex;
                flex-direction: column;
                gap: 12px;
            }

            .feature-list li {
                display: flex;
                align-items: flex-start;
                gap: 10px;
                font-size: 0.85rem;
                font-weight: 500;
                line-height: 1.4;
            }

            .feature-list li i {
                font-size: 1.1rem;
                color: var(--accent-coffee);
                flex-shrink: 0;
                margin-top: 1px;
            }

            /* ─── CTA BUTTON ─── */
            .cta-wrapper {
                display: flex;
                flex-direction: column;
                gap: 12px;
            }

            @media (min-width: 480px) {
                .cta-wrapper { flex-direction: row; }
            }

            .btn-cta {
                display: inline-block;
                padding: 14px 24px;
                background-color: #1b1b18;
                color: #fff;
                font-weight: 700;
                font-size: 0.82rem;
                letter-spacing: 0.15em;
                text-transform: uppercase;
                text-decoration: none;
                border-radius: 6px;
                text-align: center;
                transition: background 0.2s;
                box-shadow: 0 4px 16px rgba(0,0,0,0.18);
            }
            .btn-cta:hover { background-color: #000; }

            /* ─── COPYRIGHT ─── */
            .copyright-text {
                margin-top: auto;
                padding-top: 32px;
                font-size: 0.68rem;
                letter-spacing: 0.12em;
                text-transform: uppercase;
                color: #706f6c;
            }

            /* ─── RIGHT (IMAGE) PANEL ─── */
            .card-right {
                background-color: var(--emerald-dark);
                position: relative;
                overflow: hidden;
                min-height: 220px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            @media (min-width: 480px) { .card-right { min-height: 280px; } }
            @media (min-width: 900px) {
                .card-right {
                    width: 400px;
                    flex-shrink: 0;
                    min-height: unset;
                }
            }

            .card-right img {
                position: absolute;
                inset: 0;
                width: 100%;
                height: 100%;
                object-fit: cover;
                opacity: 0.5;
            }

            .card-right-overlay {
                position: absolute;
                inset: 0;
                background: linear-gradient(to top, var(--emerald-dark) 15%, transparent 70%);
            }

            .card-right-content {
                position: relative;
                z-index: 1;
                text-align: center;
                padding: 24px;
            }

            .card-right-content h2 {
                color: #fff;
                font-size: clamp(3.5rem, 15vw, 6rem);
                font-weight: 900;
                font-style: italic;
                letter-spacing: -0.04em;
                line-height: 0.9;
                margin: 0 0 16px;
            }

            @media (min-width: 900px) {
                .card-right-content h2 { font-size: 5.5rem; }
            }

            .card-right-content p {
                color: var(--accent-coffee);
                font-weight: 700;
                letter-spacing: 0.45em;
                font-size: 0.7rem;
                text-transform: uppercase;
                margin: 0;
            }

            /* ─── DROPDOWN HOVER on desktop ─── */
            @media (min-width: 1024px) {
                .register-dropdown:hover .dropdown-menu-custom { display: block; }
            }
        </style>
    </head>
    <body>
        <div class="page-wrapper">

            {{-- HEADER --}}
            <header>
                @if (Route::has('login'))
                    {{-- Mobile nav links (Katalog / Lacak) shown below header on small screens --}}
                    <div class="mobile-nav-links">
                        <a href="/">Katalog Produk</a>
                        @auth
                            <a href="{{ route('riwayat.pesanan') }}">Lacak Pesanan</a>
                        @endauth
                    </div>

                    <nav class="main-nav">
                        {{-- Brand --}}
                        <div class="nav-brand">
                            KOPI <span class="text-accent">NTT</span>
                        </div>

                        {{-- Desktop center links --}}
                        <div class="nav-center">
                            <a href="/" class="nav-link-item">Katalog Produk</a>
                            @auth
                                <a href="{{ route('riwayat.pesanan') }}" class="nav-link-item">Lacak Pesanan</a>
                            @endauth
                        </div>

                        {{-- Actions --}}
                        <div class="nav-actions">
                            @auth
                                @if(Auth::user()->role == 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="btn-admin">Admin Panel</a>
                                @elseif(Auth::user()->role == 'pengirim')
                                    <a href="{{ route('pengirim.index') }}" class="btn-kurir">Kurir</a>
                                @elseif(Auth::user()->role == 'pemilik')
                                    <a href="{{ route('admin.pemilik.dashboard') }}" class="btn-laporan">Laporan</a>
                                @endif

                                <form action="{{ route('logout') }}" method="POST" style="margin:0">
                                    @csrf
                                    <button type="submit" class="btn-logout">Keluar</button>
                                </form>
                            @else
                                <a href="{{ route('login') }}" class="btn-login login-btn">Log in</a>

                                @if (Route::has('register'))
                                    <div class="position-relative register-dropdown" style="position: relative;">
                                        <button class="btn-register" onclick="toggleDropdown(event)">
                                            Registrasi
                                        </button>
                                        <div class="dropdown-menu-custom" id="register-dropdown">
                                            <a href="{{ route('register.pelanggan') }}">Sebagai Pelanggan</a>
                                            <a href="{{ route('register.pengirim') }}">Sebagai Pengirim (Kurir)</a>
                                            <a href="{{ route('register.admin') }}">Sebagai Admin</a>
                                            <a href="{{ route('register.pemilik') }}">Sebagai Pemilik</a>
                                            <a href="{{ route('register') }}" class="hidden" style="display:none;"></a>
                                        </div>
                                    </div>
                                @endif
                            @endguest
                        </div>
                    </nav>
                @endif
            </header>

            {{-- MAIN HERO --}}
            <main class="hero">

                {{-- LEFT: Text --}}
                <div class="card-left">
                    <h1 class="hero-heading">
                        <span class="label-top">Sistem Informasi</span>
                        <span class="brand-name">Penjualan Kopi NTT</span>
                        <span class="label-bottom">Berbasis Web</span>
                    </h1>

                    <p class="desc-text">
                        Menyediakan akses digital untuk menikmati keajaiban cita rasa kopi terbaik langsung dari tanah Flobamora ke genggaman Anda.
                    </p>

                    <ul class="feature-list">
                        <li>
                            <i class="bi bi-patch-check-fill"></i>
                            <span>Produk Kopi Asli NTT (Bajawa, Manggarai, Alor)</span>
                        </li>
                        <li>
                            <i class="bi bi-patch-check-fill"></i>
                            <span>Transaksi Aman & Terintegrasi Midtrans</span>
                        </li>
                        <li>
                            <i class="bi bi-patch-check-fill"></i>
                            <span>Pelacakan Pesanan Real-Time</span>
                        </li>
                    </ul>

                    <div class="cta-wrapper">
                        <a href="{{ route('home') }}" class="btn-cta">
                            Mulai Belanja Sekarang
                        </a>
                    </div>

                    <p class="copyright-text">
                        &copy; {{ date('Y') }} SI Penjualan Kopi NTT — npm 22120068_Eulogius Jawa
                    </p>
                </div>

                {{-- RIGHT: Image --}}
                <div class="card-right">
                    <img src="https://images.unsplash.com/photo-1447933601403-0c6688de566e?q=80&w=2000" alt="Kopi NTT">
                    <div class="card-right-overlay"></div>
                    <div class="card-right-content">
                        <h2>KOPI<br><span class="text-accent">NTT</span></h2>
                        <p>Authentic Taste</p>
                    </div>
                </div>

            </main>

        </div>{{-- end page-wrapper --}}

        <script>
            function toggleDropdown(e) {
                e.stopPropagation();
                var menu = document.getElementById('register-dropdown');
                menu.classList.toggle('show');
            }
            document.addEventListener('click', function() {
                var menu = document.getElementById('register-dropdown');
                if (menu) menu.classList.remove('show');
            });
        </script>
    </body>
</html>