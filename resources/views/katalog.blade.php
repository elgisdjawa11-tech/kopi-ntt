<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Informasi Penjualan Kopi Nusa Tenggara Timur Berbasis Web</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    
    <style>
        :root { 
            --emerald-dark: #1a392a; 
            --emerald-mid: #2d5a43; 
            --gold-accent: #c5a059; 
            --bg-light: #fdfaf7; 
            --coffee-dark: #1a392a; 
            --accent: #c5a059; 
        }
        body { font-family: 'Poppins', sans-serif; background-color: var(--bg-light); color: var(--emerald-dark); }
        .navbar { background-color: var(--emerald-dark) !important; padding: 0.8rem 0; border-bottom: 2px solid var(--gold-accent); }
        .navbar-brand { font-family: 'Playfair Display', serif; letter-spacing: 2px; }
        .nav-icon { font-size: 1.4rem; color: white; position: relative; transition: 0.3s; text-decoration: none; cursor: pointer; border: none; background: none; }
        .nav-icon:hover { color: var(--gold-accent); }
        .badge-notify { position: absolute; top: -5px; right: -8px; font-size: 0.6rem; background: var(--gold-accent); color: white; border-radius: 50%; padding: 2px 5px; font-weight: bold; }
        
        /* HERO SECTION */
        .hero { 
            background: linear-gradient(rgba(10, 30, 20, 0.85), rgba(10, 30, 20, 0.85)), url('https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?q=80&w=2000'); 
            background-size: cover; 
            background-position: center; 
            height: 550px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            color: white; 
            text-align: center; 
            padding: 0 20px;
            position: relative;
            overflow: hidden;
        }

        /* EFEK UAP KOPI (STEAM ANIMATION) */
        .steam-container {
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            z-index: 1;
            pointer-events: none;
        }
        .steam-container span {
            position: relative;
            bottom: -50px;
            display: block;
            margin: 0 15px;
            min-width: 2px;
            height: 300px;
            background: #fff;
            border-radius: 50%;
            animation: animate-steam 7s linear infinite;
            opacity: 0;
            filter: blur(25px);
            animation-delay: calc(var(--i) * -1.2s);
        }
        @keyframes animate-steam {
            0% { transform: translateY(0) scaleX(1); opacity: 0; }
            15% { opacity: 0.3; }
            50% { transform: translateY(-200px) scaleX(10); opacity: 0.2; }
            95% { opacity: 0; }
            100% { transform: translateY(-400px) scaleX(20); }
        }

        .judul-sedang { 
            font-family: 'Playfair Display', serif; 
            font-size: 2.5rem; 
            font-weight: 700;
            line-height: 1.2;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 1.5rem;
            text-shadow: 0 5px 15px rgba(0,0,0,0.5);
            z-index: 2;
            position: relative;
        }
        .judul-sedang span { color: var(--gold-accent); }

        @media (max-width: 992px) {
            .judul-sedang { font-size: 1.8rem; }
        }
        @media (max-width: 768px) {
            .judul-sedang { font-size: 1.4rem; letter-spacing: 0; }
        }
        
        .section-title { font-family: 'Playfair Display', serif; margin-bottom: 3rem; position: relative; }
        .section-title::after { content: ''; background: var(--gold-accent); height: 3px; width: 60px; position: absolute; bottom: -10px; left: 50%; transform: translateX(-50%); }
        
        .card { border: none; border-radius: 20px; overflow: hidden; transition: all 0.4s ease; box-shadow: 0 10px 30px rgba(0,0,0,0.05); background: #fff; border-bottom: 4px solid transparent; }
        .card:hover { transform: translateY(-15px); box-shadow: 0 15px 45px rgba(26, 57, 42, 0.1); border-bottom: 4px solid var(--gold-accent); }
        
        .badge-origin { background-color: var(--gold-accent); color: white; border-radius: 50px; padding: 5px 15px; font-size: 0.75rem; text-transform: uppercase; }
        .price { color: var(--emerald-mid); font-weight: 700; font-size: 1.3rem; }
        .btn-buy { background-color: var(--emerald-dark); color: white; border-radius: 50px; padding: 10px 30px; border: 1px solid var(--gold-accent); font-weight: 600; text-decoration: none; display: inline-block; transition: 0.3s; }
        .btn-buy:hover { background-color: var(--gold-accent); color: white; transform: scale(1.05); }
        
        footer { background: var(--emerald-dark); color: rgba(255,255,255,0.6); padding: 3rem 0; border-top: 4px solid var(--gold-accent); }
        .text-accent { color: var(--gold-accent) !important; }
        #toast-container { position: fixed; bottom: 20px; right: 20px; z-index: 9999; }

        /* Custom Modal Gambar */
        .modal-image-preview { background: rgba(0,0,0,0.85); backdrop-filter: blur(5px); }
        .modal-image-preview .modal-content { background: transparent; border: none; }
        .modal-image-preview .btn-close { filter: invert(1); opacity: 1; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold fs-3" href="{{ route('home') }}">KOPI <span style="color: var(--accent)">NTT</span></a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link active" href="{{ route('home') }}">Katalog Produk</a></li>
                @auth
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="lacakDropdown" role="button" data-bs-toggle="dropdown">Lacak Pesanan</a>
                    <ul class="dropdown-menu p-3" style="width: 250px;">
                        <li>
                            <form onsubmit="event.preventDefault(); let id = document.getElementById('order_id').value; if(id) { window.location.href='/pesanan/lacak/' + id; }">
                                <div class="mb-2">
                                    <label class="small fw-bold mb-1 text-dark">Masukkan ID Pesanan:</label>
                                    <input type="number" id="order_id" class="form-control form-control-sm" placeholder="Contoh: 1" required>
                                </div>
                                <button type="submit" class="btn btn-sm btn-dark w-100 rounded-pill">Cek Status</button>
                            </form>
                        </li>
                    </ul>
                </li>
                @endauth
            </ul>
            <div class="d-flex align-items-center gap-4">
                <a href="{{ route('cart.index') }}" class="nav-icon">
                    <i class="bi bi-cart3"></i>
                    <span class="badge-notify" id="cart-count">{{ count((array) session('cart')) }}</span>
                </a>
                @auth
                    <div class="dropdown">
                        <button class="btn btn-outline-light btn-sm px-4 rounded-pill dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end mt-2">
                            <li><a class="dropdown-item small" href="{{ route('riwayat.pesanan') }}"><i class="bi bi-clock-history me-2"></i>Riwayat Pesanan</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item small text-danger fw-bold"><i class="bi bi-box-arrow-right me-2"></i>Keluar</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-outline-light btn-sm px-4 rounded-pill">Masuk</a>
                    <a href="{{ route('register') }}" class="btn btn-outline-light btn-sm px-4 rounded-pill ms-2">Registrasi</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<header class="hero">
    <div class="steam-container">
        <span style="--i:1;"></span>
        <span style="--i:3;"></span>
        <span style="--i:16;"></span>
        <span style="--i:5;"></span>
        <span style="--i:13;"></span>
        <span style="--i:20;"></span>
        <span style="--i:6;"></span>
        <span style="--i:7;"></span>
        <span style="--i:10;"></span>
        <span style="--i:8;"></span>
    </div>
    <div class="container text-center">
        <h1 class="judul-sedang">
            Sistem Informasi Penjualan Kopi <span>Nusa Tenggara Timur</span> Berbasis Web
        </h1>
        <p class="lead mb-4 mx-auto opacity-75" style="max-width: 900px;">Menyediakan akses digital untuk menikmati keajaiban cita rasa kopi terbaik langsung dari tanah Flobamora ke genggaman Anda.</p>
        <a href="#katalog" class="btn btn-buy shadow-lg btn-lg px-5">Mulai Belanja</a>
    </div>
</header>

<div class="container my-5 py-5" id="katalog">
    <div class="text-center">
        <h2 class="section-title">Katalog Produk Kopi NTT</h2>
    </div>
    
    <div class="row g-4 mt-2">
        @forelse($kopis as $kopi)
        <div class="col-md-4">
            <div class="card h-100">
                @php
                    $imagePath = filter_var($kopi->foto, FILTER_VALIDATE_URL) ? $kopi->foto : asset('storage/' . $kopi->foto);
                @endphp
                <img src="{{ $imagePath }}" 
                     class="card-img-top img-preview-trigger" 
                     alt="{{ $kopi->nama_kopi }}"
                     data-bs-toggle="modal" 
                     data-bs-target="#imagePreviewModal"
                     data-name="{{ $kopi->nama_kopi }}"
                     data-origin="{{ $kopi->daerah_asal }}">
                
                <div class="card-body p-4 d-flex flex-column">
                    <span class="badge-origin mb-2 d-inline-block">{{ $kopi->daerah_asal }}</span>
                    <h4 class="fw-bold mb-1">{{ $kopi->nama_kopi }}</h4>
                    
                    <div class="stock-info mb-3 {{ $kopi->stok <= 5 ? 'stock-low' : 'text-muted' }}">
                        <i class="bi bi-box-seam me-1"></i>
                        @if($kopi->stok > 0)
                            Sisa Kuota: {{ $kopi->stok }} pcs
                        @else
                            <span class="text-danger fw-bold">Kuota Habis</span>
                        @endif
                    </div>

                    <p class="text-muted mb-4 small">{{ Str::limit($kopi->deskripsi, 80) }}</p>
                    
                    <div class="d-flex justify-content-between align-items-center mt-auto">
                        <span class="price">Rp {{ number_format($kopi->harga, 0, ',', '.') }}</span>
                        
                        <div class="d-flex gap-2">
                            @if($kopi->stok > 0)
                                <button class="btn btn-outline-dark rounded-circle btn-add-to-cart" 
                                        data-id="{{ $kopi->id }}" 
                                        title="Tambah ke Keranjang">
                                    <i class="bi bi-plus-lg"></i>
                                </button>
                                <a href="{{ route('checkout.pengiriman', $kopi->id) }}" class="btn btn-buy">Beli</a>
                            @else
                                <button class="btn btn-secondary rounded-pill disabled" disabled>Sold Out</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5"><p>Kopi belum tersedia.</p></div>
        @endforelse
    </div>
</div>

<footer class="text-center">
    <div class="container">
        <p>&copy; {{ date('Y') }} SI Penjualan Kopi NTT - Elgis Jawa</p>
    </div>
</footer>

<!-- Modal Preview Gambar (Shopee/TikTok Style) -->
<div class="modal fade modal-image-preview" id="imagePreviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <div class="text-white">
                    <h5 class="modal-title fw-bold" id="modalProductName">Nama Produk</h5>
                    <small class="text-accent" id="modalProductOrigin">Daerah Asal</small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <img src="" id="modalImageDisplay" class="img-fluid rounded-4 shadow-lg" style="max-height: 80vh;">
            </div>
        </div>
    </div>
</div>

<div id="toast-container"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
// Logic untuk Image Preview Modal
document.querySelectorAll('.img-preview-trigger').forEach(img => {
    img.addEventListener('click', function() {
        const name = this.getAttribute('data-name');
        const origin = this.getAttribute('data-origin');
        const src = this.getAttribute('src');

        document.getElementById('modalProductName').innerText = name;
        document.getElementById('modalProductOrigin').innerText = origin;
        document.getElementById('modalImageDisplay').src = src;
    });
});

document.querySelectorAll('.btn-add-to-cart').forEach(button => {
    button.addEventListener('click', function() {
        const productId = this.getAttribute('data-id');
        const cartBadge = document.getElementById('cart-count');
        const originalContent = this.innerHTML;
        
        this.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
        this.disabled = true;

        fetch(`/keranjang/tambah/${productId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        })
        .then(async response => {
            const data = await response.json();
            if (response.status === 422) {
                showToast(data.message, "danger");
                return;
            }
            if (response.ok) {
                cartBadge.innerText = data.cart_count;
                showToast("Produk berhasil ditambahkan!", "success");
            } else {
                showToast("Gagal menambah produk.", "danger");
            }
        })
        .catch(error => {
            showToast("Terjadi kesalahan sistem.", "danger");
        })
        .finally(() => {
            this.innerHTML = originalContent;
            this.disabled = false;
        });
    });
});

function showToast(message, type = "success") {
    const toastContainer = document.getElementById('toast-container');
    const toast = document.createElement('div');
    const icon = type === "success" ? "bi-check-circle-fill" : "bi-exclamation-triangle-fill";
    
    toast.className = `alert alert-${type} shadow-lg rounded-pill px-4 animate__animated animate__fadeInUp`;
    toast.style.marginBottom = '10px';
    toast.innerHTML = `<i class="bi ${icon} me-2"></i> ${message}`;
    
    toastContainer.appendChild(toast);
    setTimeout(() => { toast.remove(); }, 4000);
}
</script>
</body>
</html>