<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Produk | Kopi NTT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { --coffee-dark: #3e2723; --accent: #d4a373; --bg-light: #fdfaf7; --sidebar-width: 280px; }
        body { font-family: 'Poppins', sans-serif; background-color: var(--bg-light); }
        .sidebar { width: var(--sidebar-width); height: 100vh; position: fixed; background: var(--coffee-dark); color: white; padding: 30px 20px; }
        .nav-link { color: rgba(255,255,255,0.6); padding: 12px 20px; border-radius: 12px; text-decoration: none; display: flex; align-items: center; margin-bottom: 10px; }
        .nav-link.active { background: var(--accent); color: var(--coffee-dark); font-weight: 600; }
        .main-content { margin-left: var(--sidebar-width); padding: 40px; }
        .report-section { background: white; border-radius: 25px; padding: 30px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
    </style>
</head>
<body>
<div class="sidebar d-flex flex-column">
    <div class="sidebar-brand text-center mb-4 text-white"><h3>KOPI NTT</h3></div>
    <nav class="nav flex-column">
        <a href="{{ route('admin.laporan.penjualan') }}" class="nav-link"><i class="bi bi-graph-up-arrow me-2"></i> Laporan Penjualan</a>
        <a href="{{ route('admin.laporan.produk') }}" class="nav-link active"><i class="bi bi-box-seam me-2"></i> Laporan Produk</a>
        <a href="{{ route('admin.laporan.pelanggan') }}" class="nav-link"><i class="bi bi-people me-2"></i> Laporan Pelanggan</a>
    </nav>
</div>
<div class="main-content">
    <h2 class="fw-bold mb-4">Laporan Produk & Stok</h2>
    <div class="report-section">
        <table class="table align-middle">
            <thead class="table-light">
                <tr>
                    <th>NAMA PRODUK</th>
                    <th>STOK</th>
                    <th>STATUS</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kopis as $kopi)
                <tr>
                    <td class="fw-bold">{{ $kopi->nama_kopi }}</td>
                    <td>{{ $kopi->stok }} Unit</td>
                    <td>
                        @if($kopi->stok <= 5)
                            <span class="badge bg-danger">Restock Segera</span>
                        @else
                            <span class="badge bg-success">Stok Aman</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
</body>
</html>