<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PengirimController extends Controller
{
    /**
     * 1. Dashboard Pengirim
     * Menampilkan daftar pesanan yang statusnya 'Dikirim'.
     * KUNCI: Kurir hanya melihat barang yang sedang dalam perjalanan.
     */
    public function index()
    {
        // Mengambil pesanan dengan status 'Dikirim' (Tugas Aktif)
        $orders = Order::with('items.product')
                    ->where('status', 'dikirim')
                    ->latest()
                    ->get();

        // Statistik untuk Dashboard Kurir
        $stats = [
            'total_tugas' => $orders->count(),
            'tugas_selesai' => Order::where('status', 'selesai')->count()
        ];

        return view('pengirim.index', compact('orders', 'stats'));
    }

    /**
     * 3. Riwayat Pengiriman
     * Menampilkan daftar pesanan yang sudah sukses diantar (Status Selesai).
     */
    public function history()
    {
        $orders = Order::with('items.product')
                    ->where('status', 'selesai')
                    ->latest()
                    ->paginate(10);

        return view('pengirim.history', compact('orders'));
    }

    /**
     * 2. Konfirmasi Tiba (Revisi Utama)
     * Mengubah status menjadi 'Selesai'. 
     * Langkah ini adalah pemicu agar transaksi muncul di Laporan Penjualan Admin.
     */
    public function konfirmasiTiba(Request $request, $id)
    {
        // Validasi: Wajib mengunggah foto bukti penerimaan barang
        $request->validate([
            'bukti_foto' => 'required|image|mimes:jpg,png,jpeg|max:2048'
        ], [
            'bukti_foto.required' => 'Wajib mengunggah foto bukti penerimaan barang.',
        ]);

        $order = Order::findOrFail($id);

        try {
            DB::transaction(function () use ($request, $order) {
                if ($request->hasFile('bukti_foto')) {
                    // Hapus foto lama jika ada
                    if ($order->foto_penerimaan) {
                        Storage::disk('public')->delete($order->foto_penerimaan);
                    }

                    // Simpan foto bukti baru
                    $path = $request->file('bukti_foto')->store('bukti_penerimaan', 'public');
                    
                    // Update status jadi 'Selesai'
                    $order->update([
                        'foto_penerimaan' => $path,
                        'status' => 'Selesai' 
                    ]);
                }
            });

            return back()->with('success', 'Pesanan #ORD-'.$id.' berhasil diselesaikan. Bukti foto telah tersimpan!');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}