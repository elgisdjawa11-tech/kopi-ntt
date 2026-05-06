@extends('layouts.admin')

@section('title', 'Manajemen Pesanan')

@section('styles')
<style>
    .status-badge { font-weight: 600; padding: 7px 16px; border-radius: 50px; font-size: 0.8rem; border: none; }
    
    /* Palet Warna Modern */
    .bg-pending { background-color: #fff3cd; color: #856404; } /* Kuning/Amber - Menunggu */
    .bg-bayar-berhasil { background-color: #d1e7dd; color: #0f5132; } /* Hijau - Lunas */
    .bg-diproses { background-color: #cfe2ff; color: #084298; } /* Biru - Proses */
    .bg-dikirim { background-color: #e0cffc; color: #6610f2; } /* Ungu - Jalan */
    .bg-selesai { background-color: #d2f4ea; color: #055160; } /* Teal - Selesai */
    .bg-gagal { background-color: #f8d7da; color: #842029; } /* Merah - Batal/Gagal */
</style>
@endsection

@section('content')
<div class="admin-card">
    <h2 class="fw-bold mb-4">Manajemen Pesanan</h2>
    
    @if(session('success')) <div class="alert alert-success border-0 rounded-4 shadow-sm mb-3">{{ session('success') }}</div> @endif
    @if(session('info')) <div class="alert alert-info border-0 rounded-4 shadow-sm mb-3">{{ session('info') }}</div> @endif
    @if(session('error')) <div class="alert alert-danger border-0 rounded-4 shadow-sm mb-3">{{ session('error') }}</div> @endif

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead>
                <tr class="table-light">
                    <th>ID Pesanan</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                @php 
                    $status = strtolower($order->status);
                    $badgeClass = 'bg-secondary';
                    $displayStatus = $order->status;

                    if (in_array($status, ['pending', 'menunggu pembayaran'])) {
                        $badgeClass = 'bg-pending';
                        $displayStatus = 'Menunggu Pembayaran';
                    } elseif (in_array($status, ['pembayaran berhasil', 'settlement', 'capture', 'menunggu verifikasi'])) {
                        $badgeClass = 'bg-bayar-berhasil';
                        $displayStatus = 'Pembayaran Berhasil';
                    } elseif ($status == 'diproses') {
                        $badgeClass = 'bg-diproses';
                        $displayStatus = 'Pesanan Diproses';
                    } elseif ($status == 'dikirim') {
                        $badgeClass = 'bg-dikirim';
                        $displayStatus = 'Barang Dikirim';
                    } elseif ($status == 'selesai') {
                        $badgeClass = 'bg-selesai';
                        $displayStatus = 'Selesai';
                    } elseif (in_array($status, ['expire', 'cancel', 'deny', 'failure', 'gagal', 'kadaluarsa', 'dibatalkan'])) {
                        $badgeClass = 'bg-gagal';
                    }
                @endphp
                <tr>
                    <td class="fw-bold">#ORD-{{ $order->id }}</td>
                    <td>{{ $order->nama_penerima ?? ($order->user->name ?? '-') }}</td>
                    <td class="text-success fw-bold">Rp {{ number_format($order->total_harga) }}</td>
                    <td>
                        <span class="badge {{ $badgeClass }} status-badge text-capitalize">{{ $displayStatus }}</span>
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            @if(in_array($status, ['pembayaran berhasil', 'settlement', 'capture', 'menunggu verifikasi', 'diproses', 'dikirim', 'selesai']))
                                @if(in_array($status, ['pembayaran berhasil', 'settlement', 'capture', 'menunggu verifikasi']))
                                    <form action="{{ route('admin.orders.konfirmasi', $order->id) }}" method="POST">@csrf<button class="btn btn-warning btn-sm rounded-pill px-3">Konfirmasi</button></form>
                                @endif
                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-outline-dark btn-sm rounded-pill px-3">Detail</a>
                            @else
                                <span class="text-muted small italic"><i class="bi bi-lock-fill me-1"></i>Menunggu Pembayaran</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-5">Belum ada pesanan.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $orders->links() }}
    </div>
</div>
@endsection