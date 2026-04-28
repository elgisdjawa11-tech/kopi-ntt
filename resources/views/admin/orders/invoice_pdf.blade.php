<!DOCTYPE html>
<html>
<head>
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .table th { background-color: #3e2723; color: white; }
        .total { text-align: right; font-weight: bold; font-size: 14px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>INVOICE KOPI NTT</h2>
        <p>ID Pesanan: #ORD-{{ $order->id }} | Tanggal: {{ $order->created_at->format('d/m/Y') }}</p>
    </div>

    <p><strong>Penerima:</strong> {{ $order->nama_penerima }}<br>
       <strong>Alamat:</strong> {{ $order->alamat_pengiriman }}<br>
       <strong>No. HP:</strong> {{ $order->nomor_hp }}</p>

    <table class="table">
        <thead>
            <tr>
                <th>Produk</th>
                <th>Jumlah</th>
                <th>Harga</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
            <tr>
                <td>{{ $item->product->nama_kopi }}</td>
                <td>{{ $item->jumlah }}</td>
                <td>Rp {{ number_format($item->harga_satuan) }}</td>
                <td>Rp {{ number_format($item->jumlah * $item->harga_satuan) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Total Bayar: Rp {{ number_format($order->total_harga) }}
    </div>
</body>
</html>