<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pesanan | Admin Kopi NTT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { --coffee-dark: #3e2723; --sidebar-width: 260px; --accent: #d4a373; }
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .sidebar { width: var(--sidebar-width); height: 100vh; position: fixed; background: var(--coffee-dark); color: white; z-index: 1000; }
        .sidebar .nav-link { color: rgba(255,255,255,0.7); padding: 12px 25px; display: flex; align-items: center; gap: 10px; text-decoration: none; }
        .sidebar .nav-link.active { color: white; background: rgba(255,255,255,0.1); border-left: 4px solid #ffc107; }
        .main-content { margin-left: var(--sidebar-width); padding: 40px; }
        .order-card { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); background: white; }
        
        .status-badge { font-weight: 600; padding: 7px 16px; border-radius: 50px; font-size: 0.8rem; }
        .bg-bayar-berhasil { background-color: #d1e7dd; color: #0f5132; } 
        .bg-proses { background-color: #ffc107; color: #000; }
        .bg-kirim { background-color: #0dcaf0; color: white; }
        .bg-selesai { background-color: #198754; color: white; }
        .bg-waiting { background-color: #f8f9fa; color: #6c757d; border: 1px solid #dee2e6; }
        
        .btn-aksi { border: none; border-radius: 50px; padding: 8px 20px; font-size: 0.75rem; font-weight: 600; transition: 0.3s; box-shadow: 0 4px 6px rgba(0,0,0,0.1); color: white; }
        .btn-konfirmasi { background-color: #ffc107; color: black !important; }
        .btn-kirim { background-color: #3e2723; color: white !important; }
        .btn-aksi:hover { transform: translateY(-2px); opacity: 0.9; }
        
        .modal-content { border-radius: 25px; border: none; }
        .detail-label { font-size: 0.75rem; color: #6c757d; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; }
    </style>
</head>
<body>

<div class="sidebar d-flex flex-column p-3">
    <h4 class="text-center fw-bold mb-4 py-3 border-bottom text-uppercase text-white">KOPI NTT <span class="text-warning">ADMIN</span></h4>
    <ul class="nav nav-pills flex-column mb-auto">
        <li><a href="{{ route('admin.dashboard') }}" class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.orders.index') }}" class="nav-link {{ Route::is('admin.orders.*') ? 'active' : '' }}"><i class="bi bi-cart-check"></i> Pesanan Masuk</a></li>
        <li><a href="{{ route('admin.products.index') }}" class="nav-link {{ Route::is('admin.products.*') ? 'active' : '' }}"><i class="bi bi-cup-hot"></i> Produk Kopi</a></li>
        <li><a href="{{ route('admin.laporan.penjualan') }}" class="nav-link {{ Route::is('admin.laporan.*') ? 'active' : '' }}"><i class="bi bi-file-earmark-bar-graph"></i> Laporan</a></li>
    </ul>
    <div class="mt-auto p-3 text-center border-top border-secondary">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button class="btn btn-outline-danger w-100 rounded-pill btn-sm">Keluar</button>
        </form>
    </div>
</div>

<div class="main-content">
    <div class="order-card p-4 shadow-sm">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-0 text-dark">Manajemen Pesanan</h2>
                <p class="text-muted small mb-0">Validasi transaksi dan kelola pengiriman kopi ke tangan kurir.</p>
            </div>
            <div class="badge bg-dark text-white p-2 px-3 rounded-pill">Total: {{ $orders->total() }} Pesanan</div>
        </div>

        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">ID Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Total Bayar</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    @php 
                        $statusClean = trim(strtolower($order->status));
                        // Mengecek apakah status termasuk kategori 'Siap Dikonfirmasi'
                        $isPaid = in_array($statusClean, ['menunggu verifikasi', 'pembayaran berhasil', 'settlement', 'capture']);
                    @endphp
                    <tr data-bs-toggle="modal" data-bs-target="#modalDetail{{ $order->id }}" style="cursor: pointer;">
                        <td class="ps-3 fw-bold">#ORD-{{ $order->id }}</td>
                        <td>
                            <div class="fw-semibold text-capitalize">{{ $order->nama_penerima ?? $order->nama }}</div>
                            <small class="text-muted"><i class="bi bi-geo-alt me-1"></i> {{ Str::limit($order->alamat_pengiriman ?? $order->alamat, 30) }}</small>
                        </td>
                        <td class="text-success fw-bold">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                        <td>
                            @if($isPaid)
                                <span class="badge bg-bayar-berhasil status-badge"><i class="bi bi-patch-check-fill me-1"></i> Terbayar</span>
                            @elseif($statusClean == 'diproses')
                                <span class="badge bg-proses status-badge"><i class="bi bi-box-seam me-1"></i> Diproses</span>
                            @elseif($statusClean == 'dikirim')
                                <span class="badge bg-kirim status-badge"><i class="bi bi-truck me-1"></i> Dikirim</span>
                            @elseif($statusClean == 'selesai')
                                <span class="badge bg-selesai status-badge"><i class="bi bi-house-check me-1"></i> Selesai</span>
                            @else
                                <span class="badge bg-waiting status-badge text-capitalize">{{ $order->status }}</span>
                            @endif
                        </td>
                        <td class="text-center" onclick="event.stopPropagation()">
                            <div class="d-flex justify-content-center gap-2">
                                @if($isPaid)
                                    <form action="{{ route('admin.orders.konfirmasi', $order->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-aksi btn-konfirmasi shadow-sm">Konfirmasi</button>
                                    </form>
                                @elseif($statusClean == 'diproses')
                                    <form action="{{ route('admin.orders.kirim', $order->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn-aksi btn-kirim shadow-sm">Kirim Barang</button>
                                    </form>
                                @endif

                                <div class="dropdown">
                                    <button class="btn btn-outline-dark btn-sm dropdown-toggle rounded-pill px-3" data-bs-toggle="dropdown">Opsi</button>
                                    <ul class="dropdown-menu shadow border-0">
                                        <li><a class="dropdown-item py-2 fw-bold text-success" href="{{ route('admin.orders.update', [$order->id, 'selesai']) }}">Set Selesai</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item py-2 text-danger" href="{{ route('admin.orders.update', [$order->id, 'dibatalkan']) }}">Batalkan</a></li>
                                    </ul>
                                </div>
                                <button type="button" class="btn btn-info btn-sm rounded-pill text-white px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $order->id }}">Detail</button>
                            </div>
                        </td>
                    </tr>

                    <div class="modal fade" id="modalDetail{{ $order->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content p-3 shadow-lg">
                                <div class="modal-header border-0 pb-0">
                                    <h5 class="fw-bold">Detail Pesanan #{{ $order->id }}</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="row mb-3">
                                        <div class="col-6">
                                            <label class="detail-label">Penerima</label>
                                            <p class="fw-bold mb-0 text-dark">{{ $order->nama_penerima ?? $order->nama }}</p>
                                        </div>
                                        <div class="col-6">
                                            <label class="detail-label">Status Database</label>
                                            <p class="mb-0 fw-bold text-primary">{{ $order->status }}</p>
                                        </div>
                                    </div>
                                    <div class="mb-4 bg-light p-3 rounded-4 border-start border-4 border-warning">
                                        <label class="detail-label">Alamat Pengiriman</label>
                                        <p class="small mb-0 fw-semibold">{{ $order->alamat_pengiriman ?? $order->alamat }}</p>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center p-3 bg-dark text-white rounded-4 shadow-sm">
                                        <span class="small opacity-75">Total Bayar:</span>
                                        <h4 class="fw-bold mb-0 text-warning">Rp {{ number_format($order->total_harga) }}</h4>
                                    </div>
                                </div>
                                <div class="modal-footer border-0 pt-0">
                                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Tutup</button>
                                    @if($isPaid)
                                        <form action="{{ route('admin.orders.konfirmasi', $order->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm">Konfirmasi Pembayaran</button>
                                        </form>
                                    @elseif($statusClean == 'diproses')
                                        <form action="{{ route('admin.orders.kirim', $order->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm text-white">Kirim ke Kurir</button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @empty
                    <tr><td colspan="5" class="text-center py-5 text-muted">Belum ada pesanan yang masuk/terbayar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">
            {{ $orders->links() }}
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>