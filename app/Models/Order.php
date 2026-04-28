<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * Kolom yang diizinkan untuk diisi secara massal (Mass Assignment).
     */
    protected $fillable = [
        'user_id', 
        'nama_penerima', 
        'total_harga', 
        'status', 
        'alamat_pengiriman',
        'nomor_hp',
        'bukti_bayar',      
        'foto_penerimaan',
        'snap_token'
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
     * ACCESSOR: Mengubah status menjadi huruf kecil & menghapus spasi.
     * Ini sangat penting agar status "Menunggu Verifikasi" dari database 
     * bisa dibaca sebagai "menunggu verifikasi" di Controller dan View.
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
     * Helper untuk mengecek apakah pesanan sudah dibayar atau masuk tahap verifikasi.
     * Kita tambahkan 'menunggu verifikasi' di sini agar tombol konfirmasi muncul.
     */
    public function isPaid()
    {
        $statusValid = [
            'menunggu verifikasi', 
            'pembayaran berhasil', 
            'settlement', 
            'capture'
        ];
        
        return in_array($this->status, $statusValid);
    }
}