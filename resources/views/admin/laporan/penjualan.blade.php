@extends('layouts.admin')

@section('title', 'Laporan Penjualan')

@section('content')
<header class="mb-4 d-flex justify-content-between align-items-center">
    <div>
        <h2 class="fw-bold">Laporan Penjualan</h2>
        <p class="text-muted small">Ringkasan transaksi selesai untuk periode yang dipilih.</p>
    </div>
    <a href="{{ route('admin.laporan.pdf', request()->query()) }}" class="btn btn-outline-danger rounded-pill px-4 fw-bold">
        <i class="bi bi-file-pdf me-2"></i>Ekspor PDF
    </a>
</header>

<div class="card p-4 border-0 shadow-sm mb-4" style="border-radius: 20px;">
    <form action="{{ route('admin.laporan.penjualan') }}" method="GET" class="row g-3 align-items-end">
        <div class="col-md-5 border-end">
            <label class="form-label small fw-bold">FILTER MINGGUAN (RENTANG TANGGAL)</label>
            <div class="input-group input-group-sm">
                <input type="date" name="tgl_mulai" class="form-control" value="{{ request('tgl_mulai') }}">
                <span class="input-group-text">s/d</span>
                <input type="date" name="tgl_selesai" class="form-control" value="{{ request('tgl_selesai') }}">
            </div>
        </div>
        <div class="col-md-4">
            <label class="form-label small fw-bold">FILTER BULANAN</label>
            <div class="d-flex gap-2">
                <select name="bulan" class="form-select form-select-sm">
                    <option value="">-- Bulan --</option>
                    @foreach(['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'] as $k => $v)
                        <option value="{{ $k }}" {{ request('bulan') == $k ? 'selected' : '' }}>{{ $v }}</option>
                    @endforeach
                </select>
                <select name="tahun" class="form-select form-select-sm">
                    @for($i=date('Y'); $i>=2024; $i--)
                        <option value="{{ $i }}" {{ request('tahun', date('Y')) == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>
        <div class="col-md-3">
            <button type="submit" class="btn btn-dark btn-sm w-100 fw-bold">TERAPKAN</button>
        </div>
    </form>
</div>

<div class="admin-card">
    <table class="table table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>TANGGAL</th>
                <th>ID ORDER</th>
                <th>NAMA PENERIMA</th>
                <th class="text-end">TOTAL</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr>
                <td>{{ $order->created_at->format('d M Y') }}</td>
                <td class="fw-bold">#{{ $order->id }}</td>
                <td>{{ $order->nama_penerima }}</td>
                <td class="text-end fw-bold text-success">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center py-4 text-muted">Tidak ada data untuk periode ini.</td>
            </tr>
            @endforelse
        </tbody>
        
        @if($orders->count() > 0)
        <tfoot class="table-light">
            <tr>
                <td colspan="3" class="text-end fw-bold py-3" style="font-size: 1.1rem;">TOTAL KESELURUHAN :</td>
                <td class="text-end fw-bold py-3 text-primary" style="font-size: 1.2rem;">
                    Rp {{ number_format($orders->sum('total_harga'), 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
        @endif
    </table>
</div>
@endsection