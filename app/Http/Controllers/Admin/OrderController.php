<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; 

class OrderController extends Controller 
{
    // Daftar status yang sudah "Lunas" atau sedang diproses
    protected $validStatuses = [
        'pembayaran berhasil', 'settlement', 'capture', 
        'menunggu verifikasi', 'diproses', 'dikirim', 'selesai'
    ];

    public function index() 
    {
        $orders = Order::with('items.product')
                    ->whereIn('status', $this->validStatuses)
                    ->latest()->take(10)->get();
        
        $stats = [
            'total_masuk'  => Order::whereIn('status', $this->validStatuses)->count(),
            'perlu_proses' => Order::whereIn('status', ['pembayaran berhasil', 'menunggu verifikasi', 'diproses'])->count(),
            'pendapatan'   => Order::where('status', 'selesai')->sum('total_harga') ?? 0,
        ];

        return view('admin.dashboard', compact('orders', 'stats'));
    }

    /**
     * Dashboard Utama Khusus Pemilik (Owner)
     */
    public function ownerDashboard()
    {
        $stats = [
            'total_omzet'   => Order::where('status', 'selesai')->sum('total_harga'),
            'total_pesanan' => Order::where('status', 'selesai')->count(),
            'total_produk'  => Product::count(),
            'total_user'    => User::where('role', 'pelanggan')->count(),
            'transaksi_baru' => Order::latest()->take(5)->get()
        ];

        return view('admin.dashboard_pemilik', compact('stats'));
    }

    public function listOrders()
    {
        // Filter agar pesanan yang gagal (expire, cancel, deny, failure) tidak muncul di daftar kerja Admin
        $orders = Order::with('items.product', 'user')
                    ->whereNotIn('status', ['expire', 'cancel', 'deny', 'failure'])
                    ->latest()
                    ->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function cekStatusPembayaran($id)
    {
        $order = Order::findOrFail($id);
        
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);

        // Gunakan midtrans_id yang tersimpan, jika tidak ada baru gunakan ID utama
        $midtransId = $order->midtrans_id ?? $order->id;

        try {
            $status = \Midtrans\Transaction::status($midtransId);
            
            if ($status->transaction_status == 'settlement' || $status->transaction_status == 'capture') {
                $order->update(['status' => 'menunggu verifikasi']);
                return back()->with('success', 'Pembayaran Lunas! Silakan klik Konfirmasi untuk memproses.');
            }
            return back()->with('info', 'Status di Midtrans: ' . $status->transaction_status);
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal cek ke Midtrans: ' . $e->getMessage());
        }
    }

    public function konfirmasiPembayaran($id)
    {
        $order = Order::findOrFail($id);
        if (!in_array($order->status, ['pembayaran berhasil', 'settlement', 'capture', 'menunggu verifikasi'])) {
            return back()->with('error', 'Pesanan belum lunas.');
        }
        $order->update(['status' => 'diproses']);
        return back()->with('success', 'Pesanan mulai diproses.');
    }

    public function kirimBarang($id)
    {
        $order = Order::findOrFail($id);
        
        // Cek apakah status sudah 'dikirim' untuk mencegah pengurangan stok ganda
        if ($order->status === 'dikirim') {
            return back()->with('info', 'Pesanan ini sudah dikirim sebelumnya.');
        }

        if ($order->status !== 'diproses') {
            return back()->with('error', 'Pesanan harus dalam status "diproses" sebelum dikirim.');
        }

        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->decrement('stok', $item->jumlah);
                }
            }
            $order->update(['status' => 'dikirim']);
        });

        return back()->with('success', 'Barang dikirim ke kurir.');
    }

    public function uploadBukti(Request $request, $id)
    {
        $request->validate(['bukti_foto' => 'required|image|max:2048']);
        $order = Order::findOrFail($id);
        if ($request->hasFile('bukti_foto')) {
            $path = $request->file('bukti_foto')->store('bukti_pengiriman', 'public');
            $order->update(['foto_penerimaan' => $path, 'status' => 'selesai']);
            return back()->with('success', 'Transaksi Selesai!');
        }
        return back()->with('error', 'Gagal upload.');
    }

    public function updateStatus($id, $status) {
        Order::findOrFail($id)->update(['status' => $status]);
        return back()->with('success', 'Status diubah manual.');
    }

    public function laporanPenjualan(Request $request)
    {
        $query = Order::where('status', 'selesai'); 

        // Filter Rentang Tanggal
        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('updated_at', [$request->tgl_mulai . ' 00:00:00', $request->tgl_selesai . ' 23:59:59']);
        }

        // Filter Bulan & Tahun
        if ($request->filled('bulan')) {
            $query->whereMonth('updated_at', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('updated_at', $request->tahun);
        }

        $orders = $query->latest('updated_at')->get();
        
        $totalPendapatan = $orders->sum('total_harga');
        $jumlahSelesai = $orders->count();
        $rataRata = $jumlahSelesai > 0 ? $totalPendapatan / $jumlahSelesai : 0;

        return view('admin.laporan.penjualan', compact('orders', 'totalPendapatan', 'jumlahSelesai', 'rataRata'));
    }

    public function exportLaporanPDF(Request $request)
    {
        $query = Order::where('status', 'selesai'); 

        // Filter yang sama dengan tampilan web
        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('updated_at', [$request->tgl_mulai . ' 00:00:00', $request->tgl_selesai . ' 23:59:59']);
        }
        if ($request->filled('bulan')) {
            $query->whereMonth('updated_at', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('updated_at', $request->tahun);
        }

        $orders = $query->latest('updated_at')->get();
        
        $totalPendapatan = $orders->sum('total_harga');
        $jumlahSelesai = $orders->count();
        $rataRata = $jumlahSelesai > 0 ? $totalPendapatan / $jumlahSelesai : 0;

        $pdf = Pdf::loadView('admin.pdf.laporan_bisnis', [
            'orders' => $orders,
            'totalPendapatan' => $totalPendapatan,
            'jumlahSelesai' => $jumlahSelesai,
            'rataRata' => $rataRata,
            'periode' => 'Laporan Penjualan'
        ]);

        return $pdf->download('Laporan-Penjualan-Kopi-NTT.pdf');
    }

    public function show($id) {
        return view('admin.orders.show', ['order' => Order::with(['items.product', 'user'])->findOrFail($id)]);
    }

    public function laporanProduk() { return view('admin.laporan.produk', ['kopis' => Product::all()]); }
    public function laporanPelanggan() { return view('admin.laporan.pelanggan', ['pelanggan' => User::where('role', 'pelanggan')->get()]); }
}