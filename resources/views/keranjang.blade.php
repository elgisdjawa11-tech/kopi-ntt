<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Keranjang Belanja | Kopi NTT</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        :root { --coffee-dark: #3e2723; --accent: #d4a373; --coffee-light: #fdfaf7; }
        body { font-family: 'Poppins', sans-serif; background-color: var(--coffee-light); color: var(--coffee-dark); }
        .navbar { background-color: var(--coffee-dark) !important; }
        .card-cart { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .btn-checkout { background-color: var(--coffee-dark); color: white; border-radius: 50px; padding: 12px 30px; font-weight: 600; text-decoration: none; display: inline-block; }
        .btn-checkout:hover { background-color: var(--accent); color: white; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container">
        <a class="navbar-brand fw-bold fs-3" href="{{ route('home') }}">KOPI <span style="color: var(--accent)">NTT</span></a>
        <div class="ms-auto">
            <a href="{{ route('home') }}" class="btn btn-outline-light btn-sm rounded-pill px-4">Kembali Belanja</a>
        </div>
    </div>
</nav>

<div class="container my-5 py-5">
    <h2 class="fw-bold mb-4" style="font-family: 'Playfair Display', serif;">Keranjang Belanja Anda</h2>

    @if(session('cart') && count(session('cart')) > 0)
        <div class="card card-cart p-4">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0 @endphp
                        @foreach(session('cart') as $id => $details)
                            @php $total += $details['price'] * $details['quantity'] @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('storage/'.$details['photo']) }}" width="60" height="60" class="rounded me-3" style="object-fit: cover;">
                                        <div>
                                            <div class="fw-bold">{{ $details['name'] }}</div>
                                            <small class="text-muted">{{ $details['origin'] }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>Rp {{ number_format($details['price'], 0, ',', '.') }}</td>
                                <td>{{ $details['quantity'] }} Pack</td>
                                <td class="fw-bold">Rp {{ number_format($details['price'] * $details['quantity'], 0, ',', '.') }}</td>
                                <td>
                                    <form action="{{ route('cart.remove', $id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger rounded-circle border-0">
                                            <i class="bi bi-trash-fill"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="row mt-4 align-items-center">
                <div class="col-md-6">
                    <a href="{{ route('home') }}" class="text-decoration-none text-muted small">
                        <i class="bi bi-arrow-left"></i> Tambah produk lain
                    </a>
                </div>
                <div class="col-md-6 text-end">
                    <h4 class="mb-3">Total: <span class="fw-bold" style="color: var(--coffee-brown);">Rp {{ number_format($total, 0, ',', '.') }}</span></h4>
                    <a href="{{ route('checkout.pengiriman', array_key_first(session('cart'))) }}" class="btn btn-checkout shadow-sm">
                        Lanjut ke Pengiriman <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-cart-x display-1 text-muted"></i>
            <h4 class="mt-4">Keranjang Anda masih kosong</h4>
            <p class="text-muted">Sepertinya Anda belum memilih kopi NTT favorit Anda.</p>
            <a href="{{ route('home') }}" class="btn btn-checkout mt-3">Mulai Belanja</a>
        </div>
    @endif
</div>

<footer class="text-center mt-5 py-4 border-top">
    <p class="small text-muted">&copy; 2026 Kopi NTT - Proyek Tugas Akhir</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>