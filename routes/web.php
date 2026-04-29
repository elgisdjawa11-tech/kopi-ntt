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

// MIDTRANS CALLBACK (Wajib di luar middleware auth agar bisa diakses server Midtrans)
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

    // Checkout & Pengiriman (Pindahkan ke Controller agar rapi)
    Route::get('/checkout/pengiriman/{id}', [CheckoutController::class, 'showPengiriman'])->name('checkout.pengiriman');
    Route::post('/checkout/proses', [CheckoutController::class, 'process'])->name('checkout.process');
    
    // Pembayaran
    Route::get('/pembayaran/{id}', [CheckoutController::class, 'pembayaran'])->name('pembayaran');

    // Riwayat Belanja User
    Route::get('/riwayat-pesanan', [ProductController::class, 'riwayatPesanan'])->name('riwayat.pesanan');
});

// Lacak Pesanan (Akses Publik)
Route::get('/pesanan/lacak/{id}', [ProductController::class, 'riwayat'])->name('pesanan.lacak');

// 3. ENTITAS PENGIRIM (KURIR)
Route::middleware(['auth', 'role:pengirim'])->prefix('pengirim')->name('pengirim.')->group(function() {
    Route::get('/dashboard', [PengirimController::class, 'index'])->name('index');
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

    // Alur Bisnis
    Route::post('/orders/{id}/konfirmasi', [OrderController::class, 'konfirmasiPembayaran'])->name('orders.konfirmasi');
    Route::post('/orders/{id}/kirim', [OrderController::class, 'kirimBarang'])->name('orders.kirim');
    Route::post('/orders/{id}/upload-bukti', [OrderController::class, 'uploadBukti'])->name('orders.uploadBukti');

    // FITUR CEK PESANAN BARU (UPDATE STATUS KE 'Diproses')
    Route::get('/check-new-orders', function() {
        // Cek pesanan yang statusnya 'Diproses' (sudah dibayar via Midtrans) dalam 2 menit terakhir
        $newOrder = Order::where('status', 'Diproses')
                    ->where('updated_at', '>=', now()->subMinutes(2))
                    ->exists();
        return response()->json(['new_order' => $newOrder]);
    })->name('check_orders');

    // Laporan Bisnis
    Route::prefix('laporan')->name('laporan.')->group(function() {
        Route::get('/penjualan', [OrderController::class, 'laporanPenjualan'])->name('penjualan');
        Route::get('/produk', [OrderController::class, 'laporanProduk'])->name('produk');
        Route::get('/pelanggan', [OrderController::class, 'laporanPelanggan'])->name('pelanggan');
        Route::get('/pdf', [OrderController::class, 'exportLaporanPDF'])->name('pdf');
    });

    Route::get('/redirect-laporan', function() {
        return redirect()->route('admin.laporan.penjualan');
    })->name('laporan_redirect');

    Route::resource('products', ProductManagementController::class);
});