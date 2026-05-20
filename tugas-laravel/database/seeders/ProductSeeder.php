<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Facades\Schema;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // KUNCI ANTI-ERROR: Mengosongkan data lama di tabel products agar tidak terjadi Duplicate Entry
        Schema::disableForeignKeyConstraints();
        Product::truncate();
        Schema::enableForeignKeyConstraints();

        // Data produk kue kering Alia Cookies yang disesuaikan agar klop dengan data transaksi dummy
        $data = [
            [
                'kode' => 'CK-001',
                'nama' => 'Choco Chip Cookies',
                'stok' => 50,
                'satuan' => 'Toples',
                'kategori' => 'Cookies',
                'harga' => 45000,
                'is_active' => true,
                'foto' => null
            ],
            [
                'kode' => 'CK-002',
                'nama' => 'Almond Butter Cookies',
                'stok' => 30,
                'satuan' => 'Toples',
                'kategori' => 'Cookies',
                'harga' => 55000,
                'is_active' => true,
                'foto' => null
            ],
            [
                'kode' => 'HM-001',
                'nama' => 'Red Velvet Hampers',
                'stok' => 10,
                'satuan' => 'Box',
                'kategori' => 'Hampers',
                'harga' => 250000,
                'is_active' => true,
                'foto' => null
            ],
            [
                'kode' => 'CK-003',
                'nama' => 'Nastar Premium',
                'stok' => 5,
                'satuan' => 'Toples',
                'kategori' => 'Cookies',
                'harga' => 35000,
                'is_active' => true,
                'foto' => null
            ],
            [
                'kode' => 'HM-002',
                'nama' => 'Assorted Cookies Hampers',
                'stok' => 0,
                'satuan' => 'Box',
                'kategori' => 'Hampers',
                'harga' => 300000,
                'is_active' => false,
                'foto' => null
            ],
            [
                'kode' => 'CK-004',
                'nama' => 'Dubai Chewy Cookies',
                'stok' => 20,
                'satuan' => 'Pcs',
                'kategori' => 'Cookies',
                'harga' => 15000,
                'is_active' => true,
                'foto' => null
            ],
            // TAMBAHAN: Produk penunjang agar klop dengan kebutuhan TransactionSeeder
            [
                'kode' => 'CK-006',
                'nama' => 'Putri Salju Box',
                'stok' => 45,
                'satuan' => 'Box',
                'kategori' => 'Cookies',
                'harga' => 45000,
                'is_active' => true,
                'foto' => null
            ],
            [
                'kode' => 'CK-007',
                'nama' => 'Cheese Cookies',
                'stok' => 30,
                'satuan' => 'Toples',
                'kategori' => 'Cookies',
                'harga' => 40000,
                'is_active' => true,
                'foto' => null
            ],
            [
                'kode' => 'HM-003',
                'nama' => 'Brownie Hampers',
                'stok' => 15,
                'satuan' => 'Box',
                'kategori' => 'Hampers',
                'harga' => 200000,
                'is_active' => true,
                'foto' => null
            ],
        ];

        foreach ($data as $item) {
            Product::create($item);
        }
    }
}
