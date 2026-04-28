<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $username = session('username', 'Admin');

        $transactions = [
            ['id' => '#TRX-001', 'customer' => 'Budi Santoso',   'produk' => 'Choco Chip Cookies',   'qty' => 3, 'total' => 135000, 'status' => 'Selesai', 'tanggal' => '27 Apr 2026'],
            ['id' => '#TRX-002', 'customer' => 'Siti Rahayu',    'produk' => 'Almond Butter Cookies', 'qty' => 2, 'total' => 110000, 'status' => 'Proses',  'tanggal' => '27 Apr 2026'],
            ['id' => '#TRX-003', 'customer' => 'Ahmad Fauzi',    'produk' => 'Red Velvet Hampers',    'qty' => 1, 'total' => 250000, 'status' => 'Selesai', 'tanggal' => '26 Apr 2026'],
            ['id' => '#TRX-004', 'customer' => 'Dewi Lestari',   'produk' => 'Nastar Premium',        'qty' => 5, 'total' => 175000, 'status' => 'Selesai', 'tanggal' => '26 Apr 2026'],
            ['id' => '#TRX-005', 'customer' => 'Rizky Pratama',  'produk' => 'Putri Salju Box',       'qty' => 2, 'total' => 90000,  'status' => 'Batal',   'tanggal' => '25 Apr 2026'],
            ['id' => '#TRX-006', 'customer' => 'Maya Indah',     'produk' => 'Choco Chip Cookies',   'qty' => 4, 'total' => 180000, 'status' => 'Proses',  'tanggal' => '25 Apr 2026'],
            ['id' => '#TRX-007', 'customer' => 'Hendra Gunawan', 'produk' => 'Cheese Cookies',       'qty' => 3, 'total' => 120000, 'status' => 'Selesai', 'tanggal' => '24 Apr 2026'],
            ['id' => '#TRX-008', 'customer' => 'Rina Wulandari', 'produk' => 'Brownie Hampers',      'qty' => 1, 'total' => 200000, 'status' => 'Selesai', 'tanggal' => '24 Apr 2026'],
        ];

        $stats = [
            'pendapatan' => 'Rp 12.480.000',
            'pesanan'    => 148,
            'terjual'    => 342,
            'pelanggan'  => 87,
        ];

        $grafik = [
            'labels' => ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
            'data'   => [1200000, 980000, 1450000, 870000, 1600000, 2100000, 1850000],
        ];

        return view('dashboard', compact('username', 'transactions', 'stats', 'grafik'));
    }
}
