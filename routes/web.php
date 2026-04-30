<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CartController; 
use App\Http\Controllers\AuthController; 
use App\Http\Controllers\Admin\OrderController; 
use App\Http\Controllers\Admin\ProductManagementController;
use App\Http\Controllers\PengirimController;

/*
|--------------------------------------------------------------------------
| Web Routes - Sistem Informasi Penjualan Kopi NTT
|--------------------------------------------------------------------------
*/

// 1. OTENTIKASI & AKSES UMUM
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// RUTE REGISTRASI TERPISAH (Agar Judul & Role Berbeda)
Route::get('/register/pelanggan', [AuthController::class, 'regPelanggan'])->name('register.pelanggan');
Route::get('/register/pengirim', [AuthController::class, 'regPengirim'])->name('register.pengirim');
Route::get('/register/admin', [AuthController::class, 'regAdmin'])->name('register.admin');
Route::get('/register/pemilik', [AuthController::class, 'regPemilik'])->name('register.pemilik');

// Proses Registrasi Tunggal (Menggunakan Input Hidden 'role' di View)
Route::post('/register/proses', [AuthController::class, 'register'])->name('register.post');

// MIDTRANS CALLBACK (Khusus untuk komunikasi server-ke-server)
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

    // Checkout & Pembayaran
    Route::get('/checkout/pengiriman/{id}', [CheckoutController::class, 'showPengiriman'])->name('checkout.pengiriman');
    Route::post('/checkout/proses', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/pembayaran/{id}', [CheckoutController::class, 'pembayaran'])->name('pembayaran');

    // Riwayat & Lacak Pesanan (Dipindah ke dalam Auth agar tidak muncul sembarangan)
    Route::get('/riwayat-pesanan', [ProductController::class, 'riwayatPesanan'])->name('riwayat.pesanan');
    Route::get('/pesanan/lacak/{id}', [ProductController::class, 'riwayat'])->name('pesanan.lacak');
});

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
    
    // FITUR UTAMA: Cek Status Pembayaran Manual ke Midtrans
    Route::get('/orders/{id}/cek-status', [OrderController::class, 'cekStatusPembayaran'])->name('orders.cek-status');

    // Alur Bisnis
    Route::post('/orders/{id}/konfirmasi', [OrderController::class, 'konfirmasiPembayaran'])->name('orders.konfirmasi');
    Route::post('/orders/{id}/kirim', [OrderController::class, 'kirimBarang'])->name('orders.kirim');
    Route::post('/orders/{id}/upload-bukti', [OrderController::class, 'uploadBukti'])->name('orders.uploadBukti');
    Route::match(['get', 'post'], '/orders/update/{id}/{status}', [OrderController::class, 'updateStatus'])->name('orders.update');

    // Laporan Bisnis
    Route::prefix('laporan')->name('laporan.')->group(function() {
        Route::get('/penjualan', [OrderController::class, 'laporanPenjualan'])->name('penjualan');
        Route::get('/produk', [OrderController::class, 'laporanProduk'])->name('produk');
        Route::get('/pelanggan', [OrderController::class, 'laporanPelanggan'])->name('pelanggan');
        Route::get('/pdf', [OrderController::class, 'exportLaporanPDF'])->name('pdf');
    });

    Route::resource('products', ProductManagementController::class);
});