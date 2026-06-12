<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel users
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Relasi ke tabel products menggunakan nama kolom id_produk sesuai konversimu
            $table->unsignedBigInteger('id_produk');
            $table->foreign('id_produk')->references('id')->on('products')->onDelete('cascade');

            // Jumlah produk yang dimasukkan ke keranjang
            $table->integer('jumlah');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
