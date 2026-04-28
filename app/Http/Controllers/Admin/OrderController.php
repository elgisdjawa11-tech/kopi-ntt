<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf; 
use Carbon\Carbon;

class OrderController extends Controller 
{
    /**
     * 1. Dashboard Utama Admin
     */
    public function index() 
    {
        // Menambahkan 'menunggu verifikasi' agar muncul di statistik dashboard
        $validStatuses = [
            'menunggu verifikasi', 
            'pembayaran berhasil', 
            'settlement', 
            'capture', 
            'diproses', 
            'dikirim', 
            'selesai'
        ];

        $orders = Order::with('items.product')
                    ->whereIn('status', $validStatuses)
                    ->latest()
                    ->take(10)
                    ->get();
        
        $stats = [
            'total_masuk'  => Order::whereIn('status', $validStatuses)->count(),
            'perlu_proses' => Order::whereIn('status', ['menunggu verifikasi', 'diproses'])->count(),
            'pendapatan'   => Order::where('status', 'selesai')->sum('total_harga') ?? 0,
        ];

        return view('admin.dashboard', compact('orders', 'stats'));
    }

    /**
     * 2. Manajemen Daftar Pesanan
     */
    public function listOrders()
    {
        // Status 'menunggu verifikasi' dimasukkan agar data tampil di tabel index
        $validStatuses = [
            'menunggu verifikasi', 
            'pembayaran berhasil', 
            'settlement', 
            'capture', 
            'diproses', 
            'dikirim', 
            'selesai'
        ];

        $orders = Order::with('items.product')
                    ->whereIn('status', $validStatuses)
                    ->latest()
                    ->paginate(10);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * 3. Tahap 1: Konfirmasi Pembayaran
     */
    public function konfirmasiPembayaran($id)
    {
        $order = Order::findOrFail($id);
        
        // Membolehkan konfirmasi jika statusnya 'menunggu verifikasi'
        $statusBisaDikonfirmasi = ['menunggu verifikasi', 'pembayaran berhasil', 'settlement', 'capture'];
        
        if (!in_array($order->status, $statusBisaDikonfirmasi)) {
            return back()->with('error', 'Pesanan ini tidak dalam status yang bisa dikonfirmasi.');
        }

        $order->update(['status' => 'diproses']);
        return back()->with('success', "Pesanan Berhasil Dikonfirmasi! Status kini: 'Diproses'.");
    }

    /**
     * 4. Tahap 2: Kirim Barang (Potong Stok)
     */
    public function kirimBarang($id)
    {
        $order = Order::findOrFail($id);
        
        if ($order->status !== 'diproses') {
            return back()->with('error', 'Pesanan harus dikonfirmasi terlebih dahulu sebelum mengirim.');
        }

        DB::transaction(function () use ($order) {
            foreach ($order->items as $item) {
                if ($item->product) {
                    $item->product->decrement('stok', $item->jumlah);
                }
            }
            $order->update(['status' => 'dikirim']);
        });

        return back()->with('success', "Pesanan #ORD-{$id} telah dikirim ke kurir.");
    }

    /**
     * 5. Upload Bukti & Selesai
     */
    public function uploadBukti(Request $request, $id)
    {
        $request->validate(['bukti_foto' => 'required|image|mimes:jpeg,png,jpg|max:2048']);
        $order = Order::findOrFail($id);

        if ($request->hasFile('bukti_foto')) {
            $path = $request->file('bukti_foto')->store('bukti_pengiriman', 'public');
            $order->update([
                'foto_penerimaan' => $path,
                'status' => 'selesai' 
            ]);
            return redirect()->back()->with('success', 'Transaksi Selesai! Data masuk ke Laporan Penjualan.');
        }
        return redirect()->back()->with('error', 'Gagal mengunggah foto.');
    }

    /**
     * 6. Laporan Penjualan (HANYA YANG SELESAI)
     */
    public function laporanPenjualan(Request $request)
    {
        $query = Order::where('status', 'selesai'); 

        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('updated_at', [$request->tgl_mulai . ' 00:00:00', $request->tgl_selesai . ' 23:59:59']);
        } else {
            $query->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'));
        }

        $orders = $query->latest('updated_at')->get();
        $totalPendapatan = $orders->sum('total_harga');
        $jumlahSelesai = $orders->count();
        $rataRata = $jumlahSelesai > 0 ? $totalPendapatan / $jumlahSelesai : 0;

        return view('admin.laporan.penjualan', compact('orders', 'totalPendapatan', 'jumlahSelesai', 'rataRata'));
    }

    /**
     * 7. Export PDF Laporan
     */
    public function exportLaporanPDF(Request $request)
    {
        $query = Order::where('status', 'selesai');
        
        if ($request->filled('tgl_mulai') && $request->filled('tgl_selesai')) {
            $query->whereBetween('updated_at', [$request->tgl_mulai . ' 00:00:00', $request->tgl_selesai . ' 23:59:59']);
            $periode = $request->tgl_mulai . " s/d " . $request->tgl_selesai;
        } else {
            $query->whereMonth('updated_at', date('m'))->whereYear('updated_at', date('Y'));
            $periode = date('F Y');
        }

        $orders = $query->latest('updated_at')->get();
        $totalPendapatan = $orders->sum('total_harga');
        $jumlahSelesai = $orders->count();
        $rataRata = $jumlahSelesai > 0 ? $totalPendapatan / $jumlahSelesai : 0;

        $pdf = Pdf::loadView('admin.pdf.laporan_bisnis', compact('orders', 'totalPendapatan', 'jumlahSelesai', 'rataRata', 'periode'));
        return $pdf->download('Laporan-Kopi-NTT-' . date('Ymd') . '.pdf');
    }

    public function show($id) {
        $order = Order::with(['items.product', 'user'])->findOrFail($id);
        return view('admin.orders.show', compact('order'));
    }

    public function laporanProduk() { 
        return view('admin.laporan.produk', ['kopis' => Product::all()]); 
    }

    public function laporanPelanggan() { 
        return view('admin.laporan.pelanggan', ['pelanggan' => User::where('role', 'pelanggan')->latest()->get()]); 
    }
}