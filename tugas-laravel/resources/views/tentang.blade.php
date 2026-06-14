@extends('layouts.store')

@section('title', 'Tentang Kami')

@section('content')

@php
    $username = session('username', 'Admin');

    // Data ini tetap kita biarkan di sini karena berhubungan langsung dengan warna dan ikon UI (CSS)
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

    // Menggunakan variabel baru agar tidak bentrok dengan controller, menjaga class warna 'bg' tetap jalan
    $produkUnggulanUI = [
        ['nama' => 'Choco Chip Cookies',    'ket' => 'Cookies klasik dengan lelehan cokelat yang sempurna.',  'badge' => 'Best Seller', 'harga' => 'Rp 45.000',  'bg' => 'pu-mocha'],
        ['nama' => 'Almond Butter Cookies', 'ket' => 'Perpaduan almond premium dan butter pilihan yang kaya.', 'badge' => 'Favorit',     'harga' => 'Rp 55.000',  'bg' => 'pu-rose'],
        ['nama' => 'Premium Gift Box',      'ket' => 'Hampers eksklusif untuk hadiah momen paling spesial.',  'badge' => 'Premium',     'harga' => 'Rp 350.000', 'bg' => 'pu-lavender'],
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

                {{-- MENGAMBIL CERITA DARI DATABASE --}}
                <p class="tentang-hero-desc">
                    {{ $cerita }}
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
            {{-- MENGAMBIL DATA MILESTONES DARI DATABASE --}}
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

    {{-- ══ KONTAK ══ --}}
    <div class="card kontak-card">
        <div class="card-header">
            <h2 class="card-title">Hubungi Kami</h2>
            <span class="t-label">Kami senang mendengar dari Anda</span>
        </div>
        <div class="kontak-grid">
            {{-- MENGAMBIL DATA KONTAK DARI DATABASE --}}
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

@push('scripts')
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
@endpush
