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
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        Config::$isSanitized = env('MIDTRANS_IS_SANITIZED');
        Config::$is3ds = env('MIDTRANS_IS_3DS');
    }

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
                    'status' => 'Menunggu Pembayaran' // Sesuai dengan Enum di Migration
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

            $snapToken = Snap::getSnapToken($params);
            $order->update(['snap_token' => $snapToken]);

            return redirect()->route('pembayaran', $order->id);

        } catch (Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function callback(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        $hashed = hash("sha512", $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed == $request->signature_key) {
            if ($request->transaction_status == 'capture' || $request->transaction_status == 'settlement') {
                $order_id = explode('-', $request->order_id)[0];
                $order = Order::find($order_id);
                
                if ($order) {
                    // Gunakan 'Diproses' karena ada di Enum Migration kita
                    $order->update(['status' => 'Diproses']);
                }
            }
        }
    }

    public function completeOrder($id)
    {
        $order = Order::findOrFail($id);

        if ($order->status !== 'Selesai') {
            $order->update(['status' => 'Selesai']);
            return redirect()->back()->with('success', 'Pesanan selesai!');
        }

        return redirect()->back()->with('info', 'Pesanan sudah selesai.');
    }
}