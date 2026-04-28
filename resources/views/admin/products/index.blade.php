<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Produk | Admin Kopi NTT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --coffee-dark: #3e2723;
            --sidebar-width: 260px;
            --accent-gold: #ffc107;
        }
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            background: var(--coffee-dark);
            color: white;
            z-index: 1000;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.7);
            padding: 12px 25px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: 0.3s;
            text-decoration: none;
        }
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255,255,255,0.1);
            border-left: 4px solid var(--accent-gold);
        }
        
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 40px;
        }
        
        .product-card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            background: white;
        }

        .img-thumbnail-custom {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 12px;
        }
    </style>
</head>
<body>

<div class="sidebar d-flex flex-column p-3">
    <div class="text-center py-4 border-bottom border-secondary mb-4">
        <h4 class="fw-bold text-uppercase m-0 text-white">KOPI <span class="text-warning">NTT</span></h4>
        <small class="text-secondary text-uppercase" style="font-size: 0.65rem; letter-spacing: 1px;">Administrator Panel</small>
    </div>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="{{ route('admin.orders.index') }}" class="nav-link">
                <i class="bi bi-cart-check me-2"></i> Pesanan Masuk
            </a>
        </li>
        <li>
            <a href="{{ route('admin.products.index') }}" class="nav-link active">
                <i class="bi bi-cup-hot"></i> Produk Kopi
            </a>
        </li>
        <li>
            {{-- PERBAIKAN: Gunakan rute lengkap sesuai web.php --}}
            <a href="{{ route('admin.laporan.penjualan') }}" class="nav-link">
                <i class="bi bi-file-earmark-bar-graph"></i> Laporan
            </a>
        </li>
    </ul>
    <hr class="text-secondary">
    <form action="{{ route('logout') }}" method="POST" class="px-3">
        @csrf
        <button type="submit" class="btn btn-outline-danger w-100 rounded-pill btn-sm">
            <i class="bi bi-box-arrow-left"></i> Keluar
        </button>
    </form>
</div>

<div class="main-content">
    <div class="product-card p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0 text-dark">Manajemen Katalog Produk</h2>
                <p class="text-muted small">Kelola ketersediaan stok dan informasi detail kopi NTT.</p>
            </div>
            <a href="{{ route('admin.products.create') }}" class="btn btn-dark px-4 rounded-pill shadow-sm">
                <i class="bi bi-plus-lg me-2"></i> Tambah Produk
            </a>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4 rounded-4" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="py-3 border-0">Foto</th>
                        <th class="py-3 border-0">Nama Kopi</th>
                        <th class="py-3 border-0">Asal</th>
                        <th class="py-3 border-0">Harga</th>
                        <th class="py-3 border-0">Stok</th>
                        <th class="py-3 border-0 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $p)
                    <tr>
                        <td>
                            <img src="{{ asset('storage/'.$p->foto) }}" class="img-thumbnail-custom shadow-sm border" alt="{{ $p->nama_kopi }}" onerror="this.src='https://via.placeholder.com/60'">
                        </td>
                        <td class="fw-bold text-dark">{{ $p->nama_kopi }}</td>
                        <td><span class="badge bg-light text-dark border shadow-sm"><i class="bi bi-geo-alt me-1 text-danger"></i> {{ $p->daerah_asal }}</span></td>
                        <td class="text-dark fw-bold">Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $p->stok < 5 ? 'bg-danger' : 'bg-success bg-opacity-10 text-success' }} rounded-pill px-3">
                                {{ $p->stok }} Pack
                            </span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('admin.products.edit', $p->id) }}" class="btn btn-outline-warning btn-sm rounded-3">
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <form action="{{ route('admin.products.destroy', $p->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm rounded-3" onclick="return confirm('Hapus produk {{ $p->nama_kopi }}?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">Belum ada koleksi produk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>