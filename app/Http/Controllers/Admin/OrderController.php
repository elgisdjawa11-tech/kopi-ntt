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
            'perlu_proses' => Order::whereIn('status', ['menunggu verifikasi', 'diproses'])->count(),
            'pendapatan'   => Order::where('status', 'selesai')->sum('total_harga') ?? 0,
        ];

        return view('admin.dashboard', compact('orders', 'stats'));
    }

    public function listOrders()
    {
        // Untuk Manajemen Pesanan, kita tampilkan 'pending' hanya jika Admin mau cek status, 
        // tapi secara default kita filter agar Admin fokus pada yang sudah bayar.
        $orders = Order::with('items.product', 'user')
                    ->latest()
                    ->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    public function cekStatusPembayaran($id)
    {
        $order = Order::findOrFail($id);
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = config('midtrans.is_production');

        try {
            $status = \Midtrans\Transaction::status($order->id);
            
            if ($status->transaction_status == 'settlement' || $status->transaction_status == 'capture') {
                $order->update(['status' => 'pembayaran berhasil']);
                return back()->with('success', 'Pembayaran Lunas! Data sudah masuk ke laporan.');
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
        if ($order->status !== 'diproses') return back()->with('error', 'Belum dikonfirmasi.');

        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                if ($item->product) $item->product->decrement('stok', $item->jumlah);
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
        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('updated_at', [$request->tgl_mulai . ' 00:00:00', $request->tgl_selesai . ' 23:59:59']);
        }
        $orders = $query->latest('updated_at')->get();
        return view('admin.laporan.penjualan', [
            'orders' => $orders,
            'totalPendapatan' => $orders->sum('total_harga'),
            'jumlahSelesai' => $orders->count(),
            'rataRata' => $orders->count() > 0 ? $orders->sum('total_harga') / $orders->count() : 0
        ]);
    }

    public function exportLaporanPDF(Request $request)
    {
        $orders = Order::where('status', 'selesai')->latest('updated_at')->get();
        $pdf = Pdf::loadView('admin.pdf.laporan_bisnis', [
            'orders' => $orders,
            'totalPendapatan' => $orders->sum('total_harga'),
            'periode' => 'Laporan Penjualan'
        ]);
        return $pdf->download('Laporan-Kopi-NTT.pdf');
    }

    public function show($id) {
        return view('admin.orders.show', ['order' => Order::with(['items.product', 'user'])->findOrFail($id)]);
    }

    public function laporanProduk() { return view('admin.laporan.produk', ['kopis' => Product::all()]); }
    public function laporanPelanggan() { return view('admin.laporan.pelanggan', ['pelanggan' => User::where('role', 'pelanggan')->get()]); }
}