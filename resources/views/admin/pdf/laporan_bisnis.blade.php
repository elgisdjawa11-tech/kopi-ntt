<!DOCTYPE html>
<html>
<head>
    <title>Laporan Penjualan Kopi NTT</title>
    <style>
        body { font-family: sans-serif; font-size: 11px; color: #333; }
        .header { 
            text-align: center; 
            margin-bottom: 30px; 
            border-bottom: 2px solid #3e2723; 
            padding-bottom: 10px; 
        }
        .header h1 { 
            color: #3e2723; 
            margin: 0; 
            text-transform: uppercase; 
            font-size: 22px;
        }
        .header p { 
            margin: 5px 0 0 0; 
            font-size: 12px; 
            color: #666; 
        }
        .stat-table { width: 100%; margin-bottom: 20px; border-collapse: collapse; }
        .stat-table td { 
            padding: 15px; 
            background: #fdfaf7; 
            border: 1px solid #ddd; 
            text-align: center; 
            width: 33.33%;
        }
        .table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table th { 
            background-color: #3e2723; 
            color: white; 
            border: 1px solid #3e2723; 
            padding: 10px 8px; 
            text-align: left; 
            text-transform: uppercase;
        }
        .table td { 
            border: 1px solid #ddd; 
            padding: 8px; 
        }
        .text-end { text-align: right; }
        .fw-bold { font-weight: bold; }

        /* AREA TANDA TANGAN TANPA GARIS */
        .signature-container {
            margin-top: 50px;
            width: 100%;
        }
        .signature-table {
            float: right;
            width: 250px;
            border-collapse: collapse;
        }
        .signature-table td {
            padding: 5px 0;
            text-align: center;
            border: none; /* Menghilangkan semua garis */
        }
        .signature-space {
            height: 60px; /* Ruang kosong untuk tanda tangan basah */
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN PENJUALAN KOPI NTT</h1>
        <p>Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y | H:i') }} WIB</p>
    </div>

    <h3>Ringkasan Eksekutif</h3>
    <table class="stat-table">
        <tr>
            <td>
                <strong>Total Pendapatan</strong><br>
                <span style="color: #2e7d32; font-size: 16px; font-weight: bold;">
                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                </span>
            </td>
            <td>
                <strong>Volume Penjualan</strong><br>
                <span style="font-size: 16px; font-weight: bold;">{{ $jumlahSelesai }} Transaksi</span>
            </td>
            <td>
                <strong>Rata-rata Transaksi</strong><br>
                <span style="font-size: 16px; font-weight: bold;">
                    Rp {{ number_format($rataRata, 0, ',', '.') }}
                </span>
            </td>
        </tr>
    </table>

    <h3>Rincian Transaksi Selesai</h3>
    <table class="table">
        <thead>
            <tr>
                <th style="width: 20%;">TANGGAL</th>
                <th style="width: 20%;">ID ORDER</th>
                <th style="width: 35%;">NAMA PELANGGAN</th>
                <th style="width: 25%; text-align: right;">TOTAL HARGA</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                <td class="fw-bold">#ORD-{{ $order->id }}</td>
                <td>{{ $order->nama_penerima }}</td>
                <td class="text-end">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr style="background-color: #f9f9f9;">
                <td colspan="3" class="text-end fw-bold" style="padding: 10px;">TOTAL KESELURUHAN :</td>
                <td class="text-end fw-bold" style="padding: 10px; font-size: 13px; color: #3e2723;">
                    Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                </td>
            </tr>
        </tfoot>
    </table>

    <div class="signature-container">
        <table class="signature-table">
            <tr>
                <td>Pemilik Kopi NTT,</td>
            </tr>
            <tr>
                <td class="signature-space"></td>
            </tr>
            <tr>
                <td style="font-weight: bold; text-decoration: underline;">Bapak Yohanes Goru</td>
            </tr>
        </table>
    </div>

</body>
</html>