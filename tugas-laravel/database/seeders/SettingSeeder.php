<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Cerita Narasi Utama
        Setting::updateOrCreate(
            ['key' => 'tentang_cerita'],
            ['value' => 'Alia Cookies bermula dari kecintaan terhadap seni membuat kue kering tradisional yang diolah dengan sentuhan modern. Kami berkomitmen untuk selalu menyajikan cita rasa premium menggunakan bahan-bahan pilihan terbaik tanpa bahan pengawet. Setiap gigitan mencerminkan kehangatan rumah dan dedikasi kami dalam memberikan kebahagiaan manis di setiap momen spesial Anda. Berawal dari dapur rumahan, kini kami bangga bisa menjadi bagian dari perayaan cerita manis para pelanggan setia kami.']
        );

        // 2. Data Milestones / Timeline (Disimpan dalam format JSON)
        $milestones = [
            ['tahun' => '2020', 'judul' => 'Awal Berdiri',     'desc' => 'Alia Cookies lahir dari dapur rumahan di Jember dengan semangat berbagi cita rasa terbaik.'],
            ['tahun' => '2021', 'judul' => 'Produk Pertama',   'desc' => 'Meluncurkan Choco Chip Cookies sebagai produk andalan yang langsung mendapat sambutan hangat.'],
            ['tahun' => '2022', 'judul' => 'Ekspansi Hampers', 'desc' => 'Memperluas lini produk ke hampers premium untuk momen spesial seperti Lebaran dan Natal.'],
            ['tahun' => '2023', 'judul' => '500+ Pelanggan',   'desc' => 'Mencapai lebih dari 500 pelanggan setia dan mulai melayani pengiriman ke seluruh Indonesia.'],
            ['tahun' => '2024', 'judul' => 'Sistem Digital',   'desc' => 'Meluncurkan panel admin digital untuk manajemen stok dan transaksi yang lebih efisien.'],
            ['tahun' => '2025', 'judul' => 'Terus Berkembang', 'desc' => 'Terus berinovasi dengan varian baru dan layanan yang semakin personal untuk pelanggan.'],
        ];
        Setting::updateOrCreate(
            ['key' => 'tentang_milestones'],
            ['value' => json_encode($milestones)]
        );

        // 3. Data Kontak (Disimpan dalam format JSON)
        $kontaks = [
            ['icon' => '📍', 'label' => 'Alamat Toko', 'val' => 'Jl. Jawa No. 12', 'sub' => 'Sumbersari, Jember'],
            ['icon' => '📞', 'label' => 'WhatsApp', 'val' => '0812-3456-7890', 'sub' => 'Respons cepat jam kerja'],
            ['icon' => '✉️', 'label' => 'Email Resmi', 'val' => 'halo@aliacookies.com', 'sub' => 'Untuk kerjasama & saran'],
        ];
        Setting::updateOrCreate(
            ['key' => 'tentang_kontak'],
            ['value' => json_encode($kontaks)]
        );
    }
}
