<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User; // <-- PASTIKAN IMPORT MODEL USER INI ADA
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash; // <-- UNTUK BIKIN PASSWORD AMAN

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        TransactionItem::truncate();
        Transaction::truncate();
        Schema::enableForeignKeyConstraints();

        // Ambil data produk asli dari database
        $p1 = Product::where('kode', 'CK-001')->first();
        $p2 = Product::where('kode', 'CK-002')->first();
        $p3 = Product::where('kode', 'HM-001')->first();
        $p4 = Product::where('kode', 'CK-003')->first();
        $p5 = Product::where('kode', 'CK-006')->first();
        $p6 = Product::where('kode', 'CK-007')->first();
        $p7 = Product::where('kode', 'HM-003')->first();

        // Masukkan data transaksi (Langsung pakai ID user karena dijamin usernya sudah dibuat duluan oleh UserSeeder)
        Transaction::insert([
            ['id' => '#TRX-001', 'user_id' => 2, 'status_pesanan' => 'Selesai', 'tanggal_transaksi' => '27 Apr 2026'],
            ['id' => '#TRX-002', 'user_id' => 3, 'status_pesanan' => 'Proses',  'tanggal_transaksi' => '27 Apr 2026'],
            ['id' => '#TRX-003', 'user_id' => 4, 'status_pesanan' => 'Selesai', 'tanggal_transaksi' => '26 Apr 2026'],
            ['id' => '#TRX-004', 'user_id' => 5, 'status_pesanan' => 'Selesai', 'tanggal_transaksi' => '26 Apr 2026'],
            ['id' => '#TRX-005', 'user_id' => 6, 'status_pesanan' => 'Batal',   'tanggal_transaksi' => '25 Apr 2026'],
            ['id' => '#TRX-006', 'user_id' => 7, 'status_pesanan' => 'Proses',  'tanggal_transaksi' => '25 Apr 2026'],
            ['id' => '#TRX-007', 'user_id' => 8, 'status_pesanan' => 'Selesai', 'tanggal_transaksi' => '24 Apr 2026'],
            ['id' => '#TRX-008', 'user_id' => 9, 'status_pesanan' => 'Selesai', 'tanggal_transaksi' => '24 Apr 2026'],
        ]);

        // Masukkan data detail item transaksi
        TransactionItem::insert([
            ['id_transaksi' => '#TRX-001', 'id_produk' => $p1 ? $p1->id : null, 'nama_produk' => 'Choco Chip Cookies', 'jumlah' => 3, 'harga_satuan' => 45000, 'subtotal' => 3 * 45000],
            ['id_transaksi' => '#TRX-002', 'id_produk' => $p2 ? $p2->id : null, 'nama_produk' => 'Almond Butter Cookies', 'jumlah' => 2, 'harga_satuan' => 55000, 'subtotal' => 2 * 55000],
            ['id_transaksi' => '#TRX-003', 'id_produk' => $p3 ? $p3->id : null, 'nama_produk' => 'Red Velvet Hampers', 'jumlah' => 1, 'harga_satuan' => 250000, 'subtotal' => 1 * 250000],
            ['id_transaksi' => '#TRX-004', 'id_produk' => $p4 ? $p4->id : null, 'nama_produk' => 'Nastar Premium', 'jumlah' => 5, 'harga_satuan' => 35000, 'subtotal' => 5 * 35000],
            ['id_transaksi' => '#TRX-005', 'id_produk' => $p5 ? $p5->id : null, 'nama_produk' => 'Putri Salju Box', 'jumlah' => 2, 'harga_satuan' => 45000, 'subtotal' => 2 * 45000],
            ['id_transaksi' => '#TRX-006', 'id_produk' => $p1 ? $p1->id : null, 'nama_produk' => 'Choco Chip Cookies', 'jumlah' => 4, 'harga_satuan' => 45000, 'subtotal' => 4 * 45000],
            ['id_transaksi' => '#TRX-007', 'id_produk' => $p6 ? $p6->id : null, 'nama_produk' => 'Cheese Cookies', 'jumlah' => 3, 'harga_satuan' => 40000, 'subtotal' => 3 * 40000],
            ['id_transaksi' => '#TRX-008', 'id_produk' => $p7 ? $p7->id : null, 'nama_produk' => 'Brownie Hampers', 'jumlah' => 1, 'harga_satuan' => 200000, 'subtotal' => 1 * 200000],
        ]);
    }
}
