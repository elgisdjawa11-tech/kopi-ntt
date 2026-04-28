<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database (opsional jika nama tabelnya 'settings').
     * Laravel secara otomatis menganggap jamak (plural), 
     * jadi 'Setting' akan mencari tabel 'settings'.
     */
    protected $table = 'settings';

    /**
     * Kolom yang dapat diisi secara massal (Mass Assignment).
     * Pastikan kolom ini sesuai dengan yang ada di Migration kamu.
     */
    protected $fillable = [
        'nama_bank',
        'no_rekening',
        'atas_nama',
        'foto_qris'
    ];
}