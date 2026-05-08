<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pengirim Dashboard | Kopi NTT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { 
            --emerald-dark: #1a392a; 
            --emerald-mid: #2d5a43; 
            --gold-accent: #c5a059; 
            --bg-light: #f1f5f9; 
        }
        body { background-color: var(--bg-light); font-family: 'Poppins', sans-serif; color: var(--emerald-dark); }
        
        .sidebar { 
            width: 260px; 
            height: 100vh; 
            position: fixed; 
            background: var(--emerald-dark); 
            color: white; 
            border-right: 2px solid var(--gold-accent); 
            display: flex;
            flex-direction: column;
            z-index: 1000;
        }
        
        .main-content { margin-left: 260px; padding: 40px; }
        
        .nav-link { color: rgba(255,255,255,0.6); padding: 15px 25px; transition: 0.3s; text-decoration: none; display: flex; align-items: center; }
        .nav-link.active { background: rgba(255,255,255,0.1); color: var(--gold-accent); border-left: 4px solid var(--gold-accent); font-weight: 600; }
        .nav-link:hover { color: white; background: rgba(255,255,255,0.05); }
        
        .logout-container { margin-top: auto; padding: 20px; border-top: 1px solid rgba(255,255,255,0.1); }
        .btn-logout { background-color: transparent; border: 1px solid #ff4d4d; color: #ff4d4d; transition: 0.3s; width: 100%; border-radius: 50px; padding: 10px; font-weight: 600; }
        .btn-logout:hover { background-color: #ff4d4d; color: white; transform: translateY(-2px); }

        .stat-card { border-radius: 20px; border: none; border-left: 5px solid var(--gold-accent); transition: 0.3s; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        
        .task-card { border-radius: 20px; border: none; border-left: 5px solid var(--emerald-mid); }
        .btn-konfirmasi { background-color: var(--emerald-dark); border-color: var(--gold-accent); color: white; transition: 0.3s; border-radius: 50px; font-weight: 600; }
        .btn-konfirmasi:hover { background-color: var(--gold-accent); color: var(--emerald-dark); }
    </style>
</head>
<body>

<div class="sidebar">
    <div class="p-4 text-center border-bottom border-secondary">
        <h5 class="fw-bold m-0 text-white">KOPI <span style="color: var(--gold-accent)">NTT</span></h5>
        <small class="text-white-50 text-uppercase tracking-widest" style="font-size: 0.6rem;">Pengirim Dashboard</small>
    </div>
    
    <div class="py-4">
        <a href="{{ route('pengirim.index') }}" class="nav-link {{ Route::is('pengirim.index') ? 'active' : '' }}">
            <i class="bi bi-grid-1x2-fill me-2"></i> Dashboard Kerja
        </a>
        <a href="{{ route('pengirim.history') }}" class="nav-link {{ Route::is('pengirim.history') ? 'active' : '' }}">
            <i class="bi bi-clock-history me-2"></i> Riwayat Selesai
        </a>
    </div>

    <div class="logout-container">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn-logout">
                <i class="bi bi-power me-2"></i> KELUAR
            </button>
        </form>
    </div>
</div>

<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold m-0">Ringkasan Pengiriman</h3>
        <span class="badge bg-white text-dark border px-3 py-2 rounded-pill shadow-sm small">
            <i class="bi bi-person-circle me-1 text-success"></i> {{ Auth::user()->name }}
        </span>
    </div>

    <!-- Statistik Kurir -->
    <div class="row mb-5">
        <div class="col-md-4">
            <div class="card stat-card p-4 shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 p-3 rounded-4 me-3"><i class="bi bi-box-seam text-primary fs-3"></i></div>
                    <div>
                        <p class="text-muted small mb-0">Tugas Aktif</p>
                        <h3 class="fw-bold mb-0 text-dark">{{ $stats['total_tugas'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card p-4 shadow-sm">
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 p-3 rounded-4 me-3"><i class="bi bi-check-all text-success fs-3"></i></div>
                    <div>
                        <p class="text-muted small mb-0">Total Selesai</p>
                        <h3 class="fw-bold mb-0 text-dark">{{ $stats['tugas_selesai'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <h5 class="fw-bold mb-3"><i class="bi bi-list-ul me-2"></i>Daftar Tugas Antar</h5>
    
    <div class="row">
        @forelse($orders as $order)
        <div class="col-md-6 mb-4">
            <div class="card task-card border-0 shadow-sm p-4">
                <div class="d-flex justify-content-between mb-3">
                    <span class="badge bg-primary rounded-pill">#ORD-{{ $order->id }}</span>
                    <span class="text-muted small">{{ $order->created_at->format('d M Y') }}</span>
                </div>
                <h6 class="fw-bold mb-1 text-dark">{{ $order->nama_penerima ?? ($order->user->name ?? 'Pelanggan') }}</h6>
                <p class="text-muted small mb-4"><i class="bi bi-geo-alt"></i> {{ $order->alamat_pengiriman ?? ($order->user->alamat ?? '-') }}</p>
                
                <form action="{{ route('pengirim.konfirmasi', $order->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Unggah Bukti Foto Tiba:</label>
                        <input type="file" name="bukti_foto" class="form-control form-control-sm border-emerald" required accept="image/*">
                    </div>
                    <button type="submit" class="btn btn-konfirmasi w-100 py-2 shadow-sm">Konfirmasi Selesai</button>
                </form>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <div class="mb-3"><i class="bi bi-emoji-smile fs-1 text-muted"></i></div>
            <p class="text-muted">Luar biasa! Tidak ada tugas pengiriman tersisa hari ini.</p>
        </div>
        @endforelse
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>