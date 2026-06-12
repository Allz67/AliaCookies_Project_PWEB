<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ubah namanya jadi 'transaksis'
        Schema::table('transaksis', function (Blueprint $table) {
            $table->integer('total_harga')->default(0)->after('user_id');
            $table->string('snap_token')->nullable()->after('total_harga');
            $table->enum('payment_status', ['Unpaid', 'Paid', 'Failed', 'Expired'])->default('Unpaid')->after('snap_token');

            $table->text('shipping_address')->nullable()->after('status_pesanan');
            $table->integer('shipping_cost')->default(0)->after('shipping_address');
            $table->string('courier')->nullable()->after('shipping_cost');
            $table->string('resi_number')->nullable()->after('courier');
        });
    }

    public function down(): void
    {
        // Ubah di sini juga jadi 'transaksis'
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn([
                'total_harga', 'snap_token', 'payment_status',
                'shipping_address', 'shipping_cost', 'courier', 'resi_number'
            ]);
        });
    }
};
