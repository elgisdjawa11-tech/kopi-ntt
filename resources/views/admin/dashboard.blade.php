@extends('layouts.admin')

@section('title', 'Dashboard')

@section('styles')
<style>
    .stat-card { border: none; border-radius: 15px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); transition: 0.3s; border-bottom: 4px solid transparent; }
    .stat-card:hover { transform: translateY(-5px); border-bottom: 4px solid var(--accent); }
    .table-container { background: white; border-radius: 15px; padding: 25px; box-shadow: 0 5px 15px rgba(0,0,0,0.05); }

    /* Palet Warna Modern (Konsisten dengan Index) */
    .status-badge { font-weight: 600; padding: 7px 16px; border-radius: 50px; font-size: 0.75rem; border: none; }
    .bg-pending { background-color: #fff3cd; color: #856404; }
    .bg-bayar-berhasil { background-color: #d1e7dd; color: #0f5132; }
    .bg-diproses { background-color: #cfe2ff; color: #084298; }
    .bg-dikirim { background-color: #e0cffc; color: #6610f2; }
    .bg-selesai { background-color: #d2f4ea; color: #055160; }
    .bg-gagal { background-color: #f8d7da; color: #842029; }
</style>
@endsection

@section('content')
<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="card stat-card p-4">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 p-3 rounded-4 me-3"><i class="bi bi-cart-check text-primary fs-3"></i></div>
                <div>
                    <p class="text-muted small mb-0">Total Pesanan</p>
                    <h3 class="fw-bold mb-0">{{ $stats['total_masuk'] }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card p-4">
            <div class="d-flex align-items-center">
                <div class="bg-success bg-opacity-10 p-3 rounded-4 me-3"><i class="bi bi-wallet2 text-success fs-3"></i></div>
                <div>
                    <p class="text-muted small mb-0">Total Pendapatan</p>
                    <h3 class="fw-bold mb-0">Rp {{ number_format($stats['pendapatan'], 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card p-4">
            <div class="d-flex align-items-center">
                <div class="bg-warning bg-opacity-10 p-3 rounded-4 me-3"><i class="bi bi-clock-history text-warning fs-3"></i></div>
                <div>
                    <p class="text-muted small mb-0">Menunggu Proses</p>
                    <h3 class="fw-bold mb-0">{{ $stats['perlu_proses'] }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="table-container admin-card">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold m-0"><i class="bi bi-lightning-charge me-2 text-warning"></i>Transaksi Terbaru</h5>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-dark rounded-pill px-3">Lihat Semua</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="bg-light">
                <tr class="text-muted small">
                    <th>ID PESANAN</th>
                    <th>PELANGGAN</th>
                    <th>TOTAL BAYAR</th>
                    <th>STATUS</th>
                    <th class="text-center">AKSI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
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
                    <td class="fw-bold text-dark">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge {{ $badgeClass }} status-badge text-capitalize">
                            {{ $displayStatus }}
                        </span>
                    </td>
                    <td class="text-center">
                        @if(in_array($status, ['pembayaran berhasil', 'settlement', 'capture', 'menunggu verifikasi', 'diproses', 'dikirim', 'selesai']))
                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-light border shadow-sm">
                                <i class="bi bi-eye"></i>
                            </a>
                        @else
                            <i class="bi bi-hourglass-split text-muted" title="Menunggu Pembayaran"></i>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection