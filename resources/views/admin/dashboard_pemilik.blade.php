@extends('layouts.admin')

@section('title', 'Owner Dashboard')

@section('styles')
<style>
    .owner-stat-card { border: none; border-radius: 20px; transition: 0.3s; background: white; overflow: hidden; }
    .owner-stat-card:hover { transform: translateY(-10px); }
    .icon-box { width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; border-radius: 15px; }
    .bg-gradient-emerald { background: linear-gradient(135deg, #1a392a, #2d5a43); color: white; }
    .bg-gradient-gold { background: linear-gradient(135deg, #c5a059, #e0b87a); color: white; }
</style>
@endsection

@section('content')
<div class="row g-4 mb-5">
    <div class="col-md-12">
        <h2 class="fw-bold mb-0">Ringkasan Bisnis Kopi NTT</h2>
        <p class="text-muted">Selamat datang kembali, berikut adalah performa toko Anda hari ini.</p>
    </div>

    <!-- Statistik Utama -->
    <div class="col-md-3">
        <div class="card owner-stat-card p-4 shadow-sm">
            <div class="icon-box bg-gradient-emerald mb-3"><i class="bi bi-wallet2 fs-3"></i></div>
            <p class="text-muted small mb-0 font-bold uppercase">Total Omzet</p>
            <h3 class="fw-bold mb-0">Rp {{ number_format($stats['total_omzet'], 0, ',', '.') }}</h3>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card owner-stat-card p-4 shadow-sm">
            <div class="icon-box bg-gradient-gold mb-3"><i class="bi bi-bag-check fs-3"></i></div>
            <p class="text-muted small mb-0 font-bold uppercase">Total Terjual</p>
            <h3 class="fw-bold mb-0">{{ $stats['total_pesanan'] }} Paket</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card owner-stat-card p-4 shadow-sm">
            <div class="icon-box bg-gradient-emerald mb-3"><i class="bi bi-people fs-3"></i></div>
            <p class="text-muted small mb-0 font-bold uppercase">Pelanggan Aktif</p>
            <h3 class="fw-bold mb-0">{{ $stats['total_user'] }} User</h3>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card owner-stat-card p-4 shadow-sm">
            <div class="icon-box bg-gradient-gold mb-3"><i class="bi bi-box-seam fs-3"></i></div>
            <p class="text-muted small mb-0 font-bold uppercase">Jenis Produk</p>
            <h3 class="fw-bold mb-0">{{ $stats['total_produk'] }} Kopi</h3>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-7">
        <div class="admin-card">
            <h5 class="fw-bold mb-4">Aktivitas Transaksi Terakhir</h5>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr class="small text-muted">
                            <th>ID ORDER</th>
                            <th>PELANGGAN</th>
                            <th>TOTAL</th>
                            <th>STATUS</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stats['transaksi_baru'] as $order)
                        <tr>
                            <td class="fw-bold small">#{{ $order->id }}</td>
                            <td class="small">{{ $order->nama_penerima }}</td>
                            <td class="small fw-bold">Rp {{ number_format($order->total_harga) }}</td>
                            <td>
                                <span class="badge rounded-pill px-3 py-2 small" style="background: #fdfaf7; color: #1a392a; border: 1px solid #c5a059;">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <a href="{{ route('admin.laporan.penjualan') }}" class="btn btn-outline-dark btn-sm rounded-pill w-100 mt-3">Lihat Laporan Lengkap</a>
        </div>
    </div>

    <div class="col-md-5">
        <div class="admin-card bg-gradient-emerald text-white h-100">
            <h5 class="fw-bold mb-4">Akses Cepat Pemilik</h5>
            <div class="list-group list-group-flush bg-transparent">
                <a href="{{ route('admin.laporan.penjualan') }}" class="list-group-item list-group-item-action bg-transparent text-white border-white border-opacity-10 py-3 d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-graph-up-arrow me-3"></i> Analisis Penjualan</span>
                    <i class="bi bi-chevron-right"></i>
                </a>
                <a href="{{ route('admin.laporan.produk') }}" class="list-group-item list-group-item-action bg-transparent text-white border-white border-opacity-10 py-3 d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-box-seam me-3"></i> Stok & Inventaris</span>
                    <i class="bi bi-chevron-right"></i>
                </a>
                <a href="{{ route('admin.laporan.pelanggan') }}" class="list-group-item list-group-item-action bg-transparent text-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-people me-3"></i> Database Pelanggan</span>
                    <i class="bi bi-chevron-right"></i>
                </a>
            </div>
            <div class="mt-4 p-3 bg-white bg-opacity-10 rounded-4">
                <small class="d-block mb-1 opacity-75">Tips Pemilik:</small>
                <p class="small mb-0 italic">"Gunakan filter pada laporan untuk melihat performa penjualan mingguan atau bulanan."</p>
            </div>
        </div>
    </div>
</div>
@endsection