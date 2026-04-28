<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pelanggan | Kopi NTT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root { 
            --coffee-dark: #3e2723; 
            --admin-dark: #212529; 
            --accent: #d4a373; 
            --bg-light: #fdfaf7; 
            --sidebar-width: 280px; 
        }
        body { font-family: 'Poppins', sans-serif; background-color: var(--bg-light); color: var(--coffee-dark); }
        
        /* Sidebar Styling */
        .sidebar { 
            width: var(--sidebar-width); 
            height: 100vh; 
            position: fixed; 
            padding: 30px 20px; 
            z-index: 1000; 
            color: white;
            background: {{ Auth::user()->role == 'pemilik' ? 'var(--coffee-dark)' : 'var(--admin-dark)' }};
            display: flex;
            flex-direction: column;
        }
        .nav-link { color: rgba(255,255,255,0.6); padding: 12px 20px; border-radius: 12px; text-decoration: none; display: flex; align-items: center; margin-bottom: 5px; transition: 0.3s; }
        .nav-link.active { background: var(--accent); color: var(--coffee-dark); font-weight: 600; }
        .nav-link:hover { color: white; background: rgba(255,255,255,0.1); }
        
        .main-content { margin-left: var(--sidebar-width); padding: 40px; }
        .report-section { background: white; border-radius: 25px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }

        .logout-container { margin-top: auto; padding-bottom: 20px; }
        .btn-exit { 
            background-color: #e63946; 
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
        }
        .btn-exit:hover { background-color: #c1121f; transform: translateY(-2px); color: white; }
        
        /* Text styling untuk nomor agar mudah di-copy */
        .phone-text { font-family: 'Courier New', Courier, monospace; font-weight: 600; color: var(--coffee-dark); letter-spacing: 0.5px; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-brand text-center mb-4">
        <h3 class="text-white fw-bold">KOPI <span style="color: var(--accent)">NTT</span></h3>
        <p class="small text-white-50">{{ Auth::user()->role == 'pemilik' ? 'Owner Panel' : 'Admin Panel' }}</p>
    </div>

    <nav class="nav flex-column">
        @if(Auth::user()->role == 'admin')
            <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2 me-2"></i> Dashboard Admin</a>
            <a href="{{ route('admin.orders.index') }}" class="nav-link"><i class="bi bi-cart-check me-2"></i> Pesanan Pelanggan</a>
            <a href="{{ route('admin.products.index') }}" class="nav-link"><i class="bi bi-box-seam me-2"></i> Stok Kopi</a>
            <hr class="text-white-50">
            <p class="small text-white-50 px-3 mb-1">LAPORAN</p>
            <a href="{{ route('admin.laporan.penjualan') }}" class="nav-link"><i class="bi bi-graph-up-arrow me-2"></i> Laporan Jual</a>
            <a href="{{ route('admin.laporan.pelanggan') }}" class="nav-link active"><i class="bi bi-people me-2"></i> Laporan Pelanggan</a>
        @else
            <a href="{{ route('admin.laporan.penjualan') }}" class="nav-link"><i class="bi bi-graph-up-arrow me-2"></i> Laporan Penjualan</a>
            <a href="{{ route('admin.laporan.produk') }}" class="nav-link"><i class="bi bi-box-seam me-2"></i> Laporan Produk</a>
            <a href="{{ route('admin.laporan.pelanggan') }}" class="nav-link active"><i class="bi bi-people me-2"></i> Laporan Pelanggan</a>
        @endif
    </nav>

    <div class="logout-container">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-exit shadow-sm">
                <i class="bi bi-box-arrow-left me-2"></i> KELUAR SISTEM
            </button>
        </form>
    </div>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold m-0">Laporan Database Pelanggan</h2>
        <span class="badge bg-dark rounded-pill px-3 py-2">Total: {{ $pelanggan->count() }} Pelanggan</span>
    </div>

    <div class="report-section">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th style="border-radius: 15px 0 0 0;">NAMA PELANGGAN</th>
                        <th>USERNAME</th>
                        <th>KOTA / ALAMAT</th>
                        <th style="border-radius: 0 15px 0 0;">NOMOR WHATSAPP</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pelanggan as $user)
                    <tr>
                        <td class="fw-bold text-uppercase">{{ $user->name }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $user->username }}</span></td>
                        <td>{{ $user->city ?? '-' }}</td>
                        <td>
                            @if($user->phone)
                                {{-- Menampilkan nomor dalam bentuk teks biasa agar Admin mudah melakukan Copy-Paste --}}
                                <span class="phone-text">{{ $user->phone }}</span>
                            @else
                                <span class="text-muted small italic">Kosong</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>