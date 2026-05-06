<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran | Kopi NTT Premium</title>
    
    <script type="text/javascript" src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    
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
        body { font-family: 'Poppins', sans-serif; background-color: var(--bg-light); color: var(--coffee-dark); }
        .stepper { display: flex; justify-content: center; margin-bottom: 40px; }
        .step { text-align: center; position: relative; width: 150px; }
        .step-icon { width: 40px; height: 40px; border-radius: 50%; background: #e0e0e0; display: flex; align-items: center; justify-content: center; margin: 0 auto 10px; font-weight: bold; color: white; z-index: 2; position: relative; }
        .step.active .step-icon { background: var(--coffee-dark); border: 3px solid var(--gold-accent); }
        .step.completed .step-icon { background: var(--emerald-mid); }
        .step span { font-size: 0.8rem; color: #aaa; }
        .step.active span { font-weight: 600; color: var(--coffee-dark); }

        .payment-card { border: none; border-radius: 30px; box-shadow: 0 20px 60px rgba(26, 57, 42, 0.1); background: #ffffff; overflow: hidden; border-top: 8px solid var(--gold-accent); }
        .payment-header { background: linear-gradient(135deg, var(--emerald-dark) 0%, var(--emerald-mid) 100%); color: white; padding: 40px; text-align: center; }
        .amount-box { background-color: #fff9f5; border: 2px dashed var(--gold-accent); border-radius: 20px; padding: 25px; text-align: center; margin: -30px 10% 30px; position: relative; z-index: 10; }
        
        .summary-mini { background: #f8f9fa; border-radius: 15px; padding: 15px 20px; margin-bottom: 25px; border-left: 4px solid var(--gold-accent); }
        .instruction-card { background: #f8f9fa; border-radius: 20px; padding: 25px; border: 1px solid #eee; margin-bottom: 30px; }
        .btn-pay { background-color: var(--emerald-dark); color: white; border-radius: 15px; padding: 18px; font-weight: 700; transition: 0.3s; border: 1px solid var(--gold-accent); width: 100%; letter-spacing: 1px; font-size: 1.1rem; }
        .btn-pay:hover { background-color: var(--gold-accent); transform: translateY(-3px); box-shadow: 0 10px 20px rgba(197, 160, 89, 0.2); color: white; }
        .midtrans-logo { max-width: 120px; opacity: 0.6; margin-top: 20px; }
    </style>
</head>
<body>

<div class="container my-5 py-4">
    <div class="stepper">
        <div class="step completed"><div class="step-icon"><i class="bi bi-check-lg"></i></div><span>Pengiriman</span></div>
        <div class="step active"><div class="step-icon">2</div><span>Pembayaran</span></div>
        <div class="step"><div class="step-icon">3</div><span>Selesai</span></div>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="payment-card shadow-lg">
                <div class="payment-header">
                    <h3 class="fw-bold mb-1" style="font-family: 'Playfair Display', serif;">Ringkasan Pembayaran</h3>
                    <p class="mb-0 opacity-75 small">ID Transaksi: #ORD-{{ $order->id }}</p>
                </div>

                <div class="p-4 p-md-5">
                    <div class="summary-mini shadow-sm">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted small">Total Produk:</span>
                            <span class="small fw-bold">Rp {{ number_format($order->total_harga - $order->ongkir, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted small">Ongkos Kirim (NTT):</span>
                            <span class="small fw-bold text-success">+ Rp {{ number_format($order->ongkir, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="amount-box shadow-sm">
                        <span class="text-muted small text-uppercase tracking-widest">Total Harus Dibayar</span>
                        <h2 class="fw-bold mb-0 mt-1" style="color: var(--emerald-dark);">
                            Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                        </h2>
                    </div>

                    <div class="instruction-card text-center">
                        <i class="bi bi-shield-check text-success fs-1 mb-3 d-block"></i>
                        <h6 class="fw-bold">Pembayaran Aman & Terenkripsi</h6>
                        <p class="small text-muted mb-0">Layanan ini mendukung QRIS, GoPay, dan Transfer Bank melalui sistem Midtrans.</p>
                    </div>

                    <button type="button" id="pay-button" class="btn btn-pay shadow">
                        BAYAR SEKARANG <i class="bi bi-credit-card-2-front ms-2"></i>
                    </button>

                    <div class="text-center mt-4">
                        <p class="extra-small text-muted mb-1">Partner Resmi:</p>
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/Midtrans_Logo.png/640px-Midtrans_Logo.png" class="midtrans-logo" alt="Midtrans">
                    </div>

                    <div class="text-center mt-5">
                        <a href="{{ route('home') }}" class="text-decoration-none text-muted small">
                            <i class="bi bi-house-door"></i> Kembali ke Beranda
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    const payButton = document.getElementById('pay-button');
    payButton.addEventListener('click', function () {
        // Eksekusi Snap Pop-up
        window.snap.pay('{{ $order->snap_token }}', {
            onSuccess: function (result) {
                fetch("{{ route('midtrans.callback') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(result)
                }).finally(() => {
                    window.location.href = "{{ route('pesanan.lacak', $order->id) }}";
                });
            },
            onPending: function (result) {
                fetch("{{ route('midtrans.callback') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(result)
                }).finally(() => {
                    location.reload();
                });
            },
            onError: function (result) {
                alert("Pembayaran Gagal, Silakan Coba Lagi.");
            },
            onClose: function () {
                alert('Halaman pembayaran ditutup sebelum transaksi selesai.');
            }
        });
    });
</script>

</body>
</html>