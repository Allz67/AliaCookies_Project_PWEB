@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="page-wrapper">

    <div class="page-header">
        <div>
            <p class="greeting" id="greeting">Halo, Selamat Pagi 👋</p>
            <h1 class="page-title">Halo, <span class="highlight">{{ auth()->user()->name }}</span>!</h1>
            <p class="page-sub">Berikut ringkasan penjualan toko Alia Cookies hari ini.</p>

            <div id="weather-section" style="margin-top: 15px; display: inline-flex; align-items: center; background: rgba(255,255,255,0.15); padding: 6px 12px; border-radius: 30px; font-size: 0.8rem; color: white; border: 1px solid rgba(255,255,255,0.25);">
                <div id="loading-cuaca">Memuat info logistik Jember...</div>
                    <div id="konten-cuaca" style="display: none; align-items: center; gap: 8px;">
                        <span id="weather-icon">📍</span>
                        <strong>Jember:</strong> <span id="suhu-saat-ini">--</span>°C,
                        <span id="deskripsi-cuaca">--</span>
                        <span style="margin: 0 4px; opacity: 0.5;">|</span>
                        <span id="rekomendasi-toko" style="font-style: italic; opacity: 0.9;"></span>
                    </div>
                </div>
            </div>
        <div class="page-header-img">
            <img src="{{ asset('images/cookies.png') }}" alt="Cookies" onerror="this.style.display='none'">
        </div>
    </div>

    <div class="stats-grid">
        {{-- CARD PENDAPATAN --}}
        <div class="stat-card stat-green">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <div class="stat-info">
                <span class="stat-label">Total Pendapatan</span>
                <span class="stat-value">{{ $stats['pendapatan'] }}</span>
                <span class="stat-change" style="color: #666; opacity: 0.8;">Dari pesanan terbayar</span>
            </div>
        </div>

        {{-- CARD PESANAN --}}
        <div class="stat-card stat-rose">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            </div>
            <div class="stat-info">
                <span class="stat-label">Total Pesanan</span>
                <span class="stat-value">{{ $stats['pesanan'] }}</span>
                <span class="stat-change" style="color: #666; opacity: 0.8;">Transaksi berhasil</span>
            </div>
        </div>

        {{-- CARD PRODUK TERJUAL --}}
        <div class="stat-card stat-mocha">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
            </div>
            <div class="stat-info">
                <span class="stat-label">Produk Terjual</span>
                <span class="stat-value">{{ $stats['terjual'] }}</span>
                <span class="stat-change" style="color: #666; opacity: 0.8;">Keping/Toples terjual</span>
            </div>
        </div>

        {{-- CARD TOTAL PELANGGAN --}}
        <div class="stat-card stat-lavender">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div class="stat-info">
                <span class="stat-label">Total Pelanggan</span>
                <span class="stat-value">{{ $stats['pelanggan'] }}</span>
                <span class="stat-change" style="color: #666; opacity: 0.8;">Akun terdaftar</span>
            </div>
        </div>
    </div>


    <div class="section-grid">
        <div class="card chart-card">
            <div class="card-header">
                <h2 class="card-title">Grafik Penjualan Mingguan</h2>
                <div class="chart-filter">
                    <button class="chip active" onclick="switchChart('mingguan', this)">Mingguan</button>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <div class="card quick-info-card">
            <div class="card-header">
                <h2 class="card-title">Info Cepat</h2>
            </div>
            <div class="quick-list">
                <div class="quick-item">
                    <div class="quick-dot dot-green"></div>
                    <div>
                        <span class="quick-label">Pesanan Selesai</span>
                        <span class="quick-val">{{ $infoCepat['selesai'] }} transaksi</span>
                    </div>
                </div>
                <div class="quick-item">
                    <div class="quick-dot dot-yellow"></div>
                    <div>
                        <span class="quick-label">Sedang Diproses</span>
                        <span class="quick-val">{{ $infoCepat['proses'] }} transaksi</span>
                    </div>
                </div>
                <div class="quick-item">
                    <div class="quick-dot dot-red"></div>
                    <div>
                        <span class="quick-label">Dibatalkan</span>
                        <span class="quick-val">{{ $infoCepat['batal'] }} transaksi</span>
                    </div>
                </div>
                <div class="quick-item">
                    <div class="quick-dot dot-mocha"></div>
                    <div>
                        <span class="quick-label">Produk Terlaris</span>
                        <span class="quick-val">{{ $infoCepat['produk_terlaris'] }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card table-card">
        <div class="card-header">
            <h2 class="card-title">Transaksi Terakhir</h2>
            <div class="table-controls">
                <div class="search-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" id="searchTrx" placeholder="Cari transaksi..." onkeyup="searchTable('trxTable', this.value)">
                </div>
                <select id="filterStatus" onchange="filterTableByStatus('trxTable', 3, this.value)" class="filter-select">
                    <option value="">Semua Status</option>
                    <option value="Selesai">Selesai</option>
                    <option value="Proses">Proses</option>
                    <option value="Batal">Batal</option>
                </select>
            </div>
        </div>
        <div id="table-container">
            @include('partials.transaction_table')
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    async function ambilDataCuaca() {
    const loading = document.getElementById('loading-cuaca');
    const konten = document.getElementById('konten-cuaca');

    try {
        // Ambil data API publik untuk Jember dengan format JSON (j1)
        const response = await fetch('https://wttr.in/Jember?format=j1');
        if (!response.ok) throw new Error('Gagal memuat API');

        const data = await response.json();

        // Ambil data suhu dan deskripsi cuaca
        const suhu = data.current_condition[0].temp_C;
        const deskripsi = data.current_condition[0].weatherDesc[0].value;

        // Tulis ke elemen HTML
        document.getElementById('suhu-saat-ini').innerText = suhu;
        document.getElementById('deskripsi-cuaca').innerText = deskripsi;

        // Logika bisnis kemasan kue Alia Cookies berdasarkan cuaca
        const rekomendasi = document.getElementById('rekomendasi-toko');
        const descLower = deskripsi.toLowerCase();

        if (descLower.includes('rain') || descLower.includes('shower') || descLower.includes('drizzle')) {
            rekomendasi.innerText = "Packing ekstra plastik untuk pesanan, hari ini hujan.";
            if(document.getElementById('weather-icon')) document.getElementById('weather-icon').innerText = "🌧️";
        } else {
            rekomendasi.innerText = "Aman untuk pengiriman pesanan.";
            if(document.getElementById('weather-icon')) document.getElementById('weather-icon').innerText = "☀️";
        }

        // Hilangkan loading indicator dan munculkan data asli
        loading.style.display = 'none';
       konten.style.display = 'flex';

    } catch (error) {
        console.error(error);
        loading.innerText = "❌ Gagal memuat info cuaca";
    }
    }

// Jalankan fungsi otomatis saat halaman selesai dimuat
    document.addEventListener('DOMContentLoaded', ambilDataCuaca);
    const grafikLabels = @json($grafik['labels']);
    const grafikData   = @json($grafik['data']);

    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: grafikLabels, // Langsung memuat label Senin-Minggu
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: grafikData, // Langsung memuat data mingguan
                backgroundColor: 'rgba(167, 119, 97, 0.18)',
                borderColor: '#a77761',
                borderWidth: 2,
                borderRadius: 10,
                borderSkipped: false,
                tension: 0.4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(167,119,97,0.08)' },
                    ticks: {
                        font: { family: 'DM Sans', size: 11 },
                        color: '#9c8275',
                        callback: v => 'Rp ' + (v/1000) + 'K'
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'DM Sans', size: 11 }, color: '#9c8275' }
                }
            }
        }
    });

    // Fungsi Utama Live Search AJAX
    async function doLiveSearch(keyword) {
        const container = document.getElementById('table-container');
        try {
            const response = await fetch(`/dashboard?keyword=${encodeURIComponent(keyword)}`, {
                headers: { 'X-Requested-With': 'XMLHttpRequest' }
            });
            const html = await response.text();
            container.innerHTML = html;
        } catch (error) {
            console.error("Gagal memuat hasil pencarian Live Search:", error);
        }
    }

    // Interseptor Tombol Pagination Laravel agar Tidak Reload Halaman
    document.addEventListener('click', async function(e) {
        const paginationLink = e.target.closest('.pagination a');
        if (paginationLink) {
            e.preventDefault();

            const url = paginationLink.href;
            const keyword = document.getElementById('searchTrx').value;
            const container = document.getElementById('table-container');

            try {
                const targetUrl = new URL(url);
                if(keyword) targetUrl.searchParams.set('keyword', keyword);

                const response = await fetch(targetUrl, {
                    headers: { 'X-Requested-With': 'XMLHttpRequest' }
                });
                const html = await response.text();
                container.innerHTML = html;
            } catch (error) {
                console.error("Gagal memuat halaman pagination AJAX:", error);
            }
        }
    });
</script>
@endpush
