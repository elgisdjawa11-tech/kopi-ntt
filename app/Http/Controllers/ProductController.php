<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Menampilkan katalog produk kopi NTT.
     */
    public function index()
    {
        $kopis = Product::all(); 
        
        $lastOrder = null;
        if (auth()->check()) {
            $lastOrder = Order::where('user_id', auth()->id())->latest()->first();
        }
        
        return view('katalog', compact('kopis', 'lastOrder'));
    }

    /**
     * Menampilkan daftar semua pesanan milik pelanggan yang sedang login.
     * Filter: Hanya mengambil data yang user_id-nya cocok.
     */
    public function riwayatPesanan()
    {
        // Keamanan: Jika tidak login, tendang ke halaman login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $orders = Order::where('user_id', auth()->id())
                    ->orderBy('created_at', 'desc')
                    ->get();

        return view('riwayat', compact('orders'));
    }

    /**
     * Menampilkan halaman Lacak Pesanan (Timeline).
     * Keamanan: Ditambah filter where agar user tidak bisa mengintip ID pesanan orang lain lewat URL.
     */
    public function riwayat($id)
    {
        if ($id == 0) {
            return redirect()->route('home')->with('info', 'ID Pesanan tidak valid.');
        }

        // PERBAIKAN: Harus cek user_id juga agar tidak bisa intip punya orang
        $order = Order::with('items.product')
                    ->where('user_id', auth()->id()) // Tambahkan ini agar aman
                    ->where('id', $id)
                    ->first();

        // Jika data tidak ditemukan (misal ID milik orang lain), kembalikan ke riwayat
        if (!$order) {
            return redirect()->route('riwayat.pesanan')->with('error', 'Pesanan tidak ditemukan atau Anda tidak memiliki akses.');
        }

        return view('lacak_pesanan', compact('order'));
    }

    /**
     * Menampilkan detail satu produk kopi.
     */
    public function show($id)
    {
        $kopi = Product::findOrFail($id);
        return view('detail_kopi', compact('kopi'));
    }
}