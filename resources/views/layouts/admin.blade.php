<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Admin Kopi NTT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { 
            --emerald-dark: #1a392a; 
            --admin-dark: #0f1f1a; 
            --accent: #c5a059; 
            --bg-light: #fdfaf7; 
            --sidebar-width: 280px; 
        }
        body { font-family: 'Poppins', sans-serif; background-color: var(--bg-light); color: var(--emerald-dark); overflow-x: hidden; }
        
        /* Sidebar Styling */
        .sidebar { 
            width: var(--sidebar-width); 
            height: 100vh; 
            position: fixed; 
            padding: 30px 20px; 
            z-index: 1000; 
            color: white;
            background: {{ Auth::user()->role == 'pemilik' ? 'var(--emerald-dark)' : 'var(--admin-dark)' }};
            display: flex;
            flex-direction: column;
            transition: 0.3s;
            border-right: 2px solid var(--accent);
        }

        .sidebar-brand h3 { letter-spacing: 1px; color: white; }

        .nav-link { 
            color: rgba(255,255,255,0.6); 
            padding: 12px 20px; 
            border-radius: 12px; 
            margin-bottom: 5px; 
            display: flex; 
            align-items: center; 
            text-decoration: none; 
            transition: 0.3s; 
        }
        .nav-link i { font-size: 1.1rem; }
        .nav-link.active { background: var(--accent); color: var(--emerald-dark); font-weight: 600; }
        .nav-link:hover { color: white; background: rgba(255,255,255,0.1); }
        
        .main-content { margin-left: var(--sidebar-width); min-height: 100vh; display: flex; flex-direction: column; }
        .topbar { background: white; padding: 15px 40px; border-bottom: 2px solid var(--accent); }
        .content-body { padding: 40px; flex: 1; }
        
        .logout-container { margin-top: auto; padding-top: 20px; }
        .btn-exit { 
            background-color: #721c24; 
            color: white; 
            border: none; 
            width: 100%; 
            padding: 12px; 
            border-radius: 12px; 
            font-weight: 600; 
            display: flex; 
            align-items: center; 
            justify-content: center;
            transition: 0.3s;
            text-decoration: none;
            border: 1px solid var(--accent);
        }
        .btn-exit:hover { background-color: #c5a059; color: white; transform: translateY(-2px); }

        /* Global Card Styling */
        .admin-card { background: white; border-radius: 20px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); border-left: 5px solid var(--accent); }
        
        .sidebar-badge { font-size: 0.7rem; background: var(--accent); color: var(--emerald-dark); border-radius: 50px; padding: 2px 8px; font-weight: bold; margin-left: auto; }

        @media (max-width: 992px) {
            .sidebar { margin-left: calc(-1 * var(--sidebar-width)); }
            .main-content { margin-left: 0; }
            .sidebar.active { margin-left: 0; }
        }
    </style>
    @yield('styles')
</head>
<body>

<div class="sidebar" id="sidebar">
    <div class="sidebar-brand text-center mb-4">
        <h3 class="text-white fw-bold">KOPI <span style="color: var(--accent)">NTT</span></h3>
        <p class="small text-white-50 text-uppercase tracking-wider mb-0">
            {{ Auth::user()->role == 'pemilik' ? 'Owner Panel' : 'Admin Panel' }}
        </p>
    </div>

    <nav class="nav flex-column">
        @if(Auth::user()->role == 'admin')
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill me-3"></i> Dashboard
            </a>
            <a href="{{ route('admin.orders.index') }}" class="nav-link {{ Route::is('admin.orders.*') ? 'active' : '' }}">
                <i class="bi bi-cart-fill me-3"></i> Pesanan 
                <span class="sidebar-badge" id="sidebarNotif" style="display:none;">!</span>
            </a>
            <a href="{{ route('admin.products.index') }}" class="nav-link {{ Route::is('admin.products.*') ? 'active' : '' }}">
                <i class="bi bi-cup-hot-fill me-3 text-warning"></i> Stok Produk
            </a>
            <hr class="text-white-50">
            <p class="small text-white-50 px-3 mb-1">LAPORAN</p>
            <a href="{{ route('admin.laporan.penjualan') }}" class="nav-link {{ Route::is('admin.laporan.penjualan') ? 'active' : '' }}">
                <i class="bi bi-graph-up-arrow me-3"></i> Laporan Jual
            </a>
        @else
            {{-- Role Pemilik --}}
            <a href="{{ route('admin.pemilik.dashboard') }}" class="nav-link {{ Route::is('admin.pemilik.dashboard') ? 'active' : '' }}">
                <i class="bi bi-grid-1x2-fill me-3"></i> Dashboard Utama
            </a>
            <a href="{{ route('admin.laporan.penjualan') }}" class="nav-link {{ Route::is('admin.laporan.penjualan') ? 'active' : '' }}">
                <i class="bi bi-graph-up-arrow me-3"></i> Laporan Penjualan
            </a>
            <a href="{{ route('admin.laporan.produk') }}" class="nav-link {{ Route::is('admin.laporan.produk') ? 'active' : '' }}">
                <i class="bi bi-box-seam me-3"></i> Laporan Produk
            </a>
            <a href="{{ route('admin.laporan.pelanggan') }}" class="nav-link {{ Route::is('admin.laporan.pelanggan') ? 'active' : '' }}">
                <i class="bi bi-people me-3"></i> Laporan Pelanggan
            </a>
        @endif
    </nav>

    <div class="logout-container">
        <a href="{{ route('home') }}" class="nav-link text-warning fw-bold mb-2">
            <i class="bi bi-house-door me-3"></i> Lihat Toko
        </a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-exit shadow-sm">
                <i class="bi bi-box-arrow-left me-2"></i> KELUAR
            </button>
        </form>
    </div>
</div>

<div class="main-content">
    <div class="topbar d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <button class="btn btn-light d-lg-none me-3" onclick="document.getElementById('sidebar').classList.toggle('active')">
                <i class="bi bi-list"></i>
            </button>
            <div>
                <h5 class="fw-bold m-0">Halo, {{ Auth::user()->name }}</h5>
                <small class="text-muted">Panel Kendali Kopi NTT ({{ ucfirst(Auth::user()->role) }})</small>
            </div>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="bg-light p-2 px-3 rounded-pill small border shadow-sm">
                <i class="bi bi-clock me-2 text-warning"></i> {{ date('l, d M Y') }}
            </div>
        </div>
    </div>

    <div class="content-body">
        @yield('content')
    </div>
</div>

<audio id="notifSound"><source src="https://assets.mixkit.co/active_storage/sfx/2358/2358-preview.mp3" type="audio/mpeg"></audio>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    @if(Auth::user()->role == 'admin')
    function checkNewOrders() {
        fetch("{{ route('admin.check_orders') }}")
            .then(response => response.json())
            .then(data => {
                const sidebarBadge = document.getElementById('sidebarNotif');
                if (data.new_order) {
                    sidebarBadge.style.display = "inline-block";
                    const sound = document.getElementById('notifSound');
                    sound.play().catch(e => {});
                } else {
                    sidebarBadge.style.display = "none";
                }
            })
            .catch(error => console.error('Error:', error));
    }
    setInterval(checkNewOrders, 15000); 
    checkNewOrders();
    @endif
</script>
@yield('scripts')
</body>
</html>