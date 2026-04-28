<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    /**
     * Menjalankan database seeds untuk data awal produk kopi NTT.
     * Sesuai dengan fitur 'Manajemen Katalog Produk' di proposal Tugas Akhir.
     */
    public function run()
    {
        // 1. Arabika Bajawa
        Product::create([
            'nama_kopi'   => 'Arabika Bajawa',
            'daerah_asal' => 'Ngada, Flores',
            'harga'       => 85000,
            'stok'        => 50, // Menambahkan nilai stok agar tidak error
            'deskripsi'   => 'Memiliki aroma nutty (kacang-kacangan) dan karamel yang kuat dengan tingkat keasaman sedang.',
            'foto'        => 'kopi_bajawa.jpg'
        ]);

        // 2. Robusta Manggarai
        Product::create([
            'nama_kopi'   => 'Robusta Manggarai',
            'daerah_asal' => 'Manggarai, Flores',
            'harga'       => 65000,
            'stok'        => 40,
            'deskripsi'   => 'Cita rasa cokelat pekat dengan body yang tebal dan aroma yang sangat harum.',
            'foto'        => 'kopi_manggarai.jpg'
        ]);

        // 3. Arabika Flores Juria
        Product::create([
            'nama_kopi'   => 'Arabika Flores Juria',
            'daerah_asal' => 'Colol, Manggarai Timur',
            'harga'       => 120000,
            'stok'        => 15,
            'deskripsi'   => 'Kopi langka dari pohon tua berumur puluhan tahun dengan sensasi rasa buah-buahan eksotis.',
            'foto'        => 'kopi_juria.jpg'
        ]);

        // 4. Arabika Sumba
        Product::create([
            'nama_kopi'   => 'Arabika Sumba',
            'daerah_asal' => 'Sumba Barat Daya',
            'harga'       => 75000,
            'stok'        => 25,
            'deskripsi'   => 'Kopi dengan karakter rasa yang unik, bersih, dan memiliki sedikit sentuhan herbal.',
            'foto'        => 'kopi_sumba.jpg'
        ]);

        // 5. Kopi Timor (Amfoang)
        Product::create([
            'nama_kopi'   => 'Kopi Amfoang',
            'daerah_asal' => 'Kabupaten Kupang',
            'harga'       => 70000,
            'stok'        => 30,
            'deskripsi'   => 'Kopi khas daratan Timor dengan rasa yang bold dan aroma tanah yang khas.',
            'foto'        => 'kopi_amfoang.jpg'
        ]);
    }
}