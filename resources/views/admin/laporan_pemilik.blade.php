<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Strategis Pemilik | Kopi NTT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --coffee-dark: #3e2723;
            --coffee-brown: #5d4037;
            --accent: #d4a373;
            --bg-light: #fdfaf7;
            --sidebar-width: 280px;
        }

        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: var(--bg-light); 
            color: var(--coffee-dark); 
            overflow-x: hidden;
        }

        .heading-font { font-family: 'Playfair Display', serif; }

        /* SIDEBAR STYLING */
        .sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background: var(--coffee-dark);
            color: white;
            padding: 30px 20px;
            z-index: 1000;
            box-shadow: 4px 0 15px rgba(0,0,0,0.1);
        }

        .sidebar-brand {
            padding: 0 15px 30px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            margin-bottom: 30px;
        }

        .nav-link {
            color: rgba(255,255,255,0.6);
            padding: 12px 20px;
            border-radius: 12px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            transition: 0.3s;
            text-decoration: none;
        }

        .nav-link i { font-size: 1.2rem; margin-right: 15px; }

        .nav-link:hover, .nav-link.active {
            background: var(--accent);
            color: var(--coffee-dark);
            font-weight: 600;
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 40px;
            min-height: 100vh;
        }

        .report-section {
            background: white;
            border-radius: 25px;
            padding: 30px;
            margin-bottom: 40px;
            box-shadow: 0 10px 30px rgba(62, 39, 35, 0.05);
            border: 1px solid rgba(212, 163, 115, 0.1);
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--bg-light);
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-title i { color: var(--accent); }

        .stat-box {
            background: var(--bg-light);
            padding: 20px;
            border-radius: 20px;
            border: 1px solid rgba(212, 163, 115, 0.2);
            height: 100%;
        }

        .table thead th {
            background: var(--coffee-dark);
            color: white;
            padding: 15px;
            border: none;
        }

        .btn-logout {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: #ff8a80;
            margin-top: auto;
        }

        /* Form Filter Styling */
        .filter-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid rgba(212, 163, 115, 0.2);
        }
    </style>
</head>
<body>

<div class="sidebar d-flex flex-column">
    <div class="sidebar-brand text-center">
        <h3 class="heading-font fw-bold m-0 text-white">KOPI <span style="color: var(--accent)">NTT</span></h3>
        <p class="small text-white-50 mt-1 mb-0">Owner Control Panel</p>
    </div>

    <nav class="nav flex-column">
        {{-- UPDATE: Menambahkan kata "Laporan" di depan menu sidebar --}}
        <a href="#penjualan" class="nav-link active"><i class="bi bi-graph-up-arrow"></i> Laporan Penjualan</a>
        <a href="#produk" class="nav-link"><i class="bi bi-box-seam"></i> Laporan Produk</a>
        <a href="#pelanggan" class="nav-link"><i class="bi bi-people"></i> Laporan Pelanggan</a>
        <hr class="text-white-50">
        <a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard Admin</a>
        <a href="{{ route('home') }}" class="nav-link"><i class="bi bi-shop"></i> Lihat Toko</a>
    </nav>

    <form action="{{ route('logout') }}" method="POST" class="mt-auto p-2">
        @csrf
        <button type="submit" class="btn btn-logout w-100 text-start d-flex align-items-center p-3 rounded-3">
            <i class="bi bi-box-arrow-left me-2"></i> Keluar Sistem
        </button>
    </form>
</div>

<div class="main-content">
    <header class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <h2 class="heading-font fw-bold">Evaluasi Kinerja Bisnis</h2>
            <p class="text-muted small">Data real-time untuk pengambilan keputusan strategis.</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.laporan.pdf', request()->query()) }}" class="btn btn-danger rounded-pill px-4 shadow-sm fw-bold">
                <i class="bi bi-file-earmark-pdf-fill me-2"></i> Ekspor Laporan (PDF)
            </a>
        </div>
    </header>

    {{-- FORM FILTER PERIODE --}}
    <div class="filter-card shadow-sm">
        <form action="{{ route('admin.laporan') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-5 border-end">
                <label class="form-label small fw-bold text-muted text-uppercase">Filter Mingguan / Custom</label>
                <div class="input-group input-group-sm">
                    <input type="date" name="tgl_mulai" class="form-control" value="{{ request('tgl_mulai') }}">
                    <span class="input-group-text bg-light">s/d</span>
                    <input type="date" name="tgl_selesai" class="form-control" value="{{ request('tgl_selesai') }}">
                </div>
            </div>

            <div class="col-md-4">
                <label class="form-label small fw-bold text-muted text-uppercase">Filter Per Bulan</label>
                <div class="d-flex gap-2">
                    <select name="bulan" class="form-select form-select-sm">
                        <option value="">-- Pilih Bulan --</option>
                        @php
                            $months = [
                                '01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April',
                                '05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus',
                                '09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'
                            ];
                        @endphp
                        @foreach($months as $key => $name)
                            <option value="{{ $key }}" {{ request('bulan') == $key ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    <select name="tahun" class="form-select form-select-sm">
                        @for($i = date('Y'); $i >= 2024; $i--)
                            <option value="{{ $i }}" {{ request('tahun', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            <div class="col-md-3">
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-dark btn-sm w-100 fw-bold rounded-3">
                        <i class="bi bi-filter me-1"></i> Terapkan
                    </button>
                    <a href="{{ route('admin.laporan') }}" class="btn btn-outline-secondary btn-sm rounded-3" title="Reset">
                        <i class="bi bi-arrow-clockwise"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- SECTION PENJUALAN --}}
    <div id="penjualan" class="report-section">
        {{-- UPDATE: Judul section menggunakan kata "Laporan" --}}
        <h5 class="section-title"><i class="bi bi-cash-stack"></i> Laporan Penjualan (Selesai)</h5>
       
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="stat-box">
                    <small class="text-muted d-block mb-1">Total Pendapatan</small>
                    <h3 class="fw-bold mb-0 text-success">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-box">
                    <small class="text-muted d-block mb-1">Volume Penjualan</small>
                    <h3 class="fw-bold mb-0">{{ $jumlahSelesai }} Transaksi</h3>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-box">
                    <small class="text-muted d-block mb-1">Rata-rata Transaksi</small>
                    <h3 class="fw-bold mb-0">Rp {{ number_format($rataRata, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead>
                    <tr>
                        <th>TANGGAL</th>
                        <th>ID ORDER</th>
                        <th>NAMA PELANGGAN</th>
                        <th>TOTAL HARGA</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td class="small">{{ $order->created_at->format('d/m/Y') }}</td>
                        <td class="fw-bold">#ORD-{{ $order->id }}</td>
                        <td>{{ $order->nama_penerima }}</td>
                        <td class="fw-bold text-success">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                        <td><span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3">{{ $order->status }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-4">Tidak ada data untuk periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- SECTION PRODUK --}}
    <div id="produk" class="report-section">
        {{-- UPDATE: Judul section menggunakan kata "Laporan" --}}
        <h5 class="section-title"><i class="bi bi-box-seam"></i> Laporan Produk & Stok</h5>
        <div class="row g-4 mb-4 text-center">
            <div class="col-md-4">
                <div class="p-3 border rounded-4">
                    <h2 class="fw-bold m-0">{{ $kopis->count() }}</h2>
                    <small class="text-muted">Varian Produk</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 border rounded-4">
                    <h2 class="fw-bold m-0 text-danger">{{ $kopis->where('stok', '<=', 5)->count() }}</h2>
                    <small class="text-muted">Stok Kritis (<=5)</small>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 border rounded-4 bg-light">
                    <h2 class="fw-bold m-0 text-success">{{ $kopis->sum('stok') }}</h2>
                    <small class="text-muted">Total Stok Tersedia</small>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>NAMA PRODUK</th>
                        <th>HARGA</th>
                        <th>STOK</th>
                        <th>STATUS</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kopis as $kopi)
                    <tr>
                        <td class="fw-bold">{{ $kopi->nama_kopi }}</td>
                        <td>Rp {{ number_format($kopi->harga, 0, ',', '.') }}</td>
                        <td>{{ $kopi->stok }}</td>
                        <td>
                            @if($kopi->stok <= 5)
                                <span class="badge bg-danger">Restock Segera</span>
                            @else
                                <span class="badge bg-success">Aman</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- SECTION PELANGGAN --}}
    <div id="pelanggan" class="report-section">
        {{-- UPDATE: Judul section menggunakan kata "Laporan" --}}
        <h5 class="section-title"><i class="bi bi-people"></i> Laporan Database Pelanggan Aktif</h5>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>NAMA</th>
                        <th>EMAIL</th>
                        <th>BERGABUNG PADA</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pelanggan as $user)
                    <tr>
                        <td class="fw-bold">{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->created_at->format('d M Y') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center">Belum ada pelanggan terdaftar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>