<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Pesanan #{{ $order->id }} | Admin Kopi NTT</title>
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
        .detail-card { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); background: white; }
    </style>
</head>
<body>

<div class="sidebar d-flex flex-column p-3">
    <h4 class="text-center fw-bold mb-4 py-3 border-bottom text-white">KOPI NTT <span class="text-warning">ADMIN</span></h4>
    <ul class="nav flex-column mb-auto">
        <li><a href="{{ route('admin.dashboard') }}" class="nav-link"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
        <li><a href="{{ route('admin.orders.index') }}" class="nav-link active"><i class="bi bi-cart-check"></i> Pesanan</a></li>
    </ul>
</div>

<div class="main-content">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0">Detail Pesanan #{{ $order->id }}</h2>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
        </div>

        <div class="row">
            <!-- Informasi Pengiriman -->
            <div class="col-md-5">
                <div class="detail-card p-4 mb-4">
                    <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-geo-alt-fill text-danger me-2"></i>Informasi Pelanggan</h5>
                    <hr>
                    <div class="mb-3">
                        <small class="text-muted d-block">Nama Penerima:</small>
                        <span class="fw-bold fs-5 text-capitalize">{{ $order->nama_penerima ?? $order->nama }}</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Nomor WhatsApp:</small>
                        <span class="fw-bold text-success">{{ $order->nomor_hp ?? '-' }}</span>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted d-block">Alamat Lengkap:</small>
                        <p class="fw-semibold mb-0">{{ $order->alamat_pengiriman ?? $order->alamat }}</p>
                    </div>
                    <div class="mb-0">
                        <small class="text-muted d-block">Status Saat Ini:</small>
                        <span class="badge bg-primary px-3 py-2 rounded-pill text-capitalize">{{ $order->status }}</span>
                    </div>
                </div>
            </div>

            <!-- Daftar Produk -->
            <div class="col-md-7">
                <div class="detail-card p-4">
                    <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-box-seam-fill me-2" style="color: #6d4c41;"></i>Item Pesanan</h5>
                    <hr>
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Produk</th>
                                    <th class="text-center">Jumlah</th>
                                    <th class="text-end">Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        <span class="fw-bold">{{ $item->product->nama_kopi ?? 'Produk' }}</span><br>
                                        <small class="text-muted">Harga: Rp {{ number_format($item->harga_satuan) }}</small>
                                    </td>
                                    <td class="text-center">{{ $item->jumlah }} Pcs</td>
                                    <td class="text-end fw-bold">Rp {{ number_format($item->jumlah * $item->harga_satuan) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="2" class="text-end py-3">Total Pembayaran:</th>
                                    <th class="text-end py-3 text-primary fs-5">Rp {{ number_format($order->total_harga) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <!-- Tombol Aksi sesuai alur sidang -->
                    @if(in_array(strtolower($order->status), ['pembayaran berhasil', 'settlement', 'capture']))
                        <div class="mt-4">
                            <form action="{{ route('admin.orders.konfirmasi', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-warning w-100 py-2 rounded-pill fw-bold">
                                    <i class="bi bi-check-circle me-2"></i>Konfirmasi & Siapkan Barang
                                </button>
                            </form>
                        </div>
                    @elseif(strtolower($order->status) == 'diproses')
                        <div class="mt-4">
                            <form action="{{ route('admin.orders.kirim', $order->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-dark w-100 py-2 rounded-pill fw-bold">
                                    <i class="bi bi-truck me-2"></i>Kirim Ke Kurir
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>