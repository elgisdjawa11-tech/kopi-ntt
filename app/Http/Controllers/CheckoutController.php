<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\DB;
use Exception;

class CheckoutController extends Controller
{
    public function __construct()
    {
        // Konfigurasi Midtrans diambil dari file .env atau Variables Railway
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized = env('MIDTRANS_IS_SANITIZED', true);
        Config::$is3ds = env('MIDTRANS_IS_3DS', true);
    }

    /**
     * Menampilkan halaman formulir pengiriman (Step 1)
     */
    public function showPengiriman($id)
    {
        $product = Product::findOrFail($id);
        return view('pengiriman', compact('product'));
    }

    /**
     * Memproses data pengiriman dan mendaftarkan transaksi ke Midtrans (Step 2)
     */
    public function process(Request $request)
    {
        $cart = session()->get('cart');
        $totalHarga = 0;

        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'hp' => 'required|numeric|digits_between:10,15',
        ]);

        // 1. CEK STOK & HITUNG TOTAL
        if ($cart && count($cart) > 0) {
            foreach ($cart as $id => $details) {
                $product = Product::find($id);
                if (!$product || $product->stok < $details['quantity']) {
                    return redirect()->route('cart.index')->with('error', "Stok " . ($product ? $product->nama_kopi : 'Produk') . " tidak cukup.");
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
            return redirect()->route('home')->with('error', 'Keranjang belanja kosong.');
        }

        // 2. TRANSAKSI DATABASE
        try {
            $order = DB::transaction(function () use ($request, $cart, $totalHarga) {
                $newOrder = Order::create([
                    'user_id' => auth()->id(),
                    'nama_penerima' => $request->nama,
                    'alamat_pengiriman' => $request->alamat,
                    'nomor_hp' => $request->hp,
                    'total_harga' => $totalHarga,
                    'status' => 'Menunggu Pembayaran' 
                ]);

                if ($cart && count($cart) > 0) {
                    foreach ($cart as $id => $details) {
                        OrderItem::create([
                            'order_id'     => $newOrder->id,
                            'product_id'   => $id,
                            'jumlah'       => $details['quantity'],
                            'harga_satuan' => $details['price'], 
                        ]);
                    }
                    session()->forget('cart');
                } else {
                    OrderItem::create([
                        'order_id'     => $newOrder->id,
                        'product_id'   => $request->product_id,
                        'jumlah'       => 1,
                        'harga_satuan' => $totalHarga, 
                    ]);
                }

                return $newOrder;
            });

            // 3. KONFIGURASI MIDTRANS
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

            // Dapatkan Snap Token dari Midtrans
            $snapToken = Snap::getSnapToken($params);
            
            // Simpan token ke database
            $order->update(['snap_token' => $snapToken]);

            // Alihkan ke halaman pembayaran
            return redirect()->route('pembayaran', $order->id);

        } catch (Exception $e) {
            return back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan halaman tombol bayar Midtrans (Step 3)
     */
    public function pembayaran($id)
    {
        // Pastikan relasi items dan product dimuat
        $order = Order::with('items.product')->findOrFail($id);
        return view('pembayaran', compact('order'));
    }

    /**
     * Menangani laporan pembayaran otomatis dari server Midtrans
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
                    // Update status ke 'Diproses' agar Admin tahu pesanan sudah dibayar
                    $order->update(['status' => 'Diproses']);
                }
            }
        }
    }

    /**
     * Mengubah status pesanan menjadi Selesai (Oleh Kurir/Admin)
     */
    public function completeOrder($id)
    {
        $order = Order::findOrFail($id);

        if ($order->status !== 'Selesai') {
            $order->update(['status' => 'Selesai']);
            return redirect()->back()->with('success', 'Pesanan selesai! Terima kasih.');
        }

        return redirect()->back()->with('info', 'Pesanan sudah berstatus selesai.');
    }
}