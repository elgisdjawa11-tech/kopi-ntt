<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Pesanan Saya | Kopi NTT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: #fdfaf7; 
        }
        .navbar-coffee {
            background-color: #3e2723;
        }
        .card-order { 
            border-radius: 20px; 
            border: none; 
            transition: 0.3s; 
            background: white;
        }
        .card-order:hover { 
            transform: translateY(-5px); 
            box-shadow: 0 15px 30px rgba(0,0,0,0.08); 
        }
        .status-badge { 
            border-radius: 50px; 
            padding: 6px 16px; 
            font-size: 0.75rem; 
            font-weight: 600;
        }
        .btn-detail {
            background-color: #3e2723;
            color: white;
            border-radius: 12px;
            font-weight: 500;
            transition: 0.3s;
        }
        .btn-detail:hover {
            background-color: #5d4037;
            color: white;
        }
        .text-brown { color: #3e2723; }
    </style>
</head>
<body>

<nav class="navbar navbar-coffee navbar-dark mb-5 shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            <i class="bi bi-cup-hot me-2 text-warning"></i>KOPI NTT
        </a>
        <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Kembali Belanja
        </a>
    </div>
</nav>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="d-flex align-items-center mb-4">
                <div class="bg-white p-3 rounded-4 shadow-sm me-3">
                    <i class="bi bi-bag-check-fill fs-3 text-brown"></i>
                </div>
                <div>
                    <h2 class="fw-bold text-dark mb-0">Riwayat Pesanan</h2>
                    <p class="text-muted mb-0 small">Lihat daftar transaksi kopi NTT yang pernah kamu beli.</p>
                </div>
            </div>

            <div class="row">
                @forelse($orders as $order)
                <div class="col-md-6 mb-4">
                    <div class="card card-order shadow-sm p-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div>
                                <span class="text-muted small fw-bold text-uppercase">ID: #ORD-{{ $order->id }}</span>
                                <h4 class="fw-bold mt-1 text-success mb-0">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</h4>
                            </div>

                            {{-- Badge Status Dinamis --}}
                            @if($order->status == 'Menunggu Pembayaran')
                                <span class="badge bg-secondary status-badge"><i class="bi bi-clock me-1"></i> Menunggu Bayar</span>
                            @elseif($order->status == 'Diproses')
                                <span class="badge bg-warning text-dark status-badge"><i class="bi bi-gear-fill me-1"></i> Diproses</span>
                            @elseif($order->status == 'Dikirim')
                                <span class="badge bg-info text-white status-badge"><i class="bi bi-truck me-1"></i> Dikirim</span>
                            @else
                                <span class="badge bg-success status-badge text-white"><i class="bi bi-check-circle-fill me-1"></i> Selesai</span>
                            @endif
                        </div>
                        
                        <div class="mb-3">
                            <p class="small text-muted mb-0"><i class="bi bi-calendar3 me-2"></i> {{ $order->created_at->format('d F Y, H:i') }}</p>
                            <p class="small text-muted"><i class="bi bi-person me-2"></i> Penerima: {{ $order->nama_penerima }}</p>
                        </div>
                        
                        <div class="d-grid mt-2">
                            <a href="{{ route('pesanan.lacak', $order->id) }}" class="btn btn-detail py-2">
                                <i class="bi bi-geo-alt me-2"></i> Detail & Lacak Pesanan
                            </a>
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-12 text-center py-5">
                    <div class="bg-white d-inline-block p-5 rounded-circle shadow-sm mb-4">
                        <i class="bi bi-cart-x fs-1 text-muted"></i>
                    </div>
                    <h5 class="fw-bold text-muted">Belum ada riwayat belanja</h5>
                    <p class="text-muted small">Pesanan kamu akan muncul di sini setelah kamu melakukan checkout.</p>
                    <a href="{{ route('home') }}" class="btn btn-brown px-4 py-2 mt-2 rounded-pill text-white" style="background-color: #3e2723;">
                        Beli Kopi Sekarang
                    </a>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="text-center py-4 border-top">
    <p class="text-muted small">&copy; 2026 Kopi Nusa Tenggara Timur. All Rights Reserved.</p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>