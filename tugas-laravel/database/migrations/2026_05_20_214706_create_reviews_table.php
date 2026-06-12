<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            // Relasi ke tabel users (Siapa yang memberikan ulasan)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Relasi ke tabel products (Kue apa yang diulas)
            $table->unsignedBigInteger('id_produk');
            $table->foreign('id_produk')->references('id')->on('products')->onDelete('cascade');

            // Relasi ke tabel transaksis
            // WAJIB pakai string karena tipe data ID di tabel 'transaksis' milikmu berupa string (#TRX-001)
            $table->string('id_transaksi');
            $table->foreign('id_transaksi')->references('id')->on('transaksis')->onDelete('cascade');

            // Data Rating & Komentar
            $table->integer('rating'); // Kita batasi 1-5 di kodingan aplikasi nanti
            $table->text('comment')->nullable(); // Kustomer boleh rating bintang aja tanpa nulis teks

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
