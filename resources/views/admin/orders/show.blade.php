@extends('layouts.admin') {{-- Sesuaikan dengan layout admin Anda --}}

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Detail Pesanan #{{ $order->id }}</h2>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary rounded-pill">Kembali</a>
    </div>

    <div class="row">
        <div class="col-md-5">
            <div class="card shadow-sm border-0 p-4 mb-4" style="border-radius: 20px;">
                <h5 class="fw-bold mb-3"><i class="bi bi-geo-alt-fill text-danger me-2"></i>Tujuan Pengiriman</h5>
                <hr>
                <div class="mb-3">
                    <label class="text-muted small d-block">Nama Penerima:</label>
                    <span class="fw-bold fs-5">{{ $order->nama_penerima }}</span>
                </div>
                <div class="mb-3">
                    <label class="text-muted small d-block">Nomor HP / WhatsApp:</label>
                    <span class="fw-bold text-success">{{ $order->nomor_hp }}</span>
                </div>
                <div class="mb-3">
                    <label class="text-muted small d-block">Alamat Lengkap:</label>
                    <p class="fw-semibold">{{ $order->alamat_pengiriman }}</p>
                </div>
                <div class="mb-0">
                    <label class="text-muted small d-block">Status Pesanan:</label>
                    <span class="badge bg-info p-2">{{ $order->status }}</span>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card shadow-sm border-0 p-4" style="border-radius: 20px;">
                <h5 class="fw-bold mb-3"><i class="bi bi-box-seam-fill text-brown me-2"></i>Produk yang Dipesan</h5>
                <hr>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <span class="fw-bold">{{ $item->product->nama_kopi ?? 'Produk Dihapus' }}</span><br>
                                    <small class="text-muted">{{ $item->product->daerah_asal ?? '-' }}</small>
                                </td>
                                <td class="text-center">{{ $item->jumlah }} Pack</td>
                                <td class="text-end">Rp {{ number_format($item->harga_satuan) }}</td>
                                <td class="text-end fw-bold">Rp {{ number_format($item->jumlah * $item->harga_satuan) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="3" class="text-end fs-5">Total Bayar:</th>
                                <th class="text-end fs-5 text-primary">Rp {{ number_format($order->total_harga) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($order->status == 'Diproses')
                <div class="mt-4">
                    <a href="{{ route('admin.orders.update', [$order->id, 'Dikirim']) }}" 
                       class="btn btn-primary w-100 py-2 rounded-pill fw-bold">
                       <i class="bi bi-truck me-2"></i> Kirim Barang Sekarang
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection