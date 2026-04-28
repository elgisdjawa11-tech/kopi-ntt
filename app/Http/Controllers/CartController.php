<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * Menampilkan halaman keranjang belanja
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('keranjang', compact('cart'));
    }

    /**
     * Menambah produk ke keranjang via AJAX (Tanpa Refresh)
     * DILENGKAPI VALIDASI STOK (REVISI DOSEN)
     */
    public function add($id)
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        // 1. Cek kuota/stok yang tersedia sebelum diproses
        $current_qty_in_cart = isset($cart[$id]) ? $cart[$id]['quantity'] : 0;
        $next_qty = $current_qty_in_cart + 1;

        // REVISI DOSEN: Jika jumlah pesanan melebihi stok, kirim respon error
        if ($next_qty > $product->stok) {
            return response()->json([
                'status' => 'error',
                'message' => 'Maaf, kuota pesanan ditolak! Stok yang tersedia hanya ' . $product->stok . ' pcs.',
                'cart_count' => count($cart)
            ], 422); // 422 adalah kode Unprocessable Entity (Gagal diproses)
        }

        // 2. Jika stok masih cukup, lanjutkan proses simpan ke session
        if(isset($cart[$id])) {
            $cart[$id]['quantity']++;
        } else {
            $cart[$id] = [
                "name" => $product->nama_kopi,
                "quantity" => 1,
                "price" => $product->harga,
                "photo" => $product->foto,
                "origin" => $product->daerah_asal
            ];
        }

        session()->put('cart', $cart);

        return response()->json([
            'status' => 'success',
            'message' => 'Produk berhasil ditambah ke keranjang!',
            'cart_count' => count($cart)
        ]);
    }

    /**
     * Menghapus produk dari keranjang
     */
    public function remove($id)
    {
        $cart = session()->get('cart', []);
        
        if(isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang.');
    }
}