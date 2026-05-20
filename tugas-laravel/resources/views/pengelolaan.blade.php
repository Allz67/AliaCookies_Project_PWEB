@extends('layouts.app')

@section('title', 'Pengelolaan Stok')

@section('content')
<div class="page-wrapper">
    <div class="page-header">
        <div>
            <p class="greeting" id="greeting">Halo, Selamat Pagi 👋</p>
            <h1 class="page-title">Pengelolaan <span class="highlight">Stok Produk</span></h1>
            <p class="page-sub">Selamat datang, <strong>{{ auth()->user()->name }}</strong>. Kelola stok produk Alia Cookies dengan mudah.</p>
        </div>
    </div>

    <div class="stats-grid stats-3">
        <div class="stat-card stat-green">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
            </div>
            <div class="stat-info">
                <span class="stat-label">Total Stok</span>
                <span class="stat-value">{{ $stokStats['total'] }} unit</span>
                <span class="stat-change">Semua produk</span>
            </div>
        </div>
        <div class="stat-card stat-rose">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
            <div class="stat-info">
                <span class="stat-label">Stok Menipis</span>
                <span class="stat-value">{{ $stokStats['menipis'] }} produk</span>
                <span class="stat-change warn">Perlu restock</span>
            </div>
        </div>
        <div class="stat-card stat-mocha">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            </div>
            <div class="stat-info">
                <span class="stat-label">Stok Habis</span>
                <span class="stat-value">{{ $stokStats['habis'] }} produk</span>
                <span class="stat-change danger">Segera restock!</span>
            </div>
        </div>
    </div>

    <div class="section-grid">
        <div class="card chart-card">
            <div class="card-header">
                <h2 class="card-title">Perbandingan Stok: Cookies vs Hampers</h2>
            </div>
            <div class="chart-container chart-doughnut">
                <canvas id="stokChart"></canvas>
            </div>
            <div class="chart-legend">
                <div class="legend-item"><span class="legend-dot" style="background:#a77761"></span> Cookies ({{ $chartStok['cookies'] }} unit)</div>
                <div class="legend-item"><span class="legend-dot" style="background:#c9a7b2"></span> Hampers ({{ $chartStok['hampers'] }} unit)</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Status Visual Stok</h2>
            </div>
            <div class="stok-bars">
                {{-- Menggunakan $products hasil paginate --}}
                @foreach($products as $p)
                <div class="stok-bar-item">
                    <div class="stok-bar-header">
                        <span class="stok-bar-name">{{ $p->nama }}</span>
                        <span class="stok-bar-val">{{ $p->stok }}</span>
                    </div>
                    <div class="stok-bar-track">
                        @php
                            $status = $p->stok > 10 ? 'tersedia' : ($p->stok > 0 ? 'menipis' : 'habis');
                        @endphp
                        <div class="stok-bar-fill stok-fill-{{ $status }}" style="width: {{ min(($p->stok/50)*100, 100) }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div></div>

        <div style="display: flex; gap: 10px;">
            <a href="{{ route('product.trashed') }}" class="btn-admin-cancel" style="padding: 10px 20px; font-size: 0.75rem; min-width: auto; height: auto; display: flex; align-items: center; gap: 8px; text-decoration: none;">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 8H3V6a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v2zM5 8v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8M10 12h4"></path></svg>
                LIHAT ARSIP
            </a>

            <a href="{{ route('product.create') }}" class="btn-admin-save" style="padding: 10px 20px; font-size: 0.75rem; min-width: auto; height: auto; letter-spacing: 1px; display: flex; align-items: center; gap: 8px; text-decoration: none;">
                <svg viewBox="0 0 24 24" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                TAMBAH PRODUK
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-controls">
                <div class="search-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" id="searchProduct" placeholder="Cari produk..." onkeyup="doProductSearch(this.value)">
                </div>
                <select onchange="filterTableByStatus('produkTable', 2, this.value)" class="filter-select">
                    <option value="">Semua Kategori</option>
                    <option value="Cookies">Cookies</option>
                    <option value="Hampers">Hampers</option>
                </select>
                <select onchange="filterTableByStatus('produkTable', 6, this.value)" class="filter-select">
                    <option value="">Semua Status</option>
                    <option value="Tersedia">Tersedia</option>
                    <option value="Menipis">Menipis</option>
                    <option value="Habis">Habis</option>
                </select>
            </div>
        </div>

        <div id="product-table-container">
            @include('partials.product_table')
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const ctx2 = document.getElementById('stokChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Cookies', 'Hampers'],
            datasets: [{
                data: [{{ $chartStok['cookies'] }}, {{ $chartStok['hampers'] }}],
                backgroundColor: ['rgba(167,119,97,0.85)', 'rgba(201,167,178,0.85)'],
                borderColor: ['#a77761', '#c9a7b2'],
                borderWidth: 2,
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.label}: ${ctx.parsed} unit`
                    }
                }
            }
        }
    });
    // 1. Fungsi Live Search AJAX untuk Produk
    async function doProductSearch(keyword) {
        const container = document.getElementById('product-table-container');
        try {
            // Kunci AJAX: Arahkan URL fetch langsung ke rute /pengelolaan secara tertulis
            const response = await fetch(`/pengelolaan?keyword=${encodeURIComponent(keyword)}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const html = await response.text();
            container.innerHTML = html;
        } catch (error) {
            console.error("Gagal memuat hasil pencarian produk:", error);
        }
    }

    // 2. Interseptor Pagination AJAX untuk Produk
    document.addEventListener('click', async function(e) {
        const paginationLink = e.target.closest('.pagination a');
        if (paginationLink) {
            // Cek apakah link pagination ini berada di dalam container produk
            const isProductPagination = e.target.closest('#product-table-container');

            if (isProductPagination) {
                e.preventDefault(); // Cegah halaman reload utuh

                const url = paginationLink.href;
                const keyword = document.getElementById('searchProduct').value;
                const container = document.getElementById('product-table-container');

                try {
                    const targetUrl = new URL(url);
                    if(keyword) targetUrl.searchParams.set('keyword', keyword);

                    const response = await fetch(targetUrl, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    const html = await response.text();
                    container.innerHTML = html;
                } catch (error) {
                    console.error("Gagal memuat halaman pagination produk:", error);
                }
            }
        }
    });

    function bukaModalDetail(button) {
        // 1. Ambil semua data dari elemen tombol yang diklik
        const kode = button.getAttribute('data-kode');
        const nama = button.getAttribute('data-nama');
        const kategori = button.getAttribute('data-kategori');
        const stok = button.getAttribute('data-stok');
        const satuan = button.getAttribute('data-satuan');
        const harga = button.getAttribute('data-harga');
        const status = button.getAttribute('data-status');
        const foto = button.getAttribute('data-foto');

        // 2. Suntikkan data tersebut ke ID elemen modal aslimu
        // (Silakan sesuaikan ID elemen modal di bawah ini dengan ID modal yang kamu buat di show.blade.php ya!)
        if(document.getElementById('modalKode')) document.getElementById('modalKode').innerText = kode;
        if(document.getElementById('modalNama')) document.getElementById('modalNama').innerText = nama;
        if(document.getElementById('modalKategori')) document.getElementById('modalKategori').innerText = kategori;
        if(document.getElementById('modalStok')) document.getElementById('modalStok').innerText = stok + ' ' + satuan;
        if(document.getElementById('modalHarga')) document.getElementById('modalHarga').innerText = 'Rp ' + harga;
        if(document.getElementById('modalStatus')) document.getElementById('modalStatus').innerText = status;

        const elementsFoto = document.getElementById('modalFoto');
        if(elementsFoto) elementsFoto.src = foto;

        // 3. Panggil fungsi atau tampilkan modal bawaan aplikasimu
        // Contoh jika menggunakan fungsi showDetail bawaanmu:
        if (typeof showDetail === "function") {
            showDetail(kode, nama, kategori, stok, satuan, harga, status, foto);
        } else {
            // Jika modalmu menggunakan class active/show biasa untuk muncul:
            const modalElement = document.getElementById('detailModal'); // sesuaikan ID modalmu
            if(modalElement) modalElement.classList.add('show');
        }
    }
</script>
@endpush
