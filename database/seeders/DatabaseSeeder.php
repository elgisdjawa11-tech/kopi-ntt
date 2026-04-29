<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Kita buat satu user admin secara manual agar tidak bentrok dengan factory lama
        User::create([
            'name' => 'Admin Kopi NTT',
            'username' => 'admin',
            'phone' => '08123456789',
            'city' => 'Kupang',
            'password' => Hash::make('password123'), // Passwordnya: password123
            'role' => 'admin',
        ]);
    }
}