# Alia Cookies - E-Commerce & Inventory Management System

Aplikasi berbasis web yang mengintegrasikan tampilan antarmuka pelanggan (e-commerce) dengan sistem manajemen inventaris operasional. Proyek ini dikembangkan untuk memenuhi standar tugas Praktik Pemrograman Web, berfokus pada implementasi HTML5 Semantic, CSS modern, dan Vanilla JavaScript.

## Panduan Akses Sistem

Sistem ini memiliki dua antarmuka utama yang saling terhubung melalui portal otentikasi simulasi.

Untuk berpindah antar antarmuka:
1. Buka halaman utama (index.html).
2. Klik tombol profil di sudut kanan atas navigasi.
3. Pada modal portal yang muncul, pilih hak akses yang sesuai:
   - Toko (Customer): Mengarahkan ke antarmuka pelanggan.
   - Dapur (Admin): Mengarahkan ke antarmuka manajemen inventaris (admin.html).

## Fitur Utama

Antarmuka Pelanggan (index.html):
- Landing page dinamis dengan scroll-driven animation.
- Katalog produk menggunakan slider dengan efek infinite scroll.
- Tabel riwayat pesanan dengan fitur pencarian real-time, filter kategori, dan filter periode waktu.

Antarmuka Admin (admin.html):
- Dashboard analitik yang menampilkan metrik valuasi stok dan visualisasi data menggunakan Chart.js.
- Sistem CRUD (Create, Read, Update, Delete) untuk manajemen data inventaris.
- Validasi form sisi klien menggunakan JavaScript kustom.
- Penyimpanan data persisten menggunakan Local Storage API.
- Sistem notifikasi dinamis (Toast UI) untuk setiap aksi modifikasi data.
