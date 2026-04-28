<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kopi'); // [cite: 270]
            $table->string('daerah_asal'); // Contoh: Bajawa, Manggarai [cite: 271]
            $table->string('tingkat_sangrai')->nullable(); // [cite: 273]
            $table->integer('harga'); // [cite: 274]
            $table->integer('stok'); // [cite: 117, 274]
            $table->text('deskripsi'); // Profil rasa [cite: 263, 272]
            $table->string('foto')->nullable(); // [cite: 275]
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};