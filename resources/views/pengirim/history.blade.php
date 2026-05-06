<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pengiriman | Kopi NTT</title>
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

        .history-card { border-radius: 20px; border: none; border-left: 5px solid var(--gold-accent); }
        .badge-success { background-color: #d1e7dd; color: #0f5132; }
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
        <h3 class="fw-bold m-0">Riwayat Pengiriman</h3>
        <span class="badge bg-white text-dark border px-3 py-2 rounded-pill shadow-sm small">
            Total: {{ $orders->total() }} Paket Selesai
        </span>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle bg-white rounded-4 shadow-sm overflow-hidden">
            <thead class="bg-dark text-white">
                <tr>
                    <th class="p-3">ID Order</th>
                    <th>Nama Penerima</th>
                    <th>Alamat</th>
                    <th>Waktu Tiba</th>
                    <th class="text-center">Bukti Foto</th>
                    <th class="text-center">Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td class="fw-bold p-3">#ORD-{{ $order->id }}</td>
                    <td>{{ $order->nama_penerima ?? ($order->user->name ?? '-') }}</td>
                    <td><small class="text-muted">{{ $order->alamat_pengiriman ?? '-' }}</small></td>
                    <td>{{ $order->updated_at->format('d/m/Y H:i') }}</td>
                    <td class="text-center">
                        @if($order->foto_penerimaan)
                            <a href="{{ asset('storage/' . $order->foto_penerimaan) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                <i class="bi bi-image"></i> Lihat Foto
                            </a>
                        @else
                            <span class="text-muted small italic">No Photo</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="badge badge-success rounded-pill px-3">Selesai</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5">Belum ada riwayat pengiriman yang diselesaikan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $orders->links() }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>