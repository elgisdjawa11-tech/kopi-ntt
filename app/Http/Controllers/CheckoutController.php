<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function __construct()
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        Config::$isSanitized = env('MIDTRANS_IS_SANITIZED');
        Config::$is3ds = env('MIDTRANS_IS_3DS');
    }

    /**
     * 1. Proses Simpan Pesanan (Status Awal: Menunggu Pembayaran)
     */
    public function process(Request $request)
    {
        $cart = session()->get('cart');
        $totalHarga = 0;

        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required',
            'hp' => 'required',
        ]);

        // CEK STOK
        if ($cart && count($cart) > 0) {
            foreach ($cart as $id => $details) {
                $product = Product::find($id);
                if (!$product || $product->stok < $details['quantity']) {
                    return redirect()->route('cart.index')->with('error', 
                        "Pesanan ditolak! Stok " . ($product ? $product->nama_kopi : 'Produk') . " tidak cukup."
                    );
                }
                $totalHarga += $details['price'] * $details['quantity'];
            }
        } elseif ($request->has('product_id')) {
            $product = Product::findOrFail($request->product_id);
            if ($product->stok < 1) {
                return back()->with('error', 'Maaf, stok kopi ini sedang habis.');
            }
            $totalHarga = $product->harga;
        } else {
            return redirect()->route('home')->with('error', 'Pesanan tidak valid.');
        }

        return DB::transaction(function () use ($request, $cart, $totalHarga) {
            /**
             * LOGIKA REVISI: 
             * Status awal diubah menjadi 'Menunggu Verifikasi' atau 'Pending'.
             * Jangan diset 'Pembayaran Berhasil' di sini karena pelanggan belum bayar.
             */
            $order = Order::create([
                'user_id' => auth()->id(),
                'nama_penerima' => $request->nama,
                'alamat_pengiriman' => $request->alamat,
                'nomor_hp' => $request->hp,
                'total_harga' => $totalHarga,
                'status' => 'Menunggu Verifikasi' // Berubah dari 'Pembayaran Berhasil'
            ]);

            if ($cart && count($cart) > 0) {
                foreach ($cart as $id => $details) {
                    OrderItem::create([
                        'order_id'     => $order->id,
                        'product_id'   => $id,
                        'jumlah'       => $details['quantity'],
                        'harga_satuan' => $details['price'], 
                    ]);
                }
                session()->forget('cart');
            } else {
                OrderItem::create([
                    'order_id'     => $order->id,
                    'product_id'   => $request->product_id,
                    'jumlah'       => 1,
                    'harga_satuan' => $totalHarga, 
                ]);
            }

            $params = [
                'transaction_details' => [
                    'order_id' => $order->id . '-' . time(), 
                    'gross_amount' => (int) $totalHarga,
                ],
                'customer_details' => [
                    'first_name' => $request->nama,
                    'phone' => $request->hp,
                ],
            ];

            try {
                $snapToken = Snap::getSnapToken($params);
                $order->update(['snap_token' => $snapToken]);
                return redirect()->route('pembayaran', $order->id);
            } catch (\Exception $e) {
                return back()->with('error', 'Gagal terhubung ke Midtrans: ' . $e->getMessage());
            }
        });
    }

    /**
     * 2. Callback Midtrans
     * HANYA DI SINI status berubah ke "Pembayaran Berhasil" setelah duit masuk.
     */
    public function callback(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                $order_id = explode('-', $request->order_id)[0];
                $order = Order::find($order_id);
                
                if ($order) {
                    // BARU DI SINI status berubah, sehingga Admin bisa melihatnya
                    $order->update(['status' => 'Pembayaran Berhasil']);
                }
            }
        }
    }

    /**
     * 3. Selesaikan Pesanan
     */
    public function completeOrder($id)
    {
        $order = Order::findOrFail($id);

        if ($order->status !== 'Selesai') {
            $order->update(['status' => 'Selesai']);
            return redirect()->back()->with('success', 'Pesanan selesai! Barang telah diterima pelanggan.');
        }

        return redirect()->back()->with('info', 'Pesanan sudah berstatus selesai.');
    }
}