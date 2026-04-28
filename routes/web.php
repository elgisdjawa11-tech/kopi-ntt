<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CartController; 
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\Admin\OrderController; 
use App\Http\Controllers\Admin\ProductManagementController;
use App\Http\Controllers\PengirimController;
use App\Models\Product;
use App\Models\Order;

/*
|--------------------------------------------------------------------------
| Web Routes - Sistem Informasi Penjualan Kopi NTT
|--------------------------------------------------------------------------
*/

// 1. OTENTIKASI & AKSES UMUM
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// MIDTRANS CALLBACK
Route::post('/midtrans-callback', [CheckoutController::class, 'callback'])->name('midtrans.callback');

// CATALOG & HOME
Route::get('/', [ProductController::class, 'index'])->name('home');

// 2. ENTITAS PELANGGAN (Wajib Login)
Route::middleware(['auth'])->group(function() {
    
    // Keranjang
    Route::prefix('keranjang')->name('cart.')->group(function() {
        Route::get('/', [CartController::class, 'index'])->name('index'); 
        Route::post('/tambah/{id}', [CartController::class, 'add'])->name('add'); 
        Route::delete('/hapus/{id}', [CartController::class, 'remove'])->name('remove'); 
    });

    // Checkout & Pengiriman
    Route::get('/checkout/pengiriman/{id}', function($id) {
        $product = Product::findOrFail($id);
        return view('pengiriman', compact('product'));
    })->name('checkout.pengiriman');

    Route::post('/checkout/proses', [CheckoutController::class, 'process'])->name('checkout.process');
    
    // Pembayaran
    Route::get('/pembayaran/{id}', function($id) {
        $order = Order::findOrFail($id);
        return view('pembayaran', compact('order'));
    })->name('pembayaran');

    // Riwayat Belanja User
    Route::get('/riwayat-pesanan', [ProductController::class, 'riwayatPesanan'])->name('riwayat.pesanan');
});

// Lacak Pesanan (Akses Publik)
Route::get('/pesanan/lacak/{id}', [ProductController::class, 'riwayat'])->name('pesanan.lacak');


// 3. ENTITAS PENGIRIM (KURIR)
Route::middleware(['auth', 'role:pengirim'])->prefix('pengirim')->name('pengirim.')->group(function() {
    Route::get('/dashboard', [PengirimController::class, 'index'])->name('index');
    
    // Konfirmasi Tiba (Upload Foto & Ubah Status ke Selesai)
    Route::post('/konfirmasi/{id}', [PengirimController::class, 'konfirmasiTiba'])->name('konfirmasi');
});


// 4. ENTITAS ADMIN & PEMILIK
Route::middleware(['auth', 'role:admin,pemilik'])->prefix('admin')->name('admin.')->group(function() {
    
    // Dashboard & Order Management
    Route::get('/dashboard', [OrderController::class, 'index'])->name('dashboard');
    Route::get('/orders', [OrderController::class, 'listOrders'])->name('orders.index');
    Route::get('/orders/show/{id}', [OrderController::class, 'show'])->name('orders.show');
    
    // Update Status Manual
    Route::match(['get', 'post'], '/orders/update/{id}/{status}', [OrderController::class, 'updateStatus'])->name('orders.update');

    // --- ALUR BISNIS REVISI ---
    
    // Tahap 1: Konfirmasi Pembayaran
    Route::post('/orders/{id}/konfirmasi', [OrderController::class, 'konfirmasiPembayaran'])->name('orders.konfirmasi');

    // Tahap 2: Kirim Barang (Pemicu muncul di dashboard kurir)
    Route::post('/orders/{id}/kirim', [OrderController::class, 'kirimBarang'])->name('orders.kirim');

    // Tahap 3: Upload Bukti
    Route::post('/orders/{id}/upload-bukti', [OrderController::class, 'uploadBukti'])->name('orders.uploadBukti');

    // --- FITUR CEK PESANAN BARU (NOTIFIKASI) ---
    Route::get('/check-new-orders', function() {
        // Hanya cek pesanan yang statusnya sudah 'Pembayaran Berhasil' dalam 2 menit terakhir
        $newOrder = Order::where('status', 'Pembayaran Berhasil')
                    ->where('updated_at', '>=', now()->subMinutes(2))
                    ->exists();
        return response()->json(['new_order' => $newOrder]);
    })->name('check_orders');

    // --- LAPORAN BISNIS (HANYA STATUS SELESAI) ---
    Route::prefix('laporan')->name('laporan.')->group(function() {
        Route::get('/penjualan', [OrderController::class, 'laporanPenjualan'])->name('penjualan');
        Route::get('/produk', [OrderController::class, 'laporanProduk'])->name('produk');
        Route::get('/pelanggan', [OrderController::class, 'laporanPelanggan'])->name('pelanggan');
        Route::get('/pdf', [OrderController::class, 'exportLaporanPDF'])->name('pdf');
    });

    // Redirect Laporan
    Route::get('/redirect-laporan', function() {
        return redirect()->route('admin.laporan.penjualan');
    })->name('laporan_redirect');

    // Manajemen Produk Kopi
    Route::resource('products', ProductManagementController::class);
});