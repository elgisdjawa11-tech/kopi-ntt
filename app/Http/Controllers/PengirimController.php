<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PengirimController extends Controller
{
    /**
     * Dashboard Pengirim
     */
    public function index()
    {
        // Pesanan yang sedang dikirim
        $orders = Order::with('items.product')
            ->where('status', 'dikirim')
            ->latest()
            ->get();

        // Statistik dashboard
        $stats = [
            'total_tugas' => $orders->count(),
            'tugas_selesai' => Order::where('status', 'selesai')->count()
        ];

        return view('pengirim.index', compact('orders', 'stats'));
    }

    /**
     * Riwayat Pengiriman
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
     * Konfirmasi Pesanan Sampai
     */
    public function konfirmasiTiba(Request $request, $id)
    {
        // Validasi upload gambar
        $request->validate([
            'bukti_foto' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ], [
            'bukti_foto.required' => 'Foto bukti wajib diunggah.',
            'bukti_foto.image' => 'File harus berupa gambar.',
        ]);

        // Cari order
        $order = Order::findOrFail($id);

        try {

            DB::beginTransaction();

            // Upload foto
            if ($request->hasFile('bukti_foto')) {

                // Hapus foto lama jika ada
                if ($order->foto_penerimaan) {

                    Storage::disk('public')
                        ->delete($order->foto_penerimaan);
                }

                // Simpan foto baru
                $path = $request->file('bukti_foto')
                    ->store('bukti_penerimaan', 'public');

                // Update database
                $order->foto_penerimaan = $path;
            }

            // UBAH STATUS
            $order->status = 'selesai';

            // Simpan
            $order->save();

            DB::commit();

            return redirect()
                ->back()
                ->with('success', 'Pesanan berhasil dikonfirmasi selesai.');

        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}