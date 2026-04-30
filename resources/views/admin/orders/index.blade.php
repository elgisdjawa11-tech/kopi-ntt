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
        :root { --coffee-dark: #3e2723; --sidebar-width: 260px; }
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        .sidebar { width: var(--sidebar-width); height: 100vh; position: fixed; background: var(--coffee-dark); color: white; z-index: 1000; }
        .sidebar .nav-link { color: rgba(255,255,255,0.7); padding: 12px 25px; text-decoration: none; display: block; }
        .sidebar .nav-link.active { color: white; background: rgba(255,255,255,0.1); border-left: 4px solid #ffc107; }
        .main-content { margin-left: var(--sidebar-width); padding: 40px; }
        .order-card { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); background: white; padding: 25px; }
        .status-badge { font-weight: 600; padding: 7px 16px; border-radius: 50px; font-size: 0.8rem; }
        .bg-bayar-berhasil { background-color: #d1e7dd; color: #0f5132; } 
    </style>
</head>
<body>

<div class="sidebar d-flex flex-column p-3">
    <h4 class="text-center fw-bold mb-4 py-3 border-bottom text-white">KOPI NTT <span class="text-warning">ADMIN</span></h4>
    <ul class="nav flex-column mb-auto">
        <li><a href="{{ route('admin.dashboard') }}" class="nav-link {{ Route::is('admin.dashboard') ? 'active' : '' }}"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.orders.index') }}" class="nav-link {{ Route::is('admin.orders.*') ? 'active' : '' }}"><i class="bi bi-cart-check"></i> Pesanan</a></li>
        <li><a href="{{ route('admin.products.index') }}" class="nav-link"><i class="bi bi-cup-hot"></i> Produk</a></li>
    </ul>
    <form action="{{ route('logout') }}" method="POST" class="p-3">@csrf<button class="btn btn-outline-danger w-100 btn-sm">Logout</button></form>
</div>

<div class="main-content">
    <div class="order-card">
        <h2 class="fw-bold mb-4">Manajemen Pesanan</h2>
        
        @if(session('success')) <div class="alert alert-success border-0 rounded-4">{{ session('success') }}</div> @endif
        @if(session('info')) <div class="alert alert-info border-0 rounded-4">{{ session('info') }}</div> @endif

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr class="table-light">
                        <th>ID Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    @php 
                        $status = strtolower($order->status);
                        $isPaid = in_array($status, ['menunggu verifikasi', 'pembayaran berhasil', 'settlement', 'capture']);
                    @endphp
                    <tr>
                        <td class="fw-bold">#ORD-{{ $order->id }}</td>
                        <td>{{ $order->nama_penerima ?? $order->nama }}</td>
                        <td class="text-success fw-bold">Rp {{ number_format($order->total_harga) }}</td>
                        <td>
                            @if($isPaid) <span class="badge bg-bayar-berhasil status-badge">Terbayar</span>
                            @else <span class="badge bg-secondary status-badge text-capitalize">{{ $order->status }}</span> @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                @if($status == 'pending')
                                    <a href="{{ route('admin.orders.cek-status', $order->id) }}" class="btn btn-primary btn-sm rounded-pill px-3">Cek Bayar</a>
                                @endif

                                @if($isPaid)
                                    <form action="{{ route('admin.orders.konfirmasi', $order->id) }}" method="POST">@csrf<button class="btn btn-warning btn-sm rounded-pill px-3">Konfirmasi</button></form>
                                @endif
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">Detail</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-5">Belum ada pesanan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $orders->links() }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>