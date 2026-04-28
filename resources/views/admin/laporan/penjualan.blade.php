<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan | Kopi NTT</title>
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

        .nav-link { color: rgba(255,255,255,0.6); padding: 12px 20px; border-radius: 12px; margin-bottom: 5px; display: flex; align-items: center; text-decoration: none; transition: 0.3s; }
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
    </style>
</head>
<body>

<div class="sidebar">
    <div class="sidebar-brand text-center mb-4">
        <h3 class="text-white fw-bold">KOPI <span style="color: var(--accent)">NTT</span></h3>
        <p class="small text-white-50 text-uppercase tracking-wider">
            {{ Auth::user()->role == 'pemilik' ? 'Owner Panel' : 'Admin Panel' }}
        </p>
    </div>

    <nav class="nav flex-column">
        @if(Auth::user()->role == 'admin')
            <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2 me-2"></i> Dashboard Admin</a>
            <a href="{{ route('admin.orders.index') }}" class="nav-link"><i class="bi bi-cart-check me-2"></i> Pesanan Pelanggan</a>
            <a href="{{ route('admin.products.index') }}" class="nav-link"><i class="bi bi-box-seam me-2"></i> Stok Kopi</a>
            <hr class="text-white-50">
            <p class="small text-white-50 px-3 mb-1">LAPORAN</p>
            <a href="{{ route('admin.laporan.penjualan') }}" class="nav-link active"><i class="bi bi-graph-up-arrow me-2"></i> Laporan Jual</a>
        @else
            <a href="{{ route('admin.laporan.penjualan') }}" class="nav-link active"><i class="bi bi-graph-up-arrow me-2"></i> Laporan Penjualan</a>
            <a href="{{ route('admin.laporan.produk') }}" class="nav-link"><i class="bi bi-box-seam me-2"></i> Laporan Produk</a>
            <a href="{{ route('admin.laporan.pelanggan') }}" class="nav-link"><i class="bi bi-people me-2"></i> Laporan Pelanggan</a>
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
    <header class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold">Laporan Penjualan</h2>
            <p class="text-muted small">Ringkasan transaksi selesai untuk periode yang dipilih.</p>
        </div>
        <a href="{{ route('admin.laporan.pdf', request()->query()) }}" class="btn btn-outline-danger rounded-pill px-4 fw-bold">
            <i class="bi bi-file-pdf me-2"></i>Ekspor PDF
        </a>
    </header>

    <div class="card p-4 border-0 shadow-sm mb-4" style="border-radius: 20px;">
        <form action="{{ route('admin.laporan.penjualan') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-5 border-end">
                <label class="form-label small fw-bold">FILTER MINGGUAN (RENTANG TANGGAL)</label>
                <div class="input-group input-group-sm">
                    <input type="date" name="tgl_mulai" class="form-control" value="{{ request('tgl_mulai') }}">
                    <span class="input-group-text">s/d</span>
                    <input type="date" name="tgl_selesai" class="form-control" value="{{ request('tgl_selesai') }}">
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold">FILTER BULANAN</label>
                <div class="d-flex gap-2">
                    <select name="bulan" class="form-select form-select-sm">
                        <option value="">-- Bulan --</option>
                        @foreach(['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $k => $v)
                            <option value="{{ $k }}" {{ request('bulan') == $k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                    <select name="tahun" class="form-select form-select-sm">
                        @for($i=date('Y'); $i>=2024; $i--)
                            <option value="{{ $i }}" {{ request('tahun', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-dark btn-sm w-100 fw-bold">TERAPKAN</button>
            </div>
        </form>
    </div>

    <div class="report-section">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>TANGGAL</th>
                    <th>ID ORDER</th>
                    <th>NAMA PENERIMA</th>
                    <th class="text-end">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td>{{ $order->created_at->format('d M Y') }}</td>
                    <td class="fw-bold">#{{ $order->id }}</td>
                    <td>{{ $order->nama_penerima }}</td>
                    <td class="text-end fw-bold text-success">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center py-4 text-muted">Tidak ada data untuk periode ini.</td>
                </tr>
                @endforelse
            </tbody>
            
            {{-- BAGIAN TFOOT UNTUK TOTAL KESELURUHAN --}}
            @if($orders->count() > 0)
            <tfoot class="table-light">
                <tr>
                    <td colspan="3" class="text-end fw-bold py-3" style="font-size: 1.1rem;">TOTAL KESELURUHAN :</td>
                    <td class="text-end fw-bold py-3 text-primary" style="font-size: 1.2rem;">
                        Rp {{ number_format($orders->sum('total_harga'), 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
            @endif
        </table>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>