<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | Kopi NTT Premium</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --coffee-dark: #3e2723;
            --coffee-brown: #795548;
            --coffee-light: #fdfaf7;
            --accent: #d4a373;
        }

        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: var(--coffee-light); 
            color: var(--coffee-dark); 
        }

        /* STEPPER SYNC DENGAN LOGIKA 3 TAHAP */
        .stepper {
            display: flex;
            justify-content: center;
            margin-bottom: 50px;
            position: relative;
        }
        .step {
            text-align: center;
            position: relative;
            width: 150px;
            z-index: 2;
        }
        .step-icon {
            width: 45px;
            height: 45px;
            border-radius: 50%;
            background: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            font-weight: bold;
            color: white;
            transition: 0.4s;
            border: 4px solid #fff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .step.active .step-icon { 
            background: var(--coffee-dark); 
            transform: scale(1.1);
            box-shadow: 0 0 20px rgba(62, 39, 35, 0.2);
        }
        .step.active span { font-weight: 600; color: var(--coffee-dark); }
        
        .checkout-card {
            border: none;
            border-radius: 35px;
            box-shadow: 0 25px 70px rgba(62, 39, 35, 0.07);
            background: #ffffff;
            overflow: hidden;
        }

        .product-summary {
            background: linear-gradient(135deg, #fdfaf7 0%, #ffffff 100%);
            padding: 40px;
            border-radius: 30px;
            border: 1px solid rgba(212, 163, 115, 0.15);
        }

        .form-label { font-size: 0.9rem; margin-bottom: 8px; color: var(--coffee-brown); font-weight: 600; }

        .form-control {
            border-radius: 15px;
            padding: 15px 20px;
            border: 1px solid #eee;
            background-color: #fcfcfc;
            transition: 0.3s;
        }

        .form-control:focus {
            border-color: var(--accent);
            background-color: #fff;
            box-shadow: 0 10px 25px rgba(212, 163, 115, 0.12);
        }

        .btn-checkout {
            background: var(--coffee-dark);
            color: white;
            border-radius: 18px;
            padding: 20px;
            font-weight: 700;
            letter-spacing: 1px;
            transition: 0.4s;
            border: none;
            margin-top: 25px;
        }

        .btn-checkout:hover {
            background: var(--accent);
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(212, 163, 115, 0.3);
            color: var(--coffee-dark);
        }

        .section-title {
            font-family: 'Playfair Display', serif;
            font-size: 2.2rem;
            color: var(--coffee-dark);
            margin-bottom: 35px;
            line-height: 1.2;
        }

        .input-group-text {
            border-radius: 15px 0 0 15px;
            background-color: #f8f9fa;
            border: 1px solid #eee;
            color: var(--coffee-brown);
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="container my-5 py-5">
    <div class="d-flex justify-content-between align-items-center mb-4 px-lg-5">
        <a href="{{ route('home') }}" class="text-decoration-none fw-bold" style="color: var(--coffee-brown);">
            <i class="bi bi-arrow-left me-2"></i> Kembali Belanja
        </a>
        <div class="fw-bold fs-3" style="font-family: 'Playfair Display', serif;">KOPI <span style="color: var(--accent)">NTT</span></div>
    </div>

    {{-- STEPPER: Sinkron dengan sistem 3 tahap kita --}}
    <div class="stepper">
        <div class="step active">
            <div class="step-icon"><i class="bi bi-geo-alt-fill"></i></div>
            <span class="small">Alamat</span>
        </div>
        <div class="step">
            <div class="step-icon">2</div>
            <span class="small">Bayar</span>
        </div>
        <div class="step">
            <div class="step-icon">3</div>
            <span class="small">Dikirim</span>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-11">
            <div class="checkout-card p-4 p-lg-5">
                <div class="row g-5">
                    
                    <div class="col-md-7">
                        <h3 class="section-title">Detail Pengiriman <br>& Tujuan</h3>
                        
                        <form action="{{ route('checkout.process') }}" method="POST">
                            @csrf 
                            
                            @if(!session('cart') || count(session('cart')) == 0)
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                            @endif

                            <div class="mb-4">
                                <label class="form-label">Nama Lengkap Penerima</label>
                                <input type="text" name="nama" class="form-control" placeholder="Contoh: Eulogius Jawa" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Alamat Lengkap Pengiriman</label>
                                <textarea name="alamat" class="form-control" rows="4" placeholder="Nama jalan, RT/RW, Kecamatan, dan Kota/Kabupaten" required></textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Nomor WhatsApp Aktif</label>
                                <div class="input-group">
                                    <span class="input-group-text">+62</span>
                                    <input type="text" name="hp" class="form-control" placeholder="812xxxxxx" required>
                                </div>
                                <small class="text-muted mt-2 d-block">*Nomor ini digunakan kurir untuk koordinasi pengiriman paket.</small>
                            </div>

                            <button type="submit" class="btn btn-checkout w-100 shadow-lg text-uppercase">
                                Lanjutkan ke Pembayaran <i class="bi bi-credit-card-2-front ms-2"></i>
                            </button>
                        </form>
                    </div>

                    <div class="col-md-5">
                        <div class="product-summary h-100 shadow-sm">
                            <h5 class="fw-bold mb-4"><i class="bi bi-bag-check me-2"></i>Ringkasan Belanja</h5>
                            
                            <div class="order-items-list" style="max-height: 350px; overflow-y: auto; padding-right: 5px;">
                                @php $total = 0 @endphp

                                @if(session('cart') && count(session('cart')) > 0)
                                    @foreach(session('cart') as $id => $details)
                                        @php $total += $details['price'] * $details['quantity'] @endphp
                                        <div class="d-flex align-items-center mb-4 bg-white p-2 rounded-4 shadow-sm border border-light">
                                            <img src="{{ asset('storage/'.$details['photo']) }}" class="rounded-3" style="width: 70px; height: 70px; object-fit: cover;" onerror="this.src='https://via.placeholder.com/70'">
                                            <div class="ms-3">
                                                <h6 class="mb-0 fw-bold">{{ $details['name'] }}</h6>
                                                <p class="text-muted small mb-0">{{ $details['quantity'] }} pcs x Rp {{ number_format($details['price'], 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    @php $total = $product->harga @endphp
                                    <div class="d-flex align-items-center mb-4 bg-white p-2 rounded-4 shadow-sm border border-light">
                                        <img src="{{ asset('storage/'.$product->foto) }}" class="rounded-3" style="width: 70px; height: 70px; object-fit: cover;" onerror="this.src='https://via.placeholder.com/70'">
                                        <div class="ms-3">
                                            <h6 class="mb-0 fw-bold">{{ $product->nama_kopi }}</h6>
                                            <p class="text-muted small mb-0">1 pcs x Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            
                            <hr class="my-4 opacity-50">
                            
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal Produk</span>
                                <span class="fw-bold">Rp {{ number_format($total, 0, ',', '.') }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-4">
                                <span class="text-muted">Ongkos Kirim</span>
                                <span class="text-success fw-bold">Gratis Ongkir</span>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top border-2">
                                <h5 class="fw-bold mb-0">Total Tagihan</h5>
                                <h4 class="fw-bold text-dark mb-0">Rp {{ number_format($total, 0, ',', '.') }}</h4>
                            </div>

                            <div class="mt-5 p-3 text-center rounded-4" style="background: rgba(212, 163, 115, 0.08); border: 1px dashed var(--accent);">
                                <i class="bi bi-shield-lock-fill text-success fs-5"></i>
                                <p class="mb-0 small mt-1 fw-semibold text-muted text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">Pembayaran Aman & Terenkripsi</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>