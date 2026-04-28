<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Membuat tabel order_items untuk mencatat detail kopi yang dibeli.
     * Sesuai dengan kebutuhan sistem informasi penjualan kopi NTT.
     */
    public function up(): void
{
    Schema::create('order_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('order_id')->constrained()->onDelete('cascade');
        $table->foreignId('product_id')->constrained();
        $table->integer('jumlah');
        $table->integer('harga_satuan');
        $table->timestamps();
    });
}

    /**
     * Menghapus tabel jika migrasi dibatalkan.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};