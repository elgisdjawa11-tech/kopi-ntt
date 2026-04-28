<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    // Nama tabel di database kamu (HeidiSQL)
    protected $table = 'products';

    /**
     * Mass Assignment: Daftar kolom yang diizinkan untuk diisi.
     * Pastikan nama di bawah ini SAMA PERSIS dengan kolom di HeidiSQL.
     */
    protected $fillable = [
        'nama_kopi',
        'daerah_asal',
        'tingkat_sangrai', 
        'harga',
        'stok',
        'deskripsi',
        'foto'
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}