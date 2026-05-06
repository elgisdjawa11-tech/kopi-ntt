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
            --coffee-dark: #1a392a;
            --emerald-mid: #2d5a43;
            --gold-accent: #c5a059;
            --coffee-light: #fdfaf7;
            --accent: #c5a059;
        }

        body { 
            font-family: 'Poppins', sans-serif; 
            background-color: var(--coffee-light); 
            color: var(--coffee-dark); 
        }

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
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 10px; font-weight: bold; color: white; transition: 0.4s;
            border: 4px solid #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }
        .step.active .step-icon { 
            background: var(--coffee-dark); 
            transform: scale(1.1);
            box-shadow: 0 0 20px rgba(26, 57, 42, 0.2);
            border-color: var(--gold-accent);
        }
        .step.active span { font-weight: 600; color: var(--coffee-dark); }
        
        .checkout-card {
            border: none; border-radius: 35px;
            box-shadow: 0 25px 70px rgba(26, 57, 42, 0.07);
            background: #ffffff; overflow: hidden;
            border-top: 8px solid var(--gold-accent);
        }

        .product-summary {
            background: linear-gradient(135deg, #fdfaf7 0%, #ffffff 100%);
            padding: 40px; border-radius: 30px;
            border: 1px solid rgba(197, 160, 89, 0.15);
        }

        .form-label { font-size: 0.9rem; margin-bottom: 8px; color: var(--emerald-mid); font-weight: 600; }

        .form-control, .form-select {
            border-radius: 15px; padding: 15px 20px;
            border: 1px solid #eee; background-color: #fcfcfc; transition: 0.3s;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--gold-accent); background-color: #fff;
            box-shadow: 0 10px 25px rgba(197, 160, 89, 0.12);
        }

        .btn-checkout {
            background: var(--coffee-dark); color: white; border-radius: 18px;
            padding: 20px; font-weight: 700; letter-spacing: 1px; transition: 0.4s;
            border: 1px solid var(--gold-accent); margin-top: 25px;
        }

        .btn-checkout:hover {
            background: var(--gold-accent); transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(197, 160, 89, 0.3); color: var(--coffee-dark);
        }

        .section-title {
            font-family: 'Playfair Display', serif; font-size: 2.2rem;
            color: var(--coffee-dark); margin-bottom: 35px; line-height: 1.2;
        }

        .input-group-text {
            border-radius: 15px 0 0 15px; background-color: #f8f9fa;
            border: 1px solid #eee; color: var(--emerald-mid); font-weight: 600;
        }
    </style>
</head>
<body>

<div class="container my-5 py-5">
    <div class="d-flex justify-content-between align-items-center mb-4 px-lg-5">
        <a href="{{ route('home') }}" class="text-decoration-none fw-bold" style="color: var(--emerald-mid);">
            <i class="bi bi-arrow-left me-2"></i> Kembali Belanja
        </a>
        <div class="fw-bold fs-3" style="font-family: 'Playfair Display', serif;">KOPI <span style="color: var(--accent)">NTT</span></div>
    </div>

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
                        <h3 class="section-title mb-1">Detail Pengiriman <br>& Tujuan</h3>
                        <div class="mb-4">
                            <span class="badge rounded-pill px-3 py-2" style="background-color: rgba(197, 160, 89, 0.1); color: var(--emerald-mid); border: 1px solid var(--gold-accent);">
                                <i class="bi bi-info-circle-fill me-2"></i>Pengiriman Khusus Wilayah NTT
                            </span>
                        </div>
                        
                        @if ($errors->any() || session('error'))
                            <div class="alert alert-danger rounded-4 shadow-sm mb-4">
                                <h6 class="fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>Terjadi Kendala:</h6>
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                    @if(session('error'))
                                        <li>{{ session('error') }}</li>
                                    @endif
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('checkout.process') }}" method="POST">
                            @csrf 
                            
                            @if(!session('cart') || count(session('cart')) == 0)
                                @if(isset($product))
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                @endif
                            @endif

                            <div class="mb-4">
                                <label class="form-label">Nama Lengkap Penerima</label>
                                <input type="text" name="nama" class="form-control" value="{{ old('nama') }}" placeholder="Contoh: Eulogius Jawa" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Kabupaten/Kota Tujuan (Khusus NTT)</label>
                                <select name="kota_tujuan" id="kota_tujuan" class="form-select" required>
                                    <option value="" data-ongkir="0">-- Pilih Kabupaten/Kota --</option>
                                    <optgroup label="Zona 1 (Dekat - Ngada)">
                                        <option value="Kab. Ngada" data-ongkir="10000">Kab. Ngada</option>
                                        <option value="Kab. Nagekeo" data-ongkir="15000">Kab. Nagekeo</option>
                                    </optgroup>
                                    <optgroup label="Zona 2 (Daratan Flores)">
                                        <option value="Kab. Ende" data-ongkir="25000">Kab. Ende</option>
                                        <option value="Kab. Manggarai" data-ongkir="25000">Kab. Manggarai</option>
                                        <option value="Kab. Manggarai Barat" data-ongkir="30000">Kab. Manggarai Barat (Labuan Bajo)</option>
                                        <option value="Kab. Manggarai Timur" data-ongkir="25000">Kab. Manggarai Timur</option>
                                        <option value="Kab. Sikka" data-ongkir="30000">Kab. Sikka (Maumere)</option>
                                        <option value="Kab. Flores Timur" data-ongkir="35000">Kab. Flores Timur (Larantuka)</option>
                                    </optgroup>
                                    <optgroup label="Zona 3 (Timor & Sekitarnya)">
                                        <option value="Kota Kupang" data-ongkir="40000">Kota Kupang</option>
                                        <option value="Kab. Kupang" data-ongkir="45000">Kab. Kupang</option>
                                        <option value="Kab. Timor Tengah Selatan" data-ongkir="45000">Kab. Timor Tengah Selatan</option>
                                        <option value="Kab. Timor Tengah Utara" data-ongkir="45000">Kab. Timor Tengah Utara</option>
                                        <option value="Kab. Belu" data-ongkir="50000">Kab. Belu (Atambua)</option>
                                        <option value="Kab. Malaka" data-ongkir="50000">Kab. Malaka</option>
                                        <option value="Kab. Alor" data-ongkir="45000">Kab. Alor</option>
                                        <option value="Kab. Lembata" data-ongkir="40000">Kab. Lembata</option>
                                    </optgroup>
                                    <optgroup label="Zona 4 (Sumba & Lainnya)">
                                        <option value="Kab. Sumba Timur" data-ongkir="55000">Kab. Sumba Timur</option>
                                        <option value="Kab. Sumba Barat" data-ongkir="55000">Kab. Sumba Barat</option>
                                        <option value="Kab. Sumba Tengah" data-ongkir="55000">Kab. Sumba Tengah</option>
                                        <option value="Kab. Sumba Barat Daya" data-ongkir="55000">Kab. Sumba Barat Daya</option>
                                        <option value="Kab. Rote Ndao" data-ongkir="60000">Kab. Rote Ndao</option>
                                        <option value="Kab. Sabu Raijua" data-ongkir="60000">Kab. Sabu Raijua</option>
                                    </optgroup>
                                    <option value="LUAR NTT" data-ongkir="0" data-outside="true">-- DI LUAR WILAYAH NTT --</option>
                                </select>
                            </div>

                            <!-- Info Box Email Admin (Muncul jika Luar NTT dipilih) -->
                            <div id="outside_ntt_info" class="alert alert-info rounded-4 border-0 shadow-sm mb-4 d-none">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-info-circle-fill fs-4 me-3 text-primary"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1">Layanan Luar Wilayah NTT</h6>
                                        <p class="small mb-0 text-dark">Mohon maaf, sistem otomatis kami saat ini hanya melayani wilayah NTT. Silakan hubungi Admin melalui email: <strong class="text-primary">admin@kopi-ntt.test</strong> untuk bantuan pengiriman manual.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Alamat Lengkap Pengiriman</label>
                                <textarea name="alamat" class="form-control" rows="3" placeholder="Nama jalan, RT/RW, Desa/Kelurahan, dan Kecamatan" required>{{ old('alamat') }}</textarea>
                            </div>

                            <input type="hidden" name="ongkir" id="input_ongkir" value="0">

                            <div class="mb-4">
                                <label class="form-label">Nomor WhatsApp Aktif</label>
                                <div class="input-group">
                                    <span class="input-group-text">+62</span>
                                    <input type="text" name="hp" class="form-control" value="{{ old('hp') }}" placeholder="812xxxxxx" required>
                                </div>
                            </div>

                            <button type="submit" id="btn_submit" class="btn btn-checkout w-100 shadow-lg text-uppercase">
                                Lanjutkan ke Pembayaran <i class="bi bi-credit-card-2-front ms-2"></i>
                            </button>
                        </form>
                    </div>

                    <div class="col-md-5">
                        <div class="product-summary h-100 shadow-sm">
                            <h5 class="fw-bold mb-4"><i class="bi bi-bag-check me-2"></i>Ringkasan Belanja</h5>
                            
                            <div class="order-items-list" style="max-height: 350px; overflow-y: auto;">
                                @php $total = 0 @endphp

                                @if(session('cart') && count(session('cart')) > 0)
                                    @foreach(session('cart') as $id => $details)
                                        @php $total += $details['price'] * $details['quantity'] @endphp
                                        <div class="d-flex align-items-center mb-4 bg-white p-2 rounded-4 shadow-sm border border-light">
                                            <img src="{{ asset('storage/'.$details['photo']) }}" class="rounded-3" style="width: 70px; height: 70px; object-fit: cover;">
                                            <div class="ms-3">
                                                <h6 class="mb-0 fw-bold">{{ $details['name'] }}</h6>
                                                <p class="text-muted small mb-0">{{ $details['quantity'] }} pcs x Rp {{ number_format($details['price'], 0, ',', '.') }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                @elseif(isset($product))
                                    @php $total = $product->harga @endphp
                                    <div class="d-flex align-items-center mb-4 bg-white p-2 rounded-4 shadow-sm border border-light">
                                        <img src="{{ asset('storage/'.$product->foto) }}" class="rounded-3" style="width: 70px; height: 70px; object-fit: cover;">
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
                                <span class="fw-bold text-primary" id="display_ongkir">Rp 0</span>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top border-2">
                                <h5 class="fw-bold mb-0">Total Tagihan</h5>
                                <h4 class="fw-bold text-dark mb-0" id="display_total">Rp {{ number_format($total, 0, ',', '.') }}</h4>
                            </div>

                            <div class="mt-5 p-3 text-center rounded-4" style="background: rgba(197, 160, 89, 0.08); border: 1px dashed var(--accent);">
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
<script>
    const selectKota = document.getElementById('kota_tujuan');
    const inputOngkir = document.getElementById('input_ongkir');
    const displayOngkir = document.getElementById('display_ongkir');
    const displayTotal = document.getElementById('display_total');
    const infoBox = document.getElementById('outside_ntt_info');
    const submitBtn = document.getElementById('btn_submit');
    const subtotalProduk = {{ $total }};

    selectKota.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const isOutside = selectedOption.getAttribute('data-outside') === 'true';
        const ongkir = parseInt(selectedOption.getAttribute('data-ongkir')) || 0;
        const totalTagihan = subtotalProduk + ongkir;

        // Update Hidden Input & Tampilan Harga
        inputOngkir.value = ongkir;
        displayOngkir.innerText = 'Rp ' + ongkir.toLocaleString('id-ID');
        displayTotal.innerText = 'Rp ' + totalTagihan.toLocaleString('id-ID');

        // Logika Antisipasi Luar NTT
        if (isOutside) {
            infoBox.classList.remove('d-none');
            submitBtn.classList.add('d-none');
        } else {
            infoBox.classList.add('d-none');
            submitBtn.classList.remove('d-none');
        }
    });
</script>
</body>
</html>