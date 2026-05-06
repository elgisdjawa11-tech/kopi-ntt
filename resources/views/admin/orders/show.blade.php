@extends('layouts.admin')

@section('title', 'Detail Pesanan #' . $order->id)

@section('styles')
<style>
    .detail-card { border: none; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.05); background: white; }
</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Detail Pesanan #{{ $order->id }}</h2>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
    </div>

    @if(session('success')) <div class="alert alert-success border-0 rounded-4 mb-4 shadow-sm">{{ session('success') }}</div> @endif
    @if(session('info')) <div class="alert alert-info border-0 rounded-4 mb-4 shadow-sm">{{ session('info') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger border-0 rounded-4 mb-4 shadow-sm">{{ session('error') }}</div> @endif

    <div class="row">
        <!-- Informasi Pengiriman -->
        <div class="col-md-5">
            <div class="detail-card p-4 mb-4">
                <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-geo-alt-fill text-danger me-2"></i>Informasi Pelanggan</h5>
                <hr>
                <div class="mb-3">
                    <small class="text-muted d-block">Nama Penerima:</small>
                    <span class="fw-bold fs-5 text-capitalize">{{ $order->nama_penerima ?? ($order->user->name ?? 'Pelanggan') }}</span>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Nomor WhatsApp:</small>
                    <span class="fw-bold text-success">{{ $order->nomor_hp ?? ($order->user->nomor_hp ?? '-') }}</span>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block">Alamat Lengkap:</small>
                    <p class="fw-semibold mb-1">{{ $order->alamat_pengiriman ?? ($order->user->alamat ?? '-') }}</p>
                    @if($order->kabupaten)
                        <span class="badge bg-light text-dark border"><i class="bi bi-geo-alt me-1"></i>{{ $order->kabupaten }}</span>
                    @endif
                </div>                <div class="mb-0">
                    <small class="text-muted d-block">Status Saat Ini:</small>
                    <span class="badge bg-primary px-3 py-2 rounded-pill text-capitalize">{{ $order->status }}</span>
                </div>
            </div>
        </div>

        <!-- Daftar Produk -->
        <div class="col-md-7">
            <div class="detail-card p-4">
                <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-box-seam-fill me-2" style="color: #6d4c41;"></i>Item Pesanan</h5>
                <hr>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th class="text-center">Jumlah</th>
                                <th class="text-end">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>
                                    <span class="fw-bold">{{ $item->product ? $item->product->nama_kopi : 'Produk Tidak Tersedia (Dihapus)' }}</span><br>
                                    <small class="text-muted">Harga: Rp {{ number_format($item->harga_satuan) }}</small>
                                </td>
                                <td class="text-center">{{ $item->jumlah }} Pcs</td>
                                <td class="text-end fw-bold">Rp {{ number_format($item->jumlah * $item->harga_satuan) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-end py-3">Total Pembayaran:</th>
                                <th class="text-end py-3 text-primary fs-5">Rp {{ number_format($order->total_harga) }}</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Tombol Aksi sesuai alur sidang -->
                @php $statusLower = strtolower($order->status); @endphp
                <!-- Tombol Aksi sesuai alur sidang -->
                @php $statusLower = strtolower($order->status); @endphp

                <div class="mt-4 d-grid gap-3">
                    @if(in_array($statusLower, ['pembayaran berhasil', 'settlement', 'capture', 'menunggu verifikasi']))
                        <form action="{{ route('admin.orders.konfirmasi', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-warning w-100 py-2 rounded-pill fw-bold shadow-sm">
                                <i class="bi bi-check-circle me-2"></i>Konfirmasi Pesanan
                            </button>
                        </form>
                    @endif

                    @if($statusLower == 'diproses')
                        <form action="{{ route('admin.orders.kirim', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-dark w-100 py-2 rounded-pill fw-bold shadow-sm">
                                <i class="bi bi-truck me-2"></i>Kirim Barang Sekarang
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection