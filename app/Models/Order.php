<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * Kolom yang diizinkan untuk diisi secara massal.
     */
    protected $fillable = [
        'user_id', 
        'nama_penerima', 
        'total_harga', 
        'status', 
        'alamat_pengiriman',
        'nomor_hp',
        'snap_token' // Penting untuk Midtrans
    ];

    /**
     * Relasi ke OrderItem (Satu pesanan memiliki banyak item produk).
     */
    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    /**
     * Relasi ke User (Satu pesanan dimiliki oleh satu user).
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * ACCESSOR: Mengubah status menjadi huruf kecil saat dipanggil.
     * Memudahkan pengecekan string di kodingan.
     */
    public function getStatusAttribute($value)
    {
        return trim(strtolower($value));
    }

    /**
     * Helper untuk mengecek apakah pesanan sudah selesai.
     */
    public function isSelesai()
    {
        return $this->status === 'selesai';
    }

    /**
     * Helper untuk mengecek apakah pesanan sudah dibayar.
     * Disesuaikan dengan enum: 'diproses', 'dikirim', 'selesai'.
     */
    public function isPaid()
    {
        $statusSudahBayar = [
            'diproses', 
            'dikirim', 
            'selesai'
        ];
        
        return in_array($this->status, $statusSudahBayar);
    }

    /**
     * Helper untuk mengecek apakah pesanan masih menunggu pembayaran.
     */
    public function isPending()
    {
        return $this->status === 'menunggu pembayaran';
    }
}