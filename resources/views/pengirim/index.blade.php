<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Kurir | Kopi NTT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root { --sidebar-bg: #0f172a; } /* Warna Biru Gelap khusus Kurir */
        body { background-color: #f1f5f9; font-family: 'Poppins', sans-serif; }
        .sidebar { width: 260px; height: 100vh; position: fixed; background: var(--sidebar-bg); color: white; }
        .main-content { margin-left: 260px; padding: 40px; }
        .nav-link { color: rgba(255,255,255,0.7); padding: 15px 25px; }
        .nav-link.active { background: rgba(255,255,255,0.1); color: white; border-left: 4px solid #38bdf8; }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="p-4 text-center border-bottom border-secondary">
        <h5 class="fw-bold m-0 text-info">KOPI NTT <span class="text-white">KURIR</span></h5>
    </div>
    <div class="py-4">
        <a href="#" class="nav-link active"><i class="bi bi-truck me-2"></i> Tugas Antar</a>
        <form action="{{ route('logout') }}" method="POST" class="mt-4 px-4">
            @csrf
            <button class="btn btn-outline-danger w-100 btn-sm rounded-pill">Keluar</button>
        </form>
    </div>
</div>

<div class="main-content">
    <h3 class="fw-bold mb-4">Daftar Tugas Pengiriman</h3>
    
    <div class="row">
        @forelse($orders as $order)
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm rounded-4 p-4">
                <div class="d-flex justify-content-between mb-3">
                    <span class="badge bg-primary rounded-pill">#ORD-{{ $order->id }}</span>
                    <span class="text-muted small">{{ $order->created_at->format('d M Y') }}</span>
                </div>
                <h6 class="fw-bold mb-1">{{ $order->nama_penerima ?? $order->nama }}</h6>
                <p class="text-muted small mb-4"><i class="bi bi-geo-alt"></i> {{ $order->alamat_pengiriman ?? $order->alamat }}</p>
                
                <form action="{{ route('pengirim.konfirmasi', $order->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Unggah Bukti Foto Tiba:</label>
                        <input type="file" name="bukti_foto" class="form-control form-control-sm" required>
                    </div>
                    <button class="btn btn-success w-100 rounded-pill fw-bold">Konfirmasi Selesai</button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <p class="text-muted">Belum ada pesanan yang perlu diantar.</p>
        </div>
        @endforelse
    </div>
</div>

</body>
</html>