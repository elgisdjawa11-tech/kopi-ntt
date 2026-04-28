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
        $table->string('nama_penerima'); // [cite: 354]
        $table->text('alamat_pengiriman'); // [cite: 332]
        $table->string('nomor_hp'); // [cite: 354]
        $table->integer('total_harga'); // [cite: 327]
        $table->string('bukti_pembayaran')->nullable(); // [cite: 282, 333]
        $table->enum('status', ['Menunggu Pembayaran', 'Diproses', 'Dikirim', 'Selesai'])->default('Menunggu Pembayaran'); // [cite: 283, 325]
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
