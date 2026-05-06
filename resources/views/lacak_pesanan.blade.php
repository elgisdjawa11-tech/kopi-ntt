<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lacak Pesanan | Kopi NTT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --coffee-dark: #3e2723;
            --accent: #d4a373;
            --bg-light: #fdfaf7;
        }
        body { font-family: 'Poppins', sans-serif; background-color: var(--bg-light); color: var(--coffee-dark); }
        
        .tracking-card { border: none; border-radius: 30px; box-shadow: 0 15px 40px rgba(62, 39, 35, 0.05); background: white; }
        
        .timeline { position: relative; list-style: none; padding: 20px 0; }
        
        /* Garis Dasar Timeline (Abu-abu) */
        .timeline:before {
            content: ''; position: absolute; top: 0; bottom: 0; left: 40px;
            width: 3px; background: #eee;
            border-radius: 10px;
        }

        /* Garis Dinamis yang Berubah Warna (Emas) */
        .timeline-line-active {
            content: ''; position: absolute; top: 0; left: 40px;
            width: 3px; background: var(--accent);
            z-index: 1;
            transition: 0.8s ease; /* Transisi halus saat garis naik */
        }

        .timeline-item { position: relative; margin-bottom: 50px; padding-left: 80px; z-index: 2; }
        
        .timeline-icon {
            position: absolute; left: 21px; width: 40px; height: 40px;
            background: #f8f9fa; border: 2px solid #eee; border-radius: 50%;
            display: flex; align-items: center; justify-content: center; z-index: 10;
            transition: 0.4s;
            color: #adb5bd;
        }

        .timeline-content h6 { margin-bottom: 5px; color: #adb5bd; transition: 0.4s; text-transform: capitalize; }
        .timeline-content p { font-size: 0.85rem; color: #adb5bd; }

        /* Status Aktif (Warna Emas menyala) */
        .timeline-item.active .timeline-icon { 
            background: var(--accent); 
            border-color: var(--accent); 
            color: white; 
            box-shadow: 0 0 15px rgba(212, 163, 115, 0.4);
        }
        .timeline-item.active .timeline-content h6 { color: var(--coffee-dark); font-weight: 600; }
        .timeline-item.active .timeline-content p { color: #6c757d; }

        .btn-beranda {
            background-color: var(--coffee-dark);
            color: white;
            border-radius: 50px;
            padding: 12px;
            font-weight: 600;
            transition: 0.3s;
            border: none;
        }
        .btn-beranda:hover { background-color: var(--accent); color: white; transform: translateY(-3px); }

        @media (max-width: 768px) {
            .timeline-item { padding-left: 60px; }
            .timeline:before, .timeline-line-active { left: 30px; }
            .timeline-icon { left: 11px; }
            .judul-status { font-size: 1.5rem; }
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="tracking-card p-4 p-md-5">
                <div class="text-center mb-5">
                    <h2 class="fw-bold judul-status" style="letter-spacing: -1px;">Status Pesanan Anda</h2>
                    <div class="badge bg-light text-dark border px-3 py-2 rounded-pill mt-2">ID Pesanan: #ORD-{{ $order->id }}</div>
                </div>

                <div class="timeline">
                    {{-- LOGIKA TINGGI GARIS EMAS --}}
                    <div class="timeline-line-active" style="height: 
                        @if($order->status == 'selesai') 100% 
                        @elseif($order->status == 'dikirim') 60% 
                        @elseif($order->status == 'diproses') 15%
                        @else 0% @endif;">
                    </div>

                    {{-- TAHAP 1: PESANAN DIPROSES --}}
                    {{-- Aktif saat: Pembayaran Berhasil ATAU Diproses ATAU Dikirim ATAU Selesai --}}
                    <div class="timeline-item {{ in_array($order->status, ['pembayaran berhasil', 'diproses', 'dikirim', 'selesai']) ? 'active' : '' }}">
                        <div class="timeline-icon">
                            <i class="bi bi-box-seam-fill"></i>
                        </div>
                        <div class="timeline-content">
                            <h6>Pesanan Diproses</h6>
                            <p class="mb-0">
                                @if($order->status == 'pembayaran berhasil')
                                    Pembayaran tervalidasi! Pesanan masuk antrean pengemasan.
                                @else
                                    Tim kami sedang menyiapkan dan mengemas biji kopi pilihanmu dengan teliti.
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- TAHAP 2: PESANAN DIKIRIM --}}
                    {{-- Aktif saat: Dikirim ATAU Selesai --}}
                    <div class="timeline-item {{ in_array($order->status, ['dikirim', 'selesai']) ? 'active' : '' }}">
                        <div class="timeline-icon">
                            <i class="bi bi-truck"></i>
                        </div>
                        <div class="timeline-content">
                            <h6>Pesanan Dikirim</h6>
                            <p class="mb-0">Paket sudah diserahkan ke kurir dan sedang dalam perjalanan menuju lokasimu.</p>
                        </div>
                    </div>

                    {{-- TAHAP 3: PESANAN TIBA --}}
                    {{-- Aktif saat: Selesai --}}
                    <div class="timeline-item {{ $order->status == 'selesai' ? 'active' : '' }}">
                        <div class="timeline-icon">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div class="timeline-content">
                            <h6>Pesanan Tiba</h6>
                            <p class="mb-0">Pesanan telah sampai di tujuan! Nikmati aroma khas kopi Nusa Tenggara Timur. Terima kasih!</p>
                        </div>
                    </div>
                </div>

                <div class="mt-5 d-grid">
                    <a href="{{ route('home') }}" class="btn btn-beranda shadow-lg">
                        <i class="bi bi-house-door me-2"></i> Kembali ke Beranda
                    </a>
                </div>
            </div>
            
            <p class="text-center mt-4 text-muted small">
                Butuh bantuan? Hubungi WhatsApp Admin kami di <strong>+62 812-xxxx-xxxx</strong>
            </p>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>