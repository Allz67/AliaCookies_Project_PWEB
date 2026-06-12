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
        Schema::table('users', function (Blueprint $table) {
            $table->string('provinsi_id')->nullable(); // ID dari RajaOngkir
            $table->string('provinsi_nama')->nullable();
            $table->string('kota_id')->nullable(); // ID dari RajaOngkir
            $table->string('kota_nama')->nullable();
            $table->string('kecamatan')->nullable(); // Ketik manual
            $table->string('kode_pos')->nullable(); // Ketik manual
            $table->text('detail_alamat')->nullable(); // Nama jalan, RT/RW, patokan

            // Hapus kolom 'address' yang lama (opsional, biar rapi)
            $table->dropColumn('address');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['provinsi_id', 'provinsi_nama', 'kota_id', 'kota_nama', 'kecamatan', 'kode_pos', 'detail_alamat']);
            $table->string('address')->nullable();
        });
    }
};
