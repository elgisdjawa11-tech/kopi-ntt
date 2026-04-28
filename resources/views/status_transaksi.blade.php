<div class="container my-5">
    <div class="card shadow-sm border-0 p-4" style="border-radius: 20px;">
        <h3 class="fw-bold mb-4">Status Pesanan #{{ $order->id }}</h3>
        
        <div class="row">
            <div class="col-md-6">
                <h5>Informasi Pengiriman:</h5>
                <p class="text-muted">
                    Penerima: {{ $order->nama_penerima }}<br>
                    Alamat: {{ $order->alamat_pengiriman }}
                </p>
            </div>
            <div class="col-md-6 text-md-end">
                <h5>Status Saat Ini:</h5>
                @if($order->status == 'Menunggu Pembayaran')
                    <span class="badge bg-warning text-dark p-2">Menunggu Pembayaran</span>
                @elseif($order->status == 'Diproses')
                    <span class="badge bg-info p-2">Sedang Diproses Admin</span>
                @else
                    <span class="badge bg-success p-2">{{ $order->status }}</span>
                @endif
            </div>
        </div>

        <hr>
        <h5>Detail Kopi:</h5>
        @foreach($order->items as $item)
            <div class="d-flex justify-content-between">
                <span>{{ $item->product->nama_kopi }} ({{ $item->jumlah }}x)</span>
                <span>Rp {{ number_format($item->harga_satuan) }}</span>
            </div>
        @endforeach
    </div>
</div>