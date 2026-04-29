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
            
            // TAMBAHKAN BARIS INI: Untuk menghubungkan pesanan dengan User yang login
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('nama_penerima');
            $table->text('alamat_pengiriman');
            $table->string('nomor_hp');
            $table->integer('total_harga');
            $table->string('bukti_pembayaran')->nullable();
            $table->enum('status', ['Menunggu Pembayaran', 'Diproses', 'Dikirim', 'Selesai'])->default('Menunggu Pembayaran');
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