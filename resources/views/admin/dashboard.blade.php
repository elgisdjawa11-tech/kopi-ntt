<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Sistem Informasi Penjualan Kopi NTT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { --coffee-dark: #3e2723; --sidebar-width: 260px; --accent-gold: #d4a373; }
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; overflow-x: hidden; }
        
        /* Sidebar Styling */
        .sidebar { width: var(--sidebar-width); height: 100vh; position: fixed; background: var(--coffee-dark); color: white; z-index: 1000; box-shadow: 4px 0 10px rgba(0,0,0,0.1); }
        .sidebar .nav-link { color: rgba(255,255,255,0.7); padding: 12px 25px; display: flex; align-items: center; gap: 12px; transition: 0.3s; text-decoration: none; border-radius: 0; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: white; background: rgba(255,255,255,0.1); border-left: 4px solid var(--accent-gold); }
        
        .sidebar-badge { font-size: 0.7rem; background: #dc3545; color: white; border-radius: 50px; padding: 2px 8px; font-weight: bold; display: none; }

        .main-content { margin-left: var(--sidebar-width); padding: 0; }
        .topbar { background: white; padding: 15px 40px; border-bottom: 1px solid #eee; }
        .content-body { padding: 40px; }
        
        /* Card Styling */
        .stat-card { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: 0.3s; border-bottom: 4px solid transparent; }
        .stat-card:hover { transform: translateY(-5px); border-bottom: 4px solid var(--accent-gold); }
        .table-container { background: white; border-radius: 15px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }

        .btn-logout { color: #ff6b6b; transition: 0.3s; border: none; background: transparent; padding: 12px 25px; display: flex; align-items: center; gap: 12px; width: 100%; text-align: left; }
        .btn-logout:hover { background: rgba(255, 107, 107, 0.1); color: #ff5252; }
    </style>
</head>
<body>

<div class="sidebar d-flex flex-column p-0">
    <div class="text-center py-4 mb-4" style="background: rgba(0,0,0,0.2);">
        <h4 class="fw-bold text-uppercase m-0 text-white">KOPI <span style="color: var(--accent-gold)">NTT</span></h4>
        <small class="text-white-50">SISTEM INFORMASI PENJUALAN</small>
    </div>
    
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link active">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard Utama
            </a>
        </li>
        <li>
            <a href="{{ route('admin.orders.index') }}" class="nav-link d-flex justify-content-between align-items-center">
                <span><i class="bi bi-cart-fill me-2"></i> Pesanan Masuk</span>
                <span class="sidebar-badge" id="sidebarNotif">0</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.products.index') }}" class="nav-link">
                <i class="bi bi-cup-hot-fill text-warning"></i> Stok Produk Kopi
            </a>
        </li>

        <li>
            <a href="{{ route('admin.orders.index') }}?status=Dikirim" class="nav-link">
                <i class="bi bi-truck text-info"></i> Lacak Pengiriman
            </a>
        </li>

        @if(Auth::user()->role == 'pemilik')
        <hr class="mx-3 text-white-50">
        <p class="small text-white-50 px-4 mb-1">LAPORAN PEMILIK</p>
        <li>
            <a href="{{ route('admin.laporan.penjualan') }}" class="nav-link">
                <i class="bi bi-graph-up-arrow"></i> Penjualan Luwak
            </a>
        </li>
        <li>
            <a href="{{ route('admin.laporan.pelanggan') }}" class="nav-link">
                <i class="bi bi-people-fill"></i> Database Pelanggan
            </a>
        </li>
        @endif
    </ul>
    
    <div class="mt-auto border-top border-secondary">
        <a href="{{ route('home') }}" class="nav-link text-warning fw-bold">
            <i class="bi bi-house-door"></i> Lihat Toko Depan
        </a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout fw-bold mb-3">
                <i class="bi bi-power"></i> Keluar Sistem
            </button>
        </form>
    </div>
</div>

<div class="main-content">
    <div class="topbar d-flex justify-content-between align-items-center">
        <div>
            <h5 class="fw-bold m-0">Halo, {{ Auth::user()->name }}</h5>
            <small class="text-muted">Selamat datang di Panel Kendali Kopi NTT</small>
        </div>
        <div class="d-flex align-items-center gap-3">
            <div class="bg-light p-2 px-3 rounded-pill small border shadow-sm">
                <i class="bi bi-clock me-2 text-warning"></i> {{ date('l, d M Y') }}
            </div>
        </div>
    </div>

    <div class="content-body">
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card stat-card p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 p-3 rounded-4 me-3"><i class="bi bi-cart-check text-primary fs-3"></i></div>
                        <div>
                            <p class="text-muted small mb-0">Total Pesanan</p>
                            <h3 class="fw-bold mb-0">{{ $stats['total_masuk'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 p-3 rounded-4 me-3"><i class="bi bi-wallet2 text-success fs-3"></i></div>
                        <div>
                            <p class="text-muted small mb-0">Total Pendapatan</p>
                            <h3 class="fw-bold mb-0">Rp {{ number_format($stats['pendapatan'], 0, ',', '.') }}</h3>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card stat-card p-4">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 p-3 rounded-4 me-3"><i class="bi bi-clock-history text-warning fs-3"></i></div>
                        <div>
                            <p class="text-muted small mb-0">Menunggu Proses</p>
                            <h3 class="fw-bold mb-0">{{ $stats['perlu_proses'] }}</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold m-0"><i class="bi bi-lightning-charge me-2 text-warning"></i>Transaksi Terbaru</h5>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-dark rounded-pill px-3">Lihat Semua</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr class="text-muted small">
                            <th>ID PESANAN</th>
                            <th>PELANGGAN</th>
                            <th>TOTAL BAYAR</th>
                            <th>STATUS</th>
                            <th class="text-center">DETAIL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td class="fw-bold">#ORD-{{ $order->id }}</td>
                            <td>{{ $order->nama_penerima }}</td>
                            <td class="fw-bold text-dark">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge 
                                    @if($order->status == 'Selesai') bg-success 
                                    @elseif($order->status == 'Diproses') bg-warning text-dark
                                    @elseif($order->status == 'Dikirim') bg-info text-white
                                    @else bg-secondary @endif 
                                    rounded-pill px-3">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-light border shadow-sm">
                                    <i class="bi bi-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<audio id="notifSound"><source src="https://assets.mixkit.co/active_storage/sfx/2358/2358-preview.mp3" type="audio/mpeg"></audio>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function checkNewOrders() {
        fetch("{{ route('admin.check_orders') }}")
            .then(response => response.json())
            .then(data => {
                const sidebarBadge = document.getElementById('sidebarNotif');
                if (data.new_order) {
                    sidebarBadge.innerText = "!";
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
</script>
</body>
</html>