<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $username = session('username', 'Admin');

        $produk = [
            ['kode' => 'PRD-001', 'nama' => 'Choco Chip Cookies',    'kategori' => 'Cookies', 'stok' => 45, 'satuan' => 'Box', 'harga' => 45000,  'status' => 'Tersedia'],
            ['kode' => 'PRD-002', 'nama' => 'Almond Butter Cookies', 'kategori' => 'Cookies', 'stok' => 12, 'satuan' => 'Box', 'harga' => 55000,  'status' => 'Menipis'],
            ['kode' => 'PRD-003', 'nama' => 'Cheese Cookies',        'kategori' => 'Cookies', 'stok' => 30, 'satuan' => 'Box', 'harga' => 40000,  'status' => 'Tersedia'],
            ['kode' => 'PRD-004', 'nama' => 'Nastar Premium',        'kategori' => 'Cookies', 'stok' => 8,  'satuan' => 'Box', 'harga' => 35000,  'status' => 'Menipis'],
            ['kode' => 'PRD-005', 'nama' => 'Putri Salju Box',       'kategori' => 'Cookies', 'stok' => 0,  'satuan' => 'Box', 'harga' => 45000,  'status' => 'Habis'],
            ['kode' => 'PRD-006', 'nama' => 'Red Velvet Hampers',    'kategori' => 'Hampers', 'stok' => 18, 'satuan' => 'Pcs', 'harga' => 250000, 'status' => 'Tersedia'],
            ['kode' => 'PRD-007', 'nama' => 'Brownie Hampers',       'kategori' => 'Hampers', 'stok' => 5,  'satuan' => 'Pcs', 'harga' => 200000, 'status' => 'Menipis'],
            ['kode' => 'PRD-008', 'nama' => 'Premium Gift Box',      'kategori' => 'Hampers', 'stok' => 0,  'satuan' => 'Pcs', 'harga' => 350000, 'status' => 'Habis'],
            ['kode' => 'PRD-009', 'nama' => 'Choco Hazelnut Hampers','kategori' => 'Hampers', 'stok' => 22, 'satuan' => 'Pcs', 'harga' => 280000, 'status' => 'Tersedia'],
            ['kode' => 'PRD-010', 'nama' => 'Rainbow Cookies',       'kategori' => 'Cookies', 'stok' => 3,  'satuan' => 'Box', 'harga' => 50000,  'status' => 'Menipis'],
        ];

        $stokStats = [
            'total'   => array_sum(array_column($produk, 'stok')),
            'menipis' => count(array_filter($produk, fn($p) => $p['status'] === 'Menipis')),
            'habis'   => count(array_filter($produk, fn($p) => $p['status'] === 'Habis')),
        ];

        $chartStok = [
            'cookies' => array_sum(array_column(array_filter($produk, fn($p) => $p['kategori'] === 'Cookies'), 'stok')),
            'hampers' => array_sum(array_column(array_filter($produk, fn($p) => $p['kategori'] === 'Hampers'), 'stok')),
        ];

        return view('pengelolaan', compact('username', 'produk', 'stokStats', 'chartStok'));
    }
}
