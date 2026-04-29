<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            
            // Relasi ke tabel users
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('nama_penerima');
            $table->text('alamat_pengiriman');
            $table->string('nomor_hp');
            $table->integer('total_harga');

            // --- TAMBAHKAN BARIS INI ---
            $table->string('snap_token')->nullable();
            // ---------------------------

            // Daftar status diperlengkap agar tidak error "Data truncated" lagi
            $table->enum('status', [
                'Menunggu Pembayaran', 
                'Menunggu Verifikasi', 
                'Diproses', 
                'Dikirim', 
                'Selesai',
                'Dibatalkan',
                'Expired'
            ])->default('Menunggu Pembayaran');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};