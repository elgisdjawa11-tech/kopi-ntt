<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database.
     */
    protected $table = 'order_items';

    /**
     * Kolom yang diizinkan untuk diisi secara massal (Mass Assignment).
     */
    protected $fillable = [
        'order_id',
        'product_id',
        'jumlah',
        'harga_satuan',
    ];

    /**
     * Casting tipe data agar lebih konsisten saat perhitungan matematika.
     */
    protected $casts = [
        'jumlah' => 'integer',
        'harga_satuan' => 'integer',
    ];

    /**
     * Relasi ke Product (Kunci Utama Pengurangan Stok).
     * Satu baris item pesanan merujuk ke satu produk kopi.
     * Digunakan di Controller: $item->product->decrement('stok', $item->jumlah)
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Relasi ke Order.
     * Menghubungkan item ini kembali ke data induk pesanan.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    /**
     * Fungsi pembantu (Helper) untuk menghitung subtotal per item.
     * Bisa digunakan di View: Rp {{ number_format($item->subtotal()) }}
     */
    public function subtotal()
    {
        return $this->jumlah * $this->harga_satuan;
    }
}