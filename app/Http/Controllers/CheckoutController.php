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
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
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
        $ongkir = $request->input('ongkir', 0); // Ambil ongkir dari form

        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            'hp' => 'required|numeric|digits_between:10,15',
            'kota_tujuan' => 'required|string', // Validasi kota tujuan wajib diisi
            'ongkir' => 'required|numeric'
        ], [
            'kota_tujuan.required' => 'Silakan pilih Kabupaten/Kota tujuan di NTT.',
        ]);

        // Proteksi Tambahan: Pastikan ongkir tidak 0 (untuk memastikan user memilih kota dari dropdown)
        if ($ongkir <= 0) {
            return back()->with('error', 'Mohon maaf, saat ini kami hanya melayani pengiriman di wilayah Nusa Tenggara Timur. Silakan pilih kota tujuan Anda.');
        }

        // 1. CEK STOK & HITUNG TOTAL PRODUK
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

        // Total Akhir = Harga Produk + Ongkir
        $grandTotal = $totalHarga + $ongkir;

        // 2. TRANSAKSI DATABASE
        try {
            $order = DB::transaction(function () use ($request, $cart, $grandTotal, $ongkir) {
                $newOrder = Order::create([
                    'user_id' => auth()->id(),
                    'nama_penerima' => $request->nama,
                    'alamat_pengiriman' => $request->alamat,
                    'kabupaten' => $request->kota_tujuan, // Simpan nama kabupaten
                    'nomor_hp' => $request->hp,
                    'total_harga' => $grandTotal,
                    'ongkir' => $ongkir,
                    'status' => 'menunggu pembayaran' 
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
                        'harga_satuan' => $request->total_produk ?? $grandTotal - $ongkir, 
                    ]);
                }

                return $newOrder;
            });

            // 3. KONFIGURASI MIDTRANS
            $midtransOrderId = $order->id . '-' . time();
            $params = [
                'transaction_details' => [
                    'order_id' => $midtransOrderId, 
                    'gross_amount' => (int) $grandTotal, // Total sudah termasuk Ongkir
                ],
                'customer_details' => [
                    'first_name' => $request->nama,
                    'phone' => $request->hp,
                ],
            ];

            // Dapatkan Snap Token dari Midtrans
            $snapToken = Snap::getSnapToken($params);
            
            // Perbaikan Otomatis: Pastikan kolom midtrans_id ada sebelum update
            if (!\Illuminate\Support\Facades\Schema::hasColumn('orders', 'midtrans_id')) {
                \Illuminate\Support\Facades\Schema::table('orders', function (\Illuminate\Database\Schema\Blueprint $table) {
                    $table->string('midtrans_id')->nullable()->after('snap_token');
                });
            }

            // Simpan token dan midtrans_id ke database
            $order->update([
                'snap_token' => $snapToken,
                'midtrans_id' => $midtransOrderId
            ]);

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
     * Menangani laporan pembayaran otomatis dari server Midtrans (Webhook)
     */
    public function callback(Request $request)
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        
        // Ambil data yang dikirim Midtrans
        $orderIdMidtrans = $request->order_id;
        $statusCode = $request->status_code;
        $grossAmount = $request->gross_amount;
        $signatureKey = $request->signature_key;
        $transactionStatus = $request->transaction_status;
        $type = $request->payment_type;

        // 1. Verifikasi Keaslian Data
        $isValid = false;

        if ($signatureKey) {
            // Jika dipanggil oleh Server Midtrans (Webhook Asli), verifikasi Signature
            $hashed = hash("sha512", $orderIdMidtrans . $statusCode . $grossAmount . $serverKey);
            if ($hashed === $signatureKey) {
                $isValid = true;
            }
        } else {
            // Jika dipanggil oleh Browser (Localhost/JS), verifikasi langsung ke API Midtrans
            \Midtrans\Config::$serverKey = $serverKey;
            \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
            try {
                $status = \Midtrans\Transaction::status($orderIdMidtrans);
                if ($status->transaction_status == $transactionStatus) {
                    $isValid = true;
                }
            } catch (\Exception $e) {
                $isValid = false;
            }
        }

        if ($isValid) {
            // Cari pesanan berdasarkan midtrans_id atau order_id murni
            $order = Order::where('midtrans_id', $orderIdMidtrans)
                          ->orWhere('id', explode('-', $orderIdMidtrans)[0])
                          ->first();
            if ($order) {
                if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
                    // Langsung set ke 'diproses' agar sinkron dengan Lacak Pesanan Pelanggan
                    // dan menghilangkan kebutuhan tombol Konfirmasi di Admin.
                    $order->update(['status' => 'diproses']);
                } else if ($transactionStatus == 'pending') {
                    $order->update(['status' => 'menunggu pembayaran']);
                } else if ($transactionStatus == 'deny' || $transactionStatus == 'failure') {
                    $order->update(['status' => 'gagal']);
                } else if ($transactionStatus == 'expire') {
                    $order->update(['status' => 'kadaluarsa']);
                } else if ($transactionStatus == 'cancel') {
                    $order->update(['status' => 'dibatalkan']);
                }

                return response()->json(['message' => 'Success']);
            }
        }

        return response()->json(['message' => 'Verification failed'], 403);
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