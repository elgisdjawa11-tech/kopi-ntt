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
        // Mengambil pesanan dengan status 'Dikirim'
        // Status ini diset oleh Admin saat barang keluar gudang
        $orders = Order::with('items.product')
                    ->where('status', 'Dikirim')
                    ->latest()
                    ->get();

        return view('pengirim.index', compact('orders'));
    }

    /**
     * 2. Konfirmasi Tiba (Revisi Utama)
     * Mengubah status menjadi 'Selesai'. 
     * Langkah ini adalah pemicu agar transaksi muncul di Laporan Penjualan Admin.
     */
    public function konfirmasiTiba(Request $request, $id)
    {
        // Validasi unggah foto sebagai bukti barang sampai di lokasi
        $request->validate([
            'bukti_foto' => 'required|image|mimes:jpg,png,jpeg|max:2048'
        ], [
            'bukti_foto.required' => 'Wajib mengunggah foto bukti penerimaan barang.',
        ]);

        $order = Order::findOrFail($id);

        try {
            DB::transaction(function () use ($request, $order) {
                if ($request->hasFile('bukti_foto')) {
                    // 1. Hapus foto lama jika ada untuk menghemat ruang penyimpanan
                    if ($order->foto_penerimaan) {
                        Storage::disk('public')->delete($order->foto_penerimaan);
                    }

                    // 2. Simpan foto bukti baru
                    $path = $request->file('bukti_foto')->store('bukti_penerimaan', 'public');
                    
                    /**
                     * 3. Update status jadi 'Selesai'
                     * LOGIKA REVISI: Begitu status 'Selesai', barulah Admin bisa melihat 
                     * transaksi ini di Laporan Penjualan dan total pendapatan akan bertambah.
                     */
                    $order->update([
                        'foto_penerimaan' => $path,
                        'status' => 'Selesai' 
                    ]);
                }
            });

            return back()->with('success', 'Pesanan #ORD-'.$id.' berhasil diselesaikan. Data sudah masuk ke laporan penjualan admin!');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }
}