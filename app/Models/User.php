<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Atribut yang dapat diisi (Mass Assignable).
     * UPDATE REVISI: Menambahkan 'phone' dan 'city' agar bisa disimpan ke database.
     */
    protected $fillable = [
        'name',
        'username', 
        'password',
        'role',
        'phone', // Tambahan revisi nomor HP
        'city',  // Tambahan revisi kota/alamat
    ];

    /**
     * Atribut yang disembunyikan saat serialisasi.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting atribut.
     */
    protected function casts(): array
    {
        return [
            'username_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Helper untuk mengecek role user.
     */
    public function hasRole($role)
    {
        return $this->role === $role;
    }

    /**
     * Relasi ke Order (Satu user bisa punya banyak pesanan).
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}