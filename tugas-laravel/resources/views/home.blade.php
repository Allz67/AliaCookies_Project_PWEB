@extends('layouts.store')

@section('title', 'Alia Cookies — Handmade Premium Cookies')

@section('content')

{{-- ============================================================
     HERO SECTION
     ============================================================ --}}
<section class="sc-hero">
    <div class="sc-hero__bg-blur sc-hero__bg-blur--1"></div>
    <div class="sc-hero__bg-blur sc-hero__bg-blur--2"></div>
    <div class="sc-hero__bg-blur sc-hero__bg-blur--3"></div>

    <div class="sc-hero__inner">
        <div class="sc-hero__copy">
            <span class="sc-hero__eyebrow">✦ Handmade with Love</span>
            <h1 class="sc-hero__title">
                Cookies <em>Artisan</em><br>
                Untuk Setiap<br>
                Momen Spesial
            </h1>
            <p class="sc-hero__desc">
                Dibuat dari bahan pilihan terbaik, dipanggang dengan penuh kasih sayang.
                Setiap gigitan adalah cerita rasa yang tak terlupakan.
            </p>
            <div class="sc-hero__actions">
                <a href="#produk-cookies" class="sc-btn sc-btn--primary">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 002 2h14a2 2 0 002-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 01-8 0"/></svg>
                    Lihat Produk
                </a>
                <a href="{{ route('tentang') }}" class="sc-btn sc-btn--ghost">
                    Tentang Kami
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </a>
            </div>
        </div>

        <div class="sc-hero__visual">
            <div class="sc-hero__img-wrap">
                <img src="{{ asset('images/aliacookies.png') }}" alt="Alia Cookies"
                     onerror="this.parentElement.innerHTML='<div class=\'sc-hero__img-fallback\'>🍪</div>'">
                <div class="sc-hero__img-badge sc-hero__img-badge--1">
                    <span>✦</span> Handmade
                </div>
                <div class="sc-hero__img-badge sc-hero__img-badge--2">
                    🎁 Hampers Ready
                </div>
            </div>
            <div class="sc-hero__floater sc-hero__floater--a">🍪</div>
            <div class="sc-hero__floater sc-hero__floater--b">🌟</div>
            <div class="sc-hero__floater sc-hero__floater--c">🎀</div>
        </div>
    </div>

    {{-- Wave divider --}}
    <div class="sc-hero__wave">
        <svg viewBox="0 0 1440 80" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M0,40 C360,80 1080,0 1440,40 L1440,80 L0,80 Z" fill="var(--cream)"/>
        </svg>
    </div>
</section>

<div class="katalog-search-wrap">
    <svg class="katalog-search-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
    <input type="text" id="liveSearchKatalog" class="katalog-search-input" placeholder="Cari Cookies atau Hampers kesukaanmu..." onkeyup="cariKatalog(this.value)">
</div>

{{-- Container untuk Hasil Pencarian AJAX --}}
<div id="hasil-pencarian-katalog" style="display: none; padding: 2rem 0; min-height: 300px;"></div>

{{-- Bungkus katalog asli dengan div ini agar bisa disembunyikan saat mencari --}}
<div id="katalog-asli-bawaan">
{{-- ============================================================
     MARQUEE — COOKIES (kanan)
     ============================================================ --}}
@if($cookies->count())
<section class="sc-marquee-section" id="produk-cookies">
    <div class="sc-marquee-header">
        <div class="sc-section-label">
            <span class="sc-label-dot sc-label-dot--mocha"></span>
            Kategori Cookies
        </div>
        <h2 class="sc-section-title">
            Cookies <em>Favorit</em> Kami
        </h2>
    </div>

    {{-- MODIFIKASI MARQUEE COOKIES --}}
    <div class="sc-marquee-wrapper"
         onmouseenter="pauseMarquee('track-cookies')"
         onmouseleave="resumeMarquee('track-cookies')">

        <button class="marquee-nav prev" onclick="scrollMarquee('marquee-cookies', -350)">❮</button>

        <div class="sc-marquee sc-marquee--right" id="marquee-cookies">
            <div class="sc-marquee__track sc-marquee__track--right" id="track-cookies">
                @foreach($cookies->concat($cookies) as $produk)
                <a href="{{ route('product.detail', $produk->id) }}" class="sc-pcard">
                    <div class="sc-pcard__img-wrap">
                        @if($produk->foto)
                            <img src="{{ asset('storage/' . $produk->foto) }}" alt="{{ $produk->nama }}" loading="lazy">
                        @else
                            <div class="sc-pcard__img-fallback">🍪</div>
                        @endif
                        <div class="sc-pcard__overlay">
                            <span>Lihat Detail</span>
                        </div>
                    </div>
                    <div class="sc-pcard__body">
                        <span class="sc-pcard__kategori sc-pcard__kategori--cookies">Cookies</span>
                        <h3 class="sc-pcard__nama">{{ $produk->nama }}</h3>
                        <p class="sc-pcard__harga">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                        <p class="sc-pcard__stok">Stok: {{ $produk->stok }} {{ $produk->satuan }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>

        <button class="marquee-nav next" onclick="scrollMarquee('marquee-cookies', 350)">❯</button>
    </div>
</section>
@endif

{{-- ============================================================
     MARQUEE — HAMPERS (kiri)
     ============================================================ --}}
@if($hampers->count())
<section class="sc-marquee-section sc-marquee-section--hampers" id="produk-hampers">
    <div class="sc-marquee-header">
        <div class="sc-section-label">
            <span class="sc-label-dot sc-label-dot--rose"></span>
            Kategori Hampers
        </div>
        <h2 class="sc-section-title">
            Hampers <em>Spesial</em> untuk Setiap Acara
        </h2>
    </div>

    <div class="sc-marquee-wrapper"
         onmouseenter="pauseMarquee('track-hampers')"
         onmouseleave="resumeMarquee('track-hampers')">

        <button class="marquee-nav prev" onclick="scrollMarquee('marquee-hampers', -350)">❮</button>

        <div class="sc-marquee sc-marquee--left" id="marquee-hampers">
            <div class="sc-marquee__track sc-marquee__track--left" id="track-hampers">
                @foreach($hampers->concat($hampers) as $produk)
                <a href="{{ route('product.detail', $produk->id) }}" class="sc-pcard sc-pcard--hampers">
                    <div class="sc-pcard__img-wrap">
                        @if($produk->foto)
                            <img src="{{ asset('storage/' . $produk->foto) }}" alt="{{ $produk->nama }}" loading="lazy">
                        @else
                            <div class="sc-pcard__img-fallback">🎁</div>
                        @endif
                        <div class="sc-pcard__overlay">
                            <span>Lihat Detail</span>
                        </div>
                    </div>
                    <div class="sc-pcard__body">
                        <span class="sc-pcard__kategori sc-pcard__kategori--hampers">Hampers</span>
                        <h3 class="sc-pcard__nama">{{ $produk->nama }}</h3>
                        <p class="sc-pcard__harga">Rp {{ number_format($produk->harga, 0, ',', '.') }}</p>
                        <p class="sc-pcard__stok">Stok: {{ $produk->stok }} {{ $produk->satuan }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>

        <button class="marquee-nav next" onclick="scrollMarquee('marquee-hampers', 350)">❯</button>
    </div>
</section>
@endif


@push('scripts')
<script>
    // =========================================================
    // 1. FUNGSI LIVE SEARCH AJAX (FIX URL LARAVEL)
    // =========================================================
    async function cariKatalog(keyword) {
        const katalogAsli = document.getElementById('katalog-asli-bawaan');
        const hasilSearch = document.getElementById('hasil-pencarian-katalog');

        if (keyword.length > 0) {
            katalogAsli.style.display = 'none';
            hasilSearch.style.display = 'block';
            hasilSearch.innerHTML = '<div style="text-align:center; color:var(--mocha); padding: 2rem;">Memuat pencarian...</div>';

            try {
                // 🚀 PERBAIKAN NO 3: Pakai URL dinamis bawaan Laravel route('home')
                // Ini akan mencegah request nyasar ke luar folder XAMPP/project-mu
                const searchUrl = `{{ route('home') }}?keyword=${encodeURIComponent(keyword)}`;

                const response = await fetch(searchUrl, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
                });

                if (response.ok) {
                    const data = await response.json();
                    hasilSearch.innerHTML = data.html;
                } else {
                    throw new Error("Server error");
                }
            } catch (error) {
                console.error(error);
                hasilSearch.innerHTML = '<div style="text-align:center; color:red;">Gagal memuat data pencarian.</div>';
            }
        } else {
            hasilSearch.style.display = 'none';
            hasilSearch.innerHTML = '';
            katalogAsli.style.display = 'block';
        }
    }

    // =========================================================
    // 2. FUNGSI MARQUEE (FIX ARAH KANAN & KIRI)
    // =========================================================
    document.querySelectorAll('.sc-marquee__track').forEach(track => {
        track.style.animation = 'none';
        track.style.display = 'flex';
        track.style.width = 'max-content';
    });

    let autoScrollIntervals = {};

    function startAutoScroll(marqueeId, direction) {
        const container = document.getElementById(marqueeId);
        if(!container) return;

        clearInterval(autoScrollIntervals[marqueeId]);

        // Jika jalan ke kanan, kita mulai dari tengah agar tidak mentok di detik pertama
        if (direction === 'right' && container.scrollLeft === 0) {
            container.scrollLeft = container.scrollWidth / 2;
        }

        autoScrollIntervals[marqueeId] = setInterval(() => {
            if (direction === 'left') {
                container.scrollLeft += 1; // Geser ke kiri
                // Jika mentok ujung kanan, reset ke awal
                if (container.scrollLeft >= (container.scrollWidth - container.clientWidth - 1)) {
                    container.scrollLeft = 0;
                }
            } else if (direction === 'right') {
                container.scrollLeft -= 1; // Geser ke kanan
                // Jika mentok ujung kiri, reset ke akhir
                if (container.scrollLeft <= 0) {
                    container.scrollLeft = container.scrollWidth / 2;
                }
            }
        }, 20); // Angka 20 adalah kecepatan, bisa kamu ubah kalau mau lebih lambat
    }

    function pauseMarquee(trackId) {
        const marqueeId = trackId.replace('track-', 'marquee-');
        clearInterval(autoScrollIntervals[marqueeId]);
    }

    function resumeMarquee(trackId) {
        const marqueeId = trackId.replace('track-', 'marquee-');
        // Kembalikan ke arah yang benar setelah di-pause
        if (marqueeId === 'marquee-cookies') {
            startAutoScroll(marqueeId, 'right');
        } else {
            startAutoScroll(marqueeId, 'left');
        }
    }

    function scrollMarquee(marqueeId, distance) {
        const container = document.getElementById(marqueeId);
        container.scrollBy({ left: distance, behavior: 'smooth' });
    }

    // 🚀 PERBAIKAN NO 1 & 2: Set arah spesifik untuk Cookies dan Hampers saat diload
    document.addEventListener("DOMContentLoaded", () => {
        startAutoScroll('marquee-cookies', 'right'); // Cookies jalan ke kanan
        startAutoScroll('marquee-hampers', 'left');  // Hampers jalan ke kiri
    });
</script>
@endpush
@endsection
