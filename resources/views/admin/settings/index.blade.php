<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Pembayaran | Kopi NTT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { --coffee-dark: #3e2723; --sidebar-width: 260px; --accent-gold: #ffc107; }
        body { font-family: 'Poppins', sans-serif; background-color: #f4f7f6; overflow-x: hidden; }
        
        .sidebar { width: var(--sidebar-width); height: 100vh; position: fixed; background: var(--coffee-dark); color: white; z-index: 1000; }
        .sidebar .nav-link { color: rgba(255,255,255,0.7); padding: 12px 25px; display: flex; align-items: center; gap: 12px; transition: 0.3s; text-decoration: none; }
        .sidebar .nav-link:hover, .sidebar .nav-link.active { color: white; background: rgba(255,255,255,0.1); border-left: 4px solid var(--accent-gold); }

        .main-content { margin-left: var(--sidebar-width); padding: 0; }
        .topbar { background: white; padding: 15px 40px; box-shadow: 0 2px 10px rgba(0,0,0,0.05); }
        .content-body { padding: 40px; }
        
        .card-setting { border: none; border-radius: 25px; box-shadow: 0 15px 35px rgba(0,0,0,0.05); background: white; }
        .qr-preview { max-height: 250px; border-radius: 15px; border: 2px dashed #ddd; padding: 10px; }
        
        .btn-logout { color: #ff6b6b; transition: 0.3s; border: none; background: transparent; padding: 12px 25px; display: flex; align-items: center; gap: 12px; width: 100%; text-align: left; }
    </style>
</head>
<body>

<div class="sidebar d-flex flex-column p-3">
    <div class="text-center py-4 border-bottom border-secondary mb-4">
        <h4 class="fw-bold text-uppercase m-0">KOPI <span class="text-warning">NTT</span></h4>
        <small class="text-secondary">Administrator Panel</small>
    </div>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                <i class="bi bi-grid-1x2-fill"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="{{ route('admin.orders.index') }}" class="nav-link">
                <i class="bi bi-cart-fill"></i> Pesanan Masuk
            </a>
        </li>
        <li>
            <a href="{{ route('admin.products.index') }}" class="nav-link">
                <i class="bi bi-cup-hot-fill"></i> Produk Kopi
            </a>
        </li>
        <li>
            <a href="{{ route('admin.settings') }}" class="nav-link active">
                <i class="bi bi-qr-code-scan"></i> Pengaturan QRIS
            </a>
        </li>
    </ul>
    
    <hr class="text-secondary">
    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn-logout fw-bold"><i class="bi bi-power"></i> Keluar</button>
    </form>
</div>

<div class="main-content">
    <div class="topbar d-flex justify-content-between align-items-center">
        <h5 class="fw-bold m-0 text-muted">Pengaturan Pembayaran</h5>
        <div class="bg-light p-2 px-3 rounded-pill small border">
            <i class="bi bi-shield-lock me-2 text-warning"></i> Sesi Aman
        </div>
    </div>

    <div class="content-body">
        @if(session('success'))
            <div class="alert alert-success rounded-4 mb-4 border-0 shadow-sm">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            </div>
        @endif

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card card-setting p-4 p-md-5">
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="text-center mb-5">
                            <label class="form-label d-block fw-bold text-muted small text-uppercase">QRIS Aktif Pelanggan</label>
                            @if($setting && $setting->foto_qris)
                                <img src="{{ asset('storage/' . $setting->foto_qris) }}" class="qr-preview mb-3 shadow-sm" alt="QRIS">
                            @else
                                <div class="p-5 bg-light rounded-4 mb-3 border text-muted small">
                                    <i class="bi bi-image fs-1 d-block mb-2"></i> Belum ada foto QRIS diupload
                                </div>
                            @endif
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Ganti Foto QRIS (Scan Bayar)</label>
                            <input type="file" name="foto_qris" class="form-control form-control-lg rounded-4 shadow-sm">
                            <div class="form-text small text-danger">*Kosongkan jika tidak ingin mengganti gambar</div>
                        </div>

                        <div class="row g-3 mb-5">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nama Bank / E-Wallet</label>
                                <input type="text" name="nama_bank" class="form-control rounded-3" value="{{ $setting->nama_bank ?? 'BCA' }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Nomor Rekening</label>
                                <input type="text" name="no_rekening" class="form-control rounded-3" value="{{ $setting->no_rekening ?? '1234567890' }}" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-warning w-100 py-3 fw-bold rounded-pill text-white shadow">
                            <i class="bi bi-cloud-check-fill me-2"></i> SIMPAN PERUBAHAN PEMBAYARAN
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>