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
        Schema::create('detail_transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('id_transaksi');

            // KUNCI UTAMA: Ubah menjadi unsignedBigInteger biasa tanpa langsung mengunci constrained('products')
            $table->unsignedBigInteger('id_produk')->nullable();

            $table->string('nama_produk');
            $table->integer('jumlah');
            $table->bigInteger('harga_satuan');
            $table->bigInteger('subtotal');
            $table->timestamps();

            // Cukup pasang foreign key ke tabel transaksis saja (karena tabel transaksis dibuat di hari yang sama)
            $table->foreign('id_transaksi')->references('id')->on('transaksis')->onDelete('cascade');
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_transaksis');
    }
};
