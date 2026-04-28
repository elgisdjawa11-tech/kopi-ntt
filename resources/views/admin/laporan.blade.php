<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan | Kopi NTT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { --coffee-dark: #3e2723; --sidebar-width: 260px; }
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; }
        
        /* Sidebar Style */
        .sidebar { width: var(--sidebar-width); height: 100vh; position: fixed; background: var(--coffee-dark); color: white; z-index: 1000; }
        .sidebar .nav-link { color: rgba(255,255,255,0.7); padding: 12px 25px; display: flex; align-items: center; gap: 10px; }
        .sidebar .nav-link.active { color: white; background: rgba(255,255,255,0.1); border-left: 4px solid #ffc107; }

        .sidebar-badge { font-size: 0.7rem; background: #dc3545; color: white; border-radius: 50px; padding: 2px 8px; font-weight: bold; display: none; }
        
        .main-content { margin-left: var(--sidebar-width); padding: 40px; }
        .report-card { border: none; border-radius: 15px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); background: white; }
        .stat-card { border-radius: 15px; border: none; transition: 0.3s; }

        @media print {
            .no-print, .sidebar, .btn, footer, .filter-section { display: none !important; }
            .main-content { margin-left: 0 !important; padding: 0 !important; }
            @page { size: A4; margin: 1.5cm; }
            body { background-color: white !important; font-size: 11pt; }
            .report-card { box-shadow: none !important; border: 1px solid #ddd !important; }
            .table th { background-color: #333 !important; color: white !important; -webkit-print-color-adjust: exact; }
            .table td, .table th { border: 1px solid #dee2e6 !important; }
        }
    </style>
</head>
<body>

<div class="sidebar d-flex flex-column p-3 no-print">
    <h4 class="text-center fw-bold mb-4 py-3 border-bottom text-uppercase">
        KOPI NTT <span class="text-warning">ADMIN</span>
    </h4>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="{{ route('admin.dashboard') }}" class="nav-link">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="{{ route('admin.orders.index') }}" class="nav-link d-flex justify-content-between align-items-center">
                <span><i class="bi bi-cart-check me-2"></i> Pesanan Masuk</span>
                <span class="sidebar-badge" id="sidebarNotif">0</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.products.index') }}" class="nav-link">
                <i class="bi bi-cup-hot"></i> Produk Kopi
            </a>
        </li>
        <li>
            <a href="{{ route('admin.laporan') }}" class="nav-link active">
                <i class="bi bi-file-earmark-bar-graph"></i> Laporan
            </a>
        </li>
    </ul>
    <hr>
    <a href="{{ route('home') }}" class="nav-link text-warning fw-bold">
        <i class="bi bi-box-arrow-left"></i> Lihat Web Utama
    </a>
</div>

<div class="main-content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4 no-print">
            <div>
                <h2 class="fw-bold text-dark mb-0">Laporan Penjualan</h2>
                <p class="text-muted">Data rekapitulasi transaksi untuk evaluasi bisnis Pemilik.</p>
            </div>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.laporan') }}" class="btn btn-outline-secondary px-4 shadow-sm">
                    <i class="bi bi-arrow-clockwise me-2"></i> Reset Filter
                </a>
                <button onclick="window.print()" class="btn btn-primary px-4 shadow-sm">
                    <i class="bi bi-printer me-2"></i> Cetak Laporan (PDF)
                </button>
            </div>
        </div>

        {{-- FORM FILTER - REVISI DOSEN --}}
        <div class="card report-card mb-4 no-print filter-section">
            <div class="card-body p-4">
                <form action="{{ route('admin.laporan') }}" method="GET" class="row g-3 align-items-end">
                    <div class="col-md-4 border-end">
                        <label class="form-label fw-bold small text-uppercase">Filter Mingguan / Tanggal</label>
                        <div class="input-group">
                            <input type="date" name="tgl_mulai" class="form-control" value="{{ request('tgl_mulai') }}">
                            <span class="input-group-text">s/d</span>
                            <input type="date" name="tgl_selesai" class="form-control" value="{{ request('tgl_selesai') }}">
                        </div>
                    </div>
                    <div class="col-md-3 border-end">
                        <label class="form-label fw-bold small text-uppercase">Filter Bulanan</label>
                        <select name="bulan" class="form-select">
                            <option value="">-- Pilih Bulan --</option>
                            @php
                                $nama_bulan = ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'];
                            @endphp
                            @foreach($nama_bulan as $key => $val)
                                <option value="{{ $key }}" {{ request('bulan') == $key ? 'selected' : '' }}>{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 border-end">
                        <label class="form-label fw-bold small text-uppercase">Tahun</label>
                        <select name="tahun" class="form-select">
                            @for($i = date('Y'); $i >= 2024; $i--)
                                <option value="{{ $i }}" {{ request('tahun', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-dark w-100 fw-bold">
                            <i class="bi bi-search me-2"></i> CARI DATA
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="d-none d-print-block text-center mb-4">
            <h1 class="fw-bold">LAPORAN PENJUALAN KOPI NTT</h1>
            <p>Alamat: Jl. Raya Kopi NTT, Kupang, Nusa Tenggara Timur</p>
            @if(request('tgl_mulai'))
                <p class="fw-bold">Periode: {{ request('tgl_mulai') }} s/d {{ request('tgl_selesai') }}</p>
            @elseif(request('bulan'))
                <p class="fw-bold">Bulan: {{ request('bulan') }} Tahun: {{ request('tahun') }}</p>
            @else
                <p class="fw-bold">Bulan Ini: {{ date('F Y') }}</p>
            @endif
            <hr>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-4 col-4">
                <div class="card stat-card bg-primary text-white p-4 h-100 shadow-sm">
                    <small class="opacity-75 text-uppercase fw-bold">Total Transaksi</small>
                    <h2 class="fw-bold mb-0 mt-2">{{ $orders->count() }} Pesanan</h2>
                </div>
            </div>
            <div class="col-md-4 col-4">
                <div class="card stat-card bg-success text-white p-4 h-100 shadow-sm">
                    <small class="opacity-75 text-uppercase fw-bold">Total Pendapatan</small>
                    <h2 class="fw-bold mb-0 mt-2">Rp {{ number_format($orders->sum('total_harga'), 0, ',', '.') }}</h2>
                </div>
            </div>
            <div class="col-md-4 col-4">
                <div class="card stat-card bg-info text-white p-4 h-100 shadow-sm">
                    <small class="opacity-75 text-uppercase fw-bold">Pesanan Selesai</small>
                    <h2 class="fw-bold mb-0 mt-2">{{ $orders->where('status', 'Selesai')->count() }} Item</h2>
                </div>
            </div>
        </div>

        <div class="card report-card p-4">
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Tgl Transaksi</th>
                            <th>ID Pesanan</th>
                            <th>Nama Pelanggan</th>
                            <th>Produk</th>
                            <th>Total Bayar</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->created_at->format('d M Y') }}</td>
                            <td>#ORD-{{ $order->id }}</td>
                            <td>{{ $order->nama_penerima }}</td>
                            <td>
                                @foreach($order->items as $item)
                                    • {{ $item->product->nama_kopi ?? 'Produk Dihapus' }} ({{ $item->jumlah }}x)<br>
                                @endforeach
                            </td>
                            <td class="fw-bold">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                            <td>
                                <span class="badge {{ $order->status == 'Selesai' ? 'bg-success' : 'bg-warning' }}">
                                    {{ $order->status }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">Tidak ada data transaksi pada periode ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="d-none d-print-block mt-5">
            <div class="row">
                <div class="col-8"></div>
                <div class="col-4 text-center">
                    <p>Kupang, {{ date('d F Y') }}</p>
                    <p class="mb-5">Mengetahui, Pemilik</p>
                    <br><br>
                    <p class="fw-bold">( ................................ )</p>
                </div>
            </div>
        </div>

        <footer class="text-center mt-5 small text-muted no-print">
            <p>Dicetak otomatis oleh Sistem Informasi Penjualan Kopi NTT pada {{ date('d/m/Y H:i') }}</p>
        </footer>
    </div>
</div>

<audio id="notifSound"><source src="https://assets.mixkit.co/active_storage/sfx/2358/2358-preview.mp3" type="audio/mpeg"></audio>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function checkNewOrders() {
        fetch("{{ route('admin.check_orders') }}")
            .then(response => response.json())
            .then(data => {
                const sidebarBadge = document.getElementById('sidebarNotif');
                if (data.new_order) {
                    sidebarBadge.innerText = "1";
                    sidebarBadge.style.display = "inline-block";
                    const sound = document.getElementById('notifSound');
                    sound.play().catch(e => console.log('Izin suara aktif.'));
                } else {
                    sidebarBadge.style.display = "none";
                }
            })
            .catch(error => console.error('Error fetching data:', error));
    }
    setInterval(checkNewOrders, 10000);
    checkNewOrders();
</script>
</body>
</html>