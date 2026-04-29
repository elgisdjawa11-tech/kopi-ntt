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
     */
    protected $fillable = [
        'name',
        'username', 
        'phone',    // Sudah benar
        'city',     // Sudah benar
        'password',
        'role',     // Sudah benar
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
            // Kita gunakan password hashing otomatis
            'password' => 'hashed',
        ];
    }

    /**
     * Helper untuk mengecek role user (untuk proteksi halaman Admin).
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