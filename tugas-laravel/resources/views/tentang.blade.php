@extends('layouts.app')

@section('title', 'Tentang Kami')

@section('content')

@php
    $username = session('username', 'Admin');

    $milestones = [
        ['tahun' => '2020', 'judul' => 'Awal Berdiri',     'desc' => 'Alia Cookies lahir dari dapur rumahan di Jember dengan semangat berbagi cita rasa terbaik.'],
        ['tahun' => '2021', 'judul' => 'Produk Pertama',   'desc' => 'Meluncurkan Choco Chip Cookies sebagai produk andalan yang langsung mendapat sambutan hangat.'],
        ['tahun' => '2022', 'judul' => 'Ekspansi Hampers', 'desc' => 'Memperluas lini produk ke hampers premium untuk momen spesial seperti Lebaran dan Natal.'],
        ['tahun' => '2023', 'judul' => '500+ Pelanggan',   'desc' => 'Mencapai lebih dari 500 pelanggan setia dan mulai melayani pengiriman ke seluruh Indonesia.'],
        ['tahun' => '2024', 'judul' => 'Sistem Digital',   'desc' => 'Meluncurkan panel admin digital untuk manajemen stok dan transaksi yang lebih efisien.'],
        ['tahun' => '2025', 'judul' => 'Terus Berkembang', 'desc' => 'Terus berinovasi dengan varian baru dan layanan yang semakin personal untuk pelanggan.'],
    ];

    $keunggulan = [
        ['icon' => '🍪', 'judul' => 'Resep Rahasia Keluarga', 'desc' => 'Setiap cookies dibuat dari resep turun-temurun yang telah teruji dan menghasilkan cita rasa yang tak tertandingi.'],
        ['icon' => '🌿', 'judul' => 'Bahan Premium Pilihan',  'desc' => 'Kami hanya menggunakan bahan-bahan berkualitas tinggi, dipilih dengan teliti untuk hasil terbaik.'],
        ['icon' => '🎁', 'judul' => 'Kemasan Elegan',         'desc' => 'Dikemas dengan indah dan mewah, menjadikan setiap produk layak sebagai hadiah atau hampers.'],
        ['icon' => '❤️', 'judul' => 'Dibuat dengan Cinta',   'desc' => 'Setiap produk dikerjakan secara handmade dengan penuh perhatian dan dedikasi untuk kualitas terbaik.'],
    ];

    $misi = [
        'Menghadirkan cookies berkualitas premium dengan bahan-bahan terpilih',
        'Memberikan pengalaman berbelanja yang menyenangkan dan berkesan',
        'Berinovasi secara konsisten untuk memenuhi selera pelanggan',
        'Menjaga kepercayaan pelanggan melalui kualitas yang konsisten',
    ];

    $produkUnggulan = [
        ['nama' => 'Choco Chip Cookies',    'ket' => 'Cookies klasik dengan lelehan cokelat yang sempurna.',  'badge' => 'Best Seller', 'harga' => 'Rp 45.000',  'bg' => 'pu-mocha'],
        ['nama' => 'Almond Butter Cookies', 'ket' => 'Perpaduan almond premium dan butter pilihan yang kaya.', 'badge' => 'Favorit',     'harga' => 'Rp 55.000',  'bg' => 'pu-rose'],
        ['nama' => 'Premium Gift Box',      'ket' => 'Hampers eksklusif untuk hadiah momen paling spesial.',  'badge' => 'Premium',     'harga' => 'Rp 350.000', 'bg' => 'pu-lavender'],
    ];

    $kontaks = [
        ['icon' => '📧', 'label' => 'Email',     'val' => 'aliacookies@gmail.com',         'sub' => 'Balasan dalam 1×24 jam'],
        ['icon' => '📸', 'label' => 'Instagram', 'val' => '@aliacookies.jbr',              'sub' => 'Follow untuk update produk'],
        ['icon' => '💬', 'label' => 'WhatsApp',  'val' => '+62 856-4856-9562',             'sub' => 'Chat langsung dengan kami'],
        ['icon' => '📍', 'label' => 'Lokasi',    'val' => 'Jember, Jawa Timur, Indonesia', 'sub' => 'Buka setiap hari'],
    ];
@endphp

<div class="page-wrapper">

    {{-- ══ HERO TENTANG ══ --}}
    <div class="tentang-hero">
        <div class="tentang-hero-blob t-blob-1"></div>
        <div class="tentang-hero-blob t-blob-2"></div>
        <div class="tentang-hero-inner">
            <div class="tentang-hero-text">
                <div class="t-label">✦ Est. 2020 · Jember, Indonesia</div>
                <h1 class="tentang-hero-title">
                    Tentang <em>Alia Cookies</em>
                </h1>
                <p class="tentang-hero-desc">
                    Alia Cookies adalah toko cookies handmade premium berbasis di Jember, Indonesia.
                    Kami hadir untuk menghadirkan momen manis yang tak terlupakan — dari camilan harian
                    hingga hampers eksklusif untuk momen-momen paling spesial dalam hidup Anda.
                </p>
                <div class="tentang-hero-stats">
                    <div class="t-stat"><strong>5+</strong><span>Tahun Berpengalaman</span></div>
                    <div class="t-stat-div"></div>
                    <div class="t-stat"><strong>50+</strong><span>Varian Produk</span></div>
                    <div class="t-stat-div"></div>
                    <div class="t-stat"><strong>500+</strong><span>Pelanggan Puas</span></div>
                    <div class="t-stat-div"></div>
                    <div class="t-stat"><strong>4.9★</strong><span>Rating Toko</span></div>
                </div>
            </div>
            <div class="tentang-hero-visual">
                <div class="tentang-logo-ring">
                    <img src="{{ asset('images/aliacookies.png') }}" alt="Alia Cookies"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                    <div class="t-logo-fallback" style="display:none">🍪</div>
                </div>
                <div class="tentang-product-img">
                    <img src="{{ asset('images/cookies.png') }}" alt="Cookies"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                    <div style="display:none; font-size:6rem; text-align:center;">🍪</div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══ VISI MISI ══ --}}
    <div class="section-grid-2">
        <div class="card visi-card">
            <h2 class="card-title">Visi Kami</h2>
            <p class="vm-text">
                Menjadi toko cookies handmade premium terpercaya di Indonesia yang dikenal
                karena kualitas, kreativitas, dan kehangatan dalam setiap produk yang kami hadirkan.
            </p>
        </div>
        <div class="card misi-card">
            <h2 class="card-title">Misi Kami</h2>
            <ul class="misi-list">
                @foreach($misi as $item)
                <li>
                    <span class="misi-dot">✦</span>
                    {{ $item }}
                </li>
                @endforeach
            </ul>
        </div>
    </div>

    {{-- ══ KEUNGGULAN ══ --}}
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Keunggulan Kami</h2>
            <span class="t-label">Mengapa memilih Alia Cookies?</span>
        </div>
        <div class="keunggulan-grid">
            @foreach($keunggulan as $k)
            <div class="keunggulan-card">
                <div class="keunggulan-icon">{{ $k['icon'] }}</div>
                <h3>{{ $k['judul'] }}</h3>
                <p>{{ $k['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ══ TIMELINE / PERJALANAN ══ --}}
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Perjalanan Kami</h2>
            <span class="t-label">Dari dapur rumahan ke ribuan pelanggan</span>
        </div>
        <div class="timeline">
            @foreach($milestones as $i => $ms)
            <div class="timeline-item {{ $i % 2 === 0 ? 'tl-left' : 'tl-right' }}">
                <div class="tl-year">{{ $ms['tahun'] }}</div>
                <div class="tl-dot"></div>
                <div class="tl-content">
                    <h3>{{ $ms['judul'] }}</h3>
                    <p>{{ $ms['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ══ PRODUK UNGGULAN ══ --}}
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">Produk Unggulan</h2>
            <span class="t-label">Yang paling dicintai pelanggan kami</span>
        </div>
        <div class="produk-unggulan-grid">
            @foreach($produkUnggulan as $pu)
            <div class="pu-card {{ $pu['bg'] }}">
                <span class="pu-badge">{{ $pu['badge'] }}</span>
                <div class="pu-img">
                    <img src="{{ asset('images/cookies.png') }}" alt="{{ $pu['nama'] }}"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                    <div style="display:none; font-size:3.5rem; text-align:center;">🍪</div>
                </div>
                <div class="pu-info">
                    <h3>{{ $pu['nama'] }}</h3>
                    <p>{{ $pu['ket'] }}</p>
                    <strong>{{ $pu['harga'] }} / box</strong>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ══ KONTAK ══ --}}
    <div class="card kontak-card">
        <div class="card-header">
            <h2 class="card-title">Hubungi Kami</h2>
            <span class="t-label">Kami senang mendengar dari Anda</span>
        </div>
        <div class="kontak-grid">
            @foreach($kontaks as $k)
            <div class="kontak-item">
                <div class="kontak-icon">{{ $k['icon'] }}</div>
                <div class="kontak-info">
                    <span class="kontak-label">{{ $k['label'] }}</span>
                    <strong class="kontak-val">{{ $k['val'] }}</strong>
                    <span class="kontak-sub">{{ $k['sub'] }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    const tlItems = document.querySelectorAll('.timeline-item');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('tl-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15 });
    tlItems.forEach(el => observer.observe(el));
</script>
@endsection
