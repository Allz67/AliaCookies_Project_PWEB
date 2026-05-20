<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Bersihkan tabel users sebelum di-input agar ID-nya reset kembali ke 1
        Schema::disableForeignKeyConstraints();
        User::truncate();
        Schema::enableForeignKeyConstraints();

        // 1. Akun Admin Utama (Diberi role 'admin' agar bisa lolos middleware CekAdmin)
        User::create([
            'id' => 1,
            'name' => 'Admin Alia Cookies',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'admin', // Kunci lolos gatekeeper admin dashboard
        ]);

        // 2. Akun Pelanggan/Customer (Diberi role 'customer' atau sesuaikan dengan enum milikmu)
        User::create(['id' => 2, 'name' => 'Budi Santoso',   'email' => 'budi@gmail.com',  'password' => Hash::make('password'), 'role' => 'customer']);
        User::create(['id' => 3, 'name' => 'Siti Rahayu',    'email' => 'siti@gmail.com',  'password' => Hash::make('password'), 'role' => 'customer']);
        User::create(['id' => 4, 'name' => 'Ahmad Fauzi',    'email' => 'ahmad@gmail.com', 'password' => Hash::make('password'), 'role' => 'customer']);
        User::create(['id' => 5, 'name' => 'Dewi Lestari',   'email' => 'dewi@gmail.com',  'password' => Hash::make('password'), 'role' => 'customer']);
        User::create(['id' => 6, 'name' => 'Rizky Pratama',  'email' => 'rizky@gmail.com', 'password' => Hash::make('password'), 'role' => 'customer']);
        User::create(['id' => 7, 'name' => 'Maya Indah',     'email' => 'maya@gmail.com',  'password' => Hash::make('password'), 'role' => 'customer']);
        User::create(['id' => 8, 'name' => 'Hendra Gunawan', 'email' => 'hendra@gmail.com', 'password' => Hash::make('password'), 'role' => 'customer']);
        User::create(['id' => 9, 'name' => 'Rina Wulandari', 'email' => 'rina@gmail.com',  'password' => Hash::make('password'), 'role' => 'customer']);
    }
}
